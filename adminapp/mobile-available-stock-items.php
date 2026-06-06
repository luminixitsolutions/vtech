<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-stock-data.php';

$type = isset($_GET['type']) ? trim($_GET['type']) : 'store';
$branchId = isset($_GET['branch_id']) ? (int) $_GET['branch_id'] : 0;
$officerId = isset($_GET['officer_id']) ? (int) $_GET['officer_id'] : 0;

$items = array();
$title = 'Item Stock';
$subtitle = '';

if ($type === 'dispatch' && $branchId > 0 && $officerId > 0) {
    $data = mobileStockGetDispatchItemStock($branchId, $officerId);
    $items = $data['items'];
    $title = $data['officer_name'];
    $subtitle = $data['branch_name'] . ' · Dispatch officer';
} elseif ($type === 'store' && $branchId > 0) {
    $data = mobileStockGetStoreItemStock($branchId);
    $items = $data['items'];
    $title = $data['branch_name'];
    $subtitle = 'Store available stock';
} else {
    $title = 'Invalid request';
}

$totalQty = 0.0;
foreach ($items as $row) {
    $totalQty += (float) $row['avail_qty'];
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($title); ?></title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="mob-mgmt-page">
<?php include_once __DIR__ . '/inc-app-loader.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="mobile-available-stock.php"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <span class="badge bg-primary"><?php echo count($items); ?></span>
</div>

<?php if ($subtitle !== '') { ?>
<p class="text-center text-muted small mb-0 px-3"><?php echo htmlspecialchars($subtitle); ?></p>
<p class="text-center fw-bold mb-2" style="color:#f57c00;">Total: <?php echo mobileStockFormatQty($totalQty); ?> Qty</p>
<?php } ?>

<div class="mob-mgmt-list-wrap">
<?php if (empty($items)) { ?>
    <div class="mob-mgmt-empty">No item stock found.</div>
<?php } else {
    $i = 1;
    foreach ($items as $row) {
        $unit = trim($row['unit_label'] ?? '');
        ?>
    <div class="mob-mgmt-card">
        <div class="mob-mgmt-card-title"><?php echo $i; ?>. <?php echo htmlspecialchars($row['ProductName'] ?? ('Product #' . $row['ProductId'])); ?></div>
        <?php if ($unit !== '') { ?>
        <div class="mob-mgmt-card-row"><span>Unit</span><span><?php echo htmlspecialchars($unit); ?></span></div>
        <?php } ?>
        <div class="mob-mgmt-card-row"><span>Available Qty</span><span class="fw-bold" style="color:#f57c00;"><?php echo mobileStockFormatQty($row['avail_qty']); ?></span></div>
    </div>
        <?php
        $i++;
    }
} ?>
</div>

</body>
</html>
