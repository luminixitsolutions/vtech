<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once '../inc-masters-dashboard-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Masters';
$Page = 'Masters-Dashboard';

$row77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$Options = adminResolveMenuOptionsFromUserRow($row77);

$dash = getMastersDashboardData($Options);
$hasItems = count($dash['items']) > 0;
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Master Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/feather.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/css/masters-dashboard.css">
</head>
<body>
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'master-sidebar.php'; ?>

            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y masters-dash-page">

                        <?php if ($hasItems) { ?>

                        <div class="card masters-dash-shell">
                            <h5 class="card-header masters-dash-header">Master Management Dashboard</h5>
                            <div class="card-body masters-dash-body">
                                <div class="masters-dash-stat-grid">
                                    <?php foreach ($dash['items'] as $item) {
                                        mastersDashboardStatCard($item);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <?php } else { ?>
                        <div class="card masters-dash-shell">
                            <div class="card-body masters-dash-body">
                                <div class="masters-dash-empty">
                                    <i class="feather icon-lock" style="font-size:2rem;display:block;margin-bottom:0.75rem;"></i>
                                    No master modules are assigned to your account. Contact your administrator for access.
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $SiteUrl; ?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/material-ripple.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/demo.js"></script>
</body>
</html>
