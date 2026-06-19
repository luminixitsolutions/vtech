<?php
if (!isset($storeColumns) || !is_array($storeColumns)) {
    $storeColumns = [];
}
if (!isset($reqQtyLabel)) {
    $reqQtyLabel = 'Required qty';
}
if (!isset($serialCatalogCount)) {
    $serialCatalogCount = upb_count_serial_product_catalog($conn);
}
if (!isset($upbSerialCustIds) || !is_array($upbSerialCustIds)) {
    $upbSerialCustIds = [];
}
$storeColCount = count($storeColumns);
$upbSerialCustIdsJson = htmlspecialchars(json_encode(array_values(array_map('intval', $upbSerialCustIds))), ENT_QUOTES, 'UTF-8');
?>
<div class="card mt-3" style="padding: 10px;" id="upbSerialStockRoot"
    data-cust-ids="<?php echo $upbSerialCustIdsJson; ?>"
    data-store-count="<?php echo (int) $storeColCount; ?>">
    <h5 class="mb-2" style="font-size: 18px; color: #212529;">Serial No Products</h5>

    <?php if ($serialCatalogCount === 0) { ?>
        <div class="alert alert-info mb-0">No active Serial No Product records in product master. Add products with Product Type <strong>Serial No Product</strong> on Add Product.</div>
    <?php } else { ?>
    <div class="upb-serial-dt-wrap">
        <div id="upbSerialSkeleton" class="upb-serial-skeleton d-none" aria-hidden="true">
            <?php for ($sk = 0; $sk < 10; $sk++) { ?>
            <div class="upb-skeleton-row">
                <span class="upb-skeleton-block sm"></span>
                <span class="upb-skeleton-block lg"></span>
                <span class="upb-skeleton-block xs"></span>
                <span class="upb-skeleton-block xs"></span>
                <span class="upb-skeleton-block md"></span>
                <span class="upb-skeleton-block md"></span>
            </div>
            <?php } ?>
        </div>
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
            <tbody></tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2" class="text-right" style="color:#212529;">Total <small class="font-weight-normal text-muted">(this page)</small></td>
                    <td class="text-right" id="upbSerialFootReq" style="color:#212529;">0</td>
                    <?php for ($i = 0; $i < $storeColCount; $i++) { ?>
                    <td></td>
                    <?php } ?>
                    <td class="text-right" id="upbSerialFootAvail" style="color:#212529;">0</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php } ?>
</div>
