<div class="modal fade insert_frm" id="modals-default2<?php echo $result['id']; ?>">
<div class="modal-dialog">
<form class="modal-content validation-form33" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Order Canceled 
<span class="font-weight-light"> Details</span>
<!-- <br>
<small class="text-muted">We need payment information to process your order.</small> -->
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
<input type="hidden" name="action" value="Canceled">
<input type="hidden" name="oid" value="<?php echo $Oid; ?>">
<input type="hidden" name="pid" value="<?php echo $pid; ?>">
<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
<input type="hidden" name="ordno" value="<?php echo $OrderNo; ?>">
<div class="form-group col-md-12">
  <label class="form-label" style="justify-content: left;width: 30%;">Why Order Cancel <span class="text-danger">*</span></label>
  <select class="form-control ReasonId" name="ReasonId" id="ReasonId" required="">
  	<option selected="" disabled="">Select Reason</option>
  	<?php 
  	$sql11 = "SELECT * FROM order_cancel_reason";
  	$row11 = getList($sql11);
  	foreach($row11 as $result){
  	 ?>
  	 <option value="<?php echo $result['id']; ?>" <?php if($result['id'] == 1) {?> selected <?php } ?>><?php echo $result['Reasons']; ?></option>
  	<?php } ?>
  	<option value="0">Other</option>
  </select>
</div>
<div class="form-group col-md-12 ReasonShow" style="display: none;" id="ReasonShow">
  <label class="form-label">Reason <span class="text-danger">*</span></label>
<textarea class="form-control Message" rows="5" name="Message" autocomplete="off" id="Message<?php echo $row2['ApplyId']; ?>"></textarea>
</div>
<input type="hidden" name="CancelDate" value="<?php echo date('Y-m-d'); ?>">
<input type="hidden" name="CancelTime" value="<?php echo date('h:i a'); ?>">

</div>
<div class="modal-footer">
<button type="submit" class="ps-btn" name="cancel_submit" style="padding: 7px 45px;background-color: red;">Cancel Order</button>
</div>
</form>
</div>
</div>