<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once 'inc-challan-return.php';
challanReturnEnsureTables($conn);

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Edit-Challan";
$sellId = (int) ($_GET['id'] ?? $_POST['sell_id'] ?? 0);

$sell = getRecord("SELECT * FROM tbl_rooftop_sell WHERE id='$sellId' AND Status=1 LIMIT 1");
if (!$sell) {
    echo "<script>alert('Challan not found.');window.location.href='return-challans.php';</script>";
    exit;
}
if ((int) ($sell['ReturnStatus'] ?? 0) !== 1) {
    echo "<script>alert('Only returned challans can be edited.');window.location.href='return-challans.php';</script>";
    exit;
}

$returnRec = challanReturnGetRecord($conn, $sellId);
$existingItems = challanReturnGetSellItems($conn, $sellId);
$existingBulk = [];
$existingSerialIds = [];
foreach ($existingItems as $item) {
    if (challanReturnIsSerialItem($item)) {
        $existingSerialIds[] = (int) $item['ProductId'];
    } else {
        $existingBulk[(int) $item['ProductId']] = $item;
    }
}

unset($_SESSION['cart_item']);
foreach ($existingSerialIds as $distId) {
    $product = getRecord("SELECT * FROM tbl_rooftop_distibute_item_details2 WHERE id='" . (int) $distId . "' LIMIT 1");
    if ($product && !empty($product['code'])) {
        $_SESSION['cart_item'][$product['code']] = [
            'code' => $product['code'],
            'id' => $distId,
            'ProductName' => $product['ProductName'],
            'Unit' => $product['Unit'],
            'SerialNo' => $product['SerialNo'],
            'ModelNo' => $product['ModelNo'],
        ];
    }
}

if (isset($_POST['submit'])) {
    $remarks = trim((string) ($_POST['edit_remarks'] ?? ''));
    $bulkItems = [];
    $number = count($_POST['CheckId'] ?? []);
    for ($i = 0; $i < $number; $i++) {
        $bulkItems[] = [
            'CheckId' => (int) ($_POST['CheckId'][$i] ?? 0),
            'ProductId' => (int) ($_POST['ProductId'][$i] ?? 0),
            'ProductName' => (string) ($_POST['ProductName'][$i] ?? ''),
            'ModelNo' => (string) ($_POST['ModelNo'][$i] ?? ''),
            'SerialNo' => (string) ($_POST['SerialNo'][$i] ?? 'N/A'),
            'Purity' => (string) ($_POST['Purity'][$i] ?? ''),
            'Qty' => (float) ($_POST['Qty'][$i] ?? 0),
        ];
    }
    $serialDistIds = [];
    if (!empty($_SESSION['cart_item'])) {
        foreach ($_SESSION['cart_item'] as $cartItem) {
            if (!empty($cartItem['id'])) {
                $serialDistIds[] = (int) $cartItem['id'];
            }
        }
    }
    $result = challanReturnEditProcess($conn, $sellId, $remarks, $user_id, $bulkItems, $serialDistIds, (int) $sell['CustId'], (int) $sell['BranchId'], (string) $sell['InvoiceDate'], (string) ($sell['Narration'] ?? 'Challan edited after return'));
    if ($result['success']) {
        unset($_SESSION['cart_item']);
        echo "<script>alert('" . addslashes($result['message']) . "');window.location.href='view-sells.php';</script>";
        exit;
    }
    $editError = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?></title>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/datatables/datatables.css">
</head>
<body>
    <div class="page-loader"><div class="bg-primary"></div></div>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <?php include_once 'header.php'; ?>
            <div class="layout-container">
                <?php include_once 'top_header.php'; ?>
                <div class="layout-content">
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">
                            Edit Challan — <?php echo htmlspecialchars($sell['InvoiceNo']); ?>
                            <span style="float:right;"><a href="view-return-challan.php?id=<?php echo $sellId; ?>" class="btn btn-secondary btn-sm">Back</a></span>
                        </h5>
                        <?php if (!empty($editError)) { ?><div class="alert alert-danger"><?php echo htmlspecialchars($editError); ?></div><?php } ?>
                        <div class="card mb-4"><div class="card-body">
                            <form method="post" autocomplete="off">
                                <input type="hidden" name="sell_id" value="<?php echo $sellId; ?>">
                                <div class="form-row mb-3">
                                    <div class="col-md-3"><strong>DM No:</strong> <?php echo htmlspecialchars($sell['InvoiceNo']); ?></div>
                                    <div class="col-md-3"><strong>Customer:</strong> <?php echo htmlspecialchars($sell['CustName']); ?></div>
                                    <div class="col-md-3"><strong>Store:</strong> <?php $br = getRecord("SELECT Name FROM tbl_rooftop_branch WHERE id='" . (int) $sell['BranchId'] . "'"); echo htmlspecialchars($br['Name'] ?? ''); ?></div>
                                    <div class="col-md-3"><strong>Return Date:</strong> <?php echo date('d/m/Y', strtotime($returnRec['return_date'])); ?></div>
                                </div>
                                <label class="form-label" style="font-size:18px;color:#0dc30d;">Product Details</label>
                                <div class="table-responsive mb-4">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead><tr><th>#</th><th>Product</th><th>Stock Qty</th><th>Qty</th><th>Unit</th></tr></thead>
                                        <tbody>
                                            <?php
                                            $products = getList("SELECT * FROM tbl_rooftop_products WHERE Roll='0'");
                                            foreach ($products as $result) {
                                                $row11 = getRecord("SELECT SUM(Qty) AS CrQty FROM tbl_rooftop_distibute_item_details2 WHERE ProductId='" . (int) $result['id'] . "'");
                                                $rowDr = getRecord("SELECT COALESCE(SUM(Qty), 0) AS DrQty FROM tbl_rooftop_stocks WHERE CrDr='dr' AND ProductId='" . (int) $result['id'] . "'");
                                                $balQty = (float) ($row11['CrQty'] ?? 0) - (float) ($rowDr['DrQty'] ?? 0);
                                                if ($balQty <= 0 && !isset($existingBulk[(int) $result['id']])) {
                                                    continue;
                                                }
                                                $prodId = (int) $result['id'];
                                                $wasOnChallan = isset($existingBulk[$prodId]);
                                                $checkval = $wasOnChallan ? 1 : 0;
                                                $checkstatus = $wasOnChallan ? 'checked' : '';
                                                $qtyVal = $wasOnChallan ? (float) $existingBulk[$prodId]['Qty'] : 1;
                                                $unitVal = $wasOnChallan ? ($existingBulk[$prodId]['Purity'] ?? $result['Unit']) : $result['Unit'];
                                            ?>
                                                <tr>
                                                    <td>
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="Check_Id<?php echo $prodId; ?>" class="custom-control-input is-valid" onclick="featured2(<?php echo $prodId; ?>)" <?php echo $checkstatus; ?>>
                                                            <span class="custom-control-label">&nbsp;</span>
                                                        </label>
                                                    </td>
                                                    <input type="hidden" value="<?php echo $checkval; ?>" name="CheckId[]" id="CheckId<?php echo $prodId; ?>">
                                                    <td><?php echo htmlspecialchars($result['ProductName']); ?></td>
                                                    <input type="hidden" name="ProductId[]" value="<?php echo $prodId; ?>">
                                                    <input type="hidden" name="ProductName[]" value="<?php echo htmlspecialchars($result['ProductName'], ENT_QUOTES); ?>">
                                                    <input type="hidden" name="SerialNo[]" value="N/A">
                                                    <input type="hidden" name="ModelNo[]" value="<?php echo htmlspecialchars($result['Model_No'] ?? '', ENT_QUOTES); ?>">
                                                    <td><input type="text" class="form-control" value="<?php echo $balQty; ?>" readonly style="width:100px;"></td>
                                                    <td><input type="number" name="Qty[]" class="form-control" value="<?php echo $qtyVal; ?>" min="0" style="width:100px;"></td>
                                                    <td><input type="text" name="Purity[]" class="form-control" value="<?php echo htmlspecialchars($unitVal, ENT_QUOTES); ?>" style="width:100px;"></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0" style="font-size:18px;color:#0dc30d;">Serial No Products</label>
                                    <a href="#" class="small text-danger" data-toggle="modal" data-target="#cartModal" id="viewCartBtn">View Selected Items</a>
                                </div>
                                <table id="empTable" class="table table-striped table-bordered mb-3" width="100%">
                                    <thead><tr><th>#</th><th>Product</th><th>Serial No</th></tr></thead>
                                </table>
                                <div class="form-group">
                                    <label class="form-label">Edit Remarks</label>
                                    <textarea name="edit_remarks" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Update challan items and assign stock again?');">Update Challan</button>
                                <input type="hidden" id="Roll" value="<?php echo $Roll; ?>">
                            </form>
                        </div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cartModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Selected Serial Items</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body" id="cartContent"></div>
    </div></div></div>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
    <script>
        function featured2(id) { $('#CheckId' + id).val($('#Check_Id' + id).prop('checked') ? 1 : 0); }
        function saveCart(id) { $.post('assign-serial-no-challan-session.php', { action: 'saveCart', quantity: 1, id: id }); }
        function delete_prod(id) { $.post('assign-serial-no-challan-session.php', { action: 'delete_shop_prod', id: id }); }
        function featured(id) {
            if ($('#Check_Id' + id).prop('checked')) { $('#CheckId' + id).val(1); saveCart(id); }
            else { $('#CheckId' + id).val(0); delete_prod(id); }
        }
        $(function() {
            $('#empTable').DataTable({
                processing: true, serverSide: true, serverMethod: 'post',
                ajax: { url: 'pagination/serial-no-products.php', method: 'POST', data: { Roll: $('#Roll').val() } },
                columns: [{ data: 'id' }, { data: 'Product' }, { data: 'SerialNo' }],
                pageLength: 10, destroy: true, scrollX: true
            });
            $('#viewCartBtn').on('click', function(e) {
                e.preventDefault();
                $.get('view_cart.php', function(r) { $('#cartContent').html(r); });
            });
        });
    </script>
</body>
</html>
