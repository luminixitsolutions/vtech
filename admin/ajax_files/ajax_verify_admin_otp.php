<?php
session_start();
include_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_login_pending_id']) || empty($_SESSION['admin_login_otp_hash'])) {
    echo json_encode(['Status' => 0, 'Msg' => 'Session expired. Please sign in again.']);
    exit;
}

if (time() > (int) ($_SESSION['admin_login_otp_expires'] ?? 0)) {
    unset(
        $_SESSION['admin_login_otp_hash'],
        $_SESSION['admin_login_otp_expires'],
        $_SESSION['admin_login_pending_id'],
        $_SESSION['admin_login_phone_mask'],
        $_SESSION['admin_login_otp_attempts'],
        $_SESSION['admin_login_otp_prefill']
    );
    echo json_encode(['Status' => 0, 'Msg' => 'OTP expired. Please sign in again.']);
    exit;
}

$otp = preg_replace('/\D/', '', trim((string) ($_POST['Otp'] ?? '')));
if ($otp === '' || !password_verify($otp, $_SESSION['admin_login_otp_hash'])) {
    $_SESSION['admin_login_otp_attempts'] = (int) ($_SESSION['admin_login_otp_attempts'] ?? 0) + 1;
    if ($_SESSION['admin_login_otp_attempts'] >= 5) {
        unset(
            $_SESSION['admin_login_otp_hash'],
            $_SESSION['admin_login_otp_expires'],
            $_SESSION['admin_login_pending_id'],
            $_SESSION['admin_login_phone_mask'],
            $_SESSION['admin_login_otp_attempts'],
            $_SESSION['admin_login_otp_prefill']
        );
        echo json_encode(['Status' => 0, 'Msg' => 'Too many attempts. Please sign in again.']);
        exit;
    }
    echo json_encode(['Status' => 0, 'Msg' => 'Invalid OTP']);
    exit;
}

$uid = (int) $_SESSION['admin_login_pending_id'];
$query = "SELECT * FROM tbl_users WHERE id='$uid' AND Status=1";
$result = $conn->query($query);
$row = $result ? $result->fetch_assoc() : null;

if (!$row) {
    unset(
        $_SESSION['admin_login_otp_hash'],
        $_SESSION['admin_login_otp_expires'],
        $_SESSION['admin_login_pending_id'],
        $_SESSION['admin_login_phone_mask'],
        $_SESSION['admin_login_otp_attempts'],
        $_SESSION['admin_login_otp_prefill']
    );
    echo json_encode(['Status' => 0, 'Msg' => 'User not found.']);
    exit;
}

$_SESSION['Admin'] = $row;
$_SESSION['Roll'] = $row['Roll'];

unset(
    $_SESSION['admin_login_otp_hash'],
    $_SESSION['admin_login_otp_expires'],
    $_SESSION['admin_login_pending_id'],
    $_SESSION['admin_login_phone_mask'],
    $_SESSION['admin_login_otp_attempts'],
    $_SESSION['admin_login_otp_prefill']
);

echo json_encode(['Status' => 1, 'Roll' => $row['Roll']]);
exit;
