<?php
session_start();
include_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['Username'], $_POST['Password'])) {
    echo json_encode(['Status' => 0]);
    exit;
}

$username = trim((string) $_POST['Username']);
$password = (string) $_POST['Password'];

$username_esc = $conn->real_escape_string($username);
$password_esc = $conn->real_escape_string($password);

$query = "SELECT * FROM tbl_users WHERE (Phone = '$username_esc' OR EmailId='$username_esc') AND Password = '$password_esc' AND Status=1";
$result = $conn->query($query);
if (!$result) {
    echo json_encode(['Status' => 0]);
    exit;
}

$rncnt = mysqli_num_rows($result);
$row = $result->fetch_assoc();

if ($rncnt <= 0 || !$row) {
    unset(
        $_SESSION['Admin'],
        $_SESSION['Roll'],
        $_SESSION['admin_login_otp_hash'],
        $_SESSION['admin_login_otp_expires'],
        $_SESSION['admin_login_pending_id'],
        $_SESSION['admin_login_phone_mask'],
        $_SESSION['admin_login_otp_attempts'],
        $_SESSION['admin_login_otp_prefill']
    );
    echo json_encode(['Status' => 0]);
    exit;
}

unset($_SESSION['Admin'], $_SESSION['Roll']);

$otp = (string) random_int(100000, 999999);
$_SESSION['admin_login_otp_hash'] = password_hash($otp, PASSWORD_DEFAULT);
$_SESSION['admin_login_otp_expires'] = time() + 600;
$_SESSION['admin_login_pending_id'] = (int) $row['id'];
$_SESSION['admin_login_otp_attempts'] = 0;
if (!empty($AdminLoginOtpDevPrefill)) {
    $_SESSION['admin_login_otp_prefill'] = $otp;
} else {
    unset($_SESSION['admin_login_otp_prefill']);
}

$phone = preg_replace('/\D/', '', (string) ($row['Phone'] ?? ''));
$masked = strlen($phone) >= 4
    ? str_repeat('*', max(0, strlen($phone) - 4)) . substr($phone, -4)
    : '****';
$_SESSION['admin_login_phone_mask'] = $masked;

$smsEnabled = !empty($AdminLoginOtpSendSms);
$incPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'incsmsapi.php';
$incExists = is_file($incPath);
$shouldSendSms = $smsEnabled && strlen($phone) >= 10 && $incExists;

session_write_close();

echo json_encode(['Status' => 2, 'PhoneMask' => $masked]);

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
} elseif (function_exists('litespeed_finish_request')) {
    litespeed_finish_request();
} else {
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
    flush();
}

if (!$shouldSendSms) {
    error_log('Admin login OTP (user id ' . (int) $row['id'] . '): ' . $otp);
    exit;
}

session_start();
$prevOtp = $_SESSION['otp'] ?? null;
$_SESSION['otp'] = $otp;
ob_start();
include $incPath;
ob_end_clean();
unset($_SESSION['otp']);
if ($prevOtp !== null) {
    $_SESSION['otp'] = $prevOtp;
}
session_write_close();
exit;
