<?php
include '../config.php';

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
	$searchQuery = " and (ProductName like '%".$searchValue."%') ";
}

$sql = "SELECT * FROM tbl_products WHERE 1 ";
 		
## Total number of records without filtering
$totalRecords = getRow($sql);

## Total number of records with filtering
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$i=1;
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();
while ($row = mysqli_fetch_assoc($empRecords)) {


	if ($row['ProdSpec'] == 1) {
	$ProdSpec = "<span style='color:green;'>BOS</span>";
	}
    else if ($row['ProdSpec'] == 2) {
    $ProdSpec = "<span style='color:orange;'>Structure</span>";
    }
    else{
        $ProdSpec = "<span style='color:red;'>Not Specified</span>";
    }
    

    if ($row['Status'] == '1') {
        $Status = "<span style='color:green;'>Approved</span>";
    } else {
        $Status = "<span style='color:red;'>Pending</span>";
    }

    $Action = "<a href='add-product.php?id=".$row['id']."'><i class='lnr lnr-pencil mr-2'></i></a>&nbsp;&nbsp;
    	<a onClick='return confirm('Are you sure you want delete this product?\nNote : Delete all record related this product (Y/N)');' href='".$_SERVER['PHP_SELF']."?id=".$row['id']."&action=delete'><i class='lnr lnr-trash text-danger'></i></a>";
    $data[] = array(
            "id"=>$i,
    		"ProductName"=>$row['ProductName'],
    		"ModelNo"=>$row['ModelNo'],
    		"Price"=>$row['Price'],
    		"ProdSpec"=>$ProdSpec,
    		"Status"=>$Status,
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
