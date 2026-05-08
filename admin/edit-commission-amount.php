<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Dealer-Commission";
$Page = "Dealer-Commission";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Dealer-Commission</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
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
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM wallet WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

<?php 
  if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     $UserId = addslashes(trim($_POST["UserId"]));
    $CustName = addslashes(trim($_POST["CustName"]));

$DealerName = addslashes(trim($_POST["DealerName"]));
$Dealer_Code = addslashes(trim($_POST['Dealer_Code']));
$Amount = addslashes(trim($_POST["Amount"]));
$CreatedDate = addslashes(trim($_POST["CreatedDate"]));
$Narration = addslashes(trim($_POST["Narration"]));

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


$qx = "UPDATE wallet SET Amount='$Amount',CreatedDate = '$CreatedDate',Status='Cr',Narration='$Narration',Photo='$Photo',Commission=1 WHERE id='$id'";
$conn->query($qx);

  echo "<script>alert('Commission Updated Successfully...');window.location.href='dealer-commission.php';</script>";


    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pay Dealer Commission</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<input type="hidden" name="CustId" class="form-control" id="CustId" placeholder="" value="<?php echo $row7["CustId"]; ?>" readonly>
<input type="hidden" name="UserId" class="form-control" id="UserId" placeholder="" value="<?php echo $row7["UserId"]; ?>" readonly>
 
  <div class="form-group col-lg-4">
<label class="form-label">Customer Name <span class="text-danger">*</span></label>
<input type="text" name="CustName" class="form-control" id="CustName" placeholder="" value="<?php echo $row7["CustName"]; ?>" readonly>
<div class="clearfix"></div>
</div>

 <div class="form-group col-lg-4">
<label class="form-label">Dealer Name <span class="text-danger">*</span></label>
<input type="text" name="DealerName" class="form-control" id="DealerName" placeholder="" value="<?php echo $row7["DealerName"]; ?>" readonly>
<div class="clearfix"></div>
</div>

 <div class="form-group col-lg-4">
<label class="form-label">Dealer Code <span class="text-danger">*</span></label>
<input type="text" name="Dealer_Code" class="form-control" id="Dealer_Code" placeholder="" value="<?php echo $row7["Dealer_Code"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount" class="form-control" id="Amount" placeholder="" value="<?php echo $row7["Amount"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Payment date <span class="text-danger">*</span></label>
<input type="date" name="CreatedDate" class="form-control" id="CreatedDate" placeholder="" value="<?php echo $row7["CreatedDate"]; ?>" required>
<div class="clearfix"></div>
</div>
 

<div class="form-group col-md-6">
  <label class="form-label">Image </label>
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
<textarea name="Narration" class="form-control" placeholder="Narration"><?php echo $row7["Narration"]; ?></textarea>
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
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
 
</body>
</html>