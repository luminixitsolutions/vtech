<?php
/**
 * Service complaints abstract — grouped by abstract type.
 * Closed = Close or Issue Solved; In Process = material hold; pending = neither closed status.
 */

function serviceAbstractClosedStatuses() {
    return array('Close', 'Issue Solved');
}

function serviceAbstractSqlTotalClosed($alias = 'ts') {
    return "SUM(CASE WHEN {$alias}.ClainStatus IN ('Close', 'Issue Solved') THEN 1 ELSE 0 END)";
}

function serviceAbstractSqlTotalPending($alias = 'ts') {
    return "SUM(CASE WHEN {$alias}.ClainStatus NOT IN ('Close', 'Issue Solved') THEN 1 ELSE 0 END)";
}

function serviceAbstractNormalizeType($type) {
    $t = strtolower(trim((string) $type));
    $map = array(
        'all' => 'all',
        'district' => 'district',
        'district_wise' => 'district',
        'project_head' => 'project_head',
        'project_head_wise' => 'project_head',
        'sub_project_head' => 'sub_project_head',
        'sub_project_head_wise' => 'sub_project_head',
    );
    return isset($map[$t]) ? $map[$t] : 'district';
}

function serviceAbstractDistrictKey($district) {
    $d = strtoupper(trim((string) $district));
    if ($d === 'AHILYANAGAR') {
        return 'AHILYANAGAR';
    }
    if ($d === 'AHMEDNAGAR' || $d === 'AHMEDNAAGAR') {
        return 'AHMEDNAGAR';
    }
    if ($d === 'MALEGAON' || $d === 'NASHIK') {
        return 'NASHIK (MALEGAON)';
    }
    if ($d === 'GADCHIROLI') {
        return 'GADCHIROLI';
    }
    if ($d === 'GONDIA') {
        return 'GONDIA';
    }
    if ($d === 'CHHATRAPATI SAMBHAJINAGAR' || $d === 'CH. SAMBHAJINAGAR' || $d === 'SAMBHAJINAGAR') {
        return 'CH. SAMBHAJINAGAR';
    }
    if ($d === 'DHULE') {
        return 'DHULE';
    }
    if ($d === 'JALNA') {
        return 'JALNA';
    }
    return $d;
}

function serviceAbstractDistrictRows() {
    return array(
        'AHILYANAGAR', 'AKOLA', 'BEED', 'BHANDARA', 'BULDHANA', 'CH. SAMBHAJINAGAR',
        'DHARASHIV', 'DHULE', 'GADCHIROLI', 'GONDIA', 'HINGOLI', 'JALGAON', 'JALNA',
        'KOLHAPUR', 'LATUR', 'NASHIK (MALEGAON)', 'NANDED', 'NANDURBAR', 'PARBHANI',
        'SOLAPUR', 'SANGLI', 'WASHIM',
    );
}

function serviceAbstractFiltersFromRequest() {
    return array(
        'abstract_type' => serviceAbstractNormalizeType(isset($_GET['abstract_type']) ? $_GET['abstract_type'] : 'district'),
        'projid' => isset($_GET['projid']) ? (int) $_GET['projid'] : 0,
        'subheadid' => isset($_GET['subheadid']) ? (int) $_GET['subheadid'] : 0,
        'district' => isset($_GET['district']) ? trim($_GET['district']) : '',
    );
}

function serviceAbstractDistrictWhereClause($district) {
    if ($district === '' || $district === 'all') {
        return '';
    }
    $col = "UPPER(TRIM(COALESCE(NULLIF(ts.District, ''), tu.District, '')))";
    if ($district === 'NASHIK (MALEGAON)') {
        return " AND $col IN ('NASHIK','MALEGAON')";
    }
    if ($district === 'AHMEDNAGAR') {
        return " AND $col IN ('AHMEDNAGAR','AHMEDNAAGAR')";
    }
    global $conn;
    $distEsc = $conn->real_escape_string(strtoupper($district));
    return " AND $col = '$distEsc'";
}

function serviceAbstractChallanDistrictWhereClause($district) {
    if ($district === '' || $district === 'all') {
        return '';
    }
    $col = "UPPER(TRIM(COALESCE(tu.District, '')))";
    if ($district === 'NASHIK (MALEGAON)') {
        return " AND $col IN ('NASHIK','MALEGAON')";
    }
    if ($district === 'AHMEDNAGAR') {
        return " AND $col IN ('AHMEDNAGAR','AHMEDNAAGAR')";
    }
    global $conn;
    $distEsc = $conn->real_escape_string(strtoupper($district));
    return " AND $col = '$distEsc'";
}

function serviceAbstractHasChallanTypeColumn() {
    global $conn;
    static $hasColumn = null;
    if ($hasColumn === null) {
        $check = $conn->query("SHOW COLUMNS FROM tbl_sell LIKE 'ChallanType'");
        $hasColumn = $check && $check->num_rows > 0;
    }

    return $hasColumn;
}

function serviceAbstractChallanBuildWhere($filters, $exclude = array()) {
    $parts = array("ts.Status = 1", "ts.SellType = 'Challan'", 'tu.ProjectType = 1');
    if (serviceAbstractHasChallanTypeColumn()) {
        $parts[] = 'ts.ChallanType = 2';
    }

    $subheadid = (int) ($filters['subheadid'] ?? 0);
    $projid = (int) ($filters['projid'] ?? 0);

    if (!in_array('subheadid', $exclude, true) && $subheadid > 0) {
        $parts[] = "tu.ProjectSubHeadId = '$subheadid'";
    } elseif (!in_array('projid', $exclude, true) && $projid > 0) {
        $parts[] = "tu.ProjectId = '$projid'";
    }

    $distClause = serviceAbstractChallanDistrictWhereClause($filters['district'] ?? '');
    if ($distClause !== '') {
        $parts[] = ltrim($distClause, ' AND ');
    }

    return ' INNER JOIN tbl_users tu ON tu.id = ts.CustId WHERE ' . implode(' AND ', $parts);
}

function serviceAbstractGetChallanCountMap($filters, $groupColumn, $exclude = array()) {
    global $conn;
    $where = serviceAbstractChallanBuildWhere($filters, $exclude);
    $sql = "SELECT $groupColumn AS group_id, COUNT(*) AS total_challan
        FROM tbl_sell ts
        $where
        AND $groupColumn > 0
        GROUP BY $groupColumn";

    $map = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $map[(int) $row['group_id']] = (int) $row['total_challan'];
        }
    }

    return $map;
}

function serviceAbstractGetChallanDistrictMap($filters) {
    global $conn;
    $where = serviceAbstractChallanBuildWhere($filters);
    $sql = "SELECT
        UPPER(TRIM(COALESCE(tu.District, ''))) AS raw_dist,
        COUNT(*) AS total_challan
        FROM tbl_sell ts
        $where
        AND COALESCE(tu.District, '') <> ''
        GROUP BY raw_dist";

    $map = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            if ($row['raw_dist'] === '') {
                continue;
            }
            $key = serviceAbstractDistrictKey($row['raw_dist']);
            if (!isset($map[$key])) {
                $map[$key] = 0;
            }
            $map[$key] += (int) $row['total_challan'];
        }
    }

    return $map;
}

function serviceAbstractGetTotalChallanCount($filters) {
    global $conn;
    $where = serviceAbstractChallanBuildWhere($filters);
    $row = getRecord("SELECT COUNT(*) AS total_challan FROM tbl_sell ts $where");

    return (int) ($row['total_challan'] ?? 0);
}

function serviceAbstractBuildWhere($filters, $exclude = array()) {
    $parts = array('tu.ProjectType = 1');
    $subheadid = (int) ($filters['subheadid'] ?? 0);
    $projid = (int) ($filters['projid'] ?? 0);

    if (!in_array('subheadid', $exclude, true) && $subheadid > 0) {
        $parts[] = "tu.ProjectSubHeadId = '$subheadid'";
    } elseif (!in_array('projid', $exclude, true) && $projid > 0) {
        $parts[] = "tu.ProjectId = '$projid'";
    }

    $distClause = serviceAbstractDistrictWhereClause($filters['district'] ?? '');
    if ($distClause !== '') {
        $parts[] = ltrim($distClause, ' AND ');
    }

    return ' INNER JOIN tbl_users tu ON tu.id = ts.CustId WHERE ' . implode(' AND ', $parts);
}

/** Complaint counts keyed by tu.ProjectId or tu.ProjectSubHeadId. */
function serviceAbstractGetCountMap($filters, $groupColumn, $exclude = array()) {
    global $conn;
    $where = serviceAbstractBuildWhere($filters, $exclude);
    $sql = "SELECT
        $groupColumn AS group_id,
        COUNT(*) AS total_complaints,
        " . serviceAbstractSqlTotalClosed('ts') . " AS total_closed,
        SUM(CASE WHEN ts.CreatedDate = CURDATE() THEN 1 ELSE 0 END) AS today_added,
        SUM(CASE WHEN ts.ClainStatus = 'In Process' THEN 1 ELSE 0 END) AS material_hold,
        " . serviceAbstractSqlTotalPending('ts') . " AS total_pending
        FROM tbl_service_complaint ts
        $where
        AND $groupColumn > 0
        GROUP BY $groupColumn";

    $map = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $map[(int) $row['group_id']] = array(
                'total_complaints' => (int) $row['total_complaints'],
                'total_closed' => (int) $row['total_closed'],
                'today_added' => (int) $row['today_added'],
                'material_hold' => (int) $row['material_hold'],
                'total_pending' => (int) $row['total_pending'],
            );
        }
    }
    return $map;
}

function serviceAbstractGetProjectHeads() {
    return getList("SELECT id, Name FROM tbl_common_master WHERE Status = 1 AND Roll = 24 ORDER BY Name ASC");
}

function serviceAbstractGetSubHeads($projid) {
    $projid = (int) $projid;
    if ($projid <= 0) {
        return getList("SELECT id, Name, UnderBy FROM tbl_project_sub_head WHERE Status = 1 ORDER BY Name ASC");
    }
    return getList("SELECT id, Name, UnderBy FROM tbl_project_sub_head WHERE Status = 1 AND UnderBy = '$projid' ORDER BY Name ASC");
}

function serviceAbstractGetDistrictOptions($filters) {
    global $conn;
    $filtersNoDist = $filters;
    $filtersNoDist['district'] = '';
    $whereNoDist = serviceAbstractBuildWhere($filtersNoDist);

    $sql = "SELECT DISTINCT UPPER(TRIM(COALESCE(NULLIF(ts.District, ''), tu.District, ''))) AS raw_dist
        FROM tbl_service_complaint ts
        $whereNoDist
        AND COALESCE(NULLIF(ts.District, ''), tu.District, '') <> ''
        ORDER BY raw_dist ASC";
    $keys = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $keys[serviceAbstractDistrictKey($row['raw_dist'])] = true;
        }
    }
    $options = array_keys($keys);
    sort($options);
    return $options;
}

function serviceAbstractColumnLabel($abstractType) {
    if ($abstractType === 'project_head') {
        return 'PROJECT HEAD';
    }
    if ($abstractType === 'sub_project_head') {
        return 'SUB PROJECT HEAD';
    }
    if ($abstractType === 'all') {
        return 'SUMMARY';
    }
    return 'DISTRICT';
}

function serviceAbstractBuildTitle($filters) {
    $type = $filters['abstract_type'] ?? 'district';
    $parts = array('SERVICE COMPLAINTS ABSTRACT');

    if ($type === 'district') {
        $parts[0] = 'DISTRICT WISE COMPLAINTS ABSTRACT';
    } elseif ($type === 'project_head') {
        $parts[0] = 'PROJECT HEAD WISE COMPLAINTS ABSTRACT';
    } elseif ($type === 'sub_project_head') {
        $parts[0] = 'SUB PROJECT HEAD WISE COMPLAINTS ABSTRACT';
    } else {
        $parts[0] = 'ALL COMPLAINTS ABSTRACT';
    }

    if (!empty($filters['subheadid'])) {
        $row = getRecord("SELECT Name FROM tbl_project_sub_head WHERE id = '" . (int) $filters['subheadid'] . "'");
        if (!empty($row['Name'])) {
            $parts[] = strtoupper($row['Name']);
        }
    } elseif (!empty($filters['projid'])) {
        $row = getRecord("SELECT Name FROM tbl_common_master WHERE id = '" . (int) $filters['projid'] . "'");
        if (!empty($row['Name'])) {
            $parts[] = strtoupper($row['Name']);
        }
    }
    if (!empty($filters['district'])) {
        $parts[] = strtoupper($filters['district']);
    }

    return implode(' — ', $parts);
}

function serviceAbstractEmptyCounts() {
    return array(
        'total_complaints' => 0,
        'total_challan' => 0,
        'total_closed' => 0,
        'today_added' => 0,
        'material_hold' => 0,
        'total_pending' => 0,
    );
}

function serviceAbstractMergeCounts(&$target, $source) {
    foreach ($source as $k => $v) {
        $target[$k] += (int) $v;
    }
}

function serviceAbstractBuildDistrictRows($filters) {
    $where = serviceAbstractBuildWhere($filters);
    global $conn;

    $sql = "SELECT
        UPPER(TRIM(COALESCE(NULLIF(ts.District, ''), tu.District, ''))) AS raw_dist,
        COUNT(*) AS total_complaints,
        " . serviceAbstractSqlTotalClosed('ts') . " AS total_closed,
        SUM(CASE WHEN ts.CreatedDate = CURDATE() THEN 1 ELSE 0 END) AS today_added,
        SUM(CASE WHEN ts.ClainStatus = 'In Process' THEN 1 ELSE 0 END) AS material_hold,
        " . serviceAbstractSqlTotalPending('ts') . " AS total_pending
        FROM tbl_service_complaint ts
        $where
        GROUP BY raw_dist";

    $byKey = array();
    $challanByKey = serviceAbstractGetChallanDistrictMap($filters);
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            if ($row['raw_dist'] === '') {
                continue;
            }
            $key = serviceAbstractDistrictKey($row['raw_dist']);
            if (!isset($byKey[$key])) {
                $byKey[$key] = serviceAbstractEmptyCounts();
            }
            serviceAbstractMergeCounts($byKey[$key], $row);
        }
    }

    $districtFilter = $filters['district'] ?? '';
    $showOnlyDistrict = ($districtFilter !== '' && $districtFilter !== 'all');
    $filterKey = $showOnlyDistrict ? serviceAbstractDistrictKey($districtFilter) : '';
    $districtList = $showOnlyDistrict ? array($filterKey) : serviceAbstractDistrictRows();

    $out = array();
    foreach ($districtList as $label) {
        $counts = isset($byKey[$label]) ? $byKey[$label] : serviceAbstractEmptyCounts();
        $counts['total_challan'] = isset($challanByKey[$label]) ? (int) $challanByKey[$label] : 0;
        $out[] = array(
            'label' => $label,
            'group_kind' => 'district',
            'group_id' => $label,
        ) + $counts;
    }

    if (!$showOnlyDistrict) {
        foreach ($byKey as $label => $counts) {
            if (in_array($label, serviceAbstractDistrictRows(), true)) {
                continue;
            }
            $counts['total_challan'] = isset($challanByKey[$label]) ? (int) $challanByKey[$label] : 0;
            $out[] = array(
                'label' => $label,
                'group_kind' => 'district',
                'group_id' => $label,
            ) + $counts;
        }
    }

    return $out;
}

function serviceAbstractBuildProjectHeadRows($filters) {
    $heads = serviceAbstractGetProjectHeads();
    $projid = (int) ($filters['projid'] ?? 0);
    if ($projid > 0) {
        $heads = array_values(array_filter($heads, function ($h) use ($projid) {
            return (int) $h['id'] === $projid;
        }));
    }

    $counts = serviceAbstractGetCountMap($filters, 'tu.ProjectId', array('projid'));
    $challanCounts = serviceAbstractGetChallanCountMap($filters, 'tu.ProjectId', array('projid'));

    $out = array();
    foreach ($heads as $head) {
        $id = (int) $head['id'];
        $c = isset($counts[$id]) ? $counts[$id] : serviceAbstractEmptyCounts();
        $c['total_challan'] = isset($challanCounts[$id]) ? (int) $challanCounts[$id] : 0;
        $out[] = array(
            'label' => $head['Name'],
            'group_kind' => 'project_head',
            'group_id' => $id,
        ) + $c;
    }
    return $out;
}

function serviceAbstractBuildSubProjectHeadRows($filters) {
    $projid = (int) ($filters['projid'] ?? 0);
    $subheadid = (int) ($filters['subheadid'] ?? 0);

    if ($subheadid > 0) {
        $row = getRecord("SELECT id, Name FROM tbl_project_sub_head WHERE id = '$subheadid' AND Status = 1");
        $subHeads = $row ? array($row) : array();
    } else {
        $subHeads = serviceAbstractGetSubHeads($projid);
    }

    $counts = serviceAbstractGetCountMap($filters, 'tu.ProjectSubHeadId', array('subheadid'));
    $challanCounts = serviceAbstractGetChallanCountMap($filters, 'tu.ProjectSubHeadId', array('subheadid'));

    $out = array();
    foreach ($subHeads as $head) {
        $id = (int) $head['id'];
        $c = isset($counts[$id]) ? $counts[$id] : serviceAbstractEmptyCounts();
        $c['total_challan'] = isset($challanCounts[$id]) ? (int) $challanCounts[$id] : 0;
        $out[] = array(
            'label' => $head['Name'],
            'group_kind' => 'sub_project_head',
            'group_id' => $id,
        ) + $c;
    }
    return $out;
}

function serviceAbstractBuildAllRows($filters) {
    global $conn;
    $where = serviceAbstractBuildWhere($filters);
    $sql = "SELECT
        COUNT(*) AS total_complaints,
        " . serviceAbstractSqlTotalClosed('ts') . " AS total_closed,
        SUM(CASE WHEN ts.CreatedDate = CURDATE() THEN 1 ELSE 0 END) AS today_added,
        SUM(CASE WHEN ts.ClainStatus = 'In Process' THEN 1 ELSE 0 END) AS material_hold,
        " . serviceAbstractSqlTotalPending('ts') . " AS total_pending
        FROM tbl_service_complaint ts
        $where";
    $row = getRecord($sql);
    if (!$row) {
        $row = serviceAbstractEmptyCounts();
    }
    return array(array(
        'label' => 'ALL',
        'group_kind' => 'all',
        'group_id' => 0,
        'total_complaints' => (int) $row['total_complaints'],
        'total_challan' => serviceAbstractGetTotalChallanCount($filters),
        'total_closed' => (int) $row['total_closed'],
        'today_added' => (int) $row['today_added'],
        'material_hold' => (int) $row['material_hold'],
        'total_pending' => (int) $row['total_pending'],
    ));
}

function getServiceAbstractData($filters = null) {
    if ($filters === null) {
        $filters = serviceAbstractFiltersFromRequest();
    }

    $abstractType = $filters['abstract_type'];

    if ($abstractType === 'all') {
        $rows = serviceAbstractBuildAllRows($filters);
    } elseif ($abstractType === 'project_head') {
        $rows = serviceAbstractBuildProjectHeadRows($filters);
    } elseif ($abstractType === 'sub_project_head') {
        $rows = serviceAbstractBuildSubProjectHeadRows($filters);
    } else {
        $rows = serviceAbstractBuildDistrictRows($filters);
    }

    $totals = array_merge(
        array('label' => 'TOTAL', 'group_kind' => 'total', 'group_id' => 0),
        serviceAbstractEmptyCounts()
    );

    foreach ($rows as $row) {
        $totals['total_complaints'] += (int) $row['total_complaints'];
        $totals['total_challan'] += (int) $row['total_challan'];
        $totals['total_closed'] += (int) $row['total_closed'];
        $totals['today_added'] += (int) $row['today_added'];
        $totals['material_hold'] += (int) $row['material_hold'];
        $totals['total_pending'] += (int) $row['total_pending'];
    }

    return array(
        'rows' => $rows,
        'totals' => $totals,
        'title' => serviceAbstractBuildTitle($filters),
        'column_label' => serviceAbstractColumnLabel($abstractType),
        'filters' => $filters,
    );
}

function serviceAbstractExportUrl($filters) {
    $params = array(
        'abstract_type' => $filters['abstract_type'] ?? 'district',
    );
    if (!empty($filters['projid'])) {
        $params['projid'] = (int) $filters['projid'];
    }
    if (!empty($filters['subheadid'])) {
        $params['subheadid'] = (int) $filters['subheadid'];
    }
    if (!empty($filters['district'])) {
        $params['district'] = $filters['district'];
    }
    return 'export-service-abstract.php?' . http_build_query($params);
}

function serviceAbstractListUrl($row, $filters, $filter = '') {
    $params = array(
        'abstract' => '1',
        'abstract_type' => $filters['abstract_type'] ?? 'district',
    );

    if (is_array($row) && ($row['label'] ?? '') !== 'TOTAL' && ($row['group_kind'] ?? '') !== 'total') {
        if ($row['group_kind'] === 'project_head' && !empty($row['group_id'])) {
            $params['projid'] = (int) $row['group_id'];
        } elseif ($row['group_kind'] === 'sub_project_head' && !empty($row['group_id'])) {
            $params['subheadid'] = (int) $row['group_id'];
        } elseif ($row['group_kind'] === 'district' && ($row['group_id'] ?? '') !== '') {
            $params['District'] = $row['group_id'];
        }
    }

    if (!empty($filters['projid']) && empty($params['projid'])) {
        $params['projid'] = (int) $filters['projid'];
    }
    if (!empty($filters['subheadid']) && empty($params['subheadid'])) {
        $params['subheadid'] = (int) $filters['subheadid'];
    }
    if (!empty($filters['district']) && empty($params['District'])) {
        $params['District'] = $filters['district'];
    }

    if ($filter === 'closed') {
        $params['Status'] = 'Resolved';
    } elseif ($filter === 'today') {
        $params['val'] = 'today';
    } elseif ($filter === 'material') {
        $params['ClainStatus'] = 'In Process';
    } elseif ($filter === 'pending') {
        $params['Status'] = 'Pending';
    }
    return 'view-maintenance.php?' . http_build_query($params);
}

function serviceAbstractChallanListUrl($row, $filters) {
    $params = array('Search' => '1');

    if (is_array($row) && ($row['label'] ?? '') !== 'TOTAL' && ($row['group_kind'] ?? '') !== 'total') {
        if ($row['group_kind'] === 'project_head' && !empty($row['group_id'])) {
            $params['ProjectId'] = (int) $row['group_id'];
        } elseif ($row['group_kind'] === 'sub_project_head' && !empty($row['group_id'])) {
            $params['ProjectSubHeadId'] = (int) $row['group_id'];
        }
    }

    if (!empty($filters['projid']) && empty($params['ProjectId'])) {
        $params['ProjectId'] = (int) $filters['projid'];
    }
    if (!empty($filters['subheadid']) && empty($params['ProjectSubHeadId'])) {
        $params['ProjectSubHeadId'] = (int) $filters['subheadid'];
    }

    return 'view-service-challans.php?' . http_build_query($params);
}
