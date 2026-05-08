<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "View-Purchase-Order";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Purchase Order List</title>
 <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/vfs_fonts.js"></script>
   <script type="text/javascript" src="assets/js/datatables.min.js"></script>
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   
<style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">








<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_purchase_order WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_purchase_order_products WHERE SellId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);
  /* $sql11 = "DELETE FROM tbl_stocks WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);*/
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-purchase-order.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
 
<h4 class="font-weight-bold py-3 mb-0">View Products</h4>


<div class="card" style="padding: 10px;">
     
<div class="card-datatable table-responsive">
 <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                <tr>
              <th>Telephonic Survey Details</th>
                    <th>Field Survey Details</th>
                    <th>Survey Status</th>
                   
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Contact No</th>
                     <th>State</th>
                     <th>Taluka</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Address</th>
                     <th>Status</th>
                     <th>Source</th>
                     <th>Register Date</th>
                   
               </tr>
         
                </thead>
                
            </table>
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?>
         <script>


        $(document).ready(function(){
         $.fn.myFunction = function(AgencyId,SchemeId,District,FromDate,ToDate,SurveyMatch,Search,StateId){ 
             if(Search == ''){
                var PageLength = 10;
         }
         else{
            var PageLength = 5000;
         }
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/pump-customer.php',
                    method: "POST",
                    data: {
                        AgencyId: AgencyId,
                        SchemeId:SchemeId,
                        District:District,
                        FromDate:FromDate,
                        ToDate:ToDate,
                        SurveyMatch:SurveyMatch,
                        ProjectType:1,
                        StateId:StateId
                    },
                },
                'columns': [
                    { data: 'SurveyDetails' },
                    { data: 'FieldSurveyDetails' },
                    { data: 'SurveyMatch' },
                    
                    { data: 'BeneficiaryId' },
                    { data: 'Fname' },
                    { data: 'Phone' },
                    { data: 'State' },
                    { data: 'Taluka' },
                    { data: 'Village' },
                    { data: 'District' },
                    { data: 'Address' },
                    { data: 'Status' },
                    { data: 'Source' },
                    { data: 'CreatedDate' },
                   
                   
                ],
                
               "pageLength":PageLength,
                "bDestroy": true
            });
    }
    
    var AgencyId = $('#AgencyId').val();
    var SchemeId = $('#SchemeId').val();
    var District = $('#District').val();
    var FromDate = $('#FromDate').val();
    var ToDate = $('#ToDate').val();
    var SurveyMatch = $('#SurveyMatch').val();
    var StateId = $('#StateId').val();
    $.fn.myFunction(AgencyId,SchemeId,District,FromDate,ToDate,SurveyMatch,"",StateId);

     $(document).on("click", "#submit", function(event){
        var AgencyId = $('#AgencyId').val();
        var SchemeId = $('#SchemeId').val();
        var District = $('#District').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var SurveyMatch = $('#SurveyMatch').val();
        var StateId = $('#StateId').val();
        var Search = "Search";
        $.fn.myFunction(AgencyId,SchemeId,District,FromDate,ToDate,SurveyMatch,Search,StateId);

        });
        
         $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getDistrict";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
        console.log(data);
      $('#District').html(data);
    }
    });

 });
 
        });
        </script>
</body>
</html>
