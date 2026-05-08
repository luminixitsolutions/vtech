<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
   <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'account-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Account Dashboard</h5>
                        
                        <div class="row">
                             
                            
                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll=5 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Customers</h6>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll=3";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Manufacture</h6>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll=10";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Company</h6>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll NOT IN(1,3,4,5,9,10,8,11)";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Employee</h6>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll=9";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Dealer</h6>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll=11";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Agency</h6>
                                     </div>
                                </div></a>
                            </div>

                            
                            
                            
                    </div>
                        

                        

					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
</body>

</html>
