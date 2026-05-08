<?php 
session_start();
include_once '../../config.php';
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
 $srno = 1;
  $sql = "SELECT * FROM tbl_products WHERE Status='1' ORDER BY id DESC";
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
        responsive: true
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
 $srno = 1;
  $sql = "SELECT * FROM tbl_products WHERE Status='1' ORDER BY id DESC";
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
  		//echo $sql2;
  		$row2 = getRecord($sql2);
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <input type="hidden" name="ProdId[]" class="form-control" value="<?php echo $nx['id'];?>">
              <input type="hidden" name="ProdName[]" class="form-control" value='<?php echo $nx['ProductName'];?>'>
              <input type="hidden" name="Unit[]" class="form-control" value="<?php echo $nx['Unit'];?>">
              <input type="hidden" name="Qty[]" class="form-control" value="<?php echo $row2['Qty'];?>">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $row2['Qty'];?></td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
 <?php }
?>