<?php

/**
 * Top navbar: global search + logged-in profile (change password, logout).
 * Include from any rooftopadmin page after session + config.
 */

if (!function_exists('topHeaderRooftopBasePath')) {
    function topHeaderRooftopBasePath()
    {
        $sn = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if (preg_match('#/rooftopadmin/(.+)$#i', $sn, $m)) {
            $depth = substr_count($m[1], '/');

            return $depth > 0 ? str_repeat('../', $depth) : '';
        }

        return '';
    }
}

$topHeaderBase = topHeaderRooftopBasePath();

if (empty($row77) || empty($row77['id'])) {
    $topHeaderUid = (int) ($_SESSION['Admin']['id'] ?? 0);
    if ($topHeaderUid > 0 && function_exists('getRecord')) {
        $row77 = getRecord("SELECT * FROM tbl_users WHERE id='$topHeaderUid' LIMIT 1");
    }
}

$topHeaderName = '';
$topHeaderPhoto = '';
if (!empty($row77)) {
    $topHeaderName = trim((string) ($row77['Fname'] ?? '') . ' ' . (string) ($row77['Lname'] ?? ''));
    $topHeaderPhoto = trim((string) ($row77['Photo'] ?? ''));
}
if ($topHeaderName === '') {
    $topHeaderName = 'User';
}

$topHeaderSearchQ = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$topHeaderSearchAction = $topHeaderBase . 'admin-search.php';
$topHeaderChangePassUrl = $topHeaderBase . 'change-password.php';
$topHeaderLogoutUrl = $topHeaderBase . 'logout.php';

$topHeaderSearchIndexJson = '[]';

$topHeaderSite = isset($SiteUrl) ? rtrim((string) $SiteUrl, '/') : '';
$topHeaderPhotoUrl = $topHeaderPhoto !== '' && $topHeaderSite !== ''
    ? $topHeaderSite . '/uploads/' . rawurlencode($topHeaderPhoto)
    : ($topHeaderSite !== '' ? $topHeaderSite . '/user_icon.jpg' : $topHeaderBase . 'user_icon.jpg');

static $topHeaderAssetsLoaded = false;
if (!$topHeaderAssetsLoaded) {
    $topHeaderAssetsLoaded = true;
    echo '<link rel="stylesheet" href="' . htmlspecialchars($topHeaderBase . 'css/top-header-bar.css', ENT_QUOTES, 'UTF-8') . '">' . "\n";
}
?>
<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-dark container-p-x top-header-bar" id="layout-navbar">
    <div class="top-header-toolbar w-100 d-flex flex-wrap align-items-center">
    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
        <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)">
            <i class="ion ion-md-menu text-large align-middle text-white"></i>
        </a>
    </div>
    <a href="<?php echo htmlspecialchars($topHeaderBase . 'dashboard.php', ENT_QUOTES, 'UTF-8'); ?>" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
        <span class="app-brand-text demo font-weight-normal ml-2 text-white" style="font-size: 22px;"><?php echo htmlspecialchars($Proj_Title ?? 'VTECH', ENT_QUOTES, 'UTF-8'); ?></span>
    </a>

    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse" aria-controls="layout-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
        <div class="top-header-search-wrap">
            <form id="topHeaderSearchForm" class="top-header-search-form" method="get" action="<?php echo htmlspecialchars($topHeaderSearchAction, ENT_QUOTES, 'UTF-8'); ?>" role="search" data-base="<?php echo htmlspecialchars($topHeaderBase, ENT_QUOTES, 'UTF-8'); ?>">
                <i class="feather icon-search top-header-search-icon" aria-hidden="true"></i>
                <input type="search" name="q" id="topHeaderSearchInput" class="form-control" placeholder="Search pages, reports, menus…" value="<?php echo htmlspecialchars($topHeaderSearchQ, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" aria-label="Search" aria-controls="topHeaderSearchResults" aria-expanded="false">
                <div id="topHeaderSearchResults" class="top-header-search-results" aria-hidden="true"></div>
            </form>
            <script type="application/json" id="adminMenuSearchIndex"><?php echo $topHeaderSearchIndexJson; ?></script>
        </div>

        <div class="navbar-nav align-items-lg-center ml-auto">
            <div class="nav-item dropdown top-header-profile" id="topHeaderProfileDropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="topHeaderProfileToggle" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                        <img src="<?php echo htmlspecialchars($topHeaderPhotoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="top-header-avatar" onerror="this.onerror=null;this.src='<?php echo htmlspecialchars($topHeaderBase . 'user_icon.jpg', ENT_QUOTES, 'UTF-8'); ?>';">
                        <span class="px-1 mr-lg-2 ml-2 ml-lg-0 top-header-profile-name d-none d-sm-inline"><?php echo htmlspecialchars($topHeaderName, ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" id="topHeaderProfileMenu" aria-labelledby="topHeaderProfileToggle">
                    <div class="dropdown-header text-muted small px-3 py-2"><?php echo htmlspecialchars($topHeaderName, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo htmlspecialchars($topHeaderChangePassUrl, ENT_QUOTES, 'UTF-8'); ?>" class="dropdown-item">
                        <i class="feather icon-unlock text-muted"></i> Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo htmlspecialchars($topHeaderLogoutUrl, ENT_QUOTES, 'UTF-8'); ?>" class="dropdown-item text-danger">
                        <i class="feather icon-power"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</nav>
<?php

static $topHeaderProfileScriptLoaded = false;
if (!$topHeaderProfileScriptLoaded) {
    $topHeaderProfileScriptLoaded = true;
    ?>
<script>
(function () {
    function closeTopHeaderProfileMenu() {
        var wrap = document.getElementById('topHeaderProfileDropdown');
        var btn = document.getElementById('topHeaderProfileToggle');
        var menu = document.getElementById('topHeaderProfileMenu');
        if (!wrap || !btn || !menu) {
            return;
        }
        wrap.classList.remove('show');
        menu.classList.remove('show');
        btn.setAttribute('aria-expanded', 'false');
    }

    function openTopHeaderProfileMenu() {
        var wrap = document.getElementById('topHeaderProfileDropdown');
        var btn = document.getElementById('topHeaderProfileToggle');
        var menu = document.getElementById('topHeaderProfileMenu');
        if (!wrap || !btn || !menu) {
            return;
        }
        wrap.classList.add('show');
        menu.classList.add('show');
        btn.setAttribute('aria-expanded', 'true');
    }

    function initTopHeaderProfileMenu() {
        var btn = document.getElementById('topHeaderProfileToggle');
        if (!btn || btn.getAttribute('data-profile-menu-init') === '1') {
            return;
        }
        btn.setAttribute('data-profile-menu-init', '1');

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var wrap = document.getElementById('topHeaderProfileDropdown');
            if (wrap && wrap.classList.contains('show')) {
                closeTopHeaderProfileMenu();
            } else {
                openTopHeaderProfileMenu();
            }
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#topHeaderProfileDropdown')) {
                closeTopHeaderProfileMenu();
            }
        });

        window.addEventListener('resize', closeTopHeaderProfileMenu);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTopHeaderProfileMenu);
    } else {
        initTopHeaderProfileMenu();
    }
})();
</script>
    <?php
}
