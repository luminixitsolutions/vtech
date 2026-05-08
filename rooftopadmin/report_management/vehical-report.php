<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Vehical-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Vehical Report</title>
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

<?php include_once 'report-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Vehical Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

  

  <div class="form-group col-md-5">
                                            <label class="form-label">Executive</label>
                                            <select class="select2-demo form-control" name="ExeId" id="ExeId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN (1,5)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['ExeId']==$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


 <div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
         <thead>
            <tr>
               <th>Executive Name</th>
              
             
                <th>Date</th>
                 <th>Start Reading Photo</th>
                <th>Start KM</th>
                <th>End Reading Photo</th>
                <th>End KM</th>
                <th>Start Latitude</th>
              <th>Start Longitude</th>
              
              <th>End Latitude</th>
              <th>End Longitude</th>
              <!--<th>Address</th>-->
              
              <!--<th>Status</th>-->
              
               
            </tr>
        </thead>
        <tbody>
            <?php 
           
            $i=1;
            $sql = "SELECT ta.*,tu.Fname FROM tbl_vehicle_details ta INNER JOIN tbl_users tu ON tu.id=ta.Userid WHERE tu.Roll NOT IN (1,5)
                    ";
             if($_POST['ExeId']){
                $ExeId = $_POST['ExeId'];
                if($ExeId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ta.Userid='$ExeId'";
                }
            }       
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ta.CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql.= " AND ta.CreatedDate<='$ToDate'";
                }
            $sql.=" GROUP BY ta.CreatedDate ORDER BY tu.Fname";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT * FROM tbl_vehicle_details WHERE Type=1 AND CreatedDate='".$row['CreatedDate']."' AND UserId='".$row['UserId']."'";
                $row2 = getRecord($sql2);
                
                $sql3 = "SELECT * FROM tbl_vehicle_details WHERE Type=2 AND CreatedDate='".$row['CreatedDate']."' AND UserId='".$row['UserId']."'";
                $row3 = getRecord($sql3);
            ?>
            <tr>
                 <td><?php echo $row['Fname'];?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
               
               <td><?php if($row2["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row2["Photo"])){?>
                 <img src="../uploads/<?php echo $row2["Photo"];?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
               <td><?php echo $row2['StartKm'];?></td>
               
                <td><?php if($row3["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row3["Photo"])){?>
                 <img src="../uploads/<?php echo $row3["Photo"];?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
               <td><?php echo $row3['EndKm'];?></td>
               
                <td><?php echo $row2['Latitude'];?></td>
                <td><?php echo $row2['Longitude'];?></td>
                <td><?php echo $row3['Latitude'];?></td>
                <td><?php echo $row3['Longitude'];?></td>
                <!--<td><?php echo $row['Address'];?></td>-->
                 
                  
             <!--<td><?php echo $row['Status'];?></td>-->
            </tr>
            <?php } ?>
          
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
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

 
});
</script>
</body>
</html>
