<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

header('Content-Type: application/json');

$user_id      = (int)($_POST['user_id'] ?? 0);
$cif_no       = trim($_POST['cif_no'] ?? '');
$cif_date     = trim($_POST['cif_date'] ?? '');
$cif_complete = trim(strtolower($_POST['cif_complete'] ?? ''));

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user.']);
    exit;
}

$cif_complete = in_array($cif_complete, ['yes', 'no']) ? $cif_complete : '';

$cif_no_safe   = $conn->real_escape_string($cif_no);
$cif_date_safe = $cif_date ? $conn->real_escape_string($cif_date) : 'NULL';
$cif_complete_safe = $conn->real_escape_string($cif_complete);

$set_date = $cif_date_safe === 'NULL' ? "CifDate = NULL" : "CifDate = '$cif_date_safe'";

$sql = "UPDATE tbl_users SET 
        CifNo = '$cif_no_safe',
        $set_date,
        CifComplete = '$cif_complete_safe'
        WHERE id = '$user_id'";

if ($conn->query($sql)) {
    $redirect = ($cif_complete === 'yes') ? 'complete-cif.php' : null;
    echo json_encode(['success' => true, 'message' => 'CIF saved successfully.', 'redirect' => $redirect]);
} else {
    echo json_encode(['success' => false, 'message' => 'Save failed: ' . $conn->error]);
}
