<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Pump-Survey";
$Page = "Field-Survey";
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
    
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
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

            <?php include_once 'header.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Co-ordinator Survey Customers  </h5>
                        
 <style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>

<br>
                    <div class="card mb-4" style="padding: 10px;">
                             <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                        <div class="" style="padding:5px;">
                                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">Gov Agency </label>
                                                        <select class="form-control" id="AgencyId" name="AgencyId">
                                                        <option value="all" selected>All</option>
                                                            <?php
                                                           
                                                            $q = "select Fname,id from tbl_users WHERE Roll=11 ORDER BY Fname ASC";
                                                            $r = $conn->query($q);
                                                            while ($rw = $r->fetch_assoc()) {
                                                            ?>
                                                                <option <?php if ($_REQUEST['SchemeId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">Yojana </label>
                                                        <select class="form-control" id="SchemeId" name="SchemeId">
                                                        <option value="all" selected>All</option>
                                                            <?php
                                                           
                                                            $q = "select * from tbl_rooftop_scheme WHERE Status='1' ORDER BY Name ASC";
                                                            $r = $conn->query($q);
                                                            while ($rw = $r->fetch_assoc()) {
                                                            ?>
                                                                <option <?php if ($_REQUEST['SchemeId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    
                                                      <!--<div class="form-group col-md-2">
                                                        <label class="form-label">Survey Status </label>
                                                        <select class="form-control" id="SurveyMatch" name="SurveyMatch">
                                                        <option value="all" selected>All</option>
                                    <option value="1" <?php if($_POST['SurveyMatch']==1){ ?> selected <?php } ?> >Matched</option>
                                    <option value="2"  <?php if($_POST['SurveyMatch']==2){ ?> selected <?php } ?> >Not Matched</option>
                                                          
                                                        </select>
                                                    </div>-->

                                                    <div class="form-group col-md-2">
                                                        <label class="form-label">From Date </label>
                                                        <input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label class="form-label">To Date</label>
                                                        <input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
                                                    </div>
                                                    <input type="hidden" name="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:20px;">
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

                            <div class="card-datatable table-responsive">
                               <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                 <tr>
                    <th>Field Survey Details</th>
                    <th>Consumer No</th>
                    <th>Customer Name</th>
                    <th>Contact No</th>
                    <th>Taluka</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Register Date</th>
                </tr>
            </thead>
        </table>
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
    <script  src="<?php echo $SiteUrl;?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/vfs_fonts.js"></script>
   <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>

   <script>


        $(document).ready(function(){
         $.fn.myFunction = function(AgencyId,SchemeId,SurveyMatch,FromDate,ToDate){ 
            
                var PageLength = 10;
         
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/field-survey.php',
                    method: "POST",
                    data: {
                        AgencyId: AgencyId,
                        SchemeId:SchemeId,
                        FromDate:FromDate,
                        ToDate:ToDate,
                        SurveyMatch:SurveyMatch,
                        ProjectType:2
                    },
                   
                },
                'columns': [
                    { data: 'SurveyDetails' },
                    { data: 'BeneficiaryId' },
                    { data: 'Fname' },
                    { data: 'Phone' },
                    { data: 'Taluka' },
                    { data: 'Village' },
                    { data: 'District' },
                    { data: 'Address' },
                    { data: 'Status' },
                    { data: 'CreatedDate' },
                   
                ],
                "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ],
               "pageLength":PageLength,
                "bDestroy": true
            });
    }
    
   

    var AgencyId = $('#AgencyId').val();
    var SchemeId = $('#SchemeId').val();
    var SurveyMatch = $('#SurveyMatch').val();
    var FromDate = $('#FromDate').val();
    var ToDate = $('#ToDate').val();
    $.fn.myFunction(AgencyId,SchemeId,SurveyMatch,FromDate,ToDate);

     $(document).on("click", "#submit", function(event){

        var AgencyId = $('#AgencyId').val();
        var SchemeId = $('#SchemeId').val();
        var SurveyMatch = $('#SurveyMatch').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        $.fn.myFunction(AgencyId,SchemeId,SurveyMatch,FromDate,ToDate);

        });

     
        });
        </script>
</body>

</html>
