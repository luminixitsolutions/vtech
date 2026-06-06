<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-stock-data.php';

header('Content-Type: text/html; charset=UTF-8');

$userRow = mobileStockGetSessionUser();
$Roll = is_array($userRow) ? (int) ($userRow['Roll'] ?? 0) : 0;
$userBranchId = is_array($userRow) ? (int) ($userRow['BranchId'] ?? 0) : 0;
$branchId = isset($_POST['BranchId']) ? (int) $_POST['BranchId'] : 0;

if ((int) $Roll !== 1 && (int) $Roll !== 7 && $branchId !== $userBranchId) {
    echo '<option value="" selected disabled>Unauthorized</option>';
    exit;
}

if ($branchId < 1) {
    echo '<option value="" selected disabled>Select store first</option>';
    exit;
}

$officers = mobileStockGetDispatchOfficersForBranch($Roll, $userBranchId, $branchId);
if (empty($officers)) {
    echo '<option value="" selected disabled>No dispatch officer for this store</option>';
    exit;
}

echo '<option value="" disabled selected>Select</option>';
foreach ($officers as $officer) {
    $oid = (int) ($officer['id'] ?? 0);
    if ($oid < 1) {
        continue;
    }
    echo '<option value="' . $oid . '">' . htmlspecialchars((string) ($officer['Fname'] ?? ''), ENT_QUOTES, 'UTF-8') . '</option>';
}
