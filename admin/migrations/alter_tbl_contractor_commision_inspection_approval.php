<?php
/**
 * One-time: php migrations/alter_tbl_contractor_commision_inspection_approval.php
 */
include dirname(__DIR__) . '/config.php';

$col = $conn->query("SHOW COLUMNS FROM tbl_contractor_commision LIKE 'InspectionApprovalVal'");
if ($col && $col->num_rows > 0) {
    echo "OK InspectionApprovalVal already exists\n";
    exit(0);
}

$sql = "ALTER TABLE tbl_contractor_commision
    ADD COLUMN InspectionApprovalVal DECIMAL(12,2) NOT NULL DEFAULT 0.00
    AFTER InspectionVal";

if (!$conn->query($sql)) {
    die($conn->error . "\n");
}

echo "OK tbl_contractor_commision.InspectionApprovalVal added\n";
