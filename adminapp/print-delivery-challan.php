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
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
   padding-right: 5px;
  padding-left: 5px;
}
</style>

<?php  
$id = $_GET['id'];
$id = $_GET['id'];
$sql7 = "SELECT tp.*,tu.GstNo,tu.EmailId,tu.Phone2,tu2.Fname As CompName,tu2.Address As CompAddress,tu2.EmailId AS CompEmailId,tu2.Phone As CompPhone,tu2.Phone2 AS CompPhone2,tu3.Fname As AgencyName,tu3.Phone As AgencyPhone,tu3.EmailId As AgencyEmailId,tu2.Photo AS CompLogo,tu3.Address As AgencyAddress FROM tbl_sell tp 
         LEFT JOIN tbl_users tu ON tp.CustId=tu.id 
         LEFT JOIN tbl_users tu2 ON tp.CompId=tu2.id 
         LEFT JOIN tbl_users tu3 ON tp.AgencyId=tu3.id 
         WHERE tp.id='$id'";
$row = getRecord($sql7);

$sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_sell_products` WHERE SellId='$id'";
$row2 = getRecord($sql2);
$TotQty = $row2['TotQty'];
                
   //$number = $row['PaidAmt'];
  //include_once 'convert_currancy.php';
?>
<table border="1px solid">
<tbody>
    <tr>
<td colspan="7"  align="center">
        <!--<img src="../uploads/<?php echo $row['CompLogo'];?>"><br>-->
<strong style="text-align: center;font-size:20px;font-weight:bold;">DELIVERY CHALLAN</strong>

</td>
</tr>

<tr>
<td colspan="6"  align="center">
        <!--<img src="../uploads/<?php echo $row['CompLogo'];?>"><br>-->
<strong style="text-align: center;font-size:30px;font-weight:bold;"><?php echo $row['CompName'];?></strong>
<p style="text-align: center;"><?php echo $row['CompAddress'];?></p>
<!--<p style="text-align: center;">MOUZA-KAPSI(BUJURG), BHANDARA ROAD, NAGPUR-441104</p>-->
<p style="text-align: center;">Website : <a href="http://www.vtechsunsystems.com/">www.vtechsunsystems.com </a>E-Mail : <a href="mailto:<?php echo $row['CompEmailId'];?>"><?php echo $row['CompEmailId'];?></a> Phone : <?php echo $row['CompPhone'];?>, <?php echo $row['CompPhone2'];?></p>
</td>
<td colspan="1" >
<div align="center"><img width="200px" src="../uploads/<?php echo $row['CompLogo'];?>"></div>
</td>
</tr>
<tr>
<td colspan="4" rowspan="5" width="405">
<p><strong><?php echo $row['AgencyName'];?></strong></p>
<p><?php echo $row['AgencyAddress'];?></p>
<p><strong>PHONE NO. : <?php echo $row['AgencyPhone'];?> <br>EMAIL ID. : <?php echo $row['AgencyEmailId'];?></strong></p>
<p>Project Code : <?php echo $row['ProjectCode'];?></p>
</td>
<td colspan="3" width="342">
<p>DM NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><?php echo $row['InvoiceNo'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date : <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></strong></p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>L.R. No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><?php echo $row['LrNo'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date : <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['LrDate']))); ?></strong></p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>Transport : <?php echo $row['Transport'];?></p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>Project Type :SELF PROJECT</p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>Slip No.:VE-01&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Weight : <?php echo $row['Weight'];?></p>
</td>
</tr>
<tr>
<td colspan="2" width="245">
<p><strong>Consignee:</strong></p>
<p><strong><?php echo $row['CustName'];?></strong></p>
<p><strong><?php echo $row['Address'];?></strong></p>
</td>
<td colspan="3" width="255">
<p><strong>Beneficiary Contact :</strong> <?php echo $row['CellNo'];?></p>
<p><strong>Alternate Contact :</strong> <?php echo $row['Phone2'];?></p>
<p><strong>Email id:</strong> <?php echo $row['EmailId'];?></p>
<!--<p><strong>Agency Contact: <?php echo $row['AgencyPhone'];?></strong></p>
<p><strong>Agency Email: </strong><a href="mailto:<?php echo $row['AgencyEmailId'];?>"><strong><?php echo $row['AgencyEmailId'];?></strong></a></p>-->
</td>
<td colspan="2" width="247">
<p><strong>Site Engineer : <?php echo $row['SiteEngineerName'];?><br> Contact No. : <?php echo $row['SiteEngineerContactNo'];?></strong></p>
<p><strong>Site Manager : <?php echo $row['SiteManagerName'];?><br> Contact No. &nbsp;: <?php echo $row['SiteManagerContactNo'];?></strong></p>
</td>
</tr>
<tr style="background-color:#b3ffff;">
<td width="23">
<p><strong>Sr.</strong></p>
</td>
<td colspan="2" width="332">
<p><strong>P R O D U C T</strong></p>
</td>
<td colspan="2" width="145">
<p><strong>MODEL NO.</strong></p>
</td>
<td width="155">
<p><strong>SERIAL NO.</strong></p>
</td>
<td >
<p><strong>QTY.</strong></p>
</td>
</tr>
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_sell_products WHERE SellId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
<tr>
<td width="23">
<p><?php echo $i;?></p>
</td>
<td colspan="2" width="332">
<p><?php echo $result12['ProductName'];?></p>
</td>
<td colspan="2" width="145">
<p><?php echo $result12['ModelNo'];?></p>
</td>
<td width="155">
<p><?php echo $result12['SerialNo'];?></p>
</td>
<td width="92">
<p><?php echo $result12['Qty'];?> </p>
</td>
</tr>
<?php $i++;} ?>
<tr>
<td colspan="6" width="747" style="text-align:right;">
<p><strong>TOTAL C/F</strong></td>
<td >
<strong><?php echo $TotQty;?></strong></p>
</td>
</tr>
<tr>
<td colspan="5" width="747"></td>
<td >
<p><strong>GRAND TOTAL</strong></p>
</td>
<td >
<strong><?php echo $TotQty;?></strong></p>
</td>
</tr>
<tr>
    
    <tr>

<td colspan="7" >
<p><strong>TOTAL QUANTITY : <?php echo $TotQty;?></strong></p>
<p>Remark1 : <?php echo $row['Remark1'];?></p>
<p>Remark2 : <?php echo $row['Remark2'];?></p>
</td>
</tr>
<tr>
    
    
    <td colspan="4" >
        <p><strong>Terms &amp; Conditions</strong><br>Goods once sold will not be taken back or exchanged.<br>Bills not paid due date will attract 24% interest.<br>All disputes subject to NAGPUR Jurisdication only.<br>Prescribed Sales Tax declaration will be given.<br>-----------------------------------------------------------------<br>Certified that the particulars given above are true and correct<br>and the amount indicated represents the price actually charged.</p>
        
    </td>
<td colspan="1" >
    

<p>Checked By </p>
<p> E.&amp;O.E. 
</td>

<td colspan="2" >
<div align="center"><p><strong>For <?php echo $row['CompName'];?></strong></p><br><br><br>
<strong>Authorised signatory</strong></p></div>

</td>
</tr>


<tr>
    
    <td colspan="2" >
       <strong> <p>Verified By</p>
        Store Head</strong>
    </td>
<td colspan="2" >
   <strong> <p>Security Guard Authentification</p>
    Register Entry No. </strong>
 
</td>

<td colspan="3" >
<div align="center"><p><strong>For <?php echo $row['CompName'];?></strong></p><br><br><br>
<strong>Dispatch Officer</strong></p>
<strong><p>Accepted By</p>
Approved Authority</strong>
</div>

</td>
</tr>

</tbody>
</table>
<p>&nbsp;</p>
<!-- <table border="1px solid">
<tbody>
<tr>
<td colspan="7" width="747">
<h1 style="text-align: center;"><strong>VTECH ENGINEERS</strong></h1>
<p style="text-align: center;">PLOT NO.25,26, PH NO.20,RISHABH INDUSTRIAL ESTATE</p>
<p style="text-align: center;">MOUZA-KAPSI(BUJURG), BHANDARA ROAD, NAGPUR-441104&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p style="text-align: center;">Website : <a href="http://www.vtechsunsystems.com/">www.vtechsunsystems.com </a>E-Mail : <a href="mailto:vtech.engrs@gmail.com">vtech.engrs@gmail.com</a></p>
<p style="text-align: center;">Phone : 8484937592, 8484937582</p>
</td>
</tr>
<tr>
<td colspan="4" rowspan="5" width="405">
<p><strong>M/S MAHARASHTRA ENERGY DEVLOPMENT AGENCY</strong></p>
<p><strong>WARDHA</strong></p>
<p>&nbsp;</p>
<p><strong>PHONE NO. : EMAIL ID. :</strong></p>
<p>Project Code :2535</p>
</td>
<td colspan="3" width="342">
<p>DM NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>A000001&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date : 16/06/2021</strong></p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>L.R. No.&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date : 16/06/2021</p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>Transport :</p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>Project Type :SELF PROJECT</p>
</td>
</tr>
<tr>
<td colspan="3" width="342">
<p>Slip No.:VE-01&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Weight :1600KG</p>
</td>
</tr>
<tr>
<td colspan="2" width="245">
<p><strong>Consignee:</strong></p>
<p><strong>M/s Grampanchayat Shegaon (Kundd)</strong></p>
<p><strong>Village Shegaon (Kundd) Tq.Hinghanghat Dist. Wardha</strong></p>
</td>
<td colspan="3" width="255">
<p><strong>Beneficiary Contact :</strong></p>
<p><strong>Alternate Contact :</strong></p>
<p><strong>Email id:</strong></p>
<p><strong>Agency Contact:8484937582</strong></p>
<p><strong>Agency Email: </strong><a href="mailto:vtechservice.meda2@gmail.com"><strong>vtechservice.meda2@gmail.com</strong></a></p>
</td>
<td colspan="2" width="247">
<p><strong>Site Engineer :RAJESH BHANDARI Contact No. :7999146034</strong></p>
<p><strong>Site Manager :NABA RAJA Contact No. &nbsp;:8484937582</strong></p>
</td>
</tr>
<tr>
<td width="23">
<p><strong>Sr.</strong></p>
</td>
<td colspan="2" width="332">
<p><strong>P R O D U C T</strong></p>
</td>
<td colspan="2" width="145">
<p><strong>MODEL NO.</strong></p>
</td>
<td width="155">
<p><strong>SERIAL NO.</strong></p>
</td>
<td width="92">
<p><strong>QTY.</strong></p>
</td>
</tr>
<tr>
<td width="23">
<p>&nbsp;</p>
<p>28</p>
<p>29</p>
<p>30</p>
<p>31</p>
<p>32</p>
<p>33</p>
<p>34</p>
<p>35</p>
<p>36</p>
<p>37</p>
<p>38</p>
<p>39</p>
<p>40</p>
<p>41</p>
<p>42</p>
</td>
<td colspan="2" width="332">
<p>&nbsp;</p>
<p>SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP SOLAR SPV MODULE 340 WP TEFLON TAPE</p>
</td>
<td colspan="2" width="145">
<p>&nbsp;</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
<p>-BLANK-</p>
</td>
<td width="155">
<p><strong>TOTAL B/F </strong>ICON34036A0706052142 ICON34036A0706051023 ICON34036A0706052076 ICON34036A0706052211 ICON34036A0706052086 ICON34036A0706052145 ICON34036A0706051050 ICON34036A0706051039 ICON34036A0706052219 ICON34036A0706052210 ICON34036A0706052115 ICON34036A0706052082 ICON34036A0706052065 ICON34036A0706052069</p>
<p>_62Q0U6580</p>
</td>
<td width="92">
<p><strong>0.00</strong></p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
<p>1 NOS</p>
</td>
</tr>
<tr>
<td colspan="7" width="747">
<p>TOTAL QUANTITY :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 165</p>
<p>&nbsp;</p>
<p>Remark1 :</p>
<p>Remark2 :</p>
</td>
</tr>
<tr>
<td colspan="7" width="747">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td colspan="7" width="747">
<p>&nbsp;</p>
<p style="text-align: left; padding-left: 510px;"><strong>For VTECH ENGINEERS</strong></p>
<p style="text-align: left; padding-left: 510px;">&nbsp;</p>
<p style="text-align: left; padding-left: 510px;">&nbsp;</p>
<p style="text-align: left; padding-left: 510px;">&nbsp;</p>
<p style="text-align: left; padding-left: 510px;"><strong>Dispatch Officer</strong></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><strong>Verified By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Security Guard Authentification&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Accepted By</strong></p>
<p><strong>Store Head&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Register Entry No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Approved Authority</strong></p>
<p>&nbsp;</p>
<p>&nbsp;</p>

</td>
</tr>
<tr>

</tr>
</tbody>
</table> -->

</body>
</html>