<?php
/**
 * One-time: php migrations/add_employee_tracking_tbl_options.php
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncBuiltinOptionsToDb();

echo "Employee Tracking option ensured (id=187).\n";
