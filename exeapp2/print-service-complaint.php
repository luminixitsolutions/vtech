<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "View-Sell";
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
$sql = "SELECT * FROM tbl_service_complaint WHERE id='$id'";
$row = getRecord($sql);
?>
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 150px;">
<p><strong>DISTRICT</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['District'];?></p>
</td>
<td style="width: 130px;">
<p><strong>TALUKA</strong></p>
</td>
<td style="width: 108px;">
<p><?php echo $row['Taluka'];?></p>
</td>
<td style="width: 140px;">
<p><strong>VILLAGE</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p><?php echo $row['Village'];?></p>
</td>
</tr>
<tr>
<td style="width: 908px;" colspan="7">
<p style="text-align: center;"><strong>CUSTOMER DETAILS</strong></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>FARMER NAME</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p><?php echo $row['CustName'];?></p>
</td>
<td style="width: 128px;">
<p><strong>Cont. No</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['CellNo'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>ADDRESS</strong></p>
</td>
<td style="width: 631px;" colspan="5">
<p><?php echo $row['Address'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Installation Date</strong></p>
</td>
<td style="width: 238px;" colspan="2">
<p><strong><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InstallationDate'])));?></strong></p>
</td>
<td style="width: 268px;" colspan="2">
<p><strong>Commissioning Date</strong></p>
</td>
<td style="width: 125px;">
<p><strong><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ComissioningDate'])));?></strong></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>Source</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['WaterSource'];?></p>
</td>
<td style="width: 130px;">
<p><strong>Depth</strong></p>
</td>
<td style="width: 108px;">
<p><?php echo $row['Depth'];?></p>
</td>
<td style="width: 268px;" colspan="2" rowspan="2">
<p>&nbsp;</p>
<p><strong>Last Date of Inspection</strong></p>
</td>
<td style="width: 125px;" rowspan="2">
<p>&nbsp;</p>
<p><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InspectionDate'])));?></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>system</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['ServiceSystem'];?></p>
</td>
<td style="width: 130px;">
<p><strong>AC/DC</strong></p>
</td>
<td style="width: 108px;">
<p><?php echo $row['AcDc'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Previous VFD no</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p></p>
</td>
<td style="width: 128px;">
<p><strong>Recent VFD No.</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['RecentVfdNo'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Previous Motor no.</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p></p>
</td>
<td style="width: 128px;">
<p><strong>Recent motor No.</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['RecentMotorNo'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Previous pump No.</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p></p>
</td>
<td style="width: 128px;">
<p><strong>Recent pump No.</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['RecentPumpNo'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Pump Make</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p><?php echo $row['PumpMake'];?></p>
</td>
<td style="width: 128px;">
<p><strong>MOTOR Make</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['MotorMake'];?></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>SR_NO.</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p><strong>Panel Serial No.</strong></p>
</td>
<td style="width: 140px;">
<p><strong>SR_NO.</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p><strong>Panel Serial No.</strong></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>1</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>9</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>2</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>10</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>3</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>11</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>4</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>12</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>5</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>13</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>6</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>14</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>7</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>15</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>8</strong></p>
</td>
<td style="width: 365px;" colspan="3">
<p>&nbsp;</p>
</td>
<td style="width: 140px;">
<p><strong>16</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Problem</strong></p>
</td>
<td style="width: 631px;" colspan="5">
<p><?php echo $row['Problem'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Remark/Requirement</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p><?php echo $row['Remark'];?></p>
</td>
<td style="width: 128px;">
<p><strong>PHOTO/VIDEOS</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['Photos'];?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>INSPECTION DATE</strong></p>
</td>
<td style="width: 238px;" colspan="2">
<p><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InspectionDate'])));?></p>
</td>
<td style="width: 140px;">
<p><strong>INSPECT BY</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p><strong><?php echo $row['InspectionBy'];?></strong></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>Last Update</strong></p>
</td>
<td style="width: 257px;" colspan="2">
<p><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['LastDate'])));?> </p>
</td>
<td style="width: 248px;" colspan="2">
<p><strong>System ok or not</strong></p>
</td>
<td style="width: 128px;">
<p><?php echo $row['SystemStatus'];?></p>
</td>
<td style="width: 125px;">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>Extra Motor</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['Extra'];?></p>
</td>
<td style="width: 130px;">
<p><strong>Extra Pump</strong></p>
</td>
<td style="width: 248px;" colspan="2">
<p><?php echo $row['ExtraPump'];?></p>
</td>
<td style="width: 128px;">
<p><strong>Extra VFD</strong></p>
</td>
<td style="width: 125px;">
<p><?php echo $row['ExtraVfd'];?></p>
</td>
</tr>
<tr>
<td style="width: 407px;" colspan="3">
<p><strong>Name of Insurance Company</strong></p>
</td>
<td style="width: 501px;" colspan="4">
<p><?php echo $row['InsuranceCompany'];?></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>insurance policy number</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['PolicyNo'];?></p>
</td>
<td style="width: 130px;">
<p><strong>insurance policy period</strong></p>
</td>
<td style="width: 108px;">
<p><?php echo $row['PolicyPeriod'];?></p>
</td>
<td style="width: 140px;">
<p>&nbsp;</p>
<p><strong>if any claim done</strong></p>
</td>
<td style="width: 128px;">
<p>&nbsp;</p>
</td>
<td style="width: 125px;">
<p>&nbsp;</p>
<p><?php echo $row['ClainDone'];?></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>&nbsp;</p>
<p><strong>RMS Updated Upto</strong></p>
</td>
<td style="width: 127px;">
<p>&nbsp;</p>
<p><?php echo $row['Rms'];?></p>
</td>
<td style="width: 130px;">
<p>&nbsp;</p>
<p><strong>VFD problem</strong></p>
</td>
<td style="width: 108px;">
<p>&nbsp;</p>
<p><?php echo $row['VfdProblem'];?></p>
</td>
<td style="width: 268px;" colspan="2">
<p><strong>if plate damage insurance claim or not</strong></p>
</td>
<td style="width: 125px;">
<p>&nbsp;</p>
<p><?php echo $row['PlateDamageInsurance'];?></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>Pump problem</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['PumpProblem'];?></p>
</td>
<td style="width: 130px;">
<p><strong>Motor Problem</strong></p>
</td>
<td style="width: 108px;">
<p><?php echo $row['MotorProblem'];?></p>
</td>
<td style="width: 140px;">
<p><strong>Photo received</strong></p>
</td>
<td style="width: 128px;">
<p><?php echo $row['PhotoReceived'];?></p>
</td>
<td style="width: 125px;">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>Video received</strong></p>
</td>
<td style="width: 127px;">
<p><?php echo $row['VideoReceived'];?></p>
</td>
<td style="width: 130px;">
<p><strong>Letter received</strong></p>
</td>
<td style="width: 108px;">
<p><?php echo $row['LetterReceived'];?></p>
</td>
<td style="width: 140px;">
<p><strong>Last calling update</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
<p><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['LastCallUpdate'])));?></p>
</td>
</tr>
<tr>
<td style="width: 277px;" colspan="2">
<p><strong>Recent Problem</strong> <?php echo $row['RecentProblem'];?></p>
</td>

<td style="width: 631px;" colspan="5">
<p><strong>System working properly</strong> <?php echo $row['SystemWorking'];?></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p><strong>SR_NO.</strong></p>
</td>
<td style="width: 127px;">
<p><strong>DATE</strong></p>
</td>
<td style="width: 378px;" colspan="3">
<p><strong>SERVICE UPDATE</strong></p>
</td>
<td style="width: 253px;" colspan="2">
<p><strong>SERVICE PERSON</strong></p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>1</p>
</td>
<td style="width: 127px;">
<p>31.03.2021</p>
</td>
<td style="width: 378px;" colspan="3">
<p>RMS Data Email to MEDA</p>
</td>
<td style="width: 253px;" colspan="2">
<p>By Apurva mam</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>2</p>
</td>
<td style="width: 127px;">
<p>18.10.2021</p>
</td>
<td style="width: 378px;" colspan="3">
<p>System ok</p>
</td>
<td style="width: 253px;" colspan="2">
<p>Calling Monika</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>3</p>
</td>
<td style="width: 127px;">
<p>19.10.2021</p>
</td>
<td style="width: 378px;" colspan="3">
<p>system off(4 solar panel damage motor on off)</p>
</td>
<td style="width: 253px;" colspan="2">
<p>Calling Monika</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>4</p>
</td>
<td style="width: 127px;">
<p>24.03.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>System off(Motor &amp; Pump Problem)</p>
</td>
<td style="width: 253px;" colspan="2">
<p>Call received Kalyani</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>5</p>
</td>
<td style="width: 127px;">
<p>21.04.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>System off(Motor &amp; Pump Problem)</p>
</td>
<td style="width: 253px;" colspan="2">
<p>Vilas Nikose</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>6</p>
</td>
<td style="width: 127px;">
<p>25.04.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>Motor &amp; Pump send for repairing at oswal service centre</p>
</td>
<td style="width: 253px;" colspan="2">
<p>Kalyani</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>&nbsp;</p>
<p>7</p>
</td>
<td style="width: 127px;">
<p>&nbsp;</p>
<p>05.05.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>&nbsp;</p>
<p>Repaired Motor &amp; Pump received at office Sr.No-1905327</p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
<p>Kalyani</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>&nbsp;</p>
</td>
<td style="width: 127px;">
<p>&nbsp;</p>
<p>06.05.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>Maintenance Letter Ref.: VE/HO/2021-22/ Date: 06/05/2022</p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
<p>By Apurva mam</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>&nbsp;</p>
<p>8</p>
</td>
<td style="width: 127px;">
<p>&nbsp;</p>
<p>07.06.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>System ok (Repaired Motor &amp; Pump installed Sr.No- 1905327)</p>
</td>
<td style="width: 253px;" colspan="2">
<p>&nbsp;</p>
<p>Chandrashekhar</p>
</td>
</tr>
<tr>
<td style="width: 150px;">
<p>9</p>
</td>
<td style="width: 127px;">
<p>08.06.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>Satisfaction Letter send-VE/HO/2022-23/76 Date: 08.06.2022</p>
</td>
<td style="width: 253px;" colspan="2">
<p>By Apurva mam</p>
</td>
</tr>

<tr>
<td style="width: 151.5px;">
<p>&nbsp;</p>
<p>10</p>
</td>
<td style="width: 128.283px;">
<p>&nbsp;</p>
<p>22.06.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>Complaint received from The Divisional Consumer Disputes Redressal commission िज.का.अ./अ. सौ.कृ .यो-२/न&egrave;ती Đ.</p>
<p>३०८ २०२२ २३ १०७ िज २२ ०६ २०२२</p>
</td>
<td style="width: 255.5px;" colspan="2">
<p>&nbsp;</p>
<p>By Farmer</p>
</td>
</tr>
<tr>
<td style="width: 151.5px;">
<p>&nbsp;</p>
<p>10</p>
</td>
<td style="width: 128.283px;">
<p>&nbsp;</p>
<p>23.06.2023</p>
</td>
<td style="width: 378px;" colspan="3">
<p>Regarding the complaint received from the beneficiary Mr. Bhimrao Kakad</p>
<p>Ref.: VE/HO/2022-23/94 Dated 20.06.2022</p>
</td>
<td style="width: 255.5px;" colspan="2">
<p>&nbsp;</p>
<p>By Apurva mam</p>
</td>
</tr>
<tr>
<td style="width: 151.5px;">
<p>11</p>
</td>
<td style="width: 128.283px;">
<p>28.06.2022</p>
</td>
<td style="width: 378px;" colspan="3">
<p>All Letter Merge &amp; sent to MEDA VE/HO/2022-23/99 Date: 28.06.2022</p>
</td>
<td style="width: 255.5px;" colspan="2">
<p>By Apurva mam</p>
</td>
</tr>
<tr>
<td style="width: 151.5px;">
<p>12</p>
</td>
<td style="width: 128.283px;">
<p>26.05.2023</p>
</td>
<td  style="width: 378px;" colspan="3">
<p>Monthly Maintenance Done System working properly</p>
</td>
<td style="width: 255.5px;" colspan="2">
<p>Rahul Mehetre</p>
</td>
</tr>
<tr>
<td style="width: 151.5px;">
<p>13</p>
</td>
<td style="width: 128.283px;">
<p>&nbsp;</p>
</td>
<td  style="width: 378px;" colspan="3">
<p>Satisfaction Letter</p>
<p>Ref.: VE/HO/2023-24/95 Dated- 31.05.2023</p>
</td>
<td style="width: 255.5px;" colspan="2">
<p>By Apurva mam</p>
</td>
</tr>
</tbody>
</table>

</body>
</html>

