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

            <?php include_once 'master-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Master Dashboard</h5>
                        
                        <div class="row">
                             
                             <?php if(in_array("2", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="branches.php">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_branch";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Store</h6>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("3", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="issues.php">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_issues";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Issues</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("4", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="scheme.php">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_scheme";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Scheme</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("5", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="user-type.php">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_user_type";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">User Type</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("6", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=1">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=1";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Pump Head</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("7", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=2">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Pump Capacity</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("8", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=3">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=3";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Water Source</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("9", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=4">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=4";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Surface</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("12", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=7">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=7";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Bore Dia</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
                        
<?php } if(in_array("13", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=8">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=8";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Customer Type</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("16", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=10">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=10";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Lead Source</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("16", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=11">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=11";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Lead Status</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("34", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=9">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=9";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Insurance Agency</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("15", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=5">
                               <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=5";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Insurance Claim Reason</h6>
                                        
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("16", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="common-master.php?pageid=6">
                                <div class="card mb-4 bg-pattern-2-dark">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=6";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0" style="color: black;">Insurance Claim Status</h6>
                                        
                                     </div>
                                </div></a>
                            </div>

                            <?php } ?>
                         
                            
                            
                            
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
