<?php 
session_start();
include_once '../../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'addMoreService'){
	$i = $_POST['id'];?>

     <div class="form-row" id="row<?php echo $i;?>">
<div class="form-group col-md-4 ">
<label class="form-label">Accessories</label>
 <select class="form-control" name="AccId[]" id="AccId<?php echo $i; ?>"  onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Accessories</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_accessories WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["AccId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['AccName']; ?></option>
<?php } ?>
</select>
</div>


                                        <div class="form-group col-md-2">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</div>



<div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</div>

<div class="form-group col-md-3">
<label class="form-label">Total </label>
<input type="text" name="Total[]" id="Total<?php echo $i; ?>" class="form-control txt" placeholder="e.g.,150" value="" autocomplete="off" readonly>
</div>


<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>

</div>

	 
<?php }

if($_POST['action'] == 'getRate'){
$ProdId = $_POST['val'];
$type = $_POST['type'];
$sql = "SELECT MinPrice,MinPrice2 FROM products WHERE id='$ProdId'";
$row = getRecord($sql);
if($type == 2){
$MinPrice = $row['MinPrice2'];
}
else{
$MinPrice = $row['MinPrice'];    
}
echo $MinPrice;
}


if($_POST['action'] == 'getTotScrapStock'){
$BranchId = $_POST['id'];
$sql = "SELECT SUM(Qty) AS CreditStock FROM tbl_scrap_stock WHERE CrDr='cr' AND BranchId='$BranchId'";
$row = getRecord($sql);

$sql2 = "SELECT SUM(Qty) AS DebitStock FROM tbl_scrap_stock WHERE CrDr='dr' AND BranchId='$BranchId'";
$row2 = getRecord($sql2);

$BalStock = $row['CreditStock'] - $row2['DebitStock'];
echo $BalStock;
  }


if($_POST['action'] == 'addMoreService2'){
  $i = $_POST['id'];?>

     <div class="form-row" id="row<?php echo $i;?>">
<div class="form-group col-md-6">
<label class="form-label">Accessories</label>
 <select class="form-control" name="AccId[]" id="AccId<?php echo $i; ?>" >
<option selected="" value="">Select Accessories</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_accessories WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['AccName']; ?></option>
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

   
<?php } ?>