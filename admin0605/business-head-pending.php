<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id']; // Business Head
$MainPage = "Installation";
$Page = "BH-Pending";

/* =========================
   AJAX HANDLER (SAME FILE)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);
    if ($flowId <= 0) exit;

    // Validate stage
    $valid = getRow("
        SELECT id FROM tbl_installation_flow
        WHERE id='$flowId'
        AND current_stage='BUSINESS_HEAD'
        AND is_completed=0
    ");
    if ($valid == 0) exit;

    /* ================= FOLLOW-UP ================= */
    if ($action === 'followup') {

        $remark = trim($_POST['remark'] ?? '');
        if ($remark == '') exit;

        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','FOLLOW_UP','$remark',NOW())
        ");
        exit;
    }

    /* ================= MARK DISPUTE (FINAL) ================= */
    if ($action === 'dispute') {

        mysqli_query($conn,"
            UPDATE tbl_installation_flow
            SET status='DISPUTED',
                current_stage='DISPUTE',
                stage_end_date=NOW()
            WHERE id='$flowId'
        ");

        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','DISPUTE',
             'Marked as dispute by Business Head',NOW())
        ");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Business Head Pending Installations</title>
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
    Business Head - Escalated Installations
</h4>

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
    <th>Pending Days</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php
$i = 1;
$sql = "
SELECT 
    f.id AS FlowId,
    u.Fname,
    u.Phone,
    u.Address,
    u.BeneficiaryId,
    DATEDIFF(NOW(), f.stage_start_date) AS PendingDays
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id=f.CustId
WHERE f.current_stage='BUSINESS_HEAD'
AND f.is_completed=0
ORDER BY f.stage_start_date ASC
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {

    $pending = (int)$row['PendingDays'];
    $rowStyle = ($pending >= 3) ? "style='background:#ffe6e6'" : "";
?>
<tr <?php echo $rowStyle; ?>>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['Fname']; ?></td>
<td><?php echo $row['Phone']; ?></td>
<td><?php echo $row['Address']; ?></td>
<td><?php echo $pending; ?> Day(s)</td>
<td>
    <button class="btn btn-info btn-sm"
        onclick="openFollowUp('<?php echo $row['FlowId']; ?>')">
        Follow-up
    </button>

    <button class="btn btn-danger btn-sm"
        onclick="markDispute('<?php echo $row['FlowId']; ?>')">
        Mark Dispute
    </button>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>

</div>

<!-- FOLLOW-UP MODAL -->
<div class="modal fade" id="followUpModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Business Head Follow-up</h5>
    <button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <input type="hidden" id="flowId">
    <textarea id="remark" class="form-control" rows="4"
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
    if($('#remark').val().trim()===''){
        alert('Please enter remark');
        return;
    }
    $.post('',{
        action:'followup',
        flowId:$('#flowId').val(),
        remark:$('#remark').val()
    },function(){
        alert('Follow-up saved');
        location.reload();
    });
}

function markDispute(flowId){
    if(!confirm('Mark this installation as DISPUTE? This is final.')) return;

    $.post('',{
        action:'dispute',
        flowId:flowId
    },function(){
        alert('Marked as dispute');
        location.reload();
    });
}
</script>

</body>
</html>
