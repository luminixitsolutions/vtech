<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-employee-project-access.php';
$MainPage="Dashboard";
$Page = "Dashboard";
$user_id = $_SESSION['Admin']['id'];
$sql77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$projectId = isset($_GET['prjid']) ? (int) $_GET['prjid'] : 0;
$subHeadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
employeeProjectAccessEnforceProject($sql77, $projectId, 'installation-project-dashboard.php');
employeeProjectAccessEnforceSubHead($sql77, $subHeadId, 'installation-project-dashboard.php');
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

            <?php include_once 'installation-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>
<h5 class="card-header" style="text-align:center;"><?php echo strtoupper($_GET['name']);?> PROJECT DASHBOARD</h5>
                                    <div class="card-body">
                                      
<?php
$dashboardProjectId = (int) ($_GET['prjid'] ?? 0);
if ($dashboardProjectId === 102) {
    include_once 'inc-meda-project-dashboard.php';
} elseif ($dashboardProjectId === 103) {
    include_once 'inc-msedl-project-dashboard.php';
} elseif ($dashboardProjectId === 107) {
    include_once 'inc-creda-project-dashboard.php';
} elseif ($dashboardProjectId === 106) {
    include_once 'inc-mpuvnl-project-dashboard.php';
} else {
    include_once 'inc-pump-project-dashboard.php';
}
?>
                           
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