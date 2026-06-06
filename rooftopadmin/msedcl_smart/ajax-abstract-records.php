<?php
ob_start();
error_reporting(0);
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['Admin']['id'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Session expired.']);
    exit;
}

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

msedclSmartInitUserAccess();
if (!msedclSmartCanAccessOption(MSEDCL_SMART_OPT_ABSTRACT)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

$metric = isset($_REQUEST['metric']) ? trim((string) $_REQUEST['metric']) : '';
$rowDistrict = isset($_REQUEST['RowDistrict']) ? trim((string) $_REQUEST['RowDistrict']) : '';

$meta = msedclSmartAbstractFiltersFromRequest($_REQUEST);
$records = msedclSmartAbstractRecords($metric, $rowDistrict, $meta['filters']);

$titleParts = [msedclSmartAbstractMetricLabel($metric)];
if ($rowDistrict !== '') {
    $titleParts[] = $rowDistrict;
} elseif ($meta['District'] !== '') {
    $titleParts[] = $meta['District'];
} else {
    $titleParts[] = 'All Districts';
}
if ($meta['Taluka'] !== '') {
    $titleParts[] = 'Taluka: ' . $meta['Taluka'];
}

$rowsOut = [];
foreach ($records as $row) {
    $capName = msedclSmartRooftopCapacityMasterName($row['PumpCapacity'] ?? '');
    $capDisp = $capName !== '' ? $capName : (trim((string) ($row['PumpCapacity'] ?? '')) !== '' ? (string) $row['PumpCapacity'] : '—');
    $rowsOut[] = [
        'beneficiary_id' => (string) ($row['BeneficiaryId'] ?? ''),
        'cust_name' => (string) ($row['CustName'] ?? ''),
        'cell_no' => (string) ($row['CellNo'] ?? ''),
        'district' => (string) ($row['District'] ?? ''),
        'taluka' => (string) ($row['Taluka'] ?? ''),
        'village' => (string) ($row['Village'] ?? ''),
        'capacity' => $capDisp,
        'stage' => msedclSmartStageLabel($row['CurrentStage'] ?? ''),
        'pmsgy_date' => !empty($row['PmsgyAppliedDate']) && $row['PmsgyAppliedDate'] !== '0000-00-00' ? date('d/m/Y', strtotime($row['PmsgyAppliedDate'])) : '',
        'mahadiscom_date' => !empty($row['MahadiscomAppliedDate']) && $row['MahadiscomAppliedDate'] !== '0000-00-00' ? date('d/m/Y', strtotime($row['MahadiscomAppliedDate'])) : '',
        'payment_date' => !empty($row['PaymentDoneDate']) && $row['PaymentDoneDate'] !== '0000-00-00' ? date('d/m/Y', strtotime($row['PaymentDoneDate'])) : '',
        'survey_date' => !empty($row['SurveyDoneDate']) && $row['SurveyDoneDate'] !== '0000-00-00' ? date('d/m/Y', strtotime($row['SurveyDoneDate'])) : '',
    ];
}

ob_end_clean();
echo json_encode([
    'success' => true,
    'title' => implode(' — ', $titleParts),
    'count' => count($rowsOut),
    'metric' => $metric,
    'rows' => $rowsOut,
]);
