<?php
session_start();
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/auth.php';
include_once __DIR__ . '/inc-insurance-site.php';

header('Content-Type: application/json; charset=utf-8');

$custId = isset($_POST['CustId']) ? (int) $_POST['CustId'] : (isset($_GET['CustId']) ? (int) $_GET['CustId'] : 0);
if ($custId <= 0) {
    echo json_encode(array('ok' => false, 'message' => 'Invalid customer.'));
    exit;
}

$insurance = insuranceGetLatestCustomerInsurance($custId);
if (empty($insurance)) {
    echo json_encode(array(
        'ok' => true,
        'found' => false,
        'message' => 'No completed or renewed insurance record found for this customer.',
    ));
    exit;
}

echo json_encode(array(
    'ok' => true,
    'found' => true,
    'insurance_no' => $insurance['insurance_no'] ?? '',
    'company_name' => $insurance['company_name'] ?? '',
    'date_of_issue' => $insurance['date_of_issue_display'] ?? '',
    'date_of_expiry' => $insurance['date_of_expiry_display'] ?? '',
    'no_of_years' => $insurance['no_of_years'] ?? '',
    'source_label' => $insurance['source_label'] ?? '',
));
