<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once '../config.php';
include_once '../auth.php';
require_once __DIR__ . '/../inc-work-order-customer.php';

workOrderCustomerEnsureSchema($conn);

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Unauthorized.'));
    exit;
}

$action = isset($_POST['action']) ? trim((string) $_POST['action']) : '';
$custId = isset($_POST['cust_id']) ? (int) $_POST['cust_id'] : 0;

if ($action === 'get') {
    $data = workOrderCustomerLoad($conn, $custId);
    echo json_encode(array(
        'success' => true,
        'data' => $data,
        'can_edit' => workOrderCustomerIsSupported($conn),
        'has_date' => workOrderCustomerUsesInstallations($conn)
            ? workOrderInstallHasColumn($conn, 'WorkOrderDoneDate')
            : workOrderUsersHasColumn($conn, 'WorkOrderDoneDate'),
    ));
    exit;
}

if ($action === 'save') {
    $workOrderDone = isset($_POST['WorkOrderDone']) ? trim((string) $_POST['WorkOrderDone']) : 'No';
    $workOrderDoneDate = isset($_POST['WorkOrderDoneDate']) ? trim((string) $_POST['WorkOrderDoneDate']) : '';
    $result = workOrderCustomerSave($conn, $custId, $workOrderDone, $workOrderDoneDate);
    echo json_encode($result);
    exit;
}

echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
