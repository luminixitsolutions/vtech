<?php

require_once __DIR__ . '/inc-msedcl-smart-site.php';

function getMsedclSmartDashboardData()
{
    msedclSmartEnsureTables();
    msedclSmartSyncAllSurveyDoneStatuses(isset($_SESSION['Admin']['id']) ? (int) $_SESSION['Admin']['id'] : 0);

    $counts = msedclSmartDashboardCounts();
    $total = $counts['total'];
    $pmsgy = $counts['pmsgy'];
    $mahadiscom = $counts['mahadiscom'];
    $paymentDone = $counts['payment_done'];
    $surveyPending = $counts['survey_pending'];
    $surveyDone = $counts['survey_done'];
    $stagePmsgy = $counts['pmsgy_awaiting'];
    $stageMahadiscom = $counts['mahadiscom_awaiting'];

    $today = date('Y-m-d');
    $importedToday = msedclSmartCount("Status=1 AND DATE(CreatedDateTime)='$today'");

    $districtStats = getList("SELECT IFNULL(NULLIF(TRIM(District), ''), 'Unknown') AS District, COUNT(*) AS cnt
        FROM tbl_rooftop_msedcl_smart_customers WHERE Status=1
        GROUP BY IFNULL(NULLIF(TRIM(District), ''), 'Unknown')
        ORDER BY cnt DESC LIMIT 10");

    $abstractRows = msedclSmartAbstractByDistrict();
    usort($abstractRows, function ($a, $b) {
        return ((int) ($b['pmsgy_cnt'] ?? 0)) <=> ((int) ($a['pmsgy_cnt'] ?? 0));
    });
    $abstractRows = array_slice($abstractRows, 0, 10);

    $monthlyStats = getList("SELECT DATE_FORMAT(CreatedDateTime, '%b %Y') AS label,
        DATE_FORMAT(CreatedDateTime, '%Y-%m') AS sort_key,
        COUNT(*) AS cnt
        FROM tbl_rooftop_msedcl_smart_history
        WHERE ActionType='pmsgy_import'
          AND CreatedDateTime >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY label, sort_key
        ORDER BY sort_key ASC");

    $capacityStats = getList("SELECT IFNULL(NULLIF(TRIM(cm.Name), ''), CONCAT('ID ', c.PumpCapacity)) AS label,
        COUNT(*) AS cnt
        FROM tbl_rooftop_msedcl_smart_customers c
        LEFT JOIN tbl_common_master cm ON cm.id = c.PumpCapacity AND cm.Roll='2'
        WHERE c.Status=1 AND IFNULL(c.PumpCapacity, '') != '' AND IFNULL(c.PumpCapacity, '0') != '0'
        GROUP BY c.PumpCapacity, cm.Name
        ORDER BY cnt DESC
        LIMIT 8");

    $pct = function ($num, $den) {
        $den = (int) $den;
        if ($den < 1) {
            return 0;
        }
        return round(((int) $num / $den) * 100, 1);
    };

    return [
        'total' => $total,
        'pmsgy' => $pmsgy,
        'mahadiscom' => $mahadiscom,
        'payment_done' => $paymentDone,
        'survey_pending' => $surveyPending,
        'survey_done' => $surveyDone,
        'imported_today' => $importedToday,
        'district_stats' => is_array($districtStats) ? $districtStats : [],
        'abstract_rows' => is_array($abstractRows) ? $abstractRows : [],
        'monthly_stats' => is_array($monthlyStats) ? $monthlyStats : [],
        'capacity_stats' => is_array($capacityStats) ? $capacityStats : [],
        'stage_pmsgy' => $stagePmsgy,
        'stage_mahadiscom' => $stageMahadiscom,
        'funnel' => [
            ['label' => 'PMSGY Portal', 'count' => $pmsgy, 'pct' => $pct($pmsgy, $total), 'color' => '#2563eb'],
            ['label' => 'Mahadiscom Portal', 'count' => $mahadiscom, 'pct' => $pct($mahadiscom, $total), 'color' => '#7c3aed'],
            ['label' => 'Payment Done', 'count' => $paymentDone, 'pct' => $pct($paymentDone, $total), 'color' => '#059669'],
            ['label' => 'Survey Pending', 'count' => $surveyPending, 'pct' => $pct($surveyPending, $total), 'color' => '#d97706'],
            ['label' => 'Survey Done', 'count' => $surveyDone, 'pct' => $pct($surveyDone, $total), 'color' => '#64748b'],
        ],
    ];
}

function msedclSmartDashboardStatCard($title, $count, $url, $icon, $subtitle, $variant)
{
    ?>
<a href="<?php echo htmlspecialchars($url); ?>" class="msedcl-dash-stat msedcl-dash-stat-<?php echo htmlspecialchars($variant); ?>">
    <div class="msedcl-dash-stat-icon"><i class="feather icon-<?php echo htmlspecialchars($icon); ?>"></i></div>
    <div class="msedcl-dash-stat-body">
        <div class="msedcl-dash-stat-value"><?php echo number_format((int) $count); ?></div>
        <div class="msedcl-dash-stat-title"><?php echo htmlspecialchars($title); ?></div>
        <div class="msedcl-dash-stat-sub"><?php echo htmlspecialchars($subtitle); ?></div>
    </div>
</a>
    <?php
}

function msedclSmartDashboardRenderFunnel(array $funnel)
{
    if (empty($funnel)) {
        return;
    }
    ?>
<div class="msedcl-funnel">
    <?php foreach ($funnel as $step) {
        $width = max(18, min(100, (float) ($step['pct'] ?? 0)));
        ?>
    <div class="msedcl-funnel-step">
        <div class="msedcl-funnel-bar-wrap">
            <div class="msedcl-funnel-bar" style="width:<?php echo $width; ?>%;background:<?php echo htmlspecialchars($step['color']); ?>;">
                <span class="msedcl-funnel-bar-label"><?php echo number_format((int) $step['count']); ?></span>
            </div>
        </div>
        <div class="msedcl-funnel-meta">
            <strong><?php echo htmlspecialchars($step['label']); ?></strong>
            <span><?php echo htmlspecialchars($step['pct']); ?>% of total</span>
        </div>
    </div>
    <?php } ?>
</div>
    <?php
}
