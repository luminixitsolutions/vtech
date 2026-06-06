<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-menu-option-groups.php';

$user_id = (int) $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, Fname, Lname FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);
$Options = adminResolveMenuOptionsFromUserRow($row77);
$MainPage = 'Dashboard';
$Page = 'Search';

$q = trim((string) ($_GET['q'] ?? ''));
$qLower = strtolower($q);

/** @var array<int, array{title:string,url:string,group:string}> */
$links = [
    ['title' => 'Dashboard', 'url' => 'dashboard.php', 'group' => 'Home'],
    ['title' => 'Report Dashboard', 'url' => 'report_management/report-dashboard.php', 'group' => 'Reports'],
    ['title' => 'Employee Tracking Dashboard', 'url' => 'report_management/employee-tracking-dashboard.php', 'group' => 'Reports'],
    ['title' => 'Employee Tracking Report', 'url' => 'report_management/employee-tracking-report.php', 'group' => 'Reports'],
    ['title' => 'Add Employee', 'url' => 'user_management/add-employee.php', 'group' => 'Users'],
    ['title' => 'View Employees', 'url' => 'user_management/view-employee.php', 'group' => 'Users'],
    ['title' => 'Insurance Dashboard', 'url' => 'insurance-dashboard.php', 'group' => 'Insurance'],
    ['title' => 'Pending Insurance', 'url' => 'pending-insurance.php', 'group' => 'Insurance'],
];

$nameMap = menuAccessLoadOptionNames(getMenuOptionIdsFlat());
foreach ($nameMap as $optId => $optName) {
    if (!adminUserHasFullMenuAccess($Roll) && !in_array((string) $optId, $Options, true)) {
        continue;
    }
    $links[] = ['title' => $optName, 'url' => 'dashboard.php', 'group' => 'Menu option'];
}

$results = [];
if ($q !== '') {
    foreach ($links as $link) {
        if (strpos(strtolower($link['title']), $qLower) !== false
            || strpos(strtolower($link['group']), $qLower) !== false) {
            $results[] = $link;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Search</title>
<meta charset="utf-8">
<?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'header.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold mb-3">Search<?php if ($q !== '') { ?>: <?php echo htmlspecialchars($q); ?><?php } ?></h4>
<?php if ($q === '') { ?>
<p class="text-muted">Enter a keyword in the top search box to find pages and reports.</p>
<?php } elseif (empty($results)) { ?>
<p class="text-muted">No matching pages found.</p>
<?php } else { ?>
<ul class="list-group col-md-8">
<?php foreach ($results as $r) { ?>
<li class="list-group-item d-flex justify-content-between align-items-center">
<div>
<strong><?php echo htmlspecialchars($r['title']); ?></strong>
<small class="text-muted d-block"><?php echo htmlspecialchars($r['group']); ?></small>
</div>
<a href="<?php echo htmlspecialchars($r['url']); ?>" class="btn btn-sm btn-primary">Open</a>
</li>
<?php } ?>
</ul>
<?php } ?>
</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
</div>
<?php include_once 'footer_script.php'; ?>
</body>
</html>
