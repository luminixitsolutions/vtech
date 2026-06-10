<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once 'inc-challan-return.php';
challanReturnEnsureTables($conn);

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Return-Challans";
$sellId = (int) ($_GET['id'] ?? 0);

$returnRec = challanReturnGetRecord($conn, $sellId);
if (!$returnRec) {
    echo "<script>alert('Return challan record not found.');window.location.href='return-challans.php';</script>";
    exit;
}

$items = getList("SELECT * FROM challan_return_items WHERE return_id='" . (int) $returnRec['id'] . "' ORDER BY id ASC");
$logs = getList("SELECT cel.*, tu.Fname AS PerformedByName FROM challan_edit_log cel LEFT JOIN tbl_users tu ON tu.id=cel.performed_by WHERE cel.sell_id='$sellId' ORDER BY cel.id DESC");
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">
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
                            View Return Challan — <?php echo htmlspecialchars($returnRec['InvoiceNo'] ?? ''); ?>
                            <span style="float:right;">
                                <a href="return-challans.php" class="btn btn-secondary btn-sm">Back</a>
                                <?php
                                $sellRow = getRecord("SELECT ReturnStatus FROM tbl_sell WHERE id='$sellId' LIMIT 1");
                                if ((int) ($sellRow['ReturnStatus'] ?? 0) === 1) { ?>
                                    <a href="edit-challan.php?id=<?php echo $sellId; ?>" class="btn btn-primary btn-sm">Edit Challan</a>
                                <?php } ?>
                            </span>
                        </h5>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3"><strong>Challan No:</strong> <?php echo htmlspecialchars($returnRec['InvoiceNo'] ?? ''); ?></div>
                                    <div class="col-md-3"><strong>Customer:</strong> <?php echo htmlspecialchars($returnRec['CustName'] ?? ''); ?></div>
                                    <div class="col-md-3"><strong>Return Date:</strong> <?php echo date('d/m/Y', strtotime($returnRec['return_date'])); ?></div>
                                    <div class="col-md-3"><strong>Returned By:</strong> <?php echo htmlspecialchars($returnRec['ReturnedByName'] ?? ''); ?></div>
                                    <div class="col-md-12 mt-2"><strong>Remarks:</strong> <?php echo nl2br(htmlspecialchars($returnRec['remarks'] ?? '')); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header"><strong>Returned Items</strong></div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Model No</th>
                                            <th>Serial No</th>
                                            <th>Qty</th>
                                            <th>Unit</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($items as $item) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo htmlspecialchars($item['product_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['model_no'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['serial_no'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['qty'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['unit'] ?? ''); ?></td>
                                                <td><?php echo ((int) ($item['prod_type'] ?? 0) === 1) ? 'Serial' : 'Bulk'; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if (!empty($logs)) { ?>
                        <div class="card mb-4">
                            <div class="card-header"><strong>Activity History</strong></div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Action</th>
                                            <th>Performed By</th>
                                            <th>Remarks</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j = 1; foreach ($logs as $log) { ?>
                                            <tr>
                                                <td><?php echo $j++; ?></td>
                                                <td><?php echo htmlspecialchars(ucfirst($log['action_type'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars($log['PerformedByName'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($log['remarks'] ?? ''); ?></td>
                                                <td><?php echo date('d/m/Y h:i A', strtotime($log['created_at'])); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
</body>
</html>
