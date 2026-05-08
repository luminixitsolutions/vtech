<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, BranchId FROM tbl_users WHERE id='$user_id'");
$Roll = isset($row77['Roll']) ? (int) $row77['Roll'] : 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];
$canAssignDispatch = ($Roll == 1 || $Roll == 7 || in_array('10', $Options) || in_array('11', $Options));
if (!$canAssignDispatch) {
    echo json_encode(['ok' => false, 'error' => 'Access denied.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Invalid method']);
    exit;
}

$raw = isset($_POST['dist_ids']) ? $_POST['dist_ids'] : '';
if (is_string($raw)) {
    $ids = array_filter(array_map('intval', explode(',', $raw)));
} elseif (is_array($raw)) {
    $ids = array_filter(array_map('intval', $raw));
} else {
    $ids = [];
}

if (empty($ids)) {
    echo json_encode(['ok' => false, 'error' => 'No assignments selected.']);
    exit;
}

$idList = implode(',', $ids);
$sql = "SELECT d.id, d.DistId, d.ProductName, d.SerialNo, d.Qty, d.Purity, d.ModelNo, d.ProdType, d.VehicalDate, d.VehicalNo, tb.Name AS StoreName
        FROM tbl_distibute_item_details d
        LEFT JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
        LEFT JOIN tbl_branch tb ON tb.id = h.BranchId
        WHERE d.DistId IN ($idList)
        ORDER BY d.DistId ASC, d.id ASC";
$res = $conn->query($sql);
if (!$res) {
    echo json_encode(['ok' => false, 'error' => $conn->error]);
    exit;
}

$rows = [];
while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
}

ob_start();
?>
<div class="table-responsive" style="max-height:320px;overflow:auto;">
<table class="table table-sm table-bordered mb-0">
    <thead class="thead-light">
        <tr>
            <th>Store</th>
            <th>Product</th>
            <th>Serial</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Model</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)) { ?>
        <tr><td colspan="7" class="text-muted">No line items for the selected assignment(s).</td></tr>
        <?php } else {
            foreach ($rows as $r) {
                $pt = isset($r['ProdType']) ? (int) $r['ProdType'] : 0;
                $typeLabel = ($pt === 1 || $pt === 2) ? 'Serial/Bag' : 'Regular';
        ?>
        <tr>
            <td><?php echo htmlspecialchars($r['StoreName'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($r['ProductName'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($r['SerialNo'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars((string)($r['Qty'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars($r['Purity'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($r['ModelNo'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($typeLabel); ?></td>
        </tr>
        <?php }
        } ?>
    </tbody>
</table>
</div>
<p class="small text-muted mb-0"><?php echo count($rows); ?> line(s) will be given to the dispatch officer (same as store executive allocation).</p>
<?php
$html = ob_get_clean();
echo json_encode(['ok' => true, 'html' => $html, 'line_count' => count($rows)]);
