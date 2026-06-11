<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../inc-msedcl-smart-list-page.php';

$Page = 'MSEDCL-Smart-PMSGY';
msedclSmartRequireOption(MSEDCL_SMART_OPT_PMSGY);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Applications on PMSGY Portal</title>
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
<?php
msedclSmartRenderListPage('pmsgy', [
    'title' => 'Applications on PMSGY Portal',
    'import_type' => 'pmsgy',
    'sample_file' => '../sample_files/msedcl_smart_pmsgy_sample.csv',
    'capacity_reference_file' => 'download-capacity-master-ids.php',
    'page_slug' => 'pmsgy.php',
    'show_consumer_no_col' => true,
    'show_mahadiscom_btn' => true,
    'show_delete_btn' => true,
    'datatable_export' => true,
]);
?>
</div>
<?php include_once __DIR__ . '/../footer_script.php'; ?>
<script src="js/msedcl-smart-import.js"></script>
<script src="js/msedcl-smart-list.js"></script>
</div>
</div>
</body>
</html>
