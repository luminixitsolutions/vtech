<?php
/**
 * One-time: php migrations/add_food_to_tbl_trip_details.php
 */
include dirname(__DIR__) . '/config.php';

$col = $conn->query("SHOW COLUMNS FROM tbl_trip_details LIKE 'Food'");
if ($col && $col->num_rows > 0) {
    echo "OK Food already exists\n";
    exit;
}

$sql = "ALTER TABLE tbl_trip_details ADD COLUMN Food DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER ChallanPaidBy";
if (!$conn->query($sql)) {
    die($conn->error . "\n");
}

echo "OK Food column added\n";
