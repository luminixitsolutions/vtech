<?php
if (!isset($serialLines) || !is_array($serialLines)) {
    $serialLines = [];
}
if (!isset($storeColumns) || !is_array($storeColumns)) {
    $storeColumns = [];
}
if (!isset($reqQtyLabel)) {
    $reqQtyLabel = 'Required qty';
}
$storeColCount = count($storeColumns);
$serialTotalReq = 0;
$serialTotalAvail = 0;
?>
<div class="card mt-3" style="padding: 10px;">
    <h5 class="mb-2" style="font-size: 18px; color: #212529;">Serial No Products</h5>
    <p class="text-muted small mb-3">
        All products with <strong>Product Type = Serial No Product</strong> on <em>Add Product</em> (<code>tbl_products.Roll = 1</code>) — shown for every customer.
        <strong><?php echo htmlspecialchars($reqQtyLabel); ?></strong> is filled when the item is on this customer&apos;s BOM; otherwise <strong>0</strong>.
        <strong>Avail serials</strong> is how many serial numbers are in stock for that product.
    </p>

    <?php if (count($serialLines) === 0) { ?>
        <div class="alert alert-info mb-0">No active Serial No Product records in product master. Add products with Product Type <strong>Serial No Product</strong> on Add Product.</div>
    <?php } else { ?>
    <div class="upb-stock-card-inner">
        <table id="tblRequiredSerialStock" class="table table-striped table-bordered table-sm nowrap" style="width:100%" cellspacing="0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th style="min-width:240px">Item</th>
                    <th class="text-right" style="min-width:100px"><?php echo htmlspecialchars($reqQtyLabel); ?></th>
                    <?php foreach ($storeColumns as $storeCol) { ?>
                    <th class="text-right" style="min-width:120px">
                        <?php echo htmlspecialchars((string) $storeCol['store_name']); ?>
                        <br><small class="font-weight-normal text-muted">Avail serials</small>
                    </th>
                    <?php } ?>
                    <th class="text-right" style="min-width:130px">Total avail serials</th>
                    <th style="min-width:130px">Serial details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1;
                foreach ($serialLines as $ln) {
                    $pid = (int) $ln['ProductId'];
                    $req = (int) round((float) $ln['ReqQty']);
                    if ($req > 0) {
                        $serialTotalReq += $req;
                    }
                    $name = (string) $ln['ProductName'];
                    $totalSerials = ($pid > 0) ? upb_serial_available_count($conn, $pid, null) : 0;
                    $serialTotalAvail += $totalSerials;
                    $short = ($pid > 0 && $req > 0 && $req > $totalSerials);
                    $locPayload = [];
                    ?>
                    <tr class="<?php echo $short ? 'table-warning' : ''; ?>">
                        <td style="color:#212529;"><?php echo $n++; ?></td>
                        <td style="color:#212529;"><?php echo htmlspecialchars($name); ?></td>
                        <td class="text-right" style="color:#212529;"><?php echo $req > 0 ? $req : '0'; ?></td>
                        <?php foreach ($storeColumns as $storeCol) {
                            $bid = (int) $storeCol['branch_id'];
                            $storeSerials = ($pid > 0 && $bid > 0)
                                ? upb_serial_available_count($conn, $pid, $bid)
                                : 0;
                            if ($pid > 0 && $storeSerials > 0) {
                                $locPayload[] = [
                                    'StoreName' => 'Store (serial): ' . (string) $storeCol['store_name'],
                                    'AvailQty' => (float) $storeSerials,
                                    'BranchId' => $bid,
                                    'row_kind' => 'store_serial',
                                    'branch_id' => $bid,
                                    'store_exe_id' => 0,
                                ];
                            }
                            $cellShort = ($pid > 0 && $req > 0 && $storeSerials < $req);
                            ?>
                        <td class="text-right<?php echo $cellShort ? ' upb-store-short' : ''; ?>" style="color:#212529;">
                            <?php echo $pid > 0 ? (int) $storeSerials : '—'; ?>
                        </td>
                        <?php } ?>
                        <td class="text-right font-weight-bold<?php echo $short ? ' text-danger' : ''; ?>" style="color:<?php echo $short ? '#dc3545' : '#212529'; ?>;">
                            <?php echo $pid > 0 ? (int) $totalSerials : '—'; ?>
                        </td>
                        <td>
                            <?php
                            if ($pid <= 0) {
                                echo '<span class="text-muted">—</span>';
                            } elseif ($totalSerials <= 0) {
                                echo '<span class="text-muted">0 in stock</span>';
                            } else {
                                $locJson = htmlspecialchars(json_encode($locPayload, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                $itemTitle = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                                ?>
                                <button type="button" class="btn btn-sm btn-success btn-view-store-avl"
                                    data-toggle="modal" data-target="#modalAvlByStore"
                                    data-product-id="<?php echo (int) $pid; ?>"
                                    data-item-name="<?php echo $itemTitle; ?>"
                                    data-required="<?php echo (int) $req; ?>"
                                    data-total-avail="<?php echo (int) $totalSerials; ?>"
                                    data-is-serial="1"
                                    data-locations="<?php echo $locJson; ?>">
                                    View serials (<?php echo (int) $totalSerials; ?>)
                                </button>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2" class="text-right" style="color:#212529;">Total</td>
                    <td class="text-right" style="color:#212529;"><?php echo (int) $serialTotalReq; ?></td>
                    <?php for ($i = 0; $i < $storeColCount; $i++) { ?>
                    <td></td>
                    <?php } ?>
                    <td class="text-right" style="color:#212529;"><?php echo (int) $serialTotalAvail; ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php } ?>
</div>
