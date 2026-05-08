<?php
session_start();
include '../../config.php';
include 'incuserdetails.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$columnSortOrder = "desc";
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (tp.TicketNo like '%".$searchValue."%' or 
        tp.CustName like '%".$searchValue."%' or
        tp.CellNo like '%".$searchValue."%' or 
        tp.Address like '%".$searchValue."%' or 
    tp.ClainStatus like '%".$searchValue."%') ";
}

    $sql = "SELECT tp.*,tb.Name As BranchName,tu.Fname FROM tbl_rooftop_leads tp 
                    LEFT JOIN tbl_branch tb ON tp.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tp.CustId=tu.id WHERE tp.AllocateId=0"; 
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
       
            $checkbox = '<label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id'.$row['id'].'" value="0" class="custom-control-input is-valid" onclick="featured('.$row['id'].')">
                    <span class="custom-control-label">&nbsp;</span>
                 </label>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId'.$row['id'].'">
                 ';
    $data[] = array(
            "id"=>$checkbox,
            "TicketNo"=>$row['TicketNo'],
            "ClainReason"=>$row['ClainReason']." ".$row['Fname'],
            "CustName"=>$row['CustName'],
            "CellNo"=>$row['CellNo'],
            "Address"=>$row['Address'],
            "ClainStatus"=>$row['ClainStatus'],
            "CreatedDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))),
        
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
