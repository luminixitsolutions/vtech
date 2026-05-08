<?php
session_start();
include '../config.php';
include 'incuserdetails.php';
## Read value
$Roll = $_POST['Roll'];
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
	$searchQuery = " and (SerialNo like '%".$searchValue."%' or 
        ProductName like '%".$searchValue."%') ";
}

   if($Roll==1 || $Roll==7){ 
        $sql = "SELECT * FROM tbl_rooftop_distibute_item_details2 WHERE ProdType='1' AND SerialNo!='' AND SellStatus=0";
    }
    else if($Roll==27){
        $sql = "SELECT * FROM tbl_rooftop_distibute_item_details2 WHERE ProdType='1' AND StoreInchId='$user_id' AND SerialNo!='' AND SellStatus=0";
    }
    else{
        $sql = "SELECT * FROM tbl_rooftop_distibute_item_details2 WHERE ProdType='1' AND StoreExeId='$user_id' AND SerialNo!='' AND SellStatus=0";
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
       
            $checkbox = '<label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id'.$row['id'].'" value="0" class="custom-control-input is-valid" onclick="featured('.$row['id'].')">
                    <span class="custom-control-label">&nbsp;</span>
                 </label>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId'.$row['id'].'">
                 ';
    $data[] = array(
            "id"=>$checkbox,
            "Product"=>$row['ProductName'],
            "SerialNo"=>$row['SerialNo'],
        
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
