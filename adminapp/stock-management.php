<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$PageName = 'Stock Management';
$counts = getStockMgmtCounts();
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Stock Management</title>
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
    <h1>Stock Management</h1>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-stock">Stock Dashboard</div>

<div class="mob-mgmt-grid">
    <div class="row g-3">
        <?php
        mobileMgmtStatCard('Store Stock Report', 0, 'mobile-available-stock.php', 'orange', false);
        mobileMgmtStatCard('Dispatch Officer Stock Report', 0, 'mobile-dispatch-officer-stock.php', 'orange', false);
        mobileMgmtStatCard('Purchase Order', $counts['purchase_orders'], 'mobile-purchase-order-list.php', 'orange');
        mobileMgmtStatCard('Delivery Challan', $counts['delivery_challan'], 'mobile-delivery-challan-list.php', 'orange');
        ?>
    </div>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
