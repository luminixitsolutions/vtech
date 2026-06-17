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
