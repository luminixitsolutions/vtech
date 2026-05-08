<div class="modal fade insert_frm" id="modals-default<?php echo $result['id']; ?>">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data">
<div class="modal-header">
<h5 class="modal-title">Update
<span class="font-weight-light"> Tracking Details</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
<div id="alert_message"></div>
<input type="hidden" name="id" value="<?php echo $result['id']; ?>" id="id">
<input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>" id="pid">
<input type="hidden" name="oid" value="<?php echo $_GET['oid']; ?>" id="oid">
<input type="hidden" name="action" value="UpdateTrack" id="action">
<div class="form-row">
<div class="form-group col-md-12">
<label class="form-label">Product Name <span class="text-danger">*</span></label>
<input type="text" class="form-control" placeholder="Product Name" value="<?php echo $ProdName; ?>" autocomplete="off">
</div>  
<div class="form-group col-md-12">
<label class="form-label">Delivery Date <span class="text-danger">*</span></label>
<input type="date" name="SubDate" class="form-control" placeholder="First Name" value="<?php echo $result['SubDate']; ?>" autocomplete="off" readonly="">
</div>
<div class="form-group col-md-12">
<label class="form-label">Step 2 <span class="text-danger">*</span></label>
<input type="text" name="Step2" id="Step2" class="form-control" placeholder="" value="<?php echo $result['Step2']; ?>" autocomplete="off">
<input type="hidden" name="Step2Date" value="<?php echo $result['Step2Date']; ?>" id="Step2Date">
</div>
<div class="form-group col-md-12">
<label class="form-label">Step 3 <span class="text-danger">*</span></label>
<input type="text" name="Step3" id="Step3" class="form-control" placeholder="" value="<?php echo $result['Step3']; ?>" autocomplete="off">
<input type="hidden" name="Step3Date" value="<?php echo $result['Step3Date']; ?>" id="Step3Date">
</div>
<div class="form-group col-md-12">
<label class="form-label">Step 4 <span class="text-danger">*</span></label>
<input type="text" name="Step4" id="Step4" class="form-control" placeholder="" value="<?php echo $result['Step4']; ?>" autocomplete="off">
<input type="hidden" name="Step4Date" value="<?php echo $result['Step4Date']; ?>" id="Step4Date">
</div>
<div class="form-group col-md-12">
  <label class="form-label">Upload Photo <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="TrackPhoto" id="TrackPhoto" style="opacity: 1;">
<input type="hidden" name="OldPhoto" value="<?php echo $result['Photo'];?>" id="OldPhoto<?php echo $result['id']; ?>">
<span class="custom-file-label"></span>
</label>
<?php if($result['Photo']=='') {} else{?>
  <span id="show_photo<?php echo $result['id']; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="deleteTrackPhoto(<?php echo $result['id']; ?>)"></a><img src="../uploads/<?php echo $result['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>
</div>
<div class="form-row">
<div class="form-group col-md-12">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="OrderStatus" name="OrderStatus" required="">
<option value="2" <?php if($result['OrderStatus'] == 2) {?> selected <?php } ?>>In Progress</option>
<option value="5" <?php if($result['OrderStatus'] == 5) {?> selected <?php } ?>>Dispatch</option>
<option value="1" <?php if($result['OrderStatus'] == 1) {?> selected <?php } ?>>Delivered</option>
</select>
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-md-12">
<button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
</div>
</form>
</div>
</div>
</div>

