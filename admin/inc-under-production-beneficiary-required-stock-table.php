<?php
if (!isset($reqQtyLabel)) {
    $reqQtyLabel = 'Required qty';
}
if (!isset($storeColumns) || !is_array($storeColumns)) {
    $storeColumns = [];
}
if (!isset($lines) || !is_array($lines)) {
    $lines = [];
}
$showTotalRow = !empty($isCombined);
$tableTotalReq = 0;
$storeColCount = count($storeColumns);
?>
<div class="card" style="padding: 10px;">
    <h5 class="mb-2">Bulk / regular products</h5>
    <?php if ($storeColCount > 0) { ?>
    <p class="text-muted small mb-2 mb-md-3">
        Store columns are from each customer&apos;s assigned store (Add Pump Customer &rarr; Store).
        <?php if ($storeColCount > 1) { ?>
            <?php echo (int) $storeColCount; ?> different store(s) across selected customer(s).
        <?php } ?>
    </p>
    <?php } else { ?>
    <p class="text-muted small mb-2 mb-md-3">No store is assigned on the selected customer account(s). Assign a store on the customer profile to show store-wise balance.</p>
    <?php } ?>
    <div class="upb-stock-card-inner">
        <table id="tblRequiredStock" class="table table-striped table-bordered table-sm nowrap" style="width:100%" cellspacing="0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th style="min-width:220px">Item</th>
                    <th class="text-right" style="min-width:110px"><?php echo htmlspecialchars($reqQtyLabel); ?></th>
                    <?php foreach ($storeColumns as $storeCol) { ?>
                    <th class="text-right" style="min-width:130px" title="Balance stock at this store">
                        <?php echo htmlspecialchars((string) $storeCol['store_name']); ?>
                        <br><small class="font-weight-normal text-muted">Balance qty</small>
                    </th>
                    <?php } ?>
                    <th class="text-right" style="min-width:140px">Total available (all stores)</th>
                    <th style="min-width:120px">Available by store</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1;
                foreach ($lines as $ln) {
                    $pid = (int) $ln['ProductId'];
                    $req = (int) round((float) $ln['ReqQty']);
                    $tableTotalReq += $req;
                    $name = (string) $ln['ProductName'];
                    $totalAvail = upb_stock_net($conn, $pid, null);
                    $byBranch = upb_available_locations($conn, $pid);
                    $short = ($pid > 0 && $req > $totalAvail);
                    ?>
                    <tr class="<?php echo $short ? 'table-warning' : ''; ?>">
                        <td style="color:#212529;"><?php echo $n++; ?></td>
                        <td style="color:#212529;"><?php echo htmlspecialchars($name); ?><?php if ($pid <= 0) {
                            echo ' <span class="text-muted">(no product id)</span>';
                        } ?></td>
                        <td class="text-right" style="color:#212529;"><?php echo $req; ?></td>
                        <?php foreach ($storeColumns as $storeCol) {
                            $bid = (int) $storeCol['branch_id'];
                            $storeQty = ($pid > 0 && $bid > 0) ? upb_store_balance_qty($conn, $pid, $bid) : 0;
                            $cellShort = ($pid > 0 && $req > 0 && $storeQty < $req);
                            ?>
                        <td class="text-right<?php echo $cellShort ? ' upb-store-short' : ''; ?>" style="color:#212529;">
                            <?php echo $pid > 0 ? (int) $storeQty : '—'; ?>
                        </td>
                        <?php } ?>
                        <td class="text-right" style="color:#212529;"><?php echo $pid > 0 ? (int) $totalAvail : '—'; ?></td>
                        <td>
                            <?php
                            if ($pid <= 0) {
                                echo '<span class="text-muted">Map a product id on the customer BOM / quotation to show store stock.</span>';
                            } elseif (count($byBranch) === 0) {
                                echo '<span class="text-muted">No positive balance in any store (ledger).</span>';
                            } else {
                                $locPayload = [];
                                foreach ($byBranch as $b) {
                                    $bid = isset($b['branch_id']) ? (int) $b['branch_id'] : (isset($b['BranchId']) ? (int) $b['BranchId'] : 0);
                                    $locPayload[] = [
                                        'StoreName' => (string) ($b['StoreName'] ?? ''),
                                        'AvailQty' => isset($b['AvailQty']) ? (float) $b['AvailQty'] : 0,
                                        'BranchId' => $bid,
                                        'row_kind' => (string) ($b['row_kind'] ?? 'ledger'),
                                        'branch_id' => $bid,
                                        'store_exe_id' => (int) ($b['store_exe_id'] ?? 0),
                                    ];
                                }
                                $locJson = htmlspecialchars(json_encode($locPayload, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                $itemTitle = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                                ?>
                                <button type="button" class="btn btn-sm btn-primary btn-view-store-avl"
                                    data-toggle="modal" data-target="#modalAvlByStore"
                                    data-product-id="<?php echo (int) $pid; ?>"
                                    data-item-name="<?php echo $itemTitle; ?>"
                                    data-required="<?php echo (int) $req; ?>"
                                    data-total-avail="<?php echo (int) $totalAvail; ?>"
                                    data-is-serial="0"
                                    data-locations="<?php echo $locJson; ?>">View</button>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <?php if ($showTotalRow) { ?>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-right"><?php echo (int) $tableTotalReq; ?></td>
                    <?php for ($i = 0; $i < $storeColCount; $i++) { ?>
                    <td></td>
                    <?php } ?>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </div>
</div>
