<?php

/**
 * Search index: only pages that appear in the user's sidebar / module menus.
 */
require_once __DIR__ . '/inc-menu-option-groups.php';

/**
 * @return array<int, array{title:string,url:string,group:string,keywords:string}>
 */
function adminMenuSearchExtractLinksFromHtml($html, $urlPrefix, $base, $groupName)
{
    $entries = [];
    if (!preg_match_all(
        '/<a\s+[^>]*href=(["\'])([^"\']+)\1[^>]*class="[^"]*sidenav-link[^"]*"[^>]*>(.*?)<\/a>/is',
        (string) $html,
        $matches,
        PREG_SET_ORDER
    )) {
        return $entries;
    }

    foreach ($matches as $m) {
        $href = trim($m[2]);
        if ($href === '' || stripos($href, 'javascript') === 0 || $href === '#') {
            continue;
        }

        $inner = $m[3];
        $title = '';
        if (preg_match('/<div[^>]*>([^<]+)<\/div>/i', $inner, $tm)) {
            $title = trim(html_entity_decode(strip_tags($tm[1]), ENT_QUOTES, 'UTF-8'));
        } else {
            $title = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES, 'UTF-8'));
        }
        if ($title === '' || strcasecmp($title, 'Logout') === 0) {
            continue;
        }

        $url = $href;
        if (strpos($url, '://') === false && $url[0] !== '/') {
            $url = $base . $urlPrefix . ltrim($url, './');
        }

        $entries[] = [
            'title' => $title,
            'url' => $url,
            'group' => $groupName,
            'keywords' => strtolower($title . ' ' . $url),
        ];
    }

    return $entries;
}

/**
 * Render header.php sidenav once and extract visible links for this user.
 *
 * @return array<int, array{title:string,url:string,group:string,keywords:string}>
 */
function adminMenuSearchCaptureMainHeader($base)
{
    global $row77, $MainPage, $Page, $fileSubmissionReminderCount, $fileSubmissionReminderAlert, $SiteUrl;

    if (empty($row77) || empty($row77['id'])) {
        return [];
    }

    $prevMainPage = $MainPage ?? '';
    $prevPage = $Page ?? '';
    $MainPage = '';
    $Page = '';

    ob_start();
    try {
        include __DIR__ . '/header.php';
    } catch (Throwable $e) {
        ob_end_clean();
        $MainPage = $prevMainPage;
        $Page = $prevPage;

        return [];
    }
    $html = ob_get_clean();
    $MainPage = $prevMainPage;
    $Page = $prevPage;

    if (preg_match('/<div id="layout-sidenav"[\s\S]*?<\/div>\s*(?=<\/div>)/i', $html, $m)) {
        $html = $m[0];
    }

    return adminMenuSearchExtractLinksFromHtml($html, '', $base, 'Menu');
}

/**
 * Module sidebars (only when user has that module).
 *
 * @return array<int, array{file:string,prefix:string,group:string,options:int[]}>
 */
function adminMenuSearchModuleSidebarConfig()
{
    $g = getMenuOptionGroups();

    return [
        ['file' => 'master_management/master-sidebar.php', 'prefix' => 'master_management/', 'group' => 'Master Management', 'options' => $g['Master Management']],
        ['file' => 'report_management/report-sidebar.php', 'prefix' => 'report_management/', 'group' => 'Reports', 'options' => $g['Reports']],
        ['file' => 'user_management/account-sidebar.php', 'prefix' => 'user_management/', 'group' => 'Users', 'options' => $g['User Accounts']],
        ['file' => 'lead_management/lead-sidebar.php', 'prefix' => 'lead_management/', 'group' => 'Lead Management', 'options' => $g['Lead Management']],
        ['file' => 'dealer_lead_management/lead-sidebar.php', 'prefix' => 'dealer_lead_management/', 'group' => 'Dealer Leads', 'options' => $g['Dealer Lead Management']],
        ['file' => 'product_management/product-sidebar.php', 'prefix' => 'product_management/', 'group' => 'Product', 'options' => $g['Product Management']],
        ['file' => 'task_management/task-sidebar.php', 'prefix' => 'task_management/', 'group' => 'Tasks', 'options' => [47, 151]],
        ['file' => 'task-sidebar.php', 'prefix' => '', 'group' => 'Tasks', 'options' => [47, 151]],
        ['file' => 'warranty-sidebar.php', 'prefix' => '', 'group' => 'Warranty', 'options' => $g['Warranty']],
        ['file' => 'service-sidebar.php', 'prefix' => '', 'group' => 'Service', 'options' => $g['Service Complaint']],
        ['file' => 'installation-sidebar.php', 'prefix' => '', 'group' => 'Installation', 'options' => array_merge($g['Installation Workflow'], [68])],
        ['file' => 'rooftop-installation-sidebar.php', 'prefix' => '', 'group' => 'Rooftop', 'options' => menuAccessRooftopOptionIds()],
        ['file' => 'mpuvnl_management/mp-sidebar.php', 'prefix' => 'mpuvnl_management/', 'group' => 'MPUVNL', 'options' => menuAccessMpuvnlOptionIds()],
    ];
}

/**
 * @param string[] $options
 * @return array<int, array{title:string,url:string,group:string,keywords:string}>
 */
function adminMenuSearchCaptureModuleSidebars(array $options, $base)
{
    global $row77, $MainPage, $Page;

    $entries = [];
    $prevMainPage = $MainPage ?? '';
    $prevPage = $Page ?? '';
    $MainPage = '';
    $Page = '';

    foreach (adminMenuSearchModuleSidebarConfig() as $cfg) {
        if ($cfg['options'] !== [] && !userHasAnyMenuOption($options, $cfg['options'])) {
            continue;
        }
        $path = __DIR__ . '/' . $cfg['file'];
        if (!is_file($path)) {
            continue;
        }
        ob_start();
        try {
            include $path;
        } catch (Throwable $e) {
            ob_end_clean();
            continue;
        }
        $html = ob_get_clean();
        $entries = array_merge(
            $entries,
            adminMenuSearchExtractLinksFromHtml($html, $cfg['prefix'], $base, $cfg['group'])
        );
    }

    $MainPage = $prevMainPage;
    $Page = $prevPage;

    return $entries;
}

/**
 * @param int $roll
 * @param string[] $options
 * @param string $base
 * @return array<int, array{title:string,url:string,group:string,keywords:string}>
 */
function adminMenuSearchBuildIndex($roll, array $options, $base = '')
{
    static $cacheKey = null;
    static $cache = [];

    $key = $roll . '|' . implode(',', $options) . '|' . $base;
    if ($cacheKey === $key) {
        return $cache;
    }

    $seen = [];
    $entries = [];

    $merge = function (array $rows) use (&$entries, &$seen) {
        foreach ($rows as $row) {
            $url = $row['url'];
            if (isset($seen[$url])) {
                continue;
            }
            $seen[$url] = true;
            $entries[] = $row;
        }
    };

    $merge(adminMenuSearchCaptureMainHeader($base));
    $merge(adminMenuSearchCaptureModuleSidebars($options, $base));

    $merge([
        [
            'title' => 'Change Password',
            'url' => $base . 'change-password.php',
            'group' => 'Account',
            'keywords' => 'change password account',
        ],
    ]);

    usort($entries, function ($a, $b) {
        $c = strcasecmp($a['group'], $b['group']);
        if ($c !== 0) {
            return $c;
        }

        return strcasecmp($a['title'], $b['title']);
    });

    $cacheKey = $key;
    $cache = $entries;

    return $entries;
}

/**
 * @param int $roll
 * @param string[] $options
 * @param string $base
 */
function adminMenuSearchFilter($roll, array $options, $query, $base = '')
{
    $q = strtolower(trim((string) $query));
    if ($q === '') {
        return [];
    }
    $out = [];
    foreach (adminMenuSearchBuildIndex($roll, $options, $base) as $row) {
        if (strpos(strtolower($row['title']), $q) !== false
            || strpos(strtolower($row['group']), $q) !== false
            || strpos($row['keywords'], $q) !== false) {
            $out[] = $row;
            if (count($out) >= 25) {
                break;
            }
        }
    }

    return $out;
}
