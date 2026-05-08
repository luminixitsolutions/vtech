<?php 
session_start();
include_once 'config.php';
?>
 <!DOCTYPE html>
<html>
<body>

    <style>
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

</style>

 <style type="text/css">
         @media print {
        @page {
            margin-top: 10px;
            margin-left: 50px;
            margin-right: 0px;
            margin-bottom: 0px;
        }

        @page :footer {
            display: none
        }

        .noPrint {
            display: none;
        }

        @page :header {
            display: none
        }
    }

    @media print {
        a[href]:after {
            content: none !important;
        }
    }

    @media screen {
        div.divFooter {
            display: none;
        }
    }

    @media print {
        div.divFooter {
            position: fixed;
            bottom: 20;
        }
    }

    table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 5px;
}
    </style>
    <script type="text/javascript">
        window.print();
    </script>
<?php  
$id = $_GET['id'];
$sql7 = "SELECT tp.*,tu.GstNo,tu2.Fname As CompanyName,tu2.EmailId AS CompanyEmail,tu2.Address AS CompanyAddress FROM tbl_purchase_order tp 
         LEFT JOIN tbl_users tu ON tp.CustId=tu.id 
         LEFT JOIN tbl_users tu2 ON tp.CompId=tu2.id WHERE tp.id='$id'";
$row = getRecord($sql7);

   //$number = $row['PaidAmt'];
  //include_once 'convert_currancy.php';
?>

<img width="200px" src="logo1.jpg">
<span style="float:right;padding-right: 20px;"><img width="150px" src="logo.jpg"></span>
<br>
Ref. No. VSIPL/22-23/NGP/PO- 85 <span style="float:right;">Dated <?php echo date("d.m.Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></span>

<h3>To,</h3>
<p><?php echo $row['CustName']; ?></p>
<p><?php echo $row['Address']; ?></p>
 <p>GSTIN <?php echo $row['GstNo']; ?></p>

<h3>Kind Attn:</h3>
<p><strong><u>SUB</u> </strong><strong>: </strong><?php echo $row['PurchaseSubject']; ?></strong></p>
<br>
<strong>Ref.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </strong>
<?php 
$i=1;
$sql_1 = "SELECT * FROM tbl_po_references WHERE SellId='$id'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<p><?php echo $i;?>. <?php echo $result['Ref']; ?> </p>
<?php $i++;} ?>
<br>
<p><?php echo $row['Details']; ?></p>

<table border="1px solid" width="100%">
<tbody>
<tr>
<td style="width: 57px;">
<strong>Sr.No</strong>
</td>
<td style="width: 236px;">
<strong>Item Particulars</strong>
</td>
<td style="width: 76px;">
<strong>Qty</strong>
</td>
<td style="width: 76px;">
<strong>Unit</strong>
</td>
<td style="width: 104px;">
<strong>Rate</strong>
</td>
<td style="width: 114px;">
<strong>Amount</strong>
</td>
</tr>
  <?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_purchase_order_products WHERE SellId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
<tr>
<td style="width: 57px;">

<?php echo $i;?>
</td>
<td style="width: 236px;">

<?php echo $result12['ProductName']; ?>
</td>
<td style="width: 76px;">

<?php echo $result12['Qty']; ?>
</td>
<td style="width: 76px;">

<?php echo $result12['Purity']; ?>
</td>
<td style="width: 104px;">

<?php echo $result12['Price']; ?>
</td>
<td style="width: 114px;">

<?php echo $result12['TotalRate']; ?>
</td>
</tr>
  <?php $i++;} ?>
<tr>
<td style="width: 573px;" colspan="5">
<strong>Total</strong>
</td>
<td style="width: 114px;">
<strong><?php echo $row['Total']; ?></strong>
</td>
</tr>
</tbody>
</table>



<p style="page-break-after: always;">&nbsp;</p>

<img width="200px" src="logo1.jpg">
<span style="float:right;padding-right: 20px;"><img width="150px" src="logo.jpg"></span>
<br>

<p><strong><u>Annexure &ldquo;B&rdquo; &ndash; Standard Terms &amp; Conditions:</u></strong></p>

<?php echo $row['TermsCondition']; ?>



<p style="page-break-after: always;">&nbsp;</p>

<img width="200px" src="logo1.jpg">
<span style="float:right;padding-right: 100px;"><img width="150px" src="logo.jpg"></span>
<br>

<?php echo $row['ConsigneeAddress']; ?>


<p style="page-break-after: always;">&nbsp;</p>

<img width="200px" src="logo1.jpg">
<span style="float:right;padding-right: 20px;"><img width="150px" src="logo.jpg"></span>
<br>
<?php echo $row['SpecialConditions']; ?>

<div class="bel">
<strong style="font-size: 40px;"><?php echo $row['CompanyName'];?></strong><br>
<strong style="font-size: 20px;">Add: <?php echo $row['CompanyAddress'];?><br>
Email : <?php echo $row['CompanyEmail'];?></strong>
</div>

</body>
</html> 