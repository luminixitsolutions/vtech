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
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
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
<input type="number" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required>
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off">
</td>



<td>
<input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required>
</td>


<td><input type="text" name="SGST[]" id="SGST<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required></td>
       <td><input type="text" name="CGST[]" id="CGST<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required></td>
       <td><input type="text" name="IGST[]" id="IGST<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i;?>').value,document.getElementById('Price<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value,document.getElementById('SGST<?php echo $i;?>').value,document.getElementById('CGST<?php echo $i;?>').value,document.getElementById('IGST<?php echo $i;?>').value)" required></td>
      
  


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
    $sql = "SELECT tp.*,tc.Name As Category,tsc.Name As Brand FROM tbl_products tp 
            LEFT JOIN tbl_category tc ON tc.id=tp.CatId 
            LEFT JOIN tbl_sub_category tsc ON tsc.id=tp.BrandId WHERE tp.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
 //    $Price = $row['Price'];
	// echo json_encode(array('Price'=>$Price));
   }

   if($_POST['action'] == 'getProdDetails2') {
    $id = addslashes(trim($_POST['id']));
    $sql = "SELECT tp.*,tc.Name As Category,tsc.Name As Brand,tp2.Price,tp2.Details FROM tbl_stocks tp 
            LEFT JOIN tbl_category tc ON tc.id=tp.CatId 
            LEFT JOIN tbl_products tp2 ON tp2.id=tp.ProductId 
            LEFT JOIN tbl_sub_category tsc ON tsc.id=tp.BrandId WHERE tp.ProductNo='$id' AND tp.BuyStatus=0";
    $row = getRecord($sql);
    echo json_encode($row);
   }


   if($_POST['action'] == 'addCart'){
$ProductId = $_POST['ProductId'];
$Qty = $_POST['Qty'];
$CatId = $_POST['CatId'];
$BrandId = $_POST['BrandId'];
$Code = $_POST['Code'];
$ModelNo = $_POST['ModelNo'];
$ModelName = addslashes(trim($_POST['ModelName']));
$sql22 = "SELECT * FROM tbl_temp_stock WHERE SessionId='$sessionid'";
$rncnt22 = getRow($sql22);
if($Qty > $rncnt22){
$sql = "INSERT INTO tbl_temp_stock SET SessionId='$sessionid',ProductId='$ProductId',CatId='$CatId',BrandId='$BrandId',Code='$Code',ModelNo='$ModelNo',ModelName='$ModelName'";
$conn->query($sql);
echo 1;
}
else{
echo "You Cant add stock more than ".$Qty;
}
 // if(!empty($_POST["Qty"])) {
 //  $productByCode = $db_handle->runQuery("SELECT * FROM tbl_products WHERE Code='" . $_POST["Code"] . "'");
 //      $itemArray = array($productByCode[0]["Code"]=>array('ModelNo'=>$productByCode[0]["ModelNo"], 'Code'=>$productByCode[0]["Code"]));
 //      if(!empty($_SESSION["cart_item"])) {
 //        if(in_array($productByCode[0]["Code"],$_SESSION["cart_item"])) {
 //          foreach($_SESSION["cart_item"] as $k => $v) {
 //              if($productByCode[0]["Code"] == $k)
 //                $_SESSION["cart_item"][$k]["Qty"] = $_POST["Qty"];
 //          }
 //        } else {
 //          $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
 //        }
 //      } else {
 //        $_SESSION["cart_item"] = $itemArray;
 //      }

 //  }
 }


  if($_POST['action'] == 'showCart'){?>
   <table class="table table-striped table-bordered">
         <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
            $i=1;
            $sql2 = "SELECT * FROM tbl_temp_stock WHERE SessionId='$sessionid'";
            $row2 = getList($sql2);
            foreach($row2 as $result){
          ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $result['ModelName'] ?></td>
                <td><a onClick="deleteStock(<?php echo $result['id'];?>)" href="javascript:void(0)"><i class="lnr lnr-trash text-danger"></i></a></td>
            </tr>
          <?php $i++;} ?>
            </tbody>
    </table>
<?php } 

  if($_POST['action'] == 'deleteStock'){
    $id = $_POST['id'];
    $sql = "DELETE FROM tbl_temp_stock WHERE id='$id'";
    $conn->query($sql);
    echo 1;
  }

  if($_POST['action'] == 'availableSeries'){
    $ModelNo = $_POST['modelno'];
    ?>
   <table class="table table-striped table-bordered">
    <thead>
            <tr>
              <th>#</th>
              <th>Series</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql2 = "SELECT * FROM tbl_stocks WHERE ModelNo='".$ModelNo."' AND BuyStatus=0";
            $row2 = getList($sql2);
            foreach($row2 as $result){
          ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $result['ProductNo'] ?></td>
                
            </tr>
          <?php $i++;} ?>
        </tbody>
</table>
 
<?php } 


 if($_POST['action'] == 'checkCart'){
    $Qty = $_POST['Qty'];
   $sql22 = "SELECT * FROM tbl_temp_stock WHERE SessionId='$sessionid'";
$rncnt22 = getRow($sql22);
if($Qty == $rncnt22){  
    echo 1;
}
else{
    echo 0;
}
 }

 if($_POST['action'] == 'getAccDetails'){
     $id = addslashes(trim($_POST['id']));
    $sql = "SELECT tp.* FROM tbl_accessories tp 
            WHERE tp.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
 }
?>