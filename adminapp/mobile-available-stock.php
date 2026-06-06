<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-stock-data.php';

$userRow = mobileStockGetSessionUser();
$Roll = is_array($userRow) ? (int) ($userRow['Roll'] ?? 0) : 0;
$userBranchId = is_array($userRow) ? (int) ($userRow['BranchId'] ?? 0) : 0;
$branches = mobileStockGetBranchList($Roll, $userBranchId);

$searched = isset($_GET['Search']) && $_GET['Search'] === 'Search';
$branchGet = isset($_GET['BranchId']) ? trim((string) $_GET['BranchId']) : '';
$selectedBranchId = ($branchGet !== '' && $branchGet !== 'all') ? (int) $branchGet : 0;
$allBranches = ($branchGet === 'all');

if (!$searched && $branchGet === '' && count($branches) === 1) {
    $selectedBranchId = (int) $branches[0]['id'];
}

$report = null;
$reportError = '';

if ($searched) {
    if ($allBranches) {
        if ((int) $Roll !== 1 && (int) $Roll !== 7) {
            $reportError = 'You cannot view all stores.';
        } else {
            set_time_limit(120);
            $report = mobileStockGetStoreReportData(array(
                'all_branches' => true,
            ));
        }
    } elseif (!mobileStockCanAccessBranch($Roll, $userBranchId, $selectedBranchId)) {
        $reportError = 'Invalid store selected.';
    } elseif ($selectedBranchId < 1) {
        $reportError = 'Please select a store.';
    } else {
        set_time_limit(120);
        $report = mobileStockGetStoreReportData(array(
            'branch_id' => $selectedBranchId,
        ));
    }

    if ($report === null && $reportError === '') {
        $reportError = 'Unable to load stock report. Please try again.';
    }
}

$showBranchColumn = ($report !== null && !empty($report['all_branches']));
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Store Stock Report</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="stock-management.php"><span class="material-icons">arrow_back</span></a>
    <h1>Store Stock Report</h1>
</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="get" action="">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Store <span class="text-danger">*</span></label>
                <select class="form-control form-control-sm" name="BranchId" id="BranchId" required>
                    <?php if ((int) $Roll === 1 || (int) $Roll === 7) { ?>
                    <option value="all" <?php echo $allBranches ? 'selected' : ''; ?>>All</option>
                    <?php }
                    foreach ($branches as $branch) {
                        $bid = (int) $branch['id'];
                        ?>
                    <option value="<?php echo $bid; ?>" <?php echo (!$allBranches && $selectedBranchId === $bid) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($branch['Name']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <input type="hidden" name="Search" value="Search">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <?php if ($searched) { ?>
                <a href="mobile-available-stock.php" class="btn btn-outline-secondary btn-sm">Clear</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <?php if ($reportError !== '') { ?>
    <div class="mob-mgmt-empty"><?php echo htmlspecialchars($reportError); ?></div>
    <?php } elseif ($report !== null) { ?>
    <div class="mob-mgmt-section-title">Store Stock Report — <?php echo htmlspecialchars($report['store_name']); ?></div>
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-stock mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <?php if ($showBranchColumn) { ?><th>Branch</th><?php } ?>
                    <th>Product Name</th>
                    <th>Inward</th>
                    <th>Outward</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($report['rows'] as $row) {
                    $rowBranchId = (int) $row['BranchId'];
                    $qBase = array(
                        'BranchId' => $rowBranchId,
                        'ProductId' => (int) $row['ProductId'],
                    );
                    if ($allBranches) {
                        $qBase['ListBranch'] = 'all';
                    } elseif ($selectedBranchId > 0) {
                        $qBase['ListBranch'] = $selectedBranchId;
                    }
                    $inwardHref = 'mobile-store-stock-credit-detail.php?' . http_build_query($qBase);
                    $outwardHref = 'mobile-store-stock-debit-detail.php?' . http_build_query($qBase);
                    $inward = (float) $row['inward_qty'];
                    $outward = (float) $row['outward_qty'];
                    ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <?php if ($showBranchColumn) { ?>
                    <td><?php echo htmlspecialchars((string) ($row['Branch'] ?? '')); ?></td>
                    <?php } ?>
                    <td><?php echo htmlspecialchars((string) ($row['ProductName'] ?? '')); ?></td>
                    <td><?php if ($inward > 0) { ?><a href="<?php echo htmlspecialchars($inwardHref, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(mobileStockFormatQty($inward)); ?></a><?php } else { echo '0'; } ?></td>
                    <td><?php if ($outward > 0) { ?><a href="<?php echo htmlspecialchars($outwardHref, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(mobileStockFormatQty($outward)); ?></a><?php } else { echo '0'; } ?></td>
                    <td><?php echo htmlspecialchars(mobileStockFormatQty($row['balance_qty'])); ?></td>
                </tr>
                    <?php
                    $i++;
                }
                if (empty($report['rows'])) {
                    $colspan = $showBranchColumn ? 6 : 5;
                    ?>
                <tr>
                    <td colspan="<?php echo $colspan; ?>" class="text-center text-muted">No stock records found for selected filters.</td>
                </tr>
                    <?php
                }
                ?>
                <tr class="mob-mgmt-table-total">
                    <td><?php echo $i; ?></td>
                    <?php if ($showBranchColumn) { ?><td></td><?php } ?>
                    <th>Total</th>
                    <th><?php echo htmlspecialchars(mobileStockFormatQty($report['tot_inward'])); ?></th>
                    <th><?php echo htmlspecialchars(mobileStockFormatQty($report['tot_outward'])); ?></th>
                    <th><?php echo htmlspecialchars(mobileStockFormatQty($report['tot_balance'])); ?></th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
