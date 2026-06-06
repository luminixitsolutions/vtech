<?php
session_start();
include_once '../config.php';

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(['ok' => false, 'message' => 'Session expired. Please login again.']);
    exit;
}

$user_id = (int) $_SESSION['Admin']['id'];
$old = (string) ($_POST['OldPassword'] ?? '');
$new = (string) ($_POST['NewPassword'] ?? '');
$confirm = (string) ($_POST['ConfirmPassword'] ?? '');

if ($old === '' || $new === '' || $confirm === '') {
    echo json_encode(['ok' => false, 'message' => 'All fields are required.']);
    exit;
}
if ($new !== $confirm) {
    echo json_encode(['ok' => false, 'message' => 'New password and confirm password do not match.']);
    exit;
}
if (strlen($new) < 4) {
    echo json_encode(['ok' => false, 'message' => 'New password must be at least 4 characters.']);
    exit;
}

$row = getRecord("SELECT Password FROM tbl_users WHERE id='$user_id' LIMIT 1");
if (empty($row)) {
    echo json_encode(['ok' => false, 'message' => 'User not found.']);
    exit;
}

if ((string) $row['Password'] !== $old) {
    echo json_encode(['ok' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

$newEsc = $conn->real_escape_string($new);
if (!$conn->query("UPDATE tbl_users SET Password='$newEsc' WHERE id='$user_id'")) {
    echo json_encode(['ok' => false, 'message' => 'Could not update password.']);
    exit;
}

$_SESSION['Admin']['Password'] = $new;
echo json_encode(['ok' => true, 'message' => 'Password updated successfully.']);
