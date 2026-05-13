<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$CatId = $_POST["CatId"];
$SubCatId = $_POST["SubCatId"];
$AttrNameId = $_POST["AttrNameId"];
$query = "SELECT * FROM attribute_value WHERE Name = '$Name' AND AttrNameId='$AttrNameId' AND CatId='$CatId'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO attribute_value SET CatId='$CatId',SubCatId='$SubCatId',Name = '$Name',AttrNameId='$AttrNameId',Status='$Status'";
	$conn->query($qx);
	echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM attribute_value WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$AttrNameId = $_POST["AttrNameId"];
$CatId = $_POST["CatId"];
$SubCatId = $_POST["SubCatId"];
$query = "SELECT * FROM attribute_value WHERE Name = '$Name' AND AttrNameId='$AttrNameId' AND CatId='$CatId' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE attribute_value SET CatId='$CatId',SubCatId='$SubCatId',Name = '$Name',AttrNameId='$AttrNameId',Status='$Status' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM attribute_value WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }


  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
               <<!-- th>Category</th> -->
                <!-- <th>Sub Category</th> -->
              <th>Attribute Name</th>
               <th>Attribute Value</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT sb.Name As AttrValue,ct.Name As Category,c.Name As AttrName,sb.id As Avid,sb.Status As AVstatus FROM attribute_value sb LEFT JOIN attribute_name c ON c.id=sb.AttrNameId
  LEFT JOIN category ct ON ct.id=sb.CatId
 ORDER BY sb.id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
              <!-- <td><?php echo $nx['Category']; ?></td> -->
          <!--       <td><?php echo $nx['SubCategory']; ?></td> -->
             <td><?php echo $nx['AttrName']; ?></td>
              <td><?php echo $nx['AttrValue']; ?></td>
             <td><?php if($nx['AVstatus']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <td><a data-id="<?php echo $nx['Avid']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['Avid']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
             </td>
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