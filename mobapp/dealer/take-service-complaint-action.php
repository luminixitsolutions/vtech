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
    
   
    
        <div class="main-container">
           
            
            <?php 
$id = $_GET['id'];
$CompId = $_GET['qid'];
$sql7 = "SELECT * FROM tbl_service_complaint_details WHERE id='$id'";
$row7 = getRecord($sql7);

$sql77 = "SELECT * FROM tbl_service_complaint WHERE id='$CompId'";
$row77 = getRecord($sql77);


if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     $CellNo = addslashes(trim($_POST["CellNo"]));
    $CustName = addslashes(trim($_POST["CustName"]));
$Status = 1;
$Address = addslashes(trim($_POST["Address"]));
$RelatedIssue = addslashes(trim($_POST['RelatedIssue']));
$Issue = addslashes(trim($_POST["Issue"]));
$Message = addslashes(trim($_POST["Message"]));
$ClainStatus = addslashes(trim($_POST["ClainStatus"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');


if($_GET['id']==''){
     $qx = "INSERT INTO tbl_service_complaint_details SET CompId='$CompId',Message='$Message',CreatedDate='$CreatedDate',CreatedBy='$user_id',ClainStatus='$ClainStatus'";
  $conn->query($qx);

  $sql = "UPDATE tbl_service_complaint SET ClainStatus='$ClainStatus' WHERE id='$CompId'";
  $conn->query($sql);
  echo "<script>window.location.href='view-service-complaint-action.php?id=$CompId';</script>";
}
else{
 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_service_complaint_details SET CompId='$CompId',Message='$Message',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',ClainStatus='$ClainStatus' WHERE id = '$id'";
  $conn->query($query2);

  $sql = "UPDATE tbl_service_complaint SET ClainStatus='$ClainStatus' WHERE id='$CompId'";
  $conn->query($sql);

  echo "<script>window.location.href='view-service-complaint-action.php?id=$CompId';</script>";

}
    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Take
                            <?php } ?> Action On Service Complaint</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="form-control" name="CustId" id="CustId" disabled>
 <?php 
  echo $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5 AND id='".$row77['CustId']."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-md-2" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

<div class="form-group col-md-12">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row77["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()" disabled>
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-12">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row77["CustName"]; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  disabled
                                                ><?php echo $row77['Address']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


  <div class="form-group col-md-4">
<label class="form-label"> Service Related Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue" disabled>

<option selected="" value="">Select Related Issue</option>

  <option value="Repair" <?php if($row77['RelatedIssue'] == 'Repair'){?> selected <?php } ?>>Repair</option>
    <option value="Replacement" <?php if($row77['RelatedIssue'] == 'Replacement'){?> selected <?php } ?>>Replacement</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label"> Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="Issue" id="Issue" disabled>
<option selected="" value="">Select Issue</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row77['Issue'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label"> Status<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainStatus" id="ClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=6";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Message</label>
     <textarea  type="text" name="Message" id="Message" class="form-control"><?php echo $row7['Message']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


 

</div>
<br>

                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                
                                    </div>
                               </div>


 <div class="col-lg-5" id="emidetails" style="display:none;">
    

 </div>

  
                                

 </div>
 </form>





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
     
    
</body>

</html>
