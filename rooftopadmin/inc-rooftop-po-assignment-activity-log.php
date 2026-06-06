<?php
/**
 * Activity log for rooftop PO → store (and later dispatch) workflow.
 */

function rooftopPoAssignmentActivityEnsureTable($conn)
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;
    $sql = "CREATE TABLE IF NOT EXISTS tbl_rooftop_po_assignment_activity_log (
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

function parseRooftopPoIdFromStoreDistNarration($narration)
{
    $narration = (string) $narration;
    if (preg_match('/__ROOFTOPPOID(\d+)__/i', $narration, $m)) {
        return (int) $m[1];
    }
    return 0;
}

/**
 * @param string $actionType po_to_store|dispatch_assign|dispatch_revert
 */
function logRooftopPoAssignmentActivity($conn, $poId, $storeDistId, $actionType, $branchId, $performedBy, $lineCount = 0, $remarks = '', $dispatchOfficerId = 0)
{
    rooftopPoAssignmentActivityEnsureTable($conn);
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
        $br = getRecord("SELECT Name FROM tbl_rooftop_branch WHERE id='$branchId' LIMIT 1");
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
    $sql = "INSERT INTO tbl_rooftop_po_assignment_activity_log SET
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

function getRooftopPoAssignmentActivityRows($conn, $poId)
{
    $poId = (int) $poId;
    if ($poId < 1) {
        return [];
    }
    rooftopPoAssignmentActivityEnsureTable($conn);
    $rows = getList("SELECT * FROM tbl_rooftop_po_assignment_activity_log WHERE PoId='$poId' ORDER BY id DESC LIMIT 200");
    return is_array($rows) ? $rows : [];
}

function renderRooftopPoAssignmentActivityLogHtml($conn, $poId)
{
    $rows = getRooftopPoAssignmentActivityRows($conn, $poId);
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
 * Same rules as take-po-action.php: serial by product+serial; regular by product, vehicle, qty, model.
 *
 * @return array{assigned: bool, store_name: string}
 */
function rooftop_po_line_store_assignment_info($conn, $stk, $poVehicalDate, $poVehicalNo)
{
    $empty = ['assigned' => false, 'store_name' => ''];
    $pid = mysqli_real_escape_string($conn, (string) $stk['ProductId']);
    $vd = mysqli_real_escape_string($conn, (string) $poVehicalDate);
    $vn = mysqli_real_escape_string($conn, (string) $poVehicalNo);
    $pt = isset($stk['ProdType']) ? intval($stk['ProdType']) : 0;

    if ($pt === 1 || $pt === 2) {
        $sn = isset($stk['SerialNo']) ? trim((string) $stk['SerialNo']) : '';
        if ($sn === '' || strcasecmp($sn, 'N/A') === 0) {
            return $empty;
        }
        $snEsc = mysqli_real_escape_string($conn, $sn);
        $sql = "SELECT d.id, tb.Name AS StoreName FROM tbl_rooftop_distibute_item_details d LEFT JOIN tbl_rooftop_branch tb ON d.BranchId = tb.id WHERE d.ProductId='$pid' AND d.SerialNo='$snEsc' AND (d.ProdType='1' OR d.ProdType='2' OR d.ProdType=1 OR d.ProdType=2) LIMIT 1";
        $r = getRecord($sql);
        if (is_array($r) && !empty($r['id'])) {
            $name = isset($r['StoreName']) ? trim((string) $r['StoreName']) : '';
            return ['assigned' => true, 'store_name' => $name];
        }
        return $empty;
    }

    $qty = floatval($stk['Qty']);
    if ($qty <= 0) {
        return $empty;
    }
    $mn = isset($stk['ModelNo']) ? trim((string) $stk['ModelNo']) : '';
    $mnEsc = mysqli_real_escape_string($conn, $mn);
    $qtyLit = mysqli_real_escape_string($conn, (string) $qty);
    $qtyClause = "ABS(CAST(d.Qty AS DECIMAL(18,6)) - CAST('$qtyLit' AS DECIMAL(18,6))) < 0.000001";
    $sql = "SELECT d.id, tb.Name AS StoreName FROM tbl_rooftop_distibute_item_details d LEFT JOIN tbl_rooftop_branch tb ON d.BranchId = tb.id WHERE d.ProductId='$pid' AND d.VehicalDate='$vd' AND d.VehicalNo='$vn' AND $qtyClause AND (d.ProdType='0' OR d.ProdType=0 OR d.ProdType IS NULL OR d.ProdType='')";
    if ($mn !== '') {
        $sql .= " AND d.ModelNo='$mnEsc'";
    }
    $r = getRecord($sql);
    if (is_array($r) && !empty($r['id'])) {
        $name = isset($r['StoreName']) ? trim((string) $r['StoreName']) : '';
        return ['assigned' => true, 'store_name' => $name];
    }
    return $empty;
}

/**
 * Detect store assignment from PO stock lines (when no activity log / PO narration marker).
 *
 * @return array{assigned: bool, partial: bool, store_name: string, line_count: int, assigned_line_count: int}
 */
function summarizeRooftopPoStoreAssignFromStockLines($conn, $poId, $poVehicalDate = '', $poVehicalNo = '')
{
    $empty = ['assigned' => false, 'partial' => false, 'store_name' => '', 'line_count' => 0, 'assigned_line_count' => 0];
    $poId = (int) $poId;
    if ($poId < 1) {
        return $empty;
    }
    if ($poVehicalDate === '' && $poVehicalNo === '') {
        $po = getRecord("SELECT VehicalDate, VehicalNo FROM tbl_rooftop_purchase_order WHERE id='$poId' LIMIT 1");
        if (!is_array($po)) {
            return $empty;
        }
        $poVehicalDate = isset($po['VehicalDate']) ? (string) $po['VehicalDate'] : '';
        $poVehicalNo = isset($po['VehicalNo']) ? (string) $po['VehicalNo'] : '';
    }

    $resStk = $conn->query("SELECT * FROM tbl_rooftop_stocks WHERE SellId='$poId' AND SellType='Purchase' ORDER BY id ASC");
    if (!$resStk) {
        return $empty;
    }

    $lineCount = 0;
    $assignedLineCount = 0;
    $storeName = '';
    while ($stk = $resStk->fetch_assoc()) {
        if (floatval($stk['Qty']) <= 0) {
            continue;
        }
        $lineCount++;
        $info = rooftop_po_line_store_assignment_info($conn, $stk, $poVehicalDate, $poVehicalNo);
        if (!empty($info['assigned'])) {
            $assignedLineCount++;
            if ($storeName === '' && !empty($info['store_name'])) {
                $storeName = trim((string) $info['store_name']);
            }
        }
    }
    if ($lineCount < 1) {
        return $empty;
    }

    $allAssigned = ($assignedLineCount === $lineCount);
    $partial = ($assignedLineCount > 0 && !$allAssigned);

    return [
        'assigned' => $allAssigned,
        'partial' => $partial,
        'store_name' => $storeName,
        'line_count' => $lineCount,
        'assigned_line_count' => $assignedLineCount,
    ];
}

/**
 * @param int[] $poIds PO ids still marked unassigned after log / narration checks
 * @return array<int, array{assigned: bool, partial: bool, store_name: string, line_count: int, assigned_line_count: int}>
 */
function buildRooftopPoStoreAssignFromStockLinesMap($conn, array $poIds)
{
    $map = [];
    $ids = [];
    foreach ($poIds as $id) {
        $id = (int) $id;
        if ($id > 0) {
            $ids[$id] = true;
        }
    }
    if (empty($ids)) {
        return $map;
    }

    $idList = implode(',', array_keys($ids));
    $poMeta = [];
    $resPo = $conn->query("SELECT id, VehicalDate, VehicalNo FROM tbl_rooftop_purchase_order WHERE id IN ($idList)");
    if ($resPo) {
        while ($po = $resPo->fetch_assoc()) {
            $poMeta[(int) $po['id']] = $po;
        }
    }

    $stocksByPo = [];
    $resStk = $conn->query("SELECT * FROM tbl_rooftop_stocks WHERE SellId IN ($idList) AND SellType='Purchase' ORDER BY SellId ASC, id ASC");
    if ($resStk) {
        while ($stk = $resStk->fetch_assoc()) {
            if (floatval($stk['Qty']) <= 0) {
                continue;
            }
            $pid = (int) $stk['SellId'];
            if (!isset($stocksByPo[$pid])) {
                $stocksByPo[$pid] = [];
            }
            $stocksByPo[$pid][] = $stk;
        }
    }

    foreach (array_keys($ids) as $poId) {
        if (empty($stocksByPo[$poId])) {
            continue;
        }
        $vd = '';
        $vn = '';
        if (isset($poMeta[$poId])) {
            $vd = isset($poMeta[$poId]['VehicalDate']) ? (string) $poMeta[$poId]['VehicalDate'] : '';
            $vn = isset($poMeta[$poId]['VehicalNo']) ? (string) $poMeta[$poId]['VehicalNo'] : '';
        }
        $lineCount = count($stocksByPo[$poId]);
        $assignedLineCount = 0;
        $storeName = '';
        foreach ($stocksByPo[$poId] as $stk) {
            $info = rooftop_po_line_store_assignment_info($conn, $stk, $vd, $vn);
            if (!empty($info['assigned'])) {
                $assignedLineCount++;
                if ($storeName === '' && !empty($info['store_name'])) {
                    $storeName = trim((string) $info['store_name']);
                }
            }
        }
        if ($assignedLineCount < 1) {
            continue;
        }
        $map[$poId] = [
            'assigned' => ($assignedLineCount === $lineCount),
            'partial' => ($assignedLineCount > 0 && $assignedLineCount < $lineCount),
            'store_name' => $storeName,
            'line_count' => $lineCount,
            'assigned_line_count' => $assignedLineCount,
        ];
    }

    return $map;
}

/**
 * @param int[] $poIds
 * @return array<int, array{assigned: bool, store_name: string, assigned_by: string, assigned_at: string, line_count: int, store_dist_id: int}>
 */
function buildRooftopPoStoreAssignListSummaryMap($conn, array $poIds)
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

    rooftopPoAssignmentActivityEnsureTable($conn);
    $idList = implode(',', array_keys($ids));

    $sqlLog = "SELECT l.PoId, l.StoreDistId, l.StoreName, l.PerformedByName, l.LineCount, l.CreatedDate
        FROM tbl_rooftop_po_assignment_activity_log l
        INNER JOIN (
            SELECT PoId, MAX(id) AS max_id
            FROM tbl_rooftop_po_assignment_activity_log
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
        FROM tbl_rooftop_distibute_items di
        LEFT JOIN tbl_rooftop_branch tb ON tb.id = di.BranchId
        WHERE di.Status = '1' AND di.Narration REGEXP '__ROOFTOPPOID[0-9]+__'
        ORDER BY di.id DESC";
    $resDist = $conn->query($sqlDist);
    if ($resDist) {
        while ($row = $resDist->fetch_assoc()) {
            $poId = parseRooftopPoIdFromStoreDistNarration($row['Narration'] ?? '');
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

    $needStockCheck = [];
    foreach ($map as $poId => $row) {
        if (empty($row['assigned'])) {
            $needStockCheck[] = (int) $poId;
        }
    }
    if (!empty($needStockCheck)) {
        $stockMap = buildRooftopPoStoreAssignFromStockLinesMap($conn, $needStockCheck);
        foreach ($stockMap as $poId => $stkSum) {
            if (!isset($map[$poId])) {
                continue;
            }
            if (!empty($stkSum['assigned'])) {
                $map[$poId] = [
                    'assigned' => true,
                    'store_name' => trim((string) ($stkSum['store_name'] ?? '')),
                    'assigned_by' => '',
                    'assigned_at' => '',
                    'line_count' => (int) ($stkSum['line_count'] ?? 0),
                    'store_dist_id' => 0,
                    'partial' => false,
                ];
            } elseif (!empty($stkSum['partial'])) {
                $map[$poId] = [
                    'assigned' => false,
                    'store_name' => trim((string) ($stkSum['store_name'] ?? '')),
                    'assigned_by' => '',
                    'assigned_at' => '',
                    'line_count' => (int) ($stkSum['assigned_line_count'] ?? 0),
                    'store_dist_id' => 0,
                    'partial' => true,
                    'line_count_total' => (int) ($stkSum['line_count'] ?? 0),
                ];
            }
        }
    }

    return $map;
}

function renderRooftopPoStoreAssignListCellHtml($poId, array $summary)
{
    $poId = (int) $poId;
    if (!empty($summary['partial'])) {
        $store = htmlspecialchars((string) ($summary['store_name'] ?? ''));
        $assignedLines = (int) ($summary['line_count'] ?? 0);
        $totalLines = (int) ($summary['line_count_total'] ?? 0);
        ob_start();
        ?>
<div class="small">
    <span class="badge badge-warning">Partially at store</span>
    <?php if ($store !== '') { ?><div><strong><?php echo $store; ?></strong></div><?php } ?>
    <?php if ($totalLines > 0) { ?><div><?php echo $assignedLines; ?> / <?php echo $totalLines; ?> line(s)</div><?php } ?>
    <button type="button" class="btn btn-link btn-sm p-0 btn-po-assign-history" data-po-id="<?php echo $poId; ?>">View history</button>
</div>
        <?php
        return ob_get_clean();
    }
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

function logRooftopPoDispatchActivityFromStoreDist($conn, $storeDistId, $actionType, $dispatchOfficerId, $performedBy, $lineCount, $remarks)
{
    $storeDistId = (int) $storeDistId;
    if ($storeDistId < 1) {
        return false;
    }
    $h = getRecord("SELECT BranchId, Narration FROM tbl_rooftop_distibute_items WHERE id='$storeDistId' LIMIT 1");
    if (!is_array($h)) {
        return false;
    }
    $poId = parseRooftopPoIdFromStoreDistNarration($h['Narration'] ?? '');
    if ($poId < 1) {
        return false;
    }
    return logRooftopPoAssignmentActivity(
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
