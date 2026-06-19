<?php
session_start();
include_once 'config.php';
include_once 'inc-insurance-site.php';

header('Content-Type: text/html; charset=utf-8');

if (empty($_SESSION['Admin']['id'])) {
    echo '<option value="all">All Sub Head</option>';
    exit;
}

$projectId = (int) ($_POST['project_id'] ?? $_POST['id'] ?? 0);
$userRow = insuranceSiteLoggedInUserRow();
$subHeadRows = insuranceSiteSubHeadRowsForProject($projectId, $userRow);

echo '<option value="all">All Sub Head</option>';
foreach ($subHeadRows as $subHeadRow) {
    echo '<option value="' . (int) $subHeadRow['id'] . '">' . htmlspecialchars($subHeadRow['Name']) . '</option>';
}
