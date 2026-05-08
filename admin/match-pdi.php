<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];

$pdi_id = $_GET['id'];
$sql = "SELECT serialno,id FROM tbl_pdi_verification_serialno WHERE pdi_id='$pdi_id' AND match_status=0";
$row = getList($sql);
foreach($row as $result){
    $serialno = $result['serialno'];
    $id = $result['id'];
    $sql2 = "SELECT SerialNo FROM `tbl_stocks` WHERE ProdType='1' 
    AND SerialNo!='' AND SellType='Purchase' AND SerialNo='$serialno'";
    $rncnt2 = getRow($sql2);
    if($rncnt2 > 0){
        $sql = "UPDATE tbl_pdi_verification_serialno SET match_status=1 WHERE id='$id'";
        $conn->query($sql);
    }
}
echo "<script>alert('PDI Matched Successfully');window.location.href='match-pdi-verification.php';</script>";
?>