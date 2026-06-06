<?php
/**
 * One-time: php migrations/add_report_submenu_tbl_options.php
 * Dedicated report permissions (previously bundled under parent report options).
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncBuiltinOptionsToDb();

echo "Report submenu options ensured (183-186, 160, 187).\n";
