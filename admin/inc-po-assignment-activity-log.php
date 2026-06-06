<?php
/**
 * Main activity log for PO → store and later store → dispatch steps.
 */

function poAssignmentActivityEnsureTable($conn)
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;
    $sql = "CREATE TABLE IF NOT EXISTS tbl_po_assignment_activity_log (
      id INT(11) NOT NULL AUTO_INCREMENT,
      PoId INT(11) NOT NULL DEFAULT 0,
      StoreDistId INT(11) NOT NULL DEFAULT 0,
      ActionType VARCHAR(32) NOT NULL,
      BranchId INT(11) NOT NULL DEFAULT 0,
      StoreName VARCHAR(255) DEFAULT NULL,
      DispatchOfficerId INT(11) NOT NULL DEFAULT 0,
      DispatchOfficerName VARCHAR(255) DEFAULT NULL,
      PerformedBy INT(11) NOT NULL,
      PerformedByName VARCHAR(255) DEFAULT NULL,
      LineCount INT(11) NOT NULL DEFAULT 0,
      Remarks VARCHAR(500) DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      PRIMARY KEY (id),
      KEY PoId (PoId),
      KEY StoreDistId (StoreDistId),
      KEY CreatedDate (CreatedDate)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
}

/**
 * Parse PO id from store distribute narration marker __POID123__
 */
function parsePoIdFromStoreDistNarration($narration)
{
    $narration = (string) $narration;
    if (preg_match('/__POID(\d+)__/i', $narration, $m)) {
        return (int) $m[1];
    }
    return 0;
}

/**
 * @param string $actionType po_to_store|dispatch_assign|dispatch_revert
 */
function logPoAssignmentActivity($conn, $poId, $storeDistId, $actionType, $branchId, $performedBy, $lineCount = 0, $remarks = '', $dispatchOfficerId = 0)
{
    poAssignmentActivityEnsureTable($conn);
    $poId = (int) $poId;
    $storeDistId = (int) $storeDistId;
    $branchId = (int) $branchId;
    $performedBy = (int) $performedBy;
    $lineCount = (int) $lineCount;
    $dispatchOfficerId = (int) $dispatchOfficerId;
    $actionType = strtolower(trim((string) $actionType));
    $allowed = ['po_to_store', 'dispatch_assign', 'dispatch_revert'];
    if (!in_array($actionType, $allowed, true)) {
        return false;
    }

    $performerName = '';
    if ($performedBy > 0) {
        $pu = getRecord("SELECT Fname FROM tbl_users WHERE id='$performedBy' LIMIT 1");
        if (is_array($pu) && !empty($pu['Fname'])) {
            $performerName = trim((string) $pu['Fname']);
        }
    }
    $storeName = '';
    if ($branchId > 0) {
        $br = getRecord("SELECT Name FROM tbl_branch WHERE id='$branchId' LIMIT 1");
        if (is_array($br) && !empty($br['Name'])) {
            $storeName = trim((string) $br['Name']);
        }
    }
    $officerName = '';
    if ($dispatchOfficerId > 0) {
        $ou = getRecord("SELECT Fname FROM tbl_users WHERE id='$dispatchOfficerId' LIMIT 1");
        if (is_array($ou) && !empty($ou['Fname'])) {
            $officerName = trim((string) $ou['Fname']);
        }
    }

    $remarksEsc = mysqli_real_escape_string($conn, (string) $remarks);
    $performerEsc = mysqli_real_escape_string($conn, $performerName);
    $storeEsc = mysqli_real_escape_string($conn, $storeName);
    $officerEsc = mysqli_real_escape_string($conn, $officerName);
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO tbl_po_assignment_activity_log SET
        PoId='$poId',
        StoreDistId='$storeDistId',
        ActionType='$actionType',
        BranchId='$branchId',
        StoreName='$storeEsc',
        DispatchOfficerId='$dispatchOfficerId',
        DispatchOfficerName='$officerEsc',
        PerformedBy='$performedBy',
        PerformedByName='$performerEsc',
        LineCount='$lineCount',
        Remarks='$remarksEsc',
        CreatedDate='$now'";
    return (bool) $conn->query($sql);
}

/**
 * @return array<int, array>
 */
function getPoAssignmentActivityRows($conn, $poId)
{
    $poId = (int) $poId;
    if ($poId < 1) {
        return [];
    }
    poAssignmentActivityEnsureTable($conn);
    $rows = getList("SELECT * FROM tbl_po_assignment_activity_log WHERE PoId='$poId' ORDER BY id DESC LIMIT 200");
    return is_array($rows) ? $rows : [];
}

function renderPoAssignmentActivityLogHtml($conn, $poId)
{
    $rows = getPoAssignmentActivityRows($conn, $poId);
    $labels = [
        'po_to_store' => ['Store assignment', 'badge-primary'],
        'dispatch_assign' => ['Assigned to dispatch', 'badge-success'],
        'dispatch_revert' => ['Reverted from dispatch to store', 'badge-warning'],
    ];
    ob_start();
    ?>
<div class="table-responsive" style="max-height:320px;overflow:auto;">
<table class="table table-sm table-bordered mb-0">
    <thead class="thead-light">
        <tr>
            <th>Date / time</th>
            <th>Action</th>
            <th>Store</th>
            <th>Dispatch officer</th>
            <th>Lines</th>
            <th>By</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)) { ?>
        <tr><td colspan="7" class="text-muted">No activity logged yet.</td></tr>
        <?php } else {
            foreach ($rows as $r) {
                $act = isset($r['ActionType']) ? (string) $r['ActionType'] : '';
                $meta = isset($labels[$act]) ? $labels[$act] : [ucfirst($act), 'badge-secondary'];
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
            <td><span class="badge <?php echo $meta[1]; ?>"><?php echo htmlspecialchars($meta[0]); ?></span></td>
            <td><?php echo htmlspecialchars((string) ($r['StoreName'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($r['DispatchOfficerName'] ?? '—')); ?></td>
            <td><?php echo (int) ($r['LineCount'] ?? 0) > 0 ? (int) $r['LineCount'] : '—'; ?></td>
            <td><?php echo htmlspecialchars((string) ($r['PerformedByName'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($r['Remarks'] ?? '')); ?></td>
        </tr>
        <?php }
        } ?>
    </tbody>
</table>
</div>
    <?php
    return ob_get_clean();
}

/**
 * Log dispatch assign/revert on main PO log when batch came from a PO store transfer.
 */
/**
 * Latest store-assignment summary per PO for list screens.
 *
 * @param int[] $poIds
 * @return array<int, array{assigned: bool, store_name: string, assigned_by: string, assigned_at: string, line_count: int, store_dist_id: int}>
 */
function buildPoStoreAssignListSummaryMap($conn, array $poIds)
{
    $emptyRow = [
        'assigned' => false,
        'store_name' => '',
        'assigned_by' => '',
        'assigned_at' => '',
        'line_count' => 0,
        'store_dist_id' => 0,
    ];
    $map = [];
    $ids = [];
    foreach ($poIds as $id) {
        $id = (int) $id;
        if ($id > 0) {
            $ids[$id] = true;
            $map[$id] = $emptyRow;
        }
    }
    if (empty($ids)) {
        return $map;
    }

    poAssignmentActivityEnsureTable($conn);
    $idList = implode(',', array_keys($ids));

    $sqlLog = "SELECT l.PoId, l.StoreDistId, l.StoreName, l.PerformedByName, l.LineCount, l.CreatedDate
        FROM tbl_po_assignment_activity_log l
        INNER JOIN (
            SELECT PoId, MAX(id) AS max_id
            FROM tbl_po_assignment_activity_log
            WHERE ActionType = 'po_to_store' AND PoId IN ($idList)
            GROUP BY PoId
        ) latest ON latest.max_id = l.id";
    $resLog = $conn->query($sqlLog);
    if ($resLog) {
        while ($row = $resLog->fetch_assoc()) {
            $poId = (int) ($row['PoId'] ?? 0);
            if ($poId < 1 || !isset($map[$poId])) {
                continue;
            }
            $dt = '';
            if (!empty($row['CreatedDate'])) {
                $ts = strtotime($row['CreatedDate']);
                if ($ts) {
                    $dt = date('d/m/Y H:i', $ts);
                }
            }
            $map[$poId] = [
                'assigned' => true,
                'store_name' => trim((string) ($row['StoreName'] ?? '')),
                'assigned_by' => trim((string) ($row['PerformedByName'] ?? '')),
                'assigned_at' => $dt,
                'line_count' => (int) ($row['LineCount'] ?? 0),
                'store_dist_id' => (int) ($row['StoreDistId'] ?? 0),
            ];
        }
    }

    $sqlDist = "SELECT di.id AS StoreDistId, di.BranchId, di.CreatedDate, di.Narration, tb.Name AS StoreName
        FROM tbl_distibute_items di
        LEFT JOIN tbl_branch tb ON tb.id = di.BranchId
        WHERE di.Status = '1' AND di.Narration LIKE '%__POID%__'
        ORDER BY di.id DESC";
    $resDist = $conn->query($sqlDist);
    if ($resDist) {
        while ($row = $resDist->fetch_assoc()) {
            $poId = parsePoIdFromStoreDistNarration($row['Narration'] ?? '');
            if ($poId < 1 || !isset($map[$poId]) || !empty($map[$poId]['assigned'])) {
                continue;
            }
            $dt = '';
            if (!empty($row['CreatedDate'])) {
                $ts = strtotime(str_replace('-', '/', $row['CreatedDate']));
                if ($ts) {
                    $dt = date('d/m/Y', $ts);
                }
            }
            $map[$poId] = [
                'assigned' => true,
                'store_name' => trim((string) ($row['StoreName'] ?? '')),
                'assigned_by' => '',
                'assigned_at' => $dt,
                'line_count' => 0,
                'store_dist_id' => (int) ($row['StoreDistId'] ?? 0),
            ];
        }
    }

    return $map;
}

/**
 * Compact table cell for PO list — store assignment tracking.
 */
function renderPoStoreAssignListCellHtml($poId, array $summary)
{
    $poId = (int) $poId;
    if (empty($summary['assigned'])) {
        return '<span class="text-muted small">Not assigned</span>';
    }
    $store = htmlspecialchars((string) ($summary['store_name'] ?? ''));
    $by = trim((string) ($summary['assigned_by'] ?? ''));
    $at = trim((string) ($summary['assigned_at'] ?? ''));
    $lines = (int) ($summary['line_count'] ?? 0);
    ob_start();
    ?>
<div class="small">
    <span class="badge badge-primary">At store</span>
    <?php if ($store !== '') { ?><div><strong><?php echo $store; ?></strong></div><?php } ?>
    <?php if ($by !== '') { ?><div>By: <?php echo htmlspecialchars($by); ?></div><?php } ?>
    <?php if ($at !== '') { ?><div><?php echo htmlspecialchars($at); ?></div><?php } ?>
    <?php if ($lines > 0) { ?><div><?php echo (int) $lines; ?> line(s)</div><?php } ?>
    <button type="button" class="btn btn-link btn-sm p-0 btn-po-assign-history" data-po-id="<?php echo $poId; ?>">View history</button>
</div>
    <?php
    return ob_get_clean();
}

function logPoDispatchActivityFromStoreDist($conn, $storeDistId, $actionType, $dispatchOfficerId, $performedBy, $lineCount, $remarks)
{
    $storeDistId = (int) $storeDistId;
    if ($storeDistId < 1) {
        return false;
    }
    $h = getRecord("SELECT BranchId, Narration FROM tbl_distibute_items WHERE id='$storeDistId' LIMIT 1");
    if (!is_array($h)) {
        return false;
    }
    $poId = parsePoIdFromStoreDistNarration($h['Narration'] ?? '');
    if ($poId < 1) {
        return false;
    }
    return logPoAssignmentActivity(
        $conn,
        $poId,
        $storeDistId,
        $actionType,
        (int) ($h['BranchId'] ?? 0),
        (int) $performedBy,
        (int) $lineCount,
        (string) $remarks,
        (int) $dispatchOfficerId
    );
}
