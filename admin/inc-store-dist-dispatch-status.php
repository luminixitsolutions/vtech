<?php
/**
 * Detect whether a store distribute batch (tbl_distibute_items.id) was handed to dispatch.
 */

function storeDistDispatchEnsureLogTable($conn)
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;
    $sql = "CREATE TABLE IF NOT EXISTS tbl_store_dist_dispatch_log (
      id INT(11) NOT NULL AUTO_INCREMENT,
      StoreDistId INT(11) NOT NULL,
      DispatchDist2Id INT(11) NOT NULL DEFAULT 0,
      ActionType VARCHAR(20) NOT NULL,
      DispatchOfficerId INT(11) NOT NULL DEFAULT 0,
      PerformedBy INT(11) NOT NULL,
      PerformedByName VARCHAR(255) DEFAULT NULL,
      DispatchOfficerName VARCHAR(255) DEFAULT NULL,
      Remarks VARCHAR(500) DEFAULT NULL,
      CreatedDate DATETIME NOT NULL,
      PRIMARY KEY (id),
      KEY StoreDistId (StoreDistId),
      KEY CreatedDate (CreatedDate)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
}

/**
 * @param string $actionType assign|revert
 */
function logStoreDistDispatchAction($conn, $storeDistId, $dispatchDist2Id, $actionType, $dispatchOfficerId, $performedBy, $remarks = '')
{
    storeDistDispatchEnsureLogTable($conn);
    $storeDistId = (int) $storeDistId;
    $dispatchDist2Id = (int) $dispatchDist2Id;
    $dispatchOfficerId = (int) $dispatchOfficerId;
    $performedBy = (int) $performedBy;
    $actionType = strtolower(trim((string) $actionType));
    if (!in_array($actionType, ['assign', 'revert'], true)) {
        return false;
    }
    $performerName = '';
    if ($performedBy > 0) {
        $pu = getRecord("SELECT Fname FROM tbl_users WHERE id='$performedBy' LIMIT 1");
        if (is_array($pu) && !empty($pu['Fname'])) {
            $performerName = trim((string) $pu['Fname']);
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
    $officerEsc = mysqli_real_escape_string($conn, $officerName);
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO tbl_store_dist_dispatch_log SET
        StoreDistId='$storeDistId',
        DispatchDist2Id='$dispatchDist2Id',
        ActionType='$actionType',
        DispatchOfficerId='$dispatchOfficerId',
        PerformedBy='$performedBy',
        PerformedByName='$performerEsc',
        DispatchOfficerName='$officerEsc',
        Remarks='$remarksEsc',
        CreatedDate='$now'";
    return (bool) $conn->query($sql);
}

/**
 * @return array{ok: bool, error?: string}
 */
function revertStoreDistDispatchAssignment($conn, $storeDistId, $performedBy)
{
    $storeDistId = (int) $storeDistId;
    $performedBy = (int) $performedBy;
    if ($storeDistId < 1) {
        return ['ok' => false, 'error' => 'Invalid assignment.'];
    }
    $assignment = getStoreDistDispatchAssignment($conn, $storeDistId);
    if ($assignment === null) {
        return ['ok' => false, 'error' => 'This assignment is not with a dispatch officer.'];
    }
    $h2Id = (int) $assignment['id'];
    $storeExeId = (int) ($assignment['StoreExeId'] ?? 0);
    $officerName = isset($assignment['OfficerName']) ? (string) $assignment['OfficerName'] : '';

    $conn->begin_transaction();
    try {
        if (!$conn->query("DELETE FROM tbl_distibute_item_details2 WHERE DistId='$h2Id'")) {
            throw new Exception($conn->error);
        }
        if (!$conn->query("DELETE FROM tbl_distibute_items2 WHERE id='$h2Id'")) {
            throw new Exception($conn->error);
        }
        $remarks = 'Reverted to store';
        if ($officerName !== '') {
            $remarks .= ' (was: ' . $officerName . ')';
        }
        if (!logStoreDistDispatchAction($conn, $storeDistId, $h2Id, 'revert', $storeExeId, $performedBy, $remarks)) {
            throw new Exception('Could not write activity log.');
        }
        $conn->commit();
        return ['ok' => true];
    } catch (Exception $e) {
        $conn->rollback();
        return ['ok' => false, 'error' => $e->getMessage()];
    }
}

/**
 * @return int[]
 */
function parseStoreDistIdsFromDispatchNarration($narration)
{
    $narration = (string) $narration;
    $pos = stripos($narration, 'DistId(s):');
    if ($pos === false) {
        return [];
    }
    $part = trim(substr($narration, $pos + strlen('DistId(s):')));
    $part = str_replace(["\t", ' '], '', $part);
    if ($part === '') {
        return [];
    }
    $ids = [];
    foreach (explode(',', $part) as $chunk) {
        $id = (int) $chunk;
        if ($id > 0) {
            $ids[] = $id;
        }
    }
    return array_values(array_unique($ids));
}

/**
 * Only explicit store-list handoffs count (not serial overlap with older dispatch stock).
 *
 * @param int[] $storeDistIds
 * @return array<int, string>
 */
function buildStoreDistDispatchOfficerMap($conn, array $storeDistIds = [])
{
    $map = [];
    $idFilter = [];
    foreach ($storeDistIds as $id) {
        $id = (int) $id;
        if ($id > 0) {
            $idFilter[$id] = true;
        }
    }

    $sqlNarr = "SELECT h2.Narration, u2.Fname AS OfficerName
        FROM tbl_distibute_items2 h2
        LEFT JOIN tbl_users u2 ON u2.id = h2.StoreExeId AND u2.Status = '1'
        WHERE h2.Status = '1'
          AND h2.StoreExeId > 0
          AND h2.Narration LIKE '%Dispatch handoff from store assign%'
          AND h2.Narration LIKE '%DistId(s):%'
        ORDER BY h2.id DESC";
    $resNarr = $conn->query($sqlNarr);
    if ($resNarr) {
        while ($row = $resNarr->fetch_assoc()) {
            $name = isset($row['OfficerName']) ? trim((string) $row['OfficerName']) : '';
            foreach (parseStoreDistIdsFromDispatchNarration($row['Narration'] ?? '') as $storeDistId) {
                if (!empty($idFilter) && !isset($idFilter[$storeDistId])) {
                    continue;
                }
                if (!isset($map[$storeDistId])) {
                    $map[$storeDistId] = $name !== '' ? $name : 'Dispatch officer';
                }
            }
        }
    }

    return $map;
}

/**
 * @param int[] $storeDistIds empty = all DistIds (avoid on large lists)
 * @return array<int, float>
 */
function buildStoreDistTotQtyMap($conn, array $storeDistIds = [])
{
    $map = [];
    $ids = [];
    foreach ($storeDistIds as $id) {
        $id = (int) $id;
        if ($id > 0) {
            $ids[] = $id;
        }
    }
    if (empty($ids)) {
        return $map;
    }
    $idList = implode(',', $ids);
    $res = $conn->query("SELECT DistId, SUM(Qty) AS TotQty FROM tbl_distibute_item_details WHERE DistId IN ($idList) GROUP BY DistId");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $map[(int) $row['DistId']] = (float) ($row['TotQty'] ?? 0);
        }
    }
    return $map;
}

/**
 * @return array{id: int, StoreExeId: int, OfficerName: string}|null
 */
function getStoreDistDispatchAssignment($conn, $storeDistId)
{
    $id = (int) $storeDistId;
    if ($id < 1) {
        return null;
    }

    $sqlNarr = "SELECT h2.id, h2.StoreExeId, u2.Fname AS OfficerName
        FROM tbl_distibute_items2 h2
        LEFT JOIN tbl_users u2 ON u2.id = h2.StoreExeId AND u2.Status = '1'
        WHERE h2.Status = '1'
          AND h2.StoreExeId > 0
          AND h2.Narration LIKE '%Dispatch handoff from store assign%'
          AND h2.Narration LIKE '%DistId(s):%'
          AND FIND_IN_SET(
            '$id',
            REPLACE(REPLACE(TRIM(SUBSTRING_INDEX(h2.Narration, 'DistId(s):', -1)), ' ', ''), '\t', '')
          ) > 0
        ORDER BY h2.id DESC
        LIMIT 1";
    $row = getRecord($sqlNarr);
    if (!is_array($row) || empty($row['id'])) {
        return null;
    }
    $name = isset($row['OfficerName']) ? trim((string) $row['OfficerName']) : '';
    return [
        'id' => (int) $row['id'],
        'StoreExeId' => (int) ($row['StoreExeId'] ?? 0),
        'OfficerName' => $name !== '' ? $name : 'Dispatch officer',
    ];
}

function isStoreDistAssignedToDispatch($conn, $storeDistId)
{
    return getStoreDistDispatchAssignment($conn, $storeDistId) !== null;
}
