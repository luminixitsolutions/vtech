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

include_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/php-excel-reader/excel_reader2.php';
require_once __DIR__ . '/vendor/SpreadsheetReader.php';

function coordinator_import_json_response($payload)
{
    ob_end_clean();
    echo json_encode($payload);
    exit;
}

function coordinator_import_normalize_id($value)
{
    return strtoupper(trim((string) $value));
}

function coordinator_import_is_header_cell($value)
{
    return (bool) preg_match('/beneficiary\s*id/i', trim((string) $value));
}

function coordinator_import_looks_like_id($value)
{
    $value = coordinator_import_normalize_id($value);
    if ($value === '' || coordinator_import_is_header_cell($value)) {
        return false;
    }
    if (preg_match('/^sample_coordinator|assign_beneficia/i', $value)) {
        return false;
    }
    return (bool) preg_match('/^[A-Z]{2}\d{10,}$/i', $value) || strlen($value) >= 8;
}

function coordinator_import_find_column_index($row)
{
    if (!is_array($row)) {
        return 0;
    }
    foreach ($row as $idx => $cell) {
        if (coordinator_import_is_header_cell($cell)) {
            return (int) $idx;
        }
    }
    return 0;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    coordinator_import_json_response(['success' => false, 'message' => 'Invalid request.']);
}

if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
    $uploadErr = isset($_FILES['file']['error']) ? (int) $_FILES['file']['error'] : -1;
    $msg = 'Please select an Excel file.';
    if ($uploadErr === UPLOAD_ERR_INI_SIZE || $uploadErr === UPLOAD_ERR_FORM_SIZE) {
        $msg = 'File is too large. Try a smaller Excel file.';
    }
    coordinator_import_json_response(['success' => false, 'message' => $msg]);
}

$originalName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : 'import.xlsx';
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
if (!in_array($ext, ['xls', 'xlsx', 'csv'], true)) {
    coordinator_import_json_response(['success' => false, 'message' => 'Invalid file type. Upload .xlsx, .xls, or .csv only.']);
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

$safeName = 'coordinator_import_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$targetPath = $uploadDir . '/' . $safeName;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    coordinator_import_json_response(['success' => false, 'message' => 'Could not save uploaded file. Check uploads folder permissions.']);
}

$beneficiaryIds = [];
$colIndex = null;
$headerSkipped = false;

try {
    $Reader = new SpreadsheetReader($targetPath, $originalName, $fileType);
    $sheetCount = count($Reader->sheets());

    for ($s = 0; $s < $sheetCount; $s++) {
        $Reader->ChangeSheet($s);
        foreach ($Reader as $Row) {
            if (!is_array($Row)) {
                continue;
            }

            if ($colIndex === null) {
                $colIndex = coordinator_import_find_column_index($Row);
                if (coordinator_import_is_header_cell(isset($Row[$colIndex]) ? $Row[$colIndex] : '')) {
                    $headerSkipped = true;
                    continue;
                }
            }

            $val = isset($Row[$colIndex]) ? coordinator_import_normalize_id($Row[$colIndex]) : '';
            if ($val === '') {
                continue;
            }
            if (!$headerSkipped && coordinator_import_is_header_cell($val)) {
                $headerSkipped = true;
                continue;
            }
            if (!coordinator_import_looks_like_id($val)) {
                continue;
            }
            $beneficiaryIds[] = $val;
        }
        if (!empty($beneficiaryIds)) {
            break;
        }
    }
} catch (Throwable $e) {
    @unlink($targetPath);
    coordinator_import_json_response(['success' => false, 'message' => 'Could not read Excel file. Save as .xlsx or .csv and try again.']);
}

@unlink($targetPath);

$beneficiaryIds = array_values(array_unique($beneficiaryIds));

if (empty($beneficiaryIds)) {
    coordinator_import_json_response(['success' => false, 'message' => 'No beneficiary IDs found. Use column "Beneficiary ID" with IDs like MT4421800874593.']);
}

coordinator_import_json_response([
    'success' => true,
    'message' => count($beneficiaryIds) . ' beneficiary ID(s) read from file.',
    'beneficiary_ids' => $beneficiaryIds,
    'count' => count($beneficiaryIds),
]);
