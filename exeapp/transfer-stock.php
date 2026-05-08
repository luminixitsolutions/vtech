<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Stock";
$Page = "Add-Stock";
$sessionid = session_id();
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
 <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">







<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_stock_invoice WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');
?>

<?php 
  if(isset($_POST['submit'])){
    $BranchId = $_POST['BranchId'];
    $FromBranchId = $_POST['FromBranchId'];
    $TotQty = addslashes(trim($_POST['TotQty']));
    $DmNo = addslashes(trim($_POST['DmNo']));
    $StockDate = addslashes(trim($_POST['StockDate']));
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));


     $qx = "INSERT INTO tbl_stock_invoice SET BranchId='$BranchId',TotQty='$TotQty',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',DmNo='$DmNo',StockDate='$StockDate',Narration='$Narration'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);

     $number = count($_POST["ProdId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["Qty"][$i] != '' || $_POST["Qty"][$i] != 0))  
              {
                $ProdId = $_POST['ProdId'][$i];
                $sql = "SELECT * FROM tbl_products WHERE id='$ProdId' ";
                $row = getRecord($sql);
                $ProdName = $row['ProductName'];
               
                $Qty = $_POST['Qty'][$i];
                
                $sql22 = "INSERT INTO tbl_stocks SET BranchId='$BranchId',FromBranchId='$FromBranchId',InvId='$InvId',ProductId='$ProdId',ProductName='$ProdName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$CreatedDate',Narration='$Narration'";
                $conn->query($sql22);

                $sql22 = "INSERT INTO tbl_stocks SET BranchId='$FromBranchId',FromBranchId='$BranchId',InvId='$InvId',ProductId='$ProdId',ProductName='$ProdName',Qty='$Qty',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$CreatedDate',Narration='$Narration'";
                $conn->query($sql22);
              }  

          }
      }

      
   echo "<script>alert('Product Stock Transfer Successfully!');window.location.href='view-transfer-stocks.php';</script>";

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product Stock</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-10">
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">


  <div class="form-group col-md-4">
<label class="form-label"> From Branch<span class="text-danger">*</span></label>
 <select class="form-control" name="FromBranchId" id="FromBranchId" required>
<?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="">Select Branch</option>
<?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
}

  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


  <div class="form-group col-md-4">
<label class="form-label"> To Branch<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="">Select Branch</option>
<?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
}

  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>



<div class="form-group col-md-4">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control txt" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
 </div>
    <div id="dynamic_field">
    <div class="form-row">
<div class="form-group col-md-6 ">
<label class="form-label">Product Name <span class="text-danger">*</span></label>
 <select class="form-control" name="ProdId[]" id="ProdId1" onchange="getProdStock(document.getElementById('srno1').value,this.value)" required>
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>



                                        <div class="form-group col-md-2">
<label class="form-label">Tot Qty <span class="text-danger">*</span></label>
<input type="number" id="TotQty1" class="form-control" placeholder="e.g.,1" value="" autocomplete="off" min="1" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Transfer Qty <span class="text-danger">*</span></label>
<input type="number" name="Qty[]" id="Qty1" class="form-control txt" placeholder="e.g.,1" value="" autocomplete="off" min="1" oninput="getSubTotal()" required>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>
</div>
</div>

<div class="form-row">




<div class="form-group col-md-12">
<label class="form-label">Total Transfer Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

    
<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"  
                                                ><?php echo $row7['Narration']; ?></textarea>
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Submit</button>
<span id="error_msg" style="color:red;"></span>

</form>
</div>
<div class="col-lg-4" id="showcart">
    
        
</div>
</div>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
 </main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?>
 <script>
  function getProdStock(srno,id){
        var action = "getProdStock";
        var FromBranchId = $('#FromBranchId').val();
    $.ajax({
    url:"ajax_files/ajax_stock.php",
    method:"POST",
    data : {action:action,id:id,FromBranchId:FromBranchId},
    success:function(data)
    {
      //alert(data);
      $('#TotQty'+srno).val(data);
    }   
    });  
  }
    function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#TotQty').val(sum);
   
    }

    $(document).ready(function(){
  var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "addMoreService2";
    $.ajax({
    url:"ajax_files/ajax_stock.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {
      $('#dynamic_field').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row'+button_id+'').remove();  
            
           }
      }); 

  }); 
</script>
</body>
</html>