<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-stock-data.php';

$userRow = mobileStockGetSessionUser();
$Roll = (int) ($userRow['Roll'] ?? 0);
$userBranchId = (int) ($userRow['BranchId'] ?? 0);

$reqBranch = isset($_GET['BranchId']) ? (int) $_GET['BranchId'] : 0;
$productId = isset($_GET['ProductId']) ? (int) $_GET['ProductId'] : 0;
$fromDate = isset($_GET['FromDate']) ? trim((string) $_GET['FromDate']) : '';
$toDate = isset($_GET['ToDate']) ? trim((string) $_GET['ToDate']) : '';

if (!mobileStockCanAccessBranch($Roll, $userBranchId, $reqBranch) || $productId < 1) {
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Invalid</title></head><body><p>Invalid parameters.</p></body></html>';
    exit;
}

$storeRow = getRecord("SELECT Name FROM tbl_branch WHERE id='$reqBranch' AND Status='1' LIMIT 1");
$storeName = $storeRow['Name'] ?? '';
$prodRow = getRecord("SELECT ProductName FROM tbl_products WHERE id='$productId' LIMIT 1");
$productName = $prodRow['ProductName'] ?? '';
$detail = mobileStockGetDebitDetailRows($reqBranch, $productId, $fromDate, $toDate);
$rows = $detail['rows'];

$listBranch = isset($_GET['ListBranch']) ? trim((string) $_GET['ListBranch']) : (string) $reqBranch;
$backHref = 'mobile-available-stock.php?' . http_build_query(array(
    'Search' => 'Search',
    'BranchId' => $listBranch,
));
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Outward Detail</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="mob-mgmt-page">
<?php include_once __DIR__ . '/inc-app-loader.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="<?php echo htmlspecialchars($backHref, ENT_QUOTES, 'UTF-8'); ?>"><span class="material-icons">arrow_back</span></a>
    <h1>Outward Detail</h1>
</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card">
        <div class="mob-mgmt-card-title"><?php echo htmlspecialchars($storeName); ?></div>
        <div class="mob-mgmt-card-row"><span>Product</span><span><?php echo htmlspecialchars($productName); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Total Qty</span><span class="fw-bold"><?php echo mobileStockFormatQty($detail['sum_qty']); ?></span></div>
    </div>

    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Qty</th>
                    <th>Serial</th>
                    <th>Type</th>
                    <th>Narration</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($rows as $row) {
                    $ldt = !empty($row['CreatedDate']) ? date('d/m/Y', strtotime(str_replace('-', '/', $row['CreatedDate']))) : '-';
                    ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($ldt); ?></td>
                    <td><?php echo htmlspecialchars(mobileStockFormatQty($row['Qty'])); ?></td>
                    <td><?php echo htmlspecialchars((string) $row['SerialNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['SellType'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['Narration'] ?? '')); ?></td>
                </tr>
                    <?php
                }
                if (empty($rows)) {
                    ?>
                <tr><td colspan="6" class="text-center text-muted">No outward lines found.</td></tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
