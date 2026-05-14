<?php
/**
 * One-time: php create_tbl_leave_request.php
 */
include dirname(__DIR__) . '/config.php';
$sql = "CREATE TABLE IF NOT EXISTS tbl_leave_request (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  UserId INT NOT NULL,
  FromDate DATE NOT NULL,
  ToDate DATE NOT NULL,
  LeaveDays DECIMAL(6,2) UNSIGNED NOT NULL DEFAULT 1.00,
  HalfSession VARCHAR(120) DEFAULT NULL,
  Reason VARCHAR(500) DEFAULT NULL,
  Attachment VARCHAR(500) DEFAULT NULL,
  Status VARCHAR(20) NOT NULL DEFAULT 'Pending',
  CreatedAt DATETIME NOT NULL,
  ApprovedBy INT DEFAULT NULL,
  ApprovedAt DATETIME DEFAULT NULL,
  ApprovedComment VARCHAR(1000) DEFAULT NULL,
  RejectedBy INT DEFAULT NULL,
  RejectedAt DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_user (UserId),
  KEY idx_status (Status),
  KEY idx_from (FromDate)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$conn->query($sql)) {
    die($conn->error . "\n");
}
echo "OK tbl_leave_request ready\n";
