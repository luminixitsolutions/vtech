<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage = "Service";
$Page = "Add-Service-Complaint";
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

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

                        <div class="row">

                            <div class="col-xl-12 col-md-12">
                                <div class="card ui-task mb-4">
                                    <h5 class="card-header" style="text-align:center;">Choose Service Type</h5>
                                    <div class="card-body">
                                      

                            <div class="row">
                          <div class="col-sm-6 col-xl-4"></div>
                           <div class="col-sm-6 col-xl-2">
                                <a href="add-maintaince-complaint.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Maintaince</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="add-insurance-complaint.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Insurance</h6>
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