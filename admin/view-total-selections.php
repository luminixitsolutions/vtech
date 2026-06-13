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
    <title><?php echo $Proj_Title; ?> | Pump Customer Account List</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery.min.js"></script>
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
<input type="hidden" id="ProjectId" value="<?php echo $_GET['projid'];?>">
<input type="hidden" id="SubHeadId" value="<?php echo $_GET['subheadid'];?>">
<input type="hidden" id="FieldSurveyDetails" value="<?php echo $_GET['FieldSurveyDetails'];?>">

                            <div class="card-datatable table-responsive">
                               <table id='empTable' class='table table-striped table-bordered display dataTable'>
                <thead>
                <tr>
                    <th>Work Done</th>
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

<div class="modal fade" id="workOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Work Order Done</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="workOrderModalAlert" class="alert alert-danger d-none"></div>
                <p class="mb-2"><strong>Beneficiary:</strong> <span id="woBeneficiaryId"></span></p>
                <p class="mb-3"><strong>Customer:</strong> <span id="woCustomerName"></span></p>
                <input type="hidden" id="woCustId" value="">
                <div class="form-group">
                    <label class="form-label">Work Order Done <span class="text-danger">*</span></label>
                    <select class="form-control" id="woWorkOrderDone" required>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
                <div class="form-group" id="woDateGroup">
                    <label class="form-label">Work Order Done Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="woWorkOrderDoneDate">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="woSaveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

  <script src="assets/js/sidenav.js"></script>
<script src="assets/js/layout-helpers.js"></script>
<script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/js/demo.js"></script>
<script src="assets/libs/select2/select2.js"></script>
<script src="assets/js/pages/forms_selects.js"></script>
<script src="<?php echo $SiteUrl; ?>/assets/libs/popper/popper.js"></script>
<script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.js"></script>

    <script>


        $(document).ready(function(){
         $.fn.myFunction = function(ProjectId,FieldSurveyDetails,District,SubHeadId){ 
            var PageLength = 5000;
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/total-customers.php',
                    method: "POST",
                    data: function(d) {
                        d.ProjectId = ProjectId;
                        d.FieldSurveyDetails = FieldSurveyDetails;
                        d.District = District || 'all';
                        d.SubHeadId = SubHeadId;
                    },
                },
                'columns': [
                    {
                        data: 'WorkOrderAction',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return data;
                        }
                    },
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
                columnDefs: [
                    { targets: 0, orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
               "pageLength":PageLength,
               "scrollY": "500px",
               "scrollX": true,
               "bDestroy": true,
               "order": [[2, 'asc']]
            });
    }

    function toggleWorkOrderDateField() {
        if ($('#woWorkOrderDone').val() === 'Yes') {
            $('#woDateGroup').show();
            $('#woWorkOrderDoneDate').prop('required', true);
        } else {
            $('#woDateGroup').hide();
            $('#woWorkOrderDoneDate').prop('required', false).val('');
        }
    }

    function showWorkOrderAlert(message) {
        $('#workOrderModalAlert').removeClass('d-none').text(message);
    }

    function clearWorkOrderAlert() {
        $('#workOrderModalAlert').addClass('d-none').text('');
    }

    $(document).on('click', '.btn-update-work-order', function() {
        var custId = $(this).data('cust-id');
        clearWorkOrderAlert();
        $('#woCustId').val(custId);
        $.ajax({
            url: 'ajax_files/ajax_work_order_customer.php',
            type: 'POST',
            dataType: 'json',
            data: { action: 'get', cust_id: custId },
            success: function(res) {
                if (!res.success) {
                    alert(res.message || 'Unable to load work order details.');
                    return;
                }
                if (!res.can_edit) {
                    alert(res.message || 'Work Order Done update is not available. Please run migration add_work_order_done_to_tbl_installations.php');
                    return;
                }
                $('#woBeneficiaryId').text(res.data.BeneficiaryId || '');
                $('#woCustomerName').text(res.data.Fname || '');
                $('#woWorkOrderDone').val(res.data.WorkOrderDone === 'Yes' ? 'Yes' : 'No');
                $('#woWorkOrderDoneDate').val(res.data.WorkOrderDoneDate || '');
                if (res.has_date) {
                    $('#woDateGroup').show();
                } else {
                    $('#woDateGroup').hide();
                }
                toggleWorkOrderDateField();
                if (typeof $.fn.modal === 'function') {
                    $('#workOrderModal').modal('show');
                } else {
                    $('#workOrderModal').addClass('show').css('display', 'block');
                }
            },
            error: function() {
                alert('Unable to load work order details.');
            }
        });
    });

    $('#woWorkOrderDone').on('change', toggleWorkOrderDateField);

    $('#woSaveBtn').on('click', function() {
        clearWorkOrderAlert();
        var custId = $('#woCustId').val();
        var workOrderDone = $('#woWorkOrderDone').val();
        var workOrderDoneDate = $('#woWorkOrderDoneDate').val();
        if (workOrderDone === 'Yes' && $('#woDateGroup').is(':visible') && !workOrderDoneDate) {
            showWorkOrderAlert('Please select work order done date.');
            return;
        }
        $.ajax({
            url: 'ajax_files/ajax_work_order_customer.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'save',
                cust_id: custId,
                WorkOrderDone: workOrderDone,
                WorkOrderDoneDate: workOrderDoneDate
            },
            success: function(res) {
                if (!res.success) {
                    showWorkOrderAlert(res.message || 'Save failed.');
                    return;
                }
                if (typeof $.fn.modal === 'function') {
                    $('#workOrderModal').modal('hide');
                } else {
                    $('#workOrderModal').removeClass('show').css('display', 'none');
                }
                if ($.fn.DataTable.isDataTable('#empTable')) {
                    $('#empTable').DataTable().ajax.reload(null, false);
                }
            },
            error: function() {
                showWorkOrderAlert('Save failed. Please try again.');
            }
        });
    });
    
    var ProjectId = $('#ProjectId').val();
    var SubHeadId = $('#SubHeadId').val();
    var FieldSurveyDetails = $('#FieldSurveyDetails').val();
    var District = $('#District').val();
    $.fn.myFunction(ProjectId,FieldSurveyDetails,District,SubHeadId);

    $(document).on("click", "#submit", function(event){
        var District = $('#District').val();
        var ProjectId = $('#ProjectId').val();
    var FieldSurveyDetails = $('#FieldSurveyDetails').val();
    var SubHeadId = $('#SubHeadId').val();
        $.fn.myFunction(ProjectId,FieldSurveyDetails,District,SubHeadId);

        });
        });
        </script>
</body>

</html>