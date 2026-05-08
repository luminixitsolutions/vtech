<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "Assign-Lead";
//echo "<pre>";print_r($_SESSION["cart_item"]);
//unset($_SESSION["cart_item"]);
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
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
  
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
                        <h5 class="font-weight-bold py-3 mb-0">Assign Leads</h5>
                        
 <style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>

<?php 
    if(isset($_POST['submit'])){
        $ExeId = $_POST['ExeId'];
        foreach ($_SESSION["cart_item"] as $product){
            $LeadId = $product['id'];
            $sql = "UPDATE tbl_leads SET AllocateId='$ExeId' WHERE id='$LeadId'";
                $conn->query($sql);
        }
        echo "<script>alert('Lead Assigned successfully to telecaller');window.location.href='assign-leads.php';</script>";
    }
unset($_SESSION["cart_item"]);
?>
                    <div class="card mb-4" style="padding: 10px;">

                             <div class="card-datatable table-responsive">
                               <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                 <tr>
               <th>#</th>
                <th>Ticket No</th> 
                <th>Source</th> 
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Address</th>
                <th>Lead Status</th>
                <th>Lead Date</th>
               
     
               </tr>
         
                </thead>
                
            </table>
                            </div>
<form id="validation-form" method="post" enctype="multipart/form-data" action="">
                            <div class="form-row">
 <div class="form-group col-lg-4">
<label class="form-label"> Telecaller<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ExeId" id="ExeId" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=2";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
</div>

</div>
</form>

                    </div>
                        



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
    <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>

    <script>
         function saveCart(id){
     var action = "saveCart";
     var quantity = 1;
      $.ajax({
      url:"assign-lead-session.php",
      type:"POST",
      data:{action:action,quantity:quantity,id:id},
      success:function(data){
          //alert(data);
      },

    });
 }

function featured(id){
if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            saveCart(id);
        }
        else{
           $('#CheckId'+id).val(0);
           delete_prod(id);
            }
        }

        $(document).ready(function(){
         $.fn.myFunction = function(){ 
            
                var PageLength = 10;
         
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/assign-lead-customer.php',
                   
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
