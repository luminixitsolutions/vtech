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
	$searchQuery = " and (tp.TicketNo like '%".$searchValue."%' or 
        tp.CustName like '%".$searchValue."%' or
        tp.CellNo like '%".$searchValue."%' or 
        tp.Address like '%".$searchValue."%' or 
    tp.ClainStatus like '%".$searchValue."%') ";
}
if($Roll==1 || $Roll==7){
$sql = "SELECT tp.*,tb.Name As BranchName,tu.Fname FROM tbl_leads tp 
                    LEFT JOIN tbl_branch tb ON tp.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tp.CustId=tu.id WHERE 1";
 		}
else{
    $sql = "SELECT tp.*,tb.Name As BranchName,tu.Fname FROM tbl_leads tp 
                    LEFT JOIN tbl_branch tb ON tp.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON tp.CustId=tu.id WHERE tp.CreatedBy='$user_id'"; 
}

if($_POST['ClaimReason']!=''){
    $ClaimReason = $_POST['ClaimReason'];
    $sql.= " AND tp.ClainReason='$ClaimReason'";
}

if($_POST['ClaimStatus']!=''){
    $ClaimStatus = $_POST['ClaimStatus'];
    $sql.= " AND tp.ClainStatus='$ClaimStatus'";
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

    $Action = "<a href='add-lead.php?id=".$row['id']."'><i class='lnr lnr-pencil mr-2'></i></a>&nbsp;&nbsp;
    	<a onClick='return confirm(Are you sure you want delete this record);' href='view-leads.php?id=".$row['id']."&action=delete'><i class='lnr lnr-trash text-danger'></i></a>";
    $data[] = array(
    		"id"=>$i,
    		"TicketNo"=>'<a href="view-lead-action.php?id='.$row['id'].'" target="_new">'.$row['TicketNo'].'</a>',
            "ClainReason"=>$row['ClainReason']." ".$row['Fname'],
            "CustName"=>$row['CustName'],
            "CellNo"=>$row['CellNo'],
            "Address"=>$row['Address'],
            "ClainStatus"=>$row['ClainStatus'],
    		"CreatedDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))),
    		"Action"=>$Action
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
