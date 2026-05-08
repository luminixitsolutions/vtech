<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

header('Content-Type: application/json');

$user_id  = (int)($_POST['user_id'] ?? 0);
$ntp_no   = trim($_POST['ntp_no'] ?? '');
$ntp_date = trim($_POST['ntp_date'] ?? '');

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user.']);
    exit;
}

if ($ntp_no === '' || $ntp_date === '') {
    echo json_encode(['success' => false, 'message' => 'NTP No and NTP Date are required.']);
    exit;
}

$ntp_no_safe   = $conn->real_escape_string($ntp_no);
$ntp_date_safe = $conn->real_escape_string($ntp_date);

$sql = "UPDATE tbl_users SET 
        NtpNo = '$ntp_no_safe',
        NtpDate = '$ntp_date_safe',
        NtpComplete = 'yes'
        WHERE id = '$user_id'";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'NTP saved successfully.', 'redirect' => 'complete-ntp-order.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Save failed: ' . $conn->error]);
}
