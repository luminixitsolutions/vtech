<div class="modal fade insert_frm" id="modals-default3">
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
<option selected="" disabled="" value="">Select Executive</option>
<?php 
        $q = "SELECT v.*,s.Name As State,c.Name As City FROM employee v
            LEFT JOIN state s ON s.id=v.StateId
            LEFT JOIN city c ON c.id=v.CityId WHERE v.Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']." (".$rw['State']." - ".$rw['City'].")"; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
<span id="show_details2" style="display: none;">
<div id="accordion2">
<div class="form-group col-md-12">
<a class="d-flex justify-content-between text-dark" data-toggle="collapse" aria-expanded="true" href="#accordion2-2">Executive details<div class="collapse-icon"></div></a>
</div>
<div id="accordion2-2" class="collapse" data-parent="#accordion2">
<div class="card-body">
<div class="form-group col-md-12">
<label class="form-label">Mobile No <span class="text-danger">*</span></label>
<input type="text" id="Phone2" class="form-control" placeholder="Mobile No" disabled>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
<label class="form-label">Email Id <span class="text-danger">*</span></label>
<input type="email" id="EmailId2" class="form-control" placeholder="Email Id" autocomplete="off" disabled>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
<label class="form-label">Address <span class="text-danger">*</span></label>
<textarea id="Address2" class="form-control" placeholder="Address" autocomplete="off" disabled></textarea>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
<label class="form-label">Pincode No <span class="text-danger">*</span></label>
<input type="text" id="Pincode2" class="form-control" placeholder="Pincode No" autocomplete="off" disabled>
<div class="clearfix"></div>
</div>
</div>
</div>
</div>
</span>

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
<button type="submit" class="btn btn-danger" name="submit" onClick="return confirm('Are you sure you want Confirmed this Order?');">Submit</button>
</div>
</form>
</div>
</div>
