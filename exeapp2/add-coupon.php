<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Masters";
$Page="Coupon-Code";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Coupon Code</title>
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
$sql7 = "SELECT * FROM tbl_coupon_code WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

<?php 
  if(isset($_POST['submit'])){
$Code = addslashes(trim($_POST["Code"]));
$Discount = addslashes(trim($_POST["Discount"]));
$FromDate = addslashes(trim($_POST["FromDate"]));
$ToDate = addslashes(trim($_POST["ToDate"]));
$MinOrder = addslashes(trim($_POST["MinOrder"]));
$PointDays = addslashes(trim($_POST["PointDays"]));
$Details = addslashes(trim($_POST["Details"]));
$Status = $_POST["Status"];
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
  $Photo = $_POST['OldPhoto'];
}

if($_GET['id']==''){
    $qx = "INSERT INTO tbl_coupon_code SET Code='$Code',Discount='$Discount',FromDate='$FromDate',ToDate='$ToDate',MinOrder='$MinOrder',PointDays='$PointDays',Photo='$Photo',Details='$Details',Status='$Status',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
  $conn->query($qx);
  echo "<script>alert('Coupon/Offer Added Successfully!');window.location.href='coupon-code.php';</script>";
}
else{
      $query2 = "UPDATE tbl_coupon_code SET Code='$Code',Discount='$Discount',FromDate='$FromDate',ToDate='$ToDate',MinOrder='$MinOrder',PointDays='$PointDays',Photo='$Photo',Details='$Details',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id = '$id'";
  $conn->query($query2);
  echo "<script>alert('Coupon/Offer Updated Successfully!');window.location.href='coupon-code.php';</script>";
}
    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Coupon Code</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Masters</li>
<li class="breadcrumb-item active">Coupon Code</li>
</ol>
</div>

<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-lg-12">
<label class="form-label">Referral/Coupon Code <span class="text-danger">*</span></label>
<input type="text" name="Code" class="form-control" id="Code" placeholder="" required value="<?php echo $row7['Code'];?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">From Date<span class="text-danger">*</span></label>
<input type="date" name="FromDate" class="form-control" id="FromDate" placeholder="" required value="<?php echo $row7['FromDate'];?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">To Date<span class="text-danger">*</span></label>
<input type="date" name="ToDate" class="form-control" id="ToDate" placeholder="" required value="<?php echo $row7['ToDate'];?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Points<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Discount" name="Discount" class="form-control" required onKeyPress="return isNumberKey(event)" value="<?php echo $row7['Discount'];?>">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">Pts</div>
</div>
</div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Points Validity<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="PointDays" name="PointDays" class="form-control" value="<?php echo $row7['PointDays'];?>" required onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">Days</div>
</div>
</div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Min Order Amount<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinOrder" name="MinOrder" class="form-control" value="<?php echo $row7['MinOrder'];?>" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Details <span class="text-danger">*</span></label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details" required><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-12">
  <label class="form-label"> Coupon/Offer Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" value="<?php echo $row7['Photo'];?>" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>


<div class="form-group col-md-12">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
</select>
<div class="clearfix"></div>
</div>
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
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
        CKEDITOR.replace( 'editor1');
</script>
</body>
</html>
.