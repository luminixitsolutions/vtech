<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage = 'Service';
$Page = 'Service-Dashboard';
$user_id = $_SESSION['Admin']['id'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Service Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once 'service-sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once 'top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h5 class="card-header" style="text-align:center;"><?php echo strtoupper($_GET['name']); ?> SERVICE DASHBOARD</h5>
                    <div class="card-body">
                        <?php include_once 'inc-service-project-dashboard.php'; ?>
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
