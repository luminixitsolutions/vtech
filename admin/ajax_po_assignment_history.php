<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-po-assignment-activity-log.php';

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(['ok' => false, 'error' => 'Access denied.']);
    exit;
}

$poId = isset($_POST['po_id']) ? (int) $_POST['po_id'] : 0;
if ($poId < 1) {
    echo json_encode(['ok' => false, 'error' => 'Invalid purchase order.']);
    exit;
}

$po = getRecord("SELECT InvoiceNo FROM tbl_purchase_order WHERE id='$poId' AND Status=1 LIMIT 1");
if (!is_array($po)) {
    echo json_encode(['ok' => false, 'error' => 'Purchase order not found.']);
    exit;
}

$invoice = isset($po['InvoiceNo']) ? (string) $po['InvoiceNo'] : '';
$html = renderPoAssignmentActivityLogHtml($conn, $poId);
echo json_encode([
    'ok' => true,
    'html' => $html,
    'title' => 'PO ' . $invoice . ' — assignment history',
]);
