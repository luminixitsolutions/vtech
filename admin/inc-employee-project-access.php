<?php

function employeeProjectAccessParseIds($csv)
{
    if ($csv === null || $csv === '' || $csv === '0') {
        return [];
    }
    $parts = array_filter(array_map('intval', explode(',', (string) $csv)));
    return array_values(array_unique($parts));
}

function employeeProjectAccessBypass($userRow)
{
    $roll = (int) ($userRow['Roll'] ?? 0);
    $id = (int) ($userRow['id'] ?? 0);
    return $roll === 1 || $id === 1;
}

function employeeProjectAccessResolveUserRow($userRow)
{
    static $cache = [];
    if (!is_array($userRow)) {
        return [];
    }
    $id = (int) ($userRow['id'] ?? 0);
    if ($id <= 0) {
        return $userRow;
    }
    if (isset($cache[$id])) {
        return $cache[$id];
    }
    $u2 = getRecord("SELECT MulProjectId, MulProjectSubHeadId FROM tbl_user2 WHERE id='$id'");
    if (is_array($u2)) {
        if (array_key_exists('MulProjectId', $u2)) {
            $userRow['MulProjectId'] = $u2['MulProjectId'];
        }
        if (array_key_exists('MulProjectSubHeadId', $u2)) {
            $userRow['MulProjectSubHeadId'] = $u2['MulProjectSubHeadId'];
        }
    }
    $cache[$id] = $userRow;
    return $userRow;
}

function employeeProjectAccessProjectIds($userRow)
{
    $userRow = employeeProjectAccessResolveUserRow($userRow);
    return employeeProjectAccessParseIds($userRow['MulProjectId'] ?? '');
}

function employeeProjectAccessSubHeadIds($userRow)
{
    $userRow = employeeProjectAccessResolveUserRow($userRow);
    return employeeProjectAccessParseIds($userRow['MulProjectSubHeadId'] ?? '');
}

function employeeProjectAccessHasRestriction($userRow)
{
    if (employeeProjectAccessBypass($userRow)) {
        return false;
    }
    return count(employeeProjectAccessProjectIds($userRow)) > 0;
}

function employeeProjectAccessExpandSubHeadIds(array $projectIds)
{
    $projectIds = array_values(array_filter(array_map('intval', $projectIds)));
    if (empty($projectIds)) {
        return [];
    }
    $in = implode(',', $projectIds);
    $rows = getList("SELECT id FROM tbl_project_sub_head WHERE Status=1 AND UnderBy IN ($in) ORDER BY UnderBy, Name");
    $out = [];
    foreach ($rows as $row) {
        $out[] = (int) $row['id'];
    }
    return $out;
}

function employeeProjectAccessImplodeIds(array $ids)
{
    $ids = array_values(array_filter(array_map('intval', $ids)));
    return empty($ids) ? '0' : implode(',', $ids);
}

function employeeProjectAccessAssignedProjects($userRow)
{
    if (employeeProjectAccessBypass($userRow)) {
        return getList("SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=24 ORDER BY Name ASC");
    }
    $ids = employeeProjectAccessProjectIds($userRow);
    if (empty($ids)) {
        return getList("SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=24 ORDER BY Name ASC");
    }
    $in = implode(',', $ids);
    return getList("SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=24 AND id IN ($in) ORDER BY Name ASC");
}

function employeeProjectAccessAssignedSubHeads($userRow, $projectId = 0)
{
    $projectId = (int) $projectId;
    if (employeeProjectAccessBypass($userRow)) {
        if ($projectId > 0) {
            return getList("SELECT * FROM tbl_project_sub_head WHERE Status=1 AND UnderBy='$projectId' ORDER BY Name ASC");
        }
        return getList("SELECT * FROM tbl_project_sub_head WHERE Status=1 ORDER BY Name ASC");
    }

    $subIds = employeeProjectAccessSubHeadIds($userRow);
    $projIds = employeeProjectAccessProjectIds($userRow);

    if (empty($subIds) && !empty($projIds)) {
        $subIds = employeeProjectAccessExpandSubHeadIds($projIds);
    }

    if (empty($subIds)) {
        if ($projectId > 0) {
            return getList("SELECT * FROM tbl_project_sub_head WHERE Status=1 AND UnderBy='$projectId' ORDER BY Name ASC");
        }
        return getList("SELECT * FROM tbl_project_sub_head WHERE Status=1 ORDER BY Name ASC");
    }

    $in = implode(',', $subIds);
    $sql = "SELECT * FROM tbl_project_sub_head WHERE Status=1 AND id IN ($in)";
    if ($projectId > 0) {
        $sql .= " AND UnderBy='$projectId'";
    }
    $sql .= " ORDER BY Name ASC";
    return getList($sql);
}

function employeeProjectAccessCanViewProject($userRow, $projectId)
{
    if (employeeProjectAccessBypass($userRow)) {
        return true;
    }
    $projectId = (int) $projectId;
    if ($projectId <= 0) {
        return false;
    }
    $ids = employeeProjectAccessProjectIds($userRow);
    if (empty($ids)) {
        return true;
    }
    return in_array($projectId, $ids, true);
}

function employeeProjectAccessCanViewSubHead($userRow, $subHeadId)
{
    if (employeeProjectAccessBypass($userRow)) {
        return true;
    }
    $subHeadId = (int) $subHeadId;
    if ($subHeadId <= 0) {
        return false;
    }

    $subIds = employeeProjectAccessSubHeadIds($userRow);
    if (!empty($subIds)) {
        return in_array($subHeadId, $subIds, true);
    }

    $projIds = employeeProjectAccessProjectIds($userRow);
    if (empty($projIds)) {
        return true;
    }

    $row = getRecord("SELECT UnderBy FROM tbl_project_sub_head WHERE id='$subHeadId'");
    return $row && in_array((int) $row['UnderBy'], $projIds, true);
}

function employeeProjectAccessDeny($message, $redirectUrl = 'dashboard.php')
{
    $message = addslashes($message);
    $redirectUrl = htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8');
    echo "<script>alert('$message');window.location.href='$redirectUrl';</script>";
    exit;
}

function employeeProjectAccessEnforceProject($userRow, $projectId, $redirectUrl = 'dashboard.php')
{
    if (!employeeProjectAccessCanViewProject($userRow, $projectId)) {
        employeeProjectAccessDeny('You do not have access to this project.', $redirectUrl);
    }
}

function employeeProjectAccessEnforceSubHead($userRow, $subHeadId, $redirectUrl = 'dashboard.php')
{
    if (!employeeProjectAccessCanViewSubHead($userRow, $subHeadId)) {
        employeeProjectAccessDeny('You do not have access to this sub project.', $redirectUrl);
    }
}

function employeeProjectAccessResolveSubHeadsForSave(array $projectIds)
{
    return employeeProjectAccessImplodeIds(employeeProjectAccessExpandSubHeadIds($projectIds));
}
