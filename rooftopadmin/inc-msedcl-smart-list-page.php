<?php
/**
 * Shared list renderer for MSEDCL SMART PROJECT pages.
 *
 * @param string $listType pmsgy|mahadiscom|payment|survey_pending
 * @param array $config title, import_type, show_mahadiscom_btn, show_payment_btn, page_slug
 */
function msedclSmartRenderListPage($listType, array $config)
{
    global $conn;

    msedclSmartEnsureTables();
    if ($listType === 'survey_pending') {
        msedclSmartSyncAllSurveyDoneStatuses(isset($_SESSION['Admin']['id']) ? (int) $_SESSION['Admin']['id'] : 0);
    }

    $filterDistrict = isset($_REQUEST['District']) ? trim((string) $_REQUEST['District']) : '';
    $filterSearch = isset($_REQUEST['Search']) ? trim((string) $_REQUEST['Search']) : '';
    $isSearch = isset($_REQUEST['Search']) || isset($_REQUEST['submit']);

    $filters = [];
    if ($filterDistrict !== '') {
        $filters['District'] = $filterDistrict;
    }
    if ($filterSearch !== '') {
        $filters['Search'] = $filterSearch;
    }

    $rows = getList(msedclSmartBuildListSql($listType, $filters));
    if (!is_array($rows)) {
        $rows = [];
    }

    $title = isset($config['title']) ? $config['title'] : 'MSEDCL SMART';
    $pageSlug = isset($config['page_slug']) ? $config['page_slug'] : '';
    $importType = isset($config['import_type']) ? $config['import_type'] : '';
    $sampleFile = isset($config['sample_file']) ? trim((string) $config['sample_file']) : '';
    $capacityReferenceFile = isset($config['capacity_reference_file']) ? trim((string) $config['capacity_reference_file']) : '';
    $showMahadiscomBtn = !empty($config['show_mahadiscom_btn']);
    $showPaymentBtn = !empty($config['show_payment_btn']);
    $showForwardBtn = !empty($config['show_forward_btn']);
    $showDeleteBtn = !empty($config['show_delete_btn']);
    $showSurveyColumns = !empty($config['show_survey_columns']);
    $showConsumerNoCol = !empty($config['show_consumer_no_col']);
    $excelColumnsHint = isset($config['excel_columns_hint']) ? trim((string) $config['excel_columns_hint']) : '';
    $showActionCol = ($showMahadiscomBtn || $showPaymentBtn || $showDeleteBtn);
    $enableDatatableExport = !empty($config['datatable_export']);

    $userSurveyMap = [];
    if ($showSurveyColumns && !empty($rows)) {
        $userSurveyMap = msedclSmartLoadUserSurveyMap($rows);
    }
    ?>
<div class="container-fluid flex-grow-1 container-p-y">
    <?php if ($title !== '') { ?>
    <h4 class="font-weight-bold py-3 mb-0"><?php echo htmlspecialchars($title); ?>
        <?php if ($importType !== '') { ?>
        <span class="msedcl-smart-import-wrap" style="float:right;">
            <?php if ($sampleFile !== '') { ?>
            <a href="<?php echo htmlspecialchars($sampleFile); ?>" class="btn btn-outline-secondary btn-sm mr-1" download>
                <i class="ion ion-md-download mr-1"></i> Download Sample Excel
            </a>
            <?php } ?>
            <?php if ($capacityReferenceFile !== '') { ?>
            <a href="<?php echo htmlspecialchars($capacityReferenceFile); ?>" class="btn btn-outline-info btn-sm mr-1" download>
                <i class="ion ion-md-list mr-1"></i> Capacity Master IDs
            </a>
            <?php } ?>
            <button type="button" class="btn btn-success btn-sm msedcl-smart-import-btn"
                data-ajax-url="ajax-import-msedcl-smart-excel.php"
                data-import-type="<?php echo htmlspecialchars($importType); ?>"
                data-redirect="<?php echo htmlspecialchars($pageSlug); ?>">
                <i class="ion ion-md-cloud-upload mr-1"></i> Import Excel
            </button>
            <input type="file" class="d-none msedcl-smart-import-file" accept=".xlsx,.xls,.csv">
        </span>
        <?php } ?>
    </h4>
    <?php } ?>

    <div class="card" style="padding:10px;">
        <form method="get" action="">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label class="form-label">District</label>
                    <select class="form-control" name="District">
                        <option value="">All District</option>
                        <?php
                        msedclSmartEnsureTables();
                        $districtRows = getList("SELECT DISTINCT District FROM tbl_rooftop_msedcl_smart_customers WHERE District!='' AND Status=1 ORDER BY District ASC");
                        foreach ($districtRows as $dr) {
                            $d = $dr['District'];
                            ?>
                        <option value="<?php echo htmlspecialchars($d); ?>" <?php if ($filterDistrict === $d) { ?>selected<?php } ?>><?php echo htmlspecialchars($d); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="Search" class="form-control" value="<?php echo htmlspecialchars($filterSearch); ?>" placeholder="Beneficiary ID / Name / Mobile">
                </div>
                <div class="form-group col-md-2" style="padding-top:28px;">
                    <button type="submit" name="submit" class="btn btn-primary">Search</button>
                </div>
                <?php if ($isSearch) { ?>
                <div class="form-group col-md-2" style="padding-top:28px;">
                    <a href="<?php echo htmlspecialchars($pageSlug); ?>" class="btn btn-info">Clear</a>
                </div>
                <?php } ?>
            </div>
        </form>

        <?php if ($showForwardBtn) { ?>
        <div class="d-flex flex-wrap align-items-center mb-2 msedcl-forward-toolbar">
            <button type="button" id="msedclForwardBtn" class="btn btn-primary btn-sm mr-2"
                data-redirect-url="<?php echo htmlspecialchars(msedclSmartCoordinatorAssignUrl()); ?>">
                <i class="feather icon-arrow-right mr-1"></i> Forward to Co-ordinator Assign
            </button>
            <span class="small text-muted">Select customer(s) below, then click Forward to send them to Assign Customers To Co-ordinator. Forwarded customers are removed from this list.</span>
        </div>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="msedclSmartTable"
                data-dt-export="<?php echo $enableDatatableExport ? '1' : '0'; ?>"
                data-dt-exclude-first="<?php echo $showForwardBtn ? '1' : '0'; ?>"
                data-dt-exclude-last="<?php echo $showActionCol ? '1' : '0'; ?>">
                <thead>
                    <tr>
                        <?php if ($showForwardBtn) { ?>
                        <th style="width:42px;">
                            <label class="custom-control custom-checkbox mb-0">
                                <input type="checkbox" class="custom-control-input" id="msedclForwardSelectAll" title="Select all">
                                <span class="custom-control-label">&nbsp;</span>
                            </label>
                        </th>
                        <?php } ?>
                        <th>Sr</th>
                        <th>Beneficiary ID</th>
                        <th>Customer Name</th>
                        <th>Mobile</th>
                        <?php if ($showConsumerNoCol) { ?>
                        <th>Consumer No</th>
                        <?php } ?>
                        <th>District</th>
                        <th>Taluka</th>
                        <th>Village</th>
                        <th>Rooftop Capacity</th>
                        <?php if ($showSurveyColumns) { ?>
                        <th>Telephonic Survey Details</th>
                        <th>Field Survey Details</th>
                        <?php } else { ?>
                        <th>Stage</th>
                        <?php } ?>
                        <?php if ($showActionCol) { ?><th>Action</th><?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($rows)) {
                        $colspan = 9;
                        if ($showConsumerNoCol) {
                            $colspan++;
                        }
                        if ($showSurveyColumns) {
                            $colspan++;
                        }
                        if ($showActionCol) {
                            $colspan++;
                        }
                        if ($showForwardBtn) {
                            $colspan++;
                        }
                        echo '<tr><td colspan="' . $colspan . '" class="text-muted text-center">No records found.</td></tr>';
                    } else {
                        $i = 1;
                        foreach ($rows as $row) {
                            $capName = msedclSmartRooftopCapacityMasterName($row['PumpCapacity'] ?? '');
                            $capDisp = $capName !== '' ? $capName : (trim((string) ($row['PumpCapacity'] ?? '')) !== '' ? (string) $row['PumpCapacity'] : '—');
                            $canForward = $showForwardBtn && msedclSmartCanForwardToCoordinator($row);
                            $forwardLabel = $showForwardBtn ? msedclSmartForwardStatusLabel($row) : '';
                            $canDelete = $showDeleteBtn && msedclSmartCanDeleteCustomer($row);
                            $custUserId = (int) ($row['CustUserId'] ?? 0);
                            $userSurvey = ($custUserId > 0 && isset($userSurveyMap[$custUserId])) ? $userSurveyMap[$custUserId] : null;
                            ?>
                    <tr>
                        <?php if ($showForwardBtn) { ?>
                        <td>
                            <?php if ($canForward) { ?>
                            <label class="custom-control custom-checkbox mb-0">
                                <input type="checkbox" class="custom-control-input msedcl-forward-check" value="<?php echo (int) $row['id']; ?>">
                                <span class="custom-control-label">&nbsp;</span>
                            </label>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars((string) $row['BeneficiaryId']); ?></td>
                        <td><?php echo htmlspecialchars((string) $row['CustName']); ?></td>
                        <td><?php echo htmlspecialchars((string) $row['CellNo']); ?></td>
                        <?php if ($showConsumerNoCol) {
                            $consumerNoDisp = trim((string) ($row['ConsumerNo'] ?? ''));
                            ?>
                        <td><?php echo htmlspecialchars($consumerNoDisp !== '' ? $consumerNoDisp : '—'); ?></td>
                        <?php } ?>
                        <td><?php echo htmlspecialchars((string) $row['District']); ?></td>
                        <td><?php echo htmlspecialchars((string) $row['Taluka']); ?></td>
                        <td><?php echo htmlspecialchars((string) $row['Village']); ?></td>
                        <td><?php echo htmlspecialchars($capDisp); ?></td>
                        <?php if ($showSurveyColumns) { ?>
                        <td><?php echo msedclSmartTelephonicSurveyHtml($custUserId, $userSurvey['SurveyDetails'] ?? 0); ?></td>
                        <td><?php echo msedclSmartFieldSurveyHtml($custUserId, $userSurvey['FieldSurveyDetails'] ?? 0); ?></td>
                        <?php } else { ?>
                        <td><span class="badge <?php echo htmlspecialchars(msedclSmartListStatusBadgeClass($listType, $row)); ?>"><?php echo htmlspecialchars(msedclSmartListStatusLabel($listType, $row)); ?></span>
                            <?php if ($forwardLabel !== '') { ?>
                            <div class="small text-muted mt-1"><?php echo htmlspecialchars($forwardLabel); ?></div>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <?php if ($showActionCol) { ?>
                        <td class="text-nowrap">
                            <?php if ($showMahadiscomBtn && (int) $row['MahadiscomApplied'] === 0) { ?>
                            <button type="button" class="btn btn-sm btn-primary msedcl-smart-action-btn"
                                data-customer-id="<?php echo (int) $row['id']; ?>"
                                data-action="mahadiscom"
                                data-confirm="Mark this customer as applied on Mahadiscom portal?">Mark Mahadiscom</button>
                            <?php } elseif ($showMahadiscomBtn && (int) $row['MahadiscomApplied'] === 1) { ?>
                            <span class="text-success small d-block">Done as Mahadiscom<?php echo !empty($row['MahadiscomAppliedDate']) ? ' ' . date('d/m/Y', strtotime($row['MahadiscomAppliedDate'])) : ''; ?></span>
                            <?php } ?>
                            <?php if ($showPaymentBtn && (int) $row['MahadiscomApplied'] === 1 && (int) $row['PaymentDone'] === 0) { ?>
                            <button type="button" class="btn btn-sm btn-success msedcl-smart-action-btn"
                                data-customer-id="<?php echo (int) $row['id']; ?>"
                                data-action="payment"
                                data-confirm="Mark payment as done? Customer will move to Survey Pending.">Payment Yes</button>
                            <?php } elseif ($showPaymentBtn && (int) $row['PaymentDone'] === 1) { ?>
                            <span class="text-success small d-block">Payment Done<?php echo !empty($row['PaymentDoneDate']) ? ' ' . date('d/m/Y', strtotime($row['PaymentDoneDate'])) : ''; ?></span>
                            <?php } ?>
                            <?php if ($canDelete) {
                                $deleteConfirm = 'Delete this customer record? This cannot be undone.';
                                if ($listType === 'mahadiscom') {
                                    $deleteConfirm = 'Delete this customer from Mahadiscom portal? They will move back to PMSGY portal.';
                                } elseif ($listType === 'payment') {
                                    $deleteConfirm = 'Delete this customer from payment done list? They will move back to Mahadiscom portal.';
                                }
                                ?>
                            <button type="button" class="btn btn-sm btn-danger msedcl-smart-delete-btn<?php echo ($showMahadiscomBtn || $showPaymentBtn) ? ' ml-1 mt-1' : ''; ?>"
                                data-customer-id="<?php echo (int) $row['id']; ?>"
                                data-list-type="<?php echo htmlspecialchars($listType); ?>"
                                data-label="<?php echo htmlspecialchars((string) $row['BeneficiaryId']); ?>"
                                data-confirm="<?php echo htmlspecialchars($deleteConfirm); ?>">Delete</button>
                            <?php } elseif ($showDeleteBtn && msedclSmartIsForwardedToCoordinator($row)) { ?>
                            <span class="text-muted small d-block">Forwarded to Co-ordinator</span>
                            <?php } ?>
                        </td>
                        <?php } ?>
                    </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <p class="small text-muted mb-0 mt-2">
            <?php if ($excelColumnsHint !== '') { ?>
            Excel columns: <?php echo htmlspecialchars($excelColumnsHint); ?>
            <?php } else { ?>
            Excel columns: Beneficiary ID, Customer Name, Taluka, Village, Block, District, Mobile, Rooftop Capacity (Master ID from Rooftop Plant Capacity), Consumer No.
            <?php } ?>
            <?php if ($capacityReferenceFile !== '') { ?> Use <strong>Capacity Master IDs</strong> download for valid IDs.<?php } ?>
            <?php if ($showDeleteBtn) { ?> Delete is allowed only until the customer is forwarded to Co-ordinator assign.<?php } ?>
        </p>
    </div>
</div>
    <?php
}
