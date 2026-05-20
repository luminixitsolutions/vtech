<?php
$BranchId = (int) $BranchId;
$narrationVal = isset($row7['Narration']) ? htmlspecialchars($row7['Narration']) : '';
?>
<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Product Details</label>
<table id="example2" class="table table-striped table-bordered distribute-qty-table" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
        <th>Stock Qty </th>
        <th>Qty </th>
        <th>Unit</th>
    </tr>
     </thead>
        <tbody id="dynamic_field">
            <input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo (int) $rncnt2; ?>">
            <?php
            foreach ($productRows as $result) {
                $BalQty = (int) $result['BalQty'];
            ?>
    <tr>
        <td><?php echo htmlspecialchars($result['Product_Name']); ?></td>
<input type="hidden" name="ProductId[]" value="<?php echo (int) $result['ProductId']; ?>">
 <input type="hidden" name="ProdType[]" value="0">
       <input type="hidden" name="ProductName[]" value="<?php echo htmlspecialchars($result['Product_Name']); ?>">
        <input type="hidden" name="SerialNo[]" value="">
 <input type="hidden" name="ModelNo[]" value="<?php echo htmlspecialchars($result['Model_No']); ?>">
<td><input type="number" name="BalQty[]" class="form-control" value="<?php echo $BalQty; ?>" min="1" readonly></td>
<td><input type="number" name="Qty[]" class="form-control" value="0" min="0"></td>
        <td><input type="text" name="Purity[]" class="form-control" value="<?php echo htmlspecialchars($result['Unit']); ?>"></td>
    </tr>
<?php
            }
            if ($rncnt2 === 0) {
                echo '<tr><td colspan="4" class="text-muted">No quantity products in stock for this store.</td></tr>';
            }
?>
    </tbody>
    </table>
</div>

<div id="distribute-serials-panel" class="mt-3">
    <div id="distribute-serials-loading" class="text-center py-3">
        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
        <span class="text-muted ml-2">Loading serial products...</span>
    </div>
    <div id="distribute-serials-content"></div>
</div>

<div class="form-row mt-3">
<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $narrationVal; ?>">
 </div>
</div>

<div class="form-row">
    <div class="form-group col-md-2">
        <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
    </div>
</div>
