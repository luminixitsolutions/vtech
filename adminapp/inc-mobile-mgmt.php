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

function getInsuranceMgmtProjectSubHeadSummary($filters = array())
{
    require_once __DIR__ . '/../admin/inc-insurance-site.php';

    insuranceEnsureHistoryTable();

    $from = insuranceSiteInsuranceFromSqlCore();
    $base = insuranceSiteBaseEligibleSqlCondition();
    $pendingCond = insuranceSitePendingSqlCondition();
    $completedCond = insuranceSiteActiveCompletedSqlCondition();
    $renewalCond = insuranceSiteRenewalSqlCondition();
    $expiredCond = insuranceSiteExpiredSqlCondition();
    $renewedCond = insuranceSiteRenewedSqlCondition();

    $sql = "SELECT proj.id AS project_id, proj.Name AS project_name,
            psh.id AS sub_head_id, psh.Name AS sub_head_name,
            COALESCE(ins.total_insurance, 0) AS total_insurance,
            COALESCE(ins.pending, 0) AS pending,
            COALESCE(ins.completed, 0) AS completed,
            COALESCE(ins.renewal, 0) AS renewal,
            COALESCE(ins.expired, 0) AS expired,
            COALESCE(ins.renewed, 0) AS renewed
        FROM tbl_project_sub_head psh
        INNER JOIN tbl_common_master proj ON proj.id = psh.UnderBy
        LEFT JOIN (
            SELECT tu.ProjectId, tu.ProjectSubHeadId,
                COUNT(DISTINCT tdo.CustId) AS total_insurance,
                COUNT(DISTINCT CASE WHEN ($pendingCond) THEN tdo.CustId END) AS pending,
                COUNT(DISTINCT CASE WHEN ($completedCond) THEN tdo.CustId END) AS completed,
                COUNT(DISTINCT CASE WHEN ($renewalCond) THEN tdo.CustId END) AS renewal,
                COUNT(DISTINCT CASE WHEN ($expiredCond) THEN tdo.CustId END) AS expired,
                COUNT(DISTINCT CASE WHEN ($renewedCond) THEN tdo.CustId END) AS renewed
            $from
            WHERE $base
            GROUP BY tu.ProjectId, tu.ProjectSubHeadId
        ) ins ON ins.ProjectId = psh.UnderBy AND ins.ProjectSubHeadId = psh.id
        WHERE psh.Status = 1 AND proj.Status = 1 AND proj.Roll = 24";

    if (!empty($filters['project_id']) && (int) $filters['project_id'] > 0) {
        $sql .= " AND psh.UnderBy='" . (int) $filters['project_id'] . "'";
    }
    if (!empty($filters['sub_head_id']) && (int) $filters['sub_head_id'] > 0) {
        $sql .= " AND psh.id='" . (int) $filters['sub_head_id'] . "'";
    }

    $sql .= " ORDER BY proj.Name ASC, psh.Name ASC";

    $rows = mobileMgmtQueryRows($sql);
    $totals = array(
        'project_name' => 'Total',
        'sub_head_name' => '',
        'total_insurance' => 0,
        'pending' => 0,
        'completed' => 0,
        'renewal' => 0,
        'expired' => 0,
        'renewed' => 0,
    );

    foreach ($rows as $row) {
        $totals['total_insurance'] += (int) $row['total_insurance'];
        $totals['pending'] += (int) $row['pending'];
        $totals['completed'] += (int) $row['completed'];
        $totals['renewal'] += (int) $row['renewal'];
        $totals['expired'] += (int) $row['expired'];
        $totals['renewed'] += (int) $row['renewed'];
    }

    return array(
        'rows' => $rows,
        'totals' => $totals,
    );
}

function mobileMgmtInsuranceListUrl($status, $projectId = 0, $subHeadId = 0)
{
    $status = preg_replace('/[^a-z_]/', '', (string) $status);
    $url = 'mobile-insurance-list.php?status=' . urlencode($status);

    if ((int) $projectId > 0) {
        $url .= '&ProjectId=' . (int) $projectId;
    }
    if ((int) $subHeadId > 0) {
        $url .= '&ProjectSubHeadId=' . (int) $subHeadId;
    }

    return $url;
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
        case 'renewed':
            return insuranceSiteRenewedSqlCondition();
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
        'renewed' => 'Renewed Insurance',
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

function mobileContractorBillingUrl($projectId = 0, $subheadId = 0)
{
    $url = 'mobile-contractor-billing.php';
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;

    if ($projectId > 0) {
        $url .= '?project_id=' . $projectId;
        if ($subheadId > 0) {
            $url .= '&subhead_id=' . $subheadId;
        }
    }

    return $url;
}

function mobileContractorBillingDetailsUrl($contractorId, $projectId = 0, $subheadId = 0)
{
    $contractorId = (int) $contractorId;
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $url = 'mobile-contractor-billing-details.php?id=' . $contractorId;

    if ($projectId > 0 && $subheadId > 0) {
        $url .= '&project_id=' . $projectId . '&subhead_id=' . $subheadId;
    }

    return $url;
}

function mobileContractorBillingFormatMoney($amount)
{
    return number_format((float) $amount, 2);
}

function mobileMsedclSmartGetDashboardData()
{
    require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-dashboard-data.php';

    return getMsedclSmartDashboardData();
}

function mobileMsedclSmartAdminUrl($file = 'dashboard.php')
{
    return '../rooftopadmin/msedcl_smart/' . ltrim($file, '/');
}

function mobileMsedclSmartAbstractUrl()
{
    return 'msedcl-smart-abstract.php';
}

function mobileMsedclSmartListTypes()
{
    return array(
        'total' => 'Total Customers',
        'pmsgy' => 'PMSGY Portal',
        'mahadiscom' => 'Mahadiscom Portal',
        'payment' => 'Payment Done',
        'survey_pending' => 'Survey Pending',
        'survey_done' => 'Survey Done',
    );
}

function mobileMsedclSmartListTitle($type)
{
    $types = mobileMsedclSmartListTypes();
    $type = preg_replace('/[^a-z_]/', '', (string) $type);

    return isset($types[$type]) ? $types[$type] : 'MSEDCL Smart';
}

function mobileMsedclSmartListUrl($type, $district = '', $search = '')
{
    $type = preg_replace('/[^a-z_]/', '', (string) $type);
    if (!isset(mobileMsedclSmartListTypes()[$type])) {
        $type = 'total';
    }

    $url = 'mobile-msedcl-smart-list.php?type=' . urlencode($type);
    if ($district !== '') {
        $url .= '&District=' . urlencode($district);
    }
    if ($search !== '') {
        $url .= '&Search=' . urlencode($search);
    }

    return $url;
}

function mobileMsedclSmartListFiltersFromRequest()
{
    $district = isset($_REQUEST['District']) ? trim((string) $_REQUEST['District']) : '';
    $search = isset($_REQUEST['Search']) ? trim((string) $_REQUEST['Search']) : '';
    $filters = array();

    if ($district !== '') {
        $filters['District'] = $district;
    }
    if ($search !== '') {
        $filters['Search'] = $search;
    }

    return array(
        'District' => $district,
        'Search' => $search,
        'filters' => $filters,
        'searched' => isset($_REQUEST['Search']) || isset($_REQUEST['submit']),
    );
}

function mobileMsedclSmartApplyListFilters($sql, array $filters)
{
    global $conn;

    if (!empty($filters['District'])) {
        $esc = mysqli_real_escape_string($conn, $filters['District']);
        $sql .= " AND District='$esc'";
    }
    if (!empty($filters['Search'])) {
        $esc = mysqli_real_escape_string($conn, $filters['Search']);
        $sql .= " AND (BeneficiaryId LIKE '%$esc%' OR CustName LIKE '%$esc%' OR CellNo LIKE '%$esc%')";
    }

    return $sql . ' ORDER BY id DESC';
}

function mobileMsedclSmartGetListRows($type, array $filters = array())
{
    require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';

    msedclSmartEnsureTables();
    $type = preg_replace('/[^a-z_]/', '', (string) $type);

    if (in_array($type, array('pmsgy', 'mahadiscom', 'payment', 'survey_pending'), true)) {
        return mobileMgmtQueryRows(msedclSmartBuildListSql($type, $filters));
    }

    if ($type === 'total') {
        $sql = mobileMsedclSmartApplyListFilters(
            "SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE Status=1",
            $filters
        );

        return mobileMgmtQueryRows($sql);
    }

    if ($type === 'survey_done') {
        $sql = mobileMsedclSmartApplyListFilters(
            "SELECT * FROM tbl_rooftop_msedcl_smart_customers WHERE Status=1 AND SurveyDone=1",
            $filters
        );

        return mobileMgmtQueryRows($sql);
    }

    return array();
}

function mobileMsedclSmartShowSurveyColumns($type)
{
    return in_array($type, array('survey_pending', 'survey_done'), true);
}

function mobileMsedclSmartDistrictOptions()
{
    require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';

    msedclSmartEnsureTables();

    return mobileMgmtQueryRows(
        "SELECT DISTINCT District FROM tbl_rooftop_msedcl_smart_customers WHERE District!='' AND Status=1 ORDER BY District ASC"
    );
}

function mobileMsedclSmartCapacityLabel($row)
{
    require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';

    $capName = msedclSmartRooftopCapacityMasterName($row['PumpCapacity'] ?? '');
    if ($capName !== '') {
        return $capName;
    }

    $raw = trim((string) ($row['PumpCapacity'] ?? ''));

    return $raw !== '' ? $raw : '—';
}

function mobileMsedclSmartSurveyStatusLabel($done)
{
    return (int) $done === 1 ? 'Done' : 'Pending';
}

function mobileMsedclSmartGetAbstractData($request = null)
{
    require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';

    msedclSmartEnsureTables();

    if ($request === null) {
        $request = $_REQUEST;
    }

    $meta = msedclSmartAbstractFiltersFromRequest($request);
    $rows = msedclSmartAbstractByDistrict($meta['filters']);
    $totals = msedclSmartAbstractTotals($rows);
    $districtRows = getList("SELECT DISTINCT TRIM(District) AS District FROM tbl_rooftop_msedcl_smart_customers WHERE TRIM(District)!='' AND Status=1 ORDER BY District ASC");
    $talukaRows = getList("SELECT DISTINCT TRIM(Taluka) AS Taluka FROM tbl_rooftop_msedcl_smart_customers WHERE TRIM(Taluka)!='' AND Status=1 ORDER BY Taluka ASC");

    return array(
        'meta' => $meta,
        'rows' => is_array($rows) ? $rows : array(),
        'totals' => $totals,
        'districtRows' => is_array($districtRows) ? $districtRows : array(),
        'talukaRows' => is_array($talukaRows) ? $talukaRows : array(),
        'exportQuery' => msedclSmartAbstractExportQueryString($meta),
        'isSearch' => isset($request['Search']),
        'abstractMeta' => array(
            'District' => $meta['District'],
            'Taluka' => $meta['Taluka'],
            'FromDate' => $meta['FromDate'],
            'ToDate' => $meta['ToDate'],
            'DateMode' => $meta['DateMode'],
        ),
    );
}

function mobileMsedclSmartAbstractListUrl($metric, $rowDistrict, array $meta)
{
    $params = array(
        'metric' => preg_replace('/[^a-z_]/', '', (string) $metric),
    );

    if ($rowDistrict !== '') {
        $params['RowDistrict'] = $rowDistrict;
    }

    foreach (array('District', 'Taluka', 'FromDate', 'ToDate', 'DateMode') as $key) {
        if (!empty($meta[$key])) {
            $params[$key] = $meta[$key];
        }
    }

    return 'mobile-msedcl-smart-abstract-list.php?' . http_build_query($params);
}

function mobileMsedclSmartAbstractCountCell($count, $metric, $rowDistrict, array $meta)
{
    $count = (int) $count;
    if ($count < 1) {
        return '0';
    }

    $url = mobileMsedclSmartAbstractListUrl($metric, $rowDistrict, $meta);

    return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . number_format($count) . '</a>';
}

function mobileMsedclSmartAbstractRecords($metric, $rowDistrict, array $filters = array())
{
    require_once __DIR__ . '/../rooftopadmin/inc-msedcl-smart-site.php';

    msedclSmartEnsureTables();

    $records = msedclSmartAbstractRecords($metric, $rowDistrict, $filters);

    return is_array($records) ? $records : array();
}

