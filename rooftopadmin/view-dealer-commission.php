<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Dealer-Commission";
$Page = "View-Dealer-Commission";
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


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Dealer Commission Amount
  
</h4>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_dealer_general_ledger WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-dealer-commission.php";
    </script>
<?php } ?>

<div class="card" style="padding: 10px;">
   
   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Action</th>
                 <th>Voucher No</th>
                 
                 <th>Payment Date</th>
                <th>Dealer Name</th>
                <th>Customer Name</th>
              
                <th>Amount</th>
                
                <th>Payment Mode</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT td.*,tu.Fname AS DealerName,tu2.Fname AS CustName FROM tbl_dealer_general_ledger td 
            INNER JOIN tbl_users tu ON tu.id=td.DealerId 
            INNER JOIN tbl_users tu2 ON tu2.id=td.UserId WHERE td.Type='PR'";
                  
             
              
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND td.PaymentDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND td.PaymentDate<='$ToDate'";
            }
            $sql.= " ORDER BY td.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
            ?>
            <tr style="<?php echo $bcolor;?>">
               
              <td><?php echo $i;?></td>
              <td> 
                   <a href="add-dealer-commission.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;

<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
            
               </td>
               <td><?php echo $row['Code']; ?></td>
              
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaymentDate']))); ?></td>
           
                 <td><?php echo $row['DealerName']; ?></td>
                  <td><?php echo $row['CustName']; ?></td>
            
        <td>₹<?php echo number_format($row['Amount'],2); ?></td>
    
                 <td><?php echo $row['PayMode']; ?></td>
               
               
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
