<?php
/**
 * Employee project assignment columns on tbl_user2 (extended profile).
 * One-time: php migrations/add_employee_mul_project_columns.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

include dirname(__DIR__) . '/config.php';

if (!$conn) {
    die("Database connection failed\n");
}

function migrationTableColumnExists($conn, $table, $column)
{
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");

    return $result && $result->num_rows > 0;
}

function migrationAddTableColumn($conn, $table, $column, $definition, $preferredAfter = '')
{
    if (migrationTableColumnExists($conn, $table, $column)) {
        echo "SKIP $table.$column already exists\n";
        return true;
    }

    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $sql = "ALTER TABLE `$table` ADD COLUMN $definition";
    if ($preferredAfter !== '' && migrationTableColumnExists($conn, $table, $preferredAfter)) {
        $preferredAfter = preg_replace('/[^a-zA-Z0-9_]/', '', $preferredAfter);
        $sql .= " AFTER `$preferredAfter`";
    }

    if (!$conn->query($sql)) {
        die("FAIL adding $table.$column: " . $conn->error . "\nSQL: $sql\n");
    }

    echo "OK $table.$column added\n";
    return true;
}

@$conn->query("SET SESSION sql_mode = ''");

migrationAddTableColumn(
    $conn,
    'tbl_user2',
    'MulProjectId',
    "MulProjectId VARCHAR(255) NOT NULL DEFAULT '0'",
    'OfficeEmployee'
);

migrationAddTableColumn(
    $conn,
    'tbl_user2',
    'MulProjectSubHeadId',
    "MulProjectSubHeadId VARCHAR(500) NOT NULL DEFAULT '0'",
    'MulProjectId'
);

// Copy any data previously saved on tbl_users into tbl_user2.
if (migrationTableColumnExists($conn, 'tbl_users', 'MulProjectId')) {
    $sql = "INSERT INTO tbl_user2 (id, MulProjectId, MulProjectSubHeadId)
        SELECT tu.id,
            IFNULL(NULLIF(TRIM(tu.MulProjectId), ''), '0'),
            IFNULL(NULLIF(TRIM(tu.MulProjectSubHeadId), ''), '0')
        FROM tbl_users tu
        WHERE IFNULL(NULLIF(TRIM(tu.MulProjectId), ''), '0') <> '0'
           OR IFNULL(NULLIF(TRIM(tu.MulProjectSubHeadId), ''), '0') <> '0'
        ON DUPLICATE KEY UPDATE
            MulProjectId = IF(
                VALUES(MulProjectId) <> '0',
                VALUES(MulProjectId),
                tbl_user2.MulProjectId
            ),
            MulProjectSubHeadId = IF(
                VALUES(MulProjectSubHeadId) <> '0',
                VALUES(MulProjectSubHeadId),
                tbl_user2.MulProjectSubHeadId
            )";
    if ($conn->query($sql)) {
        echo "OK copied project assignment from tbl_users to tbl_user2\n";
    } else {
        echo "WARN copy from tbl_users: " . $conn->error . "\n";
    }
}

echo "DONE\n";
