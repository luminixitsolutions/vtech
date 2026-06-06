<?php
ob_start();
error_reporting(0);
session_start();
header('Content-Type: application/json; charset=utf-8');

function msedcl_smart_forward_response($payload)
{
    if (ob_get_length()) {
        ob_end_clean();
    }
    echo json_encode($payload);
    exit;
}

if (!isset($_SESSION['Admin']['id'])) {
    msedcl_smart_forward_response(['success' => false, 'message' => 'Session expired.']);
}

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

msedclSmartInitUserAccess();
if (!msedclSmartCanAccessOption(MSEDCL_SMART_OPT_PAYMENT)) {
    msedcl_smart_forward_response(['success' => false, 'message' => 'Access denied.']);
}

$user_id = (int) $_SESSION['Admin']['id'];
$customerIds = [];

if (isset($_POST['customer_ids']) && is_array($_POST['customer_ids'])) {
    $customerIds = $_POST['customer_ids'];
} elseif (isset($_POST['customer_ids']) && is_string($_POST['customer_ids'])) {
    $decoded = json_decode($_POST['customer_ids'], true);
    if (is_array($decoded)) {
        $customerIds = $decoded;
    }
} elseif (isset($_POST['customer_ids']) && is_numeric($_POST['customer_ids'])) {
    $customerIds = [(int) $_POST['customer_ids']];
} else {
    foreach ($_POST as $key => $value) {
        if (preg_match('/^customer_ids(?:\[\d*\])?$/', (string) $key)) {
            if (is_array($value)) {
                $customerIds = array_merge($customerIds, $value);
            } elseif ((string) $value !== '') {
                $customerIds[] = $value;
            }
        }
    }
}
$customerIds = array_values(array_unique(array_filter(array_map('intval', $customerIds))));

if (empty($customerIds)) {
    msedcl_smart_forward_response(['success' => false, 'message' => 'Please select at least one customer to forward.']);
}

try {
    $result = msedclSmartForwardCustomersToCoordinator($customerIds, $user_id);
    msedcl_smart_forward_response($result);
} catch (Throwable $e) {
    msedcl_smart_forward_response(['success' => false, 'message' => 'Forward failed: ' . $e->getMessage()]);
}
