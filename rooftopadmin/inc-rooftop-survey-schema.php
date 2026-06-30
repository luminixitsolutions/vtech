<?php

/**
 * Ensure rooftop survey tables have columns for extended survey form fields.
 */

if (!function_exists('rooftopSurveyEnsureColumns')) {
    function rooftopSurveyEnsureColumns($conn)
    {
        static $done = false;
        if ($done) {
            return;
        }
        $done = true;

        $fieldColumns = [
            'FieldRoofType' => 'VARCHAR(50) NULL',
            'FieldEarthingDistance' => 'VARCHAR(100) NULL',
            'FieldPhase1ph' => 'VARCHAR(10) NULL',
            'FieldBankDetails' => 'VARCHAR(255) NULL',
            'FieldOngridKW' => 'VARCHAR(50) NULL',
            'FieldUploadDocYn' => 'VARCHAR(10) NULL',
            'FieldUploadDoc' => 'VARCHAR(255) NULL',
            'FieldSitePhotoYn' => 'VARCHAR(10) NULL',
            'FieldPanCardYn' => 'VARCHAR(10) NULL',
            'FieldAadharCardYn' => 'VARCHAR(10) NULL',
            'FieldElectricBillYn' => 'VARCHAR(10) NULL',
        ];
        $telColumns = [
            'TelRoofType' => 'VARCHAR(50) NULL',
            'TelEarthingDistance' => 'VARCHAR(100) NULL',
            'TelPhase1ph' => 'VARCHAR(10) NULL',
            'TelBankDetails' => 'VARCHAR(255) NULL',
            'TelOngridKW' => 'VARCHAR(50) NULL',
            'TelUploadDocYn' => 'VARCHAR(10) NULL',
            'TelUploadDoc' => 'VARCHAR(255) NULL',
            'TelSitePhotoYn' => 'VARCHAR(10) NULL',
            'TelPanCardYn' => 'VARCHAR(10) NULL',
            'TelAadharCardYn' => 'VARCHAR(10) NULL',
            'TelElectricBillYn' => 'VARCHAR(10) NULL',
        ];

        foreach ($fieldColumns as $col => $def) {
            rooftopSurveyAddColumnIfMissing($conn, 'tbl_rooftop_field_survey', $col, $def);
        }
        foreach ($telColumns as $col => $def) {
            rooftopSurveyAddColumnIfMissing($conn, 'tbl_rooftop_tel_survey', $col, $def);
        }
    }

    function rooftopSurveyAddColumnIfMissing($conn, $table, $column, $definition)
    {
        $safeTable = preg_replace('/[^a-z0-9_]/i', '', $table);
        $safeCol = preg_replace('/[^a-z0-9_]/i', '', $column);
        $check = $conn->query("SHOW COLUMNS FROM `$safeTable` LIKE '$safeCol'");
        if ($check && $check->num_rows === 0) {
            $conn->query("ALTER TABLE `$safeTable` ADD COLUMN `$safeCol` $definition");
        }
    }
}

rooftopSurveyEnsureColumns($conn);
