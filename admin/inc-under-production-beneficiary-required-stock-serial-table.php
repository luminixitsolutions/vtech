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
    data-page-size="20">
    <h5 class="mb-2" style="font-size: 18px; color: #212529;">Serial No Products</h5>
    <p class="text-muted small mb-2">
        All products with <strong>Product Type = Serial No Product</strong> on <em>Add Product</em> (<code>tbl_products.Roll = 1</code>) — shown for every customer.
        <strong><?php echo htmlspecialchars($reqQtyLabel); ?></strong> is filled when the item is on this customer&apos;s BOM; otherwise <strong>0</strong>.
        <strong>Avail serials</strong> is how many serial numbers are in stock for that product.
        Rows load <strong>20 per page</strong> via AJAX — use <strong>Previous / Next</strong> below the table to browse all products.
    </p>
    <p class="small mb-3" id="upbSerialStockStatus" style="color:#212529;">
        <?php if ($serialCatalogCount > 0) { ?>
            <span class="text-muted">Loading first 20 of <?php echo (int) $serialCatalogCount; ?> products…</span>
        <?php } ?>
    </p>

    <?php if ($serialCatalogCount === 0) { ?>
        <div class="alert alert-info mb-0">No active Serial No Product records in product master. Add products with Product Type <strong>Serial No Product</strong> on Add Product.</div>
    <?php } else { ?>
    <div class="upb-serial-table-scroll">
        <table id="tblRequiredSerialStock" class="table table-striped table-bordered table-sm nowrap mb-0" style="width:100%" cellspacing="0">
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
            <tbody id="upbSerialStockTbody">
                <tr id="upbSerialStockLoadingRow">
                    <td colspan="<?php echo 5 + $storeColCount; ?>" class="text-center text-muted py-4" style="color:#212529;">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                        Loading serial products…
                    </td>
                </tr>
            </tbody>
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
    <div class="upb-serial-pager mt-3 mb-2" id="upbSerialPager">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="upbSerialPrev" disabled>&laquo; Previous</button>
            <div class="text-center flex-grow-1 px-2">
                <span class="font-weight-bold" id="upbSerialPageInfo" style="color:#212529;">Page 1</span>
                <span class="text-muted small d-block" id="upbSerialPageHint">20 products per page</span>
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="upbSerialNext" disabled>Next &raquo;</button>
        </div>
    </div>
    <?php } ?>
</div>
