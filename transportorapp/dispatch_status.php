<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main"  style="background-color:#405189;">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
        <?php 
            //total
            $sql = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='$user_id'";
            $rncnt = getRow($sql);
            
            //pending
            $sql2 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='$user_id' AND tdo.Inst_Dispatcher_Otp_Verify=0";
            $rncnt2 = getRow($sql2);
            
            //done
            $sql3 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='$user_id' AND tdo.Inst_Dispatcher_Otp_Verify=1";
            $rncnt3 = getRow($sql3);
             ?>

        <div class="main-container" style="background-color:#405189;">

            <div class="container">
                
            <!-- page content start -->
        <div class="container mt-3  text-center text-white">
            <h2 style="font-size: 50px;"><?php echo $rncnt;?></h2>
            <p class="mb-4" style="text-transform: uppercase;">Total Dispatch</p>
        
        </div>
        <div class="main-container"  style="background-color:#f0f3f7;">
            <div class="container ">
                <a href="dispatch.php?val=0">
                <div class="card border-0 mb-3"   style="border-radius: 20px;">
                    <div class="card-header">
                        <div class="row align-items-center">
                            
                            <div class="col align-self-center pr-0">
                                <h6 class="mb-0" style="text-align: center;text-transform: uppercase;">Pending Dispatch</h6>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <h1 class=" text-center mb-0 display-4" style="color: red;font-size:3rem;"><?php echo $rncnt2;?></h1>
                    </div>
                    
                </div>   
</a>
                 <a href="dispatch.php?val=1">
                <div class="card border-0 "   style="border-radius: 20px;">
                    <div class="card-header" >
                        <div class="row align-items-center">
                            
                            <div class="col align-self-center pr-0">
                                <h6 class="mb-0" style="text-align: center;text-transform: uppercase;">Completed Dispatch</h6>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <h1 class=" text-center mb-0 display-4"  style="color: green;font-size:3rem;"><?php echo $rncnt3;?></h1>
                    </div>
                    
                </div>
                </a>
               
                

            
            </div>
        </div>
    </main>
  




</div>

</main>
<br><br>
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

</body>
</html>
