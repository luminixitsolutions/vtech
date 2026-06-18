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

$editSellId = (int) ($_POST['sell_id'] ?? 0);
$sellStatusClause = "SellStatus=0";
if ($editSellId > 0) {
    $sellStatusClause = "(SellStatus=0 OR (SellStatus=1 AND SellId='$editSellId'))";
}

   if($Roll==1 || $Roll==7){ 
        $sql = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType='1' AND SerialNo!='' AND $sellStatusClause";
    }
    else if($Roll==27){
        $sql = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType='1' AND StoreInchId='$user_id' AND SerialNo!='' AND $sellStatusClause";
    }
    else{
        $sql = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType='1' AND StoreExeId='$user_id' AND SerialNo!='' AND $sellStatusClause";
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

$cartIds = [];
if (!empty($_SESSION['cart_item']) && is_array($_SESSION['cart_item'])) {
    foreach ($_SESSION['cart_item'] as $cartItem) {
        if (isset($cartItem['id'])) {
            $cartIds[(string) $cartItem['id']] = true;
        }
    }
}

$pdiVerifiedSerials = [];
$dcrVerifiedSerials = [];
$pdiRes = $conn->query("SELECT serialno FROM tbl_pdi_verification_serialno WHERE match_status=1 AND TRIM(COALESCE(serialno,''))!=''");
if ($pdiRes) {
    while ($pdiRow = $pdiRes->fetch_assoc()) {
        $pdiVerifiedSerials[strtoupper(trim((string) $pdiRow['serialno']))] = true;
    }
}
$dcrRes = $conn->query("SELECT serialno FROM tbl_dcr_verification_serialno WHERE match_status=1 AND TRIM(COALESCE(serialno,''))!=''");
if ($dcrRes) {
    while ($dcrRow = $dcrRes->fetch_assoc()) {
        $dcrVerifiedSerials[strtoupper(trim((string) $dcrRow['serialno']))] = true;
    }
}

function serialNoVerificationIcon($verified)
{
    if ($verified) {
        return '<span class="text-success d-inline-block text-center" style="font-size:20px;line-height:1;" title="Verified"><span class="ion ion-md-checkmark"></span></span>';
    }
    return '<span class="text-danger d-inline-block text-center" style="font-size:20px;line-height:1;" title="Not Verified"><span class="ion ion-md-close"></span></span>';
}

while ($row = mysqli_fetch_assoc($empRecords)) {
    $rowId = (string) $row['id'];
    $isChecked = isset($cartIds[$rowId]);
    $checkVal = $isChecked ? 1 : 0;
    $checkedAttr = $isChecked ? ' checked' : '';

            $checkbox = '<label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id'.$row['id'].'" value="'.$checkVal.'" class="custom-control-input is-valid" onclick="featured('.$row['id'].')"'.$checkedAttr.'>
                    <span class="custom-control-label">&nbsp;</span>
                 </label>
                 <input type="hidden" value="'.$checkVal.'" name="CheckId[]" id="CheckId'.$row['id'].'">
                 ';
    $serialKey = strtoupper(trim((string) $row['SerialNo']));
    $data[] = array(
            "id"=>$checkbox,
            "Product"=>$row['ProductName'],
            "SerialNo"=>$row['SerialNo'],
            "PdiVerification"=>serialNoVerificationIcon(isset($pdiVerifiedSerials[$serialKey])),
            "DcrVerification"=>serialNoVerificationIcon(isset($dcrVerifiedSerials[$serialKey])),
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
