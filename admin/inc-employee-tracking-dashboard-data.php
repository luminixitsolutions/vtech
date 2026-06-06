<?php

require_once __DIR__ . '/inc-employee-activity-log.php';

/**
 * @return array{from:string,to:string,user_id:string}
 */
function employeeTrackingDashboardFiltersFromRequest()
{
    $from = trim((string) ($_REQUEST['FromDate'] ?? ''));
    $to = trim((string) ($_REQUEST['ToDate'] ?? ''));
    if ($from === '') {
        $from = date('Y-m-d', strtotime('-30 days'));
    }
    if ($to === '') {
        $to = date('Y-m-d');
    }
    $userId = isset($_REQUEST['UserId']) ? (string) $_REQUEST['UserId'] : 'all';

    return ['from' => $from, 'to' => $to, 'user_id' => $userId];
}

function employeeTrackingDashboardWhereSql(array $filters)
{
    global $conn;
    $where = '1=1';
    if (!empty($filters['from'])) {
        $where .= " AND created_at>='" . $conn->real_escape_string($filters['from']) . " 00:00:00'";
    }
    if (!empty($filters['to'])) {
        $where .= " AND created_at<='" . $conn->real_escape_string($filters['to']) . " 23:59:59'";
    }
    if (!empty($filters['user_id']) && $filters['user_id'] !== 'all') {
        $where .= " AND user_id='" . (int) $filters['user_id'] . "'";
    }

    return $where;
}

function employeeTrackingDashboardCount($where)
{
    global $conn;
    employeeActivityLogEnsureTable($conn);
    $row = getRecord("SELECT COUNT(*) AS cnt FROM tbl_employee_activity_logs WHERE $where");

    return (int) ($row['cnt'] ?? 0);
}

function employeeTrackingDashboardCountByAction($where, $actionType)
{
    global $conn;
    $actionType = $conn->real_escape_string($actionType);
    $row = getRecord("SELECT COUNT(*) AS cnt FROM tbl_employee_activity_logs WHERE $where AND action_type='$actionType'");

    return (int) ($row['cnt'] ?? 0);
}

function employeeTrackingDashboardActionBreakdown($where)
{
    global $conn;
    $labels = employeeActivityLogActionTypeOptions();
    $rows = getList("SELECT action_type, COUNT(*) AS cnt FROM tbl_employee_activity_logs WHERE $where GROUP BY action_type ORDER BY cnt DESC");
    $out = [];
    foreach ($rows as $r) {
        $code = $r['action_type'];
        $out[] = [
            'code' => $code,
            'label' => $labels[$code] ?? $code,
            'cnt' => (int) $r['cnt'],
        ];
    }

    return $out;
}

function employeeTrackingDashboardModuleBreakdown($where, $limit = 8)
{
    global $conn;
    $limit = (int) $limit;

    return getList("SELECT IFNULL(module_name,'Other') AS module_name, COUNT(*) AS cnt
        FROM tbl_employee_activity_logs WHERE $where
        GROUP BY module_name ORDER BY cnt DESC LIMIT $limit");
}

function employeeTrackingDashboardDailyTrend($where, $days = 14)
{
    global $conn;
    $days = (int) $days;

    return getList("SELECT DATE(created_at) AS day_key, DATE_FORMAT(created_at, '%d %b') AS label, COUNT(*) AS cnt
        FROM tbl_employee_activity_logs WHERE $where
          AND created_at >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
        GROUP BY day_key, label ORDER BY day_key ASC");
}

function employeeTrackingDashboardTopEmployees($where, $limit = 8)
{
    global $conn;
    $limit = (int) $limit;

    return getList("SELECT user_id, employee_name, role, COUNT(*) AS cnt
        FROM tbl_employee_activity_logs WHERE $where
        GROUP BY user_id, employee_name, role
        ORDER BY cnt DESC LIMIT $limit");
}

function employeeTrackingDashboardRecentLogs($where, $limit = 12)
{
    global $conn;
    $limit = (int) $limit;

    return getList("SELECT id, created_at, employee_name, role, module_name, page_name, action_type, record_id, ip_address
        FROM tbl_employee_activity_logs WHERE $where ORDER BY id DESC LIMIT $limit");
}

function employeeTrackingDashboardUniqueEmployees($where)
{
    global $conn;
    $row = getRecord("SELECT COUNT(DISTINCT user_id) AS cnt FROM tbl_employee_activity_logs WHERE $where");

    return (int) ($row['cnt'] ?? 0);
}

function employeeTrackingDashboardGetData(array $filters = null)
{
    if ($filters === null) {
        $filters = employeeTrackingDashboardFiltersFromRequest();
    }
    $where = employeeTrackingDashboardWhereSql($filters);
    $today = date('Y-m-d');
    $whereToday = $where . " AND DATE(created_at)='$today'";

    return [
        'filters' => $filters,
        'where' => $where,
        'total' => employeeTrackingDashboardCount($where),
        'today' => employeeTrackingDashboardCount($whereToday),
        'unique_employees' => employeeTrackingDashboardUniqueEmployees($where),
        'page_visits' => employeeTrackingDashboardCountByAction($where, EMP_ACT_PAGE_VISIT),
        'views' => employeeTrackingDashboardCountByAction($where, EMP_ACT_VIEW_RECORD),
        'adds' => employeeTrackingDashboardCountByAction($where, EMP_ACT_ADD_RECORD),
        'edits' => employeeTrackingDashboardCountByAction($where, EMP_ACT_EDIT_RECORD),
        'deletes' => employeeTrackingDashboardCountByAction($where, EMP_ACT_DELETE_RECORD),
        'logins' => employeeTrackingDashboardCountByAction($where, EMP_ACT_LOGIN),
        'logouts' => employeeTrackingDashboardCountByAction($where, EMP_ACT_LOGOUT),
        'action_breakdown' => employeeTrackingDashboardActionBreakdown($where),
        'module_breakdown' => employeeTrackingDashboardModuleBreakdown($where, 8),
        'daily_trend' => employeeTrackingDashboardDailyTrend($where, 14),
        'top_employees' => employeeTrackingDashboardTopEmployees($where, 8),
        'recent' => employeeTrackingDashboardRecentLogs($where, 12),
    ];
}

function employeeTrackingDashboardStatCard($label, $count, $href, $icon, $hint, $tone)
{
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $count = number_format((int) $count);
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $hint = htmlspecialchars($hint, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z0-9-]/', '', $tone);
    ?>
<a href="<?php echo $href; ?>" class="etd-stat-link">
    <div class="etd-stat-card etd-tone-<?php echo $tone; ?>">
        <div class="etd-stat-icon"><i class="feather icon-<?php echo $icon; ?>"></i></div>
        <div class="etd-stat-body">
            <span class="etd-stat-value"><?php echo $count; ?></span>
            <span class="etd-stat-label"><?php echo $label; ?></span>
            <span class="etd-stat-hint"><?php echo $hint; ?></span>
        </div>
    </div>
</a>
    <?php
}

function employeeTrackingDashboardReportUrl(array $filters, $extra = [])
{
    $q = array_merge([
        'Search' => 1,
        'FromDate' => $filters['from'] ?? '',
        'ToDate' => $filters['to'] ?? '',
        'UserId' => $filters['user_id'] ?? 'all',
    ], $extra);

    return 'employee-tracking-report.php?' . http_build_query($q);
}
