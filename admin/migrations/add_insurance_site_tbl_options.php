<?php
/**
 * One-time: php migrations/add_insurance_site_tbl_options.php
 * Adds Insurance Site submenu options (non-rooftop).
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncBuiltinOptionsToDb();

echo "Insurance site options ensured (168-173).\n";
