<?php

/**
 * Access helpers for admin/item_transfer_workflow/*.php
 */

function itemTransferWorkflowUserContext($userId)
{
    static $cache = [];

    $userId = (int) $userId;
    if (isset($cache[$userId])) {
        return $cache[$userId];
    }

    $row = getRecord("SELECT Roll, BranchId, MulBranchId, Options FROM tbl_users WHERE id='$userId'");
    $options = [];
    if (!empty($row['Options'])) {
        $options = array_values(array_filter(array_map('trim', explode(',', (string) $row['Options']))));
    }

    $cache[$userId] = [
        'roll' => (int) ($row['Roll'] ?? 0),
        'branch_id' => (int) ($row['BranchId'] ?? 0),
        'mul_branch_id' => $row['MulBranchId'] ?? '0',
        'options' => $options,
    ];

    return $cache[$userId];
}

function itemTransferWorkflowHasScreen(array $options, $screenId)
{
    if (!function_exists('menuAccessUserHasScreen')) {
        return false;
    }

    return menuAccessUserHasScreen($options, (int) $screenId);
}

function itemTransferWorkflowCanAccessDispatch($roll, array $options)
{
    if (in_array((int) $roll, [1, 7, 26], true)) {
        return true;
    }

    foreach ([237, 238, 239, 165] as $screenId) {
        if (itemTransferWorkflowHasScreen($options, $screenId)) {
            return true;
        }
    }

    return false;
}

function itemTransferWorkflowCanAccessStore($roll, array $options)
{
    if (in_array((int) $roll, [1, 7, 27], true)) {
        return true;
    }

    foreach ([240, 241, 242, 166] as $screenId) {
        if (itemTransferWorkflowHasScreen($options, $screenId)) {
            return true;
        }
    }

    return false;
}

function itemTransferWorkflowCanAccessStockReport($roll, array $options)
{
    if (in_array((int) $roll, [1, 7, 26, 27], true)) {
        return true;
    }

    if (itemTransferWorkflowCanAccessDispatch($roll, $options) || itemTransferWorkflowCanAccessStore($roll, $options)) {
        return true;
    }

    return itemTransferWorkflowHasScreen($options, 183);
}

function itemTransferWorkflowDeny($message = 'Access denied.', $isJson = false)
{
    if ($isJson) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'message' => $message]);
        exit;
    }

    $msg = addslashes($message);
    echo "<script>alert('{$msg}'); window.location.href='../dashboard.php';</script>";
    exit;
}

function itemTransferWorkflowIsDispatchOfficerSelf($roll)
{
    return (int) $roll === 26;
}

function itemTransferWorkflowIsStoreTransferAdmin($roll)
{
    return in_array((int) $roll, [1, 7], true);
}

/** Branch ids a store user may transfer stock from (primary + MulBranchId). */
function itemTransferWorkflowAllowedFromBranchIds($branchId, $mulBranchId)
{
    $ids = [];
    $branchId = (int) $branchId;
    if ($branchId > 0) {
        $ids[] = $branchId;
    }

    $mul = trim((string) $mulBranchId);
    if ($mul !== '' && $mul !== '0') {
        foreach (explode(',', $mul) as $part) {
            $id = (int) trim($part);
            if ($id > 0 && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }
    }

    return $ids;
}

function itemTransferWorkflowListFromStoreBranches($conn, $roll, $branchId, $mulBranchId)
{
    if (itemTransferWorkflowIsStoreTransferAdmin($roll)) {
        $res = $conn->query("SELECT id, Name FROM tbl_branch WHERE Status='1' ORDER BY Name ASC");
    } else {
        $ids = itemTransferWorkflowAllowedFromBranchIds($branchId, $mulBranchId);
        if (empty($ids)) {
            return [];
        }
        $idList = implode(',', array_map('intval', $ids));
        $res = $conn->query("SELECT id, Name FROM tbl_branch WHERE Status='1' AND id IN ($idList) ORDER BY Name ASC");
    }

    $list = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $list[] = $row;
        }
    }

    return $list;
}

function itemTransferWorkflowResolveFromBranchId($roll, $branchId, $mulBranchId, $requestedId)
{
    $requestedId = (int) $requestedId;
    $branchId = (int) $branchId;

    if (itemTransferWorkflowIsStoreTransferAdmin($roll)) {
        if ($requestedId > 0) {
            return $requestedId;
        }
        return $branchId > 0 ? $branchId : 0;
    }

    $allowed = itemTransferWorkflowAllowedFromBranchIds($branchId, $mulBranchId);
    if (empty($allowed)) {
        return 0;
    }
    if ($requestedId > 0 && in_array($requestedId, $allowed, true)) {
        return $requestedId;
    }
    if ($branchId > 0 && in_array($branchId, $allowed, true)) {
        return $branchId;
    }

    return (int) $allowed[0];
}

function itemTransferWorkflowValidateFromBranchId($roll, $branchId, $mulBranchId, $fromBranchId)
{
    $fromBranchId = (int) $fromBranchId;
    if ($fromBranchId <= 0) {
        return false;
    }

    if (itemTransferWorkflowIsStoreTransferAdmin($roll)) {
        $row = getRecord("SELECT id FROM tbl_branch WHERE id='$fromBranchId' AND Status='1' LIMIT 1");
        return !empty($row);
    }

    return in_array($fromBranchId, itemTransferWorkflowAllowedFromBranchIds($branchId, $mulBranchId), true);
}

/** Dispatch officers (Roll 26) for transfer-from dropdown; optional filter by destination store branch. */
function itemTransferWorkflowListDispatchOfficers($conn, $roll, $userBranchId, $toBranchId = 0)
{
    $toBranchId = (int) $toBranchId;
    $userBranchId = (int) $userBranchId;

    if (in_array((int) $roll, [1, 7], true)) {
        if ($toBranchId > 0) {
            $bid = $toBranchId;
            $sql = "SELECT id, Fname, Phone FROM tbl_users WHERE Status='1' AND Roll=26
                AND (BranchId='$bid' OR FIND_IN_SET('$bid', REPLACE(IFNULL(TRIM(MulBranchId),''),' ','')))
                ORDER BY Fname ASC";
        } else {
            $sql = "SELECT id, Fname, Phone FROM tbl_users WHERE Status='1' AND Roll=26 ORDER BY Fname ASC";
        }
    } else {
        $bid = $userBranchId;
        if ($bid < 1) {
            return [];
        }
        $sql = "SELECT id, Fname, Phone FROM tbl_users WHERE Status='1' AND Roll=26
            AND (BranchId='$bid' OR FIND_IN_SET('$bid', REPLACE(IFNULL(TRIM(MulBranchId),''),' ','')))
            ORDER BY Fname ASC";
    }

    $list = getList($sql);

    return is_array($list) ? $list : [];
}

function itemTransferWorkflowResolveDispatchOfficerId($conn, $roll, $sessionUserId, $requestedId)
{
    $sessionUserId = (int) $sessionUserId;
    $requestedId = (int) $requestedId;

    if (itemTransferWorkflowIsDispatchOfficerSelf($roll)) {
        return $sessionUserId;
    }

    if ($requestedId <= 0) {
        return 0;
    }

    $row = getRecord("SELECT id FROM tbl_users WHERE id='$requestedId' AND Status='1' AND Roll=26 LIMIT 1");

    return $row ? (int) $row['id'] : 0;
}
