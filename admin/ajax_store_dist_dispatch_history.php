<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-store-dist-dispatch-status.php';

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$user_id'");
$Roll = isset($row77['Roll']) ? (int) $row77['Roll'] : 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];
$canAssignDispatch = ($Roll == 1 || $Roll == 7 || in_array('10', $Options) || in_array('11', $Options));
if (!$canAssignDispatch) {
    echo json_encode(['ok' => false, 'error' => 'Access denied.']);
    exit;
}

$storeDistId = isset($_POST['store_dist_id']) ? (int) $_POST['store_dist_id'] : 0;
if ($storeDistId < 1) {
    echo json_encode(['ok' => false, 'error' => 'Invalid assignment.']);
    exit;
}

storeDistDispatchEnsureLogTable($conn);
$sql = "SELECT * FROM tbl_store_dist_dispatch_log
        WHERE StoreDistId='$storeDistId'
        ORDER BY id DESC
        LIMIT 100";
$rows = getList($sql);
if (!is_array($rows)) {
    $rows = [];
}

ob_start();
?>
<div class="table-responsive" style="max-height:360px;overflow:auto;">
<table class="table table-sm table-bordered mb-0">
    <thead class="thead-light">
        <tr>
            <th>Date / time</th>
            <th>Action</th>
            <th>Dispatch officer</th>
            <th>By</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)) { ?>
        <tr><td colspan="5" class="text-muted">No activity logged yet for this assignment.</td></tr>
        <?php } else {
            foreach ($rows as $r) {
                $action = isset($r['ActionType']) ? strtolower((string) $r['ActionType']) : '';
                $actionLabel = $action === 'revert' ? 'Reverted to store' : ($action === 'assign' ? 'Assigned to dispatch' : ucfirst($action));
                $badgeClass = $action === 'revert' ? 'badge-warning' : 'badge-success';
                $dt = '';
                if (!empty($r['CreatedDate'])) {
                    $ts = strtotime($r['CreatedDate']);
                    if ($ts) {
                        $dt = date('d/m/Y H:i', $ts);
                    }
                }
        ?>
        <tr>
            <td><?php echo htmlspecialchars($dt); ?></td>
            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($actionLabel); ?></span></td>
            <td><?php echo htmlspecialchars((string) ($r['DispatchOfficerName'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($r['PerformedByName'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($r['Remarks'] ?? '')); ?></td>
        </tr>
        <?php }
        } ?>
    </tbody>
</table>
</div>
<?php
$html = ob_get_clean();
echo json_encode(['ok' => true, 'html' => $html]);
