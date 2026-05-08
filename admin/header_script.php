<?php
$__script_name = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string)$_SERVER['SCRIPT_NAME']) : '';
if (!empty($SiteUrl) && strpos($__script_name, '/item_transfer_workflow/') !== false) {
    echo '<base href="' . htmlspecialchars(rtrim($SiteUrl, '/') . '/', ENT_QUOTES, 'UTF-8') . '">' . "\n";
}
?>
<link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
<!-- Libs -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/growl/growl.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/toastr/toastr.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

  