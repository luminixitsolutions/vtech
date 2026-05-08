<?php 
session_start();
include_once '../../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['User']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Options = explode(',',$row77['Options']);
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Phone = addslashes(trim($_POST["Phone"]));
$Phone2 = addslashes(trim($_POST["Phone2"]));
$EmailId = addslashes(trim($_POST["EmailId"]));
$Address = addslashes(trim($_POST["Address"]));
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
$query = "SELECT * FROM tbl_branch WHERE Name = '$Name'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_branch SET Name = '$Name',Status='$Status',Photo='$Photo',Phone = '$Phone',Phone2='$Phone2',EmailId='$EmailId',Address='$Address'";
	$conn->query($qx);
	echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_branch WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Phone = addslashes(trim($_POST["Phone"]));
$Phone2 = addslashes(trim($_POST["Phone2"]));
$EmailId = addslashes(trim($_POST["EmailId"]));
$Address = addslashes(trim($_POST["Address"]));
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
$query = "SELECT * FROM tbl_branch WHERE Name = '$Name' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE tbl_branch SET Name = '$Name', Status='$Status',Photo='$Photo',Phone = '$Phone',Phone2='$Phone2',EmailId='$EmailId',Address='$Address' WHERE id = '$id'";
 	$conn->query($query2);
 }
  echo 1;
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
       $query2 = "SELECT Photo FROM tbl_branch WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
      $query = "DELETE FROM tbl_branch WHERE id = '$id'";
      $conn->query($query);
       $src = "../../uploads/$Photo";
        unlink($src);
      
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_branch SET Photo='' WHERE id=$id";
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
              <th>Branch Name</th>
              <th>Phone</th>
              <th>Address</th>
             
               <th>Status</th>
               
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_branch ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             
             <td><?php echo $nx['Name']; ?></td>
              <td><?php echo $nx['Phone']; ?></td>
               <td><?php echo $nx['Address']; ?></td>
           
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