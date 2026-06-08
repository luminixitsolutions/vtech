<?php 
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Options = explode(',',$row77['Options']);
if($_POST['action'] == 'Add'){
$TripId = addslashes(trim($_POST["TripId"]));
$DriverId = addslashes(trim($_POST["DriverId"]));
$Amount = addslashes(trim($_POST["Amount"]));
$Narration = addslashes(trim($_POST["Narration"]));
$CrDate = addslashes(trim($_POST["CrDate"]));
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
$CreatedDate = date('Y-m-d');

$qx = "INSERT INTO tbl_diesel_amount SET TripId = '$TripId',Status='1',DriverId = '$DriverId',Amount='$Amount',Narration='$Narration',CrDate='$CrDate',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
	$conn->query($qx);
	echo 1;
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_diesel_amount WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$TripId = addslashes(trim($_POST["TripId"]));
$DriverId = addslashes(trim($_POST["DriverId"]));
$Amount = addslashes(trim($_POST["Amount"]));
$Narration = addslashes(trim($_POST["Narration"]));
$CrDate = addslashes(trim($_POST["CrDate"]));
$Status = $_POST["Status"];
$OldPhoto = $_POST['OldPhoto'];
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
$ModifiedDate = date('Y-m-d');
  $query2 = "UPDATE tbl_diesel_amount SET Status='1',Amount='$Amount',Narration='$Narration',CrDate='$CrDate',ModifiedBy='$user_id',ModifiedDate='$ModifiedDate' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_diesel_amount WHERE id = '$id'";
      $conn->query($query);
       $src = "../../uploads/$Photo";
        unlink($src);
      
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_diesel_amount SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Photo Delete Successfully";
} 
  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Narration</th>
               <th>Status</th>
               
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_diesel_amount WHERE TripId='".$_POST['TripId']."' ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             
             <td><?php echo $nx['Amount']; ?></td>
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$nx['CrDate']))); ?></td>
             <td><?php echo $nx['Narration']; ?></td>
           
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
          
             <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
             <td>
                <?php if(in_array("10", $Options)){?>
                <a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
                  <?php } if(in_array("11", $Options)){?>
                 <a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
                  <?php } ?>
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