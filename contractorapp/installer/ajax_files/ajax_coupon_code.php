<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
$CreatedDate = date('Y-m-d');
if($_POST['action'] == 'Add'){
$Code = addslashes(trim($_POST["Code"]));
$Discount = addslashes(trim($_POST["Discount"]));
$FromDate = addslashes(trim($_POST["FromDate"]));
$ToDate = addslashes(trim($_POST["ToDate"]));
$MinOrder = addslashes(trim($_POST["MinOrder"]));
$PointDays = addslashes(trim($_POST["PointDays"]));
$Status = $_POST["Status"];

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
  //$Photo = $_POST['OldPhoto'];
}

$query = "SELECT * FROM tbl_coupon_code WHERE Code = '$Code'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_coupon_code SET Code='$Code',Discount='$Discount',FromDate='$FromDate',ToDate='$ToDate',MinOrder='$MinOrder',PointDays='$PointDays',Photo='$Photo',Status='$Status',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
	$conn->query($qx);
	echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_coupon_code WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Code = addslashes(trim($_POST["Code"]));
$Discount = addslashes(trim($_POST["Discount"]));
$FromDate = addslashes(trim($_POST["FromDate"]));
$ToDate = addslashes(trim($_POST["ToDate"]));
$MinOrder = addslashes(trim($_POST["MinOrder"]));
$PointDays = addslashes(trim($_POST["PointDays"]));
$Status = $_POST["Status"];
$OldPhoto = $_POST['OldPhoto'];
$OldPhoto2 = $_POST['OldPhoto2'];
$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
  $src = "../../uploads/$OldPhoto";
  unlink($src); 
$Photo = $imagepath ;
} 
else{
  $Photo = $_POST['OldPhoto'];
}
$query = "SELECT * FROM tbl_coupon_code WHERE Code = '$Code' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE tbl_coupon_code SET Code='$Code',Discount='$Discount',FromDate='$FromDate',ToDate='$ToDate',MinOrder='$MinOrder',PointDays='$PointDays',,Photo='$Photo',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_coupon_code WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }


  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              
              <th>Referral/Coupon Code</th>
              <th>Points</th>
              <th>Points Validity</th>
              <th>Date</th>
              <th>Min Order Amount</th>
               <th>Status</th>
               <?php if($_SESSION['Roll']==1) {?>
               <th>Action</th>
             <?php } ?>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_coupon_code ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
           
             <td><?php echo $nx['Code']; ?></td>
             <td><?php echo $nx['Discount']; ?></td>
             <td><?php echo $nx['PointDays']." Days"; ?></td>
             <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$nx['FromDate']))); ?> - <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$nx['ToDate']))); ?></td>
             <td>&#8377;<?php echo number_format($nx["MinOrder"],2); ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <?php if($_SESSION['Roll']==1) {?>
             <td><a href='add-coupon.php?id=<?php echo $nx['id']; ?>' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
             </td><?php } ?>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
        "scrollX": true
      });
      });
    </script>
 <?php }
?>