<?php session_start();
$sessionid = session_id();
require_once 'config.php';
$PageName = "OTP Verify";
$UserId = $_SESSION['User']['id'];
 ?>
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
  if(isset($_POST['submit'])){
      $Name = addslashes(trim($_GET['name']));
       $Phone = addslashes(trim($_GET['phone']));
$CreatedDate = date('Y-m-d');
$YourOtp = addslashes(trim($_POST['YourOtp']));
$oldotp = $_GET['otp'];
$userid = $_GET['userid'];
if($YourOtp == $oldotp){
    if($_GET['val'] == 'index'){
    $sql = "INSERT INTO tbl_popup_enquiry SET SessionId='$sessionid',Name='$Name',EmailId='$EmailId',Phone='$Phone',Subject='$Subject',Message='$Message',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    echo "<script>window.location.href='index.php';</script>";
    }
    if($_GET['val'] == 'forgot'){
        echo "<script>window.location.href='new-password.php?userid=$userid';</script>";
    }
    }
else{
    $errormessage = "OTP Not Matched!";
    
}
 }

if($_GET['otp']==''){
$otp = $_SESSION['Otp'];
}
else{
unset($_SESSION['Otp']);
   $otp =  $_GET['otp'];
}
//echo $_SESSION['Otp'];
  ?>
        <div class="main-container">
            <div class="container">
                <div class="card mb-4">
                  <img src="otp.jpg"><br><br>
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
                        <div class="form-group float-label active">
                            <input type="password" name="YourOtp" id="YourOtp" class="form-control" autofocus required>
                            <label class="form-control-label">OTP</label>
                        </div>
                       
                    </div>
                    
                     <input type="hidden" id="Name" value="<?php echo $_GET['name'];?>">
                     <input type="hidden" id="Phone" value="<?php echo $_GET['phone'];?>">
                     <input type="hidden" id="OldOtp" value="<?php echo $otp;?>">
                     <input type="hidden" id="userid" value="<?php echo $_GET['userid'];?>">
                      <input type="hidden" id="PageVal" value="<?php echo $_GET['val'];?>">
                      <input type="hidden" id="city_id" value="<?php echo $_GET['city_id'];?>">
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
   
   <script>
       $(document).ready(function(){
         $(document).on("click", "#submit", function(event){
                 var action = "VerifyOtp";
                 var YourOtp = $('#YourOtp').val();
                 var Name = $('#Name').val();
                 var Phone = $('#Phone').val();
                 var OldOtp = $('#OldOtp').val();
                 var userid = $('#userid').val();
                 var PageVal = $('#PageVal').val();
                 var city_id = $('#city_id').val();
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
                data:{action:action,YourOtp:YourOtp,Name:Name,Phone:Phone,OldOtp:OldOtp,userid:userid,PageVal:PageVal},
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){
                    console.log(data);
                    if(data == 1){
                    //Android.setUserPopup();
                        $('#success_message').css('display','block').html("Verity OTP!");
                      setTimeout(function(){  
                        $('#success_message').fadeOut("Slow");
                        if(PageVal == 'index'){
                         window.location.href="index.php";    
                        }
                        if(PageVal == 'forgot'){
                         window.location.href="new-password.php?userid="+userid;    
                        }
                        if(PageVal == 'register'){
                         window.location.href="home.php?city_id="+city_id;    
                        }
                      }, 2000); 
                    }
                    else{
                      $('#danger_message').css('display','block').html("OTP Not Matched!");
                    $('#YourOtp').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
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
   </script>
</body>

</html>
