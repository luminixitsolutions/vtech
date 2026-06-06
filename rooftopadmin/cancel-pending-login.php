<?php
session_start();
unset(
    $_SESSION['rooftop_login_otp_hash'],
    $_SESSION['rooftop_login_otp_expires'],
    $_SESSION['rooftop_login_pending_id'],
    $_SESSION['rooftop_login_phone_mask'],
    $_SESSION['rooftop_login_otp_attempts'],
    $_SESSION['rooftop_login_otp_prefill']
);
header('Location: index.php');
exit;
