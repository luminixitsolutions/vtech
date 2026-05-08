<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container" style="background-color: #f1f1f1;">



<?php  
  $id = $_GET['id'];
  $sql = "SELECT * FROM tbl_trip_details WHERE id='$id'";
  $row = getRecord($sql);
?>


<div class="container">
    <h4 class="font-weight-bold py-3 mb-0" style="line-height: 0px;">Diesel Amount <span style="float: right;">
<button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#modals-default" id="add_button"><i class="ion ion-md-add mr-2"></i> Add</button></span></h4>
<br>
    <p>(<strong>Trip Details : </strong> <?php echo $row['TripDetails'];?>)</p>

    <div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light">Diesel Amount</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 
<input type="hidden" name="TripId" id="TripId" value="<?php echo $_GET['id'];?>"/> 
<input type="hidden" name="DriverId" id="DriverId" value="<?php echo $row['DriverId'];?>"/> 

 <div class="form-row">
<div class="form-group col">
<label class="form-label">Trip Details <span class="text-danger">*</span></label>
<input type="text" class="form-control" placeholder="Name" value="<?php echo $row['TripDetails'];?>" readonly>
<div class="clearfix"></div>
</div>
</div>

  <div class="form-row">
<div class="form-group col">
<label class="form-label">Diesel Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount" class="form-control" id="Amount" placeholder="Amount" value="" required>
<div class="clearfix"></div>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="CrDate" class="form-control" id="CrDate" placeholder="" value="<?php echo date('Y-m-d');?>" required>
<div class="clearfix"></div>
</div>
</div>

 <div class="form-row">
<div class="form-group col">
<label class="form-label">Narration <span class="text-danger">*</span></label>
<input type="text" name="Narration" class="form-control" id="Narration" placeholder="Narration" value="" required>
<div class="clearfix"></div>
</div>
</div>


</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger" id="submit" name="submit">Submit</button>
</div>
</form>
</div>
</div>

    <?php 
           
            $sql = "SELECT * FROM tbl_diesel_amount WHERE TripId='$id'";
            $sql.= " ORDER BY id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {

                
             ?>
<div class="card mb-4">

                    <div class="card-body">
                        <h6 style="margin-bottom: 1px;">&#8377;<?php echo $row['Amount']; ?></h6>
                      
                      
                        <p style="margin-bottom: 1px;"><strong>In Date :</strong> <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CrDate']))); ?> <br> <strong>Narration :</strong> <?php echo $row['Narration']; ?></p>
                       
                    
                                                       
                    </div>
                </div>
                <?php } ?>
                </div>








<?php include_once 'footer.php'; ?>

</div>

</main>
<br><br>
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
<script type="text/javascript">
function product_lists(){
  var TripId = $('#TripId').val();
  window.location.href="add-diesel.php?id="+TripId;
    }

     $(document).ready(function() {


      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Diesel Amount</span>");  
           $('#action').val("Add");  
           $('#id').val('');

          $('#Amount').val('');
          $('#Narration').val('');
          $('#Photo').val('');
          $('#OldPhoto').val(''); 
          $('#show_photo').hide();
          $('#Status').attr("selected","selected").val(1);
          $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var action = $('#action').val();
    if ($('#validation-form').valid()){ 
         $.ajax({  
                url :"ajax_files/ajax_diesel_amount.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                    if(data == 1){
                      if(action == 'Edit'){
                        toastr.success('Diesel Amount Update Successfully!', 'Success', {timeOut: 5000});
                      }
                      else{
                     toastr.success('New Diesel Amount Added Successfully!', 'Success', {timeOut: 5000});
                      }
                      $('.insert_frm').modal('hide'); 
                    }
                    else{
                       toastr.error('Diesel Amount Already Exists', 'Error', {timeOut: 5000});
                      $('.insert_frm').modal('show'); 
                    }
                  product_lists();
                      $('#submit').attr('disabled',false);
                       $('#submit').text('Submit');
                        $('#action').val("Add");  
                }  
           })  

  }
else{
    return false;
}
  });


      $(document).on("click", ".update", function(event){
 event.preventDefault();
 event.stopPropagation();
 var id = $(this).attr("data-id");
 var action = "fetch_record";
 $.ajax({  
                url:"ajax_files/ajax_diesel_amount.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                   
                     $('#Amount').val(data.Amount);  
                      $('#Narration').val(data.Narration);
                      $('#CrDate').val(data.CrDate);
                     
                      $('#OldPhoto').val(data.Photo); 
                      $('#Photo').val(''); 
                    $('#Status').val(data.Status).attr("selected",true);  
                   
                     $('#action').val('Edit'); 
                    if(data.Photo==''){
                       $('#show_photo').hide();
                    } else{
                       $('#show_photo').show();
                    $('#show_photo').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/'+data.Photo+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                       $('#id').val(id);  
                       $('#submit').text("Update");   
                       $('.insert_frm').modal('show');
                         $('.modal-title').html("Update <span class='font-weight-light'>Diesel Amount</span>"); 
                     
                }  
           });
});



 $(document).on("click", ".delete", function(event){
 event.preventDefault();
 var id = $(this).attr("data-id");
 var action = "delete";
 //alert(id);
   swal({
            title: "Are you sure?",
            text: "Deleted All Records Related this Diesel Amount & You will not be able to recover this Product!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                 $.ajax({  
                url:"ajax_files/ajax_diesel_amount.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Diesel Amount has been deleted.", "success");
              
              product_lists();

                     }  
           });
                
            } else {
                swal("Cancelled", "Diesel Amount is safe :)", "error");
            }
        });
        
           
           
 });
  });
</script>
</body>
</html>
