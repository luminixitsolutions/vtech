<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "View-Sell";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
      window.location.href="view-service-module.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Service Complaint List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="choose-service-type.php?CustId=<?php echo $_GET['custid'];?>" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Raise New Complaint</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               
               <th>Complaint No</th> 
               
                <th>Customer Name</th> 
                <th>Contact No</th>
               
                <th>Address</th>
             
               <th>Service Related Issue</th>
               <th>Issue/Problems</th>
                <th>Service Type</th>
             
                <th>Status</th>
                <th>Complaint Date</th>
               
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tc.Name As IssueName,tb.Name As BranchName FROM tbl_service_complaint tp 
            LEFT JOIN tbl_issues tc ON tc.id=tp.Issue 
            LEFT JOIN tbl_branch tb ON tp.BranchId=tb.id WHERE tp.CustId='".$_GET['custid']."'";
            if($_REQUEST['ClainStatus']!=''){
                $sql.=" AND tp.ClainStatus='".$_REQUEST['ClainStatus']."'";
            }
            if($_REQUEST['ServiceType']!=''){
                $sql.=" AND tp.ServiceType='".$_REQUEST['ServiceType']."'";
            }
            if($_REQUEST['val']=='today'){
                $sql.=" AND tp.CreatedDate='".date('Y-m-d')."'";
            }
            $sql.=" ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               if($row['ServiceType'] == 'Insurance'){
                $sql2 = "SELECT * FROM tbl_common_master WHERE id='".$row['InsuranceComplaint']."'";
                $row2 = getRecord($sql2);
                $Problems = $row2['Name'];
               }
               else{
                $Problems = $row['Problem']; 
               }
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><a href="view-service-complaint-action.php?id=<?php echo $row['id']; ?>"><?php echo $row['TicketNo']; ?></a></td>
                
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                 <td><?php echo $row['Address']; ?></td>
                 <td><?php echo $row['RelatedIssue']; ?></td>
                 <td><?php echo $Problems; ?></td>
                 
            <td>
              
<?php echo $row['ServiceType']; ?>
             
            </td>
                  <td><?php echo $row['ClainStatus']; ?></td>
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ComplaintDate']))); ?></td>
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
                <?php if($row['ServiceType'] == 'Insurance'){?>
                    <a href="raise-insurance-customer-complaint.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
                      <?php } else {?>
              <a href="raise-customer-complaint.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a><?php } ?>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>&nbsp;&nbsp;
              <a href="print-service-complaint.php?id=<?php echo $row['id']; ?>" target="_blank"><i class="lnr lnr-printer text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
        
              
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
