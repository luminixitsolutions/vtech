<?php 
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['Admin']['id'];

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_sub_category WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}
  if($_POST['action'] == 'delete') {
   
    $id = $_POST['id'];
    $query2 = "SELECT Photo FROM tbl_sub_category WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
    $query = "DELETE FROM tbl_sub_category WHERE id = '$id'";
    $conn->query($query);
    $src = "../../uploads/$Photo";
      unlink($src);
    echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_sub_category SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Photo Delete Successfully";
} 

if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_sub_category SET Photo2='' WHERE id=$id";
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
              <th>Category</th>
              <th>Brand</th>
               <th>Status</th>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT tc.*,tc2.Name As Category FROM tbl_sub_category tc LEFT JOIN tbl_category tc2 ON tc.CatId=tc2.id
          ORDER BY tc.id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
if($nx['Period'] == 1){
      $Period = "Month";
    }
    else{
      $Period = "Year";
    }
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
             <td><?php echo $nx['Category']; ?></td>

             <td><?php echo $nx['Name']; ?></td>
            
             
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
             <td>
              <?php if(in_array("10", $Options)){?>
              <a href='add-subcategory.php?id=<?php echo $nx['id']; ?>' title="Edit" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
              <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
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