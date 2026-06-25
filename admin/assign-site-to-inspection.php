<?php 
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Site-Inspection";
$Page = "Assign-Site-Inspection";

$assignStatus = (string) ($_GET['AssignStatus'] ?? 'unassign');
if (!in_array($assignStatus, ['all', 'assign', 'unassign'], true)) {
    $assignStatus = 'unassign';
}
$returnQs = http_build_query(['AssignStatus' => $assignStatus]);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Sell List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php

require_once __DIR__ . '/inc-menu-option-groups.php';
$currentUserRoll = (int) ($_SESSION['Admin']['Roll'] ?? 0);
$isAdmin = adminUserHasFullMenuAccess($currentUserRoll);

if (isset($_POST['unassign_site'])) {
    if (!$isAdmin) {
        echo "<script>alert('Only admin can unassign site.');window.location.href='assign-site-to-inspection.php';</script>";
        exit;
    }
    $custId = (int) ($_POST['unassign_cust_id'] ?? 0);
    if ($custId > 0) {
        $conn->query("UPDATE tbl_users SET ContractorInspectionStatus='0', ContractorInspectionId=NULL, ContractorInspectionDate=NULL WHERE id='$custId' AND ContractorInspectionStatus='1'");
        $conn->query("DELETE FROM tbl_made_contractor_commision WHERE CustId='$custId' AND Roll=6");
    }
    echo "<script>alert('Site unassigned from contractor');window.location.href=" . json_encode('assign-site-to-inspection.php?' . $returnQs) . ";</script>";
    exit;
}

if(isset($_POST['submit'])){

   $number = count($_POST['CheckId']);

   $InspectionId = $_POST['InspectionId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $CustId = addslashes(trim($_POST['CustId'][$i]));
                $sql = "UPDATE tbl_users SET ContractorInspectionStatus='1',ContractorInspectionId='$InspectionId',ContractorInspectionDate='$CreatedDate' WHERE id='$CustId'";
                $conn->query($sql);

                $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
                $row = getRecord($sql);
                $PumpCapacity = $row['PumpCapacity'];

                $sql2 = "SELECT InspectionVal FROM tbl_contractor_commision WHERE UserId='$InspectionId' AND Capacity='$PumpCapacity'";
                $row2 = getRecord($sql2);
                $Amount = $row2['InspectionVal'];

                $sql = "DELETE FROM tbl_made_contractor_commision WHERE CustId='$CustId' AND Roll=6";
                $conn->query($sql);

                $sql = "INSERT INTO tbl_made_contractor_commision SET ContractorId='$InspectionId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Inspection',Amount='$Amount',CreatedDate='$CreatedDate',Roll=6";
                $conn->query($sql);

                }
              }
            }
        }
        
  

        echo "<script>alert('Site Assign To Contractor');window.location.href='assign-site-to-inspection.php?AssignStatus=unassign';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Site For Inspection To Contractor
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<form method="get" action="assign-site-to-inspection.php" class="mb-3">
<div class="form-row align-items-end">
<div class="form-group col-lg-3 col-md-4 mb-0">
<label class="form-label">Status</label>
<select class="form-control" name="AssignStatus" onchange="this.form.submit()">
<option value="unassign" <?php if ($assignStatus === 'unassign') { ?>selected<?php } ?>>Unassign</option>
<option value="assign" <?php if ($assignStatus === 'assign') { ?>selected<?php } ?>>Assign</option>
<option value="all" <?php if ($assignStatus === 'all') { ?>selected<?php } ?>>All</option>
</select>
</div>
</div>
</form>

<div class="card" style="padding: 10px;">
<?php if ($isAdmin) { ?>
<form id="unassign-site-form" method="post" action="" style="display:none;">
    <input type="hidden" name="unassign_cust_id" id="unassign_cust_id" value="">
    <input type="hidden" name="unassign_site" value="1">
</form>
<?php } ?>
    <form id="validation-form" method="post" enctype="multipart/form-data" action="">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                
<div class="form-row">
<?php if ($assignStatus !== 'assign') { ?>
 <div class="form-group col-lg-4">
<label class="form-label"> Contractor<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="InspectionId" id="InspectionId" required>
<option selected="" value="">Select</option>
 <?php 
$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
<?php } ?>



</div>

       

 


                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
               
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Address</th>
                <th>Contractor</th>
                
                <th>Assign Date</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;

            $sql = "SELECT ts.CustId, ts.CustName, ts.CellNo, ts.Address,
                           IFNULL(tu.ContractorInspectionStatus, '0') AS ContractorInspectionStatus,
                           tu.ContractorInspectionDate,
                           tu2.Fname AS CoName
                    FROM tbl_sell ts
                    INNER JOIN (
                        SELECT CustId, MAX(id) AS max_id
                        FROM tbl_sell
                        WHERE Status = 1
                        GROUP BY CustId
                    ) latest ON ts.id = latest.max_id
                    INNER JOIN tbl_users tu ON tu.id = ts.CustId
                    LEFT JOIN tbl_users tu2 ON tu2.id = tu.ContractorInspectionId AND tu.ContractorInspectionStatus = '1'
                    WHERE 1=1";

            if ($assignStatus === 'assign') {
                $sql .= " AND tu.ContractorInspectionStatus = '1'";
            } elseif ($assignStatus === 'unassign') {
                $sql .= " AND IFNULL(tu.ContractorInspectionStatus, '0') != '1'";
            }

            $sql .= " ORDER BY (tu.ContractorInspectionStatus = '1') ASC, ts.id DESC";

            $siteRows = getList($sql);
            if (!is_array($siteRows)) {
                $siteRows = [];
            }

            foreach ($siteRows as $row) {
                $isAssigned = ((int) $row['ContractorInspectionStatus'] === 1);
                $bcolor = $isAssigned ? 'background-color: #b9efb9;' : '';
             ?>
            <tr style="<?php echo $bcolor; ?>">
                <td><?php if (!$isAssigned) { ?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo (int) $row['CustId']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo (int) $row['CustId']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <?php if (!$isAssigned) { ?>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo (int) $row['CustId']; ?>">
                 <input type="hidden" value="<?php echo (int) $row['CustId']; ?>" name="CustId[]">
                 <input type="hidden" value="<?php echo htmlspecialchars($row['CustName'], ENT_QUOTES, 'UTF-8'); ?>" name="CustName[]">
                 <input type="hidden" value="<?php echo htmlspecialchars($row['CellNo'], ENT_QUOTES, 'UTF-8'); ?>" name="CellNo[]">
                 <input type="hidden" value="<?php echo htmlspecialchars($row['Address'], ENT_QUOTES, 'UTF-8'); ?>" name="Address[]">
                 <?php } ?>
               
              <td><?php echo htmlspecialchars($row['CustName'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['CellNo'], ENT_QUOTES, 'UTF-8'); ?></td>
               <td><?php echo htmlspecialchars($row['Address'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars((string) ($row['CoName'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
               <td><?php if (empty($row['ContractorInspectionDate'])) { echo ''; } else { echo date('d/m/Y', strtotime(str_replace('-', '/', $row['ContractorInspectionDate']))); } ?></td>
               <td>
                <?php if ($isAssigned && $isAdmin) { ?>
                    <button type="submit" class="btn btn-sm btn-outline-warning" form="unassign-site-form"
                        onclick="document.getElementById('unassign_cust_id').value='<?php echo (int) $row['CustId']; ?>'; return confirm('Unassign this site from the contractor?');">Unassign</button>
                <?php } else { ?>
                    <span class="text-muted">—</span>
                <?php } ?>
               </td>
            </tr>
           <?php $i++; } ?>
        </tbody>
    </table>
</div>

<div class="form-group col-md-1" style="padding-top:20px;">
<?php if ($assignStatus !== 'assign') { ?>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
<?php } ?>
</div>
</form>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
     function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }

    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        "pageLength": 25,
        "order": []
    });
});
</script>
</body>
</html>
