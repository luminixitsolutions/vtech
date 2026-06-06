<?php

/**
 * Enforce tbl_options menu access on admin pages (included from auth.php).
 */
require_once __DIR__ . '/inc-menu-option-groups.php';

/** Rolls with full access (no option check). */
function adminPageAccessBypassRolls()
{
    return function_exists('adminMenuAccessBypassRolls')
        ? adminMenuAccessBypassRolls()
        : [1, 7];
}

function adminPageAccessSkipRelativePaths()
{
    return [
        'ajax_files/',
        'pagination/',
        'migrations/',
        'ckeditor/',
        'whatsapp_sms/',
        'vendor/',
    ];
}

function adminPageAccessSkipBasenames()
{
    return [
        'index.php', 'logout.php', 'login.php', 'auth.php', 'config.php', 'db-local.php',
        'header.php', 'header1.0.php', 'footer.php', 'top_header.php', 'footer_script.php', 'header_script.php',
        'inc-page-access.php', 'inc-menu-option-groups.php', 'inc-admin-dashboard-data.php',
        'sidebar.php',
        'database.php', 'db.php', 'dbcontroller.php', 'exe-database.php', 'db-local.php',
        'verify-login-otp.php', 'cron-installation-escalation.php',
        'employee-tracking-export.php', 'employee-tracking-log-detail.php',
        'inc-insurance-site.php', 'inc-insurance-dashboard-data.php', 'inc-mpuvnl-project-dashboard.php',
        'inc-distribute-item-store-executive-2-serials.php', 'inc-serial-location-report.php',
        'inc-dispatch-officer-stock.php', 'inc-store-dist-dispatch-status.php', 'incsmsapi.php',
    ];
}

/** Logged-in users may open these without a menu option. */
function adminPageAccessAlwaysAllowedBasenames()
{
    return [
        'dashboard.php', 'emp-dashboard.php', 'send-sms.php', 'leave-requests.php', 'change-password.php', 'admin-search.php', 'ajax-admin-menu-search.php',
        'expense-request.php', 'backup.php', 'cancel-pending-login.php',
        'dispatch-dashboard.php', 'dispatch-dashboard2.php',
    ];
}

function adminPageAccessMasterPageIdOptions()
{
    return [
        1 => 6, 2 => 7, 3 => 8, 4 => 9, 5 => 15, 6 => 16, 7 => 12, 8 => 13, 9 => 34,
        10 => 53, 11 => 54, 12 => 72, 13 => 73, 14 => 74, 15 => 75, 16 => 76, 17 => 77,
        18 => 89, 19 => 90, 20 => 91, 21 => 92, 22 => 97, 23 => 98, 24 => 140, 25 => 117,
    ];
}

function adminPageAccessBasenameRules()
{
    return [
        'report-dashboard.php' => null,
        'lead-management-dashboard.php' => menuAccessLeadDashboardOptionIds(),
        'upload-excel.php' => [64],
        'upload-application-excel.php' => [149],
        'masters-dashboard.php' => null,
        'product-managment-dashboard.php' => [24],
        'account-managment-dashboard.php' => null,
        'common-master.php' => null,
        'country.php' => [1], 'state.php' => [1], 'city.php' => [1],
        'serial-location-report.php' => [183, 101, 103],
        'pending-insurance.php' => [169], 'completed-insurance.php' => [170],
        'renewal-insurance.php' => [171], 'expired-insurance.php' => [172],
        'renewed-insurance.php' => [173], 'insurance-dashboard.php' => [168, 121],
        'assign-customers-to-co-ordinator.php' => [55],
        'assign-customers-to-field-survey.php' => [79],
        'bos-tentative-production-plan.php' => [130],
        'stucture-tentative-production-plan.php' => [131],
        'co-ordinator-survey.php' => [80], 'field-survey.php' => [81],
        'bos-final-production-plan.php' => [132],
        'stucture-final-production-plan.php' => [133],
        'under-production-beneficiary.php' => [134],
        'under-production-beneficiary-stock-report.php' => [134],
        'upload-pdi-excel.php' => [145], 'view-uploaded-pdi.php' => [145],
        'match-pdi-verification.php' => [145],
        'add-purchase-order.php' => [25], 'view-purchase-order.php' => [25],
        'delete-bill-no-stock.php' => [25],
        'upload-dcr-excel.php' => [146], 'view-uploaded-dcr.php' => [146],
        'assign-to-store-incharge.php' => [58], 'approve-store-incharge.php' => [59],
        'distribute-item-store-2.php' => [70], 'view-distribute-item-store.php' => [70],
        'assign-to-dispatch-officer.php' => [60],
        'distribute-item-store-executive-2.php' => [71],
        'view-distribute-item-store-executive.php' => [71],
        'add-sell.php' => [26], 'view-sells.php' => [26],
        'assign-challan-to-dispatcher.php' => [82],
        'assign-site-to-installation.php' => [83],
        'installation-project-dashboard.php' => [68],
        'assign-site-to-inspection.php' => [84],
        'service-dashboard.php' => [164],
        'beneficiary-service-lists.php' => [137],
        'allocate-complaints-to-engineer.php' => [135],
        'allocate-not-solved-complaints-to-engineer.php' => [136],
        'choose-service-type2.php' => [28], 'view-service-module.php' => [28],
        'service-abstract.php' => [28],
        'update-dispatch-calling-status.php' => [93],
        'before-installation.php' => [94], 'after-installation.php' => [95],
        'before-inspection.php' => [96], 'beneficiary-selection.php' => [118],
        'running-trips.php' => [138], 'completed-trips.php' => [139],
        'file-submission-reminder.php' => [254],
        'approve-attendance.php' => [144],
        'admin-installation-dashboard.php' => [174],
        'pending-installations.php' => [175],
        'coordinator-assigned-sites.php' => [176],
        'manager-pending-installations.php' => [177],
        'gm-pending-installations.php' => [178],
        'gm-extension-requests.php' => [179],
        'business-head-pending.php' => [180],
        'bh-extension-requests.php' => [181],
        'dispute-sites.php' => [182],
        'warranty-dashboard.php' => [61], 'view-warranty-registration.php' => [61],
        'warranty-customers.php' => [61], 'no-warranty-customers.php' => [61],
        'warranty-registration.php' => [61],
        'sell-report.php' => [29], 'trip-report.php' => [115],
        'stock-report2.php' => [30], 'stock-report.php' => [31],
        'all-customer-report.php' => [38], 'daily-record-report.php' => [39],
        'attendance-report.php' => [99], 'attendance-report-2.php' => [185, 99],
        'vehical-report.php' => [100], 'dealer-report.php' => [65],
        'store-stock-report.php' => [101], 'store-stock-report-2.php' => [184, 101],
        'store-item-report.php' => [102],
        'dispatch-officer-stock-report.php' => [103],
        'field-survey-report.php' => [104], 'dispatch-report.php' => [105],
        'installation-report.php' => [106], 'inspection-report.php' => [107],
        'site-engineer-report.php' => [108], 'dispatch-calling-report.php' => [109],
        'before-installation-calling-report.php' => [110],
        'after-installation-calling-report.php' => [111],
        'before-inspection-calling-report.php' => [112],
        'beneficiary-selection-calling-report.php' => [119],
        'delay-calculation-report.php' => [143],
        'delay-calculation-report-2.php' => [186, 143],
        'contractor-commision-report.php' => [142],
        'dispatch-customer-csv-report.php' => [160],
        'dispatch-material-report.php' => [120],
        'dispatch-to-store-transfer.php' => [165],
        'view-dispatch-to-store-transfers.php' => [165],
        'store-to-store-transfer.php' => [166],
        'view-store-to-store-transfers.php' => [166],
        'stock-location-report.php' => [165, 166, 183, 101, 103],
        'revert-dispatch-to-store-transfer.php' => [165],
        'add-employee.php' => [21], 'view-employee.php' => [21],
        'add-store-incharge.php' => [125], 'add-dispatch-officer.php' => [126],
        'add-dealer.php' => [22], 'add-agency.php' => [23],
        'add-customer.php' => [18], 'pump-customers.php' => [18],
        'add-company.php' => [20], 'add-manufacture.php' => [19],
        'add-driver.php' => [116], 'add-installer.php' => [127],
        'add-maintaince-engineer.php' => [129],
        'upload-customer-excel.php' => [122],
        'product-specification.php' => [17],
        'structure-product-specification.php' => [78],
        'add-product.php' => [24], 'view-products.php' => [24],
        'customer-profile.php' => [18, 113], 'customer-profile2.php' => [18, 113],
        'customer-profile (1).php' => [18, 113],
        'pending-customers.php' => [149, 150, 151, 152, 153],
        'completed-customers.php' => [149, 150, 151, 152, 153],
        'delivery-customers.php' => [149, 150, 151, 152, 153],
        'view-assigning-items.php' => [58, 59, 60, 70, 71],
        'view-assigning-items-to-store.php' => [58, 59, 60, 70, 71],
        'view-assigning-items-to-dispatch-officer.php' => [60, 71],
        'view-assigning-store-items.php' => [58, 70],
        'view-bill-amount-status.php' => [25],
        'view-quotation.php' => [117, 140], 'view-quotation-products.php' => [117, 140],
        'add-quotation.php' => [117, 140], 'add-quotation-product.php' => [117, 140],
        'add-work-order.php' => [25], 'view-work-order.php' => [25],
        'receive-amount.php' => [26], 'add-receive-amount.php' => [26],
        'coordinator-action.php' => [176, 55, 80],
        'serial-no-list.php' => [145], 'pdi-serial-no-list.php' => [145],
        'project-abstract.php' => [106], 'project-abstract-of-abstract.php' => [106],
        'print-project-abstract.php' => [106], 'print-project-abstract-old.php' => [106],
        'print-project-abstract-of-abstract.php' => [106],
        'dealer-show-balance-amount.php' => [22, 69],
        'dealer-commission.php' => [69, 142],
        'edit-commission-amount.php' => [142],
        'add-calculation.php' => [143],
        'total-beneficiary.php' => [134],
        'track-location.php' => [138, 139],
        'task-dashboard.php' => menuAccessTaskOptionIds(),
        'export-service-abstract.php' => [28],
        'employee-tracking-dashboard.php' => [187],
        'employee-tracking-report.php' => [187],
        'employee-tracking-export.php' => [187],
        'employee-tracking-log-detail.php' => [187],
        'field-service-report.php' => [28, 164],
        'installation-history.php' => [68, 106],
        'match-pdi.php' => [145],
        'dispatch-header.php' => [60], 'dispatch-store-header.php' => [70],
        'view-diesel.php' => [100],
        'view-total-selections.php' => [],
    ];
}

function adminPageAccessFolderRules()
{
    $g = getMenuOptionGroups();
    return [
        'dealer_lead_management/' => $g['Dealer Lead Management'],
        'master_management/' => $g['Master Management'],
        'product_management/' => $g['Product Management'],
        'report_management/' => $g['Reports'],
        'item_transfer_workflow/' => [165, 166],
        'mpuvnl_management/' => menuAccessMpuvnlOptionIds(),
    ];
}

function adminPageAccessDashboardBasenameGroups()
{
    $g = getMenuOptionGroups();
    return [
        'report-dashboard.php' => $g['Reports'],
        'lead-management-dashboard.php' => menuAccessLeadDashboardOptionIds(),
        'masters-dashboard.php' => $g['Master Management'],
        'account-managment-dashboard.php' => $g['User Accounts'],
    ];
}

function adminPageAccessRelativePath()
{
    $adminRoot = realpath(__DIR__);
    if (!$adminRoot) {
        return '';
    }
    $adminRootNorm = strtolower(str_replace('\\', '/', $adminRoot));

    $script = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
    if ($script) {
        $scriptReal = realpath($script);
        if ($scriptReal) {
            $scriptNorm = strtolower(str_replace('\\', '/', $scriptReal));
            if (strpos($scriptNorm, $adminRootNorm) === 0) {
                return substr(str_replace('\\', '/', $scriptReal), strlen(str_replace('\\', '/', $adminRoot)) + 1);
            }
        }
    }

    if (!empty($_SERVER['SCRIPT_NAME'])) {
        $sn = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        if (preg_match('#/admin/(.+)$#i', $sn, $m)) {
            return $m[1];
        }
    }

    return str_replace('\\', '/', basename($script));
}

function adminPageAccessShouldSkip($relativePath, $basename)
{
    if (strpos($relativePath, 'ajax_files/') === 0) {
        return true;
    }
    if (strpos($basename, 'inc-') === 0) {
        return true;
    }
    if (preg_match('/-sidebar\.php$/i', $basename)) {
        return true;
    }
    if (preg_match('/-session\.php$/i', $basename) || $basename === 'purchase-order-session.php') {
        return true;
    }
    if (in_array($basename, ['invoice.php', 'view_cart.php', 'print-delivery-challan.php'], true)) {
        return true;
    }
    if (in_array($basename, adminPageAccessSkipBasenames(), true)) {
        return true;
    }
    foreach (adminPageAccessSkipRelativePaths() as $prefix) {
        if (strpos($relativePath, $prefix) === 0) {
            return true;
        }
    }
    if (preg_match('/\.(css|js|map)$/i', $basename)) {
        return true;
    }
    return false;
}

function adminPageAccessResolveRequiredOptions($relativePath, $basename)
{
    if ($basename === 'common-master.php') {
        if (isset($_GET['pageid'])) {
            $pid = (int) $_GET['pageid'];
            $map = adminPageAccessMasterPageIdOptions();
            if (isset($map[$pid])) {
                return [$map[$pid]];
            }
        }
        return getMenuOptionGroups()['Master Management'];
    }

    $dashGroups = adminPageAccessDashboardBasenameGroups();
    if (isset($dashGroups[$basename])) {
        return $dashGroups[$basename];
    }

    $rules = adminPageAccessBasenameRules();
    if (isset($rules[$basename])) {
        return $rules[$basename];
    }

    if (strpos($relativePath, 'report_management/') === 0 && $basename !== 'report-dashboard.php') {
        if (strpos($basename, 'store-stock-report-2') !== false) {
            return [184, 101];
        }
        if (strpos($basename, 'store-stock-report') !== false || strpos($basename, 'store-qty') !== false) {
            return [101];
        }
        if (strpos($basename, 'serial-location') !== false) {
            return [183, 101, 103];
        }
        if (strpos($basename, 'dispatch-officer-stock') !== false) {
            return [103];
        }
        if (strpos($basename, 'delay-calculation-report-2') !== false) {
            return [186, 143];
        }
        if (strpos($basename, 'delay-calculation') !== false) {
            return [143];
        }
        if (strpos($basename, 'attendance-report-2') !== false) {
            return [185, 99];
        }
        if (strpos($basename, 'attendance-report') !== false) {
            return [99];
        }
        return getMenuOptionGroups()['Reports'];
    }

    if (strpos($relativePath, 'lead_management/') === 0) {
        $leadPages = menuAccessLeadManagementPageOptions();
        if (isset($leadPages[$basename])) {
            return $leadPages[$basename];
        }
        if ($basename === 'lead-management-dashboard.php') {
            return menuAccessLeadDashboardOptionIds();
        }
    }

    foreach (adminPageAccessFolderRules() as $prefix => $options) {
        if (strpos($relativePath, $prefix) === 0) {
            return $options;
        }
    }

    if (strpos($relativePath, 'user_management/') === 0) {
        return getMenuOptionGroups()['User Accounts'];
    }

    if (preg_match('/insurance/i', $basename) || preg_match('/insurance/i', $relativePath)) {
        return array_merge(getMenuOptionGroups()['Insurance Site'], [121]);
    }

    if (strpos($relativePath, 'task_management/') === 0 || $basename === 'task-dashboard.php') {
        return menuAccessTaskOptionIds();
    }

    if (preg_match('/rooftop/i', $basename) || preg_match('/rooftop/i', $relativePath)) {
        return [67, 114, 123, 124, 147, 148, 167];
    }

    if (preg_match('/service|complaint|maintenance/i', $basename)) {
        return getMenuOptionGroups()['Service Complaint'];
    }

    if (strpos($basename, 'ajax_') === 0 || strpos($basename, 'ajax-') === 0) {
        if (preg_match('/insurance/i', $basename)) {
            return array_merge(getMenuOptionGroups()['Insurance Site'], [121]);
        }
        if (preg_match('/installation|coordinator-assign/i', $basename)) {
            return getMenuOptionGroups()['Installation Workflow'];
        }
        if (preg_match('/coordinator|distribute|dispatch|assign/i', $basename)) {
            return menuAccessOperationsOptionIds();
        }
        return null;
    }

    if (strpos($relativePath, '/') === false) {
        $ops = menuAccessOperationsOptionIds();
        if (preg_match('/assign-|distribute-|production-plan|survey|purchase|challan|sell|pdi|dcr|dispatch|install|trip|attendance|store-incharge|dispatch-officer|under-production|beneficiary-selection|calling|commission/i', $basename)) {
            return $ops;
        }
        $install = getMenuOptionGroups()['Installation Workflow'];
        if (preg_match('/pending-installation|coordinator-assigned|manager-pending|gm-pending|business-head|extension-request|dispute-site|admin-installation/i', $basename)) {
            return $install;
        }
    }

    if (preg_match('/^save-|^take-/', $basename)) {
        return menuAccessOperationsOptionIds();
    }

    return null;
}

function adminPageAccessDeny($relativePath)
{
    $depth = max(0, substr_count($relativePath, '/'));
    $redirect = str_repeat('../', $depth) . 'dashboard.php';
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'message' => 'Access denied. You do not have permission for this page.']);
        exit;
    }
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo "<script>alert('Access denied. You do not have permission to access this page.');window.location.href=" . json_encode($redirect) . ";</script>";
    exit;
}

/**
 * Landing page after admin login (OTP verified).
 *
 * @param array<string,mixed> $row tbl_users row
 */
function adminPostLoginRedirectForUser(array $row)
{
    $roll = (int) ($row['Roll'] ?? 0);
    if ($roll === 26) {
        return 'dispatch-dashboard.php';
    }
    if (adminUserHasFullMenuAccess($roll)) {
        return 'file-submission-reminder.php';
    }
    $options = array_values(array_filter(explode(',', (string) ($row['Options'] ?? ''))));
    if (userHasAnyMenuOption($options, menuAccessFileSubmissionOptionIds())) {
        return 'file-submission-reminder.php';
    }

    return 'dashboard.php';
}

function enforceAdminPageAccess()
{
    static $alreadyEnforced = false;
    if ($alreadyEnforced) {
        return;
    }
    $alreadyEnforced = true;

    if (empty($_SESSION['Admin']['id'])) {
        return;
    }

    $userId = (int) $_SESSION['Admin']['id'];
    global $conn;
    $row = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$userId'");
    if (empty($row)) {
        return;
    }

    $roll = (int) ($row['Roll'] ?? 0);
    if (in_array($roll, adminPageAccessBypassRolls(), true)) {
        return;
    }

    $options = array_filter(explode(',', (string) ($row['Options'] ?? '')));
    $relativePath = adminPageAccessRelativePath();
    $basename = basename($relativePath);

    if (adminPageAccessShouldSkip($relativePath, $basename)) {
        return;
    }

    if (in_array($basename, adminPageAccessAlwaysAllowedBasenames(), true)) {
        return;
    }

    if (!preg_match('/\.php$/i', $basename)) {
        return;
    }

    $required = adminPageAccessResolveRequiredOptions($relativePath, $basename);

    if ($required === null) {
        adminPageAccessDeny($relativePath);
    }

    if ($required === []) {
        return;
    }

    if (function_exists('menuAccessEffectiveRequiredIds')) {
        $required = menuAccessEffectiveRequiredIds($required);
    }

    if (!userHasAnyMenuOption($options, $required)) {
        adminPageAccessDeny($relativePath);
    }
}
