<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "GM-Pending";

installationWorkflowBootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);
    if ($flowId <= 0) {
        exit;
    }

    if ($action === 'load_history') {
        if (!installationWorkflowCanAccessFlow($flowId, $user_id, 'gm')) {
            exit;
        }
        echo installationWorkflowRenderHistory($flowId);
        exit;
    }

    if (!installationWorkflowCanAccessFlow($flowId, $user_id, 'gm')) {
        exit;
    }

    $flow = installationWorkflowGetFlow($flowId);
    if (!$flow || installationWorkflowGmIsReadOnly($flow)) {
        exit;
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

    if ($action === 'gm_request_extension') {
        $flow = installationWorkflowGetFlow($flowId);
        $bhId = (int) ($flow['business_head_id'] ?? 0);
        if ($bhId <= 0) {
            echo 'NO_BH';
            exit;
        }
        $result = installationWorkflowRequestExtension(
            $flowId,
            $user_id,
            'GM',
            'BUSINESS_HEAD',
            $bhId,
            trim($_POST['remark'] ?? 'GM requested extension for 3 days')
        );
        echo !empty($result['ok']) ? 'OK' : ($result['code'] ?? 'ERR');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | GM Pending Installations</title>
<meta charset="utf-8">
<?php include_once 'header_script.php'; ?>
</head>
<style><?php echo installationWorkflowTimelineCss(); ?></style>
<body>
<div class="layout-wrapper layout-2"><div class="layout-inner">
<?php include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>
<div class="layout-content"><div class="container-fluid container-p-y">
<h4 class="font-weight-bold mb-3">General Manager - Pending Installations</h4>
<div class="card"><div class="table-responsive p-2">
<table id="example" class="table table-striped table-bordered">
<thead><tr>
    <th>#</th><th>Beneficiary ID</th><th>Customer Name</th><th>Contact</th><th>Address</th><th>Due Date</th><th>Overdue Days</th><th>Status</th><th>Action</th>
</tr></thead>
<tbody>
<?php
$i = 1;
$listSql = installationWorkflowGmListSql($user_id);
$sql = "
SELECT f.*, u.BeneficiaryId, u.Fname, u.Phone, u.Address
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id = f.CustId
WHERE $listSql
ORDER BY COALESCE(f.gm_due_date, f.manager_due_date, f.coordinator_due_date) ASC
";
$res = $conn->query($sql);
if (!$res && function_exists('error_log')) {
    error_log('gm-pending-installations query failed: ' . $conn->error);
}
while ($res && ($row = $res->fetch_assoc())) {
    $extStatus = installationWorkflowLatestExtensionStatus($row['id'], 'GM');
    $dueDate = installationWorkflowGmDisplayDueDate($row);
    $overdue = installationWorkflowGmOverdueDays($row);
    $readOnly = installationWorkflowGmIsReadOnly($row);
    if ($readOnly) {
        $status = "<span class='badge badge-danger'>Escalation Due</span>";
    } else {
        $status = installationWorkflowStatusBadge($row, $extStatus);
    }
    $rowStyle = $readOnly ? "style='background:#ffe6e6'" : '';
?>
<tr <?php echo $rowStyle; ?>>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['Fname']; ?></td>
<td><?php echo $row['Phone']; ?></td>
<td><?php echo $row['Address']; ?></td>
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
        <span class="badge badge-info">Awaiting BH Approval</span>
    <?php } else { ?>
        <span class="badge badge-success">Extension Approved</span>
    <?php } ?>
    <?php } ?>
</td>
</tr>
<?php } ?>
</tbody></table></div></div></div>

<div class="modal fade" id="historyModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
<div class="modal-header bg-dark text-white"><h5 class="modal-title">Installation History</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div>
<div class="modal-body"><div id="historyContent" class="timeline"></div></div>
</div></div></div>

<div class="modal fade" id="followUpModal"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">GM Follow-up</h5><button class="close" data-dismiss="modal">&times;</button></div>
<div class="modal-body"><input type="hidden" id="flowId"><textarea id="remark" class="form-control" placeholder="Enter follow-up remark"></textarea></div>
<div class="modal-footer"><button class="btn btn-primary" onclick="saveFollowUp()">Save</button></div>
</div></div></div>

<?php include_once 'footer.php'; ?></div></div></div>
<?php include_once 'footer_script.php'; ?>
<script>
$(function(){ $('#example').DataTable(); });
function openFollowUp(flowId){ $('#flowId').val(flowId); $('#remark').val(''); $('#followUpModal').modal('show'); }
function openHistoryModal(flowId){ $('#historyModal').modal('show'); $.post('',{action:'load_history',flowId:flowId},function(r){ $('#historyContent').html(r); }); }
function saveFollowUp(){ $.post('',{action:'followup',flowId:$('#flowId').val(),remark:$('#remark').val()},function(){ alert('Follow-up saved'); location.reload(); }); }
function markInstalled(flowId){ if(!confirm('Mark installation completed?')) return; $.post('',{action:'installed',flowId:flowId},function(r){ if(r==='OK') location.reload(); }); }
function requestExtension(flowId){ if(!confirm('Request 3 days extension from Business Head?')) return; $.post('',{action:'gm_request_extension',flowId:flowId},function(r){ if(r==='OK'){ alert('Sent to Business Head'); location.reload(); } else if(r==='NO_BH') alert('Business Head not mapped'); else alert('Extension already requested'); }); }
</script>
</body></html>
