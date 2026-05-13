<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Dashboard";
$Page = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | Pump Customer Account List</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
       <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="assets/js/vfs_fonts.js"></script>
   <script type="text/javascript" src="assets/js/datatables.min.js"></script>
  
</head>
<style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>
<body>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'rooftop-installation-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

               
                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php echo $_GET['title'];?>
                           
                        </h4>

                        <div class="card" style="padding: 10px;">

                             <div id="accordion2">
                                <div class="card mb-2">

                                    <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                        <div class="" style="padding:5px;">
                                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                  
                                             <div class="form-group col-md-3">      
                                        <label class="form-label">District </label>
                                            <select class="select2-demo form-control" id="District" name="District" required="">
<option selected="" value="all">All District</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select DISTINCT(District) As District from tbl_users WHERE District!='' ORDER BY District ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                                   
                                                  
                                                    
                                                    <input type="hidden" name="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:25px;">
                                                        <button type="button" id="submit" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_POST['Search'])) { ?>
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
<input type="hidden" id="StateId" value="<?php echo $_GET['stateid'];?>">

<input type="hidden" id="FieldSurveyDetails" value="<?php echo $_GET['FieldSurveyDetails'];?>">

                            <div class="card-datatable table-responsive">
                               <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                <tr>
                    <th>Project Type</th>
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Contact No</th>
                     <th>Taluka</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Address</th>
                    <th>Register Date</th>
         
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


  <script src="assets/js/sidenav.js"></script>
<script src="assets/js/layout-helpers.js"></script>
<script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/js/demo.js"></script>
<script src="assets/libs/select2/select2.js"></script>
<script src="assets/js/pages/forms_selects.js"></script>

    <script>


        $(document).ready(function(){
         $.fn.myFunction = function(StateId,FieldSurveyDetails,District){ 
            var PageLength = 5000;
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/rooftop-total-customers.php',
                    method: "POST",
                    data: {
                        StateId: StateId,
                        FieldSurveyDetails:FieldSurveyDetails,
                        District:District
                    },
                },
                'columns': [
                    { data: 'ProjectType' },
                    { data: 'BeneficiaryId' },
                    { data: 'Fname' },
                    { data: 'Phone' },
                    { data: 'Taluka' },
                    { data: 'Village' },
                    { data: 'District' },
                    { data: 'Address' },
                    { data: 'CreatedDate' },
                   
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
               "pageLength":PageLength,
               "scrollY": "500px",
                "scrollX": true,
                "bDestroy": true
            });
    }
    
    var StateId = $('#StateId').val();
    var FieldSurveyDetails = $('#FieldSurveyDetails').val();
    var District = $('#District').val();
    $.fn.myFunction(StateId,FieldSurveyDetails,District);

    $(document).on("click", "#submit", function(event){
        var District = $('#District').val();
        var StateId = $('#StateId').val();
    var FieldSurveyDetails = $('#FieldSurveyDetails').val();
        $.fn.myFunction(StateId,FieldSurveyDetails,District);

        });
        });
        </script>
</body>

</html>