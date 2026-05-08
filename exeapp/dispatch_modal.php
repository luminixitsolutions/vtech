<div class="modal fade insert_frm" id="modals-default3">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Order Dispatch
<span class="font-weight-light"> Details</span>
<!-- <br>
<small class="text-muted">We need payment information to process your order.</small> -->
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <?php 
  $oid = $_GET['oid'];
      $sql77 = "SELECT * FROM confirm_orders WHERE Ordid='$oid'";
      $res77 = $conn->query($sql77);
      $row77 = $res77->fetch_assoc();
   ?>
<input type="hidden" name="action" value="Delivered">
<input type="hidden" name="oid" value="<?php echo $_GET['oid']; ?>">
<input type="hidden" name="ordno" value="<?php echo $OrderNo; ?>">
<div class="form-group col-md-12">
 <label class="form-label">Vendor <span class="text-danger">*</span></label>
  <select class="form-control" id="VedId" name="VedId" required="" disabled>
<option selected="" disabled="" value="">Select Vendor</option>
<?php 
        $q = "SELECT v.*,s.Name As State,c.Name As City FROM vendors v
        	  LEFT JOIN state s ON s.id=v.StateId
        	  LEFT JOIN city c ON c.id=v.CityId WHERE v.Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row77['VedId'] == $rw['id']) {?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']." (".$rw['State']." - ".$rw['City'].")"; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-12">
 <label class="form-label">Executive <span class="text-danger">*</span></label>
  <select class="form-control" id="EmpId" name="EmpId" required="" disabled>
<option selected="" disabled="" value="">Select Executive</option>
<?php 
        $q = "SELECT v.*,s.Name As State,c.Name As City FROM employee v
        	  LEFT JOIN state s ON s.id=v.StateId
        	  LEFT JOIN city c ON c.id=v.CityId WHERE v.Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row77['EmpId'] == $rw['id']) {?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']." (".$rw['State']." - ".$rw['City'].")"; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>




<div class="form-group col-md-12">
 <label class="form-label">Dispatch Date <span class="text-danger">*</span></label>
<input type="date" name="DispatchDate" id="DispatchDate" class="form-control" placeholder="Delieverd Date" autocomplete="off" value="<?php echo $row77['DispatchDate']; ?>" readonly>
</div>
<div class="form-group col-md-12">
 <label class="form-label">Dispatch Time <span class="text-danger">*</span></label>
<input type="text" name="DispatchTime" id="DispatchTime" class="form-control" placeholder="Delieverd Time" autocomplete="off" value="<?php echo $row77['DispatchTime']; ?>" readonly>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</form>
</div>
</div>
