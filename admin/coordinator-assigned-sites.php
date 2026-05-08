<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id']; // Coordinator login
$MainPage = "Service";
$Page = "Coordinator-Sites";

/* =========================
   AJAX ACTION HANDLER
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);

    if ($flowId <= 0) {
        exit;
    }

    // ✅ SAVE FOLLOW-UP (MULTIPLE ALLOWED)
    if ($action === 'followup') {

        $remark = trim($_POST['remark'] ?? '');
        if ($remark === '') exit;

        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','FOLLOW_UP','$remark',NOW())
        ");
        exit;
    }

    // ✅ MARK INSTALLED (CLOSE FLOW)
    if ($action === 'installed') {

        mysqli_query($conn,"
            UPDATE tbl_installation_flow
            SET is_completed=1,
                status='COMPLETED',
                stage_end_date=NOW()
            WHERE id='$flowId'
        ");

        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','INSTALL_DONE','Installation completed',NOW())
        ");
        exit;
    }
    
    
    if ($action === 'load_history') {

    $sql = "
        SELECT a.*, u.Fname
        FROM tbl_installation_actions a
        LEFT JOIN tbl_users u ON u.id = a.action_by
        WHERE a.flow_id = '$flowId'
        ORDER BY a.action_date DESC
    ";

    $res = $conn->query($sql);

    if ($res->num_rows == 0) {
        echo "<div class='text-center text-muted'>No history found</div>";
        exit;
    }

    while ($row = $res->fetch_assoc()) {

        $badge = ($row['action_type'] == 'INSTALL_DONE')
            ? "<span class='badge badge-install'>Installed</span>"
            : "<span class='badge badge-follow'>Follow-up</span>";

        echo "
        <div class='timeline-item'>
            <div class='timeline-dot'></div>
            <div class='timeline-card'>
                <h6>
                    {$badge}
                    {$row['action_type']}
                </h6>
                <p class='mb-1'>{$row['remarks']}</p>
                <small>
                    {$row['Fname']} • 
                    ".date('d M Y, h:i A', strtotime($row['action_date']))."
                </small>
            </div>
        </div>
        ";
    }
    exit;
}
}



?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Assigned Installation Sites</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
</head>

<style>
.timeline {
    position: relative;
    padding-left: 25px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 25px;
}
.timeline-dot {
    position: absolute;
    left: -2px;
    top: 5px;
    width: 14px;
    height: 14px;
    background: #007bff;
    border-radius: 50%;
}
.timeline-card {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 12px 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
.timeline-card h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}
.timeline-card small {
    color: #6c757d;
}
.badge-follow {
    background: #17a2b8;
}
.badge-install {
    background: #28a745;
}
</style>


<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">

<h4 class="font-weight-bold py-3 mb-0">
    Assigned Installation Sites
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">

<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
    <th>Sr No</th>
    <th>Beneficiary ID</th>
    <th>Customer Name</th>
    <th>Contact No</th>
    <th>Address</th>
    <th>Pump Capacity</th>
    <th>Assigned Date</th>
    <th>Pending Days</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php
$i = 1;
$user_id = 680;
$sql = "
SELECT 
    f.id AS FlowId,
    f.assigned_date,
    f.stage_start_date,
    u.Fname,
    u.Phone,
    u.Address,
    u.BeneficiaryId,
    cm.Name AS PumpCapacity,
    DATEDIFF(NOW(), f.stage_start_date) AS PendingDays
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id = f.CustId
LEFT JOIN tbl_common_master cm ON cm.id = u.PumpCapacity
WHERE f.assigned_to = '$user_id'
AND f.current_stage = 'COORDINATOR'
AND f.is_completed = 0
ORDER BY f.assigned_date DESC
";

$res = $conn->query($sql);
while($row = $res->fetch_assoc()){

    if($row['PendingDays'] >= 3){
        $status = "<span class='badge badge-danger'>Escalation Due</span>";
    }elseif($row['PendingDays'] == 2){
        $status = "<span class='badge badge-warning'>Follow-up Today</span>";
    }else{
        $status = "<span class='badge badge-success'>In Progress</span>";
    }
?>
<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $row['BeneficiaryId']; ?></td>
    <td><?php echo $row['Fname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $row['PumpCapacity']; ?></td>
    <td><?php echo date("d/m/Y", strtotime($row['assigned_date'])); ?></td>
    <td><?php echo $row['PendingDays']; ?> Day(s)</td>
    <td><?php echo $status; ?></td>
    <td>
        <button class="btn btn-sm btn-secondary"
    onclick="openHistoryModal('<?php echo $row['FlowId']; ?>')">
    History
</button>


        <button class="btn btn-sm btn-info"
            onclick="openFollowUp('<?php echo $row['FlowId']; ?>')">
            Follow-up
        </button>

        <button class="btn btn-sm btn-success"
            onclick="markInstalled('<?php echo $row['FlowId']; ?>')">
            Mark Installed
        </button>
    </td>
</tr>
<?php $i++; } ?>
</tbody>
</table>

</div>
</div>
</div>

<!-- HISTORY MODAL -->
<div class="modal fade" id="historyModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">📜 Installation Follow-up History</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div id="historyContent" class="timeline">
          <div class="text-center text-muted">Loading history...</div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- FOLLOW-UP MODAL -->
<div class="modal fade" id="followUpModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Coordinator Follow-up</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="flowId">
        <textarea id="followupRemark" class="form-control"
            placeholder="Enter follow-up remark"></textarea>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" onclick="saveFollowUp()">Save</button>
      </div>
    </div>
  </div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>

<?php include_once 'footer_script.php'; ?>

<script>
$(document).ready(function() {
    $('#example').DataTable({ scrollX: true });
});

function viewHistory(flowId){
    window.location.href = 'installation-history.php?flowId=' + flowId;
}

function openFollowUp(flowId){
    $('#flowId').val(flowId);
    $('#followupRemark').val('');
    $('#followUpModal').modal('show');
}

function saveFollowUp(){
    let flowId = $('#flowId').val();
    let remark = $('#followupRemark').val();

    if(remark.trim() === ''){
        alert('Please enter remark');
        return;
    }

    $.post('', {
        action: 'followup',
        flowId: flowId,
        remark: remark
    }, function(){
        alert('Follow-up saved');
        location.reload();
    });
}

function markInstalled(flowId){
    if(!confirm('Are you sure installation is completed?')) return;

    $.post('', {
        action: 'installed',
        flowId: flowId
    }, function(){
        alert('Installation marked completed');
        location.reload();
    });
}

function openHistoryModal(flowId){
    $('#historyModal').modal('show');
    $('#historyContent').html('<div class="text-center text-muted">Loading history...</div>');

    $.post('', {
        action: 'load_history',
        flowId: flowId
    }, function(res){
        $('#historyContent').html(res);
    });
}
</script>

</body>
</html>
