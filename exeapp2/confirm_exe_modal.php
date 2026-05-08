<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Send Order 
<span class="font-weight-light"> Details</span>
<!-- <br>
<small class="text-muted">We need payment information to process your order.</small> -->
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
<input type="hidden" name="action" value="Delivered">
<input type="hidden" name="oid" value="<?php echo $_GET['oid']; ?>">
<input type="hidden" name="ordno" value="<?php echo $OrderNo; ?>">
<div class="form-group col-md-12">
 <label class="form-label">Executive <span class="text-danger">*</span></label>
  <select class="select2-demo form-control" id="EmpId" name="EmpId" required="">
<?php 
        $q = "SELECT id,Fname,Lname,Phone FROM tbl_users WHERE Status='1' AND Roll=3";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
<option value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']." (".$rw['Phone'].")" ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
 <label class="form-label">Confirm Date <span class="text-danger">*</span></label>
<input type="date" name="ConfirmDate" id="ConfirmDate" class="form-control" placeholder="Delieverd Date" autocomplete="off" value="<?php echo date('Y-m-d'); ?>">
</div>
<div class="form-group col-md-12">
 <label class="form-label">Confirm Time <span class="text-danger">*</span></label>
<input type="text" name="ConfirmTime" id="ConfirmTime" class="form-control" placeholder="Delieverd Time" autocomplete="off" value="<?php echo date('h:i a'); ?>" readonly>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger" name="confirm_submit" onClick="return confirm('Are you sure you want Confirmed this Order?');">Submit</button>
</div>
</form>
</div>
</div>
