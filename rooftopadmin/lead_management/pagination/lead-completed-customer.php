<?php
session_start();
include '../../config.php';
include 'incuserdetails.php';

header('Content-Type: application/json');

## Read DataTables parameters
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$rowperpage = isset($_POST['length']) ? intval($_POST['length']) : 10;

$Roll = isset($_POST['Roll']) ? $_POST['Roll'] : '';
$ClainReason = isset($_POST['ClainReason']) ? $_POST['ClainReason'] : '';
$FromDate = isset($_POST['FromDate']) ? $_POST['FromDate'] : '';
$ToDate = isset($_POST['ToDate']) ? $_POST['ToDate'] : '';

## Column Mapping (VERY IMPORTANT)
$columnArr = array(
    0 => 'ts.id',
    1 => 'ts.CustName',
    2 => 'ts.CellNo',
    3 => 'ts.ClainReason',
    4 => 'ts.ClainStatus'
);

$columnIndex = $_POST['order'][0]['column'];
$columnName = $columnArr[$columnIndex];
$columnSortOrder = $_POST['order'][0]['dir'];

$searchValue = mysqli_real_escape_string($conn, $_POST['search']['value']);

## Base Query
if ($Roll == 1 || $Roll == 7) {
    $baseQuery = " FROM tbl_rooftop_leads ts
                   LEFT JOIN tbl_users tu ON ts.CustId = tu.id
                   WHERE ts.Status = 1
                   AND ts.ClainStatus = 'Completed'";
} else {
    $baseQuery = " FROM tbl_rooftop_leads ts
                   LEFT JOIN tbl_users tu ON ts.CustId = tu.id
                   WHERE ts.AllocateId = '$user_id'
                   AND ts.ClainStatus = 'Completed'";
}

## Filters
if ($ClainReason && $ClainReason != 'all') {
    $baseQuery .= " AND ts.ClainReason = '$ClainReason'";
}

if ($FromDate) {
    $baseQuery .= " AND ts.CreatedDate >= '$FromDate'";
}

if ($ToDate) {
    $baseQuery .= " AND ts.CreatedDate <= '$ToDate'";
}

## Search
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (
        ts.CustName LIKE '%$searchValue%' OR
        ts.CellNo LIKE '%$searchValue%' OR
        ts.ClainReason LIKE '%$searchValue%' OR
        ts.ClainStatus LIKE '%$searchValue%'
    ) ";
}

## Total Records (without search)
$totalQuery = "SELECT COUNT(*) as total " . $baseQuery;
$totalResult = mysqli_query($conn, $totalQuery);
$totalRecords = mysqli_fetch_assoc($totalResult)['total'];

## Total Records (with search)
$totalFilterQuery = "SELECT COUNT(*) as total " . $baseQuery . $searchQuery;
$totalFilterResult = mysqli_query($conn, $totalFilterQuery);
$totalRecordwithFilter = mysqli_fetch_assoc($totalFilterResult)['total'];

## Fetch Records
$dataQuery = "SELECT ts.*, tu.Fname 
              " . $baseQuery . 
              $searchQuery . 
              " ORDER BY $columnName $columnSortOrder 
                LIMIT $start, $rowperpage";

$dataResult = mysqli_query($conn, $dataQuery);

$data = array();
$sr = $start + 1;

while ($row = mysqli_fetch_assoc($dataResult)) {
    $data[] = array(
        "id" => $sr,
        "CustName" => $row['CustName'],
        "CellNo" => $row['CellNo'],
        "ClainReason" => $row['ClainReason'] . " " . $row['Fname'],
        "ClainStatus" => $row['ClainStatus']
    );
    $sr++;
}

## Response
$response = array(
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecordwithFilter,
    "data" => $data
);

echo json_encode($response);
exit;
?>