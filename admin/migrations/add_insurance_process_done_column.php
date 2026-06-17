<?php
/**
 * Add insurance process tracking columns to tbl_service_complaint.
 * Run once: php admin/migrations/add_insurance_process_done_column.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-insurance-service-complaint-data.php';

insuranceServiceComplaintEnsureSchema($conn);
echo "Insurance process columns ensured on tbl_service_complaint.\n";
