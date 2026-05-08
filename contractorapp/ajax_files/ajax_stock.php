<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'addMoreService'){
	$i = $_POST['id'];?>

     <div class="form-row" id="row<?php echo $i;?>">
<div class="form-group col-md-6">
<label class="form-label">Product</label>
 <select class="form-control" name="ProdId[]" id="ProdId<?php echo $i; ?>" >
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>


                                        <div class="form-group col-md-2">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control txt" placeholder="e.g.,1" value="" autocomplete="off" min="1" oninput="getSubTotal()">
</div>





<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>

</div>

	 
<?php } 

if($_POST['action'] == 'addMoreService2'){
  $i = $_POST['id'];?>

     <div class="form-row" id="row<?php echo $i;?>">
<div class="form-group col-md-6">
<label class="form-label">Product</label>
 <select class="form-control" name="ProdId[]" id="ProdId<?php echo $i; ?>" onchange="getProdStock(document.getElementById('srno<?php echo $i; ?>').value,this.value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-2">
<label class="form-label">Tot Qty </label>
<input type="number" id="TotQty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="" autocomplete="off" min="1" readonly>
</div>

                                        <div class="form-group col-md-2">
<label class="form-label">Transfer Qty </label>
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control txt" placeholder="e.g.,1" value="" autocomplete="off" min="1" oninput="getSubTotal()">
</div>





<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>

</div>

   
<?php } 

if($_POST['action'] == 'getProdStock'){
  $id = $_POST['id'];
  $BranchId = $_POST['FromBranchId'];
  $sql = "SELECT SUM(Qty) AS AvlQty FROM tbl_stocks WHERE BranchId='$BranchId' AND CrDr='cr' AND ProductId='$id'";
  $row = getRecord($sql);

  $sql2 = "SELECT SUM(Qty) AS UseQty FROM tbl_stocks WHERE BranchId='$BranchId' AND CrDr='dr' AND ProductId='$id'";
  $row2 = getRecord($sql2);
  $AvlQty = $row['AvlQty'] - $row2['UseQty'];
  if($AvlQty == ''){
    echo 0;
  }
  else{
    echo $AvlQty;
  }
  
}
?>