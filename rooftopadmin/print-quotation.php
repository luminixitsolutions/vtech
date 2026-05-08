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
$sql7 = "SELECT tq.*,tu.Fname,tu.Address,tu.GstNo,tu.EmailId,tu.BankName,tu.AccountNo,tu.Branch,tu.IfscCode FROM tbl_rooftop_quotation tq LEFT JOIN tbl_users tu ON tq.CompId=tu.id WHERE tq.id='$id'";
$row7 = getRecord($sql7);
?>
<table width="100%">
<tbody>
<tr style="height: 35px;">
<td style="height: 35px; width: 79.0536%;" colspan="10">
<p><strong>PROFORMA&nbsp; INVOICE</strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p><strong><?php echo $row7['Fname'];?></strong></p>
</td>
<td style="height: 35px; width: 11%;" colspan="2">
<p>Profoma Invoice No.</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 6%;">
<p>Dated</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 10%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 48px;">
<td style="height: 48px; width: 32%;" colspan="2">
<p><?php echo $row7['Address'];?></p>
</td>
<td style="height: 48px; width: 11%;" colspan="2">
<p><strong><?php echo $row7['InvoiceNo'];?></strong></p>
</td>
<td style="height: 48px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 48px; width: 6%;">
<p><strong><?php echo date("d-m-Y", strtotime(str_replace('-', '/',$row7['InvoiceDate']))); ?></strong></p>
</td>
<td style="height: 48px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 48px; width: 10%;">
<p>&nbsp;</p>
</td>
<td style="height: 48px; width: 8.05361%;">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p></p>
</td>
<td style="height: 70px; width: 17%;" colspan="3" rowspan="2">
<p>Delivery Note</p>
</td>
<td style="height: 70px; width: 30.0536%;" colspan="4" rowspan="2">
<p><?php echo $row7['DeliveryNote'];?></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p>GST NO.<?php echo $row7['GstNo'];?></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p>GSTIN/UIN: <?php echo $row7['GstNo'];?></p>
</td>
<td style="height: 48px; width: 17%;" colspan="3" rowspan="2">
<p>Supplier's Ref.</p>
</td>
<td style="height: 48px; width: 30.0536%;" colspan="4" rowspan="2">
<p><?php echo $row7['SupplierRef'];?></p>
</td>

</tr>
<tr style="height: 13px;">
<td style="height: 48px; width: 32%;" colspan="2" rowspan="2">
<p><a href="mailto:<?php echo $row7['EmailId'];?>">State Name :&nbsp; Maharashtra, Code : 27<br /> <strong>E-Mail : <?php echo $row7['EmailId'];?></strong></a></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 70px; width: 17%;" colspan="3" rowspan="2">
<p>Buyer's Order No.   <?php echo $row7['BuyerOrderNo'];?></p>
</td>
<td style="height: 35px; width: 6%;">
<p>Dated</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 10%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 7%;">
<p><strong>Consignee</strong></p>
</td>
<td style="height: 35px; width: 25%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 6%;">
<p><strong><?php echo date("d.m.Y", strtotime(str_replace('-', '/',$row7['BuyerDate']))); ?></strong></p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 10%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p><strong> <?php echo $row7['CustName'];?></strong></p>
</td>
<td style="height: 83px; width: 17%;" colspan="3" rowspan="2">
<p>Despatch Document No. <?php echo $row7['DispatchDocNo'];?></p>
</td>
<td style="height: 83px; width: 30.0536%;" colspan="4" rowspan="2">
<p>Delivery Note Date : <?php echo date("d.m.Y", strtotime(str_replace('-', '/',$row7['DeliveryDate']))); ?></p>
</td>

</tr>
<tr style="height: 48px;">
<td style="height: 48px; width: 32%;" colspan="2">
<p><strong><?php echo $row7['Address'];?></strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 7%;">
<p><strong>GSTIN/UIN:</strong></p>
</td>
<td style="height: 35px; width: 25%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 17%;" colspan="3">
<p>Despatched through <?php echo $row7['DispatchThrough'];?></p>
</td>
<td style="height: 35px; width: 30.0536%;" colspan="4">
<p>Destination <?php echo $row7['Destination'];?></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p>State Name :&nbsp; Jharkhand , Code :</p>
</td>
<td style="height: 105px; width: 8%;" rowspan="3" colspan="7">
<p>Terms of Delivery</p>
<?php echo $row7['TermsCondition'];?>
</td>


</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p><strong>Buyer (if other than consignee)</strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p><strong>M/s. <?php echo $row7['CustName'];?></strong></p>
</td>

</tr>
<tr style="height: 48px;">
<td style="height: 48px; width: 32%;" colspan="2">
<p><strong><?php echo $row7['Address2'];?></strong></p>
</td>
<td style="height: 83px; width: 6%;" rowspan="2">
<p>&nbsp;</p>
</td>
<td style="height: 83px; width: 5%;" rowspan="2">
<p>&nbsp;</p>
</td>
<td style="height: 83px; width: 6%;" rowspan="2">
<p>&nbsp;</p>
</td>
<td style="height: 83px; width: 6%;" rowspan="2">
<p>&nbsp;</p>
</td>
<td style="height: 83px; width: 6%;" rowspan="2">
<p>&nbsp;</p>
</td>
<td style="height: 83px; width: 10%;" rowspan="2">
<p>&nbsp;</p>
</td>
<td style="height: 83px; width: 8.05361%;" rowspan="2">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 7%;">
<p><strong>GSTIN/UIN:</strong></p>
</td>
<td style="height: 35px; width: 25%;">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p>State Name :&nbsp; Jharkhand , Code :</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 5%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 10%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p><strong>Description of Goods</strong></p>
</td>
<td style="height: 35px; width: 6%;">
<p><strong>HSN/SAC</strong></p>
</td>
<td style="height: 35px; width: 5%;">
<p><strong>GST</strong></p>
</td>
<td style="height: 35px; width: 6%;">
<p><strong>Qty</strong></p>
</td>
<td style="height: 35px; width: 6%;">
<p><strong>Rate</strong></p>
</td>
<td style="height: 35px; width: 6%;">
<p><strong>GST</strong></p>
</td>
<td style="height: 35px; width: 10%;">
<p><strong>Unit</strong></p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p><strong>Amount</strong></p>
</td>

</tr>
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_rooftop_quotation_order_products WHERE SellId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
    $TotQty+=$result12['Qty'];
     ?>
<tr style="height: 35px;">
<td style="height: 35px; width: 32%;" colspan="2">
<p><?php echo $result12['ProductName'];?></p>
</td>
<td style="height: 35px; width: 6%;">
<p></p>
</td>
<td style="height: 35px; width: 5%;">
<p><?php echo $result12['SGST'];?>%</p>
</td>
<td style="height: 35px; width: 6%;">
<p><?php echo $result12['Qty'];?></p>
</td>
<td style="height: 35px; width: 6%;">
<p><?php echo $result12['Price'];?></p>
</td>
<td style="height: 35px; width: 6%;">
<p><?php echo $result12['SgstAmt'];?></p>
</td>
<td style="height: 35px; width: 10%;">
<p><?php echo $result12['Purity'];?></p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p><?php echo $result12['TotalRate'];?></p>
</td>

</tr>
<?php } ?>

<tr style="height: 35px;">
<td style="height: 35px; width: 43%;" colspan="4">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 6%;">
<p><strong><?php echo $TotQty;?></strong></p>
</td>
<td style="height: 35px; width: 22%;" colspan="3">
<p><strong>Amount</strong></p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p><strong><?php echo $row7['SubTotal'];?></strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 43%;" colspan="4">
<p><strong>Total</strong></p>
</td>
<td style="height: 35px; width: 6%;">
<p>&nbsp;</p>
</td>
<td style="height: 35px; width: 22%;" colspan="3">
<p><strong>Total</strong></p>
</td>
<td style="height: 35px; width: 8.05361%;">
<p><strong>&nbsp;</strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 43%;" colspan="4">
<p><strong>100% Advance against receipt of PI</strong></p>
</td>
<td style="height: 35px; width: 36.0536%;" colspan="5">
<p>&nbsp;</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 79.0536%;" colspan="9">
<p><strong>Payment Terms: <?php echo $row7['PaymentTerms'];?></strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 7%;">
<p><strong>Freight:-</strong></p>
</td>
<td style="height: 35px; width: 72.0536%;" colspan="8">
<p><strong><?php echo $row7['Freight'];?></strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 7%;">
<p><strong>Delivery:-</strong></p>
</td>
<td style="height: 35px; width: 72.0536%;" colspan="8">
<p><strong><?php echo $row7['Delivery'];?></strong></p>
</td>

</tr>
<tr style="height: 48px;">
<td style="height: 179px; width: 32%;" colspan="2" rowspan="4">
<p>Remarks :</p>
</td>
<td style="height: 48px; width: 6%;">
<p><strong>Bank Name:</strong></p>
</td>
<td style="height: 48px; width: 41.0536%;" colspan="6">
<p><strong><?php echo $row7['BankName'];?></strong></p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 6%;">
<p><strong>A/c No.:</strong></p>
</td>
<td style="height: 35px; width: 41.0536%;" colspan="6">
<p><strong><?php echo $row7['AccountNo'];?></strong></p>
</td>

</tr>
<tr style="height: 48px;">
<td style="height: 48px; width: 6%;">
<p><strong>Branch&nbsp; Add:</strong></p>
</td>
<td style="height: 48px; width: 11%;" colspan="2">
<p><strong><?php echo $row7['Branch'];?></strong></p>
</td>
<td style="height: 48px; width: 30.0536%;" colspan="4">
<p><strong>IFSC Code:- <?php echo $row7['IfscCode'];?></strong></p>
</td>

</tr>
<tr style="height: 48px;">
<td style="height: 48px; width: 47.0536%;" colspan="7">
<p><strong>For <?php echo $row7['Fname'];?><br /> </strong>Authorised Signatory</p>
</td>

</tr>
<tr style="height: 35px;">
<td style="height: 35px; width: 79.0536%;" colspan="9">
<p>SUBJECT TO NAGPUR JURISDICTION</p>
</td>

</tr>
</tbody>
</table>
<p>&nbsp;</p>

</body>
</html>