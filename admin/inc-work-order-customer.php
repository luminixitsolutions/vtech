<?php

function workOrderUsersHasColumn($conn, $column)
{
    static $cache = array();
    $key = 'users.' . $column;
    if (!array_key_exists($key, $cache)) {
        $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        $check = $conn->query("SHOW COLUMNS FROM tbl_users LIKE '$column'");
        $cache[$key] = $check && $check->num_rows > 0;
    }
    return $cache[$key];
}

function workOrderInstallHasColumn($conn, $column)
{
    static $cache = array();
    $key = 'install.' . $column;
    if (!array_key_exists($key, $cache)) {
        $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        $check = $conn->query("SHOW COLUMNS FROM tbl_installations LIKE '$column'");
        $cache[$key] = $check && $check->num_rows > 0;
    }
    return $cache[$key];
}

function workOrderCustomerUsesInstallations($conn)
{
    return workOrderInstallHasColumn($conn, 'WorkOrderDone');
}

function workOrderCustomerIsSupported($conn)
{
    return workOrderCustomerUsesInstallations($conn) || workOrderUsersHasColumn($conn, 'WorkOrderDone');
}

function workOrderCustomerEnsureSchema($conn)
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    @$conn->query("SET SESSION sql_mode = ''");

    if (!workOrderInstallHasColumn($conn, 'WorkOrderDone')) {
        @$conn->query(
            "ALTER TABLE tbl_installations ADD COLUMN WorkOrderDone VARCHAR(10) NOT NULL DEFAULT 'No' AFTER InstallStatus"
        );
    }

    if (!workOrderInstallHasColumn($conn, 'WorkOrderDoneDate')) {
        $after = workOrderInstallHasColumn($conn, 'WorkOrderDone') ? 'WorkOrderDone' : 'InstallStatus';
        @$conn->query(
            "ALTER TABLE tbl_installations ADD COLUMN WorkOrderDoneDate DATE NULL DEFAULT NULL AFTER `$after`"
        );
    }
}

function workOrderCustomerGetInstallationRow($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return null;
    }
    return getRecord("SELECT * FROM tbl_installations WHERE CustId='$custId' AND Type=2 ORDER BY id DESC LIMIT 1");
}

function workOrderCustomerLoad($conn, $custId)
{
    $custId = (int) $custId;
    $defaults = array(
        'WorkOrderDone' => 'No',
        'WorkOrderDoneDate' => '',
        'Fname' => '',
        'BeneficiaryId' => '',
    );
    if ($custId <= 0) {
        return $defaults;
    }

    $user = getRecord("SELECT Fname, Lname, BeneficiaryId FROM tbl_users WHERE id='$custId' AND Roll=5 LIMIT 1");
    if (!is_array($user)) {
        return $defaults;
    }

    $defaults['Fname'] = trim(($user['Fname'] ?? '') . ' ' . ($user['Lname'] ?? ''));
    $defaults['BeneficiaryId'] = (string) ($user['BeneficiaryId'] ?? '');

    if (workOrderCustomerUsesInstallations($conn)) {
        $hasDate = workOrderInstallHasColumn($conn, 'WorkOrderDoneDate');
        $inst = workOrderCustomerGetInstallationRow($conn, $custId);
        if (!is_array($inst)) {
            return $defaults;
        }
        $done = ((string) ($inst['WorkOrderDone'] ?? 'No') === 'Yes') ? 'Yes' : 'No';
        $date = '';
        if ($hasDate && $done === 'Yes' && !empty($inst['WorkOrderDoneDate']) && $inst['WorkOrderDoneDate'] !== '0000-00-00') {
            $date = (string) $inst['WorkOrderDoneDate'];
        }
        return array(
            'WorkOrderDone' => $done,
            'WorkOrderDoneDate' => $date,
            'Fname' => $defaults['Fname'],
            'BeneficiaryId' => $defaults['BeneficiaryId'],
        );
    }

    if (workOrderUsersHasColumn($conn, 'WorkOrderDone')) {
        $hasDate = workOrderUsersHasColumn($conn, 'WorkOrderDoneDate');
        $select = $hasDate
            ? "WorkOrderDone, WorkOrderDoneDate"
            : "WorkOrderDone";
        $row = getRecord("SELECT $select FROM tbl_users WHERE id='$custId' AND Roll=5 LIMIT 1");
        if (!is_array($row)) {
            return $defaults;
        }
        $done = ((string) ($row['WorkOrderDone'] ?? 'No') === 'Yes') ? 'Yes' : 'No';
        $date = '';
        if ($hasDate && $done === 'Yes' && !empty($row['WorkOrderDoneDate']) && $row['WorkOrderDoneDate'] !== '0000-00-00') {
            $date = (string) $row['WorkOrderDoneDate'];
        }
        return array(
            'WorkOrderDone' => $done,
            'WorkOrderDoneDate' => $date,
            'Fname' => $defaults['Fname'],
            'BeneficiaryId' => $defaults['BeneficiaryId'],
        );
    }

    if (workOrderUsersHasColumn($conn, 'WoNo')) {
        $row = getRecord("SELECT WoNo FROM tbl_users WHERE id='$custId' AND Roll=5 LIMIT 1");
        if (is_array($row) && trim((string) ($row['WoNo'] ?? '')) !== '') {
            $defaults['WorkOrderDone'] = 'Yes';
        }
    }

    return $defaults;
}

function workOrderCustomerEnsureInstallation($conn, $custId)
{
    $custId = (int) $custId;
    $inst = workOrderCustomerGetInstallationRow($conn, $custId);
    if (is_array($inst) && !empty($inst['id'])) {
        return (int) $inst['id'];
    }

    $user = getRecord("SELECT Fname, Lname, Phone, Address FROM tbl_users WHERE id='$custId' AND Roll=5 LIMIT 1");
    if (!is_array($user)) {
        return 0;
    }

    $fname = mysqli_real_escape_string($conn, trim(($user['Fname'] ?? '') . ' ' . ($user['Lname'] ?? '')));
    $phone = mysqli_real_escape_string($conn, (string) ($user['Phone'] ?? ''));
    $address = mysqli_real_escape_string($conn, (string) ($user['Address'] ?? ''));
    $createdDate = date('Y-m-d');

    $ok = $conn->query("INSERT INTO tbl_installations SET CustId='$custId', CustName='$fname', CellNo='$phone', Address='$address', Status='1', Type=2, CreatedDate='$createdDate'");
    if (!$ok) {
        return 0;
    }

    return (int) mysqli_insert_id($conn);
}

function workOrderCustomerSave($conn, $custId, $workOrderDone, $workOrderDoneDate)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return array('success' => false, 'message' => 'Invalid customer.');
    }
    if (!workOrderCustomerIsSupported($conn)) {
        return array('success' => false, 'message' => 'Work Order Done is not enabled. Please run migration add_work_order_done_to_tbl_installations.php');
    }

    $cust = getRecord("SELECT id FROM tbl_users WHERE id='$custId' AND Roll=5 LIMIT 1");
    if (!$cust) {
        return array('success' => false, 'message' => 'Customer not found.');
    }

    $workOrderDone = ((string) $workOrderDone === 'Yes') ? 'Yes' : 'No';
    $workOrderDoneDate = trim((string) $workOrderDoneDate);
    if ($workOrderDone !== 'Yes') {
        $workOrderDoneDate = '';
    } elseif ($workOrderDoneDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $workOrderDoneDate)) {
        return array('success' => false, 'message' => 'Please enter a valid work order date.');
    }

    $doneEsc = mysqli_real_escape_string($conn, $workOrderDone);

    if (workOrderCustomerUsesInstallations($conn)) {
        $instId = workOrderCustomerEnsureInstallation($conn, $custId);
        if ($instId <= 0) {
            return array('success' => false, 'message' => 'Failed to create installation record for this customer.');
        }
        if (workOrderInstallHasColumn($conn, 'WorkOrderDoneDate')) {
            $dateSql = $workOrderDoneDate !== '' ? "'" . mysqli_real_escape_string($conn, $workOrderDoneDate) . "'" : 'NULL';
            $ok = $conn->query("UPDATE tbl_installations SET WorkOrderDone='$doneEsc', WorkOrderDoneDate=$dateSql WHERE id='$instId'");
        } else {
            $ok = $conn->query("UPDATE tbl_installations SET WorkOrderDone='$doneEsc' WHERE id='$instId'");
        }
    } elseif (workOrderUsersHasColumn($conn, 'WorkOrderDone')) {
        if (workOrderUsersHasColumn($conn, 'WorkOrderDoneDate')) {
            $dateSql = $workOrderDoneDate !== '' ? "'" . mysqli_real_escape_string($conn, $workOrderDoneDate) . "'" : 'NULL';
            $ok = $conn->query("UPDATE tbl_users SET WorkOrderDone='$doneEsc', WorkOrderDoneDate=$dateSql WHERE id='$custId'");
        } else {
            $ok = $conn->query("UPDATE tbl_users SET WorkOrderDone='$doneEsc' WHERE id='$custId'");
        }
    } else {
        return array('success' => false, 'message' => 'Work Order Done column not found.');
    }

    if (!$ok) {
        return array('success' => false, 'message' => 'Failed to save work order details.');
    }

    return array('success' => true, 'message' => 'Work order details updated successfully.');
}

function workOrderCustomerDisplayLabel($conn, $userRow)
{
    $custId = (int) ($userRow['id'] ?? 0);

    if (workOrderCustomerUsesInstallations($conn) && $custId > 0) {
        $inst = workOrderCustomerGetInstallationRow($conn, $custId);
        if (is_array($inst)) {
            return ((string) ($inst['WorkOrderDone'] ?? 'No') === 'Yes') ? 'Yes' : 'No';
        }
        return 'No';
    }

    if (workOrderUsersHasColumn($conn, 'WorkOrderDone')) {
        return ((string) ($userRow['WorkOrderDone'] ?? 'No') === 'Yes') ? 'Yes' : 'No';
    }
    if (workOrderUsersHasColumn($conn, 'WoNo')) {
        return trim((string) ($userRow['WoNo'] ?? '')) !== '' ? 'Yes' : 'No';
    }
    return 'No';
}
