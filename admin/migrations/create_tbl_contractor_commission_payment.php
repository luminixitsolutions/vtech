<?php
/**
 * One-time: php migrations/create_tbl_contractor_commission_payment.php
 */
include dirname(__DIR__) . '/config.php';

$sql = "CREATE TABLE IF NOT EXISTS tbl_contractor_commission_payment (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ContractorId INT NOT NULL,
  Amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  PaymentDate DATE NOT NULL,
  PaymentMode VARCHAR(50) DEFAULT NULL,
  ReferenceNo VARCHAR(100) DEFAULT NULL,
  Narration VARCHAR(500) DEFAULT NULL,
  Status TINYINT NOT NULL DEFAULT 1,
  CreatedBy INT DEFAULT NULL,
  CreatedDate DATETIME NOT NULL,
  ModifiedBy INT DEFAULT NULL,
  ModifiedDate DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_contractor (ContractorId),
  KEY idx_payment_date (PaymentDate),
  KEY idx_status (Status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($sql)) {
    die($conn->error . "\n");
}

echo "OK tbl_contractor_commission_payment ready\n";
