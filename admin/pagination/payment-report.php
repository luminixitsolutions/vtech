<?php

session_start();

include '../config.php';

require_once __DIR__ . '/../report_management/inc-payment-report.php';

header('Content-Type: application/json; charset=utf-8');



$filters = array(

    'ProjectId' => isset($_POST['ProjectId']) ? $_POST['ProjectId'] : 'all',

    'ProjectSubHeadId' => isset($_POST['ProjectSubHeadId']) ? $_POST['ProjectSubHeadId'] : 'all',

    'District' => isset($_POST['District']) ? $_POST['District'] : 'all',

    'Taluka' => isset($_POST['Taluka']) ? $_POST['Taluka'] : 'all',

    'Village' => isset($_POST['Village']) ? $_POST['Village'] : 'all',

);



$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 1;

$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;

$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 50;

$columnIndex = isset($_POST['order'][0]['column']) ? (int) $_POST['order'][0]['column'] : 1;

$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'BeneficiaryId';

$columnSortOrder = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';

$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';

$orderBy = paymentReportOrderColumn($columnName);



$baseSql = paymentReportBaseSql($conn, $filters);

$searchSql = paymentReportSearchSql($conn, $searchValue);



$countSql = "SELECT COUNT(*) AS cnt FROM tbl_users tu

    " . paymentReportLatestInstallJoinSql() . "

    " . paymentReportLookupJoinsSql() . "

    WHERE tu.Roll = '5' AND tu.ProjectType = '1'

    " . paymentReportWhereSql($conn, $filters);



$countFilterSql = $countSql . $searchSql;



$totalRecords = 0;

$totalRecordwithFilter = 0;

$countRes = $conn->query($countSql);

if ($countRes && ($countRow = $countRes->fetch_assoc())) {

    $totalRecords = (int) $countRow['cnt'];

}

$countFilterRes = $conn->query($countFilterSql);

if ($countFilterRes && ($countFilterRow = $countFilterRes->fetch_assoc())) {

    $totalRecordwithFilter = (int) $countFilterRow['cnt'];

}



$data = array();

$empQuery = $baseSql . $searchSql . " ORDER BY $orderBy $columnSortOrder, tu.id ASC LIMIT $row, $rowperpage";

$empRecords = $conn->query($empQuery);

$serial = $row + 1;



if ($empRecords) {

    while ($rowData = $empRecords->fetch_assoc()) {

        $data[] = paymentReportFormatRow($rowData, $serial);

        $serial++;

    }

}



echo json_encode(array(
    'draw' => $draw,
    'iTotalRecords' => $totalRecords,
    'iTotalDisplayRecords' => $totalRecordwithFilter,
    'aaData' => $data,
));


