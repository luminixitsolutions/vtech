<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
require_once __DIR__ . '/../../adminapp/inc-mobile-stock-data.php';

$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$BranchId = $row77['BranchId'];
$MulBranchId = $row77['MulBranchId'];
$MainPage = "Report";
$Page = "Stock-Report";

$clearSearch = isset($_GET['clear']);
$branchPost = '';
$productPost = 'all';
if (!$clearSearch) {
    if (isset($_POST['BranchId'])) {
        $branchPost = trim((string) $_POST['BranchId']);
    } elseif (isset($_REQUEST['BranchId'])) {
        $branchPost = trim((string) $_REQUEST['BranchId']);
    }
    if (isset($_POST['ProductId'])) {
        $productPost = trim((string) $_POST['ProductId']);
    } elseif (isset($_REQUEST['ProductId'])) {
        $productPost = trim((string) $_REQUEST['ProductId']);
    }
}
if ($productPost === '') {
    $productPost = 'all';
}

$report = null;
$displayRows = array();
$TotStock = 0.0;
$SellStock = 0.0;
$BalStock = 0.0;
$showBranchColumn = false;
$runReport = false;

if ($branchPost !== '') {
    $runReport = true;
} elseif ((int) $Roll === 1 || (int) $Roll === 7) {
    $branchPost = 'all';
    $runReport = true;
} elseif (!empty($BranchId)) {
    $branchPost = (string) (int) $BranchId;
    $runReport = true;
}

if ($runReport) {
    set_time_limit(120);
    $filters = array();
    if ($branchPost === 'all' && ((int) $Roll === 1 || (int) $Roll === 7)) {
        $filters['all_branches'] = true;
    } elseif ($branchPost !== '' && $branchPost !== 'all') {
        $filters['branch_id'] = (int) $branchPost;
    }
    if ($productPost !== '' && $productPost !== 'all') {
        $filters['product_id'] = (int) $productPost;
    }

    $report = mobileStockGetStoreReportData($filters);
    $showBranchColumn = !empty($report['all_branches']);

    foreach ($report['rows'] as $row) {
        $balance = (float) $row['balance_qty'];
        if ($balance <= 0.0001) {
            continue;
        }
        $displayRows[] = $row;
        $TotStock += (float) $row['inward_qty'];
        $SellStock += (float) $row['outward_qty'];
        $BalStock += $balance;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Outstanding Stock Report</title>
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


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Outstanding Stock Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

<div class="form-group col-md-2">
<label class="form-label"> Branch<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
  <?php 
 if($Roll == 1 || $Roll == 7){?>
<option <?php if($branchPost === 'all') { ?> selected <?php } ?> value="all">All</option>
 <?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' ORDER BY Name ASC";
}
else{
  $branchSql = trim((string) $MulBranchId) !== '' ? "id IN($MulBranchId)" : "id='".(int)$BranchId."'";
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND $branchSql ORDER BY Name ASC";
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($branchPost !== '' && $branchPost !== 'all' && (string)$branchPost === (string)$result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-5">
                                            <label class="form-label">Product</label>
                                            <select class="select2-demo form-control" name="ProductId" id="ProductId">
                                                <option <?php if($productPost === 'all') { ?> selected <?php } ?> value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1' ORDER BY ProductName ASC";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($productPost !== 'all' && (string)$productPost === (string)$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if($runReport && !$clearSearch) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?clear=1" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>

<?php if ($runReport) { ?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <?php if ($showBranchColumn) { ?><th>Branch</th><?php } ?>
                <th>Product Name</th>
                <th>Total Stock</th>
                <th>Sell</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($displayRows as $row) {
                $bid = (int) $row['BranchId'];
                $pid = (int) $row['ProductId'];
                $totStock = (float) $row['inward_qty'];
                $sellStock = (float) $row['outward_qty'];
                $balance = (float) $row['balance_qty'];

                $qParamsDetail = array(
                    'BranchId' => $bid,
                    'ProductId' => $pid,
                );
                $stockHref = 'stock-report2-inward-detail.php?' . http_build_query($qParamsDetail);
                $sellHref = 'stock-report2-outward-detail.php?' . http_build_query($qParamsDetail);
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <?php if ($showBranchColumn) { ?>
               <td><?php echo htmlspecialchars((string) ($row['Branch'] ?? '')); ?></td>
               <?php } ?>
               <td><?php echo htmlspecialchars((string) ($row['ProductName'] ?? '')); ?></td>
               <td><?php if ($totStock > 0) { ?><a href="<?php echo htmlspecialchars($stockHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars(mobileStockFormatQty($totStock)); ?></a><?php } else { echo '0'; } ?></td>
               <td><?php if ($sellStock > 0) { ?><a href="<?php echo htmlspecialchars($sellHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars(mobileStockFormatQty($sellStock)); ?></a><?php } else { echo '0'; } ?></td>
               <td><?php echo htmlspecialchars(mobileStockFormatQty($balance)); ?></td>
            </tr>
           <?php
                $i++;
            }
            if (empty($displayRows)) {
                $colspan = $showBranchColumn ? 6 : 5;
                ?>
            <tr>
                <td colspan="<?php echo $colspan; ?>" class="text-center text-muted">No outstanding stock found for selected filters.</td>
            </tr>
                <?php
            }
            ?>

           <tr>
               <td><?php echo $i; ?></td>
               <?php if ($showBranchColumn) { ?><td></td><?php } ?>
               <th>Total</th>
               <th><?php echo htmlspecialchars(mobileStockFormatQty($TotStock)); ?></th>
               <th><?php echo htmlspecialchars(mobileStockFormatQty($SellStock)); ?></th>
               <th><?php echo htmlspecialchars(mobileStockFormatQty($BalStock)); ?></th>
           </tr>
        </tbody>
    </table>
</div>
<?php } else { ?>
<p class="text-muted mb-0 px-2">Select a branch and click <strong>Search</strong> to view outstanding stock.</p>
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
    if (!$('#example').length) {
        return;
    }
    $('#example').DataTable({
        "scrollX": true,
        "pageLength":500,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            'excelHtml5'
        ]
    });
    
});
</script>
</body>
</html>
