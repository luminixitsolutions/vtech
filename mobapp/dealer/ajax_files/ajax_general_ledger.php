<?php
session_start();
include_once '../../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'SaveVed'){
    $id = $_POST['id'];
    $UserId = $_POST['UserId'];
    $InvoiceNo = $_POST['InvoiceNo'];
    $TotAmt = $_POST['TotAmt'];
    $TotPaidAmt = $_POST['TotPaidAmt'];
    $TotBalAmt = $_POST['TotBalAmt'];
    $PaidAmt = $_POST['PaidAmt'];
    $BalAmt = $_POST['BalAmt'];
    $CreatedDate = date('Y-m-d');

    $sql = "SELECT Fname,Lname FROM tbl_users WHERE id='$UserId'";
    $row = getRecord($sql);
    $VedName = $row['Fname']." ".$row['Lname'];

    $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }
    if($id == ''){
    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$UserId',AccountName='$VedName',InvoiceNo='$InvoiceNo',TotAmt='$TotAmt',TotPaidAmt='$TotPaidAmt',TotBalAmt='$TotBalAmt',PaidAmt='$PaidAmt',BalAmt='$BalAmt',CrDr='cr',Roll=5,Type='PR',CreatedDate='$CreatedDate'";
    $conn->query($sql4);
}
else{
    $sql4 = "UPDATE tbl_general_ledger SET PaidAmt='$PaidAmt',ModifiedDate='$CreatedDate' WHERE id='$id'";
    $conn->query($sql4);
}
    echo 1;
}

if($_POST['action'] == 'getInvoice'){
    $id = $_POST['id'];?>
    <option value="" selected>...</option>
    <?php 
      $sql1 = "SELECT * FROM tbl_sell WHERE CustId='$id'";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option <?php if($result['InvoiceNo'] == $row7['InvoiceNo']) {?> selected <?php } ?>  value="<?php echo $result['InvoiceNo']; ?>"><?php echo $result['InvoiceNo']; ?></option>
<?php } 
} 

if($_POST['action'] == 'getInvoiceDetails'){
    $id = $_POST['id'];
    $sql = "SELECT Total AS Amount FROM tbl_sell WHERE InvoiceNo='$id'";
    $row = getRecord($sql);
    $sql2 = "SELECT SUM(PaidAmt) As TotPaidAmt FROM tbl_general_ledger WHERE InvoiceNo='$id' AND Type='PR'";
    $row2 = getRecord($sql2);
    $TotAmt = $row['Amount'];
    $TotPaidAmt = $row2['TotPaidAmt'];
    $TotBalAmt = $TotAmt - $TotPaidAmt;
    echo json_encode(array('TotAmt'=>$TotAmt,'TotPaidAmt'=>$TotPaidAmt,'TotBalAmt'=>$TotBalAmt));
}

if($_POST['action'] == 'getCourse'){
    $id = $_POST['id'];
    $sql = "SELECT tc.Name AS CourseName,tu.CourseId,tc.CoursePrice FROM tbl_users tu LEFT JOIN tbl_courses tc ON tc.id=tu.CourseId WHERE tu.id='$id'";
    $row = getRecord($sql);
    echo json_encode(array("CourseName"=>$row['CourseName'],"CourseId"=>$row['CourseId'],"CourseFees"=>$row['CoursePrice']));
     } 
?>