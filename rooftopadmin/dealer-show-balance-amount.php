<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Dealer-Commission";
$Page = "Dealer-Commission";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Dealer Commission List</title>
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
  $sql11 = "DELETE FROM tbl_show_dealer_comm_amt WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="dealer-show-balance-amount.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dealer Commission Showing Amount

</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Dealer Name</th>
                <th>Dealer Code</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Add</th>
                <th>Show Amount</th>
                <th>Action</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.*,tu2.CustomerId As Dealer_Code,tu2.Fname As DealerName,tu2.id As DealerId FROM tbl_users tu INNER JOIN tbl_users tu2 ON tu.DealerCode=tu2.CustomerId WHERE tu.Roll=5
            
            ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT * FROM tbl_delivered_invoice WHERE CustId='".$row['id']."'";
                $rncnt2 = getRow($sql2);
                
                $sql22 = "SELECT * FROM tbl_show_dealer_comm_amt WHERE CustId='".$row['id']."' AND DealerId='".$row['DealerId']."'";
                $rncnt22 = getRow($sql22);
                $row22 = getRecord($sql22);

             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['Fname']; ?></td> 
               
                <td><?php echo $row['Phone']; ?></td>
                 <td><?php echo $row['DealerName']; ?></td>
                 <td><?php echo $row['Dealer_Code']; ?></td>
             <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
                 <td><?php if($rncnt2 > 0){echo "<span style='color:green;'>Completed</span>";} else { echo "<span style='color:red;'>In Progress</span>";} ?></td>
               
               
            
           
        </td>
        <td><?php if($rncnt22 > 0){?>
        <a href="edit-show-commission-amount.php?id=<?php echo $row22['id']; ?>" class="btn btn-success btn-round">Edit Amt</a>
        <?php } else {?><a href="show-commission-amount.php?cid=<?php echo $row['id']; ?>" class="btn btn-secondary btn-round">Add Amt</a><?php } ?></td>

        <td><?php echo $row22['Amount'];?></td>
            <?php 
            
            if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php
              if($rncnt22 > 0){
               if(in_array("10", $Options)){?>
              <a href="edit-commission-amount.php?id=<?php echo $row22['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row22['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
             <?php } } ?>
            </td> <?php }  ?>
        
              
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
    });
});
</script>
</body>
</html>
