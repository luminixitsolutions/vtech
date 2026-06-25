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
$sampleRow = pumpInstallImportSampleRow();

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
