<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-dispatch-officer-stock.php';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = (int) ($row77['Roll'] ?? 0);
$BranchId = (int) ($row77['BranchId'] ?? 0);
$MainPage = "Report";
$Page = "Dispatch-Stock-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php
if ($_REQUEST["action"] == "delete") {
    $id = $_REQUEST["id"];
    $sql11 = "DELETE FROM tbl_distibute_items WHERE id = '$id'";
    $conn->query($sql11);
    $sql11 = "DELETE FROM tbl_distibute_item_details WHERE DistId = '$id'";
    $conn->query($sql11);
    ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-distribute-item-store.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dispatch Officier Stock Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">

                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

  <div class="form-group col-md-2">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<?php
if ($Roll == 1 || $Roll == 7) { ?>
    <option selected="" value="" disabled>Select</option>
<?php }
if ($Roll == 1 || $Roll == 7) {
    $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
} else {
    $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
}

  $row12 = getList($sql12);
  foreach ($row12 as $result) {
      ?>
  <option <?php if (isset($_POST['BranchId']) && (string) $_POST['BranchId'] === (string) $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId" required>
    <?php
    $branchForOfficers = 0;
    if (isset($_POST['BranchId']) && $_POST['BranchId'] !== '') {
        $branchForOfficers = (int) $_POST['BranchId'];
    } elseif ($Roll != 1 && $Roll != 7) {
        $branchForOfficers = $BranchId;
    }
    $dispatchUsers = dispatch_officer_dispatch_users_for_branch($conn, $Roll, $BranchId, $branchForOfficers);
    if (count($dispatchUsers) === 0) {
        ?>
        <option value="" selected disabled><?php echo ($Roll == 1 || $Roll == 7) && $branchForOfficers < 1 ? 'Select store first' : 'No dispatch officer for this store'; ?></option>
        <?php
    } else {
        foreach ($dispatchUsers as $du) {
            ?>
        <option <?php if (isset($_POST['StoreExeId']) && (string) $_POST['StoreExeId'] === (string) $du['id']) { ?> selected <?php } ?> value="<?php echo (int) $du['id']; ?>">
        <?php echo htmlspecialchars($du['Fname']); ?></option>
            <?php
        }
    }
    ?>
</select>

<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo isset($_POST['FromDate']) ? htmlspecialchars($_POST['FromDate']) : ''; ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo isset($_POST['ToDate']) ? htmlspecialchars($_POST['ToDate']) : ''; ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<div class="form-group col-md-2" style="padding-top:20px;">
<button type="button" class="btn btn-outline-secondary btn-block" id="btnOpenTab" title="Same report in a new browser tab">Open in new tab</button>
</div>
<?php if (isset($_POST['Search'])) { ?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
   <?php
    $stockData = ['rows' => [], 'totCredit' => 0, 'totDebit' => 0];
    if (isset($_POST['Search']) && $_POST['Search'] === 'Search') {
        $pb = isset($_POST['BranchId']) ? (int) $_POST['BranchId'] : 0;
        $pe = isset($_POST['StoreExeId']) ? (int) $_POST['StoreExeId'] : 0;
        if (dispatch_officer_stock_allowed($Roll, $BranchId, $pb, $pe)) {
            $stockData = dispatch_officer_stock_compute_rows(
                $conn,
                $pb,
                $pe,
                isset($_POST['FromDate']) ? (string) $_POST['FromDate'] : '',
                isset($_POST['ToDate']) ? (string) $_POST['ToDate'] : ''
            );
        }
    }
    ?>
   <?php if (isset($_POST['Search']) && $_POST['Search'] === 'Search') { ?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
             <th>Product Name</th>
                <th>Credit Qty</th>
                   <th>Debit Qty</th>
                <th>Balance Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $BranchIdPost = (int) $_POST['BranchId'];
            $StoreExeIdPost = (int) $_POST['StoreExeId'];
            $FromDatePost = isset($_POST['FromDate']) ? (string) $_POST['FromDate'] : '';
            $ToDatePost = isset($_POST['ToDate']) ? (string) $_POST['ToDate'] : '';
            foreach ($stockData['rows'] as $row) {
                $pid = (int) $row['ProductId'];
                $creditQty = $row['CreditQty'];
                $debitQty = $row['DebitQty'];
                $balanceQty = $row['BalanceQty'];
                $creditUrl = 'dispatch-officer-stock-qty-detail.php?' . http_build_query([
                    'type' => 'credit',
                    'BranchId' => $BranchIdPost,
                    'StoreExeId' => $StoreExeIdPost,
                    'ProductId' => $pid,
                ]);
                $debitUrl = 'dispatch-officer-stock-qty-detail.php?' . http_build_query([
                    'type' => 'debit',
                    'BranchId' => $BranchIdPost,
                    'StoreExeId' => $StoreExeIdPost,
                    'ProductId' => $pid,
                    'FromDate' => $FromDatePost,
                    'ToDate' => $ToDatePost,
                ]);
                ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo htmlspecialchars((string) $row['ProductName']); ?></td>
            <td><?php if ($creditQty > 0) { ?>
                <a href="<?php echo htmlspecialchars($creditUrl); ?>" target="_blank" rel="noopener" class="text-primary font-weight-bold"><?php echo htmlspecialchars((string) $creditQty); ?></a>
                <?php } else { echo '0'; } ?></td>
            <td><?php if ($debitQty > 0) { ?>
                <a href="<?php echo htmlspecialchars($debitUrl); ?>" target="_blank" rel="noopener" class="text-primary font-weight-bold"><?php echo htmlspecialchars((string) $debitQty); ?></a>
                <?php } else { echo htmlspecialchars((string) $debitQty); } ?></td>
            <td><?php echo htmlspecialchars((string) $balanceQty); ?></td>
             </tr>
           <?php $i++;
            } ?>
        </tbody>
        <tfoot>
            <tr>
               <th></th>
                 <th>Total</th>
            <th><?php echo htmlspecialchars((string) $stockData['totCredit']); ?></th>
            <th><?php echo htmlspecialchars((string) $stockData['totDebit']); ?></th>
            <th><?php echo htmlspecialchars((string) ($stockData['totCredit'] - $stockData['totDebit'])); ?></th>
           </tr>
        </tfoot>
    </table>
</div>
<?php } ?>
</div>
</div>


<?php include_once '../footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once '../footer_script.php'; ?>

<script type="text/javascript">

    $(document).ready(function() {
    <?php if (isset($_POST['Search']) && $_POST['Search'] === 'Search') { ?>
    $('#example').DataTable({
        "scrollX": true,
        "pageLength":1000,
         "order": [[ 0, "asc" ]],
       dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
    });
    <?php } ?>

 $(document).on("change", "#ModelNo", function(event) {
            var val = this.value;
            var action = "getModelNo";
            $.ajax({
                url: "../ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#ProductNo').html(data);

                }
            });

        });

    function refreshDispatchOfficerSelect(branchId) {
        if (!branchId) {
            $('#StoreExeId').html('<option value="" selected disabled>Select store first</option>');
            if ($('#StoreExeId').hasClass('select2-hidden-accessible')) {
                $('#StoreExeId').select2('destroy');
            }
            $('#StoreExeId').select2();
            return;
        }
        $.ajax({
            url: "../ajax_files/ajax_dropdown.php",
            method: "POST",
            data: { action: "getDispatchOfficersByBranch", id: branchId },
            success: function(data) {
                var $s = $('#StoreExeId');
                if ($s.hasClass('select2-hidden-accessible')) {
                    $s.select2('destroy');
                }
                $s.html(data);
                $s.select2();
            }
        });
    }

    $(document).on("change", "#BranchId", function() {
        refreshDispatchOfficerSelect(this.value);
    });

    $('#btnOpenTab').on('click', function() {
        var b = $('#BranchId').val();
        var e = $('#StoreExeId').val();
        var f = $('#FromDate').val() || '';
        var t = $('#ToDate').val() || '';
        if (!b || !e) {
            alert('Please select store and dispatch officer first.');
            return;
        }
        var url = 'dispatch-officer-stock-report-tab.php?Search=1&BranchId=' + encodeURIComponent(b) + '&StoreExeId=' + encodeURIComponent(e)
            + '&FromDate=' + encodeURIComponent(f) + '&ToDate=' + encodeURIComponent(t);
        window.open(url, '_blank');
    });

});
</script>
</body>
</html>
