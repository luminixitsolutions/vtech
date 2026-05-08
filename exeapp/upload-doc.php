<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['User']['id'];
$MainPage="Blogs";
$Page = "Add-Blogs";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Courses</title>
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

<?php //include_once 'sidebar.php'; ?>




<?php //include_once 'top_header.php'; ?>


<?php 

if($_REQUEST["action"]=="deletelink")
{
  $id = $_REQUEST["id"];
  $pid = $_REQUEST["leadid"];
  $sql11 = "DELETE FROM tbl_stud_docs WHERE id = '$id'";
  $conn->query($sql11);
?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
       window.location.href="upload-doc.php?id=<?php echo $pid;?>";
       window.opener.location.reload(true);
    </script>
<?php }

  if(isset($_POST['submit'])){
    $LeadId = $_POST['LeadId'];
    if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        $Title = addslashes(trim($_POST['Title'][$key]));
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_stud_docs SET UserId='$LeadId',Files='$file_name',Title='$Title'";
            $desired_dir = "../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}?>
<script type="text/javascript">
  alert("Documents Uploaded Successfully!");
   window.close();
</script>
<?php

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Upload Documents</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">

<input type="hidden" name="LeadId" value="<?php echo $_GET['id'];?>">
  <?php 
$sql_1 = "SELECT * FROM tbl_stud_docs WHERE UserId='".$_GET['id']."'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<div class="form-row">
  <div class="form-group col-md-6">
<label class="form-label">Title </label>
<input type="text" class="form-control" placeholder="e.g.,Earthquake Causes And Effects" value="<?php echo $result["Title"]; ?>" autocomplete="off" readonly>
</div>
<div class="form-group col-md-6">
<label class="form-label">Attach File <span class="text-danger">*</span></label>
<div class="input-group">
     <label class="custom-file">
<a href="../uploads/<?php echo $result['Files'];?>" target="_blank"><?php echo $result['Files'];?></a>
</label>
<div class="clearfix"></div>
<span class="input-group-append">
 <a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['id']; ?>&action=deletelink&leadid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
  </span>
</div>
<br>
  <input type="hidden" name="OldFiles[]" id="OldFiles<?php echo $result["id"]; ?>" value="<?php echo $result["Files"]; ?>">
</div>
</div>

<?php } ?>

 <div id="dynamic_field">
  <div class="form-row">
  <div class="form-group col-md-6">
<label class="form-label">Title </label>
<input type="text" name="Title[]" class="form-control" placeholder="e.g.,Aadhar Card,Pan Card" value="" autocomplete="off" >
</div>
<div class="form-group col-md-6">
<label class="form-label">Attach File <span class="text-danger">*</span></label>
<div class="input-group">
    <label class="custom-file">
<input type="file" class="custom-file-input" id="Files" name="Files[]" style="opacity: 1;">
</label>
<div class="clearfix"></div>
<span class="input-group-append">
    <button class="btn btn-secondary" type="button" id="add_more"><i class="fa fa-plus"></i></button>
  </span>
</div>
</div>
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

$(document).ready(function() {
        var i=1;  
          $('#add_more').click(function(){  
           i++;  
           var html = '';
           html+='<div class="form-row" id="row'+i+'">'; 
           html+=' <div class="form-group col-md-6">'; 
 html+='<label class="form-label">Title </label>'; 
 html+='<input type="text" name="Title[]" class="form-control" placeholder="e.g.,Aadhar Card,Pan Card" value="" autocomplete="off" >'; 
 html+='</div>'; 
 html+='<div class="form-group col-md-6">'; 
 html+='<label class="form-label">Attach File <span class="text-danger">*</span></label>'; 
 html+='<div class="input-group">    <label class="custom-file">'; 
   html+='<input type="file" class="custom-file-input" id="Files" name="Files[]" style="opacity: 1;"></label>'; 
 html+='<div class="clearfix"></div>'; 
 html+='<span class="input-group-append">'; 
     html+='<button class="btn btn-danger btn_remove" type="button" id="'+i+'"><i class="fa fa-times"></i></button>'; 
   html+='</span>'; 
 html+='</div>'; 
 html+='</div>';
 html+='</div>'; 
           $('#dynamic_field').append(html);
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