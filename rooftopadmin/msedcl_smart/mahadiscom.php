<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../inc-msedcl-smart-list-page.php';

$Page = 'MSEDCL-Smart-Mahadiscom';
msedclSmartRequireOption(MSEDCL_SMART_OPT_MAHADISCOM);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Applications on Mahadiscom Portal</title>
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
msedclSmartRenderListPage('mahadiscom', [
    'title' => 'Applications done on Mahadiscom Portal',
    'import_type' => 'mahadiscom',
    'sample_file' => '../sample_files/msedcl_smart_mahadiscom_sample.csv',
    'page_slug' => 'mahadiscom.php',
    'show_payment_btn' => true,
    'excel_columns_hint' => 'Beneficiary ID, Payment Yes/No (Yes = mark payment done and move to Survey Pending; No or blank = Mahadiscom only)',
    'show_delete_btn' => true,
    'datatable_export' => true,
]);
?>
<p class="container-fluid small text-muted">Tip: Upload Excel with Beneficiary ID and Payment Yes/No. Use <strong>Yes</strong> to mark Mahadiscom + payment in one row; <strong>No</strong> marks Mahadiscom only.</p>
</div>
<?php include_once __DIR__ . '/../footer_script.php'; ?>
<script src="js/msedcl-smart-import.js"></script>
<script src="js/msedcl-smart-list.js"></script>
</div>
</div>
</body>
</html>
