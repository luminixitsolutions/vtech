<?php

function mastersDashboardRegistry()
{
    return array(
        array('option' => '1', 'label' => 'Country', 'href' => 'country.php', 'icon' => 'globe', 'tone' => 'location', 'group' => 'location', 'type' => 'table', 'table' => 'tbl_country'),
        array('option' => '1', 'label' => 'State', 'href' => 'state.php', 'icon' => 'map', 'tone' => 'location', 'group' => 'location', 'type' => 'table', 'table' => 'tbl_state'),
        array('option' => '1', 'label' => 'City', 'href' => 'city.php', 'icon' => 'map-pin', 'tone' => 'location', 'group' => 'location', 'type' => 'table', 'table' => 'tbl_city'),
        array('option' => '56', 'label' => 'PI/Quotation Products', 'href' => 'view-quotation-products.php', 'icon' => 'package', 'tone' => 'products', 'group' => 'products', 'type' => 'table', 'table' => 'tbl_qtn_products'),
        array('option' => '2', 'label' => 'Store', 'href' => 'branches.php', 'icon' => 'home', 'tone' => 'store', 'group' => 'core', 'type' => 'table', 'table' => 'tbl_branch'),
        array('option' => '3', 'label' => 'Issues', 'href' => 'issues.php', 'icon' => 'alert-circle', 'tone' => 'issues', 'group' => 'core', 'type' => 'table', 'table' => 'tbl_issues'),
        array('option' => '4', 'label' => 'Scheme / Yojna', 'href' => 'scheme.php', 'icon' => 'flag', 'tone' => 'scheme', 'group' => 'core', 'type' => 'table', 'table' => 'tbl_scheme'),
        array('option' => '5', 'label' => 'User Type', 'href' => 'user-type.php', 'icon' => 'users', 'tone' => 'usertype', 'group' => 'core', 'type' => 'table', 'table' => 'tbl_user_type'),
        array('option' => '140', 'label' => 'Project Head', 'href' => 'common-master.php?pageid=24', 'icon' => 'layers', 'tone' => 'project', 'group' => 'core', 'type' => 'roll', 'roll' => 24),
        array('option' => '141', 'label' => 'Project Sub Head', 'href' => 'project-sub-head.php', 'icon' => 'git-branch', 'tone' => 'project', 'group' => 'core', 'type' => 'table', 'table' => 'tbl_project_sub_head'),
        array('option' => '6', 'label' => 'Pump Head', 'href' => 'common-master.php?pageid=1', 'icon' => 'droplet', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 1),
        array('option' => '7', 'label' => 'Pump Capacity', 'href' => 'common-master.php?pageid=2', 'icon' => 'activity', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 2),
        array('option' => '72', 'label' => 'Pump Outlet Size', 'href' => 'common-master.php?pageid=12', 'icon' => 'maximize-2', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 12),
        array('option' => '73', 'label' => 'Standard Depth', 'href' => 'common-master.php?pageid=13', 'icon' => 'arrow-down', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 13),
        array('option' => '74', 'label' => 'Pump Head Range', 'href' => 'common-master.php?pageid=14', 'icon' => 'sliders', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 14),
        array('option' => '8', 'label' => 'Water Source', 'href' => 'common-master.php?pageid=3', 'icon' => 'cloud-rain', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 3),
        array('option' => '9', 'label' => 'Type Of Pump', 'href' => 'common-master.php?pageid=4', 'icon' => 'cpu', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 4),
        array('option' => '12', 'label' => 'Bore Dia', 'href' => 'common-master.php?pageid=7', 'icon' => 'disc', 'tone' => 'pump', 'group' => 'pump', 'type' => 'roll', 'roll' => 7),
        array('option' => '13', 'label' => 'Customer Type', 'href' => 'common-master.php?pageid=8', 'icon' => 'user', 'tone' => 'core', 'group' => 'core', 'type' => 'roll', 'roll' => 8),
        array('option' => '75', 'label' => 'Module Watt', 'href' => 'common-master.php?pageid=15', 'icon' => 'zap', 'tone' => 'rooftop', 'group' => 'rooftop', 'type' => 'roll', 'roll' => 15),
        array('option' => '76', 'label' => 'Module Qty', 'href' => 'common-master.php?pageid=16', 'icon' => 'grid', 'tone' => 'rooftop', 'group' => 'rooftop', 'type' => 'roll', 'roll' => 16),
        array('option' => '77', 'label' => 'Structure', 'href' => 'common-master.php?pageid=17', 'icon' => 'box', 'tone' => 'rooftop', 'group' => 'rooftop', 'type' => 'roll', 'roll' => 17),
        array('option' => '97', 'label' => 'Module Make', 'href' => 'common-master.php?pageid=22', 'icon' => 'sun', 'tone' => 'rooftop', 'group' => 'rooftop', 'type' => 'roll', 'roll' => 22),
        array('option' => '98', 'label' => 'Structure Make', 'href' => 'common-master.php?pageid=23', 'icon' => 'tool', 'tone' => 'rooftop', 'group' => 'rooftop', 'type' => 'roll', 'roll' => 23),
        array('option' => '34', 'label' => 'Insurance Agency', 'href' => 'common-master.php?pageid=9', 'icon' => 'shield', 'tone' => 'insurance', 'group' => 'insurance', 'type' => 'roll', 'roll' => 9),
        array('option' => '15', 'label' => 'Insurance Claim Reason', 'href' => 'common-master.php?pageid=5', 'icon' => 'file-text', 'tone' => 'insurance', 'group' => 'insurance', 'type' => 'roll', 'roll' => 5),
        array('option' => '16', 'label' => 'Insurance Claim Status', 'href' => 'common-master.php?pageid=6', 'icon' => 'check-square', 'tone' => 'insurance', 'group' => 'insurance', 'type' => 'roll', 'roll' => 6),
        array('option' => '53', 'label' => 'Lead Source', 'href' => 'common-master.php?pageid=10', 'icon' => 'share-2', 'tone' => 'leads', 'group' => 'leads', 'type' => 'roll', 'roll' => 10),
        array('option' => '54', 'label' => 'Lead Status', 'href' => 'common-master.php?pageid=11', 'icon' => 'trending-up', 'tone' => 'leads', 'group' => 'leads', 'type' => 'roll', 'roll' => 11),
        array('option' => '89', 'label' => 'Dispatched Calling Ques', 'href' => 'common-master.php?pageid=18', 'icon' => 'phone', 'tone' => 'questions', 'group' => 'questions', 'type' => 'roll', 'roll' => 18),
        array('option' => '90', 'label' => 'Before Installation Ques', 'href' => 'common-master.php?pageid=19', 'icon' => 'help-circle', 'tone' => 'questions', 'group' => 'questions', 'type' => 'roll', 'roll' => 19),
        array('option' => '91', 'label' => 'After Installation Ques', 'href' => 'common-master.php?pageid=20', 'icon' => 'message-circle', 'tone' => 'questions', 'group' => 'questions', 'type' => 'roll', 'roll' => 20),
        array('option' => '92', 'label' => 'Before Inspection Ques', 'href' => 'common-master.php?pageid=21', 'icon' => 'search', 'tone' => 'questions', 'group' => 'questions', 'type' => 'roll', 'roll' => 21),
        array('option' => '117', 'label' => 'Beneficiary Selection Ques', 'href' => 'common-master.php?pageid=25', 'icon' => 'list', 'tone' => 'questions', 'group' => 'questions', 'type' => 'roll', 'roll' => 25),
    );
}

function mastersDashboardGroupLabels()
{
    return array(
        'core' => 'Core Masters',
        'location' => 'Locations',
        'pump' => 'Pump & Water',
        'rooftop' => 'Rooftop & Module',
        'insurance' => 'Insurance',
        'leads' => 'Leads',
        'questions' => 'Questionnaires',
        'products' => 'Products',
    );
}

function mastersDashboardGroupColors()
{
    return array(
        'core' => '#6366f1',
        'location' => '#0891b2',
        'pump' => '#2563eb',
        'rooftop' => '#f59e0b',
        'insurance' => '#10b981',
        'leads' => '#8b5cf6',
        'questions' => '#ec4899',
        'products' => '#14b8a6',
    );
}

function mastersDashboardCountItem($item)
{
    if ($item['type'] === 'table') {
        $table = preg_replace('/[^a-z0-9_]/', '', $item['table']);
        return (int) getRow("SELECT id FROM $table");
    }

    if ($item['type'] === 'roll') {
        $roll = (int) $item['roll'];
        return (int) getRow("SELECT id FROM tbl_common_master WHERE Roll='$roll'");
    }

    return 0;
}

function mastersDashboardFilterByOptions($registry, $options)
{
    $filtered = array();
    $seen = array();

    foreach ($registry as $item) {
        if (!in_array($item['option'], $options, true)) {
            continue;
        }

        $key = $item['label'] . '|' . $item['href'];
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;
        $filtered[] = $item;
    }

    return $filtered;
}

function mastersDashboardCommonMasterStatusStats()
{
    $row = getRecord("SELECT
            SUM(CASE WHEN Status = '1' THEN 1 ELSE 0 END) AS active_cnt,
            SUM(CASE WHEN Status != '1' OR Status IS NULL THEN 1 ELSE 0 END) AS inactive_cnt
        FROM tbl_common_master");

    return array(
        'active' => (int) ($row['active_cnt'] ?? 0),
        'inactive' => (int) ($row['inactive_cnt'] ?? 0),
    );
}

function getMastersDashboardData($options)
{
    $registry = mastersDashboardFilterByOptions(mastersDashboardRegistry(), $options);
    $items = array();
    $totalRecords = 0;
    $groupTotals = array();
    $groupLabels = mastersDashboardGroupLabels();

    foreach ($registry as $item) {
        $count = mastersDashboardCountItem($item);
        $item['count'] = $count;
        $items[] = $item;
        $totalRecords += $count;

        $group = $item['group'];
        if (!isset($groupTotals[$group])) {
            $groupTotals[$group] = 0;
        }
        $groupTotals[$group] += $count;
    }

    usort($items, function ($a, $b) {
        return $b['count'] - $a['count'];
    });

    $groupStats = array();
    foreach ($groupTotals as $group => $count) {
        if ($count <= 0) {
            continue;
        }
        $groupStats[] = array(
            'group' => $group,
            'label' => isset($groupLabels[$group]) ? $groupLabels[$group] : ucfirst($group),
            'count' => $count,
        );
    }

    usort($groupStats, function ($a, $b) {
        return $b['count'] - $a['count'];
    });

    $locationStats = array();
    foreach ($items as $item) {
        if ($item['group'] === 'location') {
            $locationStats[] = array(
                'label' => $item['label'],
                'count' => $item['count'],
            );
        }
    }

    $topItems = array_slice($items, 0, 10);

    return array(
        'items' => $items,
        'top_items' => $topItems,
        'total_records' => $totalRecords,
        'master_types' => count($items),
        'group_stats' => $groupStats,
        'location_stats' => $locationStats,
        'common_status' => mastersDashboardCommonMasterStatusStats(),
    );
}

function mastersDashboardStatCard($item)
{
    $label = htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8');
    $count = (int) $item['count'];
    $href = htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $item['tone']);
    ?>
    <a href="<?php echo $href; ?>" class="masters-dash-stat-link">
        <div class="masters-dash-stat-card masters-dash-tone-<?php echo $tone; ?>">
            <div class="masters-dash-stat-icon"><i class="feather icon-<?php echo $icon; ?>"></i></div>
            <div class="masters-dash-stat-body">
                <p class="masters-dash-stat-label"><?php echo $label; ?></p>
                <h3 class="masters-dash-stat-count"><?php echo number_format($count); ?></h3>
                <span class="masters-dash-stat-sub">Manage records</span>
            </div>
        </div>
    </a>
    <?php
}

function mastersDashboardChartColor($index)
{
    $palette = array('#6366f1', '#0891b2', '#2563eb', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899', '#14b8a6', '#ef4444', '#64748b');
    return $palette[$index % count($palette)];
}
