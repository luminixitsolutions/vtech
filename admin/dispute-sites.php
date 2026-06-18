<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Dispute-Sites";

installationWorkflowBootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);
    if ($flowId <= 0) {
        exit;
    }

    if ($action === 'reactivate') {
        $coordinatorId = intval($_POST['coordinatorId'] ?? 0);
        $result = installationWorkflowReactivateDispute($flowId, $user_id, $coordinatorId);
        echo !empty($result['ok']) ? 'OK' : 'ERR';
        exit;
    }
}

$coordinators = getList("SELECT id, Fname FROM tbl_users WHERE Status='1' AND Roll=6 ORDER BY Fname ASC");
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Disputed Installation Sites</title>
    <meta charset="utf-8">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2"><div class="layout-inner">
<?php include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>
<div class="layout-content"><div class="container-fluid container-p-y">
<h4 class="font-weight-bold mb-3">Disputed Installation Sites</h4>
<div class="card"><div class="card-datatable table-responsive p-2">
<table id="example" class="table table-striped table-bordered">
<thead><tr>
    <th>#</th><th>Beneficiary ID</th><th>Customer Name</th><th>Phone</th><th>Address</th><th>Dispute Date</th><th>Action</th>
</tr></thead>
<tbody>
<?php
$i = 1;
$sql = "
SELECT f.id AS FlowId, u.Fname, u.Phone, u.Address, u.BeneficiaryId, f.stage_end_date, f.assigned_to
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id=f.CustId
WHERE f.current_stage='DISPUTE' AND f.status='DISPUTED'
ORDER BY f.stage_end_date DESC
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['Fname']; ?></td>
<td><?php echo $row['Phone']; ?></td>
<td><?php echo $row['Address']; ?></td>
<td><?php echo $row['stage_end_date'] ? date('d/m/Y', strtotime($row['stage_end_date'])) : '-'; ?></td>
<td>
    <button class="btn btn-success btn-sm" onclick="reactivate('<?php echo $row['FlowId']; ?>')">Re-Assign Coordinator</button>
</td>
</tr>
<?php } ?>
</tbody></table></div></div></div>

<div class="modal fade" id="reactivateModal"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Re-Assign Coordinator</h5><button class="close" data-dismiss="modal">&times;</button></div>
<div class="modal-body">
    <input type="hidden" id="reactivateFlowId">
    <label>Coordinator</label>
    <select id="reactivateCoordinatorId" class="form-control">
        <option value="">Select Coordinator</option>
        <?php foreach ($coordinators as $c) { ?>
            <option value="<?php echo (int) $c['id']; ?>"><?php echo htmlspecialchars($c['Fname'], ENT_QUOTES, 'UTF-8'); ?></option>
        <?php } ?>
    </select>
</div>
<div class="modal-footer"><button class="btn btn-primary" onclick="saveReactivate()">Re-Assign</button></div>
</div></div></div>

<?php include_once 'footer.php'; ?></div></div></div>
<?php include_once 'footer_script.php'; ?>
<script>
$(function(){ $('#example').DataTable(); });
function reactivate(flowId){
    $('#reactivateFlowId').val(flowId);
    $('#reactivateCoordinatorId').val('');
    $('#reactivateModal').modal('show');
}
function saveReactivate(){
    var coordinatorId = $('#reactivateCoordinatorId').val();
    if(!coordinatorId){ alert('Please select coordinator'); return; }
    $.post('',{action:'reactivate',flowId:$('#reactivateFlowId').val(),coordinatorId:coordinatorId},function(r){
        if(r==='OK'){ alert('Site reassigned to coordinator'); location.reload(); } else { alert('Unable to reassign'); }
    });
}
</script>
</body></html>
