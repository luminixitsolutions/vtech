<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-pump-installation-import.php';

if (!isset($_SESSION['Admin']['id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Unauthorized');
}

$headers = pumpInstallImportSampleHeaders();
$sampleRow = array(
    'BEN123456',
    'Sample Farmer Name',
    '9876543210',
    'farmer@example.com',
    'Village Address Line',
    '18.5204',
    '73.8567',
    'Sample Taluka',
    'Sample Village',
    'Sample District',
    '5 HP',
    'https://drive.google.com/sample',
    date('Y-m-d'),
    '123456789012345',
    'Yes',
    'Yes',
    'Yes',
    'Yes',
    'Yes',
    'No',
    'No',
    'No',
    'Yes',
    'Yes',
    'Yes',
    'No',
    'JV-001',
    date('Y-m-d'),
    'Yes',
    'No',
    'No',
    'No',
    'No',
    'No',
    'No',
);

$filename = 'pump-installation-import-sample.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');
fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
fputcsv($out, $headers);
fputcsv($out, $sampleRow);
fclose($out);
exit;
