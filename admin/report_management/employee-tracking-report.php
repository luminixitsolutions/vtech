<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

$user_id = (int) $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, Fname, Lname, Photo FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];

if (!employeeActivityLogCanViewReport($Roll, $Options)) {
    echo "<script>alert('Access denied.'); window.location.href='report-dashboard.php';</script>";
    exit;
}

$MainPage = 'Report';
$Page = 'Employee-Tracking';

$doSearch = isset($_REQUEST['Search']);
$rows = [];
$total = 0;
$params = employeeActivityLogReportParamsFromRequest();
$perPage = $params['per_page'];
$page = $params['page'];
$where = $params['where'];

if ($doSearch) {
    $total = employeeActivityLogReportCount($where);
    $rows = employeeActivityLogReportRows($where, $page, $perPage);
}

$totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;
$actionLabels = employeeActivityLogActionTypeOptions();

$modules = getList("SELECT DISTINCT module_name FROM tbl_employee_activity_logs WHERE module_name IS NOT NULL AND module_name<>'' ORDER BY module_name");
$employees = getList("SELECT id, Fname, Lname, Roll FROM tbl_users WHERE Status='1' ORDER BY Fname");
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Employee Tracking</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once '../header_script.php'; ?>
<style>
.emp-act-json { max-height: 280px; overflow: auto; font-size: 12px; background: #f8f9fa; padding: 10px; border-radius: 4px; white-space: pre-wrap; word-break: break-word; }
</style>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'report-sidebar.php'; ?>
<div class="layout-container">
<?php include_once '../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<div class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-2">
<div>
<h4 class="font-weight-bold mb-1">Employee Tracking Report</h4>
<p class="text-muted mb-0">Detailed activity log — page visits, login/logout, and add/edit/delete/view actions.</p>
</div>
<a href="employee-tracking-dashboard.php" class="btn btn-primary btn-sm"><i class="feather icon-pie-chart"></i> Dashboard</a>
</div>

<div class="card mb-3">
<div class="card-body">
<form method="get" action="">
<div class="form-row">
<div class="form-group col-md-3">
<label class="form-label">Employee</label>
<select class="select2-demo form-control" name="UserId" id="UserId">
<option value="all" <?php if (($_REQUEST['UserId'] ?? 'all') === 'all') { ?>selected<?php } ?>>All Employees</option>
<?php foreach ($employees as $emp) {
    $eid = (int) $emp['id'];
    $label = trim($emp['Fname'] . ' ' . ($emp['Lname'] ?? ''));
    ?>
<option value="<?php echo $eid; ?>" <?php if ((string) ($_REQUEST['UserId'] ?? '') === (string) $eid) { ?>selected<?php } ?>><?php echo htmlspecialchars($label); ?></option>
<?php } ?>
</select>
</div>
<div class="form-group col-md-2">
<label class="form-label">From Date</label>
<input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($_REQUEST['FromDate'] ?? ''); ?>">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($_REQUEST['ToDate'] ?? ''); ?>">
</div>
<div class="form-group col-md-2">
<label class="form-label">Action Type</label>
<select name="ActionType" class="form-control">
<option value="all">All</option>
<?php foreach ($actionLabels as $code => $label) { ?>
<option value="<?php echo htmlspecialchars($code); ?>" <?php if (($_REQUEST['ActionType'] ?? '') === $code) { ?>selected<?php } ?>><?php echo htmlspecialchars($label); ?></option>
<?php } ?>
</select>
</div>
<div class="form-group col-md-2">
<label class="form-label">Module</label>
<select name="ModuleName" class="form-control">
<option value="all">All</option>
<?php foreach ($modules as $m) {
    $mn = $m['module_name'];
    ?>
<option value="<?php echo htmlspecialchars($mn); ?>" <?php if (($_REQUEST['ModuleName'] ?? '') === $mn) { ?>selected<?php } ?>><?php echo htmlspecialchars($mn); ?></option>
<?php } ?>
</select>
</div>
<div class="form-group col-md-3">
<label class="form-label">Page (contains)</label>
<input type="text" name="PageName" class="form-control" placeholder="e.g. Purchase Order" value="<?php echo htmlspecialchars($_REQUEST['PageName'] ?? ''); ?>">
</div>
<div class="form-group col-md-2 d-flex align-items-end">
<input type="hidden" name="Search" value="1">
<button type="submit" class="btn btn-primary btn-block">Search</button>
</div>
<?php if ($doSearch) { ?>
<div class="form-group col-md-1 d-flex align-items-end">
<a href="employee-tracking-report.php" class="btn btn-info btn-block" title="Clear">X</a>
</div>
<?php } ?>
</div>
</form>
</div>
</div>

<?php if ($doSearch) {
    $exportQs = $_GET;
    $exportQs['export'] = 1;
    unset($exportQs['page']);
    ?>
<div class="d-flex justify-content-between align-items-center mb-2">
<span class="text-muted"><?php echo (int) $total; ?> record(s) — page <?php echo (int) $page; ?> of <?php echo max(1, $totalPages); ?></span>
<a href="employee-tracking-export.php?<?php echo htmlspecialchars(http_build_query($exportQs)); ?>" class="btn btn-outline-secondary btn-sm">Export CSV</a>
</div>
<div class="card">
<div class="card-datatable table-responsive">
<table class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
<th>Date &amp; Time</th>
<th>Employee</th>
<th>Role</th>
<th>Module</th>
<th>Page</th>
<th>Action</th>
<th>Record ID</th>
<th>IP</th>
<th>Details</th>
</tr>
</thead>
<tbody>
<?php if (empty($rows)) { ?>
<tr><td colspan="9" class="text-center text-muted">No activity found for selected filters.</td></tr>
<?php }
foreach ($rows as $r) {
    $actLabel = $actionLabels[$r['action_type']] ?? $r['action_type'];
    $uaShort = mb_substr((string) $r['user_agent'], 0, 40);
    if (mb_strlen((string) $r['user_agent']) > 40) {
        $uaShort .= '…';
    }
    ?>
<tr>
<td><?php echo htmlspecialchars($r['created_at']); ?></td>
<td><?php echo htmlspecialchars($r['employee_name']); ?></td>
<td><?php echo htmlspecialchars($r['role']); ?></td>
<td><?php echo htmlspecialchars($r['module_name']); ?></td>
<td><?php echo htmlspecialchars($r['page_name']); ?></td>
<td><span class="badge badge-secondary"><?php echo htmlspecialchars($actLabel); ?></span></td>
<td><?php echo htmlspecialchars((string) $r['record_id']); ?><?php if (!empty($r['record_table'])) { ?><br><small class="text-muted"><?php echo htmlspecialchars($r['record_table']); ?></small><?php } ?></td>
<td title="<?php echo htmlspecialchars($r['user_agent']); ?>"><?php echo htmlspecialchars($r['ip_address']); ?><br><small><?php echo htmlspecialchars($uaShort); ?></small></td>
<td>
<button type="button" class="btn btn-sm btn-outline-primary btn-emp-act-detail" data-id="<?php echo (int) $r['id']; ?>">View</button>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php if ($totalPages > 1) {
    $baseQs = $_GET;
    ?>
<nav class="p-3">
<ul class="pagination pagination-sm mb-0">
<?php for ($p = 1; $p <= $totalPages && $p <= 200; $p++) {
    $baseQs['page'] = $p;
    ?>
<li class="page-item <?php if ($p === $page) { ?>active<?php } ?>">
<a class="page-link" href="?<?php echo htmlspecialchars(http_build_query($baseQs)); ?>"><?php echo $p; ?></a>
</li>
<?php } ?>
</ul>
</nav>
<?php } ?>
</div>
<?php } ?>

</div>
<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<div class="modal fade" id="empActDetailModal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Activity Details</h5>
<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
</div>
<div class="modal-body" id="empActDetailBody">
<p class="text-muted">Loading…</p>
</div>
</div>
</div>
</div>

<?php include_once '../footer_script.php'; ?>
<script>
$(function () {
    $('.btn-emp-act-detail').on('click', function () {
        var id = $(this).data('id');
        $('#empActDetailBody').html('<p class="text-muted">Loading…</p>');
        $('#empActDetailModal').modal('show');
        $.getJSON('employee-tracking-log-detail.php', { id: id }, function (res) {
            if (!res || !res.ok) {
                $('#empActDetailBody').html('<p class="text-danger">' + (res && res.message ? res.message : 'Failed to load') + '</p>');
                return;
            }
            var r = res.row;
            function fmtJson(raw) {
                if (!raw) return '<em class="text-muted">—</em>';
                try {
                    var o = typeof raw === 'string' ? JSON.parse(raw) : raw;
                    return '<pre class="emp-act-json">' + $('<div/>').text(JSON.stringify(o, null, 2)).html() + '</pre>';
                } catch (e) {
                    return '<pre class="emp-act-json">' + $('<div/>').text(raw).html() + '</pre>';
                }
            }
            var html = '<table class="table table-sm table-bordered">' +
                '<tr><th width="28%">Date &amp; Time</th><td>' + (r.created_at || '') + '</td></tr>' +
                '<tr><th>Employee</th><td>' + (r.employee_name || '') + ' <small class="text-muted">#' + r.user_id + '</small></td></tr>' +
                '<tr><th>Role</th><td>' + (r.role || '') + '</td></tr>' +
                '<tr><th>Module</th><td>' + (r.module_name || '') + '</td></tr>' +
                '<tr><th>Page</th><td>' + (r.page_name || '') + '</td></tr>' +
                '<tr><th>URL</th><td><small>' + (r.page_url || '') + '</small></td></tr>' +
                '<tr><th>Action</th><td>' + (r.action_label || r.action_type || '') + '</td></tr>' +
                '<tr><th>Record</th><td>' + (r.record_table || '') + ' #' + (r.record_id || '') + '</td></tr>' +
                '<tr><th>IP</th><td>' + (r.ip_address || '') + '</td></tr>' +
                '<tr><th>User Agent</th><td><small>' + (r.user_agent || '') + '</small></td></tr>' +
                '</table>' +
                '<h6>Old Data</h6>' + fmtJson(r.old_data) +
                '<h6 class="mt-3">New Data</h6>' + fmtJson(r.new_data);
            $('#empActDetailBody').html(html);
        });
    });
});
</script>
</body>
</html>
