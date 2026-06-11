<?php
include '../config.php';
include '../inc-contractor-abstract-queries.php';

$ProjectId = isset($_POST['ProjectId']) ? (int) $_POST['ProjectId'] : 0;
$ProjectSubHeadId = isset($_POST['subheadid']) ? (int) $_POST['subheadid'] : 0;
$roll = isset($_POST['roll']) ? trim((string) $_POST['roll']) : '';
$dist = isset($_POST['dist']) ? trim((string) $_POST['dist']) : '';
$contractorId = isset($_POST['contractor_id']) ? (int) $_POST['contractor_id'] : 0;
$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? (int) $_POST['order'][0]['column'] : 0;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'Fname';
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn, $_POST['search']['value']) : '';

$allowedSortColumns = array(
    'ProjectType', 'BeneficiaryId', 'Fname', 'Phone', 'Taluka', 'Village', 'District', 'Address', 'Lattitude', 'Longitude', 'ContractorName'
);
if (!in_array($columnName, $allowedSortColumns, true)) {
    $columnName = 'Fname';
}
$columnSortOrder = strtolower($columnSortOrder) === 'desc' ? 'DESC' : 'ASC';

$baseSql = contractorAbstractListSql($conn, $roll, $ProjectId, $ProjectSubHeadId, $dist, $contractorId);
if ($baseSql === '') {
    echo json_encode(array(
        'draw' => $draw,
        'iTotalRecords' => 0,
        'iTotalDisplayRecords' => 0,
        'aaData' => array(),
    ));
    exit;
}

$listSql = "SELECT abstract_scope.*,
    TRIM(CONCAT(IFNULL(tc.Fname,''), ' ', IFNULL(tc.Lname,''))) AS ContractorName
    FROM ($baseSql) abstract_scope
    LEFT JOIN tbl_users tc ON tc.id=abstract_scope.ContractorInstallerId";

$searchQuery = '';
if ($searchValue !== '') {
    $searchQuery = " AND (abstract_scope.Fname LIKE '%" . $searchValue . "%' OR
        abstract_scope.Lname LIKE '%" . $searchValue . "%' OR
        abstract_scope.Phone LIKE '%" . $searchValue . "%' OR
        abstract_scope.Taluka LIKE '%" . $searchValue . "%' OR
        abstract_scope.Village LIKE '%" . $searchValue . "%' OR
        abstract_scope.District LIKE '%" . $searchValue . "%' OR
        abstract_scope.BeneficiaryId LIKE '%" . $searchValue . "%' OR
        abstract_scope.Address LIKE '%" . $searchValue . "%' OR
        tc.Fname LIKE '%" . $searchValue . "%' OR
        tc.Lname LIKE '%" . $searchValue . "%') ";
}

$totalRecords = contractorAbstractCount($conn, $roll, $ProjectId, $ProjectSubHeadId, $dist, $contractorId);
$totalRecordwithFilter = (int) getRecord("SELECT COUNT(*) AS cnt FROM ($listSql) filtered_scope WHERE 1=1 $searchQuery")['cnt'];

$orderColumn = $columnName;
if ($columnName !== 'ContractorName') {
    $orderColumn = 'abstract_scope.' . $columnName;
}

$empQuery = $listSql . ' WHERE 1=1 ' . $searchQuery . ' ORDER BY ' . $orderColumn . ' ' . $columnSortOrder . ' LIMIT ' . $row . ',' . $rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();

while ($rowData = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        'ProjectType' => 'Rooftop Projects',
        'BeneficiaryId' => $rowData['BeneficiaryId'] ?? '',
        'Fname' => trim((string) ($rowData['Fname'] ?? '') . ' ' . (string) ($rowData['Lname'] ?? '')),
        'Phone' => $rowData['Phone'] ?? '',
        'Taluka' => $rowData['Taluka'] ?? '',
        'Village' => $rowData['Village'] ?? '',
        'District' => $rowData['District'] ?? '',
        'Address' => $rowData['Address'] ?? '',
        'Lattitude' => $rowData['Lattitude'] ?? '',
        'Longitude' => $rowData['Longitude'] ?? '',
        'ContractorName' => trim((string) ($rowData['ContractorName'] ?? '')),
    );
}

echo json_encode(array(
    'draw' => $draw,
    'iTotalRecords' => $totalRecords,
    'iTotalDisplayRecords' => $totalRecordwithFilter,
    'aaData' => $data,
));
