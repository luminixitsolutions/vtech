<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "Delete-Bill-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Purchase Order List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php

if(isset($_POST['submit'])){

   
   $CreatedDate = date('Y-m-d H:i:s');
   
      $number = count($_POST['CheckId']);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                 $QtnId = addslashes(trim($_POST['QtnId'][$i]));
                $sql = "DELETE FROM tbl_rooftop_stocks WHERE id='$QtnId'";
                $conn->query($sql);

                }
              }
            }
        }




        echo "<script>alert('Stock Delete');window.location.href='delete-bill-no-stock.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Delete Bill No Stock
   
</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-3">
<label class="form-label">Bill No</label>
 <select class="select2-demo form-control" name="BillNo" id="BillNo">
 <?php 
  $sql12 = "SELECT DISTINCT(BillNo) FROM tbl_rooftop_purchase_order";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BillNo"] == $result['BillNo']) {?> selected <?php } ?> value="<?php echo $result['BillNo'];?>">
    <?php echo $result['BillNo']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

 
<input type="hidden" id="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="button" onclick="search()" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
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
   <?php if(isset($_REQUEST['Search'])) {?>
     <form id="validation-form" method="post" enctype="multipart/form-data" action="">   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Status</th>
                <th>Product Name</th>
              
                <th>Serial No</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_rooftop_stocks WHERE BillNo='".$_REQUEST["BillNo"]."' AND SellType='Purchase'";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['ProdType'] == 1){
                 $sql2 = "SELECT * FROM tbl_rooftop_sell_products WHERE ProductId='".$row['ProdType']."' AND SerialNo='".$row['SerialNo']."'";
                $rncnt2 = getRow($sql2);
                if($rncnt2 > 0){
                    $SellStatus = "Sell";
                    $value = "1";
                }
                else{
                     $SellStatus = "Not Sell";
                     $value = "0";
                }
                }
                else{
                   $sql2 = "SELECT * FROM tbl_rooftop_sell_products WHERE ProductId='".$row['ProdType']."'";
                $rncnt2 = getRow($sql2); 
                if($rncnt2 > 0){
                    $SellStatus = "Sell";
                    $value = "1";
                }
                else{
                     $SellStatus = "Not Sell";
                     $value = "0";
                }
                }
                
             ?>
            <tr>
               <td><?php if($rncnt2 > 0){} else{?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)" checked>
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                <input type="hidden" value="<?php echo $value;?>" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId[]">
              <td><?php echo $SellStatus;?></td>
              <td><?php echo $row['ProductName']; ?></td>
              <td><?php echo $row['SerialNo']; ?></td>
            
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Delete</button>
</div>
</form>
<?php } ?>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
 function search(){
     var BillNo = $('#BillNo').val();
     var Search = $('#Search').val();
     window.location.href="delete-bill-no-stock.php?BillNo="+BillNo+"&Search="+Search;
 }
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
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
