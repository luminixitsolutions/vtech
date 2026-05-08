<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
 if($_POST['action']=='bos-tentative'){?>
<table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
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
           $i=1;
           $WaterSource = $_POST['WaterSource'];
            $BoreDia = $_POST['BoreDia'];
            $PumpHead = $_POST['PumpHead'];
            $sql = "SELECT tu.*,tcm.Name As WaterSourceName,tcm2.Name As PumpCapacityName,tcm3.Name As PumpHeadName FROM tbl_users tu 
                   
                    LEFT JOIN tbl_common_master tcm ON tu.WaterSource=tcm.id 
                    LEFT JOIN tbl_common_master tcm2 ON tu.PumpCapacity=tcm2.id
                    LEFT JOIN tbl_common_master tcm3 ON tu.PumpHead=tcm3.id WHERE tu.Roll=5 AND tu.SurveyDetails=1";
              if($WaterSource!=''){
  			$sql.=" AND tu.WaterSource='$WaterSource'";
  		}
  		if($BoreDia!=''){
  			$sql.=" AND tu.BoreDia='$BoreDia'";
  		}
  		if($PumpHead!=''){
  			$sql.=" AND tu.PumpHead='$PumpHead'";
  		}
  		
  		 if($_POST['StateId']){
                $StateId = $_POST['StateId'];
                if($StateId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.StateId='$StateId'";
                }
            }
            
            if($_POST['Taluka']){
                $Taluka = $_POST['Taluka'];
                if($Taluka == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.Taluka='$Taluka'";
                }
            }
            
            if($_POST['District']){
                $District = $_POST['District'];
                if($District == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.District='$District'";
                }
            }

            $MinLimit = $_REQUEST['MinLimit'];
            $MaxLimit = $_REQUEST['MaxLimit'];
            $sql.= " LIMIT $MinLimit,$MaxLimit";
           

            
  		//echo $sql;
  		$row = getList($sql);
  		foreach($row as $result){?>      
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
             <td><?php echo $result['TelTotalDepth']; ?></td>
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
         <?php $i++;} ?>
        </tbody>
    </table>

 	 <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
      	"pageLength":10000,
         "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
      });
      });
    </script>
 <?php } 


 if($_POST['action']=='structure-tentative'){?>
<table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
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
                $sql2 = "SELECT * FROM tbl_rooftop_products WHERE ProdSpec=2";
                
  		$row2 = getList($sql2);
  		foreach($row2 as $result2){
  		?>
                <th><?php echo $result2['ProductName'];?></th>
                <?php } ?>
                
            </tr>
        </thead>
        <tbody>
           <?php 
           $i=1;
           $WaterSource = $_POST['WaterSource'];
            $BoreDia = $_POST['BoreDia'];
            $PumpHead = $_POST['PumpHead'];
            $sql = "SELECT tu.*,tcm.Name As WaterSourceName,tcm2.Name As PumpCapacityName,tcm3.Name As PumpHeadName FROM tbl_users tu 
                   
                    LEFT JOIN tbl_common_master tcm ON tu.WaterSource=tcm.id 
                    LEFT JOIN tbl_common_master tcm2 ON tu.PumpCapacity=tcm2.id
                    LEFT JOIN tbl_common_master tcm3 ON tu.PumpHead=tcm3.id WHERE tu.Roll=5 AND tu.SurveyDetails=1";
              if($WaterSource!=''){
  			$sql.=" AND tu.WaterSource='$WaterSource'";
  		}
  		if($BoreDia!=''){
  			$sql.=" AND tu.BoreDia='$BoreDia'";
  		}
  		if($PumpHead!=''){
  			$sql.=" AND tu.PumpHead='$PumpHead'";
  		}
  		
  		 if($_POST['StateId']){
                $StateId = $_POST['StateId'];
                if($StateId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.StateId='$StateId'";
                }
            }
            
            if($_POST['Taluka']){
                $Taluka = $_POST['Taluka'];
                if($Taluka == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.Taluka='$Taluka'";
                }
            }
            
            if($_POST['District']){
                $District = $_POST['District'];
                if($District == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.District='$District'";
                }
            }

            $MinLimit = $_REQUEST['MinLimit'];
            $MaxLimit = $_REQUEST['MaxLimit'];
            $sql.= " LIMIT $MinLimit,$MaxLimit";
           

            
  		//echo $sql;
  		$row = getList($sql);
  		foreach($row as $result){?>      
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
             <td><?php echo $result['TelTotalDepth']; ?></td>
             <td><?php echo $result['SummerDepth']; ?></td>
             <td><?php echo $result['TelPumpHead']; ?></td>
              <?php 
              $sql2 = "SELECT id FROM tbl_rooftop_products WHERE ProdSpec=2";
                
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
         <?php $i++;} ?>
        </tbody>
    </table>

 	 <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
      	"pageLength":10000,
         "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
      });
      });
    </script>
 <?php } 

  if($_POST['action']=='bos-final'){?>
<table id="example" class="table table-striped table-bordered" style="width:100%">
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
           $i=1;
           $WaterSource = $_POST['WaterSource'];
            $BoreDia = $_POST['BoreDia'];
            $PumpHead = $_POST['PumpHead'];
            $sql = "SELECT tu.*,tcm.Name As WaterSourceName,tcm2.Name As PumpCapacityName,tcm3.Name As PumpHeadName FROM tbl_users tu 
                   
                    LEFT JOIN tbl_common_master tcm ON tu.WaterSource=tcm.id 
                    LEFT JOIN tbl_common_master tcm2 ON tu.PumpCapacity=tcm2.id
                    LEFT JOIN tbl_common_master tcm3 ON tu.PumpHead=tcm3.id WHERE tu.Roll=5 AND tu.SurveyDetails=1 AND tu.FieldSurveyDetails=1";
              if($WaterSource!=''){
  			$sql.=" AND tu.WaterSource='$WaterSource'";
  		}
  		if($BoreDia!=''){
  			$sql.=" AND tu.BoreDia='$BoreDia'";
  		}
  		if($PumpHead!=''){
  			$sql.=" AND tu.PumpHead='$PumpHead'";
  		}
  		
  		 if($_POST['StateId']){
                $StateId = $_POST['StateId'];
                if($StateId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.StateId='$StateId'";
                }
            }
            
            if($_POST['Taluka']){
                $Taluka = $_POST['Taluka'];
                if($Taluka == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.Taluka='$Taluka'";
                }
            }
            
            if($_POST['District']){
                $District = $_POST['District'];
                if($District == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.District='$District'";
                }
            }

            $MinLimit = $_REQUEST['MinLimit'];
            $MaxLimit = $_REQUEST['MaxLimit'];
            $sql.= " LIMIT $MinLimit,$MaxLimit";
           

            
  		//echo $sql;
  		$row = getList($sql);
  		foreach($row as $result){?>      
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
             <td><?php echo $result['FieldTotalDepth']; ?></td>
             <td><?php echo $result['SummerDepth']; ?></td>
             <td><?php echo $result['FieldPumpHead']; ?></td>
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
         <?php $i++;} ?>
        </tbody>
    </table>

 	 <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
      	"pageLength":10000,
         "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
      });
      });
    </script>
 <?php } 

  if($_POST['action']=='structure-final'){?>
<table id="example" class="table table-striped table-bordered" style="width:100%">
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
                $sql2 = "SELECT * FROM tbl_rooftop_products WHERE ProdSpec=2";
                
  		$row2 = getList($sql2);
  		foreach($row2 as $result2){
  		?>
                <th><?php echo $result2['ProductName'];?></th>
                <?php } ?>
                
            </tr>
        </thead>
        <tbody>
           <?php 
           $i=1;
           $WaterSource = $_POST['WaterSource'];
            $BoreDia = $_POST['BoreDia'];
            $PumpHead = $_POST['PumpHead'];
            $sql = "SELECT tu.*,tcm.Name As WaterSourceName,tcm2.Name As PumpCapacityName,tcm3.Name As PumpHeadName FROM tbl_users tu 
                   
                    LEFT JOIN tbl_common_master tcm ON tu.WaterSource=tcm.id 
                    LEFT JOIN tbl_common_master tcm2 ON tu.PumpCapacity=tcm2.id
                    LEFT JOIN tbl_common_master tcm3 ON tu.PumpHead=tcm3.id WHERE tu.Roll=5 AND tu.SurveyDetails=1 AND tu.FieldSurveyDetails=1";
              if($WaterSource!=''){
  			$sql.=" AND tu.WaterSource='$WaterSource'";
  		}
  		if($BoreDia!=''){
  			$sql.=" AND tu.BoreDia='$BoreDia'";
  		}
  		if($PumpHead!=''){
  			$sql.=" AND tu.PumpHead='$PumpHead'";
  		}
  		
  		 if($_POST['StateId']){
                $StateId = $_POST['StateId'];
                if($StateId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.StateId='$StateId'";
                }
            }
            
            if($_POST['Taluka']){
                $Taluka = $_POST['Taluka'];
                if($Taluka == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.Taluka='$Taluka'";
                }
            }
            
            if($_POST['District']){
                $District = $_POST['District'];
                if($District == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.District='$District'";
                }
            }

            $MinLimit = $_REQUEST['MinLimit'];
            $MaxLimit = $_REQUEST['MaxLimit'];
            $sql.= " LIMIT $MinLimit,$MaxLimit";
           

            
  		//echo $sql;
  		$row = getList($sql);
  		foreach($row as $result){?>      
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
             <td><?php echo $result['FieldTotalDepth']; ?></td>
             <td><?php echo $result['SummerDepth']; ?></td>
             <td><?php echo $result['FieldPumpHead']; ?></td>
              <?php 
              $sql2 = "SELECT id FROM tbl_rooftop_products WHERE ProdSpec=2";
                
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
         <?php $i++;} ?>
        </tbody>
    </table>

 	 <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
      	"pageLength":10000,
         "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
      });
      });
    </script>
 <?php } 
?>