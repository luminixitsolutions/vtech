<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "View-Purchase-Order";

$id = $_GET['id'];
$sql7 = "SELECT tpo.*,tu.Fname FROM tbl_purchase_order tpo LEFT JOIN tbl_users tu ON tpo.EmpId=tu.id WHERE tpo.id='$id'";
$row7 = getRecord($sql7);
$InvoiceNo = $row7['InvoiceNo'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title."-PO-".$InvoiceNo; ?>
    </title>
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

    fieldset legend {
        background: inherit;
        font-family: "Lato", sans-serif;
        color: #650812;
        font-size: 15px;
        left: 10px;
        padding: 0 10px;
        position: absolute;
        top: -12px;
        font-weight: 400;
        width: auto !important;
        border: none !important;
    }

    fieldset {
        background: #ffffff;
        border: 1px solid #4FAFB8;
        border-radius: 5px;
        margin: 20px 0 1px 0;
        padding: 20px;
        position: relative;
    }


.bs-vertical-wizard {
    border-right: 1px solid #eaecf1;
    padding-bottom: 50px;
}

.bs-vertical-wizard ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.bs-vertical-wizard ul>li {
    display: block;
    position: relative;
}

.bs-vertical-wizard ul>li>a {
    display: block;
    padding: 10px 10px 10px 40px;
    color: #333c4e;
    font-size: 17px;
    font-weight: 400;
    letter-spacing: .8px;
}

.bs-vertical-wizard ul>li>a:before {
    content: '';
    position: absolute;
    width: 1px;
    height: calc(100% - 25px);
    background-color: #bdc2ce;
    left: 13px;
    bottom: -9px;
    z-index: 3;
}

.bs-vertical-wizard ul>li>a .ico {
    pointer-events: none;
    font-size: 14px;
    position: absolute;
    left: 10px;
    top: 15px;
    z-index: 2;
}

.bs-vertical-wizard ul>li>a:after {
    content: '';
    position: absolute;
    border: 2px solid #bdc2ce;
    border-radius: 50%;
    top: 14px;
    left: 6px;
    width: 16px;
    height: 16px;
    z-index: 3;
}

.bs-vertical-wizard ul>li>a .desc {
    display: block;
    color: #bdc2ce;
    font-size: 11px;
    font-weight: 400;
    line-height: 1.8;
    letter-spacing: .8px;
}

.bs-vertical-wizard ul>li.complete>a:before {
    background-color: #5cb85c;
    opacity: 1;
    height: calc(100% - 25px);
    bottom: -9px;
}

.bs-vertical-wizard ul>li.complete>a:after {display:none;}
.bs-vertical-wizard ul>li.locked>a:after {display:none;}
.bs-vertical-wizard ul>li:last-child>a:before {display:none;}

.bs-vertical-wizard ul>li.complete>a .ico {
    left: 8px;
}

.bs-vertical-wizard ul>li>a .ico.ico-green {
    color: #5cb85c;
}

.bs-vertical-wizard ul>li>a .ico.ico-muted {
    color: #bdc2ce;
}

.bs-vertical-wizard ul>li.current {
    background-color: #fff;
}

.bs-vertical-wizard ul>li.current>a:before {
    background-color: #ffe357;
    opacity: 1;
}

.bs-vertical-wizard ul>li.current>a:after {
    border-color: #ffe357;
    background-color: #ffe357;
    opacity: 1;
}

.bs-vertical-wizard ul>li.current:after, .bs-vertical-wizard ul>li.current:before {
    left: 100%;
    top: 50%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.bs-vertical-wizard ul>li.current:after {
    border-color: rgba(255,255,255,0);
    border-left-color: #fff;
    border-width: 10px;
    margin-top: -10px;
}

.bs-vertical-wizard ul>li.current:before {
    border-color: rgba(234,236,241,0);
    border-left-color: #eaecf1;
    border-width: 11px;
    margin-top: -11px;
}

    </style>
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            


            

                

                 <?php 


if(isset($_POST['submit'])){
    $EmpId = $_POST['EmpId'];
    $ApplyDate = $_POST['ApplyDate'];
    $ApplyTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET EmpId='$EmpId',ApplyDate='$ApplyDate',ApplyTime='$ApplyTime',ApplyStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Order Applied Successfully');window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit2'])){
    $SendDate = $_POST['SendDate'];
    $SendTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET SendDate='$SendDate',SendTime='$SendTime',SendStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Order Send To Manufacture Successfully');window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit3'])){
    $ReceiveDate = $_POST['ReceiveDate'];
    $ReceiveTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET ReceiveDate='$ReceiveDate',ReceiveTime='$ReceiveTime',ReceiveStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Order Receive From Manufacture Successfully');window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit4'])){
    $CustomerId = $_POST['CustomerId'];
    $DeliveredDate = $_POST['DeliveredDate'];
    $VehicalDate = $_POST['VehicalDate'];
    $VehicalNo = $_POST['VehicalNo'];
    $BranchId = $_POST['BranchId'];
    $DeliveredTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',DeliveredDate='$DeliveredDate',DeliveredTime='$DeliveredTime',DeliveredStatus=1,BranchId='$BranchId' WHERE id='$id'";
    $conn->query($sql);

$sql = "DELETE FROM tbl_stocks WHERE SellId='$id' AND ProdType='0' AND SellType='Purchase'";
$conn->query($sql);

    $number = count($_POST["ProductId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProductId"][$i] != ''))  
              {
                $ProductName = addslashes(trim($_POST['ProductName'][$i]));
                $Qty = addslashes(trim($_POST['Qty'][$i]));
                $ProductId = addslashes(trim($_POST['ProductId'][$i]));
                $CompId = addslashes(trim($_POST['CompId'][$i]));
                //$BranchId = addslashes(trim($_POST['BranchId'][$i]));
                $PostId = addslashes(trim($_POST['PostId'][$i]));
                $SerialNo = addslashes(trim($_POST['SerialNo'][$i]));
                $ModelNo = addslashes(trim($_POST['ModelNo'][$i]));
                $SrNo = addslashes(trim($_POST['SrNo'][$i]));
                $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                
                $sql22 = "INSERT INTO tbl_stocks SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',CompId='$CompId',SellId='$id',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$DeliveredDate',Narration='Stock Added',PostId='$PostId',BranchId='$BranchId',SellType='Purchase',SerialNo='$SerialNo',ModelNo='$ModelNo',SrNo='$SrNo',ProdType='0'";
                $conn->query($sql22);

            }
        }
    }
    echo "<script>alert('Order Delivered to Customer Successfully');window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit5'])){
    $CustomerId = $_POST['CustomerId'];
    $DeliveredDate = $_POST['DeliveredDate'];
    $BranchId = $_POST['BranchId'];
    $DeliveredTime = date('H:i:s');
    
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){

         $targetPath = '../uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());
         $sql = "DELETE FROM tbl_stocks WHERE SellId='$id' AND ProdType='1' AND SellType='Purchase'";
$conn->query($sql);
        /*$Qx = "TRUNCATE TABLE sheet1";
        $conn->query($Qx);*/
        for($i=0;$i<$sheetCount;$i++)
        {
            
            $Reader->ChangeSheet($i);
            
            foreach ($Reader as $Row)
            {
          
                $SrNo = "";
                if(isset($Row[0])) {
                    $SrNo = mysqli_real_escape_string($conn,$Row[0]);
                }
                
                $ProductId = "";
                if(isset($Row[1])) {
                    $ProductId = mysqli_real_escape_string($conn,$Row[1]);
                }

                 $ProductName = "";
                if(isset($Row[2])) {
                    $ProductName = mysqli_real_escape_string($conn,$Row[2]);
                }

                 $SerialNo = "";
                if(isset($Row[3])) {
                    $SerialNo = mysqli_real_escape_string($conn,$Row[3]);
                }

                $Unit = "";
                if(isset($Row[4])) {
                    $Unit = mysqli_real_escape_string($conn,$Row[4]);
                }


                 $Qty = "";
                if(isset($Row[5])) {
                    $Qty = mysqli_real_escape_string($conn,$Row[5]);
                }

                

                 $ModelNo = "";
                if(isset($Row[6])) {
                    $ModelNo = mysqli_real_escape_string($conn,$Row[6]);
                }

                 $CompId = "";
                if(isset($Row[7])) {
                    $CompId = mysqli_real_escape_string($conn,$Row[7]);
                }

                 

               $PostId = "";
                if(isset($Row[8])) {
                    $PostId = mysqli_real_escape_string($conn,$Row[8]);
                }

                $VehicalDate = "";
                if(isset($Row[9])) {
                    $VehicalDate = mysqli_real_escape_string($conn,$Row[9]);
                }

                $VehicalNo = "";
                if(isset($Row[10])) {
                    $VehicalNo = mysqli_real_escape_string($conn,$Row[10]);
                }


               
               
                 if (!empty($SrNo) || !empty($ProductId) || !empty($ProductName) || !empty($SerialNo) || !empty($Qty) || !empty($ModelNo) || !empty($CompId) || !empty($PostId)) {

                   
                      $sql22 = "INSERT INTO tbl_stocks SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',CompId='$CompId',SellId='$id',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$DeliveredDate',Narration='Stock Added',PostId='$PostId',BranchId='$BranchId',SellType='Purchase',SerialNo='$SerialNo',ModelNo='$ModelNo',SrNo='$SrNo',ProdType='1',Unit='$Unit'";
                $conn->query($sql22);
                
               
                    

  
                  
                }
             }
        
         }
         
 $sql = "DELETE FROM tbl_stocks WHERE SrNo='SrNo' AND ProdType='1'";
$conn->query($sql);
?>
<script>
alert("Excel Data Imported into the Database");
    window.location.href='take-po-action.php?id=<?php echo $id;?>';
</script>
<?php
  }
  else
  { 
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
  }
  
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Purchase Order Status</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 
                                <fieldset>
                                 <legend>Apply Order</legend>
                                 <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">

<div class="form-group col-md-6" >
<label class="form-label"> Employee<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="EmpId" id="EmpId" required>
<option selected="" value="">Select Employee</option>
 <?php 
  $sql12 = "SELECT Fname,Phone,id FROM tbl_users WHERE Status='1' AND Roll IN(28)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["EmpId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Apply Date <span class="text-danger">*</span></label>
     <input type="date" name="ApplyDate" id="ApplyDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ApplyDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form>
</fieldset>


 <fieldset>
                                 <legend>Send Order Details</legend>
                                 <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">

<div class="form-group col-md-6" >
<label class="form-label"> Send To anufacture</label>
 <select class="form-control">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3 AND id='".$row7["CustId"]."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Send Date </label>
     <input type="date" name="SendDate" id="SendDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SendDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit2" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form>
</fieldset>


<fieldset>
                                 <legend>Received Order Details</legend>
                                 <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">

<div class="form-group col-md-6" >
<label class="form-label"> Received From Manufacture</label>
 <select class="form-control">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3 AND id='".$row7["CustId"]."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Received Date </label>
     <input type="date" name="ReceiveDate" id="ReceiveDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ReceiveDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit3" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form>
</fieldset>


<fieldset>
                                 <legend>Tracking Order</legend>
              
              <div class="row pt-3 pb-4">
        
        <div class="col">
          
          <div class="bs-vertical-wizard" style="padding-bottom: 10px;">
                            <ul>

                                <li class="complete">
                                    <a href="#">Order Placed . <i class="ico fa fa-check ico-green"></i>
                                         <span class="desc">Placed at <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['InvoiceDate'])));?></span>
                                    </a>
                                </li>
                               
                                  <?php if($row7['ApplyStatus'] == 1) {?>
                                  <li class="complete prev-step">
                                    <a href="#">Applied Order By <?php echo $row7['Fname'];?><i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['ApplyTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['ApplyDate'])));?></span>
                                    </a>
                                  </li> 
                                  <?php } else{?>  
                                  <li class="locked">
                                    <a href="#">Step 2 :<i class="ico fa fa-lock ico-muted"></i></a>
                                  </li>
                                  <?php } ?>
                                 
                                
                                <?php if($row7['SendStatus'] == 1) {?>  
                                <li class="complete prev-step">
                                    <a href="#">Order Send To Manufacture <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['SendTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['SendDate'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 3 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>   

                                 <?php if($row7['ReceiveStatus'] == 1) {?>  
                               <li class="complete prev-step">
                                    <a href="#">Order Receive From Manufacturer <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['ReceiveTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['ReceiveDate'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 4 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>    

                                <?php if($row7['DeliveredStatus'] == 1) {?>  
                               <li class="complete prev-step">
                                    <a href="#">Order Delivered To Customer <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['DeliveredTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['DeliveredDate'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 5 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>    
                             </ul>
                        </div>
                       
                   
        </div>
      </div>                  
</fieldset>


<fieldset>
                                 <legend>Delivered Order</legend>
                               
                               <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">
<h5>Regular Products</h5>
<table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>MODEL NO.</th>
             <!--  <th>SERIAL NO.</th> -->
              <th>Unit</th>

               <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i2=1;
            $sql66 = "SELECT tpo.*,tp.ModelNo FROM tbl_purchase_order_products tpo INNER JOIN tbl_products tp ON tpo.ProductId=tp.id WHERE tpo.SellId='".$_GET['id']."' AND tp.Roll=0";
            $row66 = getList($sql66);
            foreach($row66 as $result){
               

                    
                ?>
            <tr>
                <td><?php echo $i2;?></td>
                <td><?php echo $result['ProductName'];?></td>
                <td><?php echo $result['ModelNo'];?></td>
               <!--  <td><input type="text" value="<?php echo $SerialNo;?>" name="SerialNo[]" class="form-control"></td> -->
                <td><?php echo $result['Purity'];?></td>
               <td><input type="text" value="<?php echo $result['Qty'];?>" name="Qty[]" class="form-control"></td>
                <input type="hidden" name="ProductName[]" value='<?php echo $result['ProductName'];?>'>
<input type="hidden" value="N/A" name="SerialNo[]" class="form-control">
                 <input type="hidden" name="SrNo[]" value='<?php echo $i;?>'>
                <input type="hidden" name="ModelNo[]" value='<?php echo $result['ModelNo'];?>'>
                <input type="hidden" name="ProductId[]" value="<?php echo $result['ProductId'];?>">
                <!-- <input type="hidden" name="Qty[]" value="1"> -->
                <input type="hidden" name="CompId[]" value="<?php echo $row7['CompId'];?>">
                <!-- <input type="hidden" name="BranchId[]" value="<?php echo $row7['BranchId'];?>"> -->
                <input type="hidden" name="ProdType[]" value="0">
                <input type="hidden" name="PostId[]" value="<?php echo $result['id'];?>">
            </tr>
        <?php $i2++; }  ?>
        </tbody>
    </table>

   

<!-- <div class="form-group col-md-3">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="">Select Store</option>
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
</div> -->

<!-- <div class="form-group col-md-4" >
<label class="form-label"> Customer</label>
 <select class="select2-demo form-control" name="CustomerId" id="CustomerId" required>
    <option value="" selected>Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustomerId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-md-3">
   <label class="form-label">Delivered Date </label>
     <input type="date" name="DeliveredDate" id="DeliveredDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DeliveredDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-lg-3">
<label class="form-label">Vehical Date <span class="text-danger">*</span></label>
<input type="date" name="VehicalDate" id="VehicalDate" class="form-control" value="<?php echo $row7["VehicalDate"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Vehical No <span class="text-danger">*</span></label>
<input type="text" name="VehicalNo" id="VehicalNo" class="form-control" value="<?php echo $row7["VehicalNo"]; ?>" required>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-2" style="padding-top:30px;">
                                    <button type="submit" name="submit4" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form> <br>
<hr><br>
<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="form-row">
 <h5>Serial No Products
        <!--<?php for($i=1;$i<=200;$i++){?>&nbsp;<?php } ?><a href="generate-excel.php?id=<?php echo $_GET['id'];?>&compid=<?php echo $row7['CompId'];?>&invno=<?php echo $row7['InvoiceNo'];?>" style="float:right;" >Download Excel</a>--></h5>
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
              <th>SrNo</th>
               <th>Product Id</th>
              <th>Product</th>
              <th>SERIAL NO.</th> 
              <th>Unit</th>
              <th>Qty</th>
              <th>MODEL NO.</th>
              <th>Company Id</th>
              <!--<th>Product Type</th>-->
              <th>Post Id</th>
              <th>Vehical Date</th>
              <th>Vehical No</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i3=1;
            $sql = "SELECT * FROM tbl_stocks WHERE SellId='".$_GET['id']."' AND ProdType='1'";
            $rncnt = getRow($sql);
            if($rncnt > 0){
            $sql66 = "SELECT tpo.* FROM tbl_stocks tpo WHERE tpo.SellId='".$_GET['id']."' AND ProdType='1'";
            
             $row66 = getList($sql66);
            foreach($row66 as $result){
               
                ?>
            <tr>
                <td><?php echo $result['SrNo'];?></td>
                 <td><?php echo $result['ProductId'];?></td>
                <td><?php echo $result['ProductName'];?></td>
               
                <td><?php echo $result['SerialNo'];?></td>
                <td><?php echo $result['Unit'];?></td>
                 <td><?php echo $result['Qty'];?></td>
                 <td><?php echo $result['ModelNo'];?></td>
                <td><?php echo $row7['CompId'];?></td>
               <!-- <td>1</td>-->
                <td><?php echo $result['PostId'];?></td>
                <td><?php echo $result['VehicalDate'];?></td>
                <td><?php echo $result['VehicalNo'];?></td>
            </tr>
        <?php 
        
            } }
            else{
                $sql66 = "SELECT tpo.*,tp.ModelNo FROM tbl_purchase_order_products tpo INNER JOIN tbl_products tp ON tpo.ProductId=tp.id WHERE tpo.SellId='".$_GET['id']."' AND tp.Roll=1";
                 $row66 = getList($sql66);
            foreach($row66 as $result){
               for($i=1;$i<=$result['Qty'];$i++){

                    $sql = "SELECT SerialNo FROM tbl_stocks WHERE SellId='".$_GET['id']."' AND ProductId='".$result['ProductId']."' AND SrNo='$i'";
                    $row = getRecord($sql);
                    $SerialNo = $row['SerialNo'];
                ?>
            <tr>
                <td><?php echo $i;?></td>
                 <td><?php echo $result['ProductId'];?></td>
                <td><?php echo $result['ProductName'];?></td>
               
                <td><?php echo $SerialNo;?></td>
                <td><?php echo $result['Purity'];?></td>
                <td>1</td>
                 <td><?php echo $result['ModelNo'];?></td>
                <td><?php echo $row7['CompId'];?></td>
               <!-- <td>1</td>-->
                <td><?php echo $result['id'];?></td>
                <td><?php echo $row['VehicalDate'];?></td>
                <td><?php echo $row['VehicalNo'];?></td>
            </tr>
        <?php $i3++; }
            }
            }  ?>
        </tbody>
    </table>
    
    
<!--     <div class="form-group col-md-4" >
<label class="form-label"> Customer</label>
 <select class="select2-demo form-control" name="CustomerId" id="CustomerId" required>
    <option value="" selected>Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustomerId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-md-3">
   <label class="form-label">Delivered Date </label>
     <input type="date" name="DeliveredDate" id="DeliveredDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DeliveredDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

<div class="form-group col-md-3">
   <label class="form-label">Upload Excel File </label>
     <input type="file" name="file" id="" class="form-control"
                                                placeholder=""
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-2" style="padding-top:30px;">
                                    <button type="submit" name="submit5" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>
                                    
    </div>
</form> 
</fieldset>

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
<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
         "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>

</html>