<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$type = isset($_GET['type']) ? preg_replace('/[^a-z_]/', '', (string) $_GET['type']) : 'total';
$listTypes = mobileMsedclSmartListTypes();
if (!isset($listTypes[$type])) {
    $type = 'total';
}

$PageName = 'MSEDCL Smart List';
$title = mobileMsedclSmartListTitle($type);
$meta = mobileMsedclSmartListFiltersFromRequest();
$rows = mobileMsedclSmartGetListRows($type, $meta['filters']);
$showSurveyColumns = mobileMsedclSmartShowSurveyColumns($type);
$districtRows = mobileMsedclSmartDistrictOptions();
$userSurveyMap = array();

if ($showSurveyColumns && !empty($rows)) {
    $userSurveyMap = msedclSmartLoadUserSurveyMap($rows);
}

$clearUrl = mobileMsedclSmartListUrl($type);
require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';
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
    <a href="msedcl-smart-management.php"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="get" action="">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">District</label>
                <select class="form-control form-control-sm" name="District">
                    <option value="">All District</option>
                    <?php foreach ($districtRows as $districtRow) {
                        $district = (string) ($districtRow['District'] ?? '');
                        ?>
                    <option value="<?php echo htmlspecialchars($district, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($meta['District'] === $district) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($district); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Search</label>
                <input type="text" name="Search" class="form-control form-control-sm" value="<?php echo htmlspecialchars($meta['Search'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Beneficiary ID / Name / Mobile">
            </div>
            <input type="hidden" name="submit" value="1">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <?php if ($meta['searched']) { ?>
                <a href="<?php echo htmlspecialchars($clearUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-secondary btn-sm">Clear</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <?php if (empty($rows)) { ?>
    <div class="mob-mgmt-empty">No records found.</div>
    <?php } else { ?>
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-msedcl mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>District</th>
                    <th>Taluka</th>
                    <th>Village</th>
                    <th>Capacity</th>
                    <?php if ($showSurveyColumns) { ?>
                    <th>Telephonic Survey</th>
                    <th>Field Survey</th>
                    <?php } else { ?>
                    <th>Stage</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($rows as $row) {
                    $custUserId = (int) ($row['CustUserId'] ?? 0);
                    $userSurvey = ($custUserId > 0 && isset($userSurveyMap[$custUserId])) ? $userSurveyMap[$custUserId] : null;
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
                    <?php if ($showSurveyColumns) {
                        if ($custUserId < 1) {
                            $teleStatus = 'Not forwarded';
                            $fieldStatus = 'Not forwarded';
                        } else {
                            $teleStatus = mobileMsedclSmartSurveyStatusLabel($userSurvey['SurveyDetails'] ?? 0);
                            $fieldStatus = mobileMsedclSmartSurveyStatusLabel($userSurvey['FieldSurveyDetails'] ?? 0);
                        }
                        ?>
                    <td><?php echo htmlspecialchars($teleStatus); ?></td>
                    <td><?php echo htmlspecialchars($fieldStatus); ?></td>
                    <?php } else { ?>
                    <td><?php echo htmlspecialchars(msedclSmartStageLabel($row['CurrentStage'] ?? '')); ?></td>
                    <?php } ?>
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
