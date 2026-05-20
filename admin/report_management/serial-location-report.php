<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-serial-location-report.php';

$user_id = (int) $_SESSION['Admin']['id'];
$sql77 = "SELECT Roll, BranchId, Options FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = (int) ($row77['Roll'] ?? 0);
$sessionBranchId = (int) ($row77['BranchId'] ?? 0);
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();

$canAccess = ($Roll === 1 || $Roll === 7 || in_array('101', $Options) || in_array('103', $Options));
if (!$canAccess) {
    echo "<script>alert('Access denied.'); window.location.href='report-dashboard.php';</script>";
    exit;
}

$MainPage = 'Report';
$Page = 'Serial-Location-Report';

$filterBranch = isset($_REQUEST['BranchId']) ? (int) $_REQUEST['BranchId'] : 0;
$filterSerial = isset($_REQUEST['SerialNo']) ? trim((string) $_REQUEST['SerialNo']) : '';
$filterLocation = isset($_REQUEST['Location']) ? trim((string) $_REQUEST['Location']) : 'all';
$allowedLocations = array('all', 'store', 'dispatch_officer', 'reserved', 'customer');
if (!in_array($filterLocation, $allowedLocations, true)) {
    $filterLocation = 'all';
}

/* Branch optional: only restrict non-admin when a specific branch is chosen. */
if ($Roll !== 1 && $Roll !== 7) {
    if ($filterBranch > 0 && $filterBranch !== $sessionBranchId) {
        $filterBranch = $sessionBranchId;
    }
}

$doSearch = isset($_REQUEST['Search']) || isset($_REQUEST['export']);
$trackRecords = array();

if (!empty($_GET['export']) && $_GET['export'] === 'csv' && $doSearch) {
    if ($filterSerial !== '') {
        $rows = serial_report_fetch_rows_by_serial($conn, $filterSerial, 0, $filterLocation);
    } else {
        $rows = serial_report_fetch_rows($conn, $filterBranch, '', $filterLocation);
    }
    serial_report_export_csv($rows);
}

$rows = array();
if ($doSearch) {
    if ($filterSerial !== '') {
        /* Serial search: always all branches (Store/Branch filter optional, not applied to lookup). */
        $rows = serial_report_fetch_rows_by_serial($conn, $filterSerial, 0, $filterLocation);
        $trackRecords = serial_report_fetch_track_records($conn, $filterSerial, 0);
        if ($filterBranch > 0) {
            $brName = '';
            $brRow = getRecord("SELECT Name FROM tbl_branch WHERE id='" . (int) $filterBranch . "' LIMIT 1");
            $brName = trim((string) ($brRow['Name'] ?? ''));
            if ($brName !== '') {
                $rows = array_values(array_filter($rows, function ($r) use ($brName) {
                    return stripos((string) ($r['branch_display'] ?? ''), $brName) !== false
                        || ($r['location_key'] ?? '') === 'customer';
                }));
                $trackRecords = array_values(array_filter($trackRecords, function ($tr) use ($brName) {
                    return stripos((string) ($tr['branch_name'] ?? ''), $brName) !== false;
                }));
            }
        }
    } else {
        $searchBranch = $filterBranch;
        if ($searchBranch < 1 && $Roll !== 1 && $Roll !== 7) {
            $searchBranch = $sessionBranchId;
        }
        $rows = serial_report_fetch_rows($conn, $searchBranch, '', $filterLocation);
    }
}

$branches = array();
if ($Roll === 1 || $Roll === 7) {
    $br = $conn->query("SELECT id, Name FROM tbl_branch WHERE Status='1' ORDER BY Name");
} else {
    $br = $conn->query("SELECT id, Name FROM tbl_branch WHERE Status='1' AND id='" . (int) $sessionBranchId . "' ORDER BY Name");
}
if ($br) {
    while ($b = $br->fetch_assoc()) {
        $branches[] = $b;
    }
}

$locationBadge = array(
    'store' => 'badge-success',
    'dispatch_officer' => 'badge-primary',
    'reserved' => 'badge-warning',
    'customer' => 'badge-info',
    'unknown' => 'badge-secondary',
);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo htmlspecialchars($Proj_Title); ?> — Serial Location Report</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>

<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="font-weight-bold py-3 mb-0">Serial No — Location Report</h4>

    <div class="card mb-3" style="padding: 10px;">
        <form method="get" action="serial-location-report.php" class="form-row align-items-end">
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="form-label">Store / Branch <span class="text-muted small">(optional)</span></label>
                <select name="BranchId" class="form-control">
                    <option value="0"<?php echo $filterBranch === 0 ? ' selected' : ''; ?>>All branches</option>
                    <?php foreach ($branches as $b) {
                        $sel = ($filterBranch === (int) $b['id']) ? ' selected' : '';
                        echo '<option value="' . (int) $b['id'] . '"' . $sel . '>' . htmlspecialchars($b['Name']) . '</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-3 mb-2 mb-md-0">
                <label class="form-label">Serial No</label>
                <input type="text" name="SerialNo" class="form-control" value="<?php echo htmlspecialchars($filterSerial); ?>" placeholder="Full or partial serial">
            </div>
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="form-label">Current Location</label>
                <select name="Location" class="form-control">
                    <option value="all"<?php echo $filterLocation === 'all' ? ' selected' : ''; ?>>All</option>
                    <option value="store"<?php echo $filterLocation === 'store' ? ' selected' : ''; ?>>Store only</option>
                    <option value="dispatch_officer"<?php echo $filterLocation === 'dispatch_officer' ? ' selected' : ''; ?>>Dispatch Officer only</option>
                    <option value="reserved"<?php echo $filterLocation === 'reserved' ? ' selected' : ''; ?>>Reserved only</option>
                    <option value="customer"<?php echo $filterLocation === 'customer' ? ' selected' : ''; ?>>Customer only</option>
                </select>
            </div>
            <div class="form-group col-md-5 mb-0">
                <input type="hidden" name="Search" value="1">
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if ($doSearch && count($rows) > 0) {
                    $exportQs = http_build_query(array(
                        'BranchId' => $filterBranch,
                        'SerialNo' => $filterSerial,
                        'Location' => $filterLocation,
                        'Search' => '1',
                        'export' => 'csv',
                    ));
                    ?>
                <a href="serial-location-report.php?<?php echo htmlspecialchars($exportQs); ?>" class="btn btn-outline-secondary">Export CSV</a>
                <?php } ?>
                <a href="serial-location-report.php" class="btn btn-info">Clear</a>
            </div>
        </form>
    </div>

    <?php if (!$doSearch) { ?>
    <div class="alert alert-light border">Enter a serial number and click <strong>Search</strong>. Store / Branch is optional — leave it as <strong>All branches</strong> to search across every branch.</div>
    <?php } else { ?>
    <div class="card">
        <div class="card-datatable table-responsive" style="padding: 10px;">
            <table id="serialLocationTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serial No</th>
                        <th>Current Location</th>
                        <th>Product</th>
                        <th>Model</th>
                        <th>Store / Branch</th>
                        <th>Dispatch Officer</th>
                        <th>Store dispatch created</th>
                        <th>Officer assign batch</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($rows as $r) {
                    $badge = $locationBadge[$r['location_key']] ?? 'badge-secondary';
                    $storeDisp = !empty($r['store_dispatch_id'])
                        ? '#' . (int) $r['store_dispatch_id'] . ' — ' . serial_report_format_date($r['store_dispatch_date'] ?? '')
                        : '—';
                    $offBatch = !empty($r['officer_batch_id'])
                        ? '#' . (int) $r['officer_batch_id'] . ' — ' . serial_report_format_date($r['officer_batch_date'] ?? '')
                        : '—';
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><strong><?php echo htmlspecialchars((string) $r['serial_no']); ?></strong></td>
                        <td><span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($r['current_location']); ?></span></td>
                        <td><?php echo htmlspecialchars((string) $r['product_name']); ?></td>
                        <td><?php echo htmlspecialchars((string) $r['model_no']); ?></td>
                        <td><?php echo htmlspecialchars((string) $r['branch_display']); ?></td>
                        <td><?php echo htmlspecialchars((string) ($r['dispatch_officer_name'] ?? '—')); ?></td>
                        <td><?php echo htmlspecialchars($storeDisp); ?></td>
                        <td><?php echo htmlspecialchars($offBatch); ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars((string) ($r['detail_note'] ?? '')); ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                if (count($rows) === 0) {
                    $emptyMsg = count($trackRecords) > 0
                        ? 'Current location could not be determined — see the track record below for movement history.'
                        : 'No serial found for the selected filters. Try setting Store / Branch to All branches.';
                    echo '<tr><td colspan="10" class="text-center text-muted">' . htmlspecialchars($emptyMsg) . '</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($filterSerial !== '' && count($trackRecords) > 0) { ?>
    <div class="card mt-3">
        <div class="card-header font-weight-bold">Track Record — <?php echo htmlspecialchars($filterSerial); ?></div>
        <div class="card-datatable table-responsive" style="padding: 10px;">
            <table id="serialTrackTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date / Time</th>
                        <th>Event</th>
                        <th>Serial No</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Store / Branch</th>
                        <th>Dispatch Officer</th>
                        <th>Reference / Customer</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $ti = 1;
                foreach ($trackRecords as $tr) {
                    $ref = (string) ($tr['ref_label'] ?? '');
                    if (!empty($tr['ref_narration'])) {
                        $ref .= ($ref !== '' ? ' — ' : '') . $tr['ref_narration'];
                    }
                    if (!empty($tr['customer_info'])) {
                        $ref .= ($ref !== '' ? ' | ' : '') . $tr['customer_info'];
                    }
                    ?>
                    <tr>
                        <td><?php echo $ti; ?></td>
                        <td><?php echo htmlspecialchars(serial_report_format_date($tr['sort_date'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($tr['event_label'] ?? '')); ?></td>
                        <td><strong><?php echo htmlspecialchars((string) ($tr['SerialNo'] ?? '')); ?></strong></td>
                        <td><?php echo htmlspecialchars((string) ($tr['ProductName'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($tr['Qty'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($tr['branch_name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($tr['officer_name'] ?? '—')); ?></td>
                        <td class="small"><?php echo htmlspecialchars($ref); ?></td>
                    </tr>
                    <?php
                    $ti++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } elseif ($filterSerial !== '' && $doSearch) { ?>
    <div class="alert alert-info mt-3">No track record found for this serial number.</div>
    <?php } ?>

    <?php } ?>
</div>

<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
</div>

<?php include_once '../footer_script.php'; ?>
<?php if ($doSearch && (count($rows) > 0 || count($trackRecords) > 0)) { ?>
<script>
$(function() {
    var dtOpts = { pageLength: 50, order: [], dom: 'Bfrtip', buttons: ['copy', 'excel', 'pdf', 'print'] };
    if ($('#serialLocationTable').length && $('#serialLocationTable tbody tr td').length > 1) {
        $('#serialLocationTable').DataTable($.extend({}, dtOpts, { order: [[1, 'asc']] }));
    }
    if ($('#serialTrackTable').length) {
        $('#serialTrackTable').DataTable($.extend({}, dtOpts, { order: [[1, 'desc']] }));
    }
});
</script>
<?php } ?>
</body>
</html>
