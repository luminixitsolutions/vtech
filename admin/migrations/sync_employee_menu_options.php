<?php
/**
 * One-time backfill: assign tbl_users.Options to employees missing menu access,
 * using the same roll-based template as add-employee save.
 *
 * Run: php admin/migrations/sync_employee_menu_options.php
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

require dirname(__DIR__) . '/config.php';
require dirname(__DIR__) . '/inc-employee-menu-options.php';

$result = employeeMenuSyncMissingEmployeeOptions();

echo "Employee menu access sync\n";
echo "Updated: {$result['updated']}\n";
echo "Skipped (no template for roll): {$result['skipped']}\n";
if (!empty($result['errors'])) {
    echo "Errors:\n";
    foreach ($result['errors'] as $err) {
        echo "  - $err\n";
    }
}
