<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id']; // Admin
$MainPage = "Installation";
$Page = "Dispute-Sites";

/* =========================
   AJAX HANDLER (SAME FILE)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $flowId = intval($_POST['flowId'] ?? 0);
    if ($flowId <= 0) exit;

    /* ================= RE-ACTIVATE DISPUTE ================= */
    if ($action === 'reactivate') {

        // Validate dispute record
        $valid = getRow("
            SELECT id FROM tbl_installation_flow
            WHERE id='$flowId'
            AND current_stage='DISPUTE'
            AND status='DISPUTED'
        ");
        if ($valid == 0) exit;

        // Reactivate & send back to Coordinator
        mysqli_query($conn,"
            UPDATE tbl_installation_flow
            SET status='ACTIVE',
                current_stage='COORDINATOR',
                stage_start_date=NOW(),
                allowed_days=3,
                stage_end_date=NULL
            WHERE id='$flowId'
        ");

        // Log action
        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','REACTIVATED',
             'Dispute re-activated by Admin',NOW())
        ");

        echo "OK";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Disputed Installation Sites</title>
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
    Disputed Installation Sites
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
    <th>Dispute Date</th>
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
    f.stage_end_date
FROM tbl_installation_flow f
JOIN tbl_users u ON u.id=f.CustId
WHERE f.current_stage='DISPUTE'
AND f.status='DISPUTED'
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
<td><?php echo date('d/m/Y', strtotime($row['stage_end_date'])); ?></td>
<td>
    <button class="btn btn-success btn-sm"
        onclick="reactivate('<?php echo $row['FlowId']; ?>')">
        Re-Activate
    </button>
</td>
</tr>
<?php } ?>

</tbody>
</table>

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

function reactivate(flowId){
    if(!confirm('Re-activate this disputed site and send back to Coordinator?')) return;

    $.post('',{
        action:'reactivate',
        flowId:flowId
    },function(res){
        alert('Case re-activated and sent to Coordinator');
        location.reload();
    });
}
</script>

</body>
</html>
