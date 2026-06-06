<?php

function accountDashboardRegistry()
{
    return array(
        array('option' => '18', 'label' => 'Pump Customers', 'href' => 'pump-customers.php', 'icon' => 'users', 'tone' => 'customers', 'group' => 'customers', 'count_sql' => "Roll=5 AND ProjectType=1"),
        array('option' => '18', 'label' => 'Rooftop Customers', 'href' => 'rooftop-customers.php', 'icon' => 'sun', 'tone' => 'rooftop', 'group' => 'customers', 'count_sql' => "Roll=5 AND ProjectType=2"),
        array('option' => '19', 'label' => 'Manufacture', 'href' => 'view-manufacture.php', 'icon' => 'tool', 'tone' => 'manufacture', 'group' => 'business', 'count_sql' => 'Roll=3'),
        array('option' => '20', 'label' => 'Company', 'href' => 'view-company.php', 'icon' => 'briefcase', 'tone' => 'company', 'group' => 'business', 'count_sql' => 'Roll=10'),
        array('option' => '22', 'label' => 'Dealer', 'href' => 'view-dealer.php', 'icon' => 'shopping-bag', 'tone' => 'dealer', 'group' => 'partners', 'count_sql' => 'Roll=9'),
        array('option' => '23', 'label' => 'Agency', 'href' => 'view-agency.php', 'icon' => 'award', 'tone' => 'agency', 'group' => 'partners', 'count_sql' => 'Roll=11'),
        array('option' => '21', 'label' => 'Employee', 'href' => 'view-employee.php', 'icon' => 'user-check', 'tone' => 'employee', 'group' => 'staff', 'count_sql' => 'Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37,39,40,26,27,42)'),
        array('option' => '125', 'label' => 'Store Incharge', 'href' => 'view-store-incharge.php', 'icon' => 'home', 'tone' => 'store', 'group' => 'operations', 'count_sql' => 'Roll=27'),
        array('option' => '126', 'label' => 'Dispatch Officer', 'href' => 'view-dispatch-officer.php', 'icon' => 'truck', 'tone' => 'dispatch', 'group' => 'operations', 'count_sql' => 'Roll=26'),
        array('option' => '127', 'label' => 'Contractor', 'href' => 'view-installer.php', 'icon' => 'hard-drive', 'tone' => 'contractor', 'group' => 'operations', 'count_sql' => 'Roll=40'),
        array('option' => '128', 'label' => 'Installer', 'href' => 'view-installer-employee.php', 'icon' => 'settings', 'tone' => 'installer', 'group' => 'operations', 'count_sql' => 'Roll IN(34,35,36,37)'),
        array('option' => '116', 'label' => 'Driver', 'href' => 'view-drivers.php', 'icon' => 'navigation', 'tone' => 'driver', 'group' => 'operations', 'count_sql' => 'Roll=39'),
        array('option' => '129', 'label' => 'Maintenance Engineer', 'href' => 'view-maintaince-engineer.php', 'icon' => 'tool', 'tone' => 'maintenance', 'group' => 'operations', 'count_sql' => 'Roll=42'),
    );
}

function accountDashboardGroupLabels()
{
    return array(
        'customers' => 'Customer Accounts',
        'business' => 'Business Accounts',
        'partners' => 'Partner Accounts',
        'staff' => 'Staff Accounts',
        'operations' => 'Operations Accounts',
    );
}

function accountDashboardUserHasOption($options, $optionId)
{
    return in_array((string) $optionId, $options, true);
}

function accountDashboardCountSql($whereSql)
{
    $whereSql = trim($whereSql);
    if ($whereSql === '') {
        return 0;
    }

    return (int) getRow("SELECT id FROM tbl_users WHERE $whereSql");
}

function accountDashboardFilterByOptions($registry, $options)
{
    $filtered = array();
    $seen = array();

    foreach ($registry as $item) {
        if (!accountDashboardUserHasOption($options, $item['option'])) {
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

function getAccountDashboardData($options)
{
    $registry = accountDashboardFilterByOptions(accountDashboardRegistry(), $options);
    $items = array();
    $grouped = array();
    $totalRecords = 0;
    $groupLabels = accountDashboardGroupLabels();

    foreach ($registry as $item) {
        $count = accountDashboardCountSql($item['count_sql']);
        $item['count'] = $count;
        $items[] = $item;
        $totalRecords += $count;

        $group = $item['group'];
        if (!isset($grouped[$group])) {
            $grouped[$group] = array();
        }
        $grouped[$group][] = $item;
    }

    return array(
        'items' => $items,
        'grouped' => $grouped,
        'group_labels' => $groupLabels,
        'total_records' => $totalRecords,
        'account_types' => count($items),
        'total_users' => (int) getRow('SELECT id FROM tbl_users'),
        'active_users' => (int) getRow("SELECT id FROM tbl_users WHERE Status='1'"),
        'inactive_users' => (int) getRow("SELECT id FROM tbl_users WHERE Status!='1' OR Status IS NULL"),
    );
}

function accountDashboardStatCard($item)
{
    $label = htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8');
    $count = (int) $item['count'];
    $href = htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $item['tone']);
    ?>
    <a href="<?php echo $href; ?>" class="acct-dash-stat-link">
        <div class="acct-dash-stat-card acct-dash-tone-<?php echo $tone; ?>">
            <div class="acct-dash-stat-icon"><i class="feather icon-<?php echo $icon; ?>"></i></div>
            <div class="acct-dash-stat-body">
                <p class="acct-dash-stat-label"><?php echo $label; ?></p>
                <h3 class="acct-dash-stat-count"><?php echo number_format($count); ?></h3>
                <span class="acct-dash-stat-sub">View accounts</span>
            </div>
        </div>
    </a>
    <?php
}

function accountDashboardQuickLink($label, $href, $icon, $tone)
{
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $tone);
    ?>
    <a href="<?php echo $href; ?>" class="acct-dash-quick-link acct-dash-tone-<?php echo $tone; ?>">
        <span class="acct-dash-quick-icon"><i class="feather icon-<?php echo $icon; ?>"></i></span>
        <span class="acct-dash-quick-label"><?php echo $label; ?></span>
    </a>
    <?php
}
