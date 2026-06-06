<?php
if (!isset($_SESSION['Admin'])) {
    if (!empty($_SESSION['rooftop_login_pending_id']) && !empty($_SESSION['rooftop_login_otp_hash'])) {
        header('Location: verify-login-otp.php');
    } else {
        header('Location: index.php');
    }
    exit;
}
?>