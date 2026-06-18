<?php

function installationDashboardRegistry()
{
    return array(
        array('option' => '175', 'label' => 'Assign Coordinator', 'href' => 'pending-installations.php', 'icon' => 'user-plus', 'tone' => 'assign', 'metric' => 'Awaiting coordinator assignment'),
        array('option' => '176', 'label' => 'Coordinator Action', 'href' => 'coordinator-assigned-sites.php', 'icon' => 'users', 'tone' => 'coordinator', 'metric' => 'Sites at coordinator stage'),
        array('option' => '177', 'label' => 'Manager Action', 'href' => 'manager-pending-installations.php', 'icon' => 'briefcase', 'tone' => 'manager', 'metric' => 'Sites at manager stage'),
        array('option' => '178', 'label' => 'General Manager Action', 'href' => 'gm-pending-installations.php', 'icon' => 'shield', 'tone' => 'gm', 'metric' => 'Sites at GM stage'),
        array('option' => '179', 'label' => 'GM Extension Requests', 'href' => 'gm-extension-requests.php', 'icon' => 'clock', 'tone' => 'gmext', 'metric' => 'Pending GM approvals'),
        array('option' => '180', 'label' => 'Business Head Action', 'href' => 'business-head-pending.php', 'icon' => 'flag', 'tone' => 'bh', 'metric' => 'Sites at business head stage'),
        array('option' => '181', 'label' => 'BH Extension Requests', 'href' => 'bh-extension-requests.php', 'icon' => 'watch', 'tone' => 'bhext', 'metric' => 'Pending BH approvals'),
        array('option' => '182', 'label' => 'Dispute Sites', 'href' => 'dispute-sites.php', 'icon' => 'alert-triangle', 'tone' => 'dispute', 'metric' => 'Disputed installations'),
    );
}

function installationDashboardUserHasOption($options, $optionId)
{
    return in_array((string) $optionId, $options, true);
}

function installationDashboardFlowCount($whereSql)
{
    $whereSql = trim($whereSql);
    if ($whereSql === '') {
        return 0;
    }

    return (int) getRow("SELECT id FROM tbl_installation_flow WHERE $whereSql");
}

function installationDashboardExtensionCount($whereSql)
{
    $whereSql = trim($whereSql);
    if ($whereSql === '') {
        return 0;
    }

    return (int) getRow("SELECT id FROM tbl_installation_extensions WHERE $whereSql");
}

function installationDashboardAssignPendingCount()
{
    return (int) getRow("SELECT ts.id FROM tbl_sell ts
        WHERE ts.ContractorAssignStatus = 1
        AND ts.ChallanType = 1
        AND NOT EXISTS (
            SELECT 1 FROM tbl_installation_flow f
            WHERE f.CustId = ts.CustId
            AND f.is_completed = 0
        )");
}

function installationDashboardItemCount($optionId)
{
    switch ((string) $optionId) {
        case '175':
            return installationDashboardAssignPendingCount();
        case '176':
            return installationDashboardFlowCount("current_stage='COORDINATOR' AND is_completed=0");
        case '177':
            return installationDashboardFlowCount("is_completed=0 AND (current_stage='MANAGER' OR (current_stage='COORDINATOR' AND coordinator_due_date IS NOT NULL AND coordinator_due_date <= NOW()))");
        case '178':
            return installationDashboardFlowCount("current_stage IN ('GENERAL_MANAGER','GM') AND is_completed=0");
        case '179':
            return installationDashboardExtensionCount("status='PENDING' AND requested_role='MANAGER'");
        case '180':
            return installationDashboardFlowCount("is_completed=0 AND (current_stage='BUSINESS_HEAD' OR (current_stage IN ('GENERAL_MANAGER','GM') AND gm_due_date IS NOT NULL AND gm_due_date <= NOW()) OR current_stage='DISPUTE')");
        case '181':
            return installationDashboardExtensionCount("status='PENDING' AND requested_role='GM'");
        case '182':
            return installationDashboardFlowCount("current_stage='DISPUTE' AND status='DISPUTED'");
        default:
            return 0;
    }
}

function installationDashboardFilterByOptions($registry, $options)
{
    $filtered = array();

    foreach ($registry as $item) {
        if (!installationDashboardUserHasOption($options, $item['option'])) {
            continue;
        }
        $filtered[] = $item;
    }

    return $filtered;
}

function getInstallationDashboardData($options)
{
    $registry = installationDashboardFilterByOptions(installationDashboardRegistry(), $options);
    $items = array();

    foreach ($registry as $item) {
        $item['count'] = installationDashboardItemCount($item['option']);
        $items[] = $item;
    }

    $active = installationDashboardFlowCount("is_completed=0 AND status='ACTIVE'");
    $completed = installationDashboardFlowCount('is_completed=1');
    $disputed = installationDashboardFlowCount("current_stage='DISPUTE' AND status='DISPUTED'");
    $pipeline = 0;
    foreach ($items as $item) {
        if ($item['option'] !== '182') {
            $pipeline += (int) $item['count'];
        }
    }

    return array(
        'items' => $items,
        'active_flows' => $active,
        'completed_flows' => $completed,
        'disputed_flows' => $disputed,
        'pipeline_total' => $pipeline,
    );
}

function installationDashboardStatCard($item)
{
    $label = htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8');
    $count = (int) $item['count'];
    $href = htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8');
    $metric = htmlspecialchars($item['metric'], ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $item['tone']);
    ?>
    <a href="<?php echo $href; ?>" class="inst-dash-stat-link">
        <div class="inst-dash-stat-card inst-dash-tone-<?php echo $tone; ?>">
            <div class="inst-dash-stat-icon"><i class="feather icon-<?php echo $icon; ?>"></i></div>
            <div class="inst-dash-stat-body">
                <p class="inst-dash-stat-label"><?php echo $label; ?></p>
                <h3 class="inst-dash-stat-count"><?php echo number_format($count); ?></h3>
                <span class="inst-dash-stat-sub"><?php echo $metric; ?></span>
            </div>
        </div>
    </a>
    <?php
}
