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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>
    <style type="text/css">
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
   <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'task-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

<div class="row">
                             
                            
               
                          <!-- Staustic card 2 Start -->
                      
                            
                           
                            
                        <div class="col-sm-6 col-xl-3">
                                <a href="view-warranty-registration.php">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php 
                                        $sql = "SELECT * FROM tbl_warranty_registration";
                                       echo $rncnt = getRow($sql);
                                          ?></h2>
                                        <h6 class="mb-0">Total Task</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                           
                            <div class="col-sm-6 col-xl-3">
                                <a href="warranty-customers.php">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php 
                                          $CurrDate=date('Y-m-d');
                                        $sql = "SELECT * FROM tbl_warranty_registration WHERE EndDate>='$CurrDate'";
                                       echo $rncnt = getRow($sql);
                                          ?></h2>
                                        <h6 class="mb-0">Today Task</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <a href="no-warranty-customers.php">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php 
                                          $CurrDate=date('Y-m-d');
                                        $sql = "SELECT * FROM tbl_warranty_registration WHERE EndDate<'$CurrDate'";
                                       echo $rncnt = getRow($sql);
                                          ?></h2>
                                        <h6 class="mb-0">Today Due Task</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
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