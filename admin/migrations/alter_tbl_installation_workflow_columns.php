<?php
/**
 * Adds hierarchy + due-date columns for installation workflow.
 * Run once: php admin/migrations/alter_tbl_installation_workflow_columns.php
 */
include dirname(__DIR__) . '/config.php';
$conn->query("SET SESSION sql_mode = ''");

$flowColumns = [
    'manager_id' => "ALTER TABLE tbl_installation_flow ADD COLUMN manager_id INT(11) NOT NULL DEFAULT 0 AFTER assigned_to",
    'gm_id' => "ALTER TABLE tbl_installation_flow ADD COLUMN gm_id INT(11) NOT NULL DEFAULT 0 AFTER manager_id",
    'business_head_id' => "ALTER TABLE tbl_installation_flow ADD COLUMN business_head_id INT(11) NOT NULL DEFAULT 0 AFTER gm_id",
    'coordinator_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN coordinator_due_date DATETIME NULL AFTER stage_start_date",
    'manager_assigned_at' => "ALTER TABLE tbl_installation_flow ADD COLUMN manager_assigned_at DATETIME NULL AFTER coordinator_due_date",
    'manager_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN manager_due_date DATETIME NULL AFTER manager_assigned_at",
    'gm_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN gm_due_date DATETIME NULL AFTER manager_due_date",
    'business_head_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN business_head_due_date DATETIME NULL AFTER gm_due_date",
    'installed_at' => "ALTER TABLE tbl_installation_flow ADD COLUMN installed_at DATETIME NULL AFTER stage_end_date",
];

foreach ($flowColumns as $col => $sql) {
    $r = $conn->query("SHOW COLUMNS FROM tbl_installation_flow LIKE '$col'");
    if ($r && $r->num_rows) {
        echo "tbl_installation_flow.$col exists\n";
        continue;
    }
    if (!$conn->query($sql)) {
        die("Failed $col: " . $conn->error . "\n");
    }
    echo "Added tbl_installation_flow.$col\n";
}

$extColumns = [
    'remarks' => "ALTER TABLE tbl_installation_extensions ADD COLUMN remarks TEXT NULL AFTER extension_days",
    'gm_id' => "ALTER TABLE tbl_installation_extensions ADD COLUMN gm_id INT(11) NOT NULL DEFAULT 0 AFTER requested_by",
    'business_head_id' => "ALTER TABLE tbl_installation_extensions ADD COLUMN business_head_id INT(11) NOT NULL DEFAULT 0 AFTER gm_id",
];

foreach ($extColumns as $col => $sql) {
    $r = $conn->query("SHOW COLUMNS FROM tbl_installation_extensions LIKE '$col'");
    if ($r && $r->num_rows) {
        echo "tbl_installation_extensions.$col exists\n";
        continue;
    }
    if (!$conn->query($sql)) {
        die("Failed $col: " . $conn->error . "\n");
    }
    echo "Added tbl_installation_extensions.$col\n";
}

echo "OK\n";
