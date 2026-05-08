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

             <?php include_once 'back-header.php'; ?> 


            

              


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

                        <div class="row">

                            <div class="col-xl-12 col-md-12">
                                <div class="card ui-task mb-4">
                                    <h5 class="card-header">LEADS (Total No. Of Leads)</h5>
                                    <div class="card-body">
                                        <div class="row">
                                         <?php 
                                $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=10";
                                $row = getList($sql);
                                foreach($row as $result){

                                    $sql2 = "SELECT * FROM tbl_leads WHERE ClainReason='".$result['Name']."'";
                                    $rncnt2 = getRow($sql2);
                            ?>
                           <div class="col-sm-6 col-xl-2">
                                <a href="view-leads.php?ClainReason=<?php echo $result['Name'];?>">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php echo $rncnt2;?></h2>
                                        <h6 class="mb-0"><?php echo $result['Name'];?></h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            <?php } ?>
                            </div>

                            <div class="row">
                            <?php 
                                $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=11";
                                $row = getList($sql);
                                foreach($row as $result){

                                    $sql2 = "SELECT * FROM tbl_leads WHERE ClainStatus='".$result['Name']."'";
                                    $rncnt2 = getRow($sql2);
                            ?>
                           <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php echo $rncnt2;?></h2>
                                        <h6 class="mb-0"><?php echo $result['Name'];?></h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            <?php } ?>
                        </div>
                                    </div>
                                </div>
                            </div>

<?php  
    $sql = "SELECT * FROM tbl_social_media_marketing WHERE id=1";
    $row = getRecord($sql);
?>

<div class="col-xl-12 col-md-12">
                                <div class="card ui-task mb-4">
                                    <h5 class="card-header">Social Media Marketing</h5>
                                    <div class="card-body">
                                        <div class="row">
                                         
                           <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php echo $row['Videos'];?></h2>
                                        <h6 class="mb-0">No. of Videos Created</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php echo $row['Blogs'];?></h2>
                                        <h6 class="mb-0">No. of Blogs Created</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php echo $row['Influencers'];?></h2>
                                        <h6 class="mb-0">No. of influencers Created</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php echo $row['Creative'];?></h2>
                                        <h6 class="mb-0">No. of creative Created</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                          
                            </div>

                           
                                    </div>
                                </div>
                            </div>

                         </div>

                        
                        


</div>


                    



                <?php include_once 'footer.php'; ?>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>


</body>

</html>