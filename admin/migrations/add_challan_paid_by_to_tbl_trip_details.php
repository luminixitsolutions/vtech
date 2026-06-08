<?php
/**
 * One-time: php migrations/add_challan_paid_by_to_tbl_trip_details.php
 */
include dirname(__DIR__) . '/config.php';

$col = $conn->query("SHOW COLUMNS FROM tbl_trip_details LIKE 'ChallanPaidBy'");
if ($col && $col->num_rows > 0) {
    echo "OK ChallanPaidBy already exists\n";
    exit;
}

$sql = "ALTER TABLE tbl_trip_details ADD COLUMN ChallanPaidBy VARCHAR(50) NOT NULL DEFAULT '' AFTER Challan";
if (!$conn->query($sql)) {
    die($conn->error . "\n");
}

echo "OK ChallanPaidBy column added\n";
