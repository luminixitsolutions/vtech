<?php

function insuranceServiceComplaintClosedStatuses()
{
    return array('Close', 'Issue Solved');
}

function insuranceServiceComplaintEnsureSchema($conn)
{
    $check = $conn->query("SHOW COLUMNS FROM tbl_service_complaint LIKE 'InsuranceProcessDone'");
    if (!$check || $check->num_rows === 0) {
        @$conn->query("ALTER TABLE tbl_service_complaint ADD COLUMN InsuranceProcessDone VARCHAR(10) NOT NULL DEFAULT 'No'");
    }

    $checkDate = $conn->query("SHOW COLUMNS FROM tbl_service_complaint LIKE 'InsuranceProcessDate'");
    if (!$checkDate || $checkDate->num_rows === 0) {
        @$conn->query("ALTER TABLE tbl_service_complaint ADD COLUMN InsuranceProcessDate DATE NULL");
    }

    // ComplaintClose may be set on add form before fill-insurance-details; reset incomplete records.
    @$conn->query("UPDATE tbl_service_complaint SET ComplaintClose='No'
        WHERE ServiceType='Insurance'
        AND IFNULL(InsuranceProcessDone, 'No') = 'No'
        AND ComplaintClose = 'Yes'");
}

function insuranceServiceComplaintIsProcessDone(array $row)
{
    return ($row['InsuranceProcessDone'] ?? 'No') === 'Yes';
}

function insuranceServiceComplaintBaseSql()
{
    $closed = "'" . implode("','", insuranceServiceComplaintClosedStatuses()) . "'";

    return "SELECT tp.*, tc.Name AS IssueName, tb.Name AS BranchName, icm.Name AS InsuranceComplaintName
        FROM tbl_service_complaint tp
        INNER JOIN tbl_users tu ON tu.id = tp.CustId
        LEFT JOIN tbl_issues tc ON tc.id = tp.Issue
        LEFT JOIN tbl_branch tb ON tp.BranchId = tb.id
        LEFT JOIN tbl_common_master icm ON icm.id = tp.InsuranceComplaint
        WHERE tu.ProjectType = 1
        AND tp.ServiceType = 'Insurance'
        AND tp.ClainStatus IN ($closed)";
}

function insuranceServiceComplaintPendingSql()
{
    return insuranceServiceComplaintBaseSql() . " AND IFNULL(tp.InsuranceProcessDone, 'No') != 'Yes'";
}

function insuranceServiceComplaintDoneSql()
{
    return insuranceServiceComplaintBaseSql() . " AND tp.InsuranceProcessDone = 'Yes'";
}

function insuranceServiceComplaintGetById($conn, $id)
{
    $id = (int) $id;
    if ($id <= 0) {
        return null;
    }

    return getRecord("SELECT tp.*, icm.Name AS InsuranceComplaintName
        FROM tbl_service_complaint tp
        LEFT JOIN tbl_common_master icm ON icm.id = tp.InsuranceComplaint
        WHERE tp.id = '$id' AND tp.ServiceType = 'Insurance'");
}

function insuranceServiceComplaintCanFillDetails(array $row)
{
    if (empty($row) || ($row['ServiceType'] ?? '') !== 'Insurance') {
        return false;
    }
    if (!in_array($row['ClainStatus'] ?? '', insuranceServiceComplaintClosedStatuses(), true)) {
        return false;
    }

    return !insuranceServiceComplaintIsProcessDone($row);
}

function insuranceServiceComplaintYesNoOptions($selected = 'No')
{
    $html = '';
    foreach (array('No', 'Yes') as $opt) {
        $sel = ((string) $selected === $opt) ? ' selected' : '';
        $html .= '<option value="' . $opt . '"' . $sel . '>' . $opt . '</option>';
    }

    return $html;
}
