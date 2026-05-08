<?php
include '../config.php';

## Read value
$ProjectId = $_POST['ProjectId'];
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
	$searchQuery = " and (Fname like '%".$searchValue."%' or 
        Phone like '%".$searchValue."%' or 
        Taluka like'%".$searchValue."%' or 
    	Village like'%".$searchValue."%' or 
    	District like'%".$searchValue."%' or 
    	Address like'%".$searchValue."%') ";
}

$sql = "SELECT * FROM tbl_users WHERE Roll=5 AND ProjectId='$ProjectId' AND ProjectType=1";
if($_POST['FieldSurveyDetails']!=''){
    $FieldSurveyDetails = $_POST['FieldSurveyDetails'];
    $sql.=" AND FieldSurveyDetails='$FieldSurveyDetails'";
}
 if($_POST['District']){
            $District = $_POST['District'];
            if($District == 'all'){
                $sql.= " ";
            }
            else{
               $sql.= " AND District='$District'";
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
