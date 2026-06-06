<?php

/**
 * Single source of truth for rooftopadmin menu/submenu tbl_options.
 * Pump-only admin portal IDs are excluded from assignable / auto-discovery lists.
 */
define('MENU_ACCESS_OPT_ADD', 14);
define('MENU_ACCESS_OPT_EDIT', 10);
define('MENU_ACCESS_OPT_DELETE', 11);

/** tbl_options used on admin (pump) portal only — not assignable on rooftop account forms. */
function menuAccessPumpOnlyOptionIds()
{
    return [
        64, 17, 56, 72, 73, 74, 75, 76, 77, 97, 98, 34, 89, 90, 91, 92, 117, 140, 141,
        113, 122, 125, 126, 127, 128, 116, 129,
        149, 150, 151, 152, 153,
        154, 155, 156, 157, 158, 159,
        80, 81, 68, 144, 145, 146, 161, 162,
        35, 37, 42, 66, 85, 87, 88,
        164, 121,
        174, 175, 176, 177, 178, 179, 180, 181, 182,
        39, 115, 119, 120, 142, 143, 160, 184, 185, 186, 187,
        61,
    ];
}

function getMenuOptionGroups()
{
    return [
        'Lead Management' => [44, 45, 46, 47, 48, 49, 50, 51, 52, 63],
        'Master Management' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 12, 13, 15, 16, 53, 54],
        'Product Management' => [24],
        'User Accounts' => [18, 19, 20, 21, 22, 23],
        'Customer Assignment' => [55, 79],
        'Production Plan' => [130, 131],
        'Survey' => [147, 148],
        'Production / Final Plan' => [132, 133, 134],
        'Purchase & Quotation' => [25, 27],
        'Store & Dispatch Assignment' => [58, 70, 60, 71],
        'Item Transfer' => [165, 166, 72, 183],
        'Delivery & Installation' => [26, 82, 83, 167, 84],
        'Service Complaint' => [28, 135, 136, 137],
        'MPUVNL & Tasks' => [93, 94, 95, 96, 118],
        'Other Modules' => [138, 139, 69, 37],
        'Reports' => [29, 30, 31, 38, 40, 65, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112],
        'Insurance Site' => [168, 169, 170, 171, 172, 173],
        'MSEDCL SMART Project' => [188, 189, 190, 191, 192, 193],
        'Action' => [MENU_ACCESS_OPT_ADD, MENU_ACCESS_OPT_EDIT, MENU_ACCESS_OPT_DELETE],
    ];
}

/** Option ids listed explicitly in getMenuOptionGroups() (excludes DB-discovered rows). */
function getMenuOptionIdsFromGroupsOnly()
{
    $ids = [];
    foreach (getMenuOptionGroups() as $groupIds) {
        foreach ($groupIds as $id) {
            $ids[(int) $id] = true;
        }
    }
    $ids[MENU_ACCESS_OPT_ADD] = true;
    $ids[MENU_ACCESS_OPT_EDIT] = true;
    $ids[MENU_ACCESS_OPT_DELETE] = true;

    return array_keys($ids);
}

/**
 * tbl_options rows not yet listed in getMenuOptionGroups() — shown under Additional Menus.
 *
 * @return int[]
 */
function getMenuOptionDiscoveredIds()
{
    global $conn;
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $grouped = array_flip(getMenuOptionIdsFromGroupsOnly());
    $pumpOnly = array_flip(menuAccessPumpOnlyOptionIds());
    $cache = [];
    $res = $conn->query('SELECT id FROM tbl_options ORDER BY id');
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $id = (int) $row['id'];
            if (!isset($grouped[$id]) && !isset($pumpOnly[$id])) {
                $cache[] = $id;
            }
        }
    }

    return $cache;
}

/** @return array<string, array<int>> */
function getMenuAccessTree()
{
    $tree = getMenuOptionGroups();
    $discovered = getMenuOptionDiscoveredIds();
    if (!empty($discovered)) {
        $tree['Additional Menus'] = $discovered;
    }

    return $tree;
}

/** All assignable menu/submenu option ids (groups + newly discovered tbl_options). */
function getMenuOptionIdsFlat()
{
    $ids = [];
    foreach (getMenuAccessTree() as $groupIds) {
        foreach ($groupIds as $id) {
            $ids[(int) $id] = true;
        }
    }
    $ids[MENU_ACCESS_OPT_ADD] = true;
    $ids[MENU_ACCESS_OPT_EDIT] = true;
    $ids[MENU_ACCESS_OPT_DELETE] = true;

    return array_keys($ids);
}

/** Rolls that see every menu/submenu (admin / super admin). */
function adminMenuAccessBypassRolls()
{
    return [1, 7];
}

function adminUserHasFullMenuAccess($roll)
{
    return in_array((int) $roll, adminMenuAccessBypassRolls(), true);
}

/**
 * Effective tbl_options ids for menu visibility (all assignable ids for admin rolls).
 *
 * @param string|array|null $options
 * @return string[]
 */
function adminUserEffectiveOptions($roll, $options)
{
    if (adminUserHasFullMenuAccess($roll)) {
        return array_map('strval', getMenuOptionIdsFlat());
    }
    if (is_array($options)) {
        $parts = $options;
    } else {
        $parts = explode(',', (string) $options);
    }

    return array_values(array_filter($parts, function ($v) {
        return $v !== '' && $v !== null && $v !== '0';
    }));
}

/**
 * @param array<string,mixed> $row77 tbl_users row (needs Roll, Options)
 * @return string[]
 */
function adminResolveMenuOptionsFromUserRow(array $row77)
{
    return adminUserEffectiveOptions(
        (int) ($row77['Roll'] ?? 0),
        $row77['Options'] ?? ''
    );
}

function userHasAnyMenuOption(array $options, array $ids)
{
    foreach ($ids as $id) {
        if (in_array((string) $id, $options, true)) {
            return true;
        }
    }
    return false;
}

function menuAccessOptionIsChecked($id, array $selectedOptions)
{
    return in_array($id, $selectedOptions, false)
        || in_array((string) $id, $selectedOptions, true);
}

/**
 * Labels for tbl_options rows added in code/migrations (when DB Name is missing).
 *
 * @return array<int, string>
 */
function menuAccessBuiltinOptionLabels()
{
    return [
        160 => 'Dispatch Customer CSV Report',
        168 => 'Insurance Site Dashboard',
        169 => 'Pending Insurance',
        170 => 'Completed Insurance',
        171 => 'Upcoming Renewal Insurance',
        172 => 'Expired Insurance',
        173 => 'Renewed Insurance',
        188 => 'Dashboard',
        189 => 'Applications on PMSGY Portal',
        190 => 'Applications on Mahadiscom Portal',
        191 => 'Payment Done by Customers',
        192 => 'Survey Pending',
        193 => 'MSEDCL SMART PROJECT ABSTRACT',
        183 => 'Serial Location Report',
        184 => 'Store Stock Report 2',
        185 => 'Attendance Report 2',
        186 => 'Delay Calculation Report 2',
        187 => 'Employee Tracking',
    ];
}

function menuAccessResolveOptionName($optId, array $nameMap)
{
    $optId = (int) $optId;
    $fromDb = isset($nameMap[$optId]) ? trim((string) $nameMap[$optId]) : '';
    if ($fromDb !== '') {
        return $fromDb;
    }
    $builtin = menuAccessBuiltinOptionLabels();
    if (isset($builtin[$optId])) {
        return $builtin[$optId];
    }

    return 'Option ' . $optId;
}

function menuAccessLoadOptionNames(array $ids)
{
    global $conn;
    $ids = array_values(array_unique(array_map('intval', $ids)));
    if (empty($ids)) {
        return [];
    }
    $idList = implode(',', $ids);
    $rows = getList("SELECT id, Name FROM tbl_options WHERE id IN($idList)");
    $map = [];
    $builtin = menuAccessBuiltinOptionLabels();
    foreach ($rows as $row) {
        $id = (int) $row['id'];
        $name = trim((string) ($row['Name'] ?? ''));
        $map[$id] = $name !== '' ? $name : (isset($builtin[$id]) ? $builtin[$id] : '');
    }
    foreach ($ids as $id) {
        $id = (int) $id;
        if (!isset($map[$id]) && isset($builtin[$id])) {
            $map[$id] = $builtin[$id];
        }
    }

    return $map;
}

/**
 * Insert/update builtin option names in tbl_options (safe to run repeatedly).
 */
function menuAccessSyncBuiltinOptionsToDb()
{
    global $conn;
    foreach (menuAccessBuiltinOptionLabels() as $id => $name) {
        $id = (int) $id;
        $nameEsc = $conn->real_escape_string($name);
        $conn->query("INSERT INTO tbl_options (id, Name) VALUES ($id, '$nameEsc')
            ON DUPLICATE KEY UPDATE Name = '$nameEsc'");
    }
}

function renderMenuAccessAccordion(array $selectedOptions)
{
    $selectedOptions = array_values(array_filter($selectedOptions, function ($v) {
        return $v !== '' && $v !== null;
    }));
    $tree = getMenuAccessTree();
    $allViewIds = [];
    foreach ($tree as $childIds) {
        foreach ($childIds as $id) {
            $allViewIds[] = (int) $id;
        }
    }
    $nameMap = menuAccessLoadOptionNames($allViewIds);
    static $assetsLoaded = false;
    if (!$assetsLoaded) {
        $assetsLoaded = true;
        echo '<link rel="stylesheet" href="../css/menu-access.css">' . "\n";
    }
    ?>
<div class="col-md-12 menu-access-panel">
    <h5 class="font-weight-bold mb-2">Menu access</h5>
    <p class="menu-access-intro">
        Use <strong>View</strong> to allow access to each screen. Click <strong>+</strong> to expand sub-menus.
        The module <strong>View</strong> checkbox selects or clears all sub-menus in that module.
        Under <strong>Action</strong>, enable <strong>Add</strong>, <strong>Edit</strong>, or <strong>Delete</strong> to allow that action on all menus and sub-menus in the application.
    </p>
    <table class="menu-access-table">
        <thead>
            <tr>
                <th>Module / screen</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $moduleIndex = 0;
            foreach ($tree as $moduleLabel => $childIds) {
                $moduleIndex++;
                $moduleKey = 'mod-' . $moduleIndex;
                $childIds = array_values(array_map('intval', $childIds));
                $allChildrenChecked = !empty($childIds);
                foreach ($childIds as $cid) {
                    if (!menuAccessOptionIsChecked($cid, $selectedOptions)) {
                        $allChildrenChecked = false;
                        break;
                    }
                }
                $childIdAttr = implode(',', $childIds);
                ?>
            <tr class="menu-access-module-row" data-module="<?php echo htmlspecialchars($moduleKey); ?>">
                <td class="module-name-cell">
                    <button type="button" class="menu-access-toggle" data-module="<?php echo htmlspecialchars($moduleKey); ?>" aria-expanded="false" title="Show sub-menus">+</button>
                    <?php echo htmlspecialchars($moduleLabel); ?>
                </td>
                <td>
                    <input type="checkbox" class="menu-access-check menu-access-parent-view"
                        data-module="<?php echo htmlspecialchars($moduleKey); ?>"
                        data-child-ids="<?php echo htmlspecialchars($childIdAttr); ?>"
                        <?php if ($allChildrenChecked && !empty($childIds)) { ?>checked="checked"<?php } ?>>
                </td>
            </tr>
            <tr class="menu-access-submenu-row" data-module="<?php echo htmlspecialchars($moduleKey); ?>" style="display:none;">
                <td colspan="2">
                    <table class="menu-access-submenu-table">
                        <thead>
                            <tr>
                                <th>Sub-menu</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($childIds as $optId) {
                                $optName = menuAccessResolveOptionName($optId, $nameMap);
                                $breadcrumb = htmlspecialchars($optName);
                                $viewChecked = menuAccessOptionIsChecked($optId, $selectedOptions);
                                $isGlobalAction = ($moduleLabel === 'Action');
                                ?>
                            <tr<?php if ($isGlobalAction) { ?> class="menu-access-action-row"<?php } ?>>
                                <td><?php echo $breadcrumb; ?></td>
                                <td>
                                    <input type="checkbox" class="menu-access-check menu-access-view<?php if ($isGlobalAction) { ?> menu-access-global-action<?php } ?>"
                                        name="Options[]" value="<?php echo (int) $optId; ?>"
                                        data-module="<?php echo htmlspecialchars($moduleKey); ?>"
                                        <?php if ($isGlobalAction) { ?>title="Allows <?php echo $breadcrumb; ?> on all menus and sub-menus"<?php } ?>
                                        <?php if ($viewChecked) { ?>checked="checked"<?php } ?>>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
(function () {
    function syncParentView(moduleKey) {
        var views = document.querySelectorAll('.menu-access-view[data-module="' + moduleKey + '"]');
        var parent = document.querySelector('.menu-access-parent-view[data-module="' + moduleKey + '"]');
        if (!parent || !views.length) return;
        var allOn = true;
        views.forEach(function (cb) { if (!cb.checked) allOn = false; });
        parent.checked = allOn;
        parent.indeterminate = !allOn && [].some.call(views, function (cb) { return cb.checked; });
    }
    document.querySelectorAll('.menu-access-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var moduleKey = btn.getAttribute('data-module');
            var row = document.querySelector('.menu-access-submenu-row[data-module="' + moduleKey + '"]');
            if (!row) return;
            var open = row.style.display !== 'none';
            row.style.display = open ? 'none' : '';
            btn.textContent = open ? '+' : '−';
            btn.classList.toggle('is-open', !open);
            btn.setAttribute('aria-expanded', open ? 'false' : 'true');
        });
    });
    document.querySelectorAll('.menu-access-parent-view').forEach(function (parent) {
        parent.addEventListener('change', function () {
            var moduleKey = parent.getAttribute('data-module');
            var checked = parent.checked;
            parent.indeterminate = false;
            document.querySelectorAll('.menu-access-view[data-module="' + moduleKey + '"]').forEach(function (cb) {
                cb.checked = checked;
            });
        });
    });
    document.querySelectorAll('.menu-access-view').forEach(function (cb) {
        cb.addEventListener('change', function () {
            syncParentView(cb.getAttribute('data-module'));
        });
    });
    document.querySelectorAll('.menu-access-parent-view').forEach(function (p) {
        syncParentView(p.getAttribute('data-module'));
    });
})();
</script>
    <?php
}

