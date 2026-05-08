<?php 
session_start();
include_once '../config.php';
// require_once("../dbcontroller.php");
// $db_handle = new DBController();
$sessionid = session_id();
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getCustRow'){
$i = $_POST['id'];?>

<tr id="row<?php echo $i;?>">

 <td>


<select name="ProductId[]" id="ProductId<?php echo $i;?>" onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i;?>').value)" class="form-control">
    <option value="" selected>Select Product</option>
    <?php 
  $sql12 = "SELECT * FROM tbl_rooftop_qtn_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
     <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
 <?php } ?>
</select>

</td>
       <input type="hidden" name="ProductName[]" id="ProductName<?php echo $i;?>" value="">
 <input type="hidden" name="ModelNo[]" id="ModelNo<?php echo $i;?>" value="">
 <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
<td>
<input type="number" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)" required>
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off">
</td>



<td>
<input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)" required>
</td>



<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
<td>
<input type="text" name="TotalRate[]" id="Total<?php echo $i;?>" class="form-control txt" placeholder="e.g.,150" value="" autocomplete="off" readonly>
</td>
<td>
  <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
</td>

</tr>

<?php } 

if($_POST['action'] == 'getProdDetails') {
    $id = addslashes(trim($_POST['id']));
    $sql = "SELECT tp.*,tc.Name As Category,tsc.Name As Brand FROM tbl_rooftop_qtn_products tp 
            LEFT JOIN tbl_category tc ON tc.id=tp.CatId 
            LEFT JOIN tbl_sub_category tsc ON tsc.id=tp.BrandId WHERE tp.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
 //    $Price = $row['Price'];
	// echo json_encode(array('Price'=>$Price));
   }

  
?>