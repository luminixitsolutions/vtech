<?php
/**
 * One-time: php migrations/create_tbl_insurance_site_history.php
 */
include dirname(__DIR__) . '/config.php';

$sql = "CREATE TABLE IF NOT EXISTS tbl_insurance_site_history (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  CustId INT NOT NULL,
  BeneficiaryId VARCHAR(50) DEFAULT NULL,
  CustName VARCHAR(255) DEFAULT NULL,
  CellNo VARCHAR(50) DEFAULT NULL,
  ProjectType TINYINT DEFAULT NULL,
  Taluka VARCHAR(100) DEFAULT NULL,
  Village VARCHAR(100) DEFAULT NULL,
  District VARCHAR(100) DEFAULT NULL,
  Address TEXT,
  SiteDispatchDate DATE DEFAULT NULL,
  InsuranceCompany VARCHAR(255) DEFAULT NULL,
  PolicyNo VARCHAR(100) DEFAULT NULL,
  DateOfIssue DATE DEFAULT NULL,
  DateOfExpiry DATE DEFAULT NULL,
  NoOfYear VARCHAR(20) DEFAULT NULL,
  ProcessType VARCHAR(50) NOT NULL DEFAULT 'Excel Import',
  ProcessStatus VARCHAR(50) NOT NULL DEFAULT 'Completed',
  CompletedDate DATE NOT NULL,
  CompletedDateTime DATETIME NOT NULL,
  ProcessedBy INT NOT NULL,
  ProcessedByName VARCHAR(255) DEFAULT NULL,
  SourceFile VARCHAR(255) DEFAULT NULL,
  Remarks VARCHAR(500) DEFAULT NULL,
  CreatedDate DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_cust (CustId),
  KEY idx_beneficiary (BeneficiaryId),
  KEY idx_completed (CompletedDate)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($sql)) {
    die($conn->error . "\n");
}

echo "OK tbl_insurance_site_history ready\n";
