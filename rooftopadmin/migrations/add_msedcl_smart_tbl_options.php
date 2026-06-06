<?php
/**
 * One-time: php migrations/add_msedcl_smart_tbl_options.php
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';
require_once dirname(__DIR__) . '/inc-msedcl-smart-site.php';

menuAccessSyncBuiltinOptionsToDb();
msedclSmartEnsureTables();

echo "MSEDCL SMART PROJECT options ensured (188-193).\n";
