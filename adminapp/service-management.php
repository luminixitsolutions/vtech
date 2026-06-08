<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';
require_once __DIR__ . '/../admin/inc-service-abstract-data.php';

$PageName = 'Service Management';

$filters = serviceAbstractFiltersFromRequest();
$abstract = getServiceAbstractData($filters);
$rows = $abstract['rows'];
$totals = $abstract['totals'];
$reportTitle = $abstract['title'];
$columnLabel = $abstract['column_label'];
$abstractType = $filters['abstract_type'];

$projectHeads = serviceAbstractGetProjectHeads();
$subHeads = serviceAbstractGetSubHeads($filters['projid']);
$districtOptions = serviceAbstractGetDistrictOptions($filters);
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Service Abstract</title>
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
    <h1>Service Abstract</h1>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-service">Service Complaints Abstract</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="get" id="abstractFilterForm">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Abstract type</label>
                <select name="abstract_type" id="abstract_type" class="form-control form-control-sm">
                    <option value="all" <?php if ($abstractType === 'all') { ?>selected<?php } ?>>All</option>
                    <option value="district" <?php if ($abstractType === 'district') { ?>selected<?php } ?>>District wise</option>
                    <option value="project_head" <?php if ($abstractType === 'project_head') { ?>selected<?php } ?>>Project head wise</option>
                    <option value="sub_project_head" <?php if ($abstractType === 'sub_project_head') { ?>selected<?php } ?>>Sub project head wise</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Project head</label>
                <select name="projid" id="projid" class="form-control form-control-sm">
                    <option value="">All Project Head</option>
                    <?php foreach ($projectHeads as $ph) { ?>
                    <option value="<?php echo (int) $ph['id']; ?>" <?php if ($filters['projid'] == $ph['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($ph['Name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Sub project head</label>
                <select name="subheadid" id="subheadid" class="form-control form-control-sm">
                    <option value="">All Sub Project Head</option>
                    <?php foreach ($subHeads as $sh) { ?>
                    <option value="<?php echo (int) $sh['id']; ?>" <?php if ($filters['subheadid'] == $sh['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($sh['Name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">District</label>
                <select name="district" id="district" class="form-control form-control-sm">
                    <option value="">All District</option>
                    <?php foreach ($districtOptions as $distOpt) { ?>
                    <option value="<?php echo htmlspecialchars($distOpt); ?>" <?php if ($filters['district'] === $distOpt) { ?>selected<?php } ?>><?php echo htmlspecialchars($distOpt); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <a href="service-management.php" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>

    <div class="mob-mgmt-abstract-meta text-center mb-3">
        <div class="fw-bold">VTECH SUNSYSTEMS PVT LTD</div>
        <div class="fw-semibold small mt-1"><?php echo htmlspecialchars($reportTitle); ?></div>
        <div class="text-muted small">Update as on <?php echo date('d.m.Y'); ?></div>
    </div>

    <div class="mob-mgmt-table-wrap table-responsive">
        <table class="table table-striped table-bordered mob-mgmt-table mb-0">
            <thead>
                <tr>
                    <th><?php echo htmlspecialchars($columnLabel); ?></th>
                   <th>Total Complaints Pending</th>
<th>Complaints Hold Due to the Material Issue</th>
<th>Total Complaints</th>
<th>Total Complaints Closed</th>
<th>Today Complaints Added</th> 
                    
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) { ?>
                <tr>
                    <th scope="row" class="mob-mgmt-table-label"><?php echo htmlspecialchars($row['label']); ?></th>
                     <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($row, $filters, 'material'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $row['material_hold']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($row, $filters, 'pending'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $row['total_pending']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($row, $filters, ''), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $row['total_complaints']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($row, $filters, 'closed'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $row['total_closed']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($row, $filters, 'today'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $row['today_added']; ?></a></td>
                   
                </tr>
                <?php } ?>
                <?php if (count($rows) > 1) { ?>
                <tr class="mob-mgmt-table-total">
                    <th scope="row" class="mob-mgmt-table-label"><?php echo htmlspecialchars($totals['label']); ?></th>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($totals, $filters, 'material'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $totals['material_hold']; ?></a></td>
                     <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($totals, $filters, 'pending'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $totals['total_pending']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($totals, $filters, ''), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $totals['total_complaints']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($totals, $filters, 'closed'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $totals['total_closed']; ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(serviceAbstractMobileListUrl($totals, $filters, 'today'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $totals['today_added']; ?></a></td>
                    
                   
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <p class="text-muted small px-1 mb-0">
        Tap a count to view complaint details. Close = closed; In Process = material hold; pending = not closed.
    </p>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
