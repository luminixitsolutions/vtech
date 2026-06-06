<?php

/**
 * Secondary horizontal nav (light bar below top header).
 */
require_once __DIR__ . '/inc-menu-option-groups.php';

function adminHorizontalNavItems()
{
    $base = function_exists('topHeaderAdminBasePath') ? topHeaderAdminBasePath() : '';

    return [
        [
            'label' => 'Employee Management',
            'url' => $base . 'user_management/account-managment-dashboard.php',
            'options' => getMenuOptionGroups()['User Accounts'],
            'match' => ['account-managment', 'add-employee', 'view-employee', 'add-store-incharge', 'add-dispatch', 'add-dealer', 'add-agency', 'add-driver', 'add-installer', 'customer-profile', 'pump-customers', 'user_management/'],
        ],
        [
            'label' => 'Leads',
            'url' => $base . menuAccessLeadModuleEntryUrl([]),
            'options' => getMenuOptionGroups()['Lead Management'],
            'match' => ['lead_management/', 'lead-management', 'dealer_lead_management/'],
            'url_resolver' => 'menuAccessLeadModuleEntryUrl',
        ],
        [
            'label' => 'Reports',
            'url' => $base . 'report_management/report-dashboard.php',
            'options' => getMenuOptionGroups()['Reports'],
            'match' => ['report_management/', 'employee-tracking'],
        ],
        [
            'label' => 'Productivity',
            'url' => $base . 'task-dashboard.php',
            'options' => [47, 151],
            'match' => ['task_management/', 'task-dashboard', 'to-do'],
        ],
        [
            'label' => 'Master',
            'url' => $base . 'master_management/masters-dashboard.php',
            'options' => getMenuOptionGroups()['Master Management'],
            'match' => ['master_management/', 'masters-dashboard', 'common-master'],
        ],
        [
            'label' => 'Operations',
            'url' => $base . 'dashboard.php',
            'options' => menuAccessOperationsOptionIds(),
            'match' => ['assign-', 'distribute-', 'purchase-order', 'view-purchase', 'installation'],
        ],
        [
            'label' => 'Insurance',
            'url' => $base . 'insurance-dashboard.php',
            'options' => array_merge(getMenuOptionGroups()['Insurance Site'], [121]),
            'match' => ['insurance', 'pending-insurance', 'completed-insurance'],
        ],
    ];
}

function adminHorizontalNavCanSeeItem(array $item, $roll, array $options)
{
    if (function_exists('adminUserHasFullMenuAccess') && adminUserHasFullMenuAccess($roll)) {
        return true;
    }
    if (empty($item['options'])) {
        return true;
    }

    return userHasAnyMenuOption($options, $item['options']);
}

function adminHorizontalNavIsActive(array $item)
{
    $path = strtolower(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '')));
    foreach ($item['match'] as $needle) {
        if ($needle !== '' && strpos($path, strtolower($needle)) !== false) {
            return true;
        }
    }

    return false;
}

function renderAdminHorizontalNav($roll, array $options)
{
    static $assetsLoaded = false;
    $base = function_exists('topHeaderAdminBasePath') ? topHeaderAdminBasePath() : '';
    if (!$assetsLoaded) {
        $assetsLoaded = true;
        echo '<link rel="stylesheet" href="' . htmlspecialchars($base . 'css/admin-horizontal-nav.css', ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }

    $visible = [];
    foreach (adminHorizontalNavItems() as $item) {
        if (adminHorizontalNavCanSeeItem($item, $roll, $options)) {
            if (!empty($item['url_resolver']) && function_exists($item['url_resolver'])) {
                $item['url'] = $base . call_user_func($item['url_resolver'], $options);
            }
            $visible[] = $item;
        }
    }
    if (empty($visible)) {
        return;
    }
    ?>
<div class="admin-horizontal-nav-wrap">
    <nav class="admin-horizontal-nav" aria-label="Main modules">
        <?php foreach ($visible as $item) {
            $active = adminHorizontalNavIsActive($item) ? ' is-active' : '';
            ?>
        <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>" class="admin-horizontal-nav-link<?php echo $active; ?>">
            <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
        <?php } ?>
    </nav>
</div>
    <?php
}
