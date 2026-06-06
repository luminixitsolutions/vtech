<?php
/**
 * One-time: php migrations/add_installation_workflow_tbl_options.php
 */
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-menu-option-groups.php';

menuAccessSyncBuiltinOptionsToDb();

echo "Installation workflow options ensured (174-182).\n";
