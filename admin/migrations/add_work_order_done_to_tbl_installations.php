<?php
/**
 * One-time: php migrations/add_work_order_done_to_tbl_installations.php
 * Or open in browser once while logged in to admin.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

include dirname(__DIR__) . '/config.php';

if (!$conn) {
    die("Database connection failed\n");
}

function migrationInstallColumnExists($conn, $column)
{
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    $result = $conn->query("SHOW COLUMNS FROM tbl_installations LIKE '$column'");

    return $result && $result->num_rows > 0;
}

function migrationUsersColumnExists($conn, $column)
{
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    $result = $conn->query("SHOW COLUMNS FROM tbl_users LIKE '$column'");

    return $result && $result->num_rows > 0;
}

function migrationAddInstallColumn($conn, $column, $definition, $preferredAfter = '')
{
    if (migrationInstallColumnExists($conn, $column)) {
        echo "SKIP $column already exists on tbl_installations\n";
        return true;
    }

    $sql = "ALTER TABLE tbl_installations ADD COLUMN $definition";
    if ($preferredAfter !== '' && migrationInstallColumnExists($conn, $preferredAfter)) {
        $preferredAfter = preg_replace('/[^a-zA-Z0-9_]/', '', $preferredAfter);
        $sql .= " AFTER `$preferredAfter`";
    }

    if (!$conn->query($sql)) {
        die("FAIL adding $column: " . $conn->error . "\nSQL: $sql\n");
    }

    echo "OK tbl_installations.$column added\n";
    return true;
}

@$conn->query("SET SESSION sql_mode = ''");

migrationAddInstallColumn(
    $conn,
    'WorkOrderDone',
    "WorkOrderDone VARCHAR(10) NOT NULL DEFAULT 'No'",
    'InstallStatus'
);

migrationAddInstallColumn(
    $conn,
    'WorkOrderDoneDate',
    "WorkOrderDoneDate DATE NULL DEFAULT NULL",
    'WorkOrderDone'
);

if (migrationInstallColumnExists($conn, 'WorkOrderDone')
    && migrationUsersColumnExists($conn, 'WorkOrderDone')
    && migrationUsersColumnExists($conn, 'WorkOrderDoneDate')) {
    $sql = "UPDATE tbl_installations ti
        INNER JOIN tbl_users tu ON tu.id = ti.CustId
        SET ti.WorkOrderDone = tu.WorkOrderDone,
            ti.WorkOrderDoneDate = tu.WorkOrderDoneDate
        WHERE ti.Type = 2
          AND tu.WorkOrderDone = 'Yes'";
    if (!$conn->query($sql)) {
        die('FAIL syncing WorkOrderDone from tbl_users: ' . $conn->error . "\n");
    }
    echo "OK WorkOrderDone synced from tbl_users to tbl_installations\n";
} else {
    echo "SKIP tbl_users WorkOrderDone sync\n";
}

echo "DONE\n";
