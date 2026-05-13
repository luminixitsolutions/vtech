<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "Service-Beneficiary-List";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_service_complaint WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="beneficiary-service-lists.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Beneficiary Service List
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               
              <th>Project Head</th> 
              <th>Project Sub Head</th> 
                <th>Customer Name</th> 
                <th>Contact No</th>
               
                <th>Address</th>
                <th>Installation Date</th>
                <th>Warranty Date</th>
                <th>View Complaints</th>
                <th>Raise Complaints</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ti.*,tcm.Name AS ProjectHead,tps.Name AS SubProjectHead FROM tbl_installations ti 
                    INNER JOIN tbl_users tu ON ti.CustId=tu.id 
                    LEFT JOIN tbl_common_master tcm ON tcm.id=tu.ProjectId 
                    LEFT JOIN tbl_project_sub_head tps ON tps.id=tu.ProjectSubHeadId 
                    WHERE ti.WarrantyReg='Yes' AND tu.ProjectType=1 ORDER BY ti.CustName ASC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['ProjectHead']; ?></td> 
               <td><?php echo $row['SubProjectHead']; ?></td> 
              
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                 <td><?php echo $row['Address']; ?></td>
               
           
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InstallationDate']))); ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['WarrantyRegDate']))); ?></td>
          
            <td><a href="view-customer-complaints.php?custid=<?php echo $row['CustId']; ?>">View</a></td>
             <td><a href="choose-service-type.php?CustId=<?php echo $row['CustId']; ?>">Raise New Complaint</a></td>
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>
</html>
