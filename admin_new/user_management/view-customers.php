<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
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
    <?php include_once '../header_script.php'; ?>
  
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

            <?php include_once 'account-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <?php
                if ($_REQUEST["action"] == "delete") {
                    $id = $_REQUEST["id"];
                    $sql11 = "DELETE FROM tbl_users WHERE id = '$id'";
                    $conn->query($sql11);
                ?>
                    <script type="text/javascript">
                        alert("Deleted Successfully!");
                        window.location.href = "pump-customers.php";
                    </script>
                <?php } ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">View All Customer
                            <?php if (in_array("14", $Options)) { ?>
                                <span style="float: right;">
                                    <a href="add-customer.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
                        </h4>

                        <div class="card" style="padding: 10px;">

                            <div id="accordion2">
                                <div class="card mb-2">

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
                                                           
                                                            $q = "select * from tbl_scheme WHERE Status='1' ORDER BY Name ASC";
                                                            $r = $conn->query($q);
                                                            while ($rw = $r->fetch_assoc()) {
                                                            ?>
                                                                <option <?php if ($_REQUEST['SchemeId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
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


                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">From Date </label>
                                                        <input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">To Date</label>
                                                        <input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-3">
                                                        <label class="form-label">Survey Status </label>
                                                        <select class="form-control" id="SurveyMatch" name="SurveyMatch">
                                                        <option value="all" selected>All</option>
                                    <option value="1" <?php if($_POST['SurveyMatch']==1){ ?> selected <?php } ?> >Matched</option>
                                    <option value="2"  <?php if($_POST['SurveyMatch']==2){ ?> selected <?php } ?> >Not Matched</option>
                                                          
                                                        </select>
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
                                </div>
                            </div>

                            <div class="card-datatable table-responsive">
                               <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                <tr>
                    <th>Telephonic Survey Details</th>
                    <th>Field Survey Details</th>
                    <th>Survey Status</th>
                    <th>Status</th>
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Contact No</th>
                     <th>Taluka</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Address</th>
                     <th>Status</th>
                     <th>Source</th>
                     <th>Register Date</th>
                     <th>Action</th>
         
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

<?php include_once '../footer_script.php';?>

    <script>


        $(document).ready(function(){
         $.fn.myFunction = function(AgencyId,SchemeId,District,FromDate,ToDate,SurveyMatch,Search){ 
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
                    'url':'../pagination/pump-customer.php',
                    method: "POST",
                    data: {
                        AgencyId: AgencyId,
                        SchemeId:SchemeId,
                        District:District,
                        FromDate:FromDate,
                        ToDate:ToDate,
                        SurveyMatch:SurveyMatch
                    },
                },
                'columns': [
                    { data: 'SurveyDetails' },
                    { data: 'FieldSurveyDetails' },
                    { data: 'SurveyMatch' },
                    { data: 'CheckStatus' },
                    { data: 'BeneficiaryId' },
                    { data: 'Fname' },
                    { data: 'Phone' },
                    { data: 'Taluka' },
                    { data: 'Village' },
                    { data: 'District' },
                    { data: 'Address' },
                    { data: 'Status' },
                    { data: 'Source' },
                    { data: 'CreatedDate' },
                    { data: 'Action' },
                   
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
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
    $.fn.myFunction(AgencyId,SchemeId,District,FromDate,ToDate,SurveyMatch,"");

     $(document).on("click", "#submit", function(event){
        var AgencyId = $('#AgencyId').val();
        var SchemeId = $('#SchemeId').val();
        var District = $('#District').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var SurveyMatch = $('#SurveyMatch').val();
        var Search = "Search";
        $.fn.myFunction(AgencyId,SchemeId,District,FromDate,ToDate,SurveyMatch,Search);

        });
        });
        </script>
</body>

</html>