<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';
require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';

$metric = isset($_GET['metric']) ? preg_replace('/[^a-z_]/', '', (string) $_GET['metric']) : '';
$rowDistrict = isset($_GET['RowDistrict']) ? trim((string) $_GET['RowDistrict']) : '';
$meta = msedclSmartAbstractFiltersFromRequest($_REQUEST);
$records = mobileMsedclSmartAbstractRecords($metric, $rowDistrict, $meta['filters']);

$titleParts = array(msedclSmartAbstractMetricLabel($metric));
if ($rowDistrict !== '') {
    $titleParts[] = $rowDistrict;
} elseif ($meta['District'] !== '') {
    $titleParts[] = $meta['District'];
} else {
    $titleParts[] = 'All Districts';
}
if ($meta['Taluka'] !== '') {
    $titleParts[] = 'Taluka: ' . $meta['Taluka'];
}

$PageName = 'MSEDCL Smart Abstract';
$title = implode(' — ', $titleParts);
$backUrl = 'msedcl-smart-abstract.php';
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($title); ?></title>
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
    <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>"><span class="material-icons">arrow_back</span></a>
    <h1>Abstract Records</h1>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-msedcl"><?php echo htmlspecialchars($title); ?></div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card py-2 px-3 mb-3 text-center">
        <div class="small text-muted"><?php echo count($records); ?> record(s)</div>
    </div>

    <?php if (empty($records)) { ?>
    <div class="mob-mgmt-card py-3 px-3 text-center text-muted">No records found.</div>
    <?php } else { ?>
    <div class="mob-mgmt-table-wrap table-responsive">
        <table class="table table-striped table-bordered mob-mgmt-table mb-0">
            <thead>
                <tr>
                    <th>Sr</th>
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>District</th>
                    <th>Taluka</th>
                    <th>Village</th>
                    <th>Capacity</th>
                    <th>Stage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($records as $row) {
                    ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['BeneficiaryId'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['CustName'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['CellNo'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['District'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['Taluka'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['Village'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars(mobileMsedclSmartCapacityLabel($row)); ?></td>
                    <td><?php echo htmlspecialchars(msedclSmartStageLabel($row['CurrentStage'] ?? '')); ?></td>
                </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
