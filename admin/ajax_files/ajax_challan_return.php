<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
require_once '../inc-challan-return.php';

header('Content-Type: application/json');

$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($action === 'returnChallan') {
    $sellId = (int) ($_POST['sell_id'] ?? 0);
    $returnDate = trim((string) ($_POST['return_date'] ?? ''));
    $remarks = trim((string) ($_POST['remarks'] ?? ''));

    $result = challanReturnProcess($conn, $sellId, $returnDate, $remarks, $user_id);
    echo json_encode($result);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
