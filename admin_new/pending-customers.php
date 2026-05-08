<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Delivery-Products";
$Page = "Pending-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pending Customers
    
</h4>

<div class="card" style="padding: 10px;">

    

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <!-- <th>Photo</th> -->
                <th>Customer Name</th>
               <th>Contact No</th>
                <!-- <th>Email Id</th> -->
                <th>Taluka</th>
                 <!-- <th>User Type</th> -->
                <th>Status</th>
                 
                <th>Register Date</th>
              
             
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if($Roll == 1 || $Roll == 7){
            $sql = "SELECT tu.*,tut.Name As User_Type FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tu.UserType=tut.id WHERE tu.Roll=5 AND tu.SurveyDetails=0";
        }
        else{
            $sql = "SELECT tu.*,tut.Name As User_Type FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tu.UserType=tut.id WHERE tu.Roll=5 AND tu.BranchId='$BranchId' AND tu.SurveyDetails=0";
        }
            if($_POST['UserType']){
                $UserType = $_POST['UserType'];
                if($UserType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.UserType='$UserType'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tu.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tu.CreatedDate<='$ToDate'";
            }
            $sql.= " ORDER BY tu.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
             ?>
            <tr>
               <!-- <td> <?php if($row["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-100 rounded-circle"  style="width: 100px;height: 100px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="d-block ui-w-100 " alt="" style="width: 100px;height: 100px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-100 rounded-circle" style="width: 100px;height: 100px;"> 
             <?php } ?></td> -->
                <td><a href="customer-profile.php?id=<?php echo $row['id']; ?>" target="_new"><?php echo $row['Fname']." ".$row['Lname']; ?></a></td>
              
              <td><?php echo $row['Phone']."<br>".$row['Phone2']; ?></td>
                <!-- <td><?php echo $row['EmailId']; ?></td> -->
              
                  <td><?php echo $row['Taluka']; ?></td>
              
                 
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>

               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
            
           
              
            </tr>
           <?php }  ?>
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
