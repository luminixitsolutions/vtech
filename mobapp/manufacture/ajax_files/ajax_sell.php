<?php 
session_start();
include_once '../../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'calEmi'){
    $Balance = addslashes(trim($_POST['Balance']));
    $EmiMonth = $_POST['EmiMonth'];
    $EmiAmt = round($Balance/$EmiMonth);
    $InvoiceDate = $_POST['InvoiceDate'];
?>
<table class="table table-striped table-bordered">
         <thead>
            <tr>
              <th>Date</th>
              <th>EMI</th>
              <th>Cheque No</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=1;$i<=$EmiMonth;$i++){
                $TotalEmi+=$EmiAmt;
                $date = date('Y-m-d', strtotime('+'.$i.' month', strtotime($InvoiceDate)));
                ?>
            <tr>
                <td><input type="date" name="EmiDate[]" id="EmiDate<?php echo $i;?>" class="form-control" value="<?php echo $date;?>"></td>
                <td><input type="text" name="EmiAmt[]" id="EmiAmt<?php echo $i;?>" class="form-control txt" value="<?php echo $EmiAmt;?>" oninput="checkEmiAmt(<?php echo $i;?>)"></td>
                <td><input type="text" name="EmiChequeNo[]" id="EmiChequeNo<?php echo $i;?>" class="form-control"></td>
            </tr>
        <?php } ?>
        <input type="hidden" name="EmiBalance" id="EmiBalance" class="" value="<?php echo $Balance;?>">
        <input type="hidden" name="TotalEmiAmt" id="TotalEmiAmt" class="" value="<?php echo round($TotalEmi);?>">
        <tr>
                <td>Total</td>
                <td class="TotalEmiAmt"><?php echo round($TotalEmi);?></td>
                <td></td>
            </tr>
            <tr id="errormsg" style="display:none;">
                <th style="color:red;" colspan="3">Amount must be equal to balance amt</th>
            </tr>
        </tbody>
    </table>
<?php } 

if($_POST['action'] == 'calEditEmi'){
    $Balance = addslashes(trim($_POST['Balance']));
    $EmiMonth = $_POST['EmiMonth'];
    $EmiAmt = round($Balance/$EmiMonth);
    $InvoiceDate = $_POST['InvoiceDate'];
    $SellId = $_POST['SellId'];
?>
<table class="table table-striped table-bordered">
         <thead>
            <tr>
              <th>Date</th>
              <th>EMI</th>
              <th>Cheque No</th>
            </tr>
        </thead>
        <tbody>
            <?php $sql = "SELECT * FROM tbl_emi WHERE SellId='$SellId' ORDER BY EmiDate ASC";
            $row = getList($sql);
            foreach($row as $result){
                $TotalEmi+=$result['EmiAmt'];
                $date = date('Y-m-d', strtotime('+'.$i.' month', strtotime($InvoiceDate)));
                if($result['PayStatus'] == 1){
                    $Bgcolor = "#adffad";
                }
                else{
                    $Bgcolor = "";
                }
                ?>
            <tr style="background-color:<?php echo $Bgcolor;?>">
                <td><input type="date" name="EmiDate[]" id="EmiDate<?php echo $i;?>" class="form-control" value="<?php echo $result['EmiDate'];?>"></td>
                <td><input type="text" name="EmiAmt[]" id="EmiAmt<?php echo $i;?>" class="form-control txt" value="<?php echo $result['EmiAmt'];?>" oninput="checkEmiAmt(<?php echo $i;?>)"></td>
                <td><input type="text" name="EmiChequeNo[]" id="EmiChequeNo<?php echo $i;?>" class="form-control" value="<?php echo $result['EmiChequeNo'];?>"></td>
            </tr>
        <?php } ?>
        
        <tr>
                <td>Total</td>
                <td class="TotalEmiAmt"><?php echo round($TotalEmi);?></td>
                <td></td>
            </tr>
          
        </tbody>
    </table>
<?php } 

if($_POST['action'] == 'getEmiPayDetails'){
$id = $_POST['id'];
$sql = "SELECT * FROM tbl_emi WHERE id='$id'";
$row = getRecord($sql);
echo json_encode(array('EmiAmt'=>$row['EmiAmt'],'EmiDate'=>$row['EmiDate'],'ReceiptNo'=>$row['ReceiptNo'],'ReceiptDate'=>$row['ReceiptDate'],'PayMode'=>$row['PayMode'],'Narration'=>$row['Narration']));
}

if($_POST['action'] == 'saveReceipt'){
$InvId = $_POST['InvId'];
$ReceiptNo = addslashes(trim($_POST['ReceiptNo']));
$ReceiptDate = addslashes(trim($_POST['ReceiptDate']));
$ReceiptAmt = addslashes(trim($_POST['ReceiptAmt']));
$PayMode = addslashes(trim($_POST['PayMode']));
$Narration = addslashes(trim($_POST['Narration']));
$EmiId = $_POST['EmiId'];
$CreatedDate = date('Y-m-d');

$sql2 = "SELECT te.*,tu.Fname FROM tbl_sell te LEFT JOIN tbl_users tu ON te.CustId=tu.id WHERE te.id='$InvId'";
$row2 = getRecord($sql2);
$CustId = $row2['CustId'];
$CustName = $row2['CustName'];
$EmiAmt = $row2['EmiAmt'];
$sql = "INSERT INTO tbl_paid_emi SET InvId='$InvId',ReceiptNo='$ReceiptNo',ReceiptDate='$ReceiptDate',ReceiptAmt='$ReceiptAmt',PayMode='$PayMode',Narration='$Narration'";
$conn->query($sql);
$postid = mysqli_insert_id($conn);

$sql22 = "SELECT SUM(ReceiptAmt) AS TotPaidAmt FROM tbl_paid_emi WHERE InvId='$InvId'";
$row22 = getRecord($sql22);
$TotPaidAmt = $row22['TotPaidAmt'];


$sql_1 = "SELECT * FROM tbl_emi WHERE SellId='$InvId' ORDER BY EmiDate LIMIT 0,1";
$rncnt_1 = getRow($sql_1);
if($rncnt_1 > 0){
$row_1 = getRecord($sql_1);
$EmiAmt_1 = $row_1['EmiAmt'];
$PayStatus_1 = $row_1['PayStatus'];
$id_1 = $row_1['id'];

if($TotPaidAmt >= $EmiAmt_1){
    $BalAmt_1 = $TotPaidAmt - $EmiAmt_1;
    $sql33 = "UPDATE tbl_emi SET PayStatus=1,ReceiptAmt='0' WHERE id='$id_1'";
    $conn->query($sql33);
}
else if($TotPaidAmt < $EmiAmt_1){
    $BalAmt_1 = $TotPaidAmt - $TotPaidAmt;
    $sql33 = "UPDATE tbl_emi SET PayStatus=2,ReceiptAmt='0' WHERE id='$id_1'";
    $conn->query($sql33);
}

}

function changeStatus($balamt,$InvId,$stlimit){
    global $conn;
    $sql = "SELECT * FROM tbl_emi WHERE SellId='$InvId' ORDER BY EmiDate LIMIT $stlimit,1";
    $res = $conn->query($sql);
    $rncnt = mysqli_num_rows($res);
    if($rncnt > 0){
    $row = $res->fetch_assoc();
    $EmiAmt = $row['EmiAmt'];
    $id = $row['id'];
    if($balamt >= $EmiAmt){
    $BalAmt = $balamt - $EmiAmt;
    $sql33 = "UPDATE tbl_emi SET PayStatus=1,ReceiptAmt='0' WHERE id='$id'";
    $conn->query($sql33);
    }
    else if($balamt < $EmiAmt){
        $BalAmt = $balamt - $balamt;
        $sql33 = "UPDATE tbl_emi SET PayStatus=2,ReceiptAmt='0' WHERE id='$id'";
        $conn->query($sql33);
    }
    else if($balamt == 0){
        $BalAmt = 0;
        $sql33 = "UPDATE tbl_emi SET PayStatus=0,ReceiptAmt='0' WHERE id='$id'";
        $conn->query($sql33);
    }
}

return $BalAmt;
}
if($BalAmt_1 > 0){
$BalAmt_2 = changeStatus($BalAmt_1,$InvId,1);
}
if($BalAmt_2 > 0){
$BalAmt_3 = changeStatus($BalAmt_2,$InvId,2);
}
if($BalAmt_3 > 0){
$BalAmt_4 = changeStatus($BalAmt_3,$InvId,3);
}
if($BalAmt_4 > 0){
$BalAmt_5 = changeStatus($BalAmt_4,$InvId,4);
}
if($BalAmt_5 > 0){
$BalAmt_6 = changeStatus($BalAmt_5,$InvId,5);
}
if($BalAmt_6 > 0){
$BalAmt_7 = changeStatus($BalAmt_6,$InvId,6);
}
if($BalAmt_7 > 0){
$BalAmt_8 = changeStatus($BalAmt_7,$InvId,7);
}
if($BalAmt_8 > 0){
$BalAmt_9 = changeStatus($BalAmt_8,$InvId,8);
}
if($BalAmt_9 > 0){
$BalAmt_10 = changeStatus($BalAmt_9,$InvId,9);
}
if($BalAmt_10 > 0){
$BalAmt_11 = changeStatus($BalAmt_10,$InvId,10);
}
if($BalAmt_11 > 0){
$BalAmt_12 = changeStatus($BalAmt_11,$InvId,11);
}
if($BalAmt_12 > 0){
$BalAmt_13 = changeStatus($BalAmt_12,$InvId,12);
}
if($BalAmt_13 > 0){
$BalAmt_14 = changeStatus($BalAmt_13,$InvId,13);
}
if($BalAmt_14 > 0){
$BalAmt_15 = changeStatus($BalAmt_14,$InvId,14);
}


$sql33 = "DELETE FROM tbl_general_ledger WHERE EmiId='$postid'";
$conn->query($sql33);
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

    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$ReceiptNo',TotAmt='0',TotPaidAmt='0',TotBalAmt='0',PaidAmt='$ReceiptAmt',BalAmt='0',CrDr='cr',Roll=5,Type='PR',CreatedDate='$CreatedDate',SellId='$InvId',Narration='$Narration',EmiId='$postid',PayMode='$PayMode'";
    $conn->query($sql4);

    echo 1;
}


if($_POST['action'] == 'saveComplaint'){
$CustId = $_POST['CustId'];
$Title = addslashes(trim($_POST['Title']));
$ComplaintDate = addslashes(trim($_POST['ComplaintDate']));
$Complaint = addslashes(trim($_POST['Complaint']));

$sql = "INSERT INTO tbl_complaints SET CustId='$CustId',Title='$Title',ComplaintDate='$ComplaintDate',Complaint='$Complaint',CreatedBy='$user_id',Status=1";
$conn->query($sql);

}
?>