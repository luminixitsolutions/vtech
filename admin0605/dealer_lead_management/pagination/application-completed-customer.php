<?php
session_start();
include '../../config.php';
include 'incuserdetails.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$Roll = $_POST['Roll'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$columnSortOrder = "desc";
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (
        tp.applicant_name like '%".$searchValue."%' or
        tp.mobile like '%".$searchValue."%' or 
        tp.district like '%".$searchValue."%' or 
    tp.village like '%".$searchValue."%') ";
}

    if($Roll == 1 || $Roll == 7){
            $sql = "SELECT ts.* FROM tbl_mp_pump_applications ts 
            WHERE ts.ClainStatus='Completed'";
        }
        else{
            $sql = "SELECT ts.* FROM tbl_mp_pump_applications ts 
            WHERE ts.CoordinatorId='$user_id' AND ts.ClainStatus='Completed'";
        }

        
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.created_at>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.created_at<='$ToDate'";
            }

## Total number of records without filtering
$totalRecords = getRow($sql);

## Total number of records with filtering
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();
$i=1;
while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
    		"id"=>$i,
            "CustName"=>$row['applicant_name'],
            "CellNo"=>$row['mobile'],
            "District"=>$row['district'],
            "Village"=>$row['village'],
            "ClainStatus"=>$row['ClainStatus']
    	);
$i++;}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
