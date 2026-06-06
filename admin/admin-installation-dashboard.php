<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-installation-dashboard-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Installation';
$Page = 'Installation-Dashboard';

$row77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$Options = adminResolveMenuOptionsFromUserRow($row77);

$dash = getInstallationDashboardData($Options);
$hasItems = count($dash['items']) > 0;
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Installation Workflow Dashboard</title>
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="css/installation-dashboard.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y inst-dash-page">

<?php if ($hasItems) { ?>

<div class="card inst-dash-shell">
    <h5 class="card-header inst-dash-header">Installation Workflow Dashboard</h5>
    <div class="card-body inst-dash-body">
        <div class="inst-dash-stat-grid">
            <?php foreach ($dash['items'] as $item) {
                installationDashboardStatCard($item);
            } ?>
        </div>
    </div>
</div>

<?php } else { ?>
<div class="card inst-dash-shell">
    <div class="card-body inst-dash-body">
        <div class="inst-dash-empty">
            <i class="feather icon-lock" style="font-size:2rem;display:block;margin-bottom:0.75rem;"></i>
            No installation workflow modules are assigned to your account.
        </div>
    </div>
</div>
<?php } ?>

</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>

<?php include_once 'footer_script.php'; ?>
</body>
</html>
