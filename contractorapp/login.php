<?php require_once 'config.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="theme-color" content="#2196f3">
    <meta name="author" content="DexignZone" /> 
    <meta name="keywords" content="" /> 
    <meta name="robots" content="" /> 
    <meta name="description" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )"/>
    <meta property="og:title" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:description" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:image" content="social-image.png"/>
    <meta name="format-detection" content="telephone=no">
    
    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="login/assets/images/favicon.png" />
    
    <!-- Title -->

    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="login/assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="login/assets/css/style.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&amp;family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="css/toastr.min.css" rel="stylesheet" id="style">

</head>   
<body class="gradiant-bg" style="background-color:#fff;">
<div class="page-wraper" style="background-color:#fff;">
    


    <!-- Welcome Start -->
    <div class="content-body" style="background-color:#fff;">
        <div class="container vh-100">
            <div class="welcome-area">
                <div class="bg-image bg-image-overlay" style="background-image: url(login.jpg);height:100vh;"></div>
                <div class="join-area">
                    <div class="started">
                       <!-- <img style="width:100px;" src="logo33.png">-->
                        <h1 class="title">Sign in</h1>
                        <p>Please fill your phone number </p>
                    </div>
                    <form>
                         <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                         <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>
                        <div class="mb-3 input-group input-group-icon">
                            
                        <input style="color:#000;" type="number" class="form-control  active" id="Username" value="">
                        </div>

                        <!--<div class="mb-3 input-group input-group-icon">
                            
                        <input style="color:#000;" type="password" class="form-control  active" id="Password" value="">
                        </div>-->
                        <input type="hidden" id="pageid" value="<?php echo $_GET['page']; ?>">
                    </form>
                        
                        
                    <button class="btn btn-primary btn-block mb-3" id="login">OTP Verify</button>
                    
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="javascript:void(0);" class="text-light text-center d-block">Don’t have an account?</a>
                        <a href="register.php" class="btn-link d-block ms-3 text-underline">Signup here</a>
                    </div>
                <br>
            <!--    <div class="d-flex align-items-center justify-content-center" style="float: middle;">
                    
                        <a href="forgot-password.php" class="btn-link d-block ms-3 text-underline">Forgot Password</a>
                        
                        
                    </div>-->
                
                </div>
                
            </div>
        </div>
    </div>
    <!-- Welcome End -->

    
</div>
<!--**********************************
    Scripts
***********************************-->
<script src="login/assets/js/jquery.js"></script>
<script src="login/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="login/assets/vendor/swiper/swiper-bundle.min.js"></script><!-- Swiper -->
<script src="login/assets/js/dz.carousel.js"></script><!-- Swiper -->
<script src="login/assets/js/settings.js"></script>
<script src="login/assets/js/custom.js"></script>
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