<?php
if (!isset($_SESSION['Admin'])) {
    header('Location:https://vtechsolar.in/vtechnewcode/admin/index.php');
    exit;
}

if (is_file(__DIR__ . '/inc-page-access.php')) {
    require_once __DIR__ . '/inc-page-access.php';
    enforceAdminPageAccess();
}
?>