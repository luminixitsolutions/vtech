<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <style>
        #dwnldBtn{
            background-color: green; 
            color: #fff; 
            padding: 10px; 
            border: none;
            border-radius: 5px; 
            margin: 2rem 0;
            cursor: pointer;
        }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<?php
include('../config.php');
$limit = 10;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  
  
$sql = "SELECT tu.*,tcm.Name As WaterSourceName,tcm2.Name As PumpCapacityName,tcm3.Name As PumpHeadName FROM tbl_users tu 
                   
                    LEFT JOIN tbl_common_master tcm ON tu.WaterSource=tcm.id 
                    LEFT JOIN tbl_common_master tcm2 ON tu.PumpCapacity=tcm2.id
                    LEFT JOIN tbl_common_master tcm3 ON tu.PumpHead=tcm3.id WHERE tu.Roll=5 AND tu.SurveyDetails=1 LIMIT $start_from, $limit";  
$rs_result = mysqli_query($conn, $sql);  
?>

<button id="dwnldBtn" type="button">
                Download Excel Sheet
        </button>
        
        
    
    </center>
    <script>
        $(document).ready(function () {
         
                        $('#dwnldBtn').on('click', function () {
                $("#dataTable").table2excel({
                    filename: "employeeData.xls"
                });
            });
        });
    </script>
<table class="table table-bordered table-hover" id="table_id">  
<thead>  
<tr>
               <th>Sr No</th>
                <th>Beneficiary ID</th> 
                <th>Beneficiary Name</th>
               
                <th>Mobile</th>
               
                <th>Water Source</th>
                <th>Land Address</th>
                <th>District</th>
                <th>Taluka</th>
                <th>Village</th>
                <th>Capacity</th>
                <th>Source</th>
                <th>Total Depth in mtr </th>
                <th>Summer level in mtr  </th>
                <th>Pump Head (in Mtr) </th>
                <?php 
                $WaterSource = $_POST['WaterSource'];
                $BoreDia = $_POST['BoreDia'];
                $PumpHead = $_POST['PumpHead'];
                $sql2 = "SELECT * FROM tbl_rooftop_products WHERE ProdSpec=1";
                
        $row2 = getList($sql2);
        foreach($row2 as $result2){
        ?>
                <th><?php echo $result2['ProductName'];?></th>
                <?php } ?>
                                        </tr>
</thead>  
<tbody>  
<?php  
while ($result = mysqli_fetch_array($rs_result)) {  
?>  
             <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $result['BeneficiaryId']; ?></td> 
               <td><?php echo $result['Fname']; ?></td>
               <td><?php echo $result['Phone']; ?></td>
               <td><?php echo $result['WaterSourceName']; ?></td>
              <td><?php echo $result['Address']; ?></td>
              <td><?php echo $result['District']; ?></td>
              <td><?php echo $result['Taluka']; ?></td>
              <td><?php echo $result['Village']; ?></td>
              <td><?php echo $result['PumpCapacityName']; ?></td>
             <td><?php echo $result['WaterSourceName']; ?></td>
             <td><?php echo $result['TotalDepth']; ?></td>
             <td><?php echo $result['SummerDepth']; ?></td>
             <td><?php echo $result['TelPumpHead']; ?></td>
              <?php 
              $sql2 = "SELECT id FROM tbl_rooftop_products WHERE ProdSpec=1";
                
        $row2 = getList($sql2);
        foreach($row2 as $result2){
            $sql4 = "SELECT * FROM tbl_cust_product_specification WHERE CustId='".$result['id']."' AND ProdId='".$result2['id']."'";
            $rncnt4 = getRow($sql4);
            $row4 = getRecord($sql4);
            if($rncnt4 > 0){
                $Qty = $row4['Qty'];
            }
            else{
                $Qty = 0;
            }
        ?>
                <td><?php echo $Qty;?></td>
                <?php } ?>
              
            </tr>
                                               
<?php } ?>  
</tbody>  
</table> 
