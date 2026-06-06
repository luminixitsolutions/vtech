<?php
/**
 * One-time: php migrations/add_insurance_site_tbl_options.php
 * Ensures Insurance Site submenu options (shared with admin).
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncBuiltinOptionsToDb();

echo "Insurance site options ensured (168-173).\n";
