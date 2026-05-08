<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Page";
$Page = "View-Upload-Photo";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Upload Pdf</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="../ckeditor/ckeditor.js"></script>
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
$sql7 = "SELECT * FROM tbl_photos WHERE id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
  $Title = addslashes(trim($_POST['Title']));
  $Details = addslashes(trim($_POST['Details']));
  $PostId = addslashes(trim($_POST['PostId']));
  $Status = addslashes(trim($_POST['Status']));
  $CreatedDate = date('Y-m-d');

  $randno2 = rand(1,100);
  $src2 = $_FILES['Photo']['tmp_name'];
  $fnm2 = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
  $fnm2 = str_replace(" ","_",$fnm2);
  $ext2 = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
  $dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
  $imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
  if(move_uploaded_file($src2, $dest2))
  {
  $Photo = $imagepath2 ;
  } 
  else{
  $Photo = $_POST['OldFile'];
  }

  if($_GET['id'] == ''){
    $sql = "INSERT INTO tbl_photos SET Photo='$Photo'";
    $conn->query($sql);
    echo "<script>alert('Photo Uploaded Successfully!');window.location.href='view-upload-photos.php';</script>";
  }
  else{
     $sql = "UPDATE tbl_photos SET Photo='$Photo' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Photo Updated Successfully!');window.location.href='view-upload-photos.php';</script>";
  }

}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Upload Photo</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
<input type="hidden" name="action" value="Save" id="action">


<div class="form-group col-md-12">
  <label class="form-label"> Upload Photo <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldFile" value="<?php echo $row7['Photo'];?>" id="OldFile">
<span class="custom-file-label"></span>
</label>

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
<script type="text/javascript">
function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Saved Successfully...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
   $(document).ready(function(){
  


$(document).on("click", "#delete_photo2", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Document?"))  
           {  
             var action = "deleteDoc";
             var id = $('#userid').val();
             var Photo = $('#OldFile').val();
             $.ajax({
    url:"ajax_files/ajax_news.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {

      $('#show_photo2').hide();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });


  
});
</script>
</body>
</html>