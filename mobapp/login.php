<?php session_start();
require_once 'config.php';
$PageName = "Login";

$uid = $_REQUEST['uid'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$rncnt11 = getRow($sql11);
if($rncnt11 > 0){
    $row = getRecord($sql11);
    $_SESSION['User'] = $row;
} 
if(isset($_SESSION['User'])){
    echo "<script>window.location.href='home.php';</script>";
}

$UserId = 0; ?>
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
    <link href="css/toastr.min.css" rel="stylesheet" id="style">

</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <!-- screen loader -->
    



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer" style="background-color:#fff;">
       
        
        
        <div class="container h-100 text-white">
            <div class="row h-100">
                <div class="col-12 align-self-center mb-4">
                    <div class="row justify-content-center">
                        <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4" align="center">
                            <img src="logo2.png"><br><br>
                          
                            <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                           <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>
                            <div class="form-group float-label position-relative active">
                                <input style="color:#000;" type="text" class="form-control  active" id="Username" value="">
                                <label class="form-control-label  " style="color:#000;">Phone No</label>
                            </div>
                           <!--  <div class="form-group float-label position-relative active">
                                <input style="color:#000;" type="password" class="form-control " id="Password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>">
                                <label class="form-control-label " style="color:#000;">Password</label>
                            </div>  --> 
                            <p class="text-right"><a href="forgot-password.php">Forgot Password?</a></p>
                             <input type="hidden" id="pageid" value="<?php echo $_GET['page']; ?>">
                              <input type="hidden" id="city_id" value="<?php echo $_GET['city_id']; ?>">
                           
                        </div>
                        
                        <div class=" no-bg-shadow py-2" style="width:80%;">
        <div class="row justify-content-center">
            <div class="col">
                <button class="btn btn-default rounded btn-block" id="login">Login</button>
            </div>
        </div>
    </div>
    
    <div class=" no-bg-shadow " style="width:80%;">
        <div class="row justify-content-center">
            <div class="col" align="center" style="color:#000;">
                OR
            </div>
        </div>
    </div>
    
    <div class=" no-bg-shadow py-2" style="width:80%;">
        <div class="row justify-content-center">
            <div class="col">
                <a href="register.php"><button class="btn btn-primary rounded btn-block">Register</button></a>
            </div>
        </div>
    </div>
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
    <script src="js/toastr.min.js"></script>


    
   <script type="text/javascript">
         $(document).ready(function(){
         $(document).on("click", "#login", function(event){
                 var action = "Login";
                var Username = $('#Username').val();
                 var Password = $('#Password').val();
                 var pageid = $('#pageid').val();
                 var prdid = $('#prdid').val();
                 var catid = $('#catid').val();
                 var city_id = $('#city_id').val();
                 if(Username.trim() == ''){
                    $('#danger_message').css('display','block').html("Please Enter Username");
                    $('#Username').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                }
                /*else if(Password.trim() == ''){
                     $('#danger_message').css('display','block').html("Please Enter Password");
                    $('#Password').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                }*/
                else{
                     $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:{action:action,Username:Username,Password:Password,city_id:city_id},
                 beforeSend:function(){
     $('#login').attr('disabled','disabled');
     $('#login').text('Please Wait...');
    },
                success:function(data){ 
                    //alert(data);
                    var res = JSON.parse(data);
                    var status = res.status;
                    var roll = res.roll;
                    var Username = res.Username;
                    var uid = res.uid;
                     if(status == 0){
                         $('#danger_message').css('display','block').html("Invalid Login Details");
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                     }
                     else{
                         //Android.loginUser(Username,uid);
                        $('#success_message').css('display','block').html("Verity OTP!");
                      setTimeout(function(){  
                        $('#success_message').fadeOut("Slow");
                        window.location.href="login-otp-verify.php?pageid="+pageid+"&roll="+roll+"&city_id="+city_id+"&uid="+uid+"&Username="+Username;
                       /* if(pageid == 'checkout'){
                        window.location.href="checkout.php";
                     }
                     else if(pageid == 'checkout-hall'){
                        window.location.href="checkout-hall.php";
                     }
                     else if(pageid == 'wishlist'){
                        window.location.href="shop-list.php?cat_id="+catid;
                     }
                     else if(pageid == 'quick_order'){
                        window.location.href="quick-order.php";
                     }
                      else if(pageid == 'recharge'){
                        window.location.href="recharge-category.php";
                     }
                     else if(pageid == 'price'){
                        window.location.href="pricing.php";
                     }
                     else{
                      if(roll == 3){
                         window.location.href="exe-profile.php";
                        
                      }
                      else if(roll == 1){
                         window.location.href="../admin/dashboard.php";
                        
                      }
                      else if(roll == 2){
                         window.location.href="../admin/dashboard.php";
                        
                      }
                      else if(roll == 9){
                         window.location.href="../manager/dashboard.php";
                        
                      }
                      else{
                       window.location.href="profile.php";
                      }
                    }*/
                    }, 2000); 
                     }
                    $('#login').attr('disabled',false);
                    $('#login').text('Login');
                }
                });
                }

              }); 
         }); 
          </script>
</body>

</html>
