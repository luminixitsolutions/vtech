<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Quotation";
$Page = "Quotation-Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">

</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'master-sidebar.php'; ?>

                <div class="layout-container">

                <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Add Quotation Product</h5>
                        
 <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_rooftop_qtn_products WHERE id='$id'";
$row7 = getRecord($sql7);
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
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
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
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
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
     $qx = "INSERT INTO tbl_rooftop_qtn_products SET CGST='$CGST',SGST='$SGST',IGST='$IGST',Photo2='$Photo2',ProductCode='$ProductCode',BrandId='$BrandId',CatId='$CatId',ProductName = '$ProductName',Status='$Status',ModelNo='$ModelNo',Photo='$Photo',Price = '$Price',Details='$Details',CreatedDate='$CreatedDate',CreatedBy='$user_id',Code='$Code',Unit='$Unit',Roll='$Roll'";
  $conn->query($qx);
  $id = mysqli_insert_id($conn);
  $PrdNo = "1000".$id;
  $sql = "UPDATE tbl_rooftop_qtn_products SET PrdNo='$PrdNo' WHERE id='$id'";
  $conn->query($sql);
  echo "<script>alert('Product Added Successfully!');window.location.href='view-quotation-products.php';</script>";
}
else{
 
    $query2 = "UPDATE tbl_rooftop_qtn_products SET CGST='$CGST',SGST='$SGST',IGST='$IGST',Photo2='$Photo2',ProductCode='$ProductCode',BrandId='$BrandId',CatId='$CatId',ProductName = '$ProductName',Status='$Status',ModelNo='$ModelNo',Photo='$Photo',Price = '$Price',Details='$Details',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',Code='$Code',Unit='$Unit',Roll='$Roll' WHERE id = '$id'";
  $conn->query($query2);
  echo "<script>alert('Product Updated Successfully!');window.location.href='view-quotation-products.php';</script>";

}

  }
?>

                <div class="card mb-4">
                    <div class="card-body">
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


<div class="form-group col-lg-2">
<label class="form-label">GST % </label>
<input type="text" name="CGST" class="form-control" id="CGST" placeholder="" value="<?php echo $row7["CGST"]; ?>">
<div class="clearfix"></div>
</div>

<input type="hidden" name="SGST" class="form-control" id="SGST" placeholder="" value="0" required>
<input type="hidden" name="IGST" class="form-control" id="IGST" placeholder="" value="0">



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
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
</body>

</html>
