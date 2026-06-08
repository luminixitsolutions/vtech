<?php
/**
 * One-time: php migrations/add_work_order_done_to_tbl_users.php
 * Or open in browser once while logged in to admin.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

include dirname(__DIR__) . '/config.php';

if (!$conn) {
    die("Database connection failed\n");
}

function migrationUsersColumnExists($conn, $column)
{
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    $result = $conn->query("SHOW COLUMNS FROM tbl_users LIKE '$column'");

    return $result && $result->num_rows > 0;
}

function migrationAddUsersColumn($conn, $column, $definition, $preferredAfter = '')
{
    if (migrationUsersColumnExists($conn, $column)) {
        echo "SKIP $column already exists\n";
        return true;
    }

    $sql = "ALTER TABLE tbl_users ADD COLUMN $definition";
    if ($preferredAfter !== '' && migrationUsersColumnExists($conn, $preferredAfter)) {
        $preferredAfter = preg_replace('/[^a-zA-Z0-9_]/', '', $preferredAfter);
        $sql .= " AFTER `$preferredAfter`";
    }

    if (!$conn->query($sql)) {
        die("FAIL adding $column: " . $conn->error . "\nSQL: $sql\n");
    }

    echo "OK $column added\n";
    return true;
}

@$conn->query("SET SESSION sql_mode = ''");

migrationAddUsersColumn(
    $conn,
    'WorkOrderDone',
    "WorkOrderDone VARCHAR(10) NOT NULL DEFAULT 'No'",
    'WoNo'
);

migrationAddUsersColumn(
    $conn,
    'WorkOrderDoneDate',
    "WorkOrderDoneDate DATE NULL DEFAULT NULL",
    'WorkOrderDone'
);

if (migrationUsersColumnExists($conn, 'WorkOrderDone') && migrationUsersColumnExists($conn, 'WoNo')) {
    if (!$conn->query("UPDATE tbl_users SET WorkOrderDone='Yes' WHERE IFNULL(WorkOrderDone,'') IN ('','No') AND TRIM(IFNULL(WoNo,''))!=''")) {
        die('FAIL syncing WorkOrderDone from WoNo: ' . $conn->error . "\n");
    }
    echo "OK WorkOrderDone synced from existing WoNo values\n";
} else {
    echo "SKIP WorkOrderDone sync (WoNo column not found)\n";
}

echo "DONE\n";
