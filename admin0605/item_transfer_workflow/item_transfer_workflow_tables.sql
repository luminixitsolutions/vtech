-- Item Transfer Workflow Tables
-- Run this SQL in your database. Do not modify existing distribute-item tables.

CREATE TABLE IF NOT EXISTS `tbl_dispatch_to_store_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DispatchOfficerId` int(11) NOT NULL,
  `ToBranchId` int(11) NOT NULL,
  `TransferDate` date NOT NULL,
  `Narration` varchar(500) DEFAULT NULL,
  `CreatedBy` int(11) NOT NULL,
  `CreatedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `DispatchOfficerId` (`DispatchOfficerId`),
  KEY `ToBranchId` (`ToBranchId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_dispatch_to_store_transfer_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TransferId` int(11) NOT NULL,
  `Detail2Id` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Qty` decimal(15,2) NOT NULL DEFAULT 1,
  `SerialNo` varchar(255) DEFAULT NULL,
  `ProdType` tinyint(1) NOT NULL DEFAULT 0,
  `Unit` varchar(50) DEFAULT NULL,
  `ModelNo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `TransferId` (`TransferId`),
  KEY `Detail2Id` (`Detail2Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_store_to_store_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FromBranchId` int(11) NOT NULL,
  `ToBranchId` int(11) NOT NULL,
  `TransferDate` date NOT NULL,
  `Narration` varchar(500) DEFAULT NULL,
  `CreatedBy` int(11) NOT NULL,
  `CreatedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FromBranchId` (`FromBranchId`),
  KEY `ToBranchId` (`ToBranchId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_store_to_store_transfer_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `TransferId` int(11) NOT NULL,
  `ProductId` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Qty` decimal(15,2) NOT NULL DEFAULT 1,
  `SerialNo` varchar(255) DEFAULT NULL,
  `ProdType` tinyint(1) NOT NULL DEFAULT 0,
  `Unit` varchar(50) DEFAULT NULL,
  `ModelNo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `TransferId` (`TransferId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
