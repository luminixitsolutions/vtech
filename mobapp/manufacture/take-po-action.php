<?php session_start();
require_once '../config.php';
require_once '../auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
//$mycity = $row['CityId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row11['Validity'])));

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='$UserId' GROUP BY Status) as a";
    $row88 = getRecord($sql88);
    $Wallet = $row88['Credit'] - $row88['Debit'];
    
    //echo $_GET['city_id'];
    if($_GET['city_id']==0 || $_GET['city_id']==''){
    $city_id = $row11['CityId'];  
    
}
else{
  $city_id = $_REQUEST['city_id'];
}
//echo $city_id;
/* $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$UserId'";
    $conn->query($sql);*/
 ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
   <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../img/favicon180.png" sizes="180x180">
    <link rel="icon" href="../img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="../vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet" id="style">
     <?php include_once 'header_script.php'; ?>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once '../sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once '../top_header.php'; ?>
    
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
    
        <div class="main-container">
           
            
<?php 
$id = $_GET['id'];
$sql7 = "SELECT tpo.*,tu.Fname FROM tbl_purchase_order tpo LEFT JOIN tbl_users tu ON tpo.EmpId=tu.id WHERE tpo.id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
    $EmpId = $_POST['EmpId'];
    $ApplyDate = $_POST['ApplyDate'];
    $ApplyTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET EmpId='$EmpId',ApplyDate='$ApplyDate',ApplyTime='$ApplyTime',ApplyStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit2'])){
    $SendDate = $_POST['SendDate'];
    $SendTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET SendDate='$SendDate',SendTime='$SendTime',SendStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit3'])){
    $ReceiveDate = $_POST['ReceiveDate'];
    $ReceiveTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET ReceiveDate='$ReceiveDate',ReceiveTime='$ReceiveTime',ReceiveStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>window.location.href='take-po-action.php?id=$id';</script>";
}

if(isset($_POST['submit4'])){
    $CustomerId = $_POST['CustomerId'];
    $DeliveredDate = $_POST['DeliveredDate'];
    $DeliveredTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET CustomerId='$CustomerId',DeliveredDate='$DeliveredDate',DeliveredTime='$DeliveredTime',DeliveredStatus=1 WHERE id='$id'";
    $conn->query($sql);

$sql = "DELETE FROM tbl_stocks WHERE SellId='$id'";
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
                $BranchId = addslashes(trim($_POST['BranchId'][$i]));
                $PostId = addslashes(trim($_POST['PostId'][$i]));
                $sql22 = "INSERT INTO tbl_stocks SET CustomerId='$CustomerId',CompId='$CompId',SellId='$id',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$DeliveredDate',Narration='Stock Added',PostId='$PostId',BranchId='$BranchId',SellType='Purchase'";
                $conn->query($sql22);

            }
        }
    }
    echo "<script>window.location.href='take-po-action.php?id=$id';</script>";
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
 <select class="form-control" name="EmpId" id="EmpId" required>
<option selected="" value="">Select Employee</option>
 <?php 
  $sql12 = "SELECT Fname,Phone,id FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,3,4,5,9,10,8,11)";
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
                                    <a href="#">Order Receive From Manufacture <i class="ico fa fa-check ico-green"></i>
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
                                    <a href="#">Order Delivered To Constomer <i class="ico fa fa-check ico-green"></i>
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
                                    <div class="form-row"  style="overflow-x: auto;">

<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Unit</th>
               <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql66 = "SELECT * FROM tbl_purchase_order_products WHERE SellId='".$_GET['id']."'";
            $row66 = getList($sql66);
            foreach($row66 as $result){?>
            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo $result['ProductName'];?></td>
                <td><?php echo $result['Purity'];?></td>
                <td><?php echo $result['Qty'];?></td>
                <input type="hidden" name="ProductName[]" value='<?php echo $result['ProductName'];?>'>
                <input type="hidden" name="ProductId[]" value="<?php echo $result['ProductId'];?>">
                <input type="hidden" name="Qty[]" value="<?php echo $result['Qty'];?>">
                <input type="hidden" name="CompId[]" value="<?php echo $row7['CompId'];?>">
                <input type="hidden" name="BranchId[]" value="<?php echo $row7['BranchId'];?>">
                <input type="hidden" name="PostId[]" value="<?php echo $result['id'];?>">
            </tr>
        <?php $i++;} ?>
        </tbody>
    </table>
<div class="form-group col-md-6" >
<label class="form-label"> Customer</label>
 <select class="form-control" name="CustomerId" id="CustomerId" required>
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
</div>

<div class="form-group col-md-3">
   <label class="form-label">Delivered Date </label>
     <input type="date" name="DeliveredDate" id="DeliveredDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DeliveredDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit4" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form> 
</fieldset>

                            </div>
                        </div>



</div>


                   


                    <?php include_once 'footer.php'; ?>
                </div>
            
        </div>
    </main>

    <?php include_once '../footer.php';?>


    <!-- color settings style switcher -->
    



    <!-- Required jquery and libraries -->
   <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="../js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="../vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="../js/main.js"></script>
    <script src="../js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="../js/app.js"></script>
     <?php include_once 'footer_script.php'; ?>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
      </script>
      <script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
    
</body>

</html>
