<?php 
session_start();
include_once '../config.php';
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

 <script type="text/javascript">
        window.print();
    </script>
<?php  
$id = $_GET['id'];
$sql7 = "SELECT tlq.*,tu.Fname,tu.Phone,tu.EmailId,tu.Address,tlq.Address AS CustAddress FROM tbl_rooftop_lead_quotation tlq LEFT JOIN tbl_users tu ON tlq.CompId=tu.id WHERE tlq.id='$id'";
$row7 = getRecord($sql7);
$ProdSpec = $row7['ProdSpec'];
?>
<table width="100%" border="1px solid">
<tbody>
<tr>
<td>
<span style="float:right;"><img src="../logo.jpg" width="150">
</span>

<!--<p><strong>Regd Off : </strong>B-1, Thakkar Bhavan Handloom Market, Gandhibagh,<br> Nagpur - 440002 (M.S) <strong>Contact : </strong>9923870005</p>-->
<p><strong style="text-align: center;font-size:30px;font-weight:bold;"><?php echo $row7['Fname'];?></strong><br>
    <strong>Factory Address:</strong>-<?php echo $row7['Address'];?><br> Web : <a href="http://www.vtechsolar.com/">www.vtechsolar.com</a> <strong>Email: </strong><a href="mailto:<?php echo $row7['EmailId'];?>">enquiry.<?php echo $row7['EmailId'];?></a></p>

</td>

</tr>
</tbody>
</table>
<h1 style="text-align: center;">QUOTATION</h1>
<table width="100%" style="border: 0px;">
<tbody>
<tr>
<td style="border: 0px;">
<p><strong>To,</strong></p>
<p><strong><?php echo $row7['CustName'];?>,</strong></p>
<p><?php echo $row7['CustAddress'];?></p>
<p>Kind attn : <?php echo $row7['KindAttn'];?></p>
</td>
<td style="border: 0px;">
<p><strong>QTN NO. </strong><?php echo $row7['InvoiceNo'];?></p>
<p><strong>Date&nbsp;&nbsp; &nbsp;:</strong> <?php echo date("d.m.Y", strtotime(str_replace('-', '/',$row7['InvoiceDate']))); ?></p>
</td>
</tr>
</tbody>
</table>
<h4><strong style="text-align:justify;">&nbsp;Sub&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :-&nbsp; <?php echo $row7['QtnSubject'];?></strong></h4>
<p>&nbsp;</p>
<p><strong>Ref Enquiry No</strong>: - <?php echo $row7['RefEnqNo'];?></p>
<p>Dear Sir,</p>
<p>As per your enquiry, we are offering our most competative rate for your kind consideration :</p>
<p>&nbsp;</p>
<table border="1px solid">
<tbody>
<tr>
<td width="54">
<p><strong>Sr. No</strong></p>
</td>
<td width="353">
<p><strong>Item Particulars</strong></p>
</td>
<td width="80">
<p><strong>Unit</strong></p>
</td>
<td width="80">
<p><strong>Qty</strong></p>
</td>
<td width="73">
<p><strong>Rate/Unit</strong></p>
</td>
<td width="92">
<p><strong>Amount</strong></p>
</td>
</tr>
<tr>
<td width="54">
<p>&nbsp;</p>
</td>
<td width="353">
<p  style="white-space: pre-wrap;"><strong><?php echo $ProdSpec;?></strong></p>
</td>
<td width="80">
<p>&nbsp;</p>
</td>
<td width="80">
<p>&nbsp;</p>
</td>
<td width="73">
<p>&nbsp;</p>
</td>
<td width="92">
<p>&nbsp;</p>
</td>
</tr>
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_rooftop_lead_quotation_order_products WHERE SellId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
<tr>
<td width="54">
<p><strong>1</strong></p>
</td>
<td width="353">
<p><?php echo $result12['ProductName'];?></p>
</td>
<td width="80">
<p><?php echo $result12['Purity'];?></p>
</td>
<td width="80">
<p><?php echo $result12['Qty'];?></p>
</td>
<td width="73">
<p><?php echo $result12['Price'];?></p>
</td>
<td width="92">
<p><?php echo $result12['TotalRate'];?></p>
</td>
</tr>
<?php } ?>

<tr>
<td width="54">
<p>&nbsp;</p>
</td>
<td width="353">
<p>&nbsp;</p>
</td>
<td width="80">
<p>&nbsp;</p>
</td>
<td width="80">

</td>
<td width="73">
<p><strong>Total</strong></p>
</td>
<td width="92">
<p><strong><?php echo $row7['TotalAmt'];?></strong></p>
</td>
</tr>
</tbody>
</table>
<h1 style="font-size:16px;">Terms &amp; Conditions:-</h1>
<?php echo $row7['TermsCondition'];?>
<p>&nbsp;</p>
<p>We hope that the above offer is in line with your requirement. Awaiting your valued response at the earliest.</p>
<p>&nbsp;</p>
<h2>Thanking You,</h2>
<p><strong>FOR <?php echo $row7['Fname'];?>.</strong></p>
<p>&nbsp;</p>

</body>
</html>

