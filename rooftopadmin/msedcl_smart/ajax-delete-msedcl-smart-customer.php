<?php
ob_start();
error_reporting(0);
session_start();
header('Content-Type: application/json; charset=utf-8');

function msedcl_smart_delete_response($payload)
{
    if (ob_get_length()) {
        ob_end_clean();
    }
    echo json_encode($payload);
    exit;
}

if (!isset($_SESSION['Admin']['id'])) {
    msedcl_smart_delete_response(['success' => false, 'message' => 'Session expired.']);
}

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

msedclSmartInitUserAccess();

$listType = isset($_POST['list_type']) ? trim((string) $_POST['list_type']) : '';
$optionId = msedclSmartOptionForListType($listType);
if ($optionId < 1 || !msedclSmartCanAccessOption($optionId)) {
    msedcl_smart_delete_response(['success' => false, 'message' => 'Access denied.']);
}

$user_id = (int) $_SESSION['Admin']['id'];
$customerId = isset($_POST['customer_id']) ? (int) $_POST['customer_id'] : 0;

if ($customerId < 1) {
    msedcl_smart_delete_response(['success' => false, 'message' => 'Invalid customer.']);
}

try {
    $res = msedclSmartDeleteCustomer($customerId, $user_id);
    msedcl_smart_delete_response([
        'success' => !empty($res['ok']),
        'message' => $res['message'] ?? (!empty($res['ok']) ? 'Deleted.' : 'Delete failed.'),
    ]);
} catch (Throwable $e) {
    msedcl_smart_delete_response(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
}
