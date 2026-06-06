<?php
/**
 * One-time: php migrations/add_granular_menu_tbl_options.php
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncGranularOptionsToDb();

echo "Granular menu options ensured (188-253).\n";
