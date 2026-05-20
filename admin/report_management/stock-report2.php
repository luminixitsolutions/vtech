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
<title><?php echo $Proj_Title; ?> | Stock Report</title>
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
<h4 class="font-weight-bold py-3 mb-0">Stock Report</h4>

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
            $FromDate = isset($_POST['FromDate']) ? trim((string) $_POST['FromDate']) : '';
            $ToDate = isset($_POST['ToDate']) ? trim((string) $_POST['ToDate']) : '';
            $dateSqlDist = '';
            $dateSqlStockCr = '';
            $dateSqlStockDr = '';
            if ($FromDate !== '') {
                $fd = mysqli_real_escape_string($conn, $FromDate);
                $dateSqlDist .= " AND CreatedDate>='$fd'";
                $dateSqlStockCr .= " AND CreatedDate>='$fd'";
                $dateSqlStockDr .= " AND CreatedDate>='$fd'";
            }
            if ($ToDate !== '') {
                $td = mysqli_real_escape_string($conn, $ToDate);
                $dateSqlDist .= " AND CreatedDate<='$td'";
                $dateSqlStockCr .= " AND CreatedDate<='$td'";
                $dateSqlStockDr .= " AND CreatedDate<='$td'";
            }
?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Branch</th>
                <th>Product Name</th>
                <th>Inward</th>
                <th>Outward</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $TotCreditStock = 0;
            $TotDebitStock = 0;
            $BalStock = 0;
            $sql = "SELECT p.ProductId, p.BranchId, tb.Name AS Branch, tp.ProductName AS Product_Name
                    FROM (
                        SELECT ProductId, BranchId FROM tbl_distibute_item_details WHERE 1 $dateSqlDist
                        UNION
                        SELECT ProductId, BranchId FROM tbl_stocks WHERE Status=1 AND CrDr='cr' $dateSqlStockCr
                        UNION
                        SELECT ProductId, BranchId FROM tbl_stocks WHERE Status=1 AND CrDr='dr' $dateSqlStockDr
                    ) p
                    INNER JOIN tbl_products tp ON p.ProductId=tp.id
                    LEFT JOIN tbl_branch tb ON p.BranchId=tb.id
                    WHERE tp.ProductName!=''";

            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId != 'all'){
                    $sql.= " AND p.BranchId='$BranchId'";
                }
            }
            
            if($_POST['ProductId']){
                $ProductId = $_POST['ProductId'];
                if($ProductId != 'all'){
                    $sql.= " AND p.ProductId='$ProductId'";
                }
            }
            $sql.=" GROUP BY p.ProductId, p.BranchId ORDER BY tp.ProductName ASC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $bid = (int) $row['BranchId'];
                $pid = (int) $row['ProductId'];

                $sqlInDist = "SELECT SUM(Qty) AS Qty FROM tbl_distibute_item_details WHERE BranchId='$bid' AND ProductId='$pid' $dateSqlDist";
                $sqlInCr = "SELECT SUM(Qty) AS Qty FROM tbl_stocks WHERE Status=1 AND BranchId='$bid' AND ProductId='$pid' AND CrDr='cr' $dateSqlStockCr";
                $sqlOut = "SELECT SUM(Qty) AS Qty FROM tbl_stocks WHERE Status=1 AND BranchId='$bid' AND ProductId='$pid' AND CrDr='dr' $dateSqlStockDr";

                $inDist = (float) (getRecord($sqlInDist)['Qty'] ?? 0);
                $inCr = (float) (getRecord($sqlInCr)['Qty'] ?? 0);
                $Inward = $inDist + $inCr;
                $Outward = (float) (getRecord($sqlOut)['Qty'] ?? 0);

                if ($Inward <= 0 && $Outward <= 0) {
                    continue;
                }

                $TotCreditStock += $Inward;
                $TotDebitStock += $Outward;
                $BalStock += $Inward - $Outward;

                $qBase = array('BranchId' => $bid, 'ProductId' => $pid);
                if ($FromDate !== '' && $ToDate !== '') {
                    $qBase['FromDate'] = $FromDate;
                    $qBase['ToDate'] = $ToDate;
                }
                $inwardHref = 'stock-report2-inward-detail.php?' . http_build_query($qBase);
                $outwardHref = 'stock-report-sell-detail.php?' . http_build_query($qBase);

             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo htmlspecialchars((string) $row['Branch']); ?></td>
               <td><?php echo htmlspecialchars((string) $row['Product_Name']); ?></td>
               <td><?php if ($Inward > 0) { ?><a href="<?php echo htmlspecialchars($inwardHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars((string) $Inward); ?></a><?php } else { echo '0'; } ?></td>
               <td><?php if ($Outward > 0) { ?><a href="<?php echo htmlspecialchars($outwardHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars((string) $Outward); ?></a><?php } else { echo '0'; } ?></td>
               <td><?php echo $Inward - $Outward; ?></td>
            </tr>
           <?php $i++;} ?>
           
           <tr>
               <td><?php echo $i; ?></td>
               <td></td>
               <th>Total</th>
               <th><?php echo $TotCreditStock;?></th>
               <th><?php echo $TotDebitStock;?></th>
               <th><?php echo $BalStock;?></th>
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
