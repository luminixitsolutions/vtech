<?php
include '../config.php';
include '../inc-project-abstract-queries.php';

$ProjectId = isset($_POST['ProjectId']) ? (int) $_POST['ProjectId'] : 0;
$ProjectSubHeadId = isset($_POST['subheadid']) ? (int) $_POST['subheadid'] : 0;
$roll = isset($_POST['roll']) ? trim((string) $_POST['roll']) : '';
$dist = isset($_POST['dist']) ? trim((string) $_POST['dist']) : '';
$val2 = isset($_POST['val']) ? trim((string) $_POST['val']) : '';
$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;
$row = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? (int) $_POST['length'] : 10;
$columnIndex = isset($_POST['order'][0]['column']) ? (int) $_POST['order'][0]['column'] : 0;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'Fname';
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn, $_POST['search']['value']) : '';

$allowedSortColumns = array(
    'ProjectType', 'BeneficiaryId', 'Fname', 'Phone', 'Taluka', 'Village', 'District', 'Address', 'Lattitude', 'Longitude'
);
if (!in_array($columnName, $allowedSortColumns, true)) {
    $columnName = 'Fname';
}
$columnSortOrder = strtolower($columnSortOrder) === 'desc' ? 'DESC' : 'ASC';

$searchQuery = '';
if ($searchValue !== '') {
    $searchQuery = " AND (tu.Fname LIKE '%" . $searchValue . "%' OR
        tu.Phone LIKE '%" . $searchValue . "%' OR
        tu.Taluka LIKE '%" . $searchValue . "%' OR
        tu.Village LIKE '%" . $searchValue . "%' OR
        tu.District LIKE '%" . $searchValue . "%' OR
        tu.BeneficiaryId LIKE '%" . $searchValue . "%' OR
        tu.Address LIKE '%" . $searchValue . "%') ";
}

$sql = projectAbstractListSql($conn, $roll, $ProjectId, $ProjectSubHeadId, $dist, $val2);
if ($sql === '') {
    echo json_encode(array(
        'draw' => $draw,
        'iTotalRecords' => 0,
        'iTotalDisplayRecords' => 0,
        'aaData' => array(),
    ));
    exit;
}

$totalRecords = projectAbstractCount($conn, $roll, $ProjectId, $ProjectSubHeadId, $dist, $val2);
$totalRecordwithFilter = (int) getRecord("SELECT COUNT(*) AS cnt FROM ($sql $searchQuery) abstract_filtered")['cnt'];

$empQuery = $sql . $searchQuery . ' ORDER BY tu.' . $columnName . ' ' . $columnSortOrder . ' LIMIT ' . $row . ',' . $rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();

while ($rowData = mysqli_fetch_assoc($empRecords)) {
    if ((int) ($rowData['ProjectType'] ?? 0) === 1) {
        $ProjectType = 'Pump Projects';
    } else {
        $ProjectType = 'Rooftop Projects';
    }

    $data[] = array(
        'ProjectType' => $ProjectType,
        'BeneficiaryId' => $rowData['BeneficiaryId'] ?? '',
        'Fname' => trim((string) ($rowData['Fname'] ?? '') . ' ' . (string) ($rowData['Lname'] ?? '')),
        'Phone' => $rowData['Phone'] ?? '',
        'Taluka' => $rowData['Taluka'] ?? '',
        'Village' => $rowData['Village'] ?? '',
        'District' => $rowData['District'] ?? '',
        'Address' => $rowData['Address'] ?? '',
        'Lattitude' => $rowData['Lattitude'] ?? '',
        'Longitude' => $rowData['Longitude'] ?? '',
    );
}

$response = array(
    'draw' => $draw,
    'iTotalRecords' => $totalRecords,
    'iTotalDisplayRecords' => $totalRecordwithFilter,
    'aaData' => $data,
);

echo json_encode($response);
