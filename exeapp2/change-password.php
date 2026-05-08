<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['User']['id'];
$MainPage="Account";
$Page = "Change-Password";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Change Password</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />

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










<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Change Password</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Account Settings</li>
<li class="breadcrumb-item active">Change Password</li>
</ol>
</div>

<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post">
<div class="form-row">
  

<div class="form-group col-md-6">
<label class="form-label">Old Password</label>
<input type="password" name="OldPassword" id="OldPassword" class="form-control" placeholder="Old Password">
<span class="password-tog-info show" onclick="myFunction()"><i class="fa fa-eye" aria-hidden="true"></i></span>
</div>
<div class="form-group col-md-6"></div>
<div class="form-group col-md-6">
<label class="form-label">New Password</label>
<input type="password" name="AdminPassword" id="Password" class="form-control" placeholder="New Password">
<span class="password-tog-info show2" onclick="myFunction2()"><i class="fa fa-eye" aria-hidden="true"></i></span>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6"></div>
<div class="form-group col-md-6">
<label class="form-label">Confirm Password</label>
<input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-control" placeholder="Confirm Password">
<span class="password-tog-info show3" onclick="myFunction3()"><i class="fa fa-eye" aria-hidden="true"></i></span>
<div class="clearfix"></div>
</div>
</div>
<button type="submit" class="btn btn-primary btn-finish">Change Password</button>
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
       <?php include_once 'footer_script.php'; ?><script type="text/javascript">
	function myFunction() {

  var x = document.getElementById("OldPassword");
  if (x.type === "password") {
    x.type = "text";
      $('.show').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
    x.type = "password";
      $('.show').html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
}
function myFunction2() {

  var x = document.getElementById("Password");
  if (x.type === "password") {
    x.type = "text";
      $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
    x.type = "password";
      $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
}

function myFunction3() {

  var x = document.getElementById("ConfirmPassword");
  if (x.type === "password") {
    x.type = "text";
      $('.show3').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
    x.type = "password";
      $('.show3').html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
}
</script>
<script type="text/javascript">
	 $(document).ready(function(){
	 	//$(document).on("click", ".btn-finish", function(event){
	 		$('#validation-form').on('submit', function(e){
	 		e.preventDefault();    
    if ($('#validation-form').valid()){ 
    	   $.ajax({  
                url :"ajax_files/ajax_change_password.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                success:function(data){ 
                     if(data == 0){
                     	$('#alert_message').removeClass('alert alert-success');
                     	$('#alert_message').fadeIn().addClass('alert alert-danger').html("Old Password Not Match");
        setTimeout(function(){  
            $('#alert_message').fadeOut("Slow"); 
        }, 2000);
                     }
                     else{
                     	$('#alert_message').removeClass('alert alert-danger');
                     	$('#alert_message').fadeIn().addClass('alert alert-success').html("Password Changed Successfull...");
        setTimeout(function(){  
            $('#alert_message').fadeOut("Slow"); 
             window.location.href = 'dashboard.php';
        }, 2000);
                     }
                }  
           })  



    }
else{
    return false;
}
  });
	   });
</script>
</body>
</html>
