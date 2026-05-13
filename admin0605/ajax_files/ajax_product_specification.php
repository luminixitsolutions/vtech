<?php 
session_start();
include_once '../config.php';
if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
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
          $AcDc = $_POST['AcDc']; 
    $Surface = $_POST['Surface'];
    $PumpCapacity = $_POST['PumpCapacity'];
    $WaterSource = $_POST['WaterSource'];
    $BoreDia = $_POST['BoreDia'];
    $PumpHead = $_POST['PumpHead'];
    $AgencyId = $_POST['AgencyId'];
    $PumpOutletSize = $_POST['PumpOutletSize'];
 $srno = 1;
  $sql = "SELECT * FROM tbl_products WHERE Status='1' AND Roll!=1 AND ProdSpec=1 ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  		$sql2 = "SELECT * FROM tbl_product_specification WHERE ProdId='".$nx['id']."'";
  		if($AcDc!=''){
  		   $sql2.=" AND AcDc='$AcDc'";
  		}
  		if($Surface!=''){
  			$sql2.=" AND Surface='$Surface'";
  		}
  		if($PumpCapacity!=''){
  			$sql2.=" AND PumpCapacity='$PumpCapacity'";
  		}
  		if($WaterSource!=''){
  			$sql2.=" AND WaterSource='$WaterSource'";
  		}
  		if($BoreDia!=''){
  			$sql2.=" AND BoreDia='$BoreDia'";
  		}
  		if($PumpHead!=''){
  			$sql2.=" AND PumpHead='$PumpHead'";
  		}
      if($AgencyId!=''){
        $sql2.=" AND AgencyId='$AgencyId'";
      }
      if($PumpOutletSize!=''){
        $sql2.=" AND PumpOutletSize='$PumpOutletSize'";
      }
      
  		$row2 = getRecord($sql2);
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <input type="hidden" name="ProdId[]" class="form-control" value="<?php echo $nx['id'];?>">
              <input type="hidden" name="ProdName[]" class="form-control" value='<?php echo $nx['ProductName'];?>'>
              <input type="hidden" name="Unit[]" class="form-control" value="<?php echo $nx['Unit'];?>">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><input type="number" name="Qty[]" class="form-control" value="<?php echo $row2['Qty'];?>"></td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>

    <button type="submit" name="submit" class="btn btn-primary btn-finish" style="width: 100px;">Submit</button>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
        responsive: true,
        "pageLength":1000
      });
      });
    </script>
 <?php } 


 if($_POST['action']=='view2'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
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
          $AcDc = $_POST['AcDc']; 
    $Surface = $_POST['Surface'];
    $PumpCapacity = $_POST['PumpCapacity'];
    $WaterSource = $_POST['WaterSource'];
    $BoreDia = $_POST['BoreDia'];
    $PumpHead = $_POST['PumpHead'];
        $AgencyId = $_POST['AgencyId'];
    $PumpOutletSize = $_POST['PumpOutletSize'];
 $srno = 1;
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty FROM tbl_product_specification tps 
         INNER JOIN tbl_products tp ON tps.ProdId=tp.id WHERE tp.Roll!=1 AND tps.Qty>0 AND tp.ProdSpec=1";
        if($AcDc!=''){
         $sql.=" AND tps.AcDc='$AcDc'";
      }
      if($Surface!=''){
        $sql.=" AND tps.Surface='$Surface'";
      }
      if($PumpCapacity!=''){
        $sql.=" AND tps.PumpCapacity='$PumpCapacity'";
      }
      if($WaterSource!=''){
        $sql.=" AND tps.WaterSource='$WaterSource'";
      }
      if($BoreDia!=''){
        $sql.=" AND tps.BoreDia='$BoreDia'";
      }
      if($PumpHead!=''){
        $sql.=" AND tps.PumpHead='$PumpHead'";
      }
      if($AgencyId!=''){
        $sql.=" AND tps.AgencyId='$AgencyId'";
      }
      if($PumpOutletSize!=''){
        $sql.=" AND tps.PumpOutletSize='$PumpOutletSize'";
      }
      $sql.=" GROUP BY tps.ProdId ORDER BY tp.ProductName";
      //echo $sql;
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  	
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <input type="hidden" name="ProdId[]" class="form-control" value="<?php echo $nx['id'];?>">
             <input type="hidden" name="ProdName[]" class="form-control" value='<?php echo $nx['ProductName'];?>'>
             <input type="hidden" name="Unit[]" class="form-control" value="<?php echo $nx['Unit'];?>">
             <input type="hidden" name="Qty[]" class="form-control" value="<?php echo $nx['Qty'];?>">
             <input type="hidden" name="SpecType[]" class="form-control" value="0">
              <input type="hidden" name="Structure[]" class="form-control" value="0">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $nx['Qty'];?></td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
 <?php } 

  if($_POST['action']=='calSummerPumpHead'){
      $TelSummerWaterLevel= $_POST['TelSummerWaterLevel'];
      $sql = "SELECT * FROM tbl_common_master WHERE Roll=14 AND RangeTo>='$TelSummerWaterLevel' AND RangeFrom<='$TelSummerWaterLevel' AND Level=1 LIMIT 1";
      $rncnt = getRow($sql);
      if($rncnt > 0){
      $row = getRecord($sql);
      $sql2 = "SELECT * FROM tbl_common_master WHERE id='".$row['PumpHeadId']."'";
      $row2 = getRecord($sql2);
      echo $row2['Name'];
      }
      else{
        echo "NA";
      }
  }

  if($_POST['action']=='calDepthPumpHead'){
      $TelTotalDepth= $_POST['TelTotalDepth'];
      $sql = "SELECT * FROM tbl_common_master WHERE Roll=14 AND RangeTo>='$TelTotalDepth' AND RangeFrom<='$TelTotalDepth' AND Level=2 LIMIT 1";
      $rncnt = getRow($sql);
      if($rncnt > 0){
      $row = getRecord($sql);
      $sql2 = "SELECT * FROM tbl_common_master WHERE id='".$row['PumpHeadId']."'";
      $row2 = getRecord($sql2);
      echo $row2['Name'];
    }
    else{
      echo "NA";
    }
  }
?>