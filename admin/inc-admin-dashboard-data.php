<?php
/**
 * Admin dashboard counts & chart data — aligned with list pages.
 */
require_once __DIR__ . '/inc-menu-option-groups.php';

function adminDashboardHasFullAccess($roll)
{
    if (function_exists('adminUserHasFullMenuAccess')) {
        return adminUserHasFullMenuAccess($roll);
    }

    return in_array((int) $roll, [1, 7], true);
}

/** @param array<int|string> $userOptions */
function adminDashboardCanSeeOptions(array $userOptions, array $requiredIds, $roll)
{
    if (adminDashboardHasFullAccess($roll)) {
        return true;
    }
    if (empty($requiredIds)) {
        return false;
    }
    return userHasAnyMenuOption($userOptions, $requiredIds);
}

/** @param array<int|string> $userOptions */
function adminDashboardCanSeeMenuGroup(array $userOptions, $groupName, $roll)
{
    if (adminDashboardHasFullAccess($roll)) {
        return true;
    }
    $groups = getMenuOptionGroups();
    if (!isset($groups[$groupName])) {
        return false;
    }
    return userHasAnyMenuOption($userOptions, $groups[$groupName]);
}

/** Account stat rows: label, count, path, icon, tone — filtered by tbl_options. */
function adminDashboardFilterAccountStats(array $stats, array $userOptions, $roll)
{
    $map = [
        [18, 113],
        [19],
        [20],
        [21],
        [22],
        [23],
        [125],
        [126],
    ];
    if (adminDashboardHasFullAccess($roll)) {
        return $stats;
    }
    $out = [];
    foreach ($stats as $i => $stat) {
        $req = $map[$i] ?? [];
        if (userHasAnyMenuOption($userOptions, $req)) {
            $out[] = $stat;
        }
    }
    return $out;
}

/** Operations stat rows: label, count, path, params, icon, badge, tone */
function adminDashboardFilterOperationsStats(array $stats, array $userOptions, $roll)
{
    $map = [
        [25],
        [25],
        [26],
        [26],
        [117, 140],
        [117, 140],
        [25],
    ];
    if (adminDashboardHasFullAccess($roll)) {
        return $stats;
    }
    $out = [];
    foreach ($stats as $i => $stat) {
        $req = $map[$i] ?? [];
        if (userHasAnyMenuOption($userOptions, $req)) {
            $out[] = $stat;
        }
    }
    return $out;
}

function adminDashboardFilterServiceStats(array $stats, array $userOptions, $roll)
{
    $serviceOpts = [28, 135, 136, 137, 164];
    $insuranceOpts = array_merge(getMenuOptionGroups()['Insurance Site'], [121]);
    $map = [
        $serviceOpts,
        $serviceOpts,
        array_merge($serviceOpts, $insuranceOpts),
        array_merge($serviceOpts, $insuranceOpts),
    ];
    if (adminDashboardHasFullAccess($roll)) {
        return $stats;
    }
    $out = [];
    foreach ($stats as $i => $stat) {
        $req = $map[$i] ?? $serviceOpts;
        if (userHasAnyMenuOption($userOptions, $req)) {
            $out[] = $stat;
        }
    }
    return $out;
}

function adminDashboardEmployeeRollExclusionSql() {
    return '1,3,4,5,9,10,8,11,34,35,36,37,39,40,26,27,42';
}

function adminDashboardEmployeeCount() {
    return getRow('SELECT id FROM tbl_users WHERE Roll NOT IN (' . adminDashboardEmployeeRollExclusionSql() . ')');
}

function getAdminDashboardAccountStats() {
    return [
        ['Customers', getRow('SELECT id FROM tbl_users WHERE Roll=5'), 'user_management/pump-customers.php', 'users', 'blue'],
        ['Manufacturers', getRow('SELECT id FROM tbl_users WHERE Roll=3'), 'user_management/view-manufacture.php', 'briefcase', 'green'],
        ['Company', getRow('SELECT id FROM tbl_users WHERE Roll=10'), 'user_management/view-company.php', 'home', 'slate'],
        ['Employees', adminDashboardEmployeeCount(), 'user_management/view-employee.php', 'user', 'purple'],
        ['Dealers', getRow('SELECT id FROM tbl_users WHERE Roll=9'), 'user_management/view-dealer.php', 'user-check', 'amber'],
        ['Agency', getRow('SELECT id FROM tbl_users WHERE Roll=11'), 'user_management/view-agency.php', 'award', 'teal'],
        ['Store Incharge', getRow('SELECT id FROM tbl_users WHERE Roll=27'), 'user_management/view-store-incharge.php', 'archive', 'blue'],
        ['Dispatch Officer', getRow('SELECT id FROM tbl_users WHERE Roll=26'), 'user_management/view-dispatch-officer.php', 'truck', 'red'],
    ];
}

/** Project head chart (top N). */
function getAdminDashboardProjectHeadChartData($limit = 15) {
    $limit = max(1, (int) $limit);
    $rows = array_slice(getAdminDashboardProjectHeadRows(), 0, $limit);
    $labels = [];
    $counts = [];
    foreach ($rows as $row) {
        $labels[] = $row['label'];
        $counts[] = (int) $row['cnt'];
    }
    return ['labels' => $labels, 'counts' => $counts];
}

/** All active project sub heads with beneficiary count (for boxes + chart). */
function getAdminDashboardSubHeadRows() {
    $sql = "SELECT psh.id, psh.Name AS label, psh.UnderBy,
            COUNT(tu.id) AS cnt
            FROM tbl_project_sub_head psh
            LEFT JOIN tbl_users tu ON tu.ProjectSubHeadId = psh.id AND tu.Roll = 5
            WHERE psh.Status = '1'
            GROUP BY psh.id, psh.Name, psh.UnderBy
            ORDER BY cnt DESC, psh.Name ASC";
    return getList($sql);
}

/** Beneficiaries by project sub head (top N for chart). */
function getAdminDashboardSubHeadChartData($limit = 12) {
    $limit = max(1, (int) $limit);
    $rows = array_slice(getAdminDashboardSubHeadRows(), 0, $limit);
    $labels = [];
    $counts = [];
    foreach ($rows as $row) {
        $labels[] = $row['label'];
        $counts[] = (int) $row['cnt'];
    }
    return ['labels' => $labels, 'counts' => $counts];
}

/** Stat cards: project sub head — same counts as chart/boxes source. */
function getAdminDashboardSubHeadStatCards() {
    $cards = [];
    foreach (getAdminDashboardSubHeadRows() as $row) {
        $cards[] = [
            $row['label'],
            (int) $row['cnt'],
            'installation-project-dashboard-2.php',
            [
                'prjid' => (int) $row['UnderBy'],
                'id' => (int) $row['id'],
                'name' => $row['label'],
            ],
            'git-branch',
            'Beneficiaries',
            'purple',
        ];
    }
    return $cards;
}

/** All active project heads (Roll 24) with beneficiary count. */
function getAdminDashboardProjectHeadRows() {
    $sql = "SELECT cm.id, cm.Name AS label, COUNT(tu.id) AS cnt
            FROM tbl_common_master cm
            LEFT JOIN tbl_users tu ON tu.ProjectId = cm.id AND tu.Roll = 5
            WHERE cm.Status = '1' AND cm.Roll = 24
            GROUP BY cm.id, cm.Name
            ORDER BY cnt DESC, cm.Name ASC";
    return getList($sql);
}

/** Stat cards: project head wise. */
function getAdminDashboardProjectHeadStatCards() {
    $cards = [];
    foreach (getAdminDashboardProjectHeadRows() as $row) {
        $cards[] = [
            $row['label'],
            (int) $row['cnt'],
            'installation-project-sub-head-dashboard.php',
            ['id' => (int) $row['id'], 'name' => $row['label']],
            'folder',
            'Beneficiaries',
            'blue',
        ];
    }
    return $cards;
}

/** Scheme / yojna beneficiary distribution */
function getAdminDashboardSchemeChartData($limit = 10) {
    $limit = max(1, (int) $limit);
    $sql = "SELECT s.id, s.Name AS label, COUNT(tu.id) AS cnt
            FROM tbl_scheme s
            LEFT JOIN tbl_users tu ON tu.SchemeId = s.id AND tu.Roll = 5
            WHERE s.Status = '1'
            GROUP BY s.id, s.Name
            ORDER BY cnt DESC, s.Name ASC
            LIMIT $limit";
    $rows = getList($sql);
    $labels = [];
    $counts = [];
    foreach ($rows as $row) {
        $labels[] = $row['label'];
        $counts[] = (int) $row['cnt'];
    }
    return ['labels' => $labels, 'counts' => $counts];
}

function getAdminDashboardMonthlyCounts($table, $dateColumn, $months = 6) {
    $months = max(1, (int) $months);
    $dateColumn = preg_replace('/[^a-zA-Z0-9_]/', '', $dateColumn);
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $sql = "SELECT DATE_FORMAT($dateColumn, '%b %Y') AS label,
            DATE_FORMAT($dateColumn, '%Y-%m') AS ym,
            COUNT(id) AS cnt
            FROM $table
            WHERE $dateColumn >= DATE_SUB(CURDATE(), INTERVAL $months MONTH)
            GROUP BY ym, label
            ORDER BY ym ASC";
    $rows = getList($sql);
    $labels = [];
    $counts = [];
    foreach ($rows as $row) {
        $labels[] = $row['label'];
        $counts[] = (int) $row['cnt'];
    }
    return ['labels' => $labels, 'counts' => $counts];
}

function getAdminDashboardSummaryKpis($today) {
    return [
        'customers' => getRow('SELECT id FROM tbl_users WHERE Roll=5'),
        'employees' => adminDashboardEmployeeCount(),
        'purchase_orders' => getRow('SELECT id FROM tbl_purchase_order'),
        'delivery_challan' => getRow('SELECT id FROM tbl_sell'),
        'service_complaints' => getRow('SELECT id FROM tbl_service_complaint'),
        'projects' => getRow("SELECT id FROM tbl_common_master WHERE Status='1' AND Roll=24"),
        'today_po' => getRow("SELECT id FROM tbl_purchase_order WHERE InvoiceDate='$today'"),
        'today_complaints' => getRow("SELECT id FROM tbl_service_complaint WHERE CreatedDate='$today'"),
    ];
}

function getAdminDashboardOperationsChartData() {
    return [
        'labels' => ['Purchase Orders', 'Delivery Challan', 'Quotations', 'Work Orders'],
        'counts' => [
            getRow('SELECT id FROM tbl_purchase_order'),
            getRow('SELECT id FROM tbl_sell'),
            getRow('SELECT id FROM tbl_quotation'),
            getRow('SELECT id FROM tbl_work_order'),
        ],
    ];
}

function getAdminDashboardServiceChartData($today) {
    return [
        'labels' => ['Total Complaints', 'Today', 'Insurance Claims', 'Insurance Today'],
        'counts' => [
            getRow('SELECT id FROM tbl_service_complaint'),
            getRow("SELECT id FROM tbl_service_complaint WHERE CreatedDate='$today'"),
            getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance'"),
            getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance' AND CreatedDate='$today'"),
        ],
    ];
}
