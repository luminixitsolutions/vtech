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
    $ModuleWatt = $_POST['ModuleWatt'];
    $ModuleQty = $_POST['ModuleQty'];
    $Structure = $_POST['Structure'];
    $ModuleMake = $_POST['ModuleMake'];
    $StructureMake = $_POST['StructureMake'];
    $AgencyId = $_POST['AgencyId'];
    $SchemeId = $_POST['SchemeId'];
 $srno = 1;
  $sql = "SELECT * FROM tbl_rooftop_products WHERE Status='1' AND Roll!=1 AND ProdSpec=2 ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  		$sql2 = "SELECT * FROM tbl_rooftop_struct_product_specification WHERE ProdId='".$nx['id']."'";
  		if($AcDc!=''){
  		   $sql2.=" AND AcDc='$AcDc'";
  		}
  		if($Surface!=''){
  			$sql2.=" AND Surface='$Surface'";
  		}
  		if($PumpCapacity!=''){
  			$sql2.=" AND PumpCapacity='$PumpCapacity'";
  		}
  		if($ModuleWatt!=''){
  			$sql2.=" AND ModuleWatt='$ModuleWatt'";
  		}
  		if($ModuleQty!=''){
  			$sql2.=" AND ModuleQty='$ModuleQty'";
  		}
  		if($Structure!=''){
  			$sql2.=" AND Structure='$Structure'";
  		}
      if($ModuleMake!=''){
        $sql2.=" AND ModuleMake='$ModuleMake'";
      }
      if($StructureMake!=''){
        $sql2.=" AND StructureMake='$StructureMake'";
      }
      if($AgencyId!=''){
        $sql2.=" AND AgencyId='$AgencyId'";
      }
      if($SchemeId!=''){
        $sql2.=" AND SchemeId='$SchemeId'";
      }
  		$row2 = getRecord($sql2);
  		if($row2['Qty'] > 0){
  		    $Qty = $row2['Qty'];
  		}
  		else{
  		    $Qty = 0;
  		}
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <input type="hidden" name="ProdId[]" class="form-control" value="<?php echo $nx['id'];?>">
              <input type="hidden" name="ProdName[]" class="form-control" value='<?php echo $nx['ProductName'];?>'>
              <input type="hidden" name="Unit[]" class="form-control" value="<?php echo $nx['Unit'];?>">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><input type="number" name="Qty[]" class="form-control" value="<?php echo $Qty;?>"></td>
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
<table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
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
     $ModuleWatt = $_POST['ModuleWatt'];
    $ModuleQty = $_POST['ModuleQty'];
    $Structure1 = $_POST['Structure1'];
    $Structure2 = $_POST['Structure2'];
    $Structure3 = $_POST['Structure3'];
    $ModuleMake = $_POST['ModuleMake'];
    $StructureMake = $_POST['StructureMake'];
    $AgencyId = $_POST['AgencyId'];
    $SchemeId = $_POST['SchemeId'];
 $srno = 1;
 if($Structure1!=''){
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty,tps.Structure FROM tbl_rooftop_struct_product_specification tps 
         INNER JOIN tbl_rooftop_products tp ON tps.ProdId=tp.id WHERE tp.Roll!=1 AND tps.Qty>0 AND tp.ProdSpec=2";
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
      /*if($Structure1!='' || $Structure2!='' || $Structure3!=''){
        $sql.=" AND (tps.Structure='$Structure1' OR tps.Structure='$Structure2' OR tps.Structure='$Structure3')";
      }*/
      if($Structure1!=''){
        $sql.=" AND tps.Structure='$Structure1'";
      }
      if($ModuleMake!=''){
        $sql.=" AND tps.ModuleMake='$ModuleMake'";
      }
      if($StructureMake!=''){
        $sql.=" AND tps.StructureMake='$StructureMake'";
      }
      if($AgencyId!=''){
        $sql.=" AND tps.AgencyId='$AgencyId'";
      }
      if($SchemeId!=''){
        $sql.=" AND tps.SchemeId='$SchemeId'";
      }

      $sql.=" ORDER BY tp.ProductName";
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
              <input type="hidden" name="SpecType[]" class="form-control" value="1">
              <input type="hidden" name="Structure[]" class="form-control" value="<?php echo $nx['Structure'];?>">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $nx['Qty'];?></td>
            </tr>
             <?php $srno++;} } ?>

<?php
$srno2 = $srno;
  if($Structure2!=''){
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty,tps.Structure FROM tbl_rooftop_struct_product_specification tps 
         INNER JOIN tbl_rooftop_products tp ON tps.ProdId=tp.id WHERE tp.Roll!=1 AND tps.Qty>0 AND tp.ProdSpec=2";
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
      if($Structure2!=''){
        $sql.=" AND tps.Structure='$Structure2'";
      }
      if($ModuleMake!=''){
        $sql.=" AND tps.ModuleMake='$ModuleMake'";
      }
      if($StructureMake!=''){
        $sql.=" AND tps.StructureMake='$StructureMake'";
      }
      if($AgencyId!=''){
        $sql.=" AND tps.AgencyId='$AgencyId'";
      }
      if($SchemeId!=''){
        $sql.=" AND tps.SchemeId='$SchemeId'";
      }

      $sql.=" ORDER BY tp.ProductName";
      //echo $sql;
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  ?>
           <tr>
             <td><?php echo $srno2; ?></td>
             <input type="hidden" name="ProdId[]" class="form-control" value="<?php echo $nx['id'];?>">
              <input type="hidden" name="ProdName[]" class="form-control" value='<?php echo $nx['ProductName'];?>'>
              <input type="hidden" name="Unit[]" class="form-control" value="<?php echo $nx['Unit'];?>">
              <input type="hidden" name="Qty[]" class="form-control" value="<?php echo $nx['Qty'];?>">
              <input type="hidden" name="SpecType[]" class="form-control" value="1">
              <input type="hidden" name="Structure[]" class="form-control" value="<?php echo $nx['Structure'];?>">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $nx['Qty'];?></td>
            </tr>
             <?php $srno2++;} } ?>

             <?php
$srno3 = $srno2;
  if($Structure3!=''){
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty,tps.Structure FROM tbl_rooftop_struct_product_specification tps 
         INNER JOIN tbl_rooftop_products tp ON tps.ProdId=tp.id WHERE tp.Roll!=1 AND tps.Qty>0 AND tp.ProdSpec=2";
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
      if($Structure3!=''){
        $sql.=" AND tps.Structure='$Structure3'";
      }
      if($ModuleMake!=''){
        $sql.=" AND tps.ModuleMake='$ModuleMake'";
      }
      if($StructureMake!=''){
        $sql.=" AND tps.StructureMake='$StructureMake'";
      }
      if($AgencyId!=''){
        $sql.=" AND tps.AgencyId='$AgencyId'";
      }
      if($SchemeId!=''){
        $sql.=" AND tps.SchemeId='$SchemeId'";
      }

      $sql.=" ORDER BY tp.ProductName";
      //echo $sql;
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  ?>
           <tr>
             <td><?php echo $srno3; ?></td>
             <input type="hidden" name="ProdId[]" class="form-control" value="<?php echo $nx['id'];?>">
              <input type="hidden" name="ProdName[]" class="form-control" value='<?php echo $nx['ProductName'];?>'>
              <input type="hidden" name="Unit[]" class="form-control" value="<?php echo $nx['Unit'];?>">
              <input type="hidden" name="Qty[]" class="form-control" value="<?php echo $nx['Qty'];?>">
              <input type="hidden" name="SpecType[]" class="form-control" value="1">
              <input type="hidden" name="Structure[]" class="form-control" value="<?php echo $nx['Structure'];?>">
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $nx['Qty'];?></td>
            </tr>
             <?php $srno3++;} } ?>
        </tbody>
    </table>
 <?php }
?>