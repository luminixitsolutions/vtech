<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

header('Content-Type: text/html; charset=utf-8');
@set_time_limit(120);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'searchItems') {
    http_response_code(400);
    echo '<div class="alert alert-danger">Invalid request.</div>';
    exit;
}

$BranchId = (int) ($_POST['BranchId'] ?? 0);
$phase = $_POST['phase'] ?? 'products';

if ($BranchId < 1) {
    echo '<div class="alert alert-warning">Please select a store.</div>';
    exit;
}

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, BranchId FROM tbl_users WHERE id='$user_id' LIMIT 1");
$Roll = (int) ($row77['Roll'] ?? 0);
$userBranchId = (int) ($row77['BranchId'] ?? 0);
if ($Roll != 1 && $Roll != 7 && $BranchId !== $userBranchId) {
    http_response_code(403);
    echo '<div class="alert alert-danger">Unauthorized store.</div>';
    exit;
}

$row7 = ['Narration' => ''];

if ($phase === 'serials') {
    include __DIR__ . '/inc-distribute-item-store-executive-2-data.php';
    include __DIR__ . '/inc-distribute-item-store-executive-2-serials.php';
    exit;
}

include __DIR__ . '/inc-distribute-item-store-executive-2-data.php';
include __DIR__ . '/inc-distribute-item-store-executive-2-products.php';
