<?php
/**
 * One-time: php alter_tbl_leave_request_attachment.php
 * Adds Attachment, HalfSession; widens LeaveDays for half-day (0.5).
 */
include dirname(__DIR__) . '/config.php';

$c = $conn->query("SHOW COLUMNS FROM tbl_leave_request LIKE 'Attachment'");
if ($c && $c->num_rows === 0) {
    if (!$conn->query("ALTER TABLE tbl_leave_request ADD COLUMN Attachment VARCHAR(500) DEFAULT NULL AFTER Reason")) {
        die($conn->error . "\n");
    }
    echo "Added Attachment\n";
} else {
    echo "Attachment already present\n";
}

$c2 = $conn->query("SHOW COLUMNS FROM tbl_leave_request LIKE 'HalfSession'");
if ($c2 && $c2->num_rows === 0) {
    if (!$conn->query("ALTER TABLE tbl_leave_request ADD COLUMN HalfSession VARCHAR(120) DEFAULT NULL AFTER LeaveDays")) {
        die($conn->error . "\n");
    }
    echo "Added HalfSession\n";
} else {
    echo "HalfSession already present\n";
}

$r = $conn->query("SHOW COLUMNS FROM tbl_leave_request WHERE Field='LeaveDays'");
if ($r && $row = $r->fetch_assoc()) {
    if (stripos($row['Type'], 'decimal') === false) {
        if (!$conn->query("ALTER TABLE tbl_leave_request MODIFY COLUMN LeaveDays DECIMAL(6,2) UNSIGNED NOT NULL DEFAULT 1.00")) {
            die($conn->error . "\n");
        }
        echo "Modified LeaveDays to DECIMAL\n";
    } else {
        echo "LeaveDays already DECIMAL\n";
    }
}
echo "OK\n";
