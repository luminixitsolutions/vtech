<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Accessories";
$Page = "Add-Accessories";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Accessories</title>
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
$id = $_GET['qid'];
$sql7 = "SELECT * FROM tbl_quotation WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$PrevPaidAmt = $row7['PaidAmt'];
/*$sql77 = "SELECT * FROM tbl_cust_payment WHERE QtId='$id'";
$row77 = getRecord($sql77);*/
?>

<?php 
  if(isset($_POST['submit'])){
    $PaidAmt = addslashes(trim($_POST["PaidAmt"]));
    $Narration = addslashes(trim($_POST["Narration"]));
$PayMode = $_POST["PayMode"];
$PaidDate = $_POST["PaidDate"];

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

$TotPaidAmt = $PrevPaidAmt+$PaidAmt;
 
    $query2 = "UPDATE tbl_quotation SET PaidAmt = '$TotPaidAmt',PayMode='$PayMode',Photo='$Photo',Narration = '$Narration',PaidStatus='1',PaidDate='$PaidDate' WHERE id = '$id'";
  $conn->query($query2);

  $sql = "INSERT INTO tbl_cust_payment SET QtId='$id',Amount='$PaidAmt',PayDate='$PaidDate',PayMode='$PayMode',Narration = '$Narration',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Photo='$Photo',Type=1";
  $conn->query($sql);
  echo "<script>alert('Payment Record Saved Successfully!');window.location.href='view-bill-amount-status.php';</script>";


    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Bill Amount</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-lg-6">
<label class="form-label">QTN NO </label>
<input type="text" class="form-control" value="<?php echo $row7["InvoiceNo"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">QTN Date</label>
<input type="date" class="form-control" value="<?php echo $row7["InvoiceDate"]; ?>" readonly>
<div class="clearfix"></div>
</div>
 
<div class="form-group col-lg-8">
<label class="form-label">Customer Name </label>
<input type="text" class="form-control" value="<?php echo $row7["CustName"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Phone No </label>
<input type="text" class="form-control" value="<?php echo $row7["CellNo"]; ?>" readonly>
<div class="clearfix"></div>
</div>
 
<div class="form-group col-lg-4">
<label class="form-label">Total Amount </label>
<input type="text" class="form-control" id="TotalAmt" value="<?php echo $row7["TotalAmt"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Balalnce Amount </label>
<input type="text" id="BalAmt" class="form-control" value="<?php echo $row7["TotalAmt"]-$row7["PaidAmt"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Paid Amount <span class="text-danger">*</span></label>
<input type="text" name="PaidAmt" id="PaidAmt" class="form-control" value="<?php echo $row7["TotalAmt"]-$row7["PaidAmt"]; ?>" required oninput="calBalAmt()">
<div class="clearfix"></div>
</div>



 <div class="form-group col-md-3">
<label class="form-label"> Payment Mode<span class="text-danger">*</span></label>
 <select class="form-control" name="PayMode" id="PayMode" required>
<option selected="" value="" disabled>Select Payment Mode</option>
  <option value="RTGS" <?php if($row7["PayMode"]=='1') {?> selected <?php } ?>>
    By RTGS</option>
<option value="NEFT" <?php if($row7["PayMode"]=='1') {?> selected <?php } ?>>
    By NEFT</option>

</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Payment Date <span class="text-danger">*</span></label>
<input type="date" name="PaidDate" id="PaidDate" class="form-control" value="<?php echo $row7["PaidDate"]; ?>" required>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-6">
  <label class="form-label">Attach File </label>
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

<div class="form-group col-lg-12">
<label class="form-label">Narration </label>
<input type="text" id="Narration" class="form-control" value="<?php echo $row7["Narration"]; ?>">
<div class="clearfix"></div>
</div>

 <!-- <div class="form-group col-md-12">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Paid</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Not</option>
</select>
<div class="clearfix"></div>
</div>  -->
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
function calBalAmt(){
 var TotalAmt = $('#TotalAmt').val();
 var PaidAmt = $('#PaidAmt').val();
 var BalAmt = (Number(TotalAmt) - Number(PaidAmt));
 $('#BalAmt').val(parseFloat(BalAmt).toFixed(2));
}
  $(document).ready(function() {
           $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });

        });

            
            });

       
</script>
</body>
</html>