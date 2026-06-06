<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../inc-msedcl-smart-list-page.php';

$Page = 'MSEDCL-Smart-Survey-Pending';
msedclSmartRequireOption(MSEDCL_SMART_OPT_SURVEY_PENDING);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Survey Pending</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once __DIR__ . '/../header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once __DIR__ . '/msedcl-smart-sidebar.php'; ?>
<div class="layout-container">
<?php include_once __DIR__ . '/../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid"><div class="alert alert-info mt-3 mb-0">Customers appear here after payment is done. Telephonic &amp; Field survey status is updated from the same rooftop survey pages after the customer is forwarded to Co-ordinator assign.</div></div>
<?php
msedclSmartRenderListPage('survey_pending', [
    'title' => 'Survey Pending',
    'page_slug' => 'survey-pending.php',
    'show_survey_columns' => true,
    'datatable_export' => true,
]);
?>
</div>
<?php include_once __DIR__ . '/../footer_script.php'; ?>
<script src="js/msedcl-smart-list.js"></script>
</div>
</div>
</body>
</html>
