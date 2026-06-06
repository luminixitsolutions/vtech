<?php
session_start();
include_once __DIR__ . '/config.php';
if (!empty($_SESSION['Admin']['id']) && function_exists('addEmployeeLog')) {
    addEmployeeLog(['action_type' => EMP_ACT_LOGOUT]);
}
unset(
    $_SESSION['UserId'],
    $_SESSION['Admin'],
    $_SESSION['Roll'],
    $_SESSION['admin_login_otp_hash'],
    $_SESSION['admin_login_otp_expires'],
    $_SESSION['admin_login_pending_id'],
    $_SESSION['admin_login_phone_mask'],
    $_SESSION['admin_login_otp_attempts'],
    $_SESSION['admin_login_otp_prefill']
);
header('Location: index.php');
exit;
