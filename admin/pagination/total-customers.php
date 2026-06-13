<?php
session_start();
include '../config.php';
require_once __DIR__ . '/../inc-project-abstract-queries.php';
require_once __DIR__ . '/../inc-work-order-customer.php';
header('Content-Type: application/json; charset=utf-8');

workOrderCustomerEnsureSchema($conn);

function totalCustomerOrderColumn($conn, $columnName)
{
    $allowed = array('ProjectType', 'BeneficiaryId', 'Fname', 'Phone', 'Taluka', 'Village', 'District', 'Address', 'CreatedDate', 'id');
    if ($columnName === 'WorkOrderAction' || $columnName === 'WorkOrderDone') {
        if (projectAbstractHasColumn($conn, 'tbl_installations', 'WorkOrderDone')) {
            return 'id';
        }
        if (projectAbstractHasColumn($conn, 'tbl_users', 'WorkOrderDone')) {
            return 'WorkOrderDone';
        }
        if (projectAbstractHasColumn($conn, 'tbl_users', 'WoNo')) {
            return 'WoNo';
        }
        return 'id';
    }
    if (in_array($columnName, $allowed, true)) {
        return $columnName;
    }
    return 'id';
}

$ProjectId = isset($_POST['ProjectId']) ? (int) $_POST['ProjectId'] : 0;
$ProjectSubHeadId = isset($_POST['SubHeadId']) ? (int) $_POST['SubHeadId'] : 0;
$District = isset($_POST['District']) ? $_POST['District'] : 'all';
$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 1;
$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? (int) $_POST['order'][0]['column'] : 0;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'id';
$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn, $_POST['search']['value']) : '';
$orderBy = totalCustomerOrderColumn($conn, $columnName);

$searchQuery = '';
if ($searchValue !== '') {
    $searchQuery = " AND (Fname LIKE '%" . $searchValue . "%' OR 
        Phone LIKE '%" . $searchValue . "%' OR 
        Taluka LIKE '%" . $searchValue . "%' OR 
        Village LIKE '%" . $searchValue . "%' OR 
        District LIKE '%" . $searchValue . "%' OR 
        Address LIKE '%" . $searchValue . "%') ";
}

$sql = "SELECT * FROM tbl_users WHERE Roll=5 AND ProjectId='$ProjectId' AND ProjectSubHeadId='$ProjectSubHeadId' AND ProjectType=1";
if (isset($_POST['FieldSurveyDetails']) && $_POST['FieldSurveyDetails'] !== '') {
    $FieldSurveyDetails = mysqli_real_escape_string($conn, $_POST['FieldSurveyDetails']);
    $sql .= " AND FieldSurveyDetails='$FieldSurveyDetails'";
}
if ($District !== '' && $District !== 'all') {
    $DistrictEsc = mysqli_real_escape_string($conn, $District);
    $sql .= " AND District='$DistrictEsc'";
}

$totalRecords = getRow($sql);
$totalRecordwithFilter = getRow($sql . $searchQuery);

$empQuery = $sql . $searchQuery . " ORDER BY " . $orderBy . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();

if ($empRecords) {
    while ($rowData = mysqli_fetch_assoc($empRecords)) {
        $ProjectType = ((string) ($rowData['ProjectType'] ?? '') === '1') ? 'Pump Projects' : 'Rooftop Projects';
        $createdDate = '';
        if (!empty($rowData['CreatedDate']) && $rowData['CreatedDate'] !== '0000-00-00') {
            $ts = strtotime(str_replace('-', '/', $rowData['CreatedDate']));
            if ($ts) {
                $createdDate = date('d/m/Y', $ts);
            }
        }

        $custId = (int) ($rowData['id'] ?? 0);
        $workDone = workOrderCustomerDisplayLabel($conn, $rowData);
        $workOrderAction = '<div class="text-nowrap"><strong>' . htmlspecialchars($workDone) . '</strong>';
        if ($custId > 0 && workOrderCustomerIsSupported($conn)) {
            $workOrderAction .= ' <button type="button" class="btn btn-sm btn-primary btn-update-work-order" data-cust-id="' . $custId . '">Update</button>';
        }
        $workOrderAction .= '</div>';

        $data[] = array(
            'WorkOrderAction' => $workOrderAction,
            'ProjectType' => $ProjectType,
            'BeneficiaryId' => $rowData['BeneficiaryId'] ?? '',
            'Fname' => trim(($rowData['Fname'] ?? '') . ' ' . ($rowData['Lname'] ?? '')),
            'Phone' => $rowData['Phone'] ?? '',
            'Taluka' => $rowData['Taluka'] ?? '',
            'Village' => $rowData['Village'] ?? '',
            'District' => $rowData['District'] ?? '',
            'Address' => $rowData['Address'] ?? '',
            'CreatedDate' => $createdDate,
        );
    }
}

echo json_encode(array(
    'draw' => $draw,
    'iTotalRecords' => (int) $totalRecords,
    'iTotalDisplayRecords' => (int) $totalRecordwithFilter,
    'aaData' => $data,
));
