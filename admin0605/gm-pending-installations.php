<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id']; // GM login
$MainPage = "Installation";
$Page = "GM-Pending";

/* =========================
   AJAX HANDLER (SAME FILE)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);

    if ($flowId <= 0) exit;

    // 🔹 GM FOLLOW-UP
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

    // 🔹 GM REQUEST EXTENSION → BUSINESS HEAD
    if ($action === 'gm_request_extension') {

        $already = getRow("
            SELECT id FROM tbl_installation_extensions
            WHERE flow_id='$flowId'
            AND requested_role='GM'
        ");

        if ($already > 0) {
            echo "ALREADY";
            exit;
        }

        mysqli_query($conn,"
            INSERT INTO tbl_installation_extensions
            (
                flow_id,
                requested_by,
                requested_role,
                next_approver_role,
                extension_days,
                status,
                requested_date
            )
            VALUES
            (
                '$flowId',
                '$user_id',
                'GM',
                'BUSINESS_HEAD',
                3,
                'PENDING',
                NOW()
            )
        ");

        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            (
                '$flowId',
                '$user_id',
                'EXTENSION_REQUEST',
                'GM requested extension for 3 days',
                NOW()
            )
        ");

        echo "OK";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title>GM – Pending Installations</title>
<meta charset="utf-8">
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid container-p-y">

<h4 class="font-weight-bold mb-3">
    General Manager – Pending Installations
</h4>

<div class="card">
<div class="table-responsive p-2">

<table id="example" class="table table-striped table-bordered">
<thead>
<tr>
    <th>#</th>
    <th>Beneficiary ID</th>
    <th>Customer Name</th>
    <th>Contact</th>
    <th>Address</th>
    <th>Pending Days</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php
$i = 1;

$sql = "
SELECT 
    f.id AS FlowId,
    u.BeneficiaryId,
    u.Fname,
    u.Phone,
    u.Address,
    DATEDIFF(NOW(), f.stage_start_date) AS PendingDays
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id = f.CustId
WHERE f.current_stage = 'GENERAL_MANAGER'
AND f.is_completed = 0
ORDER BY f.stage_start_date ASC
";

$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {

    if ($row['PendingDays'] >= 3) {
        $status = "<span class='badge badge-danger'>Escalation Due</span>";
    } else {
        $status = "<span class='badge badge-warning'>Follow-up Required</span>";
    }

    $extRequested = getRow("
        SELECT id FROM tbl_installation_extensions
        WHERE flow_id='{$row['FlowId']}'
        AND requested_role='GM'
    ");
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['Fname']; ?></td>
<td><?php echo $row['Phone']; ?></td>
<td><?php echo $row['Address']; ?></td>
<td><?php echo $row['PendingDays']; ?> Day(s)</td>
<td><?php echo $status; ?></td>
<td>
    <button class="btn btn-sm btn-info"
        onclick="openFollowUp('<?php echo $row['FlowId']; ?>')">
        Follow-up
    </button>

    <?php if ($extRequested == 0) { ?>
        <button class="btn btn-sm btn-warning"
            onclick="requestExtension('<?php echo $row['FlowId']; ?>')">
            Request Extension
        </button>
    <?php } else { ?>
        <span class="badge badge-secondary">Extension Requested</span>
    <?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>
</div>

<!-- FOLLOW-UP MODAL -->
<div class="modal fade" id="followUpModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">GM Follow-up</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<input type="hidden" id="flowId">
<textarea id="remark" class="form-control"
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

<?php include_once 'footer_script.php'; ?>

<script>
$(function(){
    $('#example').DataTable();
});

function openFollowUp(flowId){
    $('#flowId').val(flowId);
    $('#remark').val('');
    $('#followUpModal').modal('show');
}

function saveFollowUp(){
    $.post('',{
        action:'followup',
        flowId:$('#flowId').val(),
        remark:$('#remark').val()
    },function(){
        alert('Follow-up saved');
        location.reload();
    });
}

function requestExtension(flowId){
    if(!confirm('Request 3 days extension from Business Head?')) return;

    $.post('',{
        action:'gm_request_extension',
        flowId:flowId
    },function(res){
        if(res === 'ALREADY'){
            alert('Extension already requested');
        }else{
            alert('Extension request sent to Business Head');
            location.reload();
        }
    });
}
</script>

</body>
</html>
