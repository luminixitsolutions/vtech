<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Url = addslashes(trim($_POST["Url"]));
$Icon = addslashes(trim($_POST["Icon"]));
$Status = $_POST["Status"];


if($_POST["Featured"] == '1'){
$Featured = 1;
}
else{
  $Featured = 0;
}
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

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
  //$Photo = $_POST['OldPhoto'];
}

$CreatedDate = date('Y-m-d');
$query = "SELECT * FROM category WHERE Name = '$Name'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
 $qx = "INSERT INTO category SET Name = '$Name', Icon = '$Icon',Status='$Status',Featured='$Featured',Photo='$Photo',Photo2='$Photo2',CreatedDate='$CreatedDate'";
  $conn->query($qx);
  echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM category WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Url = addslashes(trim($_POST["Url"]));
$Status = $_POST["Status"];
$Icon = addslashes(trim($_POST["Icon"]));
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

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
    $src3 = "../../uploads/$OldPhoto2";
  unlink($src3); 
$Photo2 = $imagepath2 ;
} 
else{
  $Photo2 = $_POST['OldPhoto2'];
}

if($_POST["Featured"] == 1){
$Featured = 1;
}
else{
  $Featured = 0;
}
$ModifiedDate = date('Y-m-d');
$query = "SELECT * FROM category WHERE Name = '$Name' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE category SET Name = '$Name',Icon = '$Icon',Status='$Status',Featured='$Featured',Photo='$Photo',Photo2='$Photo2',ModifiedDate='$ModifiedDate' WHERE id = '$id'";
  $conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
       $query2 = "SELECT Photo FROM category WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
     $Photo2 = $row2['Photo2'];
      $query = "DELETE FROM category WHERE id = '$id'";
      $conn->query($query);
      $query2 = "DELETE FROM sub_category WHERE CatId = '$id'";
      $conn->query($query2);
       $query3 = "DELETE FROM brands WHERE CatId = '$id'";
      $conn->query($query3);
       $query4 = "DELETE FROM attribute_name WHERE CatId = '$id'";
      $conn->query($query4);
       $query5 = "DELETE FROM attribute_value WHERE CatId = '$id'";
      $conn->query($query5);
       $src = "../../uploads/$Photo";
        unlink($src);
         $src2 = "../../uploads/$Photo2";
        unlink($src2);
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE category SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Photo Delete Successfully";
} 

if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE category SET Photo2='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Icon Delete Successfully";
} 
  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Photo</th>
               <th>Icon</th>
              <th>Category</th>
               <th>Status</th>
               <?php if($_SESSION['Roll']==1) {?>
               <th>Action</th>
             <?php } ?>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM category ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <td><?php if($nx["Photo"] == '') {?>
                  <img src="no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../../uploads/'.$nx["Photo"])){?>
                 <img src="../uploads/<?php echo $nx["Photo"];?>" class="img-fluid ui-w-40" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40"> 
             <?php } ?></td>
              <td><?php if($nx["Photo2"] == '') {?>
                  <img src="no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../../uploads/'.$nx["Photo2"])){?>
                 <img src="../uploads/<?php echo $nx["Photo2"];?>" class="img-fluid ui-w-40" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40"> 
             <?php } ?></td>
             <td><?php echo $nx['Name']; ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <?php if($_SESSION['Roll']==1) {?>
             <td><a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
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