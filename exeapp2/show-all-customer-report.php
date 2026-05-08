<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 70%;
}

th, td {
  padding: 15px;
}
</style>
<?php 
session_start();
include_once 'config.php';
$AllCustId = $_POST['AllCustId'];
function commonMaster($id){
    global $conn;
    $sql = "SELECT * FROM tbl_common_master WHERE id='$id'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['Name'];
}
$sql7 = "SELECT tu.*,tb.Name As BranchName,ts.Name As Scheme,tc.Name As Country,ts2.Name As State,tc2.Name As City FROM tbl_users tu 
         LEFT JOIN tbl_branch tb ON tu.BranchId=tb.id 
         LEFT JOIN tbl_scheme ts ON tu.SchemeId=ts.id 
         LEFT JOIN tbl_country tc ON tu.CountryId=tc.id 
         LEFT JOIN tbl_state ts2 ON tu.StateId=ts2.id 
         LEFT JOIN tbl_city tc2 ON tu.CityId=tc2.id WHERE tu.id IN ($AllCustId) ORDER BY id DESC";
         $result7 = getList($sql7);
         foreach($result7 as $row7){
?>
<table>
<tr>
	<th style="text-align:center;" colspan="2"><?php echo strtoupper($row7["Fname"]); ?></th>
</tr>
<tr>
	<th colspan="2" style="background-color: lightgrey;">CUSTOMER DETAILS</th>
</tr>
<tr>
                                                <th>Project Type:</th>
                                                <td><?php if($row7["ProjectType"]=='1') {?> Pump Projects <?php } else { ?> Rooftop Projects <?php } ?></td>
                                            </tr>
                                            <tr>
                                                <th>Beneficiary ID:</th>
                                                <td><?php echo $row7["BeneficiaryId"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Name Of Beneficiary/Grampanchayat:</th>
                                                <td><?php echo $row7["Fname"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Branch:</th>
                                                <td><?php echo $row7["BranchName"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Gov Agency:</th>
                                                <td><?php echo $row7["Scheme"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Location:</th>
                                                <td><?php echo $row7["City"].", ".$row7["State"].", ".$row7["Country"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Taluka / Village / District:</th>
                                                <td><?php echo $row7["Taluka"]." / ".$row7["Village"]." / ".$row7["District"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Mobile No:</th>
                                                <td><?php echo $row7["Phone"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Email Id:</th>
                                                <td><?php echo $row7["EmailId"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>AC/DC:</th>
                                                <td><?php echo $row7["AcDc"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Type Of Pump:</th>
                                                <td><?php echo commonMaster($row7["Surface"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Capacity:</th>
                                                <td><?php echo commonMaster($row7["PumpCapacity"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Lattitude:</th>
                                                <td><?php echo $row7["Lattitude"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Longitude:</th>
                                                <td><?php echo $row7["Longitude"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Rooftop Plant Capacity:</th>
                                                <td><?php echo $row7["RooftopPlantCapacity"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Off Grid/ On Grid System:</th>
                                                <td><?php echo $row7["OffOnGrid"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Consumer No:</th>
                                                <td><?php echo $row7["ConsumerNo"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Sanction Load:</th>
                                                <td><?php echo $row7["SanctionLoad"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Load Extension Required (kW):</th>
                                                <td><?php echo $row7["LoadExtension"]; ?></td>
                                            </tr>

                                            <tr>
                                                <th>Type Of Source:</th>
                                                <td><?php echo commonMaster($row7["WaterSource"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Bore Diameter:</th>
                                                <td><?php echo commonMaster($row7["BoreDia"]);  ?></td>
                                            </tr>
                                            <tr>
                                                <th>Depth Of Source In Summer:</th>
                                                <td><?php echo $row7["SummerDepth"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Depth Of Source In Winter:</th>
                                                <td><?php echo $row7["WinterDepth"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Pump Head:</th>
                                                <td><?php echo commonMaster($row7["PumpHead"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>BG Number:</th>
                                                <td><?php echo $row7["BgNumber"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>BG Validity:</th>
                                                <td><?php echo $row7["BgValidity"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>BG Claim Period:</th>
                                                <td><?php echo $row7["BgClaimPeriod"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Number:</th>
                                                <td><?php echo $row7["InsuranceNumber"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Agency:</th>
                                                <td><?php echo $row7["InsuranceAgency"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Validity:</th>
                                                <td><?php echo $row7["InsuranceValidity"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Installation Vendor Name:</th>
                                                <td><?php echo $row7["InstallationVendor"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date Of Inspection:</th>
                                                <td><?php echo $row7["InspectionDate"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date Of Commissioning:</th>
                                                <td><?php echo $row7["CommissioningDate"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Customer Type:</th>
                                                <td><?php echo commonMaster($row7["CustType"]); ?></td>
                                            </tr>
                                            <?php if($row7["CustType"] == 34){?>
                                            <tr>
                                                <th>Address:</th>
                                                <td><?php echo commonMaster($row7["CompAddress"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Authorised Person Name:</th>
                                                <td><?php echo commonMaster($row7["AuthorName"]); ?></td>
                                            </tr>
                                        
                                            <tr>
                                                <th>Company Name:</th>
                                                <td><?php echo commonMaster($row7["CompName"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile No:</th>
                                                <td><?php echo commonMaster($row7["CompPhone"]); ?></td>
                                            </tr>
                                        <?php } ?>

                                        <tr>
	<th colspan="2" style="background-color: lightgrey;">PRODUCT SPECIFICATIONS</th>
</tr>
</table>



<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:70%">
        <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              
               <th>Unit</th>
               <th>Qty</th>
             
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_cust_product_specification WHERE CustId='".$row7["id"]."' ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
            
             <td><?php echo $nx['ProdName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $nx['Qty'];?></td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
<br><br>
<?php } ?>