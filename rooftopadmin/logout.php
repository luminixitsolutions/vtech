<?php
session_start();
include_once __DIR__ . '/config.php';
unset(
    $_SESSION['UserId'],
    $_SESSION['Admin'],
    $_SESSION['Roll'],
    $_SESSION['rooftop_login_otp_hash'],
    $_SESSION['rooftop_login_otp_expires'],
    $_SESSION['rooftop_login_pending_id'],
    $_SESSION['rooftop_login_phone_mask'],
    $_SESSION['rooftop_login_otp_attempts'],
    $_SESSION['rooftop_login_otp_prefill']
);
header('Location: index.php');
exit;
