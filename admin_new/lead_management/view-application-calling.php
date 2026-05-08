<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$MainPage = "Feedback";
$Page = "Product-Feedback";
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
                        <h5 class="font-weight-bold py-3 mb-0">View Application List</h5>
                        


<br>
                   <div class="card" style="padding: 10px;">
                        <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       <input type="hidden" id="Roll" value="<?php echo $Roll;?>">

 

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
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
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
                              <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                 <tr>
               <th>#</th>
               <th>Customer Name</th>
                <th>Contact No</th>
                
              <th>Last Telecaller Name</th>
                <th>Last Calling Date</th>
               <th>Last Talk</th>
               <th>Call After Date</th>
               <th>Time</th>
                <th>Feedback</th>
                
                
            </tr>
            </thead>
        <tbody>
            <?php 
            $i=1;
            if($Roll == 1 || $Roll == 7){
             $sql = "SELECT ts.* FROM tbl_mp_pump_applications ts 
            WHERE ts.Status=1";
            }
            else{
                 $sql = "SELECT ts.* FROM tbl_mp_pump_applications ts 
            WHERE ts.Status=1 AND ts.CoordinatorId='$user_id'";
            }
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.created_at>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.created_at<='$ToDate'";
            }
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql3 = "SELECT tpf.*,tu.Fname,tu.Lname FROM tbl_application_lead_details tpf LEFT JOIN tbl_users tu ON tpf.CreatedBy=tu.id WHERE tpf.CompId='".$row['id']."' AND tpf.CreatedDate='".date('Y-m-d')."' ORDER BY tpf.id DESC LIMIT 1";
                $rncnt3 = getRow($sql3);
                $row3 = getRecord($sql3);
                if($rncnt3 > 0){
                    $bcolor = "background-color: antiquewhite;";
                }
                else{
                    $bcolor = "";
                }
                $action = '<a href="javascript:void(0)" onclick="getFeedback('.$row['id'].')" class="btn btn-primary btn-finish" style="padding: 0.5px 1rem">Open</a>';
        if($row3['CreatedDate'] == '' || $row3['CreatedDate'] == '0000-00-00'){ $createddate="";} else {$createddate= date("d/m/Y", strtotime(str_replace('-', '/',$row3['CreatedDate'])));}

        if($row3['NextDate'] == '' || $row3['NextDate'] == '0000-00-00'){ $nextdate="";} else {$nextdate= date("d/m/Y", strtotime(str_replace('-', '/',$row3['NextDate'])));}
        
        
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['applicant_name']; ?></td> 
                 <td>By <?php echo $row['mobile']; ?> </td> 
             <td><?php echo $row3['Fname']." ".$row3['Lname']; ?></td> 
              
                <td><?php echo $createddate; ?></td>
                 <td><?php echo $row3['Message']; ?></td>
                <td><?php echo $nextdate; ?></td>
            <td><?php echo $row3['NextTime']; ?></td>
          <td><?php echo $action;?></td>
        
              
            </tr>
           <?php $i++;} ?>
         
                </tbody>
                
            </table>
                            </div>
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

    <script>

$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true

    });
});

       /* $(document).ready(function(){
         $.fn.myFunction = function(Roll,FromDate,ToDate){ 
            
                var PageLength = 10;
         
         $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pagination/application-calling-customers.php',
                   method: "POST",
                    data: {
                        Roll: Roll,
                      
                        FromDate:FromDate,
                        ToDate:ToDate
                    },
                },
                'columns': [
                    { data: 'id' },
                    { data: 'CustName' },
                    { data: 'CellNo' },
                    
                    { data: 'TeleCallerName' },
                    { data: 'CallingDate' },
                    { data: 'Talk' },
                    { data: 'AfterDate' },
                    { data: 'Time' },
                    { data: 'Feedback' }
                    
                   
                ],
               
               "pageLength":PageLength,
                "bDestroy": true,
                "scrollX": true
            });
    }
    
   
    var Roll = $('#Roll').val();
    //var ClainReason = $('#ClainReason').val();
    var FromDate = $('#FromDate').val();
    var ToDate = $('#ToDate').val();
    $.fn.myFunction(Roll,FromDate,ToDate);

    $(document).on("click", "#submit", function(event){
        var Roll = $('#Roll').val();
        //var ClainReason = $('#ClainReason').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        $.fn.myFunction(Roll,FromDate,ToDate);

        });

     
        });
*/
        function getFeedback(id){
    setTimeout(function() {
        window.open(
            'take-application-action.php?qid=' + id, 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 }
        </script>
</body>

</html>
