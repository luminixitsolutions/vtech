<?php
/**
 * One-time: php alter_tbl_leave_request_approved_comment.php
 * Adds ApprovedComment for admin note when approving leave.
 */
include dirname(__DIR__) . '/config.php';
$chk = $conn->query("SHOW COLUMNS FROM tbl_leave_request LIKE 'ApprovedComment'");
if ($chk && $chk->num_rows > 0) {
    echo "ApprovedComment column already exists.\n";
    exit(0);
}
$sql = "ALTER TABLE tbl_leave_request
  ADD COLUMN ApprovedComment VARCHAR(1000) DEFAULT NULL AFTER ApprovedAt";
if (!$conn->query($sql)) {
    die($conn->error . "\n");
}
echo "OK Added ApprovedComment\n";
