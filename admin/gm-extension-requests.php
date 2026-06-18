<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "GM-Extensions";

installationWorkflowBootstrap();

$gmScopeSql = installationWorkflowGmExtensionScopeSql($user_id);
$managerExtFilter = installationWorkflowManagerExtensionFilterSql('e');
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'PENDING';
$customerFilter = isset($_GET['customer_id']) ? (int) $_GET['customer_id'] : 0;
$statusSql = installationWorkflowExtensionStatusFilterSql($statusFilter, 'e');
$customerSql = installationWorkflowExtensionCustomerFilterSql($customerFilter, 'f');
$customerOptions = installationWorkflowExtensionCustomerOptions($gmScopeSql, $managerExtFilter, 'mgr');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $extId = intval($_POST['extId'] ?? 0);
    if ($extId <= 0) {
        exit;
    }

    if ($action === 'approve') {
        $result = installationWorkflowApproveExtension($extId, $user_id, $gmScopeSql);
        echo !empty($result['ok']) ? 'OK' : 'ERR';
        exit;
    }

    if ($action === 'reject') {
        $result = installationWorkflowRejectExtension($extId, $user_id, $gmScopeSql);
        echo !empty($result['ok']) ? 'OK' : 'ERR';
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
<div class="layout-wrapper layout-2"><div class="layout-inner">
<?php include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>
<div class="layout-content"><div class="container-fluid container-p-y">
<h4 class="font-weight-bold mb-3">GM - Extension Requests</h4>

<div class="card mb-3"><div class="card-body py-3">
<form method="get" class="form-row align-items-end" id="filterForm">
    <div class="form-group col-md-4 mb-0">
        <label class="form-label">Customer</label>
        <select name="customer_id" id="customer_id" class="form-control">
            <option value="">All Customers</option>
            <?php foreach ($customerOptions as $cust) { ?>
                <option value="<?php echo (int) $cust['id']; ?>" <?php if ($customerFilter === (int) $cust['id']) { ?>selected<?php } ?>>
                    <?php echo htmlspecialchars($cust['Fname'] . ' (' . $cust['BeneficiaryId'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-3 mb-0">
        <label class="form-label">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="PENDING" <?php if ($statusFilter === 'PENDING') { ?>selected<?php } ?>>Pending</option>
            <option value="APPROVED" <?php if ($statusFilter === 'APPROVED') { ?>selected<?php } ?>>Approved</option>
            <option value="REJECTED" <?php if ($statusFilter === 'REJECTED') { ?>selected<?php } ?>>Rejected</option>
            <option value="ALL" <?php if ($statusFilter === 'ALL') { ?>selected<?php } ?>>All</option>
        </select>
    </div>
    <div class="form-group col-md-2 mb-0">
        <button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
    </div>
    <div class="form-group col-md-2 mb-0">
        <a href="gm-extension-requests.php" class="btn btn-secondary btn-block">Reset</a>
    </div>
</form>
</div></div>

<div class="card"><div class="card-datatable table-responsive p-2">
<table id="example" class="table table-striped table-bordered">
<thead><tr>
    <th>#</th><th>Beneficiary ID</th><th>Customer Name</th><th>Requested By</th><th>Days</th><th>Requested Date</th><th>Status</th><th>Remarks</th><th>Action</th>
</tr></thead>
<tbody>
<?php
$i = 1;
$sql = "
SELECT e.id AS ExtId, e.status, e.extension_days, e.requested_date, e.approved_date, e.remarks,
       mgr.Fname AS ManagerName, cu.Fname AS CustomerName, cu.BeneficiaryId
FROM tbl_installation_extensions e
JOIN tbl_installation_flow f ON f.id=e.flow_id
JOIN tbl_users cu ON cu.id=f.CustId
JOIN tbl_users mgr ON mgr.id=e.requested_by
WHERE $statusSql
AND $managerExtFilter
AND ($gmScopeSql)
$customerSql
ORDER BY e.requested_date DESC
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $isPending = ($row['status'] === 'PENDING');
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $row['BeneficiaryId']; ?></td>
<td><?php echo $row['CustomerName']; ?></td>
<td><?php echo $row['ManagerName']; ?></td>
<td><?php echo $row['extension_days']; ?></td>
<td><?php echo date('d/m/Y', strtotime($row['requested_date'])); ?></td>
<td><?php echo installationWorkflowExtensionStatusBadge($row['status']); ?></td>
<td><?php echo htmlspecialchars($row['remarks'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td>
    <?php if ($isPending) { ?>
        <button class="btn btn-success btn-sm" onclick="approve('<?php echo $row['ExtId']; ?>')">Approve</button>
        <button class="btn btn-danger btn-sm" onclick="reject('<?php echo $row['ExtId']; ?>')">Reject</button>
    <?php } else { ?>
        <span class="text-muted small"><?php echo $row['approved_date'] ? date('d/m/Y', strtotime($row['approved_date'])) : '-'; ?></span>
    <?php } ?>
</td>
</tr>
<?php } ?>
</tbody></table></div></div></div>
<?php include_once 'footer.php'; ?></div></div></div>
<?php include_once 'footer_script.php'; ?>
<script>
var filterQuery = '?status=' + encodeURIComponent($('#status').val() || 'PENDING') + '&customer_id=' + encodeURIComponent($('#customer_id').val() || '');

$(function(){
    $('#example').DataTable({ order: [[5, 'desc']] });
});

function reloadAfterAction(newStatus){
    var customerId = $('#customer_id').val() || '';
    window.location.href = 'gm-extension-requests.php?status=' + encodeURIComponent(newStatus) + '&customer_id=' + encodeURIComponent(customerId);
}

function approve(id){
    if(!confirm('Approve this extension request?')) return;
    $.post('',{action:'approve',extId:id},function(r){
        alert(r==='OK' ? 'Extension approved' : 'Unable to approve');
        if(r==='OK') reloadAfterAction('ALL');
    });
}

function reject(id){
    if(!confirm('Reject this extension request?')) return;
    $.post('',{action:'reject',extId:id},function(r){
        alert(r==='OK' ? 'Extension rejected' : 'Unable to reject');
        if(r==='OK') reloadAfterAction('ALL');
    });
}
</script>
</body></html>
