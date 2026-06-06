<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

header('Content-Type: application/json; charset=utf-8');

$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
$row77 = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];

if (!employeeActivityLogCanViewReport($Roll, $Options)) {
    echo json_encode(['ok' => false, 'message' => 'Access denied.']);
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['ok' => false, 'message' => 'Invalid log id.']);
    exit;
}

employeeActivityLogEnsureTable($conn);
$row = getRecord("SELECT * FROM tbl_employee_activity_logs WHERE id='$id' LIMIT 1");
if (!$row) {
    echo json_encode(['ok' => false, 'message' => 'Log not found.']);
    exit;
}

$labels = employeeActivityLogActionTypeOptions();
$row['action_label'] = $labels[$row['action_type']] ?? $row['action_type'];

echo json_encode(['ok' => true, 'row' => $row]);
