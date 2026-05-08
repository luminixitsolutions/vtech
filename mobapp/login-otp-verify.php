<?php session_start();
$sessionid = session_id();
require_once 'config.php';
$PageName = "OTP Verify";
$UserId = $_SESSION['User']['id'];
if(!isset($_SESSION['otp'])){
    //echo "<script>window.location.href='profile.php';</script>";
}
 ?>

<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?> - <?php echo $_SESSION['otp'];?></title>

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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php //include_once 'back-header.php'; ?> 
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
        
          <?php
          //echo $_SESSION['otp'];
  if(isset($_POST['submit'])){
$CreatedDate = date('Y-m-d');
$YourOtp = addslashes(trim($_POST['YourOtp']));
$oldotp = $_SESSION['otp'];
$userid = $_GET['userid'];
$pageid = $_GET['pageid'];
$roll = $_GET['roll'];
$city_id = $_GET['city_id'];
if($YourOtp == $oldotp){
    $successmessage = "OTP Matched Successfully!";
    
    unset($_SESSION['otp']);      
    if($pageid == 'checkout'){
        echo "<script>window.location.href='checkout.php';</script>";
    }
    else if($pageid == 'checkout-hall'){
        echo "<script>window.location.href='checkout-hall.php';</script>";
    }
    else if($pageid == 'wishlist'){
        echo "<script>window.location.href='shop-list.php?cat_id='+catid;</script>";
    }
    else if($pageid == 'quick_order'){
        echo "<script>window.location.href='quick-order.php';</script>";
    }
    else if($pageid == 'recharge'){
        echo "<script>window.location.href='recharge-category.php';</script>";
    }
    else if($pageid == 'price'){
        echo "<script>window.location.href='pricing.php';</script>";
    }
    else{
        if($roll == 3){
            echo "<script>window.location.href='exe-profile.php';</script>";
        }
        else if($roll == 1){
            echo "<script>window.location.href='../admin/dashboard.php';</script>";
        }
        else if($roll == 2){
            echo "<script>window.location.href='../admin/dashboard.php';</script>";
        }
        else if($roll == 9){
            echo "<script>window.location.href='../manager/dashboard.php';</script>";
        }
        else{
            echo "<script>window.location.href='home.php?city_id=$city_id';</script>";
        }
    }
    }
else{
    $errormessage = "OTP Not Matched!";
    
}
 } ?>
        <div class="main-container">
            <div class="container">
                <div class="card mb-4" align="center">
                    <div align="center">
                  <img width="250px" src="otp.jpg">
                  </div>
                  <br><br>
                    <div class="card-header">
                        <h6 class="subtitle mb-0">
                            <div class="avatar avatar-40 bg-default-light text-default rounded mr-2"><span class="material-icons vm">lock</span></div>
                           OTP Verification
                        </h6>
                    </div>
                    
                       <form id="validation-form" method="post" autocomplete="off">
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                        <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>
                        <input type="hidden" name="pageid" id="pageid" value="<?php echo $_REQUEST['pageid'];?>">
                        <input type="hidden" name="city_id" id="city_id" value="<?php echo $_REQUEST['city_id'];?>">
                        <input type="hidden" name="uid" id="uid" value="<?php echo $_REQUEST['uid'];?>">
                        <input type="hidden" name="Username" id="Username" value="<?php echo $_REQUEST['Username'];?>">
                        <input type="hidden" name="getotp" id="getotp" value="<?php echo $_SESSION['otp'];?>">
                        <div class="form-group float-label active">
                            <input type="password" name="YourOtp" id="YourOtp" class="form-control" autofocus required value="<?php echo $_SESSION['otp'];?>">
                            <label class="form-control-label">OTP</label>
                        </div>
                       
                    </div>
                    
                     
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="button" id="submit" name="submit">Verify</button>
                    </div>
                </form>
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
    
     <script type="text/javascript">
         $(document).ready(function(){
         $(document).on("click", "#submit", function(event){
                var action = "OtpVerify";
                var Username = $('#Username').val();
                var uid = $('#uid').val();
                var pageid = $('#pageid').val();
                var city_id = $('#city_id').val();
                var getotp = $('#getotp').val();
                var YourOtp = $('#YourOtp').val();
                if(YourOtp.trim() == ''){
                    $('#danger_message').css('display','block').html("Please Enter OTP");
                    $('#YourOtp').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                }
                else{
                     $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:{action:action,Username:Username,uid:uid,city_id:city_id,getotp:getotp,YourOtp:YourOtp},
                 beforeSend:function(){
                 $('#submit').attr('disabled','disabled');
                 $('#submit').text('Please Wait...');
                },
                success:function(data){ 
                    //alert(data);
                     var res = JSON.parse(data);
                    var status = res.status;
                    var roll = res.roll;
                    var Username = res.Username;
                    var uid = res.uid;
                     if(status == 0){
                         $('#danger_message').css('display','block').html("Invalid OTP");
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                     }
                     else{
                         Android.loginUser(Username,uid);
                        $('#success_message').css('display','block').html("OTP Verified!");
                        setTimeout(function(){  
                        $('#success_message').fadeOut("Slow");
                        window.location.href="home.php";
                    
                    }, 2000); 
                     }
                    $('#submit').attr('disabled',false);
                    $('#submit').text('Verify');
                }
                });
                }
                 }); 
                
         }); 
          </script>
</body>

</html>
