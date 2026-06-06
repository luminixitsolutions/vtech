<?php
if (!isset($_SESSION['User']) && !empty($_SESSION['Admin'])) {
    $_SESSION['User'] = $_SESSION['Admin'];
}
if (!isset($_SESSION['User'])) {
    echo "<script>window.location.href='login.php';</script>";
}
?>