<?php
/**
 * Shared dispatch-officer stock queries (transfer to store, stock location report).
 */

function itemTransferStockMeta($conn)
{
    $hasTbl = false;
    $hasCol = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
    if ($t1 && $t1->num_rows > 0) {
        $hasTbl = true;
        $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
        if ($c && $c->num_rows > 0) {
            $hasCol = true;
        }
    }
    return array('has_transfer_tbl' => $hasTbl, 'has_detail2_col' => $hasCol);
}

function itemTransferOpenTransferJoinSql($meta, $alias = 'd2')
{
    if (empty($meta['has_transfer_tbl']) || empty($meta['has_detail2_col'])) {
        return '';
    }
    return "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = {$alias}.id";
}

function itemTransferOpenTransferWhereSql($meta)
{
    if (empty($meta['has_transfer_tbl']) || empty($meta['has_detail2_col'])) {
        return '';
    }
    return 'AND td_open.Detail2Id IS NULL';
}

/**
 * Latest available serial line per serial no for one dispatch officer (transfer to store list).
 */
function itemTransferDispatchSerialListSql($officerId, $meta)
{
    $officerId = (int) $officerId;
    $join = itemTransferOpenTransferJoinSql($meta, 'd2');
    $whereOpen = itemTransferOpenTransferWhereSql($meta);

    return "SELECT d2.id, d2.ProductName, d2.SerialNo
        FROM tbl_distibute_item_details2 d2
        INNER JOIN (
            SELECT TRIM(SerialNo) AS sn, MAX(d2m.id) AS max_id
            FROM tbl_distibute_item_details2 d2m
            WHERE d2m.StoreExeId = '$officerId'
              AND d2m.ProdType IN (1,2)
              AND d2m.SerialNo IS NOT NULL AND TRIM(d2m.SerialNo) <> '' AND d2m.SerialNo <> 'N/A'
            GROUP BY TRIM(SerialNo)
        ) cur ON cur.max_id = d2.id
        $join
        WHERE 1=1 $whereOpen
        AND NOT EXISTS (
            SELECT 1 FROM tbl_stocks sx
            WHERE sx.CrDr = 'dr' AND sx.ProdType = 1
              AND TRIM(sx.SerialNo) = TRIM(d2.SerialNo)
        )
        ORDER BY d2.ProductName, TRIM(d2.SerialNo)";
}

function itemTransferDispatchReservedSerialCount($conn, $officerId)
{
    $meta = itemTransferStockMeta($conn);
    if (empty($meta['has_transfer_tbl']) || empty($meta['has_detail2_col'])) {
        return 0;
    }
    $officerId = (int) $officerId;
    $row = getRecord("SELECT COUNT(DISTINCT d2.id) AS c
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_dispatch_to_store_transfer_details td ON td.Detail2Id = d2.id
        INNER JOIN tbl_dispatch_to_store_transfer t ON t.id = td.TransferId AND t.DispatchOfficerId = '$officerId'
        WHERE d2.ProdType IN (1,2)
          AND d2.SerialNo IS NOT NULL AND TRIM(d2.SerialNo) <> '' AND d2.SerialNo <> 'N/A'");
    return (int) ($row['c'] ?? 0);
}

/**
 * Who currently holds a serial (latest officer line, same rules as location report).
 *
 * @return array<string, mixed>|null
 */
function itemTransferSerialCurrentOfficerHolder($conn, $serialNo)
{
    $serialNo = trim((string) $serialNo);
    if ($serialNo === '') {
        return null;
    }
    $meta = itemTransferStockMeta($conn);
    $join = itemTransferOpenTransferJoinSql($meta, 'd2');
    $whereOpen = itemTransferOpenTransferWhereSql($meta);
    $esc = $conn->real_escape_string($serialNo);

    $sql = "SELECT d2.id, d2.StoreExeId, u.Fname AS officer_name, d2.BranchId, b.Name AS branch_name
        FROM tbl_distibute_item_details2 d2
        $join
        INNER JOIN (
            SELECT TRIM(SerialNo) AS sn, MAX(d2m.id) AS max_id
            FROM tbl_distibute_item_details2 d2m
            WHERE d2m.ProdType IN (1,2) AND d2m.StoreExeId > 0
              AND TRIM(d2m.SerialNo) = '$esc'
            GROUP BY TRIM(SerialNo)
        ) cur ON cur.max_id = d2.id
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        WHERE 1=1 $whereOpen
        AND NOT EXISTS (
            SELECT 1 FROM tbl_stocks sx
            WHERE sx.CrDr = 'dr' AND sx.ProdType = 1 AND TRIM(sx.SerialNo) = TRIM(d2.SerialNo)
        )
        LIMIT 1";
    $row = getRecord($sql);
    return is_array($row) && !empty($row['id']) ? $row : null;
}
