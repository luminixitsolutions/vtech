<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
chdir(dirname(__DIR__) . '/ajax_files');
$_SERVER['SCRIPT_FILENAME'] = getcwd() . '/ajax_employee.php';
$_SERVER['SCRIPT_NAME'] = '/vtech/admin/ajax_files/ajax_employee.php';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$_SERVER['REQUEST_METHOD'] = 'POST';

session_start();
$_SESSION['Admin'] = ['id' => 1];

$row = null;
require dirname(__DIR__) . '/db-local.php';
$c = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$r = $c->query("SELECT * FROM tbl_users WHERE id=14 LIMIT 1");
if ($r && $r->num_rows) {
    $row = $r->fetch_assoc();
}

$_POST = [
    'action' => 'Save',
    'id' => '14',
    'Fname' => $row['Fname'] ?? 'Test',
    'Mname' => $row['Mname'] ?? '',
    'Lname' => $row['Lname'] ?? 'User',
    'Phone' => $row['Phone'] ?? '9999999999',
    'EmailId' => $row['EmailId'] ?? '',
    'Password' => $row['Password'] ?? 'x',
    'Roll' => $row['Roll'] ?? '5',
    'Status' => $row['Status'] ?? '1',
    'CompId' => $row['CompId'] ?? '1',
    'Options' => ['21', '14'],
];

echo "including ajax...\n";
include 'ajax_employee.php';
echo "done\n";
