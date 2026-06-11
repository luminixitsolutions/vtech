<?php 
session_start();
include_once '../config.php';
if($_POST['action']=='view'){
    $esc = function ($value) use ($conn) {
        return $conn->real_escape_string(trim((string) $value));
    };
?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Name 2</th>
              
               <th>Unit</th>
               <th>Qty</th>
             
             
            </tr>
        </thead>
        <tbody>
          <?php 
          $AcDc = $esc($_POST['AcDc'] ?? ''); 
    $Surface = $esc($_POST['Surface'] ?? '');
    $PumpCapacity = $esc($_POST['PumpCapacity'] ?? '');
    $ModuleWatt = $esc($_POST['ModuleWatt'] ?? '');
    $ModuleQty = $esc($_POST['ModuleQty'] ?? '');
    $Structure = $esc($_POST['Structure'] ?? '');
    $ModuleMake = $esc($_POST['ModuleMake'] ?? '');
    $StructureMake = $esc($_POST['StructureMake'] ?? '');
    $AgencyId = $esc($_POST['AgencyId'] ?? '');
    $SchemeId = $esc($_POST['SchemeId'] ?? '');
    $structureName = '';
    if ($Structure !== '') {
        $structRow = getRecord("SELECT Name FROM tbl_common_master WHERE id='$Structure' LIMIT 1");
        $structureName = $structRow['Name'] ?? '';
    }
    $structureKey = $structureName !== '' ? preg_replace('/\s+/', '', strtolower($structureName)) : '';
 $srno = 1;
  $sql = "SELECT * FROM tbl_products WHERE Status='1' AND ProdSpec=2 ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
        if ($structureKey !== '') {
            $productKey = preg_replace('/\s+/', '', strtolower($nx['ProductName']));
            if (strpos($productKey, $structureKey) === false) {
                continue;
            }
        }
  		$sql2 = "SELECT * FROM tbl_struct_product_specification WHERE ProdId='".$nx['id']."'";
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
      $sql2 .= " ORDER BY id DESC LIMIT 1";
  		$row2 = getRecord($sql2);
  		$Qty = ($row2 && isset($row2['Qty']) && $row2['Qty'] !== '' && $row2['Qty'] !== null) ? $row2['Qty'] : '0';
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <td><?php echo htmlspecialchars($nx['ProductName'], ENT_QUOTES, 'UTF-8'); ?></td>
             <td><?php echo htmlspecialchars($nx['Unit'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
               <input type="hidden" name="ProdId[]" value="<?php echo (int) $nx['id']; ?>">
               <input type="hidden" name="ProdName[]" value="<?php echo htmlspecialchars($nx['ProductName'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="Unit[]" value="<?php echo htmlspecialchars($nx['Unit'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="number" name="Qty[]" class="form-control struct-qty-input" min="0" step="any" value="<?php echo htmlspecialchars((string) $Qty, ENT_QUOTES, 'UTF-8'); ?>">
            </td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
 <?php } 


 if($_POST['action']=='view2'){
    $esc = function ($value) use ($conn) {
        return $conn->real_escape_string(trim((string) $value));
    };
?>
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
    $Surface = $esc($_POST['Surface'] ?? '');
    $PumpCapacity = $esc($_POST['PumpCapacity'] ?? '');
    $ModuleWatt = $esc($_POST['ModuleWatt'] ?? '');
    $ModuleQty = $esc($_POST['ModuleQty'] ?? '');
    $Structure1 = $esc($_POST['Structure1'] ?? '');
    $Structure2 = $esc($_POST['Structure2'] ?? '');
    $Structure3 = $esc($_POST['Structure3'] ?? '');
    $ModuleMake = $esc($_POST['ModuleMake'] ?? '');
    $StructureMake = $esc($_POST['StructureMake'] ?? '');
    $AgencyId = $esc($_POST['AgencyId'] ?? '');
    $SchemeId = $esc($_POST['SchemeId'] ?? '');
 $srno = 1;
 if($Structure1!=''){
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty,tps.Structure FROM tbl_struct_product_specification tps 
         INNER JOIN tbl_products tp ON tps.ProdId=tp.id WHERE tps.Qty>0 AND tp.ProdSpec=2";
      if($Surface!=''){ $sql.=" AND tps.Surface='$Surface'"; }
      if($PumpCapacity!=''){ $sql.=" AND tps.PumpCapacity='$PumpCapacity'"; }
      if($ModuleWatt!=''){ $sql.=" AND tps.ModuleWatt='$ModuleWatt'"; }
      if($ModuleQty!=''){ $sql.=" AND tps.ModuleQty='$ModuleQty'"; }
      if($Structure1!=''){ $sql.=" AND tps.Structure='$Structure1'"; }
      if($ModuleMake!=''){ $sql.=" AND tps.ModuleMake='$ModuleMake'"; }
      if($StructureMake!=''){ $sql.=" AND tps.StructureMake='$StructureMake'"; }
      if($AgencyId!=''){ $sql.=" AND tps.AgencyId='$AgencyId'"; }
      if($SchemeId!=''){ $sql.=" AND tps.SchemeId='$SchemeId'"; }

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
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty,tps.Structure FROM tbl_struct_product_specification tps 
         INNER JOIN tbl_products tp ON tps.ProdId=tp.id WHERE tps.Qty>0 AND tp.ProdSpec=2";
      if($Surface!=''){ $sql.=" AND tps.Surface='$Surface'"; }
      if($PumpCapacity!=''){ $sql.=" AND tps.PumpCapacity='$PumpCapacity'"; }
      if($ModuleWatt!=''){ $sql.=" AND tps.ModuleWatt='$ModuleWatt'"; }
      if($ModuleQty!=''){ $sql.=" AND tps.ModuleQty='$ModuleQty'"; }
      if($Structure2!=''){ $sql.=" AND tps.Structure='$Structure2'"; }
      if($ModuleMake!=''){ $sql.=" AND tps.ModuleMake='$ModuleMake'"; }
      if($StructureMake!=''){ $sql.=" AND tps.StructureMake='$StructureMake'"; }
      if($AgencyId!=''){ $sql.=" AND tps.AgencyId='$AgencyId'"; }
      if($SchemeId!=''){ $sql.=" AND tps.SchemeId='$SchemeId'"; }

      $sql.="  GROUP BY tps.ProdId ORDER BY tp.ProductName";
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
 $sql = "SELECT tp.id,tp.ProductName,tp.Unit,tps.Qty,tps.Structure FROM tbl_struct_product_specification tps 
         INNER JOIN tbl_products tp ON tps.ProdId=tp.id WHERE tps.Qty>0 AND tp.ProdSpec=2";
      if($Surface!=''){ $sql.=" AND tps.Surface='$Surface'"; }
      if($PumpCapacity!=''){ $sql.=" AND tps.PumpCapacity='$PumpCapacity'"; }
      if($ModuleWatt!=''){ $sql.=" AND tps.ModuleWatt='$ModuleWatt'"; }
      if($ModuleQty!=''){ $sql.=" AND tps.ModuleQty='$ModuleQty'"; }
      if($Structure3!=''){ $sql.=" AND tps.Structure='$Structure3'"; }
      if($ModuleMake!=''){ $sql.=" AND tps.ModuleMake='$ModuleMake'"; }
      if($StructureMake!=''){ $sql.=" AND tps.StructureMake='$StructureMake'"; }
      if($AgencyId!=''){ $sql.=" AND tps.AgencyId='$AgencyId'"; }
      if($SchemeId!=''){ $sql.=" AND tps.SchemeId='$SchemeId'"; }

      $sql.="  GROUP BY tps.ProdId ORDER BY tp.ProductName";
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