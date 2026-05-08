<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installer";
$Page = "View-Contractor-Commission";
?> 
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Employee Account List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'account-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_rooftop_contractor_commision WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-contractor-commision.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Contractor Commission List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-contractor-commission.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Contractor Name</th>
                
                <th>Project Head</th>
                <th>Project Sub Head</th>

               
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            $sql = "SELECT tcc.*,tu.Fname,tc.Name AS HeadName,tp.Name AS SubHeadName FROM tbl_rooftop_contractor_commision tcc 
                    INNER JOIN tbl_users tu ON tu.id=tcc.UserId 
                    INNER JOIN tbl_rooftop_common_master tc ON tc.id=tcc.ProjectHeadId 
                    INNER JOIN tbl_rooftop_project_sub_head tp ON tp.id=tcc.ProjectSubHeadId GROUP BY tcc.UserId,tcc.ProjectHeadId,tcc.ProjectSubHeadId ORDER BY tcc.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
                <td><?php echo $i;?>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
                
                <td><?php echo $row['HeadName']; ?></td>
                <td><?php echo $row['SubHeadName']; ?></td>
             
             
           
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="add-contractor-commission.php?UserId=<?php echo $row['UserId']; ?>&ProjectHeadId=<?php echo $row['ProjectHeadId']; ?>&ProjectSubHeadId=<?php echo $row['ProjectSubHeadId']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?UserId=<?php echo $row['UserId']; ?>&ProjectHeadId=<?php echo $row['ProjectHeadId']; ?>&ProjectSubHeadId=<?php echo $row['ProjectSubHeadId']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
</div>
</div>


<?php include_once '../footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once '../footer_script.php'; ?>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>
</html>
