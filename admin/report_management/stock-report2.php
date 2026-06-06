<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Stock-Report2";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Store Inward & Outward Report</title>
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
<h4 class="font-weight-bold py-3 mb-0">Store Inward &amp; Outward Report</h4>

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

 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="all">All</option>
 <?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id IN($MulBranchId)";
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-4">
                                            <label class="form-label">Product</label>
                                            <select class="select2-demo form-control" name="ProductId" id="ProductId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['ProductId']==$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


 <div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div> 
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
   <?php if(isset($_POST['Search'])) {
            require_once __DIR__ . '/../../adminapp/inc-mobile-stock-data.php';
            set_time_limit(120);

            $FromDate = isset($_POST['FromDate']) ? trim((string) $_POST['FromDate']) : '';
            $ToDate = isset($_POST['ToDate']) ? trim((string) $_POST['ToDate']) : '';
            $branchPost = isset($_POST['BranchId']) ? trim((string) $_POST['BranchId']) : '';
            $productPost = isset($_POST['ProductId']) ? trim((string) $_POST['ProductId']) : 'all';

            $filters = array(
                'from_date' => $FromDate,
                'to_date' => $ToDate,
            );
            if ($branchPost === 'all' && ((int) $Roll === 1 || (int) $Roll === 7)) {
                $filters['all_branches'] = true;
            } elseif ($branchPost !== '' && $branchPost !== 'all') {
                $filters['branch_id'] = (int) $branchPost;
            }
            if ($productPost !== '' && $productPost !== 'all') {
                $filters['product_id'] = (int) $productPost;
            }

            $report = mobileStockGetStoreReportData($filters);
            $TotCreditStock = (float) $report['tot_inward'];
            $TotDebitStock = (float) $report['tot_outward'];
            $BalStock = (float) $report['tot_balance'];
            $showBranchColumn = !empty($report['all_branches']);
?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <?php if ($showBranchColumn) { ?><th>Branch</th><?php } ?>
                <th>Product Name</th>
                <th>Inward</th>
                <th>Outward</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($report['rows'] as $row) {
                $bid = (int) $row['BranchId'];
                $pid = (int) $row['ProductId'];
                $inward = (float) $row['inward_qty'];
                $outward = (float) $row['outward_qty'];
                $balance = (float) $row['balance_qty'];

                $qBase = array('BranchId' => $bid, 'ProductId' => $pid);
                if ($FromDate !== '') {
                    $qBase['FromDate'] = $FromDate;
                }
                if ($ToDate !== '') {
                    $qBase['ToDate'] = $ToDate;
                }
                $inwardHref = 'stock-report2-inward-detail.php?' . http_build_query($qBase);
                $outwardHref = 'stock-report2-outward-detail.php?' . http_build_query($qBase);
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <?php if ($showBranchColumn) { ?>
               <td><?php echo htmlspecialchars((string) ($row['Branch'] ?? '')); ?></td>
               <?php } ?>
               <td><?php echo htmlspecialchars((string) ($row['ProductName'] ?? '')); ?></td>
               <td><?php if ($inward > 0) { ?><a href="<?php echo htmlspecialchars($inwardHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars(mobileStockFormatQty($inward)); ?></a><?php } else { echo '0'; } ?></td>
               <td><?php if ($outward > 0) { ?><a href="<?php echo htmlspecialchars($outwardHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars(mobileStockFormatQty($outward)); ?></a><?php } else { echo '0'; } ?></td>
               <td><?php echo htmlspecialchars(mobileStockFormatQty($balance)); ?></td>
            </tr>
           <?php
                $i++;
            }
            if (empty($report['rows'])) {
                $colspan = $showBranchColumn ? 6 : 5;
                ?>
            <tr>
                <td colspan="<?php echo $colspan; ?>" class="text-center text-muted">No stock records found for selected filters.</td>
            </tr>
                <?php
            }
            ?>
           
           <tr>
               <td><?php echo $i; ?></td>
               <?php if ($showBranchColumn) { ?><td></td><?php } ?>
               <th>Total</th>
               <th><?php echo htmlspecialchars(mobileStockFormatQty($TotCreditStock)); ?></th>
               <th><?php echo htmlspecialchars(mobileStockFormatQty($TotDebitStock)); ?></th>
               <th><?php echo htmlspecialchars(mobileStockFormatQty($BalStock)); ?></th>
           </tr>
        </tbody>
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
    $('#example').DataTable({
        "scrollX": true,
        "pageLength":500,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            'excelHtml5'
        ]
    });

 $(document).on("change", "#ModelNo", function(event) {
            var val = this.value;
            var action = "getModelNo";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
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
    
});
</script>
</body>
</html>
