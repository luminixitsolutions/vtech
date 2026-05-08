<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Attendance";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; ?>
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

<style>
    .custom-control {
  line-height: 24px;
  padding-top: 5px;
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
$sql7 = "SELECT * FROM tbl_qtn_products WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

<?php 
  if(isset($_POST['submit'])){
    $BrandId = addslashes(trim($_POST["BrandId"]));
     $CatId = addslashes(trim($_POST["CatId"]));
    $ProductName = addslashes(trim($_POST["ProductName"]));
$Status = $_POST["Status"];
$Price = $_POST['Price'];
$Duration = $_POST['Duration'];
$Period = $_POST['Period'];
$Details = addslashes(trim($_POST["Details"]));
$ShortDetails = addslashes(trim($_POST['ShortDetails']));
$CourseType = addslashes(trim($_POST["CourseType"]));
$CourseCode = addslashes(trim($_POST["CourseCode"]));
$Lectures = addslashes(trim($_POST["Lectures"]));
$Language = addslashes(trim($_POST["Language"]));
$ModelNo = addslashes(trim($_POST["ModelNo"]));
$ProductCode = addslashes(trim($_POST["ProductCode"]));

$CGST = addslashes(trim($_POST["CGST"]));
$SGST = addslashes(trim($_POST["SGST"]));
$IGST = addslashes(trim($_POST["IGST"]));
$Unit = addslashes(trim($_POST["Unit"]));
$Roll = addslashes(trim($_POST["Roll"]));
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

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
  $Photo2 = $_POST['OldPhoto2'];
}

function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;
$Code = RandomStringGenerator($n);

if($_GET['id']==''){
     $qx = "INSERT INTO tbl_qtn_products SET CGST='$CGST',SGST='$SGST',IGST='$IGST',Photo2='$Photo2',ProductCode='$ProductCode',BrandId='$BrandId',CatId='$CatId',ProductName = '$ProductName',Status='$Status',ModelNo='$ModelNo',Photo='$Photo',Price = '$Price',Details='$Details',CreatedDate='$CreatedDate',CreatedBy='$user_id',Code='$Code',Unit='$Unit',Roll='$Roll'";
  $conn->query($qx);
  $id = mysqli_insert_id($conn);
  $PrdNo = "1000".$id;
  $sql = "UPDATE tbl_qtn_products SET PrdNo='$PrdNo' WHERE id='$id'";
  $conn->query($sql);
  echo "<script>alert('Product Added Successfully!');window.location.href='view-quotation-products.php';</script>";
}
else{
 
    $query2 = "UPDATE tbl_qtn_products SET CGST='$CGST',SGST='$SGST',IGST='$IGST',Photo2='$Photo2',ProductCode='$ProductCode',BrandId='$BrandId',CatId='$CatId',ProductName = '$ProductName',Status='$Status',ModelNo='$ModelNo',Photo='$Photo',Price = '$Price',Details='$Details',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',Code='$Code',Unit='$Unit',Roll='$Roll' WHERE id = '$id'";
  $conn->query($query2);
  echo "<script>alert('Product Updated Successfully!');window.location.href='view-quotation-products.php';</script>";

}
    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Quotation Product</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

 
 <div class="form-group col-lg-12">
<label class="form-label">Product Name <span class="text-danger">*</span></label>
<input type="text" name="ProductName" class="form-control" id="ProductName" placeholder="Product Name" value="<?php echo $row7["ProductName"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">HSN Code </label>
<input type="text" name="ModelNo" class="form-control" id="ModelNo" placeholder="Model No" value="<?php echo $row7["ModelNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Product Code </label>
<input type="text" name="ProductCode" class="form-control" id="ProductCode" placeholder="" value="<?php echo $row7["ProductCode"]; ?>">
<div class="clearfix"></div>
</div>
 
 <div class="form-group col-lg-3">
<label class="form-label">Product Price </label>
<input type="text" name="Price" class="form-control" id="Price" placeholder="Product Price" value="<?php echo $row7["Price"]; ?>">
<div class="clearfix"></div>
</div>

 <div class="form-group col-lg-3">
<label class="form-label">Product Unit <span class="text-danger">*</span></label>
<input type="text" name="Unit" class="form-control" id="Unit" placeholder="Product Unit" value="<?php echo $row7["Unit"]; ?>" required>
<div class="clearfix"></div>
</div>


<div class="form-group col-lg-4">
<label class="form-label">GST % </label>
<input type="text" name="CGST" class="form-control" id="CGST" placeholder="" value="<?php echo $row7["CGST"]; ?>">
<div class="clearfix"></div>
</div>

<input type="hidden" name="SGST" class="form-control" id="SGST" placeholder="" value="0" required>
<input type="hidden" name="IGST" class="form-control" id="IGST" placeholder="" value="0">

<!-- <div class="form-group col-lg-4">
<label class="form-label">SGST % </label>
<input type="text" name="SGST" class="form-control" id="SGST" placeholder="" value="<?php echo $row7["SGST"]; ?>" required>
<div class="clearfix"></div>
</div>
 
 <div class="form-group col-lg-4">
<label class="form-label">IGST % </label>
<input type="text" name="IGST" class="form-control" id="IGST" placeholder="" value="<?php echo $row7["IGST"]; ?>">
<div class="clearfix"></div>
</div> -->

<!-- <div class="form-group col-lg-12">
<label class="form-label">Details </label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>-->



<div class="form-group col-md-8">
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

 

<!-- <input type="hidden" id="Status" name="Status" value="1"> -->
 <div class="form-group col-md-4">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">

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
           "scrollX": true
        });
    });
</script>
</body>

</html>
