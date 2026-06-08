<?php
/**
 * One-time: php migrations/create_tbl_driver_trip_billings.php
 */
include dirname(__DIR__) . '/config.php';

$sql = "CREATE TABLE IF NOT EXISTS driver_trip_billings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  TripDetails VARCHAR(255) NOT NULL DEFAULT '',
  EstimatedDistanceKm DECIMAL(12,2) DEFAULT NULL,
  TransportorId INT NOT NULL DEFAULT 0,
  TransportName VARCHAR(150) NOT NULL DEFAULT '',
  DriverId INT NOT NULL DEFAULT 0,
  DriverName VARCHAR(150) NOT NULL DEFAULT '',
  GadiNo VARCHAR(50) NOT NULL DEFAULT '',
  OutDate DATE NOT NULL,
  InDate DATE NOT NULL,
  OpeningReading DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  ClosingReading DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Fastag DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Challan DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  DieselPayment DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Food DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  PerDayRate DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  DieselRate DECIMAL(12,2) NOT NULL DEFAULT 93.00,
  TotalRunningKm DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  AvgVehicle DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  TotalDieselUsed DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Days DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  TotalAmount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  FinalBillingAmount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Status TINYINT NOT NULL DEFAULT 1,
  CreatedBy INT DEFAULT NULL,
  CreatedDate DATETIME NOT NULL,
  ModifiedBy INT DEFAULT NULL,
  ModifiedDate DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_driver (DriverId),
  KEY idx_transportor (TransportorId),
  KEY idx_out_date (OutDate),
  KEY idx_in_date (InDate),
  KEY idx_status (Status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($sql)) {
    die($conn->error . "\n");
}

echo "OK driver_trip_billings ready\n";
