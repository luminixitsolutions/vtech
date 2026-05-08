<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

header('Content-Type: application/json');

$user_id      = (int)($_POST['user_id'] ?? 0);
$loa_no       = trim($_POST['loa_no'] ?? '');
$loa_date     = trim($_POST['loa_date'] ?? '');
$loa_received = trim(strtolower($_POST['loa_received'] ?? ''));

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user.']);
    exit;
}

// Allow empty LOA No/Date; LOA Received can be yes/no/empty
$loa_received = in_array($loa_received, ['yes', 'no']) ? $loa_received : '';

$loa_no_safe   = $conn->real_escape_string($loa_no);
$loa_date_safe = $loa_date ? $conn->real_escape_string($loa_date) : 'NULL';
$loa_received_safe = $conn->real_escape_string($loa_received);

$set_date = $loa_date_safe === 'NULL' ? "LoaDate = NULL" : "LoaDate = '$loa_date_safe'";

$sql = "UPDATE tbl_users SET 
        LoaNo = '$loa_no_safe',
        $set_date,
        LoaReceived = '$loa_received_safe'
        WHERE id = '$user_id'";

if ($conn->query($sql)) {
    $redirect = ($loa_received === 'yes') ? 'received-loa.php' : null;
    echo json_encode(['success' => true, 'message' => 'LOA saved.', 'redirect' => $redirect]);
} else {
    echo json_encode(['success' => false, 'message' => 'Save failed: ' . $conn->error]);
}
