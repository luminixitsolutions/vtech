<?php

/**
 * Employee activity tracking — error-safe; never blocks main operations.
 */

define('EMP_ACT_PAGE_VISIT', 'PAGE_VISIT');
define('EMP_ACT_VIEW_RECORD', 'VIEW_RECORD');
define('EMP_ACT_ADD_RECORD', 'ADD_RECORD');
define('EMP_ACT_EDIT_RECORD', 'EDIT_RECORD');
define('EMP_ACT_DELETE_RECORD', 'DELETE_RECORD');
define('EMP_ACT_LOGIN', 'LOGIN');
define('EMP_ACT_LOGOUT', 'LOGOUT');

define('EMP_ACT_MENU_OPTION_ID', 187);

function employeeActivityLogEnsureTable($conn)
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    $sql = "CREATE TABLE IF NOT EXISTS tbl_employee_activity_logs (
      id INT(11) NOT NULL AUTO_INCREMENT,
      user_id INT(11) NOT NULL DEFAULT 0,
      employee_name VARCHAR(255) DEFAULT NULL,
      role VARCHAR(128) DEFAULT NULL,
      page_url VARCHAR(500) DEFAULT NULL,
      page_name VARCHAR(255) DEFAULT NULL,
      module_name VARCHAR(128) DEFAULT NULL,
      action_type VARCHAR(32) NOT NULL,
      record_table VARCHAR(128) DEFAULT NULL,
      record_id VARCHAR(64) DEFAULT NULL,
      old_data LONGTEXT DEFAULT NULL,
      new_data LONGTEXT DEFAULT NULL,
      ip_address VARCHAR(64) DEFAULT NULL,
      user_agent VARCHAR(500) DEFAULT NULL,
      created_at DATETIME NOT NULL,
      PRIMARY KEY (id),
      KEY user_id (user_id),
      KEY action_type (action_type),
      KEY module_name (module_name),
      KEY created_at (created_at),
      KEY record_table (record_table, record_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    @$conn->query($sql);
}

function employeeActivityLogJson($data)
{
    if ($data === null || $data === '') {
        return null;
    }
    if (is_string($data)) {
        return mb_substr($data, 0, 65000);
    }
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return null;
    }

    return mb_substr($json, 0, 65000);
}

function employeeActivityLogActor()
{
    if (empty($_SESSION['Admin']['id'])) {
        return null;
    }
    $id = (int) $_SESSION['Admin']['id'];
    $name = trim((string) ($_SESSION['Admin']['Fname'] ?? '') . ' ' . (string) ($_SESSION['Admin']['Lname'] ?? ''));
    if ($name === '') {
        $name = trim((string) ($_SESSION['Admin']['Fname'] ?? 'User'));
    }
    $role = '';
    if (!empty($_SESSION['Admin']['Roll'])) {
        $role = 'Roll ' . (int) $_SESSION['Admin']['Roll'];
        if (function_exists('getRecord')) {
            global $conn;
            $rid = (int) $_SESSION['Admin']['Roll'];
            $rt = getRecord("SELECT Name FROM tbl_user_type WHERE id='$rid' LIMIT 1");
            if (!empty($rt['Name'])) {
                $role = trim((string) $rt['Name']);
            }
        }
    }

    return ['user_id' => $id, 'employee_name' => $name, 'role' => $role];
}

function employeeActivityLogCurrentUrl()
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if ($uri === '' && !empty($_SERVER['SCRIPT_NAME'])) {
        $uri = $_SERVER['SCRIPT_NAME'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            $uri .= '?' . $_SERVER['QUERY_STRING'];
        }
    }

    return mb_substr($uri, 0, 500);
}

function employeeActivityLogPageMeta()
{
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $adminRoot = str_replace('\\', '/', realpath(dirname(__FILE__)) ?: '');
    $relative = $script;
    if ($adminRoot !== '' && stripos($script, $adminRoot) === 0) {
        $relative = substr($script, strlen($adminRoot) + 1);
    }
    $basename = basename($relative);
    $module = 'Admin';
    if (strpos($relative, 'report_management/') === 0 || strpos($relative, '/report_management/') !== false) {
        $module = 'Report Management';
    } elseif (strpos($relative, 'user_management/') !== false) {
        $module = 'User Management';
    } elseif (strpos($relative, 'lead_management/') !== false) {
        $module = 'Lead Management';
    } elseif (strpos($relative, 'master_management/') !== false) {
        $module = 'Master Management';
    } elseif (strpos($relative, 'product_management/') !== false) {
        $module = 'Product Management';
    } elseif (strpos($relative, 'ajax_files/') !== false) {
        $module = 'Ajax';
    } elseif (strpos($relative, 'item_transfer_workflow/') !== false) {
        $module = 'Item Transfer';
    }
    $pageName = preg_replace('/\.php$/i', '', $basename);
    $pageName = ucwords(str_replace(['-', '_'], ' ', $pageName));

    return [
        'page_url' => employeeActivityLogCurrentUrl(),
        'page_name' => $pageName,
        'module_name' => $module,
        'basename' => $basename,
        'relative' => $relative,
    ];
}

function employeeActivityLogShouldSkip()
{
    $meta = employeeActivityLogPageMeta();
    $basename = $meta['basename'];
    $relative = strtolower($meta['relative']);

    $skipNames = [
        'index.php', 'logout.php', 'auth.php', 'config.php', 'db-local.php',
        'header.php', 'header1.0.php', 'footer.php', 'top_header.php',
        'footer_script.php', 'header_script.php', 'cancel-pending-login.php',
        'verify-login-otp.php',
        'employee-tracking-log-detail.php', 'employee-tracking-export.php',
    ];
    if (in_array($basename, $skipNames, true)) {
        return true;
    }
    if (strpos($basename, 'inc-') === 0) {
        return true;
    }
    $skipPrefixes = ['ajax_files/', 'migrations/', 'pagination/', 'vendor/', 'ckeditor/', 'whatsapp_sms/'];
    foreach ($skipPrefixes as $prefix) {
        if (strpos($relative, $prefix) !== false) {
            return true;
        }
    }
    if (preg_match('/\.(css|js|map|png|jpg|gif|woff|ico)$/i', $basename)) {
        return true;
    }

    return false;
}

/**
 * @param array<string,mixed> $opts
 */
function addEmployeeLog(array $opts)
{
    try {
        global $conn;
        if (empty($conn)) {
            return false;
        }
        employeeActivityLogEnsureTable($conn);

        $actor = employeeActivityLogActor();
        if (!$actor && !empty($opts['user_id'])) {
            $actor = [
                'user_id' => (int) $opts['user_id'],
                'employee_name' => (string) ($opts['employee_name'] ?? ''),
                'role' => (string) ($opts['role'] ?? ''),
            ];
        }
        if (!$actor || (int) $actor['user_id'] <= 0) {
            return false;
        }

        $meta = employeeActivityLogPageMeta();
        $actionType = strtoupper(trim((string) ($opts['action_type'] ?? EMP_ACT_PAGE_VISIT)));
        $allowed = [
            EMP_ACT_PAGE_VISIT, EMP_ACT_VIEW_RECORD, EMP_ACT_ADD_RECORD,
            EMP_ACT_EDIT_RECORD, EMP_ACT_DELETE_RECORD, EMP_ACT_LOGIN, EMP_ACT_LOGOUT,
        ];
        if (!in_array($actionType, $allowed, true)) {
            $actionType = EMP_ACT_PAGE_VISIT;
        }

        $pageUrl = $opts['page_url'] ?? $meta['page_url'];
        $pageName = $opts['page_name'] ?? $meta['page_name'];
        $moduleName = $opts['module_name'] ?? $meta['module_name'];
        $recordTable = $opts['record_table'] ?? null;
        $recordId = isset($opts['record_id']) ? (string) $opts['record_id'] : '';
        if ($recordId === '' && !empty($_GET['id'])) {
            $recordId = (string) (int) $_GET['id'];
        }
        if ($recordId === '' && !empty($_POST['id'])) {
            $recordId = (string) (int) $_POST['id'];
        }

        $oldData = employeeActivityLogJson($opts['old_data'] ?? null);
        $newData = employeeActivityLogJson($opts['new_data'] ?? null);
        $ip = mb_substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 64);
        $ua = mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500);
        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO tbl_employee_activity_logs SET
            user_id='" . (int) $actor['user_id'] . "',
            employee_name='" . $conn->real_escape_string($actor['employee_name']) . "',
            role='" . $conn->real_escape_string($actor['role']) . "',
            page_url='" . $conn->real_escape_string(mb_substr((string) $pageUrl, 0, 500)) . "',
            page_name='" . $conn->real_escape_string(mb_substr((string) $pageName, 0, 255)) . "',
            module_name='" . $conn->real_escape_string(mb_substr((string) $moduleName, 0, 128)) . "',
            action_type='" . $conn->real_escape_string($actionType) . "',
            record_table=" . ($recordTable ? "'" . $conn->real_escape_string(mb_substr((string) $recordTable, 0, 128)) . "'" : 'NULL') . ",
            record_id=" . ($recordId !== '' ? "'" . $conn->real_escape_string(mb_substr($recordId, 0, 64)) . "'" : 'NULL') . ",
            old_data=" . ($oldData !== null ? "'" . $conn->real_escape_string($oldData) . "'" : 'NULL') . ",
            new_data=" . ($newData !== null ? "'" . $conn->real_escape_string($newData) . "'" : 'NULL') . ",
            ip_address='" . $conn->real_escape_string($ip) . "',
            user_agent='" . $conn->real_escape_string($ua) . "',
            created_at='$createdAt'";

        return (bool) @$conn->query($sql);
    } catch (Throwable $e) {
        return false;
    }
}

function employeeActivityLogPageVisit()
{
    if (employeeActivityLogShouldSkip()) {
        return;
    }
    if (empty($_SESSION['Admin']['id'])) {
        return;
    }
    $meta = employeeActivityLogPageMeta();
    $key = md5($meta['page_url'] . '|' . (int) $_SESSION['Admin']['id']);
    if (!isset($_SESSION['emp_act_visit'])) {
        $_SESSION['emp_act_visit'] = [];
    }
    $now = time();
    if (isset($_SESSION['emp_act_visit'][$key]) && ($now - (int) $_SESSION['emp_act_visit'][$key]) < 45) {
        return;
    }
    $_SESSION['emp_act_visit'][$key] = $now;
    if (count($_SESSION['emp_act_visit']) > 80) {
        $_SESSION['emp_act_visit'] = array_slice($_SESSION['emp_act_visit'], -40, null, true);
    }

    addEmployeeLog(['action_type' => EMP_ACT_PAGE_VISIT]);
}

function employeeActivityLogAutoViewRecord()
{
    if (employeeActivityLogShouldSkip() || empty($_SESSION['Admin']['id'])) {
        return;
    }
    $recordId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($recordId <= 0) {
        return;
    }
    $meta = employeeActivityLogPageMeta();
    $bn = strtolower($meta['basename']);
    if (!preg_match('/^(view-|.*profile|.*-profile|edit-|customer-profile)/', $bn)) {
        return;
    }
    $key = 'view|' . $bn . '|' . $recordId . '|' . (int) $_SESSION['Admin']['id'];
    if (!isset($_SESSION['emp_act_view'])) {
        $_SESSION['emp_act_view'] = [];
    }
    if (isset($_SESSION['emp_act_view'][$key]) && (time() - (int) $_SESSION['emp_act_view'][$key]) < 60) {
        return;
    }
    $_SESSION['emp_act_view'][$key] = time();

    $table = 'tbl_users';
    if (preg_match('/purchase|po|order/i', $bn)) {
        $table = 'tbl_purchase_order';
    } elseif (preg_match('/customer|beneficiary/i', $bn)) {
        $table = 'tbl_users';
    } elseif (preg_match('/product/i', $bn)) {
        $table = 'tbl_products';
    }

    addEmployeeLog([
        'action_type' => EMP_ACT_VIEW_RECORD,
        'record_table' => $table,
        'record_id' => (string) $recordId,
    ]);
}

function employeeActivityLogInferAjaxAction()
{
    $action = strtolower(trim((string) ($_POST['action'] ?? $_GET['action'] ?? '')));
    if ($action === '') {
        return null;
    }
    if ($action === 'save') {
        $rid = (int) ($_POST['id'] ?? $_POST['userid'] ?? 0);

        return $rid > 0 ? EMP_ACT_EDIT_RECORD : EMP_ACT_ADD_RECORD;
    }
    $map = [
        'update' => EMP_ACT_EDIT_RECORD,
        'add' => EMP_ACT_ADD_RECORD,
        'insert' => EMP_ACT_ADD_RECORD,
        'delete' => EMP_ACT_DELETE_RECORD,
        'remove' => EMP_ACT_DELETE_RECORD,
        'deletephoto' => EMP_ACT_DELETE_RECORD,
        'delete_photo' => EMP_ACT_DELETE_RECORD,
    ];
    foreach ($map as $needle => $type) {
        if ($action === $needle || strpos($action, $needle) !== false) {
            return $type;
        }
    }

    return null;
}

function employeeActivityLogAutoAjaxPost()
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }
    if (empty($_SESSION['Admin']['id'])) {
        return;
    }
    $meta = employeeActivityLogPageMeta();
    if (strpos(strtolower($meta['relative']), 'ajax_files/') === false
        && strpos(strtolower($meta['relative']), 'ajax_') === false
        && strpos(strtolower($meta['basename']), 'ajax') === false) {
        return;
    }
    if (strpos($meta['basename'], 'ajax_employee.php') !== false
        || strpos($meta['basename'], 'ajax_verify_admin_otp') !== false) {
        return;
    }
    $actType = employeeActivityLogInferAjaxAction();
    if ($actType === null) {
        return;
    }
    $recordId = (int) ($_POST['id'] ?? $_POST['userid'] ?? $_GET['id'] ?? 0);
    $table = 'tbl_record';
    if (strpos($meta['basename'], 'employee') !== false) {
        $table = 'tbl_users';
    } elseif (strpos($meta['basename'], 'customer') !== false) {
        $table = 'tbl_users';
    } elseif (strpos($meta['basename'], 'product') !== false) {
        $table = 'tbl_products';
    } elseif (strpos($meta['basename'], 'purchase') !== false || strpos($meta['basename'], 'po') !== false) {
        $table = 'tbl_purchase_order';
    }

    $payload = $_POST;
    unset($payload['Password'], $payload['password'], $payload['OldPassword']);
    addEmployeeLog([
        'action_type' => $actType,
        'record_table' => $table,
        'record_id' => $recordId > 0 ? (string) $recordId : '',
        'new_data' => $payload,
    ]);
}

function employeeActivityLogBootstrap()
{
    if (php_sapi_name() === 'cli') {
        return;
    }
    employeeActivityLogPageVisit();
    employeeActivityLogAutoViewRecord();
    employeeActivityLogAutoAjaxPost();
}

/** Report access: super admin rolls or menu option 187. */
function employeeActivityLogCanViewReport($roll, array $options = [])
{
    $roll = (int) $roll;
    if (function_exists('adminUserHasFullMenuAccess') && adminUserHasFullMenuAccess($roll)) {
        return true;
    }
    if (in_array($roll, [1, 7], true)) {
        return true;
    }

    return in_array((string) EMP_ACT_MENU_OPTION_ID, $options, true)
        || in_array(EMP_ACT_MENU_OPTION_ID, $options, true);
}

function employeeActivityLogFetchUserRowForEdit($userId)
{
    $userId = (int) $userId;
    if ($userId <= 0 || !function_exists('getRecord')) {
        return null;
    }
    $row = getRecord("SELECT id,Fname,Lname,Phone,EmailId,Roll,Status,Options FROM tbl_users WHERE id='$userId' LIMIT 1");
    if (!$row) {
        return null;
    }
    unset($row['Password']);

    return $row;
}

/** @return array{where:string,per_page:int,page:int} */
function employeeActivityLogReportParamsFromRequest()
{
    global $conn;
    $perPage = 50;
    $page = max(1, (int) ($_REQUEST['page'] ?? 1));
    $where = '1=1';
    $userId = isset($_REQUEST['UserId']) ? $_REQUEST['UserId'] : 'all';
    if ($userId !== '' && $userId !== 'all') {
        $where .= " AND user_id='" . (int) $userId . "'";
    }
    $actionType = trim((string) ($_REQUEST['ActionType'] ?? ''));
    if ($actionType !== '' && $actionType !== 'all') {
        $where .= " AND action_type='" . $conn->real_escape_string($actionType) . "'";
    }
    $moduleName = trim((string) ($_REQUEST['ModuleName'] ?? ''));
    if ($moduleName !== '' && $moduleName !== 'all') {
        $where .= " AND module_name='" . $conn->real_escape_string($moduleName) . "'";
    }
    $pageName = trim((string) ($_REQUEST['PageName'] ?? ''));
    if ($pageName !== '') {
        $where .= " AND page_name LIKE '%" . $conn->real_escape_string($pageName) . "%'";
    }
    if (!empty($_REQUEST['FromDate'])) {
        $where .= " AND created_at>='" . $conn->real_escape_string($_REQUEST['FromDate']) . " 00:00:00'";
    }
    if (!empty($_REQUEST['ToDate'])) {
        $where .= " AND created_at<='" . $conn->real_escape_string($_REQUEST['ToDate']) . " 23:59:59'";
    }

    return ['where' => $where, 'per_page' => $perPage, 'page' => $page];
}

function employeeActivityLogReportCount($where)
{
    global $conn;
    employeeActivityLogEnsureTable($conn);
    $row = getRecord("SELECT COUNT(*) AS cnt FROM tbl_employee_activity_logs WHERE $where");

    return (int) ($row['cnt'] ?? 0);
}

function employeeActivityLogReportRows($where, $page, $perPage)
{
    global $conn;
    employeeActivityLogEnsureTable($conn);
    $offset = ($page - 1) * $perPage;

    return getList("SELECT * FROM tbl_employee_activity_logs WHERE $where ORDER BY id DESC LIMIT $offset, $perPage");
}

function employeeActivityLogActionTypeOptions()
{
    return [
        EMP_ACT_PAGE_VISIT => 'Page Visit',
        EMP_ACT_VIEW_RECORD => 'View Record',
        EMP_ACT_ADD_RECORD => 'Add Record',
        EMP_ACT_EDIT_RECORD => 'Edit Record',
        EMP_ACT_DELETE_RECORD => 'Delete Record',
        EMP_ACT_LOGIN => 'Login',
        EMP_ACT_LOGOUT => 'Logout',
    ];
}
