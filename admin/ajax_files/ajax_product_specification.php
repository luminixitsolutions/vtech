<?php 
session_start();
include_once '../config.php';

if (($_POST['action'] ?? '') === 'save') {
    include_once '../auth.php';
    require_once dirname(__DIR__) . '/inc-sync-customer-specification.php';

    header('Content-Type: application/json; charset=utf-8');

    $response = ['ok' => false, 'error' => '', 'message' => '', 'sync' => ''];
    $user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
    if ($user_id <= 0) {
        $response['error'] = 'Session expired. Please login again.';
        echo json_encode($response);
        exit;
    }

    $esc = function ($value) use ($conn) {
        return $conn->real_escape_string(trim((string) $value));
    };

    $postArray = function ($key) {
        $val = $_POST[$key] ?? [];
        if (is_array($val)) {
            return $val;
        }
        return ($val !== '' && $val !== null) ? [(string) $val] : [];
    };

    $AcDc = $esc($_POST['AcDc'] ?? '');
    $Surface = $esc($_POST['Surface'] ?? '');
    $PumpCapacity = $esc($_POST['PumpCapacity'] ?? '');
    $WaterSource = $esc($_POST['WaterSource'] ?? '');
    $BoreDia = $esc($_POST['BoreDia'] ?? '');
    if ($BoreDia === '') {
        $BoreDia = '0';
    }
    $PumpHead = $esc($_POST['PumpHead'] ?? '');
    $AgencyId = $esc($_POST['AgencyId'] ?? '');
    $PumpOutletSize = $esc($_POST['PumpOutletSize'] ?? '');
    $CreatedDate = date('Y-m-d');

    if ($AcDc === '' || $Surface === '' || $PumpCapacity === '' || $WaterSource === '' || $PumpHead === '' || $AgencyId === '' || $PumpOutletSize === '') {
        $response['error'] = 'Please fill all required dropdown fields before submit.';
        echo json_encode($response);
        exit;
    }

    $prodIds = $postArray('ProdId');
    $prodNames = $postArray('ProdName');
    $units = $postArray('Unit');
    $qtys = $postArray('Qty');

    if (count($prodIds) === 0) {
        $response['error'] = 'Product list not loaded. Change any filter to reload the table, then submit again.';
        echo json_encode($response);
        exit;
    }

    $conn->begin_transaction();
    try {
        $sql = "DELETE FROM tbl_product_specification WHERE AcDc='$AcDc' AND Surface='$Surface' AND PumpCapacity='$PumpCapacity' AND WaterSource='$WaterSource' AND BoreDia='$BoreDia' AND PumpHead='$PumpHead' AND AgencyId='$AgencyId' AND PumpOutletSize='$PumpOutletSize'";
        if (!$conn->query($sql)) {
            throw new Exception($conn->error);
        }

        $savedCount = 0;
        $number = count($prodIds);
        for ($i = 0; $i < $number; $i++) {
            $prodId = trim((string) ($prodIds[$i] ?? ''));
            $qty = trim((string) ($qtys[$i] ?? ''));
            if ($prodId === '' || $qty === '' || !is_numeric($qty) || (float) $qty <= 0) {
                continue;
            }
            $prodName = $esc($prodNames[$i] ?? '');
            $unit = $esc($units[$i] ?? '');
            $qtyEsc = $esc($qty);

            $sql = "INSERT INTO tbl_product_specification SET AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity',WaterSource='$WaterSource',BoreDia='$BoreDia',PumpHead='$PumpHead',ProdId='$prodId',ProdName='$prodName',Unit='$unit',Qty='$qtyEsc',CreatedBy='$user_id',CreatedDate='$CreatedDate',AgencyId='$AgencyId',PumpOutletSize='$PumpOutletSize'";
            if (!$conn->query($sql)) {
                throw new Exception($conn->error);
            }
            $savedCount++;
        }

        if ($savedCount <= 0) {
            throw new Exception('Enter quantity (Qty) for at least one product row, then submit.');
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $response['error'] = $e->getMessage();
        echo json_encode($response);
        exit;
    }

    $response['ok'] = true;
    $response['message'] = 'Product specification saved successfully.';

    try {
        $syncResult = syncCustomerBosSpecifications($conn, [
            'AcDc' => $AcDc,
            'Surface' => $Surface,
            'PumpCapacity' => $PumpCapacity,
            'WaterSource' => $WaterSource,
            'BoreDia' => $BoreDia,
            'PumpHead' => $PumpHead,
            'AgencyId' => $AgencyId,
            'PumpOutletSize' => $PumpOutletSize,
        ], $user_id, $CreatedDate);
        if ($syncResult['error'] !== '') {
            $response['sync'] = 'Warning: customer sync failed — ' . $syncResult['error'];
        } else {
            $response['sync'] = (int) $syncResult['updated'] . ' customer(s) updated';
            if ((int) $syncResult['skipped'] > 0) {
                $response['sync'] .= ', ' . (int) $syncResult['skipped'] . ' skipped (delivery challan exists)';
            }
        }
    } catch (Throwable $syncEx) {
        $response['sync'] = 'Warning: customer sync failed — ' . $syncEx->getMessage();
    }

    echo json_encode($response);
    exit;
}

if($_POST['action']=='view'){
    $esc = function ($value) use ($conn) {
        return $conn->real_escape_string(trim((string) $value));
    };
?>
<table id="example" class="table table-striped table-bordered" style="width:100%">
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
          $AcDc = $esc($_POST['AcDc'] ?? ''); 
    $Surface = $esc($_POST['Surface'] ?? '');
    $PumpCapacity = $esc($_POST['PumpCapacity'] ?? '');
    $WaterSource = $esc($_POST['WaterSource'] ?? '');
    $BoreDia = $esc($_POST['BoreDia'] ?? '');
    if ($BoreDia === '') {
        $BoreDia = '0';
    }
    $PumpHead = $esc($_POST['PumpHead'] ?? '');
    $AgencyId = $esc($_POST['AgencyId'] ?? '');
    $PumpOutletSize = $esc($_POST['PumpOutletSize'] ?? '');
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
  		$sql2.=" AND BoreDia='$BoreDia'";
  		if($PumpHead!=''){
  			$sql2.=" AND PumpHead='$PumpHead'";
  		}
      if($AgencyId!=''){
        $sql2.=" AND AgencyId='$AgencyId'";
      }
      if($PumpOutletSize!=''){
        $sql2.=" AND PumpOutletSize='$PumpOutletSize'";
      }
      $sql2 .= " ORDER BY id DESC LIMIT 1";
      
  		$row2 = getRecord($sql2);
  ?>
           <tr>
             <td><?php echo $srno; ?>
               <input type="hidden" name="ProdId[]" value="<?php echo (int) $nx['id']; ?>">
               <input type="hidden" name="ProdName[]" value="<?php echo htmlspecialchars($nx['ProductName'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="Unit[]" value="<?php echo htmlspecialchars($nx['Unit'], ENT_QUOTES, 'UTF-8'); ?>">
             </td>
             <td><?php echo htmlspecialchars($nx['ProductName'], ENT_QUOTES, 'UTF-8'); ?></td>
             <td><?php echo htmlspecialchars($nx['Unit'], ENT_QUOTES, 'UTF-8'); ?></td>
             <td><input type="number" name="Qty[]" class="form-control product-spec-qty" min="0" step="any" value="<?php echo htmlspecialchars((string) ($row2['Qty'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"></td>
           </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
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
          $AcDc = trim((string) ($_POST['AcDc'] ?? $_POST['acdc'] ?? ''));
    $Surface = trim((string) ($_POST['Surface'] ?? ''));
    $PumpCapacity = trim((string) ($_POST['PumpCapacity'] ?? ''));
    $WaterSource = trim((string) ($_POST['WaterSource'] ?? ''));
    $BoreDia = trim((string) ($_POST['BoreDia'] ?? ''));
    $PumpHead = trim((string) ($_POST['PumpHead'] ?? ''));
    $AgencyId = trim((string) ($_POST['AgencyId'] ?? ''));
    $PumpOutletSize = trim((string) ($_POST['PumpOutletSize'] ?? ''));
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
      $sql.=" ORDER BY tp.ProductName";
      //echo $sql;
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  	
  ?>
           <tr>
             <td><?php echo $srno; ?>
               <input type="hidden" name="ProdId[]" value="<?php echo (int) $nx['id']; ?>">
               <input type="hidden" name="ProdName[]" value="<?php echo htmlspecialchars($nx['ProductName'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="Unit[]" value="<?php echo htmlspecialchars($nx['Unit'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="Qty[]" value="<?php echo htmlspecialchars((string) $nx['Qty'], ENT_QUOTES, 'UTF-8'); ?>">
               <input type="hidden" name="SpecType[]" value="0">
               <input type="hidden" name="Structure[]" value="0">
             </td>
             <td><?php echo htmlspecialchars($nx['ProductName'], ENT_QUOTES, 'UTF-8'); ?></td>
             <td><?php echo htmlspecialchars($nx['Unit'], ENT_QUOTES, 'UTF-8'); ?></td>
             <td><?php echo htmlspecialchars((string) $nx['Qty'], ENT_QUOTES, 'UTF-8'); ?></td>
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