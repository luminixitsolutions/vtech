<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../inc-msedcl-smart-list-page.php';

$Page = 'MSEDCL-Smart-Payment';
msedclSmartRequireOption(MSEDCL_SMART_OPT_PAYMENT);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Payment Done by Customers</title>
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
msedclSmartRenderListPage('payment', [
    'title' => 'Payment Done by Customers',
    'import_type' => 'payment',
    'page_slug' => 'payment.php',
    'show_forward_btn' => true,
    'show_delete_btn' => true,
]);
?>
</div>
<?php include_once __DIR__ . '/../footer_script.php'; ?>
<script src="js/msedcl-smart-import.js"></script>
<script src="js/msedcl-smart-forward.js"></script>
</div>
</div>
</body>
</html>
