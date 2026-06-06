<?php

function leadDashboardTableName($table = 'tbl_leads')
{
    return $table === 'tbl_dealer_leads' ? 'tbl_dealer_leads' : 'tbl_leads';
}

function leadDashboardCount($whereCondition = '1', $table = 'tbl_leads')
{
    $whereCondition = trim($whereCondition);
    if ($whereCondition === '') {
        $whereCondition = '1';
    }
    $table = leadDashboardTableName($table);

    return (int) getRow("SELECT id FROM $table WHERE $whereCondition");
}

function leadDashboardSourceStats($table = 'tbl_leads')
{
    $table = leadDashboardTableName($table);

    return getList("SELECT cm.Name, COUNT(l.id) AS cnt
        FROM tbl_common_master cm
        LEFT JOIN $table l ON l.ClainReason = cm.Name
        WHERE cm.Status = 1 AND cm.Roll = 10
        GROUP BY cm.Name
        ORDER BY cnt DESC, cm.Name ASC");
}

function leadDashboardStatusStats($table = 'tbl_leads')
{
    $table = leadDashboardTableName($table);

    return getList("SELECT cm.Name, COUNT(l.id) AS cnt
        FROM tbl_common_master cm
        LEFT JOIN $table l ON l.ClainStatus = cm.Name
        WHERE cm.Status = 1 AND cm.Roll = 11
        GROUP BY cm.Name
        ORDER BY cnt DESC, cm.Name ASC");
}

function leadDashboardMonthlyStats($months = 6, $table = 'tbl_leads')
{
    $months = (int) $months;
    $table = leadDashboardTableName($table);

    return getList("SELECT DATE_FORMAT(CreatedDate, '%b %Y') AS label,
                           DATE_FORMAT(CreatedDate, '%Y-%m') AS sort_key,
                           COUNT(*) AS cnt
        FROM $table
        WHERE CreatedDate >= DATE_SUB(CURDATE(), INTERVAL $months MONTH)
        GROUP BY label, sort_key
        ORDER BY sort_key ASC");
}

function leadDashboardBranchStats($limit = 8, $table = 'tbl_leads')
{
    $limit = (int) $limit;
    $table = leadDashboardTableName($table);

    return getList("SELECT IFNULL(tb.Name, 'Unassigned') AS BranchName, COUNT(*) AS cnt
        FROM $table tp
        LEFT JOIN tbl_branch tb ON tp.BranchId = tb.id
        GROUP BY BranchName
        ORDER BY cnt DESC
        LIMIT $limit");
}

function leadDashboardStatusTone($statusName)
{
    $name = strtolower(trim((string) $statusName));

    if (strpos($name, 'complete') !== false) {
        return 'completed';
    }
    if (strpos($name, 'progress') !== false) {
        return 'progress';
    }
    if (strpos($name, 'pending') !== false || strpos($name, 'new') !== false) {
        return 'pending';
    }
    if (strpos($name, 'follow') !== false) {
        return 'followup';
    }
    if (strpos($name, 'cancel') !== false || strpos($name, 'reject') !== false || strpos($name, 'lost') !== false) {
        return 'cancelled';
    }
    if (strpos($name, 'convert') !== false || strpos($name, 'won') !== false) {
        return 'converted';
    }

    return 'default';
}

function leadDashboardStatusIcon($statusName)
{
    $tone = leadDashboardStatusTone($statusName);

    $icons = array(
        'completed' => 'check-circle',
        'progress' => 'loader',
        'pending' => 'clock',
        'followup' => 'phone-call',
        'cancelled' => 'x-circle',
        'converted' => 'trending-up',
        'default' => 'activity',
    );

    return isset($icons[$tone]) ? $icons[$tone] : $icons['default'];
}

function leadDashboardSourceChartColor($sourceName, $index = 0)
{
    if (function_exists('leadSourceGetConfig')) {
        $cfg = leadSourceGetConfig($sourceName);
        $palette = array(
            'instagram' => '#e1306c',
            'facebook' => '#1877f2',
            'whatsapp' => '#25d366',
            'twitter' => '#1da1f2',
            'linkedin' => '#0a66c2',
            'youtube' => '#ff0000',
            'google' => '#4285f4',
            'website' => '#6366f1',
            'dealer' => '#f59e0b',
            'direct' => '#10b981',
            'referral' => '#8b5cf6',
            'walkin' => '#14b8a6',
            'phone' => '#3b82f6',
            'email' => '#ec4899',
            'exhibition' => '#f97316',
            'sms' => '#06b6d4',
            'total' => '#334155',
            'default' => '#64748b',
        );

        $slug = isset($cfg['slug']) ? $cfg['slug'] : 'default';
        if (isset($palette[$slug])) {
            return $palette[$slug];
        }
    }

    $fallback = array('#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#14b8a6', '#3b82f6', '#f59e0b', '#10b981');
    return $fallback[$index % count($fallback)];
}

function leadDashboardStatusChartColor($statusName)
{
    $tone = leadDashboardStatusTone($statusName);
    $colors = array(
        'completed' => '#10b981',
        'progress' => '#3b82f6',
        'pending' => '#f59e0b',
        'followup' => '#06b6d4',
        'cancelled' => '#ef4444',
        'converted' => '#8b5cf6',
        'default' => '#64748b',
    );

    return isset($colors[$tone]) ? $colors[$tone] : $colors['default'];
}

function leadDashboardSocialStats()
{
    $social = getRecord("SELECT Videos, Blogs, Influencers, Creative FROM tbl_social_media_marketing WHERE id = 1");
    if (!is_array($social)) {
        return array(
            'Videos' => 0,
            'Blogs' => 0,
            'Influencers' => 0,
            'Creative' => 0,
        );
    }

    return $social;
}

function getLeadDashboardData($table = 'tbl_leads')
{
    $today = date('Y-m-d');
    $monthStart = date('Y-m-01');

    $total = leadDashboardCount('1', $table);
    $addedToday = leadDashboardCount("CreatedDate = '$today'", $table);
    $addedMonth = leadDashboardCount("CreatedDate >= '$monthStart'", $table);
    $assigned = leadDashboardCount("IFNULL(AllocateId, 0) > 0", $table);
    $unassigned = leadDashboardCount("IFNULL(AllocateId, 0) = 0 OR AllocateId IS NULL", $table);
    $converted = leadDashboardCount("OppConverted = '1'", $table);

    return array(
        'total' => $total,
        'added_today' => $addedToday,
        'added_month' => $addedMonth,
        'assigned' => $assigned,
        'unassigned' => $unassigned,
        'converted' => $converted,
        'source_stats' => leadDashboardSourceStats($table),
        'status_stats' => leadDashboardStatusStats($table),
        'monthly_stats' => leadDashboardMonthlyStats(6, $table),
        'branch_stats' => leadDashboardBranchStats(8, $table),
        'social' => leadDashboardSocialStats(),
    );
}

function getDealerLeadDashboardData()
{
    return getLeadDashboardData('tbl_dealer_leads');
}

function leadDashboardStatCard($label, $count, $href, $icon, $subtitle, $tone)
{
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $subtitle = htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $tone);
    ?>
    <a href="<?php echo $href; ?>" class="lead-dash-stat-link">
        <div class="lead-dash-stat-card lead-dash-tone-<?php echo $tone; ?>">
            <div class="lead-dash-stat-icon"><i class="feather icon-<?php echo $icon; ?>"></i></div>
            <div class="lead-dash-stat-body">
                <p class="lead-dash-stat-label"><?php echo $label; ?></p>
                <h3 class="lead-dash-stat-count"><?php echo number_format($count); ?></h3>
                <span class="lead-dash-stat-sub"><?php echo $subtitle; ?></span>
            </div>
        </div>
    </a>
    <?php
}

function leadDashboardSourceStatCard($sourceName, $count)
{
    $name = htmlspecialchars(trim((string) $sourceName), ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $href = 'view-leads.php?ClainReason=' . urlencode($sourceName);
    ?>
    <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>" class="lead-dash-source-link">
        <div class="lead-dash-source-card">
            <?php echo leadSourceDashboardCardIcon($sourceName); ?>
            <div class="lead-dash-source-meta">
                <p class="lead-dash-source-label"><?php echo $name; ?></p>
                <h4 class="lead-dash-source-count"><?php echo number_format($count); ?></h4>
            </div>
        </div>
    </a>
    <?php
}

function leadDashboardStatusStatCard($statusName, $count)
{
    $name = htmlspecialchars(trim((string) $statusName), ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $tone = leadDashboardStatusTone($statusName);
    $icon = leadDashboardStatusIcon($statusName);
    $href = 'view-leads.php?ClainStatus=' . urlencode($statusName);
    ?>
    <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>" class="lead-dash-stat-link">
        <div class="lead-dash-stat-card lead-dash-tone-<?php echo htmlspecialchars($tone, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="lead-dash-stat-icon"><i class="feather icon-<?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
            <div class="lead-dash-stat-body">
                <p class="lead-dash-stat-label"><?php echo $name; ?></p>
                <h3 class="lead-dash-stat-count"><?php echo number_format($count); ?></h3>
                <span class="lead-dash-stat-sub">Click to view leads</span>
            </div>
        </div>
    </a>
    <?php
}
