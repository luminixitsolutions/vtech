<?php
include_once "../../config.php";

## Read DataTables variables
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];

$searchValue = $_POST['search']['value'];

## Base query
$searchQuery = "";
if($searchValue != ''){
   $searchQuery .= " AND (CustName LIKE '%".$searchValue."%' 
                    OR CellNo LIKE '%".$searchValue."%' 
                    OR TicketNo LIKE '%".$searchValue."%')";
}

## Total records
$sel = mysqli_query($conn,"SELECT COUNT(*) as allcount FROM tbl_irrigation_leads");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total with filter
$sel2 = mysqli_query($conn,"SELECT COUNT(*) as allcount FROM tbl_irrigation_leads WHERE 1 ".$searchQuery);
$records2 = mysqli_fetch_assoc($sel2);
$totalRecordwithFilter = $records2['allcount'];

## Fetch records
$empQuery = "SELECT * FROM tbl_irrigation_leads WHERE 1 ".$searchQuery."
             ORDER BY id DESC 
             LIMIT ".$row.",".$rowperpage;

$empRecords = mysqli_query($conn, $empQuery);
$data = array();
$sr = $row+1;

while ($row = mysqli_fetch_assoc($empRecords)) {

$action = "
<a href='add-irrigation-leads.php?id=".$row['id']."'>
  <i class='lnr lnr-pencil mr-2'></i>
</a>
&nbsp;&nbsp;
<a href='view-irrigation-leads.php?id=".$row['id']."&action=delete' 
   onclick=\"return confirm('Are you sure you want to delete this record?');\">
   <i class='lnr lnr-trash text-danger'></i>
</a>";

    	

   $data[] = array(
        "id" => $sr++,
        "TicketNo" => $row['TicketNo'],
        "CustName" => $row['CustName'],
        "CellNo" => $row['CellNo'],
        "Address" => $row['Address'],

        "CurrentIrrigationMethod" => $row['CurrentIrrigationMethod'],
        "InterestedInIrrigation" => $row['InterestedInIrrigation'],
        "CropsGrown" => $row['CropsGrown'],
        "InterestedSoilTesting" => $row['InterestedSoilTesting'],

        "Status" => $row['Status'],
        "CreatedDate" => $row['CreatedDate'],
        "Action" => $action
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
exit;

?>
