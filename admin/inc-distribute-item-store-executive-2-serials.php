<?php
$BranchId = (int) $BranchId;
?>
<input type="hidden" name="Rncnt3" id="Rncnt3" value="<?php echo (int) $rncnt223; ?>">
<?php if ($rncnt223 > 80) { ?>
<div class="form-group col-md-4 mb-2">
  <input type="text" class="form-control form-control-sm serial-table-filter" data-target="#example" placeholder="Search bag serial no / product...">
</div>
<?php } ?>
<div class="form-row">
 <label class="form-label d-flex align-items-center" style="font-size: 18px; color: #0dc30d;">
  Bag Serial No Products &nbsp;|&nbsp;&nbsp;&nbsp; <input type="checkbox" id="selectAll2" style="margin-right: 8px;">&nbsp;Select All
</label>
<div class="col-lg-12">
 <table id="example" class="table table-striped table-bordered distribute-serial-table" width="100%">
     <thead>
    <tr>
       <th style="width: 10px;"><input type="checkbox" id="selectAll2Header"></th>
        <th width="50%">Product</th>
       <th>Serial No </th>
     </tr>
     </thead>
     <tbody>
  <?php
    foreach ($bagRows as $result) {
    ?>
<tr>
    <td>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" id="Check_Id2<?php echo (int) $result['id']; ?>" value="0" class="custom-control-input is-valid">
      <span class="custom-control-label"></span>
    </label>
  </td>
            <input type="hidden" name="SerialProd2[]" value="<?php echo (int) $result['id']; ?>">
            <input type="hidden" value="0" name="CheckId2[]" id="CheckId<?php echo (int) $result['id']; ?>">
            <td><?php echo htmlspecialchars($result['ProductName']); ?></td>
            <td><?php echo htmlspecialchars($result['SerialNo']); ?></td>
</tr>
<?php
    }
    if ($rncnt223 === 0) {
        echo '<tr><td colspan="3" class="text-muted">No bag serial products available.</td></tr>';
    }
?>
     </tbody>
</table>
</div>
</div>

<br>
<input type="hidden" name="Rncnt2" id="Rncnt2" value="<?php echo (int) $rncnt22; ?>">
<div class="form-row">
 <label class="form-label d-flex align-items-center" style="font-size: 18px; color: #0dc30d;">
  Serial No Products &nbsp;|&nbsp;&nbsp;&nbsp; <input type="checkbox" id="selectAll" style="margin-right: 8px;">&nbsp;Select All
  <?php if ($rncnt22 > 0) { ?>
  <span class="text-muted ml-2" style="font-size: 13px;">(<?php echo (int) $rncnt22; ?> items)</span>
  <?php } ?>
</label>
<?php if ($rncnt22 > 80) { ?>
<div class="form-group col-md-4 mb-2">
  <input type="text" class="form-control form-control-sm serial-table-filter" data-target="#example3" placeholder="Search serial no / product...">
</div>
<?php } ?>
<div class="col-lg-12">
 <table id="example3" class="table table-striped table-bordered distribute-serial-table" width="100%">
     <thead>
    <tr>
       <th style="width: 10px;"><input type="checkbox" id="selectAllHeader"></th>
        <th width="50%">Product</th>
       <th>Serial No </th>
     </tr>
     </thead>
     <tbody>
  <?php
    foreach ($serialRows as $result) {
    ?>
<tr>
    <td>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" id="Check_Id<?php echo (int) $result['id']; ?>" value="0" class="custom-control-input is-valid">
      <span class="custom-control-label"></span>
    </label>
  </td>
            <input type="hidden" name="SerialProd[]" value="<?php echo (int) $result['id']; ?>">
            <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo (int) $result['id']; ?>">
            <td><?php echo htmlspecialchars($result['ProductName']); ?></td>
            <td><?php echo htmlspecialchars($result['SerialNo']); ?></td>
</tr>
<?php
    }
    if ($rncnt22 === 0) {
        echo '<tr><td colspan="3" class="text-muted">No serial products available.</td></tr>';
    }
?>
     </tbody>
</table>
</div>
</div>
