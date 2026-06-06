<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = (int) $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, Fname, Lname FROM tbl_users WHERE id='$user_id'");
$MainPage = 'Dashboard';
$Page = 'Search';

$q = trim((string) ($_GET['q'] ?? ''));
$qLower = strtolower($q);

$results = [];
if ($q !== '') {
    $links = [
        ['title' => 'Dashboard', 'url' => 'dashboard.php', 'group' => 'Home'],
        ['title' => 'Send WhatsApp SMS', 'url' => 'send-sms.php', 'group' => 'Messaging'],
        ['title' => 'Lead Management', 'url' => 'lead_management/lead-management-dashboard.php', 'group' => 'Leads'],
        ['title' => 'Master Management', 'url' => 'master_management/masters-dashboard.php', 'group' => 'Masters'],
        ['title' => 'Report Dashboard', 'url' => 'report_management/report-dashboard.php', 'group' => 'Reports'],
    ];
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
<h4 class="font-weight-bold mb-3">Search<?php if ($q !== '') { ?>: <?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?><?php } ?></h4>
<?php if ($q === '') { ?>
<p class="text-muted">Enter a keyword in the top search box to find pages and reports.</p>
<?php } elseif (empty($results)) { ?>
<p class="text-muted">No matching pages found. Try another keyword or use the sidebar menu.</p>
<?php } else { ?>
<ul class="list-group col-md-8">
<?php foreach ($results as $r) { ?>
<li class="list-group-item d-flex justify-content-between align-items-center">
<div>
<strong><?php echo htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8'); ?></strong>
<small class="text-muted d-block"><?php echo htmlspecialchars($r['group'], ENT_QUOTES, 'UTF-8'); ?></small>
</div>
<a href="<?php echo htmlspecialchars($r['url'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-primary">Open</a>
</li>
<?php } ?>
</ul>
<?php } ?>
</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once 'footer_script.php'; ?>
</body>
</html>
