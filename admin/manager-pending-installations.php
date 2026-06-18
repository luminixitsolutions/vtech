<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Manager-Pending";

installationWorkflowBootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);
    if ($flowId <= 0) {
        exit;
    }

    if ($action === 'load_history') {
        if (!installationWorkflowCanAccessFlow($flowId, $user_id, 'manager')) {
            exit;
        }
        echo installationWorkflowRenderHistory($flowId);
        exit;
    }

    if (!installationWorkflowCanAccessFlow($flowId, $user_id, 'manager')) {
        exit;
    }

    $flow = installationWorkflowGetFlow($flowId);
    if (!$flow || installationWorkflowManagerIsReadOnly($flow)) {
        if ($action !== 'load_history') {
            exit;
        }
    }

    if ($action === 'followup') {
        $remark = trim($_POST['remark'] ?? '');
        if ($remark === '') {
            exit;
        }
        installationWorkflowLogAction($flowId, $user_id, 'FOLLOW_UP', $remark);
        exit;
    }

    if ($action === 'installed') {
        $result = installationWorkflowMarkInstalled($flowId, $user_id);
        echo !empty($result['ok']) ? 'OK' : 'ERR';
        exit;
    }

    if ($action === 'request_extension') {
        $flow = installationWorkflowGetFlow($flowId);
        $gmId = installationWorkflowResolveGmForManager($flow ?: [], $user_id);
        if ($gmId <= 0) {
            echo 'NO_GM';
            exit;
        }
        if (!empty($flow['id']) && (int) ($flow['gm_id'] ?? 0) <= 0) {
            mysqli_query($conn, "UPDATE tbl_installation_flow SET gm_id='$gmId' WHERE id='$flowId'");
        }
        $result = installationWorkflowRequestExtension(
            $flowId,
            $user_id,
            'MANAGER',
            'GENERAL_MANAGER',
            $gmId,
            trim($_POST['remark'] ?? 'Extension requested for 3 days')
        );
        echo !empty($result['ok']) ? 'OK' : ($result['code'] ?? 'ERR');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Manager Pending Installations</title>
    <meta charset="utf-8">
    <?php include_once 'header_script.php'; ?>
</head>
<style><?php echo installationWorkflowTimelineCss(); ?></style>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid container-p-y">

<h4 class="font-weight-bold mb-3">Manager - Pending Installations</h4>

<div class="card">
<div class="card-datatable table-responsive p-2">
<table id="example" class="table table-striped table-bordered">
<thead>
<tr>
    <th>#</th>
    <th>Beneficiary ID</th>
    <th>Customer Name</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Coordinator</th>
    <th>Due Date</th>
    <th>Overdue Days</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php
$i = 1;
$listSql = installationWorkflowManagerListSql($user_id);
$sql = "
SELECT f.*, u.Fname, u.Phone, u.Address, u.BeneficiaryId, coord.Fname AS CoordinatorName
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id=f.CustId
LEFT JOIN tbl_users coord ON coord.id=f.assigned_to
WHERE $listSql
ORDER BY f.manager_due_date ASC, f.coordinator_due_date ASC
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $extStatus = installationWorkflowLatestExtensionStatus($row['id'], 'MANAGER');
    $dueDate = installationWorkflowManagerDisplayDueDate($row);
    $overdue = installationWorkflowManagerOverdueDays($row);
    $readOnly = installationWorkflowManagerIsReadOnly($row);
    if ($readOnly) {
        $status = "<span class='badge badge-danger'>Escalation Due</span>";
    } else {
        $status = installationWorkflowStatusBadge($row, $extStatus);
    }
    $rowStyle = $readOnly ? "style='background:#ffe6e6'" : (($extStatus === 'APPROVED') ? "style='background:#e8f5e9'" : '');
?>
<tr <?php echo $rowStyle; ?>>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['Fname']; ?></td>
<td><?php echo $row['Phone']; ?></td>
<td><?php echo $row['Address']; ?></td>
<td><?php echo $row['CoordinatorName'] ?: '-'; ?></td>
<td><?php echo $dueDate ? date('d/m/Y', strtotime($dueDate)) : '-'; ?></td>
<td><?php echo $overdue > 0 ? $overdue : 0; ?></td>
<td><?php echo $status; ?></td>
<td>
    <button class="btn btn-sm btn-secondary" onclick="openHistoryModal('<?php echo $row['id']; ?>')">History</button>
    <?php if ($readOnly) { ?>
        <span class="badge badge-secondary ml-1">View history only</span>
    <?php } else { ?>
    <button class="btn btn-sm btn-info" onclick="openFollowUp('<?php echo $row['id']; ?>')">Follow-up</button>
    <button class="btn btn-sm btn-success" onclick="markInstalled('<?php echo $row['id']; ?>')">Mark Installed</button>
    <?php if ($extStatus === '') { ?>
        <button class="btn btn-sm btn-warning" onclick="requestExtension('<?php echo $row['id']; ?>')">Request Extension</button>
    <?php } elseif ($extStatus === 'PENDING') { ?>
        <span class="badge badge-info">Awaiting GM Approval</span>
    <?php } else { ?>
        <span class="badge badge-success">Extension Approved</span>
    <?php } ?>
    <?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

</div>

<div class="modal fade" id="historyModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Installation History</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body"><div id="historyContent" class="timeline"></div></div>
      <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<div class="modal fade" id="followUpModal">
<div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Manager Follow-up</h5><button class="close" data-dismiss="modal">&times;</button></div>
<div class="modal-body"><input type="hidden" id="flowId"><textarea id="remark" class="form-control" rows="4" placeholder="Enter follow-up remark"></textarea></div>
<div class="modal-footer"><button class="btn btn-primary" onclick="saveFollowUp()">Save</button></div>
</div></div></div>

<?php include_once 'footer.php'; ?>
</div></div></div>
<?php include_once 'footer_script.php'; ?>
<script>
$(function(){ $('#example').DataTable(); });
function openFollowUp(flowId){ $('#flowId').val(flowId); $('#remark').val(''); $('#followUpModal').modal('show'); }
function openHistoryModal(flowId){
    $('#historyModal').modal('show');
    $('#historyContent').html('<div class="text-center text-muted">Loading...</div>');
    $.post('',{action:'load_history',flowId:flowId},function(res){ $('#historyContent').html(res); });
}
function saveFollowUp(){
    if($('#remark').val().trim()===''){ alert('Please enter remark'); return; }
    $.post('',{action:'followup',flowId:$('#flowId').val(),remark:$('#remark').val()},function(){ alert('Follow-up saved'); location.reload(); });
}
function markInstalled(flowId){
    if(!confirm('Mark installation as completed?')) return;
    $.post('',{action:'installed',flowId:flowId},function(res){
        if(res==='OK'){ alert('Installation completed'); location.reload(); } else { alert('Unable to complete'); }
    });
}
function requestExtension(flowId){
    if(!confirm('Request 3 days extension from General Manager?')) return;
    $.post('',{action:'request_extension',flowId:flowId,remark:'Extension requested for 3 days'},function(res){
        if(res==='ALREADY') alert('Extension already requested');
        else if(res==='NO_GM') alert('General Manager not mapped for this site');
        else { alert('Extension request sent to GM'); location.reload(); }
    });
}
</script>
</body>
</html>
