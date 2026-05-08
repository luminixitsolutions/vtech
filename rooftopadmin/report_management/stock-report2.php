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

       
<!--<div class="form-group col-md-2">
<label class="form-label"> Branch<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
  <?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="all">All</option>
 <?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id='$BranchId'";
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>-->

  <div class="form-group col-md-5">
                                            <label class="form-label">Product</label>
                                            <select class="select2-demo form-control" name="ProductId" id="ProductId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_rooftop_products WHERE Status='1'";
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
<div class="form-group col-md-1" style="padding-top:30px;">
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
   <?php if(isset($_POST['Search'])) {?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               
                <th>Product Name</th>
                <th>Narration</th>
                <th>Date</th>
                <th>Inward</th>
                <th>Outward</th>
                
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As Branch,tp.ProductName AS Product_Name FROM tbl_rooftop_stocks ts 
                    INNER JOIN tbl_rooftop_products tp ON ts.ProductId=tp.id 
                    LEFT JOIN tbl_rooftop_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 
                    ";
             

            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.BranchId='$BranchId'";
                }
            }
            
            if($_POST['ProductId']){
                $ProductId = $_POST['ProductId'];
                if($ProductId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.ProductId='$ProductId'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.CreatedDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.CreatedDate ASC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['CrDr'] == 'cr'){
                    $CreditStock = $row['Qty'];
                    $DebitStock = "";
                    $TotCreditStock+=$row['Qty'];
                }
                else{
                    $DebitStock = $row['Qty'];
                    $CreditStock = "";
                    $TotDebitStock+=$row['Qty'];
                }

                /*$sql2 = "SELECT SUM(Qty) AS TotStock FROM tbl_rooftop_stocks WHERE BranchId='".$row['BranchId']."' AND ProductId='".$row['ProductId']."' AND CrDr='cr'";
                $row2 = getRecord($sql2);

                $sql3 = "SELECT SUM(Qty) AS SellStock FROM tbl_rooftop_stocks WHERE BranchId='".$row['BranchId']."' AND ProductId='".$row['ProductId']."' AND CrDr='dr'";
                $row3 = getRecord($sql3);*/

                $TotStock+=$row2['TotStock'];
                $SellStock+=$row3['SellStock'];
                $BalStock+=$row2['TotStock'] - $row3['SellStock'];


             ?>
            <tr>
               <td><?php echo $i; ?></td>
           
           
          
               <td><?php echo $row['Product_Name']; ?></td>
            
                <td><?php echo $row['Narration']; ?></td>
                  <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
               <td><?php echo $CreditStock; ?></td>
               <td><?php echo $DebitStock; ?></td>
                 
            
          
          
            </tr>
           <?php $i++;} ?>

           <tr>
               <td><?php echo $i; ?></td>
               
              
              
               <td></td>
               <td></td>
               <th>Total</th>
               <th><?php echo $TotCreditStock;?></th>
               <th><?php echo $TotDebitStock;?></th>
               
           </tr>
           <tr>
               <td><?php echo $i; ?></td>
               
              
              
               <td></td>
               <td></td>
               <th>Balance</th>
               <th><?php echo $TotCreditStock-$TotDebitStock;?></th>
               <th></th>
               
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
