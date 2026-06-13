<?php
/**
 * One-time: php migrations/create_tbl_project_cost.php
 */
include dirname(__DIR__) . '/config.php';

$sql = "CREATE TABLE IF NOT EXISTS tbl_project_cost (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ProjectId INT NOT NULL DEFAULT 0,
  ProjectSubHeadId INT NOT NULL DEFAULT 0,
  CapacityId INT NOT NULL DEFAULT 0,
  Amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Status TINYINT NOT NULL DEFAULT 1,
  CreatedDate DATE NULL DEFAULT NULL,
  ModifiedDate DATE NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_project (ProjectId),
  KEY idx_subhead (ProjectSubHeadId),
  KEY idx_capacity (CapacityId),
  KEY idx_status (Status),
  UNIQUE KEY uq_project_cost (ProjectId, ProjectSubHeadId, CapacityId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($sql)) {
    die('FAIL: ' . $conn->error . "\n");
}

echo "OK tbl_project_cost ready\n";
