<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

header('Content-Type: application/json');

$user_id      = (int)($_POST['user_id'] ?? 0);
$aif_no       = trim($_POST['aif_no'] ?? '');
$aif_date     = trim($_POST['aif_date'] ?? '');
$aif_complete = trim(strtolower($_POST['aif_complete'] ?? ''));

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user.']);
    exit;
}

$aif_complete = in_array($aif_complete, ['yes', 'no']) ? $aif_complete : '';

$aif_no_safe   = $conn->real_escape_string($aif_no);
$aif_date_safe = $aif_date ? $conn->real_escape_string($aif_date) : 'NULL';
$aif_complete_safe = $conn->real_escape_string($aif_complete);

$set_date = $aif_date_safe === 'NULL' ? "AifDate = NULL" : "AifDate = '$aif_date_safe'";

$sql = "UPDATE tbl_users SET 
        AifNo = '$aif_no_safe',
        $set_date,
        AifComplete = '$aif_complete_safe'
        WHERE id = '$user_id'";

if ($conn->query($sql)) {
    $redirect = ($aif_complete === 'yes') ? 'complete-aif.php' : null;
    echo json_encode(['success' => true, 'message' => 'AIF saved successfully.', 'redirect' => $redirect]);
} else {
    echo json_encode(['success' => false, 'message' => 'Save failed: ' . $conn->error]);
}
