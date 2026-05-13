<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "View-Lead";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    
   <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
    
  
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
     <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">View <?php echo $_REQUEST['ClainReason'];?> Lead List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-lead.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Create Lead</a></span><?php } ?></h5>
                        
 <style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>
 <?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_leads WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-leads.php";
    </script>
<?php } ?>
<br>
                    <div class="card mb-4">
                             <div class="card-datatable table-responsive">
                               <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                 <tr>
               <th>Sr No</th>
                <th>Ticket No</th> 
                <th>Source</th> 
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Address</th>
                <th>Lead Status</th>
                <th>Lead Date</th>
                <th>Action</th>
     
               </tr>
         
                </thead>
                
            </table>
                            </div>
                    </div>
                        

<input type="hidden" id="ClainReason" value="<?php echo $_GET['ClainReason'];?>">
<input type="hidden" id="ClaimStatus" value="<?php echo $_GET['ClainStatus'];?>">
<input type="hidden" id="Roll" value="<?php echo $Roll;?>">

					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/vfs_fonts.js"></script>
   <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>

    <script>


        $(document).ready(function(){
         $.fn.myFunction = function(ClaimReason,ClaimStatus,Roll){ 
            
                var PageLength = 10;
         
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/lead-customer.php',
                    method: "POST",
                    data: {
                        ClaimReason: ClaimReason,
                        ClaimStatus:ClaimStatus,
                        Roll:Roll
                    },
                   
                },
                'columns': [
                    { data: 'id' },
                    { data: 'TicketNo' },
                    { data: 'ClainReason' },
                    { data: 'CustName' },
                    { data: 'CellNo' },
                    { data: 'Address' },
                    { data: 'ClainStatus' },
                    { data: 'CreatedDate' },
                    { data: 'Action' },
                   
                ],
               
               "pageLength":PageLength,
                "bDestroy": true,
                "scrollX": true
            });
    }
    
   
   var ClaimReason = $('#ClainReason').val();
    var ClaimStatus = $('#ClaimStatus').val();
    var Roll = $('#Roll').val();
    $.fn.myFunction(ClaimReason,ClaimStatus,Roll);

     
        });
        </script>
</body>

</html>
