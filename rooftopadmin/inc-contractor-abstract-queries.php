<?php

require_once __DIR__ . '/inc-project-abstract-queries.php';

function contractorAbstractEnsureSchema($conn)
{
    // Schema changes are handled by migrations/add_work_order_done_to_tbl_users.php only.
    return true;
}

function contractorAbstractHasColumn($conn, $table, $column)
{
    static $cache = array();
    $key = $table . '.' . $column;
    if (!array_key_exists($key, $cache)) {
        $tableEsc = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $columnEsc = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        try {
            $check = $conn->query("SHOW COLUMNS FROM `$tableEsc` LIKE '$columnEsc'");
            $cache[$key] = $check && $check->num_rows > 0;
        } catch (Exception $e) {
            $cache[$key] = false;
        }
    }

    return $cache[$key];
}

function contractorAbstractEscape($conn, $value)
{
    return projectAbstractEscape($conn, $value);
}

function contractorAbstractScopeSql($conn, $projectId, $subheadId, $dist = '', $alias = 'tu')
{
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $sql = " AND $alias.ProjectType=2 AND $alias.ProjectId='$projectId' AND $alias.ProjectSubHeadId='$subheadId' AND $alias.Roll=5";

    if ($dist !== '') {
        $sql .= " AND $alias.District='" . contractorAbstractEscape($conn, $dist) . "'";
    } else {
        $sql .= " AND $alias.District!=''";
    }

    return $sql;
}

function contractorAbstractContractorScopeSql($conn, $contractorId, $alias = 'tu')
{
    $contractorId = (int) $contractorId;
    if ($contractorId <= 0) {
        return '';
    }

    return " AND $alias.ContractorInstallerId='$contractorId'";
}

function contractorAbstractListSql($conn, $metric, $projectId, $subheadId, $dist = '', $contractorId = 0)
{
    $scope = contractorAbstractScopeSql($conn, $projectId, $subheadId, $dist, 'tu');
    $contractorScope = contractorAbstractContractorScopeSql($conn, $contractorId, 'tu');
    $today = date('Y-m-d');

    if ($metric === 'app_received') {
        return "SELECT tu.* FROM tbl_users tu WHERE 1=1 $scope";
    }

    if ($metric === 'work_order_done') {
        if (contractorAbstractHasColumn($conn, 'tbl_users', 'WorkOrderDone')) {
            return "SELECT tu.* FROM tbl_users tu WHERE tu.WorkOrderDone='Yes' $scope";
        }

        if (contractorAbstractHasColumn($conn, 'tbl_users', 'WoNo')) {
            return "SELECT tu.* FROM tbl_users tu WHERE TRIM(IFNULL(tu.WoNo,''))!='' $scope";
        }

        return "SELECT tu.* FROM tbl_users tu WHERE 1=0";
    }

    if ($metric === 'work_order_pending') {
        if (contractorAbstractHasColumn($conn, 'tbl_users', 'WorkOrderDone')) {
            return "SELECT tu.* FROM tbl_users tu WHERE IFNULL(tu.WorkOrderDone,'No')!='Yes' AND tu.FieldSurveyDetails!=2 $scope";
        }

        if (contractorAbstractHasColumn($conn, 'tbl_users', 'WoNo')) {
            return "SELECT tu.* FROM tbl_users tu WHERE TRIM(IFNULL(tu.WoNo,''))='' AND tu.FieldSurveyDetails!=2 $scope";
        }

        return "SELECT tu.* FROM tbl_users tu WHERE tu.FieldSurveyDetails!=2 $scope";
    }

    if ($metric === 'jsr_pending') {
        return "SELECT tu.* FROM tbl_users tu WHERE tu.FieldSurveyDetails=0 $scope";
    }

    if ($metric === 'dispatch_pending') {
        return projectAbstractListSql($conn, 'dispatchpending', $projectId, $subheadId, $dist, '');
    }

    if ($metric === 'material_dispatch') {
        return "SELECT tu.* FROM tbl_users tu
            INNER JOIN tbl_rooftop_sell ts ON ts.CustId=tu.id AND ts.Inst_Dispatcher_Otp_Verify=1
            WHERE 1=1 $scope $contractorScope";
    }

    if ($metric === 'cancel_sites') {
        return "SELECT tu.* FROM tbl_users tu WHERE tu.FieldSurveyDetails=2 $scope $contractorScope";
    }

    if ($metric === 'ic_done') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.FieldSurveyDetails=1 $scope $contractorScope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes'
            )";
    }

    if ($metric === 'ic_pending') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.FieldSurveyDetails=1 $scope $contractorScope
            AND EXISTS (
                SELECT 1 FROM tbl_rooftop_sell ts
                WHERE ts.CustId=tu.id AND ts.Inst_Dispatcher_Otp_Verify=1
            )
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes'
            )";
    }

    if ($metric === 'data_upload_done') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE 1=1 $scope $contractorScope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.DataUploadStatus='Yes'
            )";
    }

    if ($metric === 'data_upload_pending') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.FieldSurveyDetails=1 $scope $contractorScope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes'
            )
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti2
                WHERE ti2.CustId=tu.id AND ti2.DataUploadStatus='Yes'
            )";
    }

    if ($metric === 'inspection_done') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.FieldSurveyDetails=1 $scope $contractorScope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.PoInspection='Yes'
            )";
    }

    if ($metric === 'inspection_pending') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.FieldSurveyDetails=1 $scope $contractorScope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.DataUploadStatus='Yes'
            )
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti2
                WHERE ti2.CustId=tu.id AND ti2.PoInspection='Yes' AND ti2.Type=2
            )";
    }

    if ($metric === 'today_installation') {
        return "SELECT tu.* FROM tbl_users tu
            INNER JOIN tbl_installations ti ON ti.CustId=tu.id AND ti.InstallStatus='Yes'
            WHERE DATE(ti.InstallationDate)='$today' $scope $contractorScope";
    }

    if ($metric === 'ic_planning_today') {
        if (!contractorAbstractHasColumn($conn, 'tbl_users', 'InstallationDate')) {
            return "SELECT tu.* FROM tbl_users tu WHERE 1=0";
        }

        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.InstallationDate='$today' $scope $contractorScope
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes'
            )";
    }

    return '';
}

function contractorAbstractCount($conn, $metric, $projectId, $subheadId, $dist = '', $contractorId = 0)
{
    $listSql = contractorAbstractListSql($conn, $metric, $projectId, $subheadId, $dist, $contractorId);
    if ($listSql === '') {
        return 0;
    }

    try {
        if ($metric === 'material_dispatch') {
            $countSql = "SELECT COUNT(DISTINCT contractor_scope.id) AS cnt FROM ($listSql) contractor_scope";
        } else {
            $countSql = "SELECT COUNT(*) AS cnt FROM ($listSql) contractor_scope";
        }

        $res = @$conn->query($countSql);
        if (!$res) {
            return 0;
        }

        $row = $res->fetch_assoc();
        return (int) (isset($row['cnt']) ? $row['cnt'] : 0);
    } catch (Exception $e) {
        return 0;
    }
}

function contractorAbstractDistricts($conn, $projectId, $subheadId, $districtFilter = '')
{
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $sql = "SELECT DISTINCT tu.District FROM tbl_users tu
        WHERE tu.District!='' AND tu.ProjectType=2 AND tu.ProjectId='$projectId'
        AND tu.ProjectSubHeadId='$subheadId' AND tu.Roll=5";

    if ($districtFilter !== '' && $districtFilter !== 'all') {
        $replaceDistrict = str_replace(',', "','", $districtFilter);
        $sql .= " AND tu.District IN('$replaceDistrict')";
    }

    $sql .= ' ORDER BY tu.District ASC';
    $rows = getList($sql);

    return is_array($rows) ? $rows : array();
}

function contractorAbstractContractorsByDistrict($conn, $projectId, $subheadId, $dist)
{
    if (!contractorAbstractHasColumn($conn, 'tbl_users', 'ContractorInstallerId')) {
        return array();
    }

    $scope = contractorAbstractScopeSql($conn, $projectId, $subheadId, $dist, 'tu');
    $sql = "SELECT DISTINCT tu.ContractorInstallerId AS id, tc.Fname, tc.Lname
        FROM tbl_users tu
        INNER JOIN tbl_users tc ON tc.id=tu.ContractorInstallerId
        WHERE tu.ContractorInstallerId>0 $scope
        ORDER BY tc.Fname ASC, tc.Lname ASC";

    $rows = getList($sql);

    return is_array($rows) ? $rows : array();
}

function contractorAbstractTotalSites($conn, $projectId, $subheadId, $districtFilter = '')
{
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $sql = "SELECT COUNT(*) AS cnt FROM tbl_users tu WHERE tu.Roll=5 AND tu.ProjectType=2 AND tu.ProjectId='$projectId' AND tu.ProjectSubHeadId='$subheadId' AND tu.District!=''";

    if ($districtFilter !== '' && $districtFilter !== 'all') {
        $replaceDistrict = str_replace(',', "','", $districtFilter);
        $sql .= " AND tu.District IN('$replaceDistrict')";
    }

    $row = getRecord($sql);
    return (int) (is_array($row) && isset($row['cnt']) ? $row['cnt'] : 0);
}

function contractorAbstractContractorName($contractor)
{
    $fname = (is_array($contractor) && isset($contractor['Fname'])) ? $contractor['Fname'] : '';
    $lname = (is_array($contractor) && isset($contractor['Lname'])) ? $contractor['Lname'] : '';

    return trim((string) $fname . ' ' . (string) $lname);
}

function contractorAbstractMetricTitle($metric)
{
    $titles = array(
        'app_received' => 'Application Received',
        'work_order_done' => 'Work Order Done',
        'work_order_pending' => 'Work Order Pending',
        'jsr_pending' => 'JSR Pending',
        'dispatch_pending' => 'Dispatch Pending',
        'material_dispatch' => 'Material Dispatch',
        'cancel_sites' => 'Cancel Sites',
        'ic_done' => 'I & C Done',
        'ic_pending' => 'I & C Pending',
        'data_upload_done' => 'Data Upload Done',
        'data_upload_pending' => 'Data Upload Pending',
        'inspection_done' => 'Inspection Done',
        'inspection_pending' => 'Inspection Pending',
        'today_installation' => 'Today Installation Done',
        'ic_planning_today' => 'I & C Planning Today',
    );

    return isset($titles[$metric]) ? $titles[$metric] : ucwords(str_replace('_', ' ', $metric));
}

function contractorAbstractCountLink($count, $metric, $projectId, $subheadId, $dist, $contractorId = 0, $contractorName = '')
{
    $count = (int) $count;
    if ($count <= 0) {
        return '<span class="count-value">0</span>';
    }

    $title = contractorAbstractMetricTitle($metric);
    if ($dist !== '') {
        $title .= ' - ' . $dist;
    }
    if ($contractorName !== '') {
        $title .= ' - ' . $contractorName;
    }

    $params = array(
        'roll' => $metric,
        'projid' => (int) $projectId,
        'subheadid' => (int) $subheadId,
        'dist' => $dist,
        'contractor_id' => (int) $contractorId,
        'title' => $title,
    );

    return '<a class="count-value" href="total-contractor-beneficiary.php?' . http_build_query($params) . '" target="_blank">' . $count . '</a>';
}
