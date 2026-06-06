<?php
ob_start();
error_reporting(0);
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['Admin']['id'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Session expired.']);
    exit;
}

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

$user_id = (int) $_SESSION['Admin']['id'];
$customerId = isset($_POST['customer_id']) ? (int) $_POST['customer_id'] : 0;
$action = isset($_POST['action']) ? trim((string) $_POST['action']) : '';

ob_end_clean();

if ($customerId < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid customer.']);
    exit;
}

if ($action === 'mahadiscom') {
    $res = msedclSmartMarkMahadiscom($customerId, $user_id, '', 'Marked via list');
} elseif ($action === 'payment') {
    $res = msedclSmartMarkPaymentDone($customerId, $user_id, '', 'Payment marked via list');
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

echo json_encode([
    'success' => !empty($res['ok']),
    'message' => !empty($res['ok']) ? 'Updated successfully.' : ($res['message'] ?? 'Update failed.'),
]);
