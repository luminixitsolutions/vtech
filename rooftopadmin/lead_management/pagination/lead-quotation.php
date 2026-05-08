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
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (tp.InvoiceNo like '%".$searchValue."%' or 
        tp.CustName like '%".$searchValue."%' or
        tp.CellNo like '%".$searchValue."%' or 
        tp.Address like '%".$searchValue."%' or 
    tp.InvoiceDate like '%".$searchValue."%') ";
}
if($Roll==1 || $Roll==7){
$sql = "SELECT tp.* FROM tbl_rooftop_lead_quotation tp WHERE 1";
 		}
else{
    $sql = "SELECT tp.* FROM tbl_rooftop_lead_quotation tp 
                    LEFT JOIN tbl_rooftop_leads tl ON tp.CustId=tl.id WHERE tl.AllocateId='$user_id'"; 
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

    if ($row['Status'] == '1') {
        $Status = "<span style='color:green;'>Approved</span>";
    } else {
        $Status = "<span style='color:red;'>Pending</span>";
    }

    $Action = "<a href='add-lead-quotation.php?id=".$row['id']."'><i class='lnr lnr-pencil mr-2'></i></a>&nbsp;&nbsp;
    	<a onClick='return confirm('Are you sure you want delete this record');' href='lead-quotation.php?id=".$row['id']."&action=delete'><i class='lnr lnr-trash text-danger'></i></a>
        &nbsp;&nbsp;
        <a href='print-lead-quotation.php?id=".$row['id']."' target='_new'><i class='lnr lnr-printer text-danger'></i></a>";
    $data[] = array(
    		"id"=>$i,
            "CustName"=>$row['CustName'],
            "CellNo"=>$row['CellNo'],
            "Address"=>$row['Address'],
            "InvoiceNo"=>$row['InvoiceNo'],
    		"InvoiceDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['InvoiceDate']))),
            "Status"=>$Status,
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
