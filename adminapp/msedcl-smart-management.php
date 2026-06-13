<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$PageName = 'MSEDCL Smart Project';
$dash = mobileMsedclSmartGetDashboardData();
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | MSEDCL Smart Project</title>
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
    <h1>MSEDCL Smart Project</h1>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-msedcl">Status Overview</div>

<div class="px-3 mb-3">
    <div class="mob-mgmt-card py-2 px-3 mb-0 text-center">
        <div class="small text-muted">PMSGY &rarr; Mahadiscom &rarr; Payment &rarr; Survey workflow</div>
        <div class="small mt-1"><strong><?php echo number_format((int) $dash['imported_today']); ?></strong> new records today</div>
    </div>
</div>

<div class="mob-mgmt-grid">
    <div class="row g-3">
        <?php
        mobileMgmtStatCard('Total Customers', (int) $dash['total'], mobileMsedclSmartListUrl('total'), 'blue');
        mobileMgmtStatCard('PMSGY Portal', (int) $dash['pmsgy'], mobileMsedclSmartListUrl('pmsgy'), 'purple');
        mobileMgmtStatCard('Mahadiscom Portal', (int) $dash['mahadiscom'], mobileMsedclSmartListUrl('mahadiscom'), 'purple');
        mobileMgmtStatCard('Payment Done', (int) $dash['payment_done'], mobileMsedclSmartListUrl('payment'), 'green');
        mobileMgmtStatCard('Survey Pending', (int) $dash['survey_pending'], mobileMsedclSmartListUrl('survey_pending'), 'orange');
        mobileMgmtStatCard('Survey Done', (int) $dash['survey_done'], mobileMsedclSmartListUrl('survey_done'), 'teal');
        mobileMgmtStatCard('MSEDCL SMART PROJECT ABSTRACT', 0, mobileMsedclSmartAbstractUrl(), 'purple', false);
        ?>
    </div>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
