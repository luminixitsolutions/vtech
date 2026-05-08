<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
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
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
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
    <style type="text/css">
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
   <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            <?php include_once 'master-sidebar.php'; ?>


            

              


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

<div class="row">
                             
                             <?php if(in_array("2", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="branches.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_branch";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Store</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("3", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="issues.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_issues";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Issues</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("4", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="scheme.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_scheme";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Scheme</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("5", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="user-type.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_user_type";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">User Type</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("6", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=1">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=1";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Pump Head</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("7", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=2">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Pump Capacity</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("8", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=3">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=3";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Water Source</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("9", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=4">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=4";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Surface</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("12", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=7">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=7";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Bio Dia</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                        
<?php } if(in_array("13", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=8">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=8";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Customer Type</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("34", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=9">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=9";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Insurance Agency</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("15", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=5">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=5";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Insurance Claim Reason</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("16", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=6">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=6";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Insurance Claim Status</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("16", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=10">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=10";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Lead Source</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
<?php } if(in_array("16", $Options)) {?>
                            <div class="col-sm-6 col-xl-3">
                                <a href="common-master.php?pageid=11">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=11";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Lead Status</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            <?php } ?>
                         
                            
                            
                            
                    </div>
                        


</div>


                    



                <?php include_once 'footer.php'; ?>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>
    
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    

</body>

</html>