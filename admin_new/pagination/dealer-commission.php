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
	$searchQuery = " and (Fname like '%".$searchValue."%' or 
        Phone like '%".$searchValue."%' or 
        Taluka like'%".$searchValue."%' or 
    	Village like'%".$searchValue."%' or 
    	District like'%".$searchValue."%' or 
    	Address like'%".$searchValue."%') ";
}

$sql = "SELECT tu.*,tu2.CustomerId As Dealer_Code,tu2.Fname As DealerName,tu2.id As DealerId FROM tbl_users tu INNER JOIN tbl_users tu2 ON tu.DealerCode=tu2.CustomerId WHERE tu.Roll=5";
 		
## Total number of records without filtering
//$sel = mysqli_query($conn,$sql);
//$records = mysqli_fetch_assoc($sel);
$totalRecords = getRow($sql);

## Total number of records with filtering
//$sel = mysqli_query($conn,$sql." ".$searchQuery);
//$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();
$i=1;
while ($row = mysqli_fetch_assoc($empRecords)) {
$sql2 = "SELECT * FROM tbl_delivered_invoice WHERE CustId='".$row['id']."'";
                $rncnt2 = getRow($sql2);

                $sql3 = "SELECT * FROM wallet WHERE CustId='".$row['id']."' AND UserId='".$row['DealerId']."' AND Commission=1";
                $rncnt3 = getRow($sql3);
                $row3 = getRecord($sql3);

	
if($rncnt2 > 0){
        $Status = "<span style='color:green;'>Completed</span>";
    } else {
        $Status = "<span style='color:red;'>In Progress</span>";
    }

if($rncnt3 > 0){
    $pay = '<button type="button" class="btn btn-secondary btn-round" disabled>Paid</button>';
}
else if($rncnt2 > 0){
    $pay = '<a href="pay-commission-amount.php?cid='.$row['id'].'" class="btn btn-secondary btn-round">Pay Amt</a>';
}
    $Action = "<a href='edit-commission-amount.php?id=".$row['id']."'><i class='lnr lnr-pencil mr-2'></i></a>&nbsp;&nbsp;
    	<a onClick='return confirm('Are you sure you want delete this customer account?\nNote : Delete all record related this customer (Y/N)');' href='".$_SERVER['PHP_SELF']."?id=".$row['id']."&action=delete'><i class='lnr lnr-trash text-danger'></i></a>";
    $data[] = array(
    		"id"=>$i,
    		"CustName"=>$row['Fname'],
            "Phone"=>$row['Phone'],
    		"DealerName"=>$row['DealerName'],
    		"DealerCode"=>$row['Dealer_Code'],
    		"CreatedDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))),
    		"Status"=>$Status,
    		"Pay"=>$pay,
    		"PaidAmt"=>$row3['Amount'],
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
