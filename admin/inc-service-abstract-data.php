<?php
/**
 * Service complaints abstract — district-wise counts.
 * Status mapping (same ClainStatus values as complaint forms, Roll=6):
 *   Closed        => Close
 *   Material hold => In Process
 *   Total pending => not Close
 */

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
        'AHILYANAGAR',
        'AKOLA',
        'BEED',
        'BHANDARA',
        'BULDHANA',
        'CH. SAMBHAJINAGAR',
        'DHARASHIV',
        'DHULE',
        'GADCHIROLI',
        'GONDIA',
        'HINGOLI',
        'JALGAON',
        'JALNA',
        'KOLHAPUR',
        'LATUR',
        'NASHIK (MALEGAON)',
        'NANDED',
        'NANDURBAR',
        'PARBHANI',
        'SOLAPUR',
        'SANGLI',
        'WASHIM',
    );
}

function serviceAbstractFiltersFromRequest() {
    return array(
        'scope' => isset($_GET['scope']) ? $_GET['scope'] : 'mtskpy',
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

function serviceAbstractBuildWhere($filters) {
    $parts = array('tu.ProjectType = 1');
    $subheadid = (int) ($filters['subheadid'] ?? 0);
    $projid = (int) ($filters['projid'] ?? 0);
    $scope = $filters['scope'] ?? 'mtskpy';

    if ($subheadid > 0) {
        $parts[] = "tu.ProjectSubHeadId = '$subheadid'";
    } elseif ($projid > 0) {
        $parts[] = "tu.ProjectId = '$projid'";
    } elseif ($scope === 'msedcl') {
        $parts[] = 'tu.ProjectId = 103';
    } elseif ($scope === 'mtskpy') {
        $parts[] = "tu.ProjectSubHeadId IN (
            SELECT id FROM tbl_project_sub_head WHERE UnderBy = 103 AND Name LIKE '%MTSKPY%'
        )";
    }

    $distClause = serviceAbstractDistrictWhereClause($filters['district'] ?? '');
    if ($distClause !== '') {
        $parts[] = ltrim($distClause, ' AND ');
    }

    return ' INNER JOIN tbl_users tu ON tu.id = ts.CustId WHERE ' . implode(' AND ', $parts);
}

function serviceAbstractGetProjectHeads() {
    return getList("SELECT id, Name FROM tbl_common_master WHERE Status = 1 AND Roll = 24 ORDER BY Name ASC");
}

function serviceAbstractGetSubHeads($projid) {
    $projid = (int) $projid;
    if ($projid <= 0) {
        return array();
    }
    return getList("SELECT id, Name FROM tbl_project_sub_head WHERE Status = 1 AND UnderBy = '$projid' ORDER BY Name ASC");
}

function serviceAbstractGetDistrictOptions($filters) {
    global $conn;
    $where = serviceAbstractBuildWhere($filters);
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

function serviceAbstractBuildTitle($filters) {
    global $conn;
    $parts = array();

    if (!empty($filters['subheadid'])) {
        $row = getRecord("SELECT tps.Name AS SubName, tcm.Name AS HeadName
            FROM tbl_project_sub_head tps
            LEFT JOIN tbl_common_master tcm ON tcm.id = tps.UnderBy
            WHERE tps.id = '" . (int) $filters['subheadid'] . "'");
        if (!empty($row['SubName'])) {
            $parts[] = strtoupper($row['SubName']);
        }
    } elseif (!empty($filters['projid'])) {
        $row = getRecord("SELECT Name FROM tbl_common_master WHERE id = '" . (int) $filters['projid'] . "'");
        if (!empty($row['Name'])) {
            $parts[] = strtoupper($row['Name']);
        }
    } else {
        $scope = $filters['scope'] ?? 'mtskpy';
        if ($scope === 'all') {
            $parts[] = 'ALL SERVICE PROJECTS';
        } elseif ($scope === 'msedcl') {
            $parts[] = 'MSEDCL';
        } else {
            $parts[] = 'MTSKPY';
        }
    }

    $title = (!empty($parts) ? implode(' — ', $parts) : 'SERVICE') . ' COMPLAINTS ABSTRACT';
    if (!empty($filters['district']) && $filters['district'] !== 'all') {
        $title .= ' — ' . strtoupper($filters['district']);
    }
    return $title;
}

function getServiceAbstractData($filters = null) {
    global $conn;

    if ($filters === null) {
        $filters = serviceAbstractFiltersFromRequest();
    }

    $where = serviceAbstractBuildWhere($filters);
    $sql = "SELECT
        UPPER(TRIM(COALESCE(NULLIF(ts.District, ''), tu.District, ''))) AS raw_dist,
        COUNT(*) AS total_complaints,
        SUM(CASE WHEN ts.ClainStatus = 'Close' THEN 1 ELSE 0 END) AS total_closed,
        SUM(CASE WHEN ts.CreatedDate = CURDATE() THEN 1 ELSE 0 END) AS today_added,
        SUM(CASE WHEN ts.ClainStatus = 'In Process' THEN 1 ELSE 0 END) AS material_hold,
        SUM(CASE WHEN ts.ClainStatus <> 'Close' THEN 1 ELSE 0 END) AS total_pending
        FROM tbl_service_complaint ts
        $where
        GROUP BY raw_dist";

    $byKey = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            if ($row['raw_dist'] === '') {
                continue;
            }
            $key = serviceAbstractDistrictKey($row['raw_dist']);
            if (!isset($byKey[$key])) {
                $byKey[$key] = array(
                    'total_complaints' => 0,
                    'total_closed' => 0,
                    'today_added' => 0,
                    'material_hold' => 0,
                    'total_pending' => 0,
                );
            }
            foreach ($byKey[$key] as $k => $v) {
                $byKey[$key][$k] += (int) $row[$k];
            }
        }
    }

    $districtFilter = $filters['district'] ?? '';
    $showOnlyDistrict = ($districtFilter !== '' && $districtFilter !== 'all');
    $filterKey = $showOnlyDistrict ? serviceAbstractDistrictKey($districtFilter) : '';

    $rows = array();
    $totals = array(
        'district' => 'TOTAL',
        'total_complaints' => 0,
        'total_closed' => 0,
        'today_added' => 0,
        'material_hold' => 0,
        'total_pending' => 0,
    );

    $districtList = $showOnlyDistrict ? array($filterKey) : serviceAbstractDistrictRows();

    foreach ($districtList as $label) {
        $data = isset($byKey[$label]) ? $byKey[$label] : array(
            'total_complaints' => 0,
            'total_closed' => 0,
            'today_added' => 0,
            'material_hold' => 0,
            'total_pending' => 0,
        );
        $row = array_merge(array('district' => $label), $data);
        $rows[] = $row;
        foreach ($totals as $k => $v) {
            if ($k === 'district') {
                continue;
            }
            $totals[$k] += (int) $row[$k];
        }
    }

    if (!$showOnlyDistrict) {
        foreach ($byKey as $label => $data) {
            if (in_array($label, serviceAbstractDistrictRows(), true)) {
                continue;
            }
            $row = array_merge(array('district' => $label), $data);
            $rows[] = $row;
            foreach ($totals as $k => $v) {
                if ($k === 'district') {
                    continue;
                }
                $totals[$k] += (int) $row[$k];
            }
        }
    }

    return array(
        'rows' => $rows,
        'totals' => $totals,
        'title' => serviceAbstractBuildTitle($filters),
        'filters' => $filters,
    );
}

function serviceAbstractExportUrl($filters) {
    $params = array(
        'scope' => $filters['scope'] ?? 'mtskpy',
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

function serviceAbstractListUrl($district, $filters, $filter = '') {
    $params = array(
        'abstract' => '1',
        'scope' => $filters['scope'] ?? 'mtskpy',
    );
    if (!empty($filters['projid'])) {
        $params['projid'] = (int) $filters['projid'];
    }
    if (!empty($filters['subheadid'])) {
        $params['subheadid'] = (int) $filters['subheadid'];
    }
    if ($district !== '' && $district !== 'TOTAL') {
        $params['District'] = $district;
    } elseif (!empty($filters['district']) && $filters['district'] !== 'all') {
        $params['District'] = $filters['district'];
    }
    if ($filter === 'closed') {
        $params['ClainStatus'] = 'Close';
    } elseif ($filter === 'today') {
        $params['val'] = 'today';
    } elseif ($filter === 'material') {
        $params['ClainStatus'] = 'In Process';
    } elseif ($filter === 'pending') {
        $params['Status'] = 'Pending';
    }
    return 'view-maintenance.php?' . http_build_query($params);
}
