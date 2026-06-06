<?php
ob_start();
error_reporting(0);
ini_set('display_errors', '0');

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['Admin']['id'])) {
    ob_end_clean();
    echo json_encode(array('success' => false, 'message' => 'Session expired. Please login again.'));
    exit;
}

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/inc-rooftop-insurance-site.php';

$user_id = (int) $_SESSION['Admin']['id'];

function insurance_import_response($payload)
{
    ob_end_clean();
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    insurance_import_response(array('success' => false, 'message' => 'Invalid request.'));
}

if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
    insurance_import_response(array('success' => false, 'message' => 'Please select an Excel file.'));
}

$originalName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : 'import.xlsx';
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
if (!in_array($ext, array('xls', 'xlsx', 'csv'), true)) {
    insurance_import_response(array('success' => false, 'message' => 'Invalid file type. Upload .xlsx, .xls, or .csv only.'));
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

$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$safeName = 'pending_insurance_import_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$targetPath = $uploadDir . '/' . $safeName;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    insurance_import_response(array('success' => false, 'message' => 'Could not save uploaded file.'));
}

$result = insuranceImportProcessSpreadsheet(
    $targetPath,
    $originalName,
    $fileType,
    'insuranceGetPendingCustomerByBeneficiaryId',
    function ($customer, $data) use ($user_id, $originalName) {
        insuranceMarkCustomerCompleted($customer['CustId'], $data, $user_id, $customer, array(
            'process_type' => 'Excel Import',
            'process_status' => 'Completed',
            'source_file' => $originalName,
            'remarks' => 'Insurance completed via Excel import',
        ));
    },
    array(
        'require_issue_date' => true,
        'require_years' => true,
        'not_found_message' => 'Not found in pending insurance list.',
    )
);

@unlink($targetPath);

if (!$result['success']) {
    insurance_import_response(array(
        'success' => false,
        'message' => $result['message'],
        'imported' => 0,
        'skipped' => 0,
        'errors' => array(),
    ));
}

$imported = (int) $result['imported'];
$skipped = (int) $result['skipped'];
$errors = isset($result['errors']) ? $result['errors'] : array();

if ($imported === 0) {
    $message = 'No records imported.';
    if (!empty($errors)) {
        $message .= ' ' . implode(' ', array_slice($errors, 0, 3));
    }
    insurance_import_response(array(
        'success' => false,
        'message' => $message,
        'imported' => 0,
        'skipped' => $skipped,
        'errors' => array_slice($errors, 0, 20),
    ));
}

insurance_import_response(array(
    'success' => true,
    'message' => $imported . ' record(s) imported successfully.',
    'imported' => $imported,
    'skipped' => $skipped,
    'errors' => array_slice($errors, 0, 20),
    'redirect' => 'completed-insurance.php?imported=' . $imported,
));
