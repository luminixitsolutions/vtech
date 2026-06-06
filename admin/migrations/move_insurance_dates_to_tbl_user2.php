<?php
/**
 * Move InsuranceIssueDate & InsuranceYears from tbl_users to tbl_user2.
 * Run once: php migrations/move_insurance_dates_to_tbl_user2.php
 *
 * Note: Extended profile table in this project is tbl_user2 (not tbl_users2).
 */
include dirname(__DIR__) . '/config.php';

function insuranceMigrationColumnExists($table, $column)
{
    global $conn;
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
    $res = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $res && $res->num_rows > 0;
}

function insuranceMigrationAddUser2Columns()
{
    global $conn;
    if (!insuranceMigrationColumnExists('tbl_user2', 'InsuranceIssueDate')) {
        $conn->query('ALTER TABLE tbl_user2 ADD COLUMN InsuranceIssueDate DATE NULL DEFAULT NULL');
        echo "Added tbl_user2.InsuranceIssueDate\n";
    }
    if (!insuranceMigrationColumnExists('tbl_user2', 'InsuranceYears')) {
        $conn->query('ALTER TABLE tbl_user2 ADD COLUMN InsuranceYears VARCHAR(20) NULL DEFAULT NULL');
        echo "Added tbl_user2.InsuranceYears\n";
    }
}

function insuranceMigrationCopyFromUsers()
{
    global $conn;
    if (!insuranceMigrationColumnExists('tbl_users', 'InsuranceIssueDate')
        && !insuranceMigrationColumnExists('tbl_users', 'InsuranceYears')) {
        echo "tbl_users insurance date columns already removed — skip copy.\n";
        return;
    }

    $sql = "INSERT INTO tbl_user2 (id, InsuranceIssueDate, InsuranceYears)
        SELECT u.id,
               NULLIF(NULLIF(u.InsuranceIssueDate, ''), '0000-00-00'),
               NULLIF(TRIM(u.InsuranceYears), '')
        FROM tbl_users u
        WHERE (
            (u.InsuranceIssueDate IS NOT NULL AND u.InsuranceIssueDate != '' AND u.InsuranceIssueDate != '0000-00-00')
            OR (u.InsuranceYears IS NOT NULL AND TRIM(u.InsuranceYears) != '')
        )
        ON DUPLICATE KEY UPDATE
            InsuranceIssueDate = COALESCE(VALUES(InsuranceIssueDate), tbl_user2.InsuranceIssueDate),
            InsuranceYears = COALESCE(NULLIF(VALUES(InsuranceYears), ''), tbl_user2.InsuranceYears)";
    $conn->query($sql);
    echo "Copied insurance date fields into tbl_user2.\n";
}

function insuranceMigrationDropUsersColumns()
{
    global $conn;
    if (insuranceMigrationColumnExists('tbl_users', 'InsuranceIssueDate')) {
        $conn->query('ALTER TABLE tbl_users DROP COLUMN InsuranceIssueDate');
        echo "Dropped tbl_users.InsuranceIssueDate\n";
    }
    if (insuranceMigrationColumnExists('tbl_users', 'InsuranceYears')) {
        $conn->query('ALTER TABLE tbl_users DROP COLUMN InsuranceYears');
        echo "Dropped tbl_users.InsuranceYears\n";
    }
}

insuranceMigrationAddUser2Columns();
insuranceMigrationCopyFromUsers();
insuranceMigrationDropUsersColumns();

echo "Done.\n";
