<?php session_start();
require_once 'config.php';
$PageName = "Forgot Password";?>


<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Finwallapp - Mobile HTML template</title>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column  menu-overlay">
    <!-- screen loader -->





    <!-- Begin page content -->
     <main class="flex-shrink-0 main has-footer" style="background-color:#fff;">
        <!-- Fixed navbar -->
        <header class="header">
            <div class="row">
                <div class="col-auto px-0">
                    <button class="btn btn-40 btn-link back-btn" type="button">
                        <span class="material-icons">keyboard_arrow_left</span>
                    </button>
                </div>
                <div class="text-left  align-self-center">
                    <a class="navbar-brand" href="#">
                        <h5 class="mb-0"><?php echo $PageName; ?></h5>
                    </a>
                      
                </div>
              
            </div>
        </header>
            

<style>
    .sweet-alert h2 {
      font-size: 18px;  
    }
</style>       
<?php
if(isset($_POST['submit'])){
    $EmailId = $_POST['EmailId'];
$sql = "SELECT * FROM customers WHERE (EmailId='$EmailId' OR Phone='$EmailId')";
$row = getRecord($sql);
$rncnt = getRow($sql);
if($rncnt > 0){
    $CustId = $row['id'];
    $Phone = $row['Phone'];
    $Name = $row['Fname'];
    $EmailId = $row['EmailId'];
    $Password = $row['Password'];
    $MemberId = $row['MemberId'];
    $otp = rand(1000,9999);
    include 'incsmsfile.php';
 ?>
    <script type="text/javascript">
        //alert("Enquiry Sent Successfully!");
        window.location.href="otp-verify.php?name=<?php echo $Name;?>&phone=<?php echo $Phone;?>&otp=<?php echo $otp;?>&val=forgot&userid=<?php echo $CustId;?>";
    </script>
<?php
   /* include '../email_files/incsmscode.php';
    $to = $EmailId;
include '../email_files/incforgotmail.php';
include '../email_files/sendmailsmtp.php';*/

?>
<?php }
else{?>
<script type="text/javascript">
    setTimeout(function () { 
swal({
  title: "Phone No Not Registered with us!",
  text: "",
  type: "error",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
   window.location.href = "forgot-password.php";
  }
}); });</script>
<?php

}
}

?>        
<br><br><br>
        <form method="post">
            
            <div class="container h-100 text-white">
            <div class="row h-100">
                <div class="col-12 align-self-center mb-4">
                    <div class="row justify-content-center">
                        <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4" align="center">
                            <img src="logo2.png"><br><br>
                            <h2 class="font-weight-normal mb-5" style="color:#000;font-size:17px;text-align:center;">Daily Door Service<br> <span style="font-size:11px;">Online Grocery App</span></h2>
                            <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                           <!-- <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>-->
                            <div class="form-group float-label position-relative active">
                                <input style="color:#000;" type="text" class="form-control  active" name="EmailId" id="EmailId" value="">
                                <label class="form-control-label  " style="color:#000;">Phone No</label>
                            </div>
                         
                        
                        </div>
                        
                        <div class=" no-bg-shadow py-2" style="width:80%;">
        <div class="row justify-content-center">
            <div class="col">
                <button class="btn btn-default rounded btn-block" type="submit" name="submit">Reset Password</button>
            </div>
        </div>
    </div>
    
         </div>
                </div>
                 
                
            </div>
        </div>
    
</form>
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
    
</body>

</html>
