<?php

require_once __DIR__ . '/inc-rooftop-insurance-site.php';

function insuranceDashboardCount($whereCondition)
{
    global $conn;

    $sql = "SELECT COUNT(DISTINCT tdo.CustId) AS cnt
        " . insuranceSiteInsuranceFromSqlCore() . "
        WHERE $whereCondition";
    $row = getRecord($sql);
    return (int) $row['cnt'];
}

function insuranceDashboardDistrictStats($whereCondition, $limit = 10)
{
    global $conn;

    $limit = (int) $limit;
    $sql = "SELECT tu.District, COUNT(DISTINCT tdo.CustId) AS cnt
        " . insuranceSiteInsuranceFromSqlCore() . "
        WHERE $whereCondition
          AND IFNULL(tu.District, '') != ''
        GROUP BY tu.District
        ORDER BY cnt DESC
        LIMIT $limit";

    return getList($sql);
}

function insuranceDashboardCompanyStats($limit = 8)
{
    global $conn;

    insuranceEnsureHistoryTable();
    $limit = (int) $limit;

    return getList("SELECT InsuranceCompany, COUNT(*) AS cnt
        FROM tbl_rooftop_insurance_site_history
        WHERE IFNULL(InsuranceCompany, '') != ''
        GROUP BY InsuranceCompany
        ORDER BY cnt DESC
        LIMIT $limit");
}

function insuranceDashboardMonthlyCompletedStats($months = 6)
{
    global $conn;

    insuranceEnsureHistoryTable();
    $months = (int) $months;

    return getList("SELECT DATE_FORMAT(CompletedDate, '%b %Y') AS label,
                           DATE_FORMAT(CompletedDate, '%Y-%m') AS sort_key,
                           COUNT(*) AS cnt
        FROM tbl_rooftop_insurance_site_history
        WHERE CompletedDate >= DATE_SUB(CURDATE(), INTERVAL $months MONTH)
        GROUP BY label, sort_key
        ORDER BY sort_key ASC");
}

function getInsuranceDashboardData()
{
    $today = date('Y-m-d');
    $monthStart = date('Y-m-01');
    $base = insuranceSiteBaseEligibleSqlCondition();

    $pending = insuranceDashboardCount(insuranceSitePendingSqlCondition());
    $activeCompleted = insuranceDashboardCount(insuranceSiteActiveCompletedSqlCondition());
    $renewal = insuranceDashboardCount(insuranceSiteRenewalSqlCondition());
    $expired = insuranceDashboardCount(insuranceSiteExpiredSqlCondition());
    $renewed = insuranceDashboardCount(insuranceSiteRenewedSqlCondition());
    $siteDispatched = insuranceDashboardCount($base);
    $totalCompleted = insuranceDashboardCount(insuranceSiteCompletedSqlCondition());

    insuranceEnsureHistoryTable();
    $importedToday = (int) getRow("SELECT id FROM tbl_rooftop_insurance_site_history WHERE CompletedDate = '$today'");
    $importedMonth = (int) getRow("SELECT id FROM tbl_rooftop_insurance_site_history WHERE CompletedDate >= '$monthStart'");

    return array(
        'pending' => $pending,
        'active_completed' => $activeCompleted,
        'renewal' => $renewal,
        'expired' => $expired,
        'renewed' => $renewed,
        'site_dispatched' => $siteDispatched,
        'total_completed' => $totalCompleted,
        'imported_today' => $importedToday,
        'imported_month' => $importedMonth,
        'district_stats' => insuranceDashboardDistrictStats(insuranceSiteCompletedSqlCondition(), 10),
        'company_stats' => insuranceDashboardCompanyStats(8),
        'monthly_stats' => insuranceDashboardMonthlyCompletedStats(6),
    );
}

function insuranceDashboardStatCard($label, $count, $href, $icon, $subtitle, $tone)
{
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $subtitle = htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $tone);
    ?>
    <a href="<?php echo $href; ?>" class="ins-dash-stat-link">
        <div class="ins-dash-stat-card ins-dash-tone-<?php echo $tone; ?>">
            <div class="ins-dash-stat-icon"><i class="feather icon-<?php echo $icon; ?>"></i></div>
            <div class="ins-dash-stat-body">
                <p class="ins-dash-stat-label"><?php echo $label; ?></p>
                <h3 class="ins-dash-stat-count"><?php echo number_format($count); ?></h3>
                <span class="ins-dash-stat-sub"><?php echo $subtitle; ?></span>
            </div>
        </div>
    </a>
    <?php
}
