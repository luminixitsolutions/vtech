<div class="modal fade insert_frm" id="modals-default2">
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
<input type="hidden" name="oid" value="<?php echo $_GET['oid']; ?>">
<div class="form-group col-md-12">
  <label class="form-label">Message <span class="text-danger">*</span></label>
<textarea class="form-control bs-markdown" rows="10" name="Message" autocomplete="off" required="required" id="Message<?php echo $row2['ApplyId']; ?>"></textarea>
</div>
<div class="form-group col-md-12">
 <label class="form-label">Canceled Date <span class="text-danger">*</span></label>
<input type="date" name="CancelDate" id="CancelDate" class="form-control" placeholder="Canceled Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
</div>
<div class="form-group col-md-12">
 <label class="form-label">Canceled Time <span class="text-danger">*</span></label>
<input type="text" name="CancelTime" id="CancelTime" class="form-control" placeholder="Canceled Time" autocomplete="off" value="<?php echo date('h:i a'); ?>" readonly>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger" name="cancel_submit" onClick="return confirm('Are you sure you want cancel this Order?');">Submit</button>
</div>
</form>
</div>
</div>