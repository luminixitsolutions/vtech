<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$OrderPrice = addslashes(trim($_POST["OrderPrice"]));
$ShippingPrice = addslashes(trim($_POST["ShippingPrice"]));
$Status = $_POST["Status"];
$query = "SELECT * FROM shipping_price WHERE OrderPrice = '$OrderPrice'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO shipping_price SET OrderPrice = '$OrderPrice',ShippingPrice = '$ShippingPrice',Status='$Status'";
	$conn->query($qx);
	echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM shipping_price WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$OrderPrice = addslashes(trim($_POST["OrderPrice"]));
$ShippingPrice = addslashes(trim($_POST["ShippingPrice"]));
$Status = $_POST["Status"];
$query = "SELECT * FROM shipping_price WHERE OrderPrice = '$OrderPrice' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE shipping_price SET OrderPrice = '$OrderPrice',ShippingPrice = '$ShippingPrice',Status='$Status' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM shipping_price WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }


  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              
              <th>Order Price</th>
               <th>Shipping Price</th>
               <th>Status</th>
               <?php if($_SESSION['Roll']==1) {?>
               <th>Action</th>
             <?php } ?>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM shipping_price ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
           
             <td>&#8377;<?php echo $nx['OrderPrice']; ?></td>
              <td>&#8377;<?php echo $nx['ShippingPrice']; ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <?php if($_SESSION['Roll']==1) {?>
             <td><a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a><!-- &nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a> -->
             </td><?php } ?>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
        responsive: true
      });
      });
    </script>
 <?php }
?>