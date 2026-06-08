<?php

/**
 * Single source of truth for admin menu/submenu tbl_options (non-rooftop).
 * Rooftop-only IDs excluded from assignable / auto-discovery lists.
 */
define('MENU_ACCESS_OPT_ADD', 14);
define('MENU_ACCESS_OPT_EDIT', 10);
define('MENU_ACCESS_OPT_DELETE', 11);

require_once __DIR__ . '/inc-menu-access-sidebar-tree.php';

/** @var int[] */
function menuAccessRooftopOptionIds()
{
    return [67, 114, 123, 124, 147, 148, 167];
}

function getMenuOptionGroups()
{
    return [
        'MPUVNL Management' => [93, 94, 95, 96, 118],
        'Lead Management' => [64, 188, 44, 45, 46, 47, 48, 49, 50, 51, 52, 63],
        'Pump Application Management' => [189, 190, 191, 192, 193, 194, 195, 149, 150, 151, 152, 153],
        'Dealer Lead Management' => [154, 155, 156, 157, 158, 159],
        'Master Management' => [196, 197, 198, 1, 56, 2, 3, 4, 5, 6, 7, 72, 73, 74, 75, 76, 77, 97, 98, 8, 9, 12, 13, 34, 15, 16, 53, 54, 89, 90, 91, 92, 117, 140, 141],
        'Product Management' => [199, 200, 24, 17, 78],
        'User Accounts' => [201, 202, 122, 113, 203, 204, 205, 206, 207, 208, 125, 209, 210, 126, 211, 212, 127, 213, 214, 215, 142, 128, 216, 217, 255, 256, 257, 116, 218, 219, 22, 220, 221, 23, 222, 223, 129, 18, 19, 20, 21],
        'Assign Customers' => [55, 79],
        'Tentative Production Plan' => [130, 131],
        'Pump Survey' => [80, 81],
        'Final Production Plan' => [132, 133],
        'Under Production Beneficiary' => [134, 224],
        'PDI Verification' => [225, 226, 227, 145],
        'Purchase Order' => [228, 229, 230, 25, 27],
        'DCR Verification' => [231, 232, 146],
        'Store Incharge Assignment' => [58, 59, 233, 234, 70],
        'Dispatch Officer Assignment' => [60],
        'Assign Items To Dispatch Officer' => [235, 236, 71],
        'Transfer Item Dispatch to Store' => [237, 238, 239, 165],
        'Transfer Item Store to Store' => [240, 241, 242, 166],
        'Delivery / Sell' => [243, 244, 26],
        'Assign Challan to Dispatcher' => [82],
        'Assign Site To Installation' => [83],
        'Installation Project' => [68],
        'Assign Site To Inspection' => [84],
        'Service Complaint' => [164, 137, 135, 136, 245, 246, 247, 28],
        'Insurance Site' => [121, 168, 169, 170, 171, 172, 173],
        'Trip Details' => [138, 139],
        'File submission reminder' => [254],
        'Approve Attendance' => [144],
        'Reports' => [29, 30, 31, 38, 39, 65, 99, 185, 100, 101, 184, 183, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 115, 119, 120, 142, 143, 186, 160, 187, 248],
        'Installation Workflow' => [174, 175, 176, 177, 178, 179, 180, 181, 182],
        'Warranty' => [61],
        'Task Management' => [253, 252, 249, 250, 251, 47, 151],
        'Action' => [MENU_ACCESS_OPT_ADD, MENU_ACCESS_OPT_EDIT, MENU_ACCESS_OPT_DELETE],
    ];
}

/** @return int[] All workflow / operations sidebar option ids (for page access & nav). */
function menuAccessOperationsOptionIds()
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $keys = [
        'Assign Customers',
        'Tentative Production Plan',
        'Pump Survey',
        'Final Production Plan',
        'Under Production Beneficiary',
        'PDI Verification',
        'Purchase Order',
        'DCR Verification',
        'Store Incharge Assignment',
        'Dispatch Officer Assignment',
        'Assign Items To Dispatch Officer',
        'Transfer Item Dispatch to Store',
        'Transfer Item Store to Store',
        'Delivery / Sell',
        'Assign Challan to Dispatcher',
        'Assign Site To Installation',
        'Installation Project',
        'Assign Site To Inspection',
        'Trip Details',
        'File submission reminder',
        'Approve Attendance',
    ];
    $groups = getMenuOptionGroups();
    $ids = [];
    foreach ($keys as $key) {
        if (!isset($groups[$key])) {
            continue;
        }
        foreach ($groups[$key] as $id) {
            $ids[(int) $id] = true;
        }
    }
    $cache = array_keys($ids);

    return $cache;
}

/** Option ids listed explicitly in getMenuOptionGroups() and sidebar tree (excludes DB-discovered rows). */
function getMenuOptionIdsFromGroupsOnly()
{
    $ids = [];
    foreach (getMenuOptionGroups() as $groupIds) {
        foreach ($groupIds as $id) {
            $ids[(int) $id] = true;
        }
    }
    foreach (menuAccessDetailedTreeFlatIds() as $groupIds) {
        foreach ($groupIds as $id) {
            $ids[(int) $id] = true;
        }
    }
    if (function_exists('menuAccessAllGranularOptionIds')) {
        foreach (menuAccessAllGranularOptionIds() as $id) {
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
    $rooftop = array_flip(menuAccessRooftopOptionIds());
    $hidden = array_flip(menuAccessHiddenOptionIds());
    $cache = [];
    $res = $conn->query('SELECT id FROM tbl_options ORDER BY id');
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $id = (int) $row['id'];
            if (!isset($grouped[$id]) && !isset($rooftop[$id]) && !isset($hidden[$id])) {
                $cache[] = $id;
            }
        }
    }

    return $cache;
}

/** @return array<string, array<int>> */
function getMenuAccessTree()
{
    $tree = menuAccessDetailedTreeFlatIds();
    $tree['Action'] = getMenuOptionGroups()['Action'];
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
    $options = adminUserEffectiveOptions(
        (int) ($row77['Roll'] ?? 0),
        $row77['Options'] ?? ''
    );
    $expanded = [];
    foreach ($options as $id) {
        $expanded[] = (string) $id;
        if (function_exists('menuAccessScreenOptionIds')) {
            foreach (menuAccessScreenOptionIds((int) $id) as $sid) {
                $expanded[] = (string) $sid;
            }
        }
    }
    if (userHasAnyMenuOption($expanded, [161, 162]) && !userHasAnyMenuOption($expanded, [254])) {
        $expanded[] = '254';
    }

    return array_values(array_unique($expanded));
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

require_once __DIR__ . '/inc-menu-access-granular-options.php';

/** MPUVNL module screens (sidebar + mpuvnl_management/*). */
function menuAccessMpuvnlOptionIds()
{
    return [93, 94, 95, 96, 118];
}

/** Task management screens. */
function menuAccessTaskOptionIds()
{
    return [47, 151];
}

/** Option ids hidden from menu access UI and auto-discovery (deprecated / removed menus). */
function menuAccessHiddenOptionIds()
{
    return [35, 37, 42, 66, 69, 85, 86, 87, 88, 161, 162];
}

/** File submission reminder screen. */
function menuAccessFileSubmissionOptionIds()
{
    return [254];
}

/** Lead dashboard / stats (not Upload Excel alone). */
function menuAccessLeadDashboardOptionIds()
{
    return [44, 45, 46, 47, 48, 49, 50, 51, 52, 63, 188];
}

/** Lead + pump application pages under lead_management/. */
function menuAccessLeadModuleOptionIds()
{
    $g = getMenuOptionGroups();

    return array_values(array_unique(array_merge(
        $g['Lead Management'],
        $g['Pump Application Management']
    )));
}

function menuAccessDealerLeadOptionIds()
{
    return getMenuOptionGroups()['Dealer Lead Management'];
}

function menuAccessInstallationProjectOptionIds()
{
    return [68];
}

function menuAccessServiceComplaintOptionIds()
{
    return getMenuOptionGroups()['Service Complaint'];
}

function menuAccessProductManagementOptionIds()
{
    return getMenuOptionGroups()['Product Management'];
}

function menuAccessUserAccountsOptionIds()
{
    return getMenuOptionGroups()['User Accounts'];
}

function menuAccessReportsOptionIds()
{
    return getMenuOptionGroups()['Reports'];
}

/** First lead_management screen the user may open (not always the dashboard). */
function menuAccessLeadModuleEntryUrl(array $options)
{
    if (userHasAnyMenuOption($options, menuAccessLeadDashboardOptionIds())) {
        return 'lead_management/lead-management-dashboard.php';
    }
    $entryOrder = [
        'upload-excel.php' => [64],
        'add-irrigation-leads.php' => [188],
        'add-lead.php' => [44],
        'view-leads.php' => [45],
        'assign-leads.php' => [46],
        'view-leads-calling.php' => [47],
        'lead-completed-customers.php' => [63],
        'opportunity.php' => [49],
        'lead-quotation.php' => [50],
        'opportunity-convert-to-order.php' => [51],
        'social-media-marketing.php' => [52],
    ];
    foreach ($entryOrder as $file => $req) {
        if (userHasAnyMenuOption($options, $req)) {
            return 'lead_management/' . $file;
        }
    }

    return 'lead_management/lead-management-dashboard.php';
}

/** First pump application screen the user may open. */
function menuAccessPumpApplicationEntryUrl(array $options)
{
    $entryOrder = [
        'upload-application-excel.php' => [189],
        'view-application-form.php' => [190],
        'pending-application-form.php' => [191],
        'approve-application-form.php' => [192],
        'reject-application-form.php' => [193],
        'assign-applications.php' => [194],
        'assigned-applications.php' => [195],
        'view-application-calling.php' => [151],
        'application-completed-customers.php' => [152],
        'applications-convert-to-order.php' => [153],
    ];
    foreach ($entryOrder as $file => $req) {
        if (userHasAnyMenuOption($options, $req)) {
            return 'lead_management/' . $file;
        }
    }

    return 'lead_management/upload-application-excel.php';
}

/** First dealer lead screen the user may open. */
function menuAccessDealerLeadEntryUrl(array $options)
{
    $entryOrder = [
        'add-lead.php' => [154],
        'view-leads.php' => [155],
        'assign-leads.php' => [156],
        'view-leads-calling.php' => [157],
        'lead-completed-customers.php' => [158],
        'opportunity-convert-to-order.php' => [159],
    ];
    foreach ($entryOrder as $file => $req) {
        if (userHasAnyMenuOption($options, $req)) {
            return 'dealer_lead_management/' . $file;
        }
    }

    return 'dealer_lead_management/lead-management-dashboard.php';
}

/** Per-screen option ids for lead_management/*.php (sidebar labels). */
function menuAccessLeadManagementPageOptions()
{
    $fallback = [
        'upload-excel.php' => [64],
        'add-lead.php' => [44],
        'add-irrigation-leads.php' => [44],
        'view-leads.php' => [45],
        'view-irrigation-leads.php' => [45],
        'assign-leads.php' => [46],
        'view-leads-calling.php' => [47],
        'lead-completed-customers.php' => [63],
        'lead-quotation.php' => [50],
        'opportunity.php' => [49],
        'opportunity-convert-to-order.php' => [51],
        'social-media-marketing.php' => [52],
        'upload-application-excel.php' => [149],
        'view-application-form.php' => [149],
        'pending-application-form.php' => [149],
        'approve-application-form.php' => [149],
        'reject-application-form.php' => [149],
        'add-application-form.php' => [149],
        'assign-applications.php' => [150],
        'assigned-applications.php' => [150],
        'view-application-calling.php' => [151],
        'application-completed-customers.php' => [152],
        'applications-convert-to-order.php' => [153],
        'take-lead-action.php' => [44, 45, 47],
        'take-lead-action-2.php' => [44, 45, 47],
        'take-lead-quotation-action-2.php' => [50],
        'view-lead-action.php' => [45, 47],
        'take-application-action.php' => [151],
        'add-lead-quotation.php' => [50],
    ];
    $out = [];
    foreach ($fallback as $basename => $ids) {
        $out[$basename] = menuAccessRequiredIdsForPage($basename, $ids);
    }

    return $out;
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
        1 => 'Locations (Country / State / City)',
        2 => 'Store',
        3 => 'Issues',
        4 => 'Department',
        5 => 'Category',
        6 => 'Product',
        7 => 'Product Specification',
        8 => 'Scheme',
        9 => 'Project Head',
        10 => 'Edit (global action)',
        11 => 'Delete (global action)',
        12 => 'Project Sub Head',
        13 => 'Vendor',
        14 => 'Add (global action)',
        15 => 'User Type',
        16 => 'Branch',
        17 => 'Product Specification',
        18 => 'Customers',
        19 => 'Manufacture',
        20 => 'Company',
        21 => 'Employee',
        22 => 'Contractor',
        23 => 'Installer',
        24 => 'Product Management Dashboard',
        25 => 'Purchase Order',
        26 => 'Delivery / Sell',
        27 => 'Quotation',
        28 => 'Add Service Complaint',
        29 => 'Delivery Challan Report',
        30 => 'Stock Report',
        31 => 'Outstanding Stock Report',
        34 => 'Structure Product Specification',
        35 => 'Legacy Module 35',
        37 => 'E-Commerce Management',
        38 => 'Customer Report',
        39 => 'Daily Record Report',
        42 => 'Legacy Module 42',
        44 => 'Lead Creation',
        45 => 'View Leads',
        46 => 'Lead Assign',
        47 => 'To do Activity',
        48 => 'Appointment Scheduling',
        49 => 'Opportunity',
        50 => 'Quotation',
        51 => 'Opportunity Convert To Order',
        52 => 'Social Media Marketing',
        53 => 'Diesel Amount',
        54 => 'Employee Tracking (legacy)',
        55 => 'Assign Pump Customers To Co-ordinator',
        56 => 'PI/Quotation Products',
        58 => 'Assign To Store Incharge',
        59 => 'Approve Store Incharge',
        60 => 'Assign Beneficiary To Dispatch Officer',
        61 => 'Warranty',
        63 => 'Completed Customers',
        64 => 'Upload Excel',
        65 => 'Dealer Report',
        66 => 'Legacy Module 66',
        68 => 'Installation Project Dashboard',
        69 => 'Dealer Commission',
        70 => 'Distribute Item Store',
        71 => 'Assign Items To Dispatch Officer',
        72 => 'Bank',
        73 => 'Payment Mode',
        74 => 'Expense Head',
        75 => 'Vehicle',
        76 => 'Vehicle Type',
        77 => 'Vehicle Model',
        78 => 'Structure Product Specification',
        79 => 'Assign Pump Customers To Field Survey',
        80 => 'Pump Co-ordinator Survey',
        81 => 'Pump Field Survey',
        82 => 'Assign Challan To Dispatcher',
        83 => 'Assign Site To Installation',
        84 => 'Assign Site To Inspection',
        85 => 'Installer Menu 85',
        86 => 'Installer Menu 86',
        87 => 'Installer Menu 87',
        88 => 'Installer Menu 88',
        89 => 'Common Master',
        90 => 'Common Master 2',
        91 => 'Common Master 3',
        92 => 'Common Master 4',
        93 => 'Dispatched Calling Confirmation',
        94 => 'Before Installation Calling',
        95 => 'After Installation Calling',
        96 => 'Before Inspection Calling',
        97 => 'Designation',
        98 => 'Document Type',
        99 => 'Attendance Report',
        100 => 'Vehicle Report',
        101 => 'Store Stock Report',
        102 => 'Store Item Report',
        103 => 'Dispatch Officer Stock Report',
        104 => 'Field Survey Report',
        105 => 'Dispatch Report',
        106 => 'Installation Report',
        107 => 'Inspection Report',
        108 => 'Site Engineer Report',
        109 => 'Dispatch Calling Report',
        110 => 'Before Installation Calling Report',
        111 => 'After Installation Calling Report',
        112 => 'Before Inspection Calling Report',
        113 => 'Agency Account',
        115 => 'Trip Report',
        116 => 'Driver Account',
        255 => 'Transportor Account',
        117 => 'Project',
        118 => 'Beneficiary Selection',
        119 => 'Beneficiary Selection Calling Report',
        120 => 'Material Dispatch Report',
        121 => 'Insurance Site (legacy)',
        122 => 'Customer Profile',
        125 => 'Store Incharge Account',
        126 => 'Dispatch Officer Account',
        127 => 'Dealer Account',
        128 => 'Agency Account',
        129 => 'Contractor Account',
        130 => 'BOS Tentative Production Plan',
        131 => 'Structure Tentative Production Plan',
        132 => 'BOS Final Production Plan',
        133 => 'Structure Final Production Plan',
        134 => 'Under Production Beneficiary',
        135 => 'Allocate Complaints To Engineer',
        136 => 'Allocate Not Solved Complaints',
        137 => 'Beneficiary Service Lists',
        138 => 'Running Trips',
        139 => 'Completed Trips',
        140 => 'Project Sub Head 2',
        141 => 'Project Sub Head 3',
        142 => 'Contractor Billing Report',
        143 => 'Delay Calculation Report',
        144 => 'Approve Attendance',
        145 => 'PDI Verification',
        146 => 'DCR Verification',
        149 => 'Pump Application — Upload Excel',
        150 => 'Pump Application — Assign',
        151 => 'Pump Application — Calling / Task',
        152 => 'Pump Application — Completed Customers',
        153 => 'Pump Application — Convert To Order',
        154 => 'Dealer Lead Creation',
        155 => 'Dealer View Leads',
        156 => 'Dealer Lead Assign',
        157 => 'Dealer To do Activity',
        158 => 'Dealer Completed Customers',
        159 => 'Dealer Convert To Order',
        160 => 'Dispatch Customer CSV Report',
        161 => 'Assign Pending File Submissions',
        162 => 'Pending File Submission Lists',
        164 => 'Service Dashboard',
        165 => 'Transfer Item Dispatch to Store',
        166 => 'Transfer Item Store to Store',
        168 => 'Insurance Site Dashboard',
        169 => 'Pending Insurance',
        170 => 'Completed Insurance',
        171 => 'Upcoming Renewal Insurance',
        172 => 'Expired Insurance',
        173 => 'Renewed Insurance',
        174 => 'Installation Dashboard',
        175 => 'Assign Coordinator',
        176 => 'Coordinator Action',
        177 => 'Manager Action',
        178 => 'General Manager Action',
        179 => 'GM Extension Requests',
        180 => 'Business Head Action',
        181 => 'BH Extension Requests',
        182 => 'Dispute Sites',
        183 => 'Serial Location Report',
        184 => 'Store Stock Report 2',
        185 => 'Attendance Report 2',
        186 => 'Delay Calculation Report 2',
        187 => 'Employee Tracking',
        254 => 'File submission reminder',
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
 * Only syncs options added via migrations — does not overwrite legacy tbl_options names.
 */
function menuAccessSyncBuiltinOptionsToDb()
{
    global $conn;
    $syncIds = [160, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 254];
    $labels = menuAccessBuiltinOptionLabels();
    foreach ($syncIds as $id) {
        if (!isset($labels[$id])) {
            continue;
        }
        $nameEsc = $conn->real_escape_string($labels[$id]);
        $conn->query("INSERT INTO tbl_options (id, Name) VALUES ($id, '$nameEsc')
            ON DUPLICATE KEY UPDATE Name = IF(TRIM(Name) = '' OR Name IS NULL, '$nameEsc', Name)");
    }
}

function renderMenuAccessAccordion(array $selectedOptions)
{
    menuAccessSyncBuiltinOptionsToDb();
    if (function_exists('menuAccessSyncGranularOptionsToDb')) {
        menuAccessSyncGranularOptionsToDb();
    }
    $selectedOptions = array_values(array_filter($selectedOptions, function ($v) {
        return $v !== '' && $v !== null && $v !== '0';
    }));
    $tree = getMenuAccessDetailedTree();
    if (isset($tree['Action'])) {
        unset($tree['Action']);
    }
    $tree['Action'] = [
        ['id' => MENU_ACCESS_OPT_ADD, 'label' => 'Add (global action)', 'subs' => []],
        ['id' => MENU_ACCESS_OPT_EDIT, 'label' => 'Edit (global action)', 'subs' => []],
        ['id' => MENU_ACCESS_OPT_DELETE, 'label' => 'Delete (global action)', 'subs' => []],
    ];
    $flatIds = getMenuAccessTree();
    $allViewIds = [];
    foreach ($flatIds as $childIds) {
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
            foreach ($tree as $moduleLabel => $entries) {
                $moduleIndex++;
                $moduleKey = 'mod-' . $moduleIndex;
                $childIds = isset($flatIds[$moduleLabel]) ? $flatIds[$moduleLabel] : [];
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
                            <?php
                            foreach ($entries as $entry) {
                                if (isset($entry['group'])) {
                                    ?>
                            <tr class="menu-access-group-row">
                                <td colspan="2"><strong><?php echo htmlspecialchars($entry['group']); ?></strong></td>
                            </tr>
                                    <?php
                                    foreach ($entry['items'] as $item) {
                                        $optId = (int) ($item['id'] ?? 0);
                                        if ($optId <= 0) {
                                            continue;
                                        }
                                        $label = trim((string) ($item['label'] ?? ''));
                                        if ($label === '') {
                                            $label = menuAccessResolveOptionName($optId, $nameMap);
                                        }
                                        $viewChecked = menuAccessOptionIsChecked($optId, $selectedOptions);
                                        ?>
                            <tr>
                                <td><div class="menu-access-item-label menu-access-item-indent"><?php echo htmlspecialchars($label); ?></div></td>
                                <td>
                                    <input type="checkbox" class="menu-access-check menu-access-view"
                                        name="Options[]" value="<?php echo $optId; ?>"
                                        data-module="<?php echo htmlspecialchars($moduleKey); ?>"
                                        data-opt-id="<?php echo $optId; ?>"
                                        <?php if ($viewChecked) { ?>checked="checked"<?php } ?>>
                                </td>
                            </tr>
                                        <?php
                                    }
                                    continue;
                                }
                                $optId = (int) ($entry['id'] ?? 0);
                                if ($optId <= 0) {
                                    continue;
                                }
                                $label = trim((string) ($entry['label'] ?? ''));
                                if ($label === '') {
                                    $label = menuAccessResolveOptionName($optId, $nameMap);
                                }
                                $viewChecked = menuAccessOptionIsChecked($optId, $selectedOptions);
                                $isGlobalAction = ($moduleLabel === 'Action');
                                ?>
                            <tr<?php if ($isGlobalAction) { ?> class="menu-access-action-row"<?php } ?>>
                                <td><div class="menu-access-item-label"><?php echo htmlspecialchars($label); ?></div></td>
                                <td>
                                    <input type="checkbox" class="menu-access-check menu-access-view<?php if ($isGlobalAction) { ?> menu-access-global-action<?php } ?>"
                                        name="Options[]" value="<?php echo $optId; ?>"
                                        data-module="<?php echo htmlspecialchars($moduleKey); ?>"
                                        data-opt-id="<?php echo $optId; ?>"
                                        <?php if ($isGlobalAction) { ?>title="Allows <?php echo htmlspecialchars($label); ?> on all menus and sub-menus"<?php } ?>
                                        <?php if ($viewChecked) { ?>checked="checked"<?php } ?>>
                                </td>
                            </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
                <?php
            }
            $discovered = getMenuOptionDiscoveredIds();
            if (!empty($discovered)) {
                $moduleIndex++;
                $moduleKey = 'mod-' . $moduleIndex;
                $moduleLabel = 'Additional Menus';
                $childIds = $discovered;
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
                                $optName = menuAccessResolveOptionName((int) $optId, $nameMap);
                                $viewChecked = menuAccessOptionIsChecked($optId, $selectedOptions);
                                ?>
                            <tr>
                                <td><div class="menu-access-item-label"><?php echo htmlspecialchars($optName); ?></div></td>
                                <td>
                                    <input type="checkbox" class="menu-access-check menu-access-view"
                                        name="Options[]" value="<?php echo (int) $optId; ?>"
                                        data-module="<?php echo htmlspecialchars($moduleKey); ?>"
                                        data-opt-id="<?php echo (int) $optId; ?>"
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
            var optId = cb.getAttribute('data-opt-id');
            if (optId) {
                document.querySelectorAll('.menu-access-view[data-opt-id="' + optId + '"]').forEach(function (other) {
                    if (other !== cb) {
                        other.checked = cb.checked;
                    }
                });
            }
            syncParentView(cb.getAttribute('data-module'));
            document.querySelectorAll('.menu-access-parent-view').forEach(function (p) {
                syncParentView(p.getAttribute('data-module'));
            });
        });
    });
    document.querySelectorAll('.menu-access-parent-view').forEach(function (p) {
        syncParentView(p.getAttribute('data-module'));
    });
    document.querySelectorAll('.menu-access-module-row').forEach(function (row) {
        var moduleKey = row.getAttribute('data-module');
        var hasChecked = [].some.call(
            document.querySelectorAll('.menu-access-view[data-module="' + moduleKey + '"]'),
            function (cb) { return cb.checked; }
        );
        if (!hasChecked) return;
        var subRow = document.querySelector('.menu-access-submenu-row[data-module="' + moduleKey + '"]');
        var btn = row.querySelector('.menu-access-toggle');
        if (subRow) subRow.style.display = '';
        if (btn) {
            btn.textContent = '−';
            btn.classList.add('is-open');
            btn.setAttribute('aria-expanded', 'true');
        }
    });
})();
</script>
    <?php
}

