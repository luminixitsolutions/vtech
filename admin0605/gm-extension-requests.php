<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id']; // GM login
$MainPage = "Installation";
$Page = "GM-Extensions";

/* =========================
   AJAX HANDLER (SAME FILE)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $extId  = intval($_POST['extId'] ?? 0);

    if ($extId <= 0) exit;

    /* ================= APPROVE EXTENSION ================= */
    if ($action === 'approve') {

        $ext = getRecord("
            SELECT e.*, f.id AS FlowId
            FROM tbl_installation_extensions e
            JOIN tbl_installation_flow f ON f.id=e.flow_id
            WHERE e.id='$extId'
            AND e.status='PENDING'
            AND f.is_completed=0
        ");
        if (!$ext) exit;

        $flowId = $ext['flow_id'];
        $days   = $ext['extension_days'];

        // Approve extension
        mysqli_query($conn,"
            UPDATE tbl_installation_extensions
            SET status='APPROVED',
                approved_by='$user_id',
                approved_date=NOW()
            WHERE id='$extId'
        ");

        // Apply extension to flow
        mysqli_query($conn,"
            UPDATE tbl_installation_flow
            SET allowed_days = allowed_days + $days,
                stage_start_date = NOW()
            WHERE id='$flowId'
        ");

        // Log action
        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','EXTENSION_APPROVED',
             'Extension approved for $days days',NOW())
        ");

        echo "OK";
        exit;
    }

    /* ================= REJECT EXTENSION ================= */
    if ($action === 'reject') {

        $ext = getRecord("
            SELECT e.*, f.id AS FlowId
            FROM tbl_installation_extensions e
            JOIN tbl_installation_flow f ON f.id=e.flow_id
            WHERE e.id='$extId'
            AND e.status='PENDING'
            AND f.is_completed=0
        ");
        if (!$ext) exit;

        $flowId = $ext['flow_id'];

        mysqli_query($conn,"
            UPDATE tbl_installation_extensions
            SET status='REJECTED',
                approved_by='$user_id',
                approved_date=NOW()
            WHERE id='$extId'
        ");

        mysqli_query($conn,"
            INSERT INTO tbl_installation_actions
            (flow_id, action_by, action_type, remarks, action_date)
            VALUES
            ('$flowId','$user_id','EXTENSION_REJECTED',
             'Extension request rejected',NOW())
        ");

        echo "OK";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | GM Extension Requests</title>
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

<h4 class="font-weight-bold mb-3">GM - Extension Requests</h4>

<div class="card">
<div class="card-datatable table-responsive p-2">

<table id="example" class="table table-striped table-bordered">
<thead>
<tr>
    <th>#</th>
    <th>Beneficiary ID</th>
    <th>Customer Name</th>
    <th>Requested By</th>
    <th>Days</th>
    <th>Requested Date</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$i = 1;
$sql = "
SELECT 
    e.id AS ExtId,
    e.extension_days,
    e.requested_date,
    u.Fname AS ManagerName,
    cu.Fname AS CustomerName,
    cu.BeneficiaryId
FROM tbl_installation_extensions e
JOIN tbl_installation_flow f ON f.id=e.flow_id
JOIN tbl_users cu ON cu.id=f.CustId
JOIN tbl_users u ON u.id=e.requested_by
WHERE e.status='PENDING'
ORDER BY e.requested_date ASC
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['CustomerName']; ?></td>
<td><?php echo $row['ManagerName']; ?></td>
<td><?php echo $row['extension_days']; ?></td>
<td><?php echo date('d/m/Y', strtotime($row['requested_date'])); ?></td>
<td>
    <button class="btn btn-success btn-sm"
        onclick="approve('<?php echo $row['ExtId']; ?>')">
        Approve
    </button>
    <button class="btn btn-danger btn-sm"
        onclick="reject('<?php echo $row['ExtId']; ?>')">
        Reject
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

function approve(id){
    if(!confirm('Approve this extension request?')) return;
    $.post('',{action:'approve', extId:id},function(){
        alert('Extension approved successfully');
        location.reload();
    });
}

function reject(id){
    if(!confirm('Reject this extension request?')) return;
    $.post('',{action:'reject', extId:id},function(){
        alert('Extension rejected');
        location.reload();
    });
}
</script>

</body>
</html>
