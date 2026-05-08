<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
 <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <!-- Core stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">
    <!-- Libs -->
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <!-- Page -->
    <link rel="stylesheet" href="assets/css/pages/authentication.css">
    <link rel="stylesheet" href="assets/libs/growl/growl.css">
    <link rel="stylesheet" href="assets/libs/toastr/toastr.css">

</head>

<body>
  
    <!-- [ Preloader ] End -->
    <!-- [ content ] Start -->
    <div class="authentication-wrapper authentication-3">
        <div class="authentication-inner">

            <!-- [ Side container ] Start -->
            <!-- Do not display the container on extra small, small and medium screens -->
            <div class="d-none d-lg-flex col-lg-8 align-items-center ui-bg-cover ui-bg-overlay-container p-5" style="background-image:url(slider_1.jpg);">
                <div class="ui-bg-overlay bg-dark opacity-50"></div>
                <!-- [ Text ] Start -->
                <div class="w-100 text-white px-5">
                    <div class="w-100 text-white px-5">
                    <h1 class="display-2 font-weight-bolder mb-4">VTECH SUNSYSTEMS PVT. LTD.</h1>
                    
                </div>
                        
                </div>
                <!-- [ Text ] End -->
            </div>
            <!-- [ Side container ] End -->

            <!-- [ Form container ] Start -->
            <div class="d-flex col-lg-4 align-items-center bg-white p-5">
                <!-- Inner container -->
                <!-- Have to add `.d-flex` to control width via `.col-*` classes -->
                <div class="d-flex col-sm-7 col-md-5 col-lg-12 px-0 px-xl-4 mx-auto">
                    <div class="w-100">

                        <!-- [ Logo ] Start -->
                        <div class=" justify-content-center align-items-center">
                            <div>
                                <div class=" position-relative" align="center">
                                    <img src="logo.jpg" alt="Brand Logo" width="250px" >
                                    
                                </div>
                            </div>
                        </div>
                        <!-- [ Logo ] End -->

                        <h4 class="text-center  font-weight-normal mt-3 mb-0">Sign in to your accounts</h4>
<br>
                        <!-- [ Form ] Start -->
                        <form id="validation-form" method="post">
<div class="form-group">
<label class="form-label">Username</label>
<input type="text" name="Username" class="form-control" required="">
<div class="clearfix"></div>
</div>
<div class="form-group" style="padding-top:10px;">
<label class="form-label d-flex justify-content-between align-items-end">
<span>Password</span>
</label>
<input type="password" name="Password" class="form-control" required="">
<div class="clearfix"></div>
</div>



<div class="d-flex justify-content-between align-items-center m-0" align="center" style="padding-top:10px;">
<button type="submit" id="submit" class="btn btn-primary">Sign In</button>
<!--<a href="emp-login.php" class="btn btn-primary">Employee Login</a>-->
<!-- <a href="sign-up.php" class="btn btn-success">Sign Up</a> -->
</div>
</form>
                        <!-- [ Form ] End -->

                       <!--  <div class="text-center text-muted">
                            Don't have an account yet?
                            <a href="pages_authentication_register-v3.html">Sign Up</a>
                        </div> -->

                    </div>
                </div>
            </div>
            <!-- [ Form container ] End -->

        </div>
    </div>
    <!-- [ content ] End -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/pace.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>
    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/validate/validate.js"></script>
    <script src="assets/libs/select2/select2.js"></script>
     <script src="assets/libs/growl/growl.js"></script>
    <script src="assets/libs/toastr/toastr.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script>
    <script src="assets/js/analytics.js"></script>
    <script src="assets/js/pages/forms_validation.js"></script>
    <script src="assets/js/pages/ui_notifications.js"></script>
    <script src="assets/js/pages/forms_selects.js"></script>

<script type="text/javascript">
function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Invalid Username / Password',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.notice({
      title:    'Success',
      message:  'Login Successfull! Please Wait...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
     $(document).ready(function(){
            $('#validation-form').on('submit', function(e){
            e.preventDefault();    
    if ($('#validation-form').valid()){ 
           $.ajax({  
                url :"ajax_files/ajax_login.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  //alert(data);
                  res = JSON.parse(data);
                  Status = res.Status;
                  Roll = res.Roll;
                     if(Status == 1){
                         success_toast();
                     setTimeout(function(){  
                   
                 window.location.href = 'dashboard.php'; 
                 
                    }, 2000);
                     
                     }
                     else{
                    error_toast();
                  
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Sign In');
                }  
           })  



    }
else{
  //$('#Fname').focus();
    return false;
}
  });

       });
</script>

</body>

</html>
