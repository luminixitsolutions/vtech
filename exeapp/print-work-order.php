<?php 
session_start();
include_once 'config.php';
?>
<!DOCTYPE html>
<html>
<body>
    
<style>
body {
  font-family:"Calibri", sans-serif;
}
p {
  display: block;
  margin-top: 1px;
  margin-bottom: 1px;
  margin-left: 0;
  margin-right: 0;
  text-align:justify;
}
@media print{
 .bel{
     position:fixed;
     bottom:0;
     }
}

@media print{
 .bel2{
     position:fixed;
     top:0;
     }
}

li {
    
    text-align:justify;
}

</style>

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  padding-right: 5px;
  padding-left: 5px;
}
</style>

</style>
 <script type="text/javascript">
        window.print();
    </script>

<?php  
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_work_order WHERE id='$id'";
$row7 = getRecord($sql7);
?>

<table width="100%" border="1px solid">
<tbody>
<tr>
<td>

<img src="wi.png" style="float:left;padding-right:25px;"> <strong style="font-size:50px;">VTECH ENGINEERS</strong><br>
<strong>Mobile: 9923870005, 9372032323 Email :vtech.engrs@gmail.com<br>
Regd Off : B-1,Thakkar Bhavan Handloom Market, Gandhibagh, Nagpur–440002 (M.S)
</strong>

</td>

</tr>
</tbody>
</table>

<p>Ref. No . <?php echo $row7['RefEnqNo'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dated <?php echo date("d.m.Y", strtotime(str_replace('-', '/',$row7['InvoiceDate']))); ?></p>
<p>&nbsp;</p>
<h2>To,</h2>
<p><strong><u>M/s .</u><?php echo $row7['CustName'];?></strong></p>
<h2>District-<?php echo $row7['Address'];?> Contact No-<?php echo $row7['CellNo'];?></h2>
<p><strong>&nbsp;</strong></p>
<p><strong><u>Kind Attn: <?php echo $row7['KindAttn'];?></u></strong></p>
<p><strong>&nbsp;</strong></p>
<p><strong><u>SUB </u></strong><strong>: </strong><?php echo $row7['QtnSubject'];?> </strong>.</p>
<p>&nbsp;</p>
<p>Ref.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; &nbsp;</p>
<?php 
$i=1;
$sql_1 = "SELECT * FROM tbl_wo_references WHERE WoId='$id'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<p><?php echo $i;?>. <?php echo $result['Ref']; ?> </p>
<?php $i++;} ?>
<?php echo $row7['Details'];?>
<p>&nbsp;</p>
<h2>Annexure &ldquo;A&rdquo; &ndash; Rate &amp; Qty Details:</h2>
<p><strong>&nbsp;</strong></p>
<?php echo $row7['TermsCondition'];?>
<p><strong>&nbsp;</strong></p>
<p><strong><u>Annexure &ldquo;B&rdquo; &ndash; Standard Terms &amp; Conditions:</u></strong></p>
<p><strong>&nbsp;</strong></p>
<?php echo $row7['TermsCondition2'];?>
<p>&nbsp;</p>
<h4>Annexure &ldquo;C&rdquo; &ndash; Standard Terms &amp; Conditions:</h4>
<?php echo $row7['TermsCondition3'];?>
<p>&nbsp;</p>
<h4>Annexure :</h4>
<?php echo $row7['TermsCondition4'];?>
<p>&nbsp;</p>
</body>
</html>