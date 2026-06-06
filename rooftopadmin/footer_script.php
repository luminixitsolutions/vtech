    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <?php
    $__rooftop_script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string) $_SERVER['SCRIPT_NAME']) : '';
    $__top_header_js = (strpos($__rooftop_script, '/rooftopadmin/') !== false) ? 'js/top-header-bar.js' : '';
    if ($__top_header_js !== '' && is_file(__DIR__ . '/' . $__top_header_js)) {
        $__th_depth = 0;
        if (preg_match('#/rooftopadmin/(.+)$#i', $__rooftop_script, $__th_m)) {
            $__th_depth = substr_count($__th_m[1], '/');
        }
        $__th_base = $__th_depth > 0 ? str_repeat('../', $__th_depth) : '';
        echo '<script src="' . htmlspecialchars($__th_base . $__top_header_js, ENT_QUOTES, 'UTF-8') . '"></script>' . "\n";
    }
    ?>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/ui_navs.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_validation.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/validate/validate.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/ui_notifications.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/growl/growl.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/toastr/toastr.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/buttons.html5.min.js"></script>









