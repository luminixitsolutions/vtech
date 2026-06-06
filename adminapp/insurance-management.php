<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$PageName = 'Insurance Management';
$counts = getInsuranceMgmtCounts();
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Insurance Management</title>
<link rel="manifest" href="manifest.json">
<link rel="apple-touch-icon" href="img/favicon180.png">
<link rel="icon" href="img/favicon32.png">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="home.php"><span class="material-icons">arrow_back</span></a>
    <h1>Insurance Management</h1>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-insurance">Insurance Dashboard</div>

<div class="mob-mgmt-grid">
    <div class="row g-3">
        <?php
        mobileMgmtStatCard('Pending Insurance', $counts['pending'], 'mobile-insurance-list.php?status=pending', 'teal');
        mobileMgmtStatCard('Active Completed', $counts['active_completed'], 'mobile-insurance-list.php?status=active', 'teal');
        mobileMgmtStatCard('Upcoming Renewal', $counts['renewal'], 'mobile-insurance-list.php?status=renewal', 'teal');
        mobileMgmtStatCard('Expired Insurance', $counts['expired'], 'mobile-insurance-list.php?status=expired', 'teal');
        mobileMgmtStatCard('Site Dispatched', $counts['site_dispatched'], 'mobile-insurance-list.php?status=dispatched', 'teal');
        mobileMgmtStatCard('Total Completed', $counts['total_completed'], 'mobile-insurance-list.php?status=completed', 'teal');
        ?>
    </div>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
