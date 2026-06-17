<?php

/**
 * Filtered delivery challan list (service replacement / partial material).
 *
 * Expects $challanListFilter = 'service'|'partial' and sets page variables.
 */

function viewFilteredChallanConfig($mode)
{
    if ($mode === 'partial') {
        return [
            'mode' => 'partial',
            'page' => 'View-Partial-Material-Challan',
            'title' => 'Partial Material Challan List',
            'subtitle' => 'Partial Material Dispatch challans only',
            'self_url' => 'view-partial-material-challans.php',
            'type_column' => 'Material Dispatch Status',
            'type_label' => 'Partial Material Dispatch',
            'sql_filter' => 'ts.MaterialDispatchStatus = 2',
            'cust_filter' => 'ts.MaterialDispatchStatus = 2',
        ];
    }

    return [
        'mode' => 'service',
        'page' => 'View-Service-Challan',
        'title' => 'Service Challan List',
        'subtitle' => 'Service Replacement Challan only',
        'self_url' => 'view-service-challans.php',
        'type_column' => 'Challan Type',
        'type_label' => 'Service Replacement Challan',
        'sql_filter' => 'ts.ChallanType = 2',
        'cust_filter' => 'ts.ChallanType = 2',
    ];
}

function viewFilteredChallanBaseSql($cfg, $roll, $userId, $branchId)
{
    $filter = $cfg['sql_filter'];
    if ($roll == 1 || $roll == 7) {
        return "SELECT ts.*, tb.Name AS Branch, tu.ProjectId, tu.ProjectSubHeadId
            FROM tbl_sell ts
            LEFT JOIN tbl_branch tb ON ts.BranchId = tb.id
            LEFT JOIN tbl_users tu ON ts.CustId = tu.id
            WHERE ts.Status = 1 AND ts.SellType = 'Challan' AND {$filter}";
    }
    if ($roll == 26) {
        $storeId = (int) ($_SESSION['storeid'] ?? 0);
        return "SELECT ts.*, tb.Name AS Branch, tu.ProjectId, tu.ProjectSubHeadId
            FROM tbl_sell ts
            LEFT JOIN tbl_branch tb ON ts.BranchId = tb.id
            LEFT JOIN tbl_users tu ON ts.CustId = tu.id
            WHERE ts.Status = 1 AND ts.SellType = 'Challan' AND {$filter}
            AND ts.BranchId='{$storeId}' AND ts.CreatedBy='" . (int) $userId . "'";
    }
    if ($roll == 27) {
        return "SELECT ts.*, tb.Name AS Branch, tu.ProjectId, tu.ProjectSubHeadId
            FROM tbl_sell ts
            LEFT JOIN tbl_branch tb ON ts.BranchId = tb.id
            LEFT JOIN tbl_users tu ON ts.CustId = tu.id
            WHERE ts.Status = 1 AND ts.SellType = 'Challan' AND {$filter}
            AND ts.BranchId='" . (int) $branchId . "'";
    }

    return "SELECT ts.*, tb.Name AS Branch, tu.ProjectId, tu.ProjectSubHeadId
        FROM tbl_sell ts
        LEFT JOIN tbl_branch tb ON ts.BranchId = tb.id
        LEFT JOIN tbl_users tu ON ts.CustId = tu.id
        WHERE ts.Status = 1 AND ts.SellType = 'Challan' AND {$filter}
        AND ts.CreatedBy='" . (int) $userId . "'";
}

function viewFilteredChallanCustomerSql($cfg, $roll, $userId)
{
    $filter = $cfg['cust_filter'];
    if ($roll == 1 || $roll == 7) {
        return "SELECT DISTINCT tu.id, tu.Fname FROM tbl_sell ts
            INNER JOIN tbl_users tu ON tu.id = ts.CustId
            WHERE ts.Status = 1 AND {$filter} AND tu.Roll = 5
            ORDER BY tu.Fname ASC";
    }
    if ($roll == 26) {
        $storeId = (int) ($_SESSION['storeid'] ?? 0);
        return "SELECT DISTINCT tu.id, tu.Fname FROM tbl_sell ts
            INNER JOIN tbl_users tu ON tu.id = ts.CustId
            WHERE ts.Status = 1 AND {$filter} AND tu.Roll = 5
            AND ts.BranchId='{$storeId}'
            ORDER BY tu.Fname ASC";
    }

    return "SELECT DISTINCT tu.id, tu.Fname FROM tbl_sell ts
        INNER JOIN tbl_users tu ON tu.id = ts.CustId
        WHERE ts.Status = 1 AND {$filter} AND ts.CreatedBy='" . (int) $userId . "'
        AND tu.Roll = 5 ORDER BY tu.Fname ASC";
}
