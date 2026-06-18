<?php

if (!defined('INST_WORKFLOW_SLA_DAYS')) {
    define('INST_WORKFLOW_SLA_DAYS', 3);
}

function installationWorkflowEnsureSchema()
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    global $conn;
    if (!$conn) {
        return;
    }

    $flowColumns = [
        'manager_id' => "ALTER TABLE tbl_installation_flow ADD COLUMN manager_id INT(11) NOT NULL DEFAULT 0 AFTER assigned_to",
        'gm_id' => "ALTER TABLE tbl_installation_flow ADD COLUMN gm_id INT(11) NOT NULL DEFAULT 0 AFTER manager_id",
        'business_head_id' => "ALTER TABLE tbl_installation_flow ADD COLUMN business_head_id INT(11) NOT NULL DEFAULT 0 AFTER gm_id",
        'coordinator_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN coordinator_due_date DATETIME NULL AFTER stage_start_date",
        'manager_assigned_at' => "ALTER TABLE tbl_installation_flow ADD COLUMN manager_assigned_at DATETIME NULL AFTER coordinator_due_date",
        'manager_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN manager_due_date DATETIME NULL AFTER manager_assigned_at",
        'gm_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN gm_due_date DATETIME NULL AFTER manager_due_date",
        'business_head_due_date' => "ALTER TABLE tbl_installation_flow ADD COLUMN business_head_due_date DATETIME NULL AFTER gm_due_date",
        'installed_at' => "ALTER TABLE tbl_installation_flow ADD COLUMN installed_at DATETIME NULL AFTER stage_end_date",
    ];

    foreach ($flowColumns as $col => $sql) {
        $r = $conn->query("SHOW COLUMNS FROM tbl_installation_flow LIKE '$col'");
        if (!$r || !$r->num_rows) {
            $conn->query($sql);
        }
    }

    $extColumns = [
        'remarks' => "ALTER TABLE tbl_installation_extensions ADD COLUMN remarks TEXT NULL AFTER extension_days",
        'gm_id' => "ALTER TABLE tbl_installation_extensions ADD COLUMN gm_id INT(11) NOT NULL DEFAULT 0 AFTER requested_by",
        'business_head_id' => "ALTER TABLE tbl_installation_extensions ADD COLUMN business_head_id INT(11) NOT NULL DEFAULT 0 AFTER gm_id",
    ];

    foreach ($extColumns as $col => $sql) {
        $r = $conn->query("SHOW COLUMNS FROM tbl_installation_extensions LIKE '$col'");
        if (!$r || !$r->num_rows) {
            $conn->query($sql);
        }
    }
}

function installationWorkflowSlaDays()
{
    return (int) INST_WORKFLOW_SLA_DAYS;
}

function installationWorkflowAddDays($days = null)
{
    $days = $days === null ? installationWorkflowSlaDays() : (int) $days;
    return date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));
}

function installationWorkflowResolveHierarchy($coordinatorId)
{
    $coordinatorId = (int) $coordinatorId;
    $result = [
        'coordinator_id' => $coordinatorId,
        'manager_id' => 0,
        'gm_id' => 0,
        'business_head_id' => 0,
    ];

    if ($coordinatorId <= 0) {
        return $result;
    }

    $coord = getRecord("SELECT id, UnderByManager FROM tbl_users WHERE id='$coordinatorId' AND Status='1' LIMIT 1");
    if (!$coord) {
        return $result;
    }

    $managerId = (int) ($coord['UnderByManager'] ?? 0);
    $result['manager_id'] = $managerId;

    if ($managerId > 0) {
        $mgr = getRecord("SELECT id, UnderByGrManager FROM tbl_users WHERE id='$managerId' AND Status='1' LIMIT 1");
        if ($mgr) {
            $result['gm_id'] = (int) ($mgr['UnderByGrManager'] ?? 0);
        }
    }

    if ($result['gm_id'] > 0) {
        $gm = getRecord("SELECT id, UnderByBusHead FROM tbl_users WHERE id='{$result['gm_id']}' AND Status='1' LIMIT 1");
        if ($gm) {
            $result['business_head_id'] = (int) ($gm['UnderByBusHead'] ?? 0);
        }
    }

    return $result;
}

function installationWorkflowLogAction($flowId, $actionBy, $actionType, $remarks)
{
    global $conn;

    $flowId = (int) $flowId;
    $actionBy = (int) $actionBy;
    $actionType = $conn->real_escape_string((string) $actionType);
    $remarks = $conn->real_escape_string((string) $remarks);

    return mysqli_query($conn, "
        INSERT INTO tbl_installation_actions
        (flow_id, action_by, action_type, remarks, action_date)
        VALUES
        ('$flowId', '$actionBy', '$actionType', '$remarks', NOW())
    ");
}

function installationWorkflowGetFlow($flowId)
{
    $flowId = (int) $flowId;
    return getRecord("SELECT * FROM tbl_installation_flow WHERE id='$flowId' LIMIT 1");
}

function installationWorkflowActiveDueDate(array $flow)
{
    $stage = $flow['current_stage'] ?? '';

    switch ($stage) {
        case 'COORDINATOR':
            if (!empty($flow['coordinator_due_date'])) {
                return $flow['coordinator_due_date'];
            }
            break;
        case 'MANAGER':
            if (!empty($flow['manager_due_date'])) {
                return $flow['manager_due_date'];
            }
            break;
        case 'GENERAL_MANAGER':
        case 'GM':
            if (!empty($flow['gm_due_date'])) {
                return $flow['gm_due_date'];
            }
            break;
        case 'BUSINESS_HEAD':
            if (!empty($flow['business_head_due_date'])) {
                return $flow['business_head_due_date'];
            }
            break;
    }

    if (!empty($flow['stage_start_date']) && !empty($flow['allowed_days'])) {
        return date('Y-m-d H:i:s', strtotime($flow['stage_start_date'] . ' +' . (int) $flow['allowed_days'] . ' days'));
    }

    return null;
}

function installationWorkflowPendingDays(array $flow)
{
    $due = installationWorkflowActiveDueDate($flow);
    if ($due) {
        return max(0, (int) floor((time() - strtotime($due)) / 86400));
    }

    if (!empty($flow['stage_start_date'])) {
        return max(0, (int) getRecord("SELECT DATEDIFF(NOW(), '" . $flow['stage_start_date'] . "') AS d")['d']);
    }

    return 0;
}

function installationWorkflowOverdueDays(array $flow)
{
    $due = installationWorkflowActiveDueDate($flow);
    if (!$due) {
        return 0;
    }

    if (time() <= strtotime($due)) {
        return 0;
    }

    return (int) ceil((time() - strtotime($due)) / 86400);
}

function installationWorkflowIsOverdue(array $flow)
{
    $stage = $flow['current_stage'] ?? '';

    if ($stage === 'COORDINATOR' && !empty($flow['manager_due_date'])) {
        return installationWorkflowManagerOverdueDays($flow) > 0;
    }

    return installationWorkflowOverdueDays($flow) > 0;
}

function installationWorkflowHasPendingExtension($flowId, $requestedRole = null)
{
    $flowId = (int) $flowId;
    $roleSql = '';
    if ($requestedRole !== null) {
        if ($requestedRole === 'MANAGER') {
            $roleSql = " AND IFNULL(requested_role,'') IN ('MANAGER', '')";
        } else {
            $roleSql = " AND requested_role='" . addslashes((string) $requestedRole) . "'";
        }
    }

    return getRow("
        SELECT id FROM tbl_installation_extensions
        WHERE flow_id='$flowId'
        AND status='PENDING'
        $roleSql
    ") > 0;
}

function installationWorkflowEnsureFlowHierarchy($flowId)
{
    global $conn;

    $flowId = (int) $flowId;
    $flow = installationWorkflowGetFlow($flowId);
    if (!$flow) {
        return;
    }

    $coordinatorId = (int) ($flow['assigned_to'] ?? 0);
    $managerId = (int) ($flow['manager_id'] ?? 0);
    $gmId = (int) ($flow['gm_id'] ?? 0);
    $bhId = (int) ($flow['business_head_id'] ?? 0);

    if ($managerId <= 0 && $coordinatorId > 0) {
        $coord = getRecord("SELECT UnderByManager FROM tbl_users WHERE id='$coordinatorId' LIMIT 1");
        $managerId = (int) ($coord['UnderByManager'] ?? 0);
    }

    if ($gmId <= 0 && $managerId > 0) {
        $mgr = getRecord("SELECT UnderByGrManager FROM tbl_users WHERE id='$managerId' LIMIT 1");
        $gmId = (int) ($mgr['UnderByGrManager'] ?? 0);
    }

    if ($bhId <= 0 && $gmId > 0) {
        $gm = getRecord("SELECT UnderByBusHead FROM tbl_users WHERE id='$gmId' LIMIT 1");
        $bhId = (int) ($gm['UnderByBusHead'] ?? 0);
    }

    if ($managerId > 0 || $gmId > 0 || $bhId > 0) {
        $conn->query("
            UPDATE tbl_installation_flow
            SET manager_id = IF(manager_id > 0, manager_id, '$managerId'),
                gm_id = IF(gm_id > 0, gm_id, '$gmId'),
                business_head_id = IF(business_head_id > 0, business_head_id, '$bhId')
            WHERE id='$flowId'
        ");
    }
}

function installationWorkflowLatestExtensionStatus($flowId, $requestedRole = null)
{
    $flowId = (int) $flowId;
    $roleSql = '';
    if ($requestedRole !== null) {
        $roleSql = " AND requested_role='" . addslashes((string) $requestedRole) . "'";
    }

    $row = getRecord("
        SELECT status FROM tbl_installation_extensions
        WHERE flow_id='$flowId'
        $roleSql
        ORDER BY id DESC
        LIMIT 1
    ");

    return $row['status'] ?? '';
}

function installationWorkflowEscalateFlow($flowId, $nextStage, $remark)
{
    global $conn;

    $flowId = (int) $flowId;
    $flow = installationWorkflowGetFlow($flowId);
    if (!$flow || (int) ($flow['is_completed'] ?? 0) === 1) {
        return false;
    }

    $now = date('Y-m-d H:i:s');
    $slaEnd = installationWorkflowAddDays();
    $status = 'ACTIVE';
    $extraSql = '';

    switch ($nextStage) {
        case 'MANAGER':
            $status = 'ACTIVE';
            if (empty($flow['manager_due_date'])) {
                $extraSql = ",
                manager_assigned_at='$now',
                manager_due_date='$slaEnd',
                stage_start_date='$now',
                allowed_days='" . installationWorkflowSlaDays() . "'";
            } else {
                $extraSql = ",
                manager_assigned_at=IFNULL(manager_assigned_at, '$now')";
            }
            break;
        case 'GENERAL_MANAGER':
            installationWorkflowEnsureFlowHierarchy($flowId);
            $flow = installationWorkflowGetFlow($flowId) ?: $flow;
            $gmId = (int) ($flow['gm_id'] ?? 0);
            $bhId = (int) ($flow['business_head_id'] ?? 0);
            $hierarchySql = '';
            if ($gmId > 0) {
                $hierarchySql .= ", gm_id='$gmId'";
            }
            if ($bhId > 0) {
                $hierarchySql .= ", business_head_id='$bhId'";
            }
            $extraSql = ",
                gm_due_date='$slaEnd',
                stage_start_date='$now',
                allowed_days='" . installationWorkflowSlaDays() . "'
                $hierarchySql";
            break;
        case 'BUSINESS_HEAD':
            installationWorkflowEnsureFlowHierarchy($flowId);
            $flow = installationWorkflowGetFlow($flowId) ?: $flow;
            $bhId = (int) ($flow['business_head_id'] ?? 0);
            $hierarchySql = $bhId > 0 ? ", business_head_id='$bhId'" : '';
            $extraSql = ",
                business_head_due_date='$slaEnd',
                stage_start_date='$now',
                allowed_days='" . installationWorkflowSlaDays() . "'
                $hierarchySql";
            break;
        case 'DISPUTE':
            $status = 'DISPUTED';
            $extraSql = ",
                stage_end_date='$now'";
            break;
    }

    $nextStage = $conn->real_escape_string($nextStage);
    $status = $conn->real_escape_string($status);
    $remark = $conn->real_escape_string($remark);

    $ok = mysqli_query($conn, "
        UPDATE tbl_installation_flow
        SET current_stage='$nextStage',
            status='$status'
            $extraSql
        WHERE id='$flowId'
        AND is_completed=0
    ");

    if ($ok) {
        installationWorkflowLogAction($flowId, 0, 'ESCALATED', $remark);
    }

    return (bool) $ok;
}

function installationWorkflowRunEscalations()
{
    global $conn;

    installationWorkflowEnsureSchema();

    $total = 0;
    for ($pass = 0; $pass < 3; $pass++) {
        $res = $conn->query("
            SELECT *
            FROM tbl_installation_flow
            WHERE is_completed=0
            AND IFNULL(status, '') NOT IN ('COMPLETED', 'DISPUTED')
            AND current_stage IN ('COORDINATOR', 'MANAGER', 'GENERAL_MANAGER', 'GM', 'BUSINESS_HEAD')
        ");

        if (!$res) {
            break;
        }

        $count = 0;
        while ($flow = $res->fetch_assoc()) {
            if (!installationWorkflowIsOverdue($flow)) {
                continue;
            }

            $stage = $flow['current_stage'];
            $next = null;
            $remark = '';

            if ($stage === 'COORDINATOR') {
                $mgrDue = $flow['manager_due_date'] ?? null;
                if ($mgrDue && strtotime($mgrDue) <= time()) {
                    $next = 'GENERAL_MANAGER';
                    $remark = 'Auto escalated to General Manager after manager due date';
                } else {
                    if (installationWorkflowHasPendingExtension($flow['id'], 'MANAGER')) {
                        continue;
                    }
                    $next = 'MANAGER';
                    $remark = 'Auto escalated to Manager after coordinator due date';
                }
            } elseif ($stage === 'MANAGER') {
                $next = 'GENERAL_MANAGER';
                $remark = 'Auto escalated to General Manager after manager due date';
            } elseif ($stage === 'GENERAL_MANAGER' || $stage === 'GM') {
                if (installationWorkflowHasPendingExtension($flow['id'], 'GM')) {
                    continue;
                }
                $next = 'BUSINESS_HEAD';
                $remark = 'Auto escalated to Business Head after GM due date';
            } elseif ($stage === 'BUSINESS_HEAD') {
                $next = 'DISPUTE';
                $remark = 'Auto escalated to Dispute after Business Head due date';
            }

            if ($next === 'GENERAL_MANAGER' || $next === 'BUSINESS_HEAD') {
                installationWorkflowEnsureFlowHierarchy($flow['id']);
            }

            if ($next && installationWorkflowEscalateFlow($flow['id'], $next, $remark)) {
                $count++;
            }
        }

        $total += $count;
        if ($count === 0) {
            break;
        }
    }

    return $total;
}

function installationWorkflowAssignCoordinator($sellId, $coordinatorId, $adminId)
{
    global $conn;

    installationWorkflowEnsureSchema();

    $sellId = (int) $sellId;
    $coordinatorId = (int) $coordinatorId;
    $adminId = (int) $adminId;

    if ($sellId <= 0 || $coordinatorId <= 0) {
        return ['ok' => false, 'message' => 'Invalid assignment data.'];
    }

    $sell = getRecord("SELECT id, CustId FROM tbl_sell WHERE id='$sellId' LIMIT 1");
    if (!$sell) {
        return ['ok' => false, 'message' => 'Delivery challan not found.'];
    }

    $existing = getRow("
        SELECT id FROM tbl_installation_flow
        WHERE SellId='$sellId' AND is_completed=0
    ");
    if ($existing > 0) {
        return ['ok' => false, 'message' => 'Installation workflow already active for this challan.'];
    }

    $hierarchy = installationWorkflowResolveHierarchy($coordinatorId);
    $custId = (int) $sell['CustId'];
    $now = date('Y-m-d H:i:s');
    $due = installationWorkflowAddDays();

    mysqli_begin_transaction($conn);

    try {
        $ok = mysqli_query($conn, "
            INSERT INTO tbl_installation_flow
            (
                CustId, SellId, current_stage,
                assigned_to, manager_id, gm_id, business_head_id,
                assigned_by, assigned_date, stage_start_date,
                coordinator_due_date, allowed_days, status, is_completed
            )
            VALUES
            (
                '$custId', '$sellId', 'COORDINATOR',
                '$coordinatorId', '{$hierarchy['manager_id']}', '{$hierarchy['gm_id']}', '{$hierarchy['business_head_id']}',
                '$adminId', '$now', '$now',
                '$due', '" . installationWorkflowSlaDays() . "', 'ACTIVE', 0
            )
        ");

        if (!$ok) {
            throw new Exception('Failed to create installation workflow.');
        }

        $flowId = (int) mysqli_insert_id($conn);
        installationWorkflowLogAction(
            $flowId,
            $adminId,
            'ASSIGNED',
            'Site assigned to coordinator for installation'
        );

        mysqli_commit($conn);
        return ['ok' => true, 'flow_id' => $flowId];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['ok' => false, 'message' => $e->getMessage()];
    }
}

function installationWorkflowMarkInstalled($flowId, $userId, $remark = 'Installation completed')
{
    global $conn;

    $flowId = (int) $flowId;
    $userId = (int) $userId;
    $now = date('Y-m-d H:i:s');

    mysqli_begin_transaction($conn);

    try {
        $ok = mysqli_query($conn, "
            UPDATE tbl_installation_flow
            SET is_completed=1,
                status='COMPLETED',
                current_stage='COMPLETED',
                installed_at='$now',
                stage_end_date='$now'
            WHERE id='$flowId'
            AND is_completed=0
        ");

        if (!$ok || mysqli_affected_rows($conn) === 0) {
            throw new Exception('Unable to mark installation completed.');
        }

        installationWorkflowLogAction($flowId, $userId, 'INSTALL_DONE', $remark);
        mysqli_commit($conn);
        return ['ok' => true];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['ok' => false, 'message' => $e->getMessage()];
    }
}

function installationWorkflowUserFullAccess($userId)
{
    $row = getRecord("SELECT Roll FROM tbl_users WHERE id='" . (int) $userId . "' LIMIT 1");
    return function_exists('adminUserHasFullMenuAccess')
        && adminUserHasFullMenuAccess((int) ($row['Roll'] ?? 0));
}

function installationWorkflowManagerListSql($userId)
{
    $userId = (int) $userId;
    $scope = installationWorkflowUserFullAccess($userId)
        ? ''
        : " AND f.manager_id='$userId'";

    return "
        f.is_completed=0
        AND (
            (
                f.current_stage='MANAGER'
                AND (f.manager_due_date IS NULL OR f.manager_due_date > NOW())
            )
            OR (
                f.current_stage='COORDINATOR'
                AND f.coordinator_due_date IS NOT NULL
                AND f.coordinator_due_date <= NOW()
                AND (f.manager_due_date IS NULL OR f.manager_due_date > NOW())
                AND NOT EXISTS (
                    SELECT 1 FROM tbl_installation_extensions ex
                    WHERE ex.flow_id = f.id
                    AND IFNULL(ex.requested_role,'') IN ('', 'MANAGER')
                    AND ex.status='APPROVED'
                )
            )
        )
        $scope
    ";
}

function installationWorkflowManagerIsReadOnly(array $flow)
{
    if ((int) ($flow['is_completed'] ?? 0) === 1) {
        return true;
    }

    if (($flow['current_stage'] ?? '') === 'COORDINATOR') {
        if (!empty($flow['manager_due_date']) && strtotime($flow['manager_due_date']) <= time()) {
            return true;
        }
        if (!empty($flow['coordinator_due_date']) && strtotime($flow['coordinator_due_date']) <= time()) {
            return true;
        }
    }

    if (($flow['current_stage'] ?? '') === 'MANAGER') {
        if (!empty($flow['manager_due_date']) && strtotime($flow['manager_due_date']) <= time()) {
            return true;
        }
    }

    return false;
}

function installationWorkflowManagerDisplayDueDate(array $flow)
{
    if (!empty($flow['manager_due_date'])) {
        return $flow['manager_due_date'];
    }

    if (!empty($flow['coordinator_due_date'])) {
        return $flow['coordinator_due_date'];
    }

    return installationWorkflowActiveDueDate($flow);
}

function installationWorkflowManagerOverdueDays(array $flow)
{
    $due = installationWorkflowManagerDisplayDueDate($flow);
    if (!$due || time() <= strtotime($due)) {
        return 0;
    }

    return (int) ceil((time() - strtotime($due)) / 86400);
}

function installationWorkflowGmScopeSql($userId, $alias = 'f')
{
    $userId = (int) $userId;
    if (installationWorkflowUserFullAccess($userId)) {
        return '1=1';
    }

    global $conn;
    $managerIds = [0];
    $res = $conn->query("SELECT id FROM tbl_users WHERE UnderByGrManager='$userId'");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $managerIds[] = (int) $row['id'];
        }
    }
    $managerIn = implode(',', array_unique($managerIds));

    return "(
        $alias.gm_id='$userId'
        OR $alias.manager_id IN ($managerIn)
        OR EXISTS (
            SELECT 1 FROM tbl_users c2
            WHERE c2.id = $alias.assigned_to
            AND c2.UnderByManager IN ($managerIn)
        )
        OR EXISTS (
            SELECT 1 FROM tbl_installation_extensions ex
            WHERE ex.flow_id = $alias.id
            AND (
                ex.gm_id='$userId'
                OR ex.requested_by IN ($managerIn)
            )
        )
    )";
}

function installationWorkflowGmOverdueMatchSql($alias = 'f')
{
    return "(
        ($alias.gm_due_date IS NOT NULL AND $alias.gm_due_date <= NOW())
        OR ($alias.manager_due_date IS NOT NULL AND $alias.manager_due_date <= NOW())
        OR ($alias.coordinator_due_date IS NOT NULL AND $alias.coordinator_due_date <= NOW())
    )";
}

function installationWorkflowGmListSql($userId)
{
    $scope = installationWorkflowGmScopeSql($userId);
    $overdue = installationWorkflowGmOverdueMatchSql('f');

    return "
        f.is_completed=0
        AND IFNULL(f.status, '') NOT IN ('COMPLETED')
        AND ($scope)
        AND (
            f.current_stage IN ('GENERAL_MANAGER', 'GM')
            OR ($overdue)
        )
    ";
}

function installationWorkflowGmIsReadOnly(array $flow)
{
    if ((int) ($flow['is_completed'] ?? 0) === 1) {
        return true;
    }

    return installationWorkflowGmOverdueDays($flow) > 0;
}

function installationWorkflowGmDisplayDueDate(array $flow)
{
    if (!empty($flow['gm_due_date'])) {
        return $flow['gm_due_date'];
    }

    if (!empty($flow['manager_due_date'])) {
        return $flow['manager_due_date'];
    }

    if (!empty($flow['coordinator_due_date'])) {
        return $flow['coordinator_due_date'];
    }

    return installationWorkflowActiveDueDate($flow);
}

function installationWorkflowGmOverdueDays(array $flow)
{
    $due = installationWorkflowGmDisplayDueDate($flow);
    if (!$due || time() <= strtotime($due)) {
        return 0;
    }

    return (int) ceil((time() - strtotime($due)) / 86400);
}

function installationWorkflowBhScopeSql($userId, $alias = 'f')
{
    $userId = (int) $userId;
    if (installationWorkflowUserFullAccess($userId)) {
        return '1=1';
    }

    global $conn;
    $gmIds = [0];
    $res = $conn->query("SELECT id FROM tbl_users WHERE UnderByBusHead='$userId'");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $gmIds[] = (int) $row['id'];
        }
    }
    $gmIn = implode(',', array_unique($gmIds));

    $managerIds = [0];
    if ($gmIn !== '0') {
        $res = $conn->query("SELECT id FROM tbl_users WHERE UnderByGrManager IN ($gmIn)");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $managerIds[] = (int) $row['id'];
            }
        }
    }
    $mgrIn = implode(',', array_unique($managerIds));

    return "(
        $alias.business_head_id='$userId'
        OR $alias.gm_id IN ($gmIn)
        OR $alias.manager_id IN ($mgrIn)
        OR EXISTS (
            SELECT 1 FROM tbl_users c2
            WHERE c2.id = $alias.assigned_to
            AND c2.UnderByManager IN ($mgrIn)
        )
        OR EXISTS (
            SELECT 1 FROM tbl_installation_extensions ex
            WHERE ex.flow_id = $alias.id
            AND (ex.business_head_id='$userId' OR ex.requested_by IN ($gmIn))
        )
    )";
}

function installationWorkflowBhListSql($userId)
{
    $scope = installationWorkflowBhScopeSql($userId);

    return "
        f.is_completed=0
        AND IFNULL(f.status, '') NOT IN ('COMPLETED')
        AND ($scope)
        AND (
            f.current_stage IN ('BUSINESS_HEAD', 'DISPUTE')
            OR (
                f.current_stage IN ('GENERAL_MANAGER', 'GM')
                AND f.gm_due_date IS NOT NULL
                AND f.gm_due_date <= NOW()
            )
        )
    ";
}

function installationWorkflowBhIsReadOnly(array $flow)
{
    if ((int) ($flow['is_completed'] ?? 0) === 1) {
        return true;
    }

    if (($flow['current_stage'] ?? '') === 'DISPUTE') {
        return true;
    }

    return installationWorkflowBhOverdueDays($flow) > 0;
}

function installationWorkflowBhDisplayDueDate(array $flow)
{
    if (($flow['current_stage'] ?? '') === 'BUSINESS_HEAD') {
        if (!empty($flow['business_head_due_date'])) {
            return $flow['business_head_due_date'];
        }
        if (!empty($flow['stage_start_date']) && !empty($flow['allowed_days'])) {
            return date('Y-m-d H:i:s', strtotime($flow['stage_start_date'] . ' +' . (int) $flow['allowed_days'] . ' days'));
        }
    }

    if (!empty($flow['gm_due_date']) && in_array($flow['current_stage'] ?? '', ['GENERAL_MANAGER', 'GM'], true)) {
        return $flow['gm_due_date'];
    }

    return installationWorkflowActiveDueDate($flow);
}

function installationWorkflowBhOverdueDays(array $flow)
{
    $due = installationWorkflowBhDisplayDueDate($flow);
    if (!$due || time() <= strtotime($due)) {
        return 0;
    }

    return (int) ceil((time() - strtotime($due)) / 86400);
}

function installationWorkflowCoordinatorListSql($userId)
{
    $userId = (int) $userId;
    $scope = installationWorkflowUserFullAccess($userId)
        ? ''
        : " AND f.assigned_to='$userId'";

    return "
        f.is_completed=0
        AND IFNULL(f.status, '') NOT IN ('DISPUTED', 'COMPLETED')
        AND f.current_stage NOT IN ('DISPUTE', 'COMPLETED')
        AND (
            f.current_stage='COORDINATOR'
            OR (
                f.coordinator_due_date IS NOT NULL
                AND f.coordinator_due_date <= NOW()
            )
        )
        $scope
    ";
}

function installationWorkflowCoordinatorIsReadOnly(array $flow)
{
    if ((int) ($flow['is_completed'] ?? 0) === 1) {
        return true;
    }

    if (!empty($flow['coordinator_due_date']) && strtotime($flow['coordinator_due_date']) <= time()) {
        return true;
    }

    return false;
}

function installationWorkflowCanAccessFlow($flowId, $userId, $context)
{
    $flowId = (int) $flowId;
    $userId = (int) $userId;

    if (installationWorkflowUserFullAccess($userId)) {
        return getRow("SELECT id FROM tbl_installation_flow WHERE id='$flowId' AND is_completed=0") > 0;
    }

    switch ($context) {
        case 'coordinator':
            $sql = installationWorkflowCoordinatorListSql($userId);
            break;
        case 'manager':
            $sql = installationWorkflowManagerListSql($userId);
            break;
        case 'gm':
            $sql = installationWorkflowGmListSql($userId);
            break;
        case 'bh':
            $sql = installationWorkflowBhListSql($userId);
            break;
        default:
            return false;
    }

    return getRow("SELECT f.id FROM tbl_installation_flow f WHERE f.id='$flowId' AND $sql") > 0;
}

function installationWorkflowRequestExtension($flowId, $userId, $requestedRole, $nextApproverRole, $approverId, $remarks = '')
{
    global $conn;

    $flowId = (int) $flowId;
    $userId = (int) $userId;
    $approverId = (int) $approverId;
    $days = installationWorkflowSlaDays();

    if (installationWorkflowHasPendingExtension($flowId, $requestedRole)) {
        return ['ok' => false, 'code' => 'ALREADY', 'message' => 'Extension request already pending.'];
    }

    $existing = getRow("
        SELECT id FROM tbl_installation_extensions
        WHERE flow_id='$flowId'
        AND requested_role='" . addslashes($requestedRole) . "'
        AND status IN ('PENDING', 'APPROVED')
    ");
    if ($existing > 0) {
        return ['ok' => false, 'code' => 'ALREADY', 'message' => 'Extension already requested or approved.'];
    }

    mysqli_begin_transaction($conn);

    try {
        $remarksEsc = $conn->real_escape_string((string) $remarks);
        $requestedRoleEsc = $conn->real_escape_string((string) $requestedRole);
        $nextApproverEsc = $conn->real_escape_string((string) $nextApproverRole);

        if ($requestedRole === 'MANAGER') {
            $insertSql = "
                INSERT INTO tbl_installation_extensions
                (flow_id, requested_by, gm_id, requested_role, next_approver_role, extension_days, remarks, status, requested_date)
                VALUES
                ('$flowId', '$userId', '$approverId', '$requestedRoleEsc', '$nextApproverEsc', '$days', '$remarksEsc', 'PENDING', NOW())
            ";
        } else {
            $insertSql = "
                INSERT INTO tbl_installation_extensions
                (flow_id, requested_by, business_head_id, requested_role, next_approver_role, extension_days, remarks, status, requested_date)
                VALUES
                ('$flowId', '$userId', '$approverId', '$requestedRoleEsc', '$nextApproverEsc', '$days', '$remarksEsc', 'PENDING', NOW())
            ";
        }

        $ok = mysqli_query($conn, $insertSql);

        if (!$ok) {
            throw new Exception('Failed to save extension request.');
        }

        installationWorkflowLogAction(
            $flowId,
            $userId,
            'EXTENSION_REQUEST',
            $remarks !== '' ? $remarks : 'Extension requested for ' . $days . ' days'
        );

        mysqli_commit($conn);
        return ['ok' => true];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['ok' => false, 'message' => $e->getMessage()];
    }
}

function installationWorkflowApproveExtension($extId, $approverId, $scopeSql = '1=1')
{
    global $conn;

    $extId = (int) $extId;
    $approverId = (int) $approverId;

    $ext = getRecord("
        SELECT e.*, f.*
        FROM tbl_installation_extensions e
        JOIN tbl_installation_flow f ON f.id=e.flow_id
        LEFT JOIN tbl_users mgr ON mgr.id=e.requested_by
        WHERE e.id='$extId'
        AND e.status='PENDING'
        AND f.is_completed=0
        AND ($scopeSql)
    ");

    if (!$ext) {
        return ['ok' => false, 'message' => 'Extension request not found or access denied.'];
    }

    $flowId = (int) $ext['flow_id'];
    $days = (int) $ext['extension_days'];
    $now = date('Y-m-d H:i:s');

    mysqli_begin_transaction($conn);

    try {
        mysqli_query($conn, "
            UPDATE tbl_installation_extensions
            SET status='APPROVED',
                approved_by='$approverId',
                approved_date='$now'
            WHERE id='$extId'
        ");

        if (($ext['requested_role'] ?? '') === 'MANAGER' || (
            ($ext['requested_role'] ?? '') === '' && ($ext['next_approver_role'] ?? '') !== 'BUSINESS_HEAD'
        )) {
            $baseDue = !empty($ext['manager_due_date']) ? $ext['manager_due_date'] : $now;
            if (strtotime($baseDue) < time()) {
                $baseDue = $now;
            }
            $newDue = date('Y-m-d H:i:s', strtotime($baseDue . " +$days days"));

            mysqli_query($conn, "
                UPDATE tbl_installation_flow
                SET manager_due_date='$newDue',
                    allowed_days = allowed_days + $days,
                    stage_start_date='$now',
                    current_stage='MANAGER',
                    status='ACTIVE'
                WHERE id='$flowId'
            ");
        } elseif (($ext['requested_role'] ?? '') === 'GM') {
            $baseDue = !empty($ext['gm_due_date']) ? $ext['gm_due_date'] : $now;
            $newDue = date('Y-m-d H:i:s', strtotime($baseDue . " +$days days"));

            mysqli_query($conn, "
                UPDATE tbl_installation_flow
                SET gm_due_date='$newDue',
                    allowed_days = allowed_days + $days,
                    stage_start_date='$now'
                WHERE id='$flowId'
            ");
        } else {
            mysqli_query($conn, "
                UPDATE tbl_installation_flow
                SET allowed_days = allowed_days + $days,
                    stage_start_date='$now'
                WHERE id='$flowId'
            ");
        }

        installationWorkflowLogAction(
            $flowId,
            $approverId,
            'EXTENSION_APPROVED',
            'Extension approved for ' . $days . ' days'
        );

        mysqli_commit($conn);
        return ['ok' => true];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['ok' => false, 'message' => $e->getMessage()];
    }
}

function installationWorkflowRejectExtension($extId, $approverId, $scopeSql = '1=1')
{
    global $conn;

    $extId = (int) $extId;
    $approverId = (int) $approverId;

    $ext = getRecord("
        SELECT e.*, f.id AS FlowId
        FROM tbl_installation_extensions e
        JOIN tbl_installation_flow f ON f.id=e.flow_id
        LEFT JOIN tbl_users mgr ON mgr.id=e.requested_by
        WHERE e.id='$extId'
        AND e.status='PENDING'
        AND f.is_completed=0
        AND ($scopeSql)
    ");

    if (!$ext) {
        return ['ok' => false, 'message' => 'Extension request not found or access denied.'];
    }

    mysqli_begin_transaction($conn);

    try {
        mysqli_query($conn, "
            UPDATE tbl_installation_extensions
            SET status='REJECTED',
                approved_by='$approverId',
                approved_date=NOW()
            WHERE id='$extId'
        ");

        installationWorkflowLogAction(
            (int) $ext['flow_id'],
            $approverId,
            'EXTENSION_REJECTED',
            'Extension request rejected'
        );

        mysqli_commit($conn);
        return ['ok' => true];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['ok' => false, 'message' => $e->getMessage()];
    }
}

function installationWorkflowReactivateDispute($flowId, $userId, $coordinatorId = 0)
{
    global $conn;

    $flowId = (int) $flowId;
    $userId = (int) $userId;
    $coordinatorId = (int) $coordinatorId;

    $flow = getRecord("
        SELECT * FROM tbl_installation_flow
        WHERE id='$flowId'
        AND current_stage='DISPUTE'
        AND status='DISPUTED'
    ");
    if (!$flow) {
        return ['ok' => false, 'message' => 'Dispute record not found.'];
    }

    if ($coordinatorId <= 0) {
        $coordinatorId = (int) ($flow['assigned_to'] ?? 0);
    }

    $hierarchy = installationWorkflowResolveHierarchy($coordinatorId);
    $now = date('Y-m-d H:i:s');
    $due = installationWorkflowAddDays();

    mysqli_begin_transaction($conn);

    try {
        mysqli_query($conn, "
            UPDATE tbl_installation_flow
            SET status='ACTIVE',
                current_stage='COORDINATOR',
                assigned_to='$coordinatorId',
                manager_id='{$hierarchy['manager_id']}',
                gm_id='{$hierarchy['gm_id']}',
                business_head_id='{$hierarchy['business_head_id']}',
                stage_start_date='$now',
                coordinator_due_date='$due',
                manager_assigned_at=NULL,
                manager_due_date=NULL,
                gm_due_date=NULL,
                allowed_days='" . installationWorkflowSlaDays() . "',
                stage_end_date=NULL
            WHERE id='$flowId'
        ");

        installationWorkflowLogAction(
            $flowId,
            $userId,
            'REACTIVATED',
            'Dispute site reassigned to coordinator; workflow restarted'
        );

        mysqli_commit($conn);
        return ['ok' => true];
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['ok' => false, 'message' => $e->getMessage()];
    }
}

function installationWorkflowRenderHistory($flowId)
{
    $flowId = (int) $flowId;
    $sql = "
        SELECT a.*, u.Fname AS ActionByName, coord.Fname AS CoordinatorName, f.assigned_to
        FROM tbl_installation_actions a
        JOIN tbl_installation_flow f ON f.id = a.flow_id
        LEFT JOIN tbl_users u ON u.id = a.action_by
        LEFT JOIN tbl_users coord ON coord.id = f.assigned_to
        WHERE a.flow_id = '$flowId'
        ORDER BY a.action_date DESC
    ";

    global $conn;
    $res = $conn->query($sql);
    if (!$res || $res->num_rows === 0) {
        return "<div class='text-center text-muted'>No history found</div>";
    }

    $rows = [];
    $coordinatorName = 'Not assigned';
    while ($row = $res->fetch_assoc()) {
        if ($coordinatorName === 'Not assigned' && !empty($row['CoordinatorName'])) {
            $coordinatorName = $row['CoordinatorName'];
        }
        $rows[] = $row;
    }

    $html = "<div class='history-summary mb-3'>";
    $html .= "<strong>Assigned Coordinator:</strong> " . htmlspecialchars($coordinatorName, ENT_QUOTES, 'UTF-8');
    $html .= "<br><small class='text-muted'>All follow-ups, escalations, and extension actions</small>";
    $html .= "</div>";

    foreach ($rows as $row) {
        $actionType = $row['action_type'];
        $badges = [
            'INSTALL_DONE' => ['Installed', 'badge-install'],
            'FOLLOW_UP' => ['Follow-up', 'badge-follow'],
            'EXTENSION_REQUEST' => ['Extension Request', 'badge-extension'],
            'EXTENSION_APPROVED' => ['Extension Approved', 'badge-install'],
            'EXTENSION_REJECTED' => ['Extension Rejected', 'badge-escalated'],
            'ESCALATED' => ['Escalated', 'badge-escalated'],
            'DISPUTE' => ['Dispute', 'badge-dispute'],
            'ASSIGNED' => ['Assigned', 'badge-default'],
            'REACTIVATED' => ['Reactivated', 'badge-default'],
        ];
        $badge = $badges[$actionType] ?? [$actionType, 'badge-default'];

        if ((int) $row['action_by'] === 0) {
            $byLine = 'System';
        } elseif ((int) $row['action_by'] === (int) $row['assigned_to']) {
            $byLine = htmlspecialchars($row['ActionByName'] ?: 'Unknown', ENT_QUOTES, 'UTF-8') . ' (Coordinator)';
        } else {
            $byLine = htmlspecialchars($row['ActionByName'] ?: 'Unknown', ENT_QUOTES, 'UTF-8');
        }

        $html .= "
        <div class='timeline-item'>
            <div class='timeline-dot'></div>
            <div class='timeline-card'>
                <h6><span class='badge {$badge[1]}'>{$badge[0]}</span> {$actionType}</h6>
                <p class='mb-1'>" . htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8') . "</p>
                <small>{$byLine} &bull; " . date('d M Y, h:i A', strtotime($row['action_date'])) . "</small>
            </div>
        </div>";
    }

    return $html;
}

function installationWorkflowStatusBadge(array $flow, $extStatus = '')
{
    if ($extStatus === 'APPROVED') {
        return "<span class='badge badge-success'>Extension Approved</span>";
    }
    if ($extStatus === 'PENDING') {
        return "<span class='badge badge-info'>Extension Pending</span>";
    }
    if (installationWorkflowIsOverdue($flow)) {
        return "<span class='badge badge-danger'>Overdue</span>";
    }
    if (($flow['current_stage'] ?? '') === 'COORDINATOR') {
        return "<span class='badge badge-warning'>Coordinator Pending</span>";
    }
    if (($flow['current_stage'] ?? '') === 'MANAGER') {
        return "<span class='badge badge-warning'>Manager Pending</span>";
    }
    if (($flow['current_stage'] ?? '') === 'GENERAL_MANAGER') {
        return "<span class='badge badge-warning'>GM Pending</span>";
    }

    return "<span class='badge badge-success'>Within SLA</span>";
}

function installationWorkflowTimelineCss()
{
    return <<<'CSS'
.timeline { position: relative; padding-left: 25px; }
.timeline::before { content: ''; position: absolute; left: 8px; top: 0; bottom: 0; width: 3px; background: #dee2e6; }
.timeline-item { position: relative; margin-bottom: 20px; padding-left: 25px; }
.timeline-dot { position: absolute; left: -2px; top: 5px; width: 14px; height: 14px; background: #007bff; border-radius: 50%; }
.timeline-card { background: #f8f9fa; border-radius: 6px; padding: 12px 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.timeline-card h6 { margin: 0 0 6px; font-size: 14px; font-weight: 600; }
.timeline-card small { color: #6c757d; font-size: 12px; }
.badge-follow { background: #17a2b8; color: #fff; }
.badge-install { background: #28a745; color: #fff; }
.badge-extension { background: #ffc107; color: #212529; }
.badge-escalated { background: #dc3545; color: #fff; }
.badge-dispute { background: #343a40; color: #fff; }
.badge-default { background: #6c757d; color: #fff; }
CSS;
}

function installationWorkflowResolveGmForManager(array $flow, $managerUserId)
{
    $gmId = (int) ($flow['gm_id'] ?? 0);
    if ($gmId > 0) {
        return $gmId;
    }

    $managerUserId = (int) $managerUserId;
    if ($managerUserId > 0) {
        $mgr = getRecord("SELECT UnderByGrManager FROM tbl_users WHERE id='$managerUserId' LIMIT 1");
        if (!empty($mgr['UnderByGrManager'])) {
            return (int) $mgr['UnderByGrManager'];
        }
    }

    $flowManagerId = (int) ($flow['manager_id'] ?? 0);
    if ($flowManagerId > 0) {
        $mgr = getRecord("SELECT UnderByGrManager FROM tbl_users WHERE id='$flowManagerId' LIMIT 1");
        if (!empty($mgr['UnderByGrManager'])) {
            return (int) $mgr['UnderByGrManager'];
        }
    }

    return 0;
}

function installationWorkflowManagerExtensionFilterSql($alias = 'e')
{
    return "(
        IFNULL($alias.requested_role,'') IN ('', 'MANAGER')
        AND IFNULL($alias.requested_role,'') != 'GM'
        AND IFNULL($alias.next_approver_role,'') NOT IN ('BUSINESS_HEAD')
    )";
}

function installationWorkflowGmExtensionScopeSql($userId)
{
    $userId = (int) $userId;
    if (installationWorkflowUserFullAccess($userId)) {
        return '1=1';
    }

    return "(
        e.gm_id='$userId'
        OR f.gm_id='$userId'
        OR mgr.UnderByGrManager='$userId'
        OR EXISTS (
            SELECT 1 FROM tbl_users m2
            WHERE m2.id = IF(f.manager_id > 0, f.manager_id, e.requested_by)
            AND m2.UnderByGrManager='$userId'
        )
    )";
}

function installationWorkflowBhExtensionFilterSql($alias = 'e')
{
    return "IFNULL($alias.requested_role,'')='GM'";
}

function installationWorkflowBhExtensionScopeSql($userId)
{
    $userId = (int) $userId;
    if (installationWorkflowUserFullAccess($userId)) {
        return '1=1';
    }

    return "(
        e.business_head_id='$userId'
        OR f.business_head_id='$userId'
        OR EXISTS (
            SELECT 1 FROM tbl_users g2
            WHERE g2.id = e.requested_by
            AND g2.UnderByBusHead='$userId'
        )
    )";
}

function installationWorkflowExtensionStatusFilterSql($statusFilter, $alias = 'e')
{
    $allowed = ['PENDING', 'APPROVED', 'REJECTED', 'ALL'];
    $statusFilter = strtoupper(trim((string) $statusFilter));
    if (!in_array($statusFilter, $allowed, true)) {
        $statusFilter = 'PENDING';
    }
    if ($statusFilter === 'ALL') {
        return "$alias.status IN ('PENDING','APPROVED','REJECTED')";
    }

    return "$alias.status='" . addslashes($statusFilter) . "'";
}

function installationWorkflowExtensionCustomerFilterSql($customerId, $alias = 'f')
{
    $customerId = (int) $customerId;
    if ($customerId <= 0) {
        return '';
    }

    return " AND $alias.CustId='$customerId'";
}

function installationWorkflowExtensionStatusBadge($status)
{
    switch (strtoupper((string) $status)) {
        case 'APPROVED':
            return "<span class='badge badge-success'>Approved</span>";
        case 'REJECTED':
            return "<span class='badge badge-danger'>Rejected</span>";
        default:
            return "<span class='badge badge-warning'>Pending</span>";
    }
}

function installationWorkflowExtensionCustomerOptions($scopeSql, $typeFilterSql, $requesterJoinAlias = 'mgr')
{
    global $conn;

    $sql = "
        SELECT DISTINCT cu.id, cu.Fname, cu.BeneficiaryId
        FROM tbl_installation_extensions e
        JOIN tbl_installation_flow f ON f.id=e.flow_id
        JOIN tbl_users cu ON cu.id=f.CustId
        JOIN tbl_users $requesterJoinAlias ON $requesterJoinAlias.id=e.requested_by
        WHERE e.status IN ('PENDING','APPROVED','REJECTED')
        AND $typeFilterSql
        AND ($scopeSql)
        ORDER BY cu.Fname ASC
    ";

    $res = $conn->query($sql);
    $options = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $options[] = $row;
        }
    }

    return $options;
}

function installationWorkflowBackfillDueDates()
{
    global $conn;

    $slaDays = installationWorkflowSlaDays();

    $conn->query("
        UPDATE tbl_installation_flow
        SET status='ACTIVE'
        WHERE is_completed=0
        AND IFNULL(status, '') NOT IN ('ACTIVE', 'ESCALATED', 'COMPLETED', 'DISPUTED')
    ");

    $conn->query("
        UPDATE tbl_installation_flow
        SET current_stage='GENERAL_MANAGER'
        WHERE is_completed=0
        AND current_stage='GM'
    ");

    $conn->query("
        UPDATE tbl_installation_flow
        SET coordinator_due_date = DATE_ADD(stage_start_date, INTERVAL allowed_days DAY)
        WHERE current_stage='COORDINATOR'
        AND is_completed=0
        AND coordinator_due_date IS NULL
        AND stage_start_date IS NOT NULL
    ");

    $conn->query("
        UPDATE tbl_installation_flow f
        JOIN tbl_users c ON c.id = f.assigned_to
        SET f.manager_id = IF(f.manager_id > 0, f.manager_id, IFNULL(c.UnderByManager, 0))
        WHERE f.is_completed=0
    ");

    $conn->query("
        UPDATE tbl_installation_flow f
        JOIN tbl_users m ON m.id = f.manager_id
        SET f.gm_id = IF(f.gm_id > 0, f.gm_id, IFNULL(m.UnderByGrManager, 0))
        WHERE f.is_completed=0 AND f.manager_id > 0
    ");

    $conn->query("
        UPDATE tbl_installation_flow f
        JOIN tbl_users g ON g.id = f.gm_id
        SET f.business_head_id = IF(f.business_head_id > 0, f.business_head_id, IFNULL(g.UnderByBusHead, 0))
        WHERE f.is_completed=0 AND f.gm_id > 0
    ");

    $conn->query("
        UPDATE tbl_installation_flow
        SET current_stage='MANAGER',
            status='ACTIVE',
            manager_assigned_at = IFNULL(manager_assigned_at, NOW())
        WHERE is_completed=0
        AND current_stage='COORDINATOR'
        AND manager_due_date IS NOT NULL
        AND coordinator_due_date IS NOT NULL
        AND coordinator_due_date <= NOW()
    ");

    $conn->query("
        UPDATE tbl_installation_flow f
        JOIN tbl_installation_extensions e ON e.flow_id = f.id
        SET f.current_stage='MANAGER',
            f.status='ACTIVE',
            f.manager_assigned_at = IFNULL(f.manager_assigned_at, NOW()),
            f.manager_due_date = IF(
                f.manager_due_date IS NULL,
                DATE_ADD(NOW(), INTERVAL " . installationWorkflowSlaDays() . " DAY),
                f.manager_due_date
            )
        WHERE f.is_completed=0
        AND f.current_stage='COORDINATOR'
        AND IFNULL(e.requested_role,'') IN ('', 'MANAGER')
        AND e.status='APPROVED'
    ");

    $conn->query("
        UPDATE tbl_installation_extensions e
        SET requested_role='MANAGER',
            next_approver_role='GENERAL_MANAGER'
        WHERE e.status='PENDING'
        AND IFNULL(e.requested_role,'') IN ('', 'MANAGER')
        AND IFNULL(e.next_approver_role,'') NOT IN ('BUSINESS_HEAD', 'GENERAL_MANAGER')
        AND IFNULL(e.requested_role,'') != 'GM'
    ");

    $conn->query("
        UPDATE tbl_installation_extensions e
        JOIN tbl_installation_flow f ON f.id = e.flow_id
        JOIN tbl_users mgr ON mgr.id = e.requested_by
        SET e.gm_id = IF(
            e.gm_id > 0,
            e.gm_id,
            IFNULL(NULLIF(mgr.UnderByGrManager, 0), IFNULL(NULLIF(f.gm_id, 0), 0))
        )
        WHERE e.status='PENDING'
        AND IFNULL(e.requested_role,'') IN ('', 'MANAGER')
    ");

    $conn->query("
        UPDATE tbl_installation_flow f
        JOIN tbl_users m ON m.id = f.manager_id
        SET f.gm_id = IF(f.gm_id > 0, f.gm_id, IFNULL(m.UnderByGrManager, 0))
        WHERE f.is_completed=0
        AND f.current_stage='GENERAL_MANAGER'
        AND f.gm_id=0
        AND f.manager_id > 0
    ");

    $conn->query("
        UPDATE tbl_installation_flow
        SET business_head_due_date = DATE_ADD(stage_start_date, INTERVAL allowed_days DAY)
        WHERE is_completed=0
        AND current_stage='BUSINESS_HEAD'
        AND business_head_due_date IS NULL
        AND stage_start_date IS NOT NULL
        AND allowed_days > 0
    ");
}

function installationWorkflowSyncOverdueManagerToGm()
{
    global $conn;

    $slaDays = installationWorkflowSlaDays();

    $conn->query("
        UPDATE tbl_installation_flow f
        LEFT JOIN tbl_users c ON c.id = f.assigned_to AND f.assigned_to > 0
        LEFT JOIN tbl_users m ON m.id = IF(f.manager_id > 0, f.manager_id, IFNULL(c.UnderByManager, 0))
            AND IF(f.manager_id > 0, f.manager_id, IFNULL(c.UnderByManager, 0)) > 0
        SET f.manager_id = IF(f.manager_id > 0, f.manager_id, IFNULL(c.UnderByManager, 0)),
            f.gm_id = IF(f.gm_id > 0, f.gm_id, IFNULL(m.UnderByGrManager, 0))
        WHERE f.is_completed = 0
        AND f.current_stage IN ('COORDINATOR', 'MANAGER')
        AND (
            (f.manager_due_date IS NOT NULL AND f.manager_due_date <= NOW())
            OR (f.coordinator_due_date IS NOT NULL AND f.coordinator_due_date <= NOW())
        )
    ");

    $conn->query("
        UPDATE tbl_installation_flow
        SET current_stage = 'GENERAL_MANAGER',
            status = 'ACTIVE',
            gm_due_date = IF(
                gm_due_date IS NULL OR gm_due_date <= NOW(),
                DATE_ADD(NOW(), INTERVAL $slaDays DAY),
                gm_due_date
            ),
            stage_start_date = IF(
                gm_due_date IS NULL OR gm_due_date <= NOW(),
                NOW(),
                stage_start_date
            ),
            allowed_days = $slaDays
        WHERE is_completed = 0
        AND current_stage IN ('COORDINATOR', 'MANAGER')
        AND (
            (manager_due_date IS NOT NULL AND manager_due_date <= NOW())
            OR (coordinator_due_date IS NOT NULL AND coordinator_due_date <= NOW())
        )
    ");
}

function installationWorkflowRecoverGmDisputeToBusinessHead()
{
    global $conn;

    $slaDays = installationWorkflowSlaDays();

    $conn->query("
        UPDATE tbl_installation_flow f
        LEFT JOIN tbl_users g ON g.id = f.gm_id AND f.gm_id > 0
        SET f.current_stage = 'BUSINESS_HEAD',
            f.status = 'ACTIVE',
            f.stage_end_date = NULL,
            f.business_head_id = IF(f.business_head_id > 0, f.business_head_id, IFNULL(g.UnderByBusHead, 0)),
            f.stage_start_date = NOW(),
            f.business_head_due_date = DATE_ADD(NOW(), INTERVAL $slaDays DAY),
            f.allowed_days = $slaDays
        WHERE f.is_completed = 0
        AND f.current_stage = 'DISPUTE'
        AND IFNULL(f.status, '') = 'DISPUTED'
        AND f.gm_due_date IS NOT NULL
        AND f.gm_due_date <= NOW()
    ");
}

function installationWorkflowSyncOverdueBusinessHeadToDispute()
{
    global $conn;

    $conn->query("
        UPDATE tbl_installation_flow
        SET current_stage='DISPUTE',
            status='DISPUTED',
            stage_end_date=NOW()
        WHERE is_completed=0
        AND current_stage='BUSINESS_HEAD'
        AND (
            (business_head_due_date IS NOT NULL AND business_head_due_date <= NOW())
            OR (
                business_head_due_date IS NULL
                AND stage_start_date IS NOT NULL
                AND allowed_days > 0
                AND DATE_ADD(stage_start_date, INTERVAL allowed_days DAY) <= NOW()
            )
        )
    ");
}

function installationWorkflowBootstrap()
{
    installationWorkflowEnsureSchema();
    installationWorkflowBackfillDueDates();
    installationWorkflowSyncOverdueManagerToGm();
    installationWorkflowRecoverGmDisputeToBusinessHead();
    installationWorkflowSyncOverdueBusinessHeadToDispute();
    installationWorkflowRunEscalations();
}
