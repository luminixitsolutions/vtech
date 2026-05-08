<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getCustRow'){
$i = $_POST['id'];?>
<div class="form-row" id="row<?php echo $i;?>">
	 <div class="form-group col-md-2">
<label class="form-label"> Category<span class="text-danger">*</span></label>
 <select class="form-control" name="CatId[]" id="CatId<?php echo $i;?>" required onchange="getBrand(this.value,<?php echo $i;?>)">
<option selected="" value="">Select Category</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_category WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-2">
<label class="form-label"> Brand<span class="text-danger">*</span></label>
 <select class="form-control" name="BrandId[]" id="BrandId<?php echo $i;?>" required onchange="getProd(this.value,<?php echo $i;?>)">
<option selected="" value="">Select Brand</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_sub_category WHERE CatId='".$row7["CatId"]."' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["BrandId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Product</label>
<select class="form-control" id="ProductId<?php echo $i;?>" name="ProductId[]" onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i;?>').value)">
<option selected="" disabled="">Select Product</option>
 <?php 
     $sql4 = "SELECT * FROM tbl_products WHERE Status=1";
     $row4 = getList($sql4);
     foreach($row4 as $result)
      {
      ?>
    <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (".$result['PrdNo'].")"; ?></option>
<?php } ?>
</select>

                                       </div>

                                       <div class="form-group col-md-1">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)">
</div>

<!-- <div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,200" value="" autocomplete="off" readonly>
</div>
 -->
<div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)">
</div>
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
<div class="form-group col-md-2">
<label class="form-label">Total </label>
<div class="input-group">
<input type="text" name="Total[]" id="Total<?php echo $i;?>" class="form-control txt" placeholder="e.g.,150" value="" autocomplete="off" readonly>
<span class="input-group-append">
  <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
</div>

</div>
<?php } 

if($_POST['action'] == 'getProdDetails') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_products WHERE id='$id'";
    $row = getRecord($sql);
    $Price = $row['Price'];
	echo json_encode(array('Price'=>$Price));
   }
?>