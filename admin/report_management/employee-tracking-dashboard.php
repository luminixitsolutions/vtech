<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once '../inc-employee-tracking-dashboard-data.php';

$user_id = (int) $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, Fname, Lname, Photo FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);
$Options = adminResolveMenuOptionsFromUserRow($row77);

if (!employeeActivityLogCanViewReport($Roll, $Options)) {
    echo "<script>alert('Access denied.'); window.location.href='report-dashboard.php';</script>";
    exit;
}

$MainPage = 'Report';
$Page = 'Employee-Tracking-Dashboard';
$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));
$dash = employeeTrackingDashboardGetData();
$f = $dash['filters'];
$reportUrl = employeeTrackingDashboardReportUrl($f);
$actionLabels = employeeActivityLogActionTypeOptions();

$actionChartLabels = [];
$actionChartCounts = [];
foreach ($dash['action_breakdown'] as $row) {
    $actionChartLabels[] = $row['label'];
    $actionChartCounts[] = $row['cnt'];
}

$moduleLabels = [];
$moduleCounts = [];
foreach ($dash['module_breakdown'] as $row) {
    $moduleLabels[] = $row['module_name'];
    $moduleCounts[] = (int) $row['cnt'];
}

$dailyLabels = [];
$dailyCounts = [];
foreach ($dash['daily_trend'] as $row) {
    $dailyLabels[] = $row['label'];
    $dailyCounts[] = (int) $row['cnt'];
}

$topEmpLabels = [];
$topEmpCounts = [];
$topEmpMax = 1;
foreach ($dash['top_employees'] as $row) {
    $topEmpLabels[] = $row['employee_name'] ?: ('User #' . $row['user_id']);
    $topEmpCounts[] = (int) $row['cnt'];
    if ((int) $row['cnt'] > $topEmpMax) {
        $topEmpMax = (int) $row['cnt'];
    }
}

$employees = getList("SELECT id, Fname, Lname FROM tbl_users WHERE Status='1' ORDER BY Fname");
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Employee Tracking Dashboard</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once '../header_script.php'; ?>
<link rel="stylesheet" href="../css/employee-tracking-dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'report-sidebar.php'; ?>
<div class="layout-container">
<?php include_once '../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y etd-page">

<div class="etd-hero">
    <h4>Employee Tracking Dashboard</h4>
    <p>Visual overview of staff activity — page visits, logins, and add/edit/delete actions. Use filters below, then open the detailed log report.</p>
    <div class="etd-hero-meta">
        <span class="etd-hero-pill"><i class="feather icon-user"></i> <?php echo htmlspecialchars($adminName !== '' ? $adminName : 'Admin'); ?></span>
        <span class="etd-hero-pill"><i class="feather icon-calendar"></i> <?php echo date('d M Y', strtotime($f['from'])); ?> – <?php echo date('d M Y', strtotime($f['to'])); ?></span>
        <span class="etd-hero-pill"><i class="feather icon-activity"></i> <?php echo number_format($dash['total']); ?> events in range</span>
    </div>
    <div class="etd-hero-actions">
        <a href="<?php echo htmlspecialchars($reportUrl); ?>" class="btn btn-light btn-sm"><i class="feather icon-list"></i> Full activity report</a>
        <a href="employee-tracking-export.php?<?php echo htmlspecialchars(http_build_query(['Search' => 1, 'FromDate' => $f['from'], 'ToDate' => $f['to'], 'UserId' => $f['user_id']])); ?>" class="btn btn-outline-light btn-sm"><i class="feather icon-download"></i> Export CSV</a>
    </div>
</div>

<div class="card etd-filter-card">
<div class="card-body">
<form method="get" class="form-row align-items-end">
<div class="form-group col-md-3 mb-md-0">
<label class="form-label">Employee</label>
<select class="form-control select2-demo" name="UserId">
<option value="all" <?php if ($f['user_id'] === 'all') { ?>selected<?php } ?>>All employees</option>
<?php foreach ($employees as $emp) {
    $eid = (int) $emp['id'];
    $nm = trim($emp['Fname'] . ' ' . ($emp['Lname'] ?? '')); ?>
<option value="<?php echo $eid; ?>" <?php if ((string) $f['user_id'] === (string) $eid) { ?>selected<?php } ?>><?php echo htmlspecialchars($nm); ?></option>
<?php } ?>
</select>
</div>
<div class="form-group col-md-2 mb-md-0">
<label class="form-label">From</label>
<input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($f['from']); ?>">
</div>
<div class="form-group col-md-2 mb-md-0">
<label class="form-label">To</label>
<input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($f['to']); ?>">
</div>
<div class="form-group col-md-2 mb-md-0">
<button type="submit" class="btn btn-primary btn-block">Apply</button>
</div>
<div class="form-group col-md-1 mb-md-0">
<a href="employee-tracking-dashboard.php" class="btn btn-outline-secondary btn-block" title="Reset">X</a>
</div>
</form>
</div>
</div>

<div class="card etd-shell">
<div class="etd-shell-header">Activity summary</div>
<div class="etd-shell-body">
<div class="etd-stat-grid">
<?php
employeeTrackingDashboardStatCard('Total events', $dash['total'], $reportUrl, 'layers', 'In selected period', 'total');
employeeTrackingDashboardStatCard('Today', $dash['today'], employeeTrackingDashboardReportUrl(array_merge($f, ['from' => date('Y-m-d'), 'to' => date('Y-m-d')]), []), 'sun', 'Events today', 'today');
employeeTrackingDashboardStatCard('Active employees', $dash['unique_employees'], $reportUrl, 'users', 'Distinct users', 'users');
employeeTrackingDashboardStatCard('Page visits', $dash['page_visits'], employeeTrackingDashboardReportUrl($f, ['ActionType' => EMP_ACT_PAGE_VISIT]), 'compass', 'Navigation', 'visit');
employeeTrackingDashboardStatCard('View record', $dash['views'], employeeTrackingDashboardReportUrl($f, ['ActionType' => EMP_ACT_VIEW_RECORD]), 'eye', 'Detail views', 'view');
employeeTrackingDashboardStatCard('Add record', $dash['adds'], employeeTrackingDashboardReportUrl($f, ['ActionType' => EMP_ACT_ADD_RECORD]), 'plus-circle', 'Creates', 'add');
employeeTrackingDashboardStatCard('Edit record', $dash['edits'], employeeTrackingDashboardReportUrl($f, ['ActionType' => EMP_ACT_EDIT_RECORD]), 'edit-2', 'Updates', 'edit');
employeeTrackingDashboardStatCard('Delete', $dash['deletes'], employeeTrackingDashboardReportUrl($f, ['ActionType' => EMP_ACT_DELETE_RECORD]), 'trash-2', 'Removals', 'delete');
employeeTrackingDashboardStatCard('Logins', $dash['logins'], employeeTrackingDashboardReportUrl($f, ['ActionType' => EMP_ACT_LOGIN]), 'log-in', 'Sessions', 'login');
?>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-8">
<div class="etd-chart-card">
<h6 class="etd-chart-title">Daily activity trend (last 14 days in range)</h6>
<div class="etd-chart-wrap"><canvas id="dailyLineChart"></canvas></div>
</div>
</div>
<div class="col-lg-4">
<div class="etd-chart-card">
<h6 class="etd-chart-title">By action type</h6>
<div class="etd-chart-wrap etd-chart-wrap-sm"><canvas id="actionDoughnutChart"></canvas></div>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-6">
<div class="etd-chart-card">
<h6 class="etd-chart-title">Top modules</h6>
<div class="etd-chart-wrap etd-chart-wrap-sm"><canvas id="moduleBarChart"></canvas></div>
</div>
</div>
<div class="col-lg-6">
<div class="etd-chart-card">
<h6 class="etd-chart-title">Most active employees</h6>
<div class="etd-chart-wrap etd-chart-wrap-sm"><canvas id="employeeBarChart"></canvas></div>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-5">
<div class="etd-chart-card">
<h6 class="etd-chart-title">Employee ranking</h6>
<ul class="etd-rank-list">
<?php foreach ($dash['top_employees'] as $row) {
    $pct = $topEmpMax > 0 ? round(((int) $row['cnt'] / $topEmpMax) * 100) : 0; ?>
<li class="etd-rank-item">
<div>
<div class="etd-rank-name"><?php echo htmlspecialchars($row['employee_name'] ?: 'User #' . $row['user_id']); ?></div>
<div class="etd-rank-meta"><?php echo htmlspecialchars($row['role'] ?? ''); ?></div>
</div>
<div class="etd-rank-bar-wrap"><div class="etd-rank-bar" style="width:<?php echo (int) $pct; ?>%"></div></div>
<strong><?php echo number_format((int) $row['cnt']); ?></strong>
</li>
<?php } ?>
<?php if (empty($dash['top_employees'])) { ?>
<li class="text-muted py-3">No activity in this period.</li>
<?php } ?>
</ul>
</div>
</div>
<div class="col-lg-7">
<div class="etd-chart-card">
<h6 class="etd-chart-title">Recent activity <a href="<?php echo htmlspecialchars($reportUrl); ?>" class="float-right small">View all</a></h6>
<div class="table-responsive">
<table class="table table-sm etd-recent-table mb-0">
<thead>
<tr>
<th>Time</th>
<th>Employee</th>
<th>Module</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($dash['recent'] as $r) {
    $al = $actionLabels[$r['action_type']] ?? $r['action_type']; ?>
<tr>
<td><?php echo htmlspecialchars(date('d M H:i', strtotime($r['created_at']))); ?></td>
<td><?php echo htmlspecialchars($r['employee_name']); ?></td>
<td><?php echo htmlspecialchars($r['module_name']); ?></td>
<td><span class="badge badge-secondary etd-badge-action"><?php echo htmlspecialchars($al); ?></span></td>
</tr>
<?php } ?>
<?php if (empty($dash['recent'])) { ?>
<tr><td colspan="4" class="text-center text-muted">No logs yet. Activity appears as users work in the system.</td></tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

</div>
<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>
(function () {
    var palette = ['#6366f1','#8b5cf6','#0ea5e9','#10b981','#f59e0b','#ef4444','#14b8a6','#ec4899'];
    var dailyLabels = <?php echo json_encode($dailyLabels); ?>;
    var dailyCounts = <?php echo json_encode($dailyCounts); ?>;
    var actionLabels = <?php echo json_encode($actionChartLabels); ?>;
    var actionCounts = <?php echo json_encode($actionChartCounts); ?>;
    var moduleLabels = <?php echo json_encode($moduleLabels); ?>;
    var moduleCounts = <?php echo json_encode($moduleCounts); ?>;
    var empLabels = <?php echo json_encode($topEmpLabels); ?>;
    var empCounts = <?php echo json_encode($topEmpCounts); ?>;

    if (document.getElementById('dailyLineChart') && dailyLabels.length) {
        new Chart(document.getElementById('dailyLineChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Events',
                    data: dailyCounts,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#4f46e5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    if (document.getElementById('actionDoughnutChart') && actionLabels.length) {
        new Chart(document.getElementById('actionDoughnutChart'), {
            type: 'doughnut',
            data: {
                labels: actionLabels,
                datasets: [{ data: actionCounts, backgroundColor: palette, borderWidth: 0 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
    }

    function horizontalBar(canvasId, labels, counts, color) {
        if (!document.getElementById(canvasId) || !labels.length) return;
        new Chart(document.getElementById(canvasId), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: color,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    horizontalBar('moduleBarChart', moduleLabels, moduleCounts, 'rgba(14, 165, 233, 0.75)');
    horizontalBar('employeeBarChart', empLabels, empCounts, 'rgba(124, 58, 237, 0.75)');
})();
</script>
</body>
</html>
