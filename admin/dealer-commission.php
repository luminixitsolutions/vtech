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
  $sql11 = "DELETE FROM wallet WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="dealer-commission.php";
    </script>
<?php } ?>

 <style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dealer Commission List

</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                 <tr>
              <th>Sr No</th>
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Dealer Name</th>
                <th>Dealer Code</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Pay</th>
                <th>Paid Amount</th>
                <th>Action</th>
     
               </tr>
         
                </thead>
                
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
 
    $(document).ready(function(){
         $.fn.myFunction = function(){ 
            
                var PageLength = 10;
         
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/dealer-commission.php',
                   
                },
                'columns': [
                    { data: 'id' },
                    { data: 'CustName' },
                    { data: 'Phone' },
                    { data: 'DealerName' },
                    { data: 'DealerCode' },
                    { data: 'CreatedDate' },
                    { data: 'Status' },
                    { data: 'Pay' },
                    { data: 'PaidAmt' },
                    { data: 'Action' }
                   
                ],
               
               "pageLength":PageLength,
                "bDestroy": true,
                "scrollX": true
            });
    }
    
    $.fn.myFunction();

     
        });
</script>
</body>
</html>
