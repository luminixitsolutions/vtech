<?php

/**
 * Granular sub-menu option ids (188+). Legacy parent ids still grant full group access.
 *
 * @return array<int, array{label:string, legacy:int[]}>
 */
function menuAccessGranularDefinitions()
{
    return [
        188 => ['label' => 'Lead Irrigation', 'legacy' => [44]],
        189 => ['label' => 'Pump App — Upload Excel', 'legacy' => [149]],
        190 => ['label' => 'Pump App — All Application Form', 'legacy' => [149]],
        191 => ['label' => 'Pump App — Pending Application Form', 'legacy' => [149]],
        192 => ['label' => 'Pump App — Approve Application Form', 'legacy' => [149]],
        193 => ['label' => 'Pump App — Reject Application Form', 'legacy' => [149]],
        194 => ['label' => 'Pump App — Assign Application Form', 'legacy' => [150]],
        195 => ['label' => 'Pump App — Assigned Application Form', 'legacy' => [150]],
        196 => ['label' => 'Master — Country', 'legacy' => [1]],
        197 => ['label' => 'Master — State', 'legacy' => [1]],
        198 => ['label' => 'Master — City', 'legacy' => [1]],
        199 => ['label' => 'Add Product', 'legacy' => [24]],
        200 => ['label' => 'View Product', 'legacy' => [24]],
        201 => ['label' => 'Add Pump Customer', 'legacy' => [18]],
        202 => ['label' => 'Pump Customers', 'legacy' => [18]],
        203 => ['label' => 'Add Manufacture', 'legacy' => [19]],
        204 => ['label' => 'View Manufactures', 'legacy' => [19]],
        205 => ['label' => 'Add Company', 'legacy' => [20]],
        206 => ['label' => 'View Company', 'legacy' => [20]],
        207 => ['label' => 'Add Employee', 'legacy' => [21]],
        208 => ['label' => 'View Employee', 'legacy' => [21]],
        209 => ['label' => 'Create Store Incharge', 'legacy' => [125]],
        210 => ['label' => 'View Store Incharge', 'legacy' => [125]],
        211 => ['label' => 'Add Dispatch Officer', 'legacy' => [126]],
        212 => ['label' => 'View Dispatch Officer', 'legacy' => [126]],
        213 => ['label' => 'Add Contractor', 'legacy' => [127]],
        214 => ['label' => 'View Contractor', 'legacy' => [127]],
        215 => ['label' => 'Set Contractor Commission', 'legacy' => [127]],
        216 => ['label' => 'Add Installer', 'legacy' => [128]],
        217 => ['label' => 'View Installer', 'legacy' => [128]],
        218 => ['label' => 'Add Driver Account', 'legacy' => [116]],
        219 => ['label' => 'View Driver Account', 'legacy' => [116]],
        220 => ['label' => 'Add Dealer', 'legacy' => [22]],
        221 => ['label' => 'View Dealer', 'legacy' => [22]],
        222 => ['label' => 'Add Agency', 'legacy' => [23]],
        223 => ['label' => 'View Agency', 'legacy' => [23]],
        224 => ['label' => 'Done beneficiary — required stock', 'legacy' => [134]],
        225 => ['label' => 'Upload PDI Excel', 'legacy' => [145]],
        226 => ['label' => 'View Uploaded PDI', 'legacy' => [145]],
        227 => ['label' => 'Match PDI', 'legacy' => [145]],
        228 => ['label' => 'Add Purchase Order', 'legacy' => [25]],
        229 => ['label' => 'View Purchase Order', 'legacy' => [25]],
        230 => ['label' => 'Delete Bill No Stock', 'legacy' => [25]],
        231 => ['label' => 'Upload DCR Excel', 'legacy' => [146]],
        232 => ['label' => 'View Uploaded DCR', 'legacy' => [146]],
        233 => ['label' => 'Assign Items To Store', 'legacy' => [70]],
        234 => ['label' => 'View Assign Items To Store', 'legacy' => [70]],
        235 => ['label' => 'Assign Items To Dispatch Officer', 'legacy' => [71]],
        236 => ['label' => 'View Assign Items To Dispatch Officer', 'legacy' => [71]],
        237 => ['label' => 'Transfer to Store', 'legacy' => [165]],
        238 => ['label' => 'View Dispatch to Store Transfers', 'legacy' => [165]],
        239 => ['label' => 'Stock Location Report (Dispatch Transfer)', 'legacy' => [165]],
        240 => ['label' => 'Store to Store Transfer', 'legacy' => [166]],
        241 => ['label' => 'View Store to Store Transfers', 'legacy' => [166]],
        242 => ['label' => 'Stock Location Report (Store Transfer)', 'legacy' => [166]],
        243 => ['label' => 'Add Sell', 'legacy' => [26]],
        244 => ['label' => 'View Sells', 'legacy' => [26]],
        245 => ['label' => 'Add Service Complaint', 'legacy' => [28]],
        246 => ['label' => 'View Service Complaint', 'legacy' => [28]],
        247 => ['label' => 'Service Abstract', 'legacy' => [28]],
        248 => ['label' => 'Employee Tracking Report', 'legacy' => [187]],
        249 => ['label' => 'Task — Create Task', 'legacy' => [47]],
        250 => ['label' => 'Task — View Tasks', 'legacy' => [47]],
        251 => ['label' => 'Task — To Do Tasks', 'legacy' => [47]],
        252 => ['label' => 'Task — Project Head / Department', 'legacy' => [47]],
        253 => ['label' => 'Task — Dashboard', 'legacy' => [47, 151]],
        256 => ['label' => 'Add Transportor Account', 'legacy' => [255]],
        257 => ['label' => 'View Transportor Account', 'legacy' => [255]],
    ];
}

/** Primary granular (or legacy) option id per page basename. */
function menuAccessGranularPagePrimaryIds()
{
    return [
        'add-irrigation-leads.php' => 188,
        'view-irrigation-leads.php' => 188,
        'add-lead.php' => 44,
        'upload-application-excel.php' => 189,
        'view-application-form.php' => 190,
        'pending-application-form.php' => 191,
        'approve-application-form.php' => 192,
        'reject-application-form.php' => 193,
        'add-application-form.php' => 189,
        'assign-applications.php' => 194,
        'assigned-applications.php' => 195,
        'country.php' => 196,
        'state.php' => 197,
        'city.php' => 198,
        'add-product.php' => 199,
        'view-products.php' => 200,
        'add-customer.php' => 201,
        'pump-customers.php' => 202,
        'add-manufacture.php' => 203,
        'view-manufacture.php' => 204,
        'add-company.php' => 205,
        'view-company.php' => 206,
        'add-employee.php' => 207,
        'view-employee.php' => 208,
        'add-store-incharge.php' => 209,
        'view-store-incharge.php' => 210,
        'add-dispatch-officer.php' => 211,
        'view-dispatch-officer.php' => 212,
        'add-installer.php' => 213,
        'view-installer.php' => 214,
        'view-contractor-commision.php' => 215,
        'add-installer-employee.php' => 216,
        'view-installer-employee.php' => 217,
        'add-driver.php' => 218,
        'view-drivers.php' => 219,
        'add-transportor.php' => 256,
        'view-transportor.php' => 257,
        'add-dealer.php' => 220,
        'view-dealer.php' => 221,
        'add-agency.php' => 222,
        'view-agency.php' => 223,
        'under-production-beneficiary-stock-report.php' => 224,
        'upload-pdi-excel.php' => 225,
        'view-uploaded-pdi.php' => 226,
        'match-pdi-verification.php' => 227,
        'add-purchase-order.php' => 228,
        'view-purchase-order.php' => 229,
        'delete-bill-no-stock.php' => 230,
        'upload-dcr-excel.php' => 231,
        'view-uploaded-dcr.php' => 232,
        'distribute-item-store-2.php' => 233,
        'view-distribute-item-store.php' => 234,
        'distribute-item-store-executive-2.php' => 235,
        'view-distribute-item-store-executive.php' => 236,
        'add-sell.php' => 243,
        'view-sells.php' => 244,
        'choose-service-type2.php' => 245,
        'view-service-module.php' => 246,
        'service-abstract.php' => 247,
        'employee-tracking-report.php' => 248,
        'create-task.php' => 249,
        'view-tasks.php' => 250,
        'to-do-tasks.php' => 251,
        'department.php' => 252,
        'task-dashboard.php' => 253,
    ];
}

/** @return int[] */
function menuAccessScreenOptionIds($screenId)
{
    $screenId = (int) $screenId;
    $ids = [$screenId];
    $defs = menuAccessGranularDefinitions();
    if (isset($defs[$screenId]['legacy'])) {
        foreach ($defs[$screenId]['legacy'] as $legacyId) {
            $ids[] = (int) $legacyId;
        }
    }
    foreach ($defs as $granularId => $def) {
        foreach ($def['legacy'] ?? [] as $legacyId) {
            if ((int) $legacyId === $screenId) {
                $ids[] = (int) $granularId;
            }
        }
    }

    return array_values(array_unique(array_map('intval', $ids)));
}

/** @return int[] */
function menuAccessEffectiveRequiredIds(array $optionIds)
{
    $expanded = [];
    foreach ($optionIds as $id) {
        foreach (menuAccessScreenOptionIds($id) as $sid) {
            $expanded[$sid] = $sid;
        }
    }

    return array_values($expanded);
}

function menuAccessUserHasScreen(array $options, $screenId)
{
    return userHasAnyMenuOption($options, menuAccessScreenOptionIds($screenId));
}

function menuAccessUserHasFamily(array $options, $legacyParentId)
{
    return menuAccessUserHasScreen($options, $legacyParentId);
}

/** @return int[] */
function menuAccessRequiredIdsForPage($basename, array $fallback = [])
{
    $map = menuAccessGranularPagePrimaryIds();
    if (isset($map[$basename])) {
        return menuAccessScreenOptionIds($map[$basename]);
    }

    return menuAccessEffectiveRequiredIds($fallback);
}

/** @return int[] */
function menuAccessAllGranularOptionIds()
{
    return array_map('intval', array_keys(menuAccessGranularDefinitions()));
}

function menuAccessSyncGranularOptionsToDb()
{
    global $conn;
    foreach (menuAccessGranularDefinitions() as $id => $def) {
        $id = (int) $id;
        $nameEsc = $conn->real_escape_string($def['label']);
        $conn->query("INSERT INTO tbl_options (id, Name) VALUES ($id, '$nameEsc')
            ON DUPLICATE KEY UPDATE Name = '$nameEsc'");
    }
}
