<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
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
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
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

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Lead Dashboard</h5>
                        

                        <div class="row">
                            <?php 
                                $sql2 = "SELECT * FROM tbl_leads";
                                $rncnt2 = getRow($sql2);
                            ?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="view-leads.php">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Leads</h6>
                                                
                                                <div class="text-large"><?php echo $rncnt2;?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php 
                                $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=10";
                                $row = getList($sql);
                                foreach($row as $result){

                                    $sql2 = "SELECT * FROM tbl_leads WHERE ClainReason='".$result['Name']."'";
                                    $rncnt2 = getRow($sql2);
                            ?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="view-leads.php?ClainReason=<?php echo $result['Name'];?>">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $result['Name'];?></h6>
                                                
                                                <div class="text-large"><?php echo $rncnt2;?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } ?>


                            <?php 
                                $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=11";
                                $row = getList($sql);
                                foreach($row as $result){

                                    $sql2 = "SELECT * FROM tbl_leads WHERE ClainStatus='".$result['Name']."'";
                                    $rncnt2 = getRow($sql2);
                            ?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="view-leads.php?ClainReason=<?php echo $result['Name'];?>">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $result['Name'];?></h6>
                                                
                                                <div class="text-large"><?php echo $rncnt2;?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } ?>

                        </div>

<?php  
    $sql = "SELECT * FROM tbl_social_media_marketing WHERE id=1";
    $row = getRecord($sql);
?>
                        <h5 class="font-weight-bold py-3 mb-0">Social Media Marketing</h5>
                        <div class="row">
                            <div class="col-sm-6 col-xl-3">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">No. of Videos Created</h6>
                                                
                                                <div class="text-large"><?php echo $row['Videos'];?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">No. of Blogs Created</h6>
                                                
                                                <div class="text-large"><?php echo $row['Blogs'];?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">No. of influencers Created</h6>
                                                
                                                <div class="text-large"><?php echo $row['Influencers'];?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="col-sm-6 col-xl-3">
                                <div class="card mb-4 bg-pattern-2-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">No. of creative Created</h6>
                                                
                                                <div class="text-large"><?php echo $row['Creative'];?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
