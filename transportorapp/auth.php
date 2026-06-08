<?php
if (!isset($_SESSION['User']['id'])) {
    header('Location: ../driverapp/login.php');
    exit;
}
if ((int) ($_SESSION['User']['Roll'] ?? 0) !== 46) {
    header('Location: ../driverapp/home.php');
    exit;
}
