<?php
include '../config.php';

## Read value
$StateId = $_POST['StateId'];
$District = $_POST['District'];
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (tu.Fname like '%".$searchValue."%' or 
        tu.Phone like '%".$searchValue."%' or 
        tu.Taluka like'%".$searchValue."%' or 
    	tu.Village like'%".$searchValue."%' or 
    	tu.District like'%".$searchValue."%' or 
    	tu.Address like'%".$searchValue."%') ";
}

$sql = "SELECT tu.* FROM tbl_lead_quotation ts 
        LEFT JOIN tbl_users tu ON ts.CustId=tu.id 
        WHERE ts.OppConverted=1 AND tu.Roll=5 AND tu.StateId='$StateId' AND tu.ProjectType=2
        ";

 if($_POST['District']){
            $District = $_POST['District'];
            if($District == 'all'){
                $sql.= " ";
            }
            else{
               $sql.= " AND tu.District='$District'";
            }
        }
## Total number of records without filtering
$totalRecords = getRow($sql);

## Total number of records with filtering
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {

    if ($row['ProjectType'] == '1') {
        $ProjectType = "Pump Projects";
    } else {
        $ProjectType = "Rooftop Projects";
    }

    $data[] = array(
    		"ProjectType"=>$ProjectType,
    		"BeneficiaryId"=>$row['BeneficiaryId'],
    		"Fname"=>$row['Fname'] . " " . $row['Lname'],
    		"Phone"=>$row['Phone'],
    		"Taluka"=>$row['Taluka'],
    		"Village"=>$row['Village'],
    		"District"=>$row['District'],
    		"Address"=>$row['Address'],
    		"CreatedDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate'])))
    	);
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
