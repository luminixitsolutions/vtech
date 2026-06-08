<?php

function projectAbstractEscape($conn, $value)
{
    return mysqli_real_escape_string($conn, (string) $value);
}

function projectAbstractScopeSql($conn, $projectId, $subheadId, $dist = '', $alias = 'tu')
{
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $sql = " AND $alias.ProjectType=1 AND $alias.ProjectId='$projectId' AND $alias.ProjectSubHeadId='$subheadId'";

    if ($dist !== '') {
        $sql .= " AND $alias.District='" . projectAbstractEscape($conn, $dist) . "'";
    } else {
        $sql .= " AND $alias.District!=''";
    }

    return $sql;
}

function projectAbstractListSql($conn, $roll, $projectId, $subheadId, $dist = '', $val2 = '')
{
    $projectId = (int) $projectId;
    $subheadId = (int) $subheadId;
    $scope = projectAbstractScopeSql($conn, $projectId, $subheadId, $dist, 'tu');
    $val2Esc = projectAbstractEscape($conn, $val2);

    if ($roll === 'totapp') {
        return "SELECT tu.* FROM tbl_users tu WHERE 1=1 $scope";
    }

    if ($roll === 'capacity') {
        return "SELECT tu.* FROM tbl_users tu WHERE tu.PumpCapacity='$val2Esc' $scope";
    }

    if ($roll === 'surveydone' || $roll === 'surveypending') {
        return "SELECT tu.* FROM tbl_users tu WHERE tu.FieldSurveyDetails='$val2Esc' $scope";
    }

    if ($roll === 'surveyrejected') {
        return "SELECT tu.* FROM tbl_users tu WHERE tu.SurveyMatch='$val2Esc' $scope";
    }

    if ($roll === 'deliverychallan') {
        return "SELECT tu.* FROM tbl_users tu WHERE 1=1 $scope
            AND EXISTS (SELECT 1 FROM tbl_sell ts WHERE ts.CustId=tu.id AND ts.SellType='Challan')";
    }

    if ($roll === 'dispatch') {
        return "SELECT tu.* FROM tbl_users tu WHERE 1=1 $scope
            AND EXISTS (SELECT 1 FROM tbl_sell ts WHERE ts.CustId=tu.id)";
    }

    if ($roll === 'dispatchpending') {
        return "SELECT tu.* FROM tbl_users tu
            LEFT JOIN tbl_sell ts ON tu.id=ts.CustId AND ts.Inst_Dispatcher_Otp_Verify=1
            WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope AND ts.CustId IS NULL";
    }

    if ($roll === 'installation') {
        if ($val2 === 'Yes') {
            return "SELECT tu.* FROM tbl_users tu
                WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
                AND EXISTS (
                    SELECT 1 FROM tbl_installations ti
                    WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes' AND ti.Type=2
                )";
        }

        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes' AND ti.Type=2
            )";
    }

    if ($roll === 'installationpending') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
            AND (
                (
                    EXISTS (SELECT 1 FROM tbl_sell ts WHERE ts.CustId=tu.id)
                    AND NOT EXISTS (
                        SELECT 1 FROM tbl_installations ti
                        WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes' AND ti.Type=2
                    )
                )
                OR NOT EXISTS (
                    SELECT 1 FROM tbl_sell ts
                    WHERE ts.CustId=tu.id AND ts.Inst_Dispatcher_Otp_Verify=1
                )
            )";
    }

    if ($roll === 'dataupload') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE 1=1 $scope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.DataUploadStatus='$val2Esc'
            )";
    }

    if ($roll === 'datauploadpending') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InstallStatus='Yes' AND ti.Type=2
            )
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.DataUploadStatus='Yes'
            )";
    }

    if ($roll === 'inspection') {
        if ($val2 === 'Yes') {
            return "SELECT tu.* FROM tbl_users tu
                WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
                AND EXISTS (
                    SELECT 1 FROM tbl_installations ti
                    WHERE ti.CustId=tu.id AND ti.PoInspection='Yes' AND ti.Type=2
                )";
        }

        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.PoInspection='Yes' AND ti.Type=2
            )";
    }

    if ($roll === 'inspectionpending') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE tu.Roll=5 AND tu.FieldSurveyDetails=1 $scope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.DataUploadStatus='Yes'
            )
            AND NOT EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.PoInspection='Yes' AND ti.Type=2
            )";
    }

    if ($roll === 'inspectiondis') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE 1=1 $scope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.InspectionDiscrepancy='$val2Esc'
            )";
    }

    if ($roll === 'dcr') {
        return "SELECT tu.* FROM tbl_users tu
            WHERE 1=1 $scope
            AND EXISTS (
                SELECT 1 FROM tbl_installations ti
                WHERE ti.CustId=tu.id AND ti.DcrVerify='$val2Esc'
            )";
    }

    return '';
}

function projectAbstractCount($conn, $roll, $projectId, $subheadId, $dist = '', $val2 = '')
{
    $listSql = projectAbstractListSql($conn, $roll, $projectId, $subheadId, $dist, $val2);
    if ($listSql === '') {
        return 0;
    }

    $row = getRecord("SELECT COUNT(*) AS cnt FROM ($listSql) abstract_scope");
    return (int) ($row['cnt'] ?? 0);
}
