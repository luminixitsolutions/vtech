<?php
ob_start();
error_reporting(0);
ini_set('display_errors', '0');

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['Admin']['id'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Session expired. Please login again.']);
    exit;
}

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

$user_id = (int) $_SESSION['Admin']['id'];
$importType = isset($_POST['import_type']) ? trim((string) $_POST['import_type']) : 'pmsgy';
$allowed = ['pmsgy', 'mahadiscom', 'payment'];
if (!in_array($importType, $allowed, true)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid import type.']);
    exit;
}

function msedcl_smart_import_response($payload)
{
    ob_end_clean();
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    msedcl_smart_import_response(['success' => false, 'message' => 'Invalid request.']);
}

if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
    msedcl_smart_import_response(['success' => false, 'message' => 'Please select an Excel file.']);
}

$originalName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : 'import.xlsx';
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
if (!in_array($ext, ['xls', 'xlsx', 'csv'], true)) {
    msedcl_smart_import_response(['success' => false, 'message' => 'Invalid file type. Upload .xlsx, .xls, or .csv only.']);
}

$fileType = isset($_FILES['file']['type']) ? $_FILES['file']['type'] : '';
if ($fileType === '' || $fileType === 'application/octet-stream') {
    if ($ext === 'xlsx') {
        $fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    } elseif ($ext === 'xls') {
        $fileType = 'application/vnd.ms-excel';
    } else {
        $fileType = 'text/csv';
    }
}

$uploadDir = __DIR__ . '/../uploads';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$safeName = 'msedcl_smart_' . $importType . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$targetPath = $uploadDir . '/' . $safeName;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    msedcl_smart_import_response(['success' => false, 'message' => 'Could not save uploaded file.']);
}

$result = msedclSmartProcessSpreadsheet($targetPath, $originalName, $fileType, $importType, $user_id);
@unlink($targetPath);

$redirects = [
    'pmsgy' => 'pmsgy.php',
    'mahadiscom' => 'mahadiscom.php',
    'payment' => 'payment.php',
];
$result['redirect'] = isset($redirects[$importType]) ? $redirects[$importType] : 'dashboard.php';

msedcl_smart_import_response($result);
