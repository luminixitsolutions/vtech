<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Installation";
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

            <?php include_once 'installation-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

               
                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php echo $_GET['title'];?>
                           
                        </h4>

                        <div class="card" style="padding: 10px;">

                          
<input type="hidden" id="ProjectId" value="<?php echo $_GET['projid'];?>">
<input type="hidden" id="subheadid" value="<?php echo $_GET['subheadid'];?>">
<input type="hidden" id="roll" value="<?php echo $_GET['roll'];?>">
<input type="hidden" id="dist" value="<?php echo $_GET['dist'];?>">
<input type="hidden" id="val" value="<?php echo $_GET['val'];?>">



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
                    <th>Lattitude</th>
                    <th>Longitude</th>
                    
         
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
         $.fn.myFunction = function(ProjectId,roll,dist,val,subheadid){ 
            var PageLength = 5000;
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/total-abstarct-beneficiary.php',
                    method: "POST",
                    data: {
                        ProjectId: ProjectId,
                        roll:roll,
                        dist:dist,
                        val:val,
                        subheadid:subheadid
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
                    { data: 'Lattitude' },
                    { data: 'Longitude' },
                    
                   
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
               "pageLength":PageLength,

                "scrollX": true,
                "bDestroy": true
            });
    }
    
    var ProjectId = $('#ProjectId').val();
    var subheadid = $('#subheadid').val();
    var roll = $('#roll').val();
    var dist = $('#dist').val();
     var val = $('#val').val();
    $.fn.myFunction(ProjectId,roll,dist,val,subheadid);

    $(document).on("click", "#submit", function(event){
       var ProjectId = $('#ProjectId').val();
       var subheadid = $('#subheadid').val();
    var roll = $('#roll').val();
    var dist = $('#dist').val();
    var val = $('#val').val();
   
    $.fn.myFunction(ProjectId,roll,dist,val,subheadid);

        });
        });
        </script>
</body>

</html>