<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
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
<h4 class="font-weight-bold py-3 mb-0">Dealer Report
   
</h4>

<div class="card" style="padding: 10px;">

       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       


  <div class="form-group col-md-3">
<label class="form-label">Dealer</label>
 <select class="select2-demo form-control" name="DealerCode" id="DealerCode">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '9' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["DealerCode"] == $result['CustomerId']) {?> selected <?php } ?> value="<?php echo $result['CustomerId'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-3">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
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
               <th>#</th>
               <th>Dealer Name</th>
                <th>Customer Name</th>
               <th>Contact No</th>
                <!-- <th>Email Id</th> -->
                <th>Address</th>
                 <!-- <th>User Type</th> -->
                <th>Status</th>
              
                <th>Register Date</th>
              
            
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.*,tu2.Fname As DealerName FROM tbl_users tu 
                    LEFT JOIN tbl_users tu2 ON tu2.CustomerId=tu.DealerCode WHERE tu.Roll=5 ";
       
            if($_POST['DealerCode']){
                $DealerCode = $_POST['DealerCode'];
                if($DealerCode == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.DealerCode='$DealerCode'";
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
                <td><?php echo $i;?></td>
                <td><?php echo $row['DealerName']." (".$row['DealerCode'].")";?></td>
                <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
              <td><?php echo $row['Phone']."<br>".$row['Phone2']; ?></td>
                <!-- <td><?php echo $row['EmailId']; ?></td> -->
              
                  <td><?php echo $row['Address']; ?></td>
              
                   
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>

               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
            
           
              
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

    $('.common_selector').click(function(){
        var filter = [];
        $('.Proccedures:checked').each(function(){
            filter.push($(this).val());
        });
$('#AllCustId').val(filter);
    });
</script>

</body>
</html>
