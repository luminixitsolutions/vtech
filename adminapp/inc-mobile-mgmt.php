<?php

function mobileMgmtToday()
{
    return date('Y-m-d');
}

function mobileMgmtStatCard($label, $count, $href, $tone = 'blue', $showCount = true)
{
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $tone = preg_replace('/[^a-z]/', '', $tone);
    $cardClass = $showCount ? 'mob-mgmt-stat-card' : 'mob-mgmt-stat-card mob-mgmt-stat-card--no-count';
    ?>
    <div class="col-6">
        <a href="<?php echo $href; ?>" class="mob-mgmt-stat">
            <div class="<?php echo $cardClass; ?>">
                <div class="mob-mgmt-stat-label"><?php echo $label; ?></div>
                <?php if ($showCount) { ?>
                <div class="mob-mgmt-stat-count tone-<?php echo $tone; ?>"><?php echo number_format($count); ?></div>
                <?php } ?>
            </div>
        </a>
    </div>
    <?php
}

function getStockMgmtCounts()
{
    return array(
        'purchase_orders' => getRow("SELECT id FROM tbl_purchase_order WHERE Status=1"),
        'delivery_challan' => getRow("SELECT id FROM tbl_sell WHERE Status=1"),
    );
}

function getInsuranceMgmtCounts()
{
    require_once __DIR__ . '/../admin/inc-insurance-site.php';
    require_once __DIR__ . '/../admin/inc-insurance-dashboard-data.php';

    $dash = getInsuranceDashboardData();

    return array(
        'pending' => $dash['pending'],
        'active_completed' => $dash['active_completed'],
        'renewal' => $dash['renewal'],
        'expired' => $dash['expired'],
        'site_dispatched' => $dash['site_dispatched'],
        'total_completed' => $dash['total_completed'],
    );
}

function getServiceMgmtCounts()
{
    $today = mobileMgmtToday();

    return array(
        'total' => getRow("SELECT id FROM tbl_service_complaint"),
        'today' => getRow("SELECT id FROM tbl_service_complaint WHERE CreatedDate='$today'"),
        'pending' => getRow("SELECT id FROM tbl_service_complaint WHERE ClainStatus<>'Close'"),
        'closed' => getRow("SELECT id FROM tbl_service_complaint WHERE ClainStatus='Close'"),
        'insurance' => getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance'"),
        'maintenance' => getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType<>'Insurance' OR ServiceType IS NULL OR ServiceType=''"),
    );
}

function mobileMgmtInsuranceStatusCondition($status)
{
    require_once __DIR__ . '/../admin/inc-insurance-site.php';

    switch ($status) {
        case 'active':
            return insuranceSiteActiveCompletedSqlCondition();
        case 'renewal':
            return insuranceSiteRenewalSqlCondition();
        case 'expired':
            return insuranceSiteExpiredSqlCondition();
        case 'completed':
            return insuranceSiteCompletedSqlCondition();
        case 'dispatched':
            return insuranceSiteBaseEligibleSqlCondition();
        case 'pending':
        default:
            return insuranceSitePendingSqlCondition();
    }
}

function mobileMgmtInsuranceStatusLabel($status)
{
    $labels = array(
        'pending' => 'Pending Insurance',
        'active' => 'Active Completed',
        'renewal' => 'Upcoming Renewal',
        'expired' => 'Expired Insurance',
        'completed' => 'Total Completed',
        'dispatched' => 'Site Dispatched',
    );

    return isset($labels[$status]) ? $labels[$status] : 'Insurance List';
}

function mobileMgmtFormatDate($value)
{
    if (empty($value) || $value === '0000-00-00') {
        return '-';
    }

    $ts = strtotime(str_replace('-', '/', $value));
    if (!$ts) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    return date('d/m/Y', $ts);
}

function mobileMgmtProjectTypeLabel($projectType)
{
    return ((int) $projectType === 2) ? 'Rooftop' : 'Pump';
}

function mobileMgmtQueryRows($sql)
{
    global $conn;
    $rows = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function mobileMgmtPurchaseOrderStatusLabel($row)
{
    if ((int) ($row['DeliveredStatus'] ?? 0) === 1) {
        return 'Order Delivered';
    }
    if ((int) ($row['ReceiveStatus'] ?? 0) === 1) {
        return 'Order Received';
    }
    if ((int) ($row['SendStatus'] ?? 0) === 1) {
        return 'Order Sent';
    }
    if ((int) ($row['ApplyStatus'] ?? 0) === 1) {
        return 'Order Applied';
    }

    return 'Apply Order';
}

function mobileMgmtGetPurchaseOrders($filters = array())
{
    global $conn;

    $sql = "SELECT ts.id, ts.InvoiceNo, ts.InvoiceDate, ts.CustName, ts.CellNo, ts.GrossAmt, ts.SubTotal,
            ts.Discount, ts.Total, ts.DeliveryDate, ts.DeliveredStatus, ts.ReceiveStatus, ts.SendStatus, ts.ApplyStatus,
            tu.Fname AS CompanyName
        FROM tbl_purchase_order ts
        LEFT JOIN tbl_users tu ON ts.CompId = tu.id
        WHERE ts.Status=1 AND ts.BagOrder=0";

    if (!empty($filters['comp_id']) && (int) $filters['comp_id'] > 0) {
        $sql .= " AND ts.CompId='" . (int) $filters['comp_id'] . "'";
    }
    if (!empty($filters['cust_id']) && (int) $filters['cust_id'] > 0) {
        $sql .= " AND ts.CustId='" . (int) $filters['cust_id'] . "'";
    }
    if (!empty($filters['from_date'])) {
        $from = mysqli_real_escape_string($conn, (string) $filters['from_date']);
        $sql .= " AND ts.InvoiceDate>='$from'";
    }
    if (!empty($filters['to_date'])) {
        $to = mysqli_real_escape_string($conn, (string) $filters['to_date']);
        $sql .= " AND ts.InvoiceDate<='$to'";
    }

    $today = mobileMgmtToday();
    $sql .= " ORDER BY (ts.InvoiceDate = '$today') DESC, ts.id DESC";

    return mobileMgmtQueryRows($sql);
}

function mobileMgmtSplitPurchaseOrdersByToday($rows)
{
    $today = mobileMgmtToday();
    $todayRows = array();
    $otherRows = array();

    foreach ($rows as $row) {
        $invoiceDate = isset($row['InvoiceDate']) ? trim((string) $row['InvoiceDate']) : '';
        if ($invoiceDate !== '' && $invoiceDate !== '0000-00-00' && substr($invoiceDate, 0, 10) === $today) {
            $todayRows[] = $row;
        } else {
            $otherRows[] = $row;
        }
    }

    return array(
        'today' => $todayRows,
        'other' => $otherRows,
    );
}

function mobileMgmtFormatAmount($value)
{
    if ($value === '' || $value === null) {
        return '-';
    }

    return number_format((float) $value, 2);
}

function mobileMgmtGetProjects()
{
    return mobileMgmtQueryRows("SELECT id, Name FROM tbl_common_master WHERE Status=1 AND Roll=24 ORDER BY Name ASC");
}

function mobileMgmtGetSubHeadsForProject($projectId)
{
    $projectId = (int) $projectId;
    if ($projectId < 1) {
        return array();
    }

    return mobileMgmtQueryRows("SELECT id, Name FROM tbl_project_sub_head WHERE Status=1 AND UnderBy='$projectId' ORDER BY Name ASC");
}

function mobileMgmtGetSubHeadsByProjectMap($projects)
{
    $map = array();
    foreach ($projects as $project) {
        $pid = (int) $project['id'];
        $map[$pid] = array();
        foreach (mobileMgmtGetSubHeadsForProject($pid) as $subHead) {
            $map[$pid][] = array(
                'id' => (int) $subHead['id'],
                'name' => (string) $subHead['Name'],
            );
        }
    }

    return $map;
}

function mobileMgmtGetDeliveryChallanSummary($filters = array())
{
    global $conn;

    $sql = "SELECT psh.id AS sub_head_id, psh.Name AS sub_head_name, psh.UnderBy AS project_id,
            proj.Name AS project_name,
            COALESCE(dc.cnt, 0) AS total_delivery_challan,
            COALESCE(md.cnt, 0) AS total_material_dispatch
        FROM tbl_project_sub_head psh
        INNER JOIN tbl_common_master proj ON proj.id = psh.UnderBy
        LEFT JOIN (
            SELECT tu.ProjectId, tu.ProjectSubHeadId, COUNT(DISTINCT ts.CustId) AS cnt
            FROM tbl_sell ts
            INNER JOIN tbl_users tu ON tu.id = ts.CustId
            WHERE ts.SellType = 'Challan' AND tu.ProjectType = 1
            GROUP BY tu.ProjectId, tu.ProjectSubHeadId
        ) dc ON dc.ProjectId = psh.UnderBy AND dc.ProjectSubHeadId = psh.id
        LEFT JOIN (
            SELECT tu.ProjectId, tu.ProjectSubHeadId, COUNT(DISTINCT tu.id) AS cnt
            FROM tbl_sell ts
            INNER JOIN tbl_users tu ON tu.id = ts.CustId
            WHERE tu.ProjectType = 1
            GROUP BY tu.ProjectId, tu.ProjectSubHeadId
        ) md ON md.ProjectId = psh.UnderBy AND md.ProjectSubHeadId = psh.id
        WHERE psh.Status = 1 AND proj.Status = 1 AND proj.Roll = 24";

    if (!empty($filters['project_id']) && (int) $filters['project_id'] > 0) {
        $sql .= " AND psh.UnderBy='" . (int) $filters['project_id'] . "'";
    }
    if (!empty($filters['sub_head_ids']) && is_array($filters['sub_head_ids'])) {
        $subHeadIds = array_values(array_filter(array_map('intval', $filters['sub_head_ids']), function ($id) {
            return $id > 0;
        }));
        if (!empty($subHeadIds)) {
            $sql .= ' AND psh.id IN (' . implode(',', $subHeadIds) . ')';
        }
    } elseif (!empty($filters['sub_head_id']) && (int) $filters['sub_head_id'] > 0) {
        $sql .= " AND psh.id='" . (int) $filters['sub_head_id'] . "'";
    }

    $sql .= " ORDER BY proj.Name ASC, psh.Name ASC";

    $rows = mobileMgmtQueryRows($sql);
    $totDelivery = 0;
    $totDispatch = 0;
    $totBalance = 0;

    foreach ($rows as &$row) {
        $delivery = (int) $row['total_delivery_challan'];
        $dispatch = (int) $row['total_material_dispatch'];
        $balance = $delivery - $dispatch;
        $row['balance_challan'] = $balance;
        $totDelivery += $delivery;
        $totDispatch += $dispatch;
        $totBalance += $balance;
    }
    unset($row);

    return array(
        'rows' => $rows,
        'tot_delivery_challan' => $totDelivery,
        'tot_material_dispatch' => $totDispatch,
        'tot_balance_challan' => $totBalance,
    );
}

function serviceAbstractMobileListUrl($row, $filters, $filter = '')
{
    require_once __DIR__ . '/../admin/inc-service-abstract-data.php';

    $url = serviceAbstractListUrl($row, $filters, $filter);

    return preg_replace('#^view-maintenance\.php#', 'mobile-service-list.php', $url);
}

function mobileServiceAbstractListTitle($filters, $filter, $rowLabel = '')
{
    $parts = array('Service Complaints');

    if ($rowLabel !== '' && strtoupper($rowLabel) !== 'TOTAL') {
        $parts[] = $rowLabel;
    }

    if ($filter === 'closed') {
        $parts[] = 'Closed';
    } elseif ($filter === 'today') {
        $parts[] = 'Today Added';
    } elseif ($filter === 'material') {
        $parts[] = 'Material Hold';
    } elseif ($filter === 'pending') {
        $parts[] = 'Pending';
    }

    return implode(' — ', $parts);
}
