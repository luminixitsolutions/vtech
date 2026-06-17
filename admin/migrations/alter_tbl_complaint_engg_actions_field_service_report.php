<?php
/**
 * Add field service report columns to tbl_complaint_engg_actions.
 * Run once: php admin/migrations/alter_tbl_complaint_engg_actions_field_service_report.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-complaint-engg-action-report-fields.php';

complaintEnggActionReportEnsureSchema($conn);
echo "Field service report columns ensured on tbl_complaint_engg_actions.\n";
