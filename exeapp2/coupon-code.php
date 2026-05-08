<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="E-Commerce";
$Page="Coupon-Code";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Coupon Code</title>
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

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">

<?php include_once 'ecommerce-sidebar.php'; ?>








<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">List Of Coupon Code
  <?php if($_SESSION['Roll']==1) {?>
  <span style="float: right;">
<a href="add-coupon.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add More</a></span><?php } ?></h4><br>
<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light">Coupon Code</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 
  <div class="form-row">
<div class="form-group col-lg-12">
<label class="form-label">Referral/Coupon Code <span class="text-danger">*</span></label>
<input type="text" name="Code" class="form-control" id="Code" placeholder="" required>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-lg-12">
<label class="form-label">Discount<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Discount" name="Discount" class="form-control" value="" required onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div> -->


<div class="form-group col-lg-6">
<label class="form-label">From Date<span class="text-danger">*</span></label>
<input type="date" name="FromDate" class="form-control" id="FromDate" placeholder="" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">To Date<span class="text-danger">*</span></label>
<input type="date" name="ToDate" class="form-control" id="ToDate" placeholder="" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Points<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Discount" name="Discount" class="form-control" value="" required onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">Pts</div>
</div>
</div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Points Validity<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="PointDays" name="PointDays" class="form-control" value="" required onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">Days</div>
</div>
</div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Min Order Amount<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinOrder" name="MinOrder" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>



</div>

  <div class="form-row">
<div class="form-group col">
  <label class="form-label">Coupon Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label><br>
 <span id="show_photo">
</span>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<!-- <option selected="" disabled="" value="">Select Status</option> -->
<option value="1" selected="">Active</option>
<option value="0">Inctive</option>
</select>
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
<div class="card">
<div class="card-datatable table-responsive" id="custresult">
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

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
  function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
function category_lists(){
  var action = 'view';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_coupon_code.php",
   data:{action:action},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Coupon Code Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'New Coupon Code Added Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }
function update_toast(){
             var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Coupon Code Updated Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }

  $(document).ready(function() {
      category_lists();
    $('#example').DataTable( {
      
    } );

      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Coupon Code</span>");  
           $('#action').val("Add");  
           $('#id').val('');

      $('#Code').val('');
      $('#Discount').val('');
      $('#FromDate').val('');
      $('#ToDate').val('');
      $('#MinOrder').val('');
      $('#PointDays').val('');
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
                url :"ajax_files/ajax_coupon_code.php",  
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
                        update_toast();
                      }
                      else{
                      success_toast();
                      }
                      $('.insert_frm').modal('hide'); 
                    }
                    else{
                      error_toast();
                      $('.insert_frm').modal('show'); 
                    }
                  category_lists();
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
                url:"ajax_files/ajax_coupon_code.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                   
                     $('#Code').val(data.Code);  
                     $('#Discount').val(data.Discount);  
                     $('#FromDate').val(data.FromDate);  
                     $('#ToDate').val(data.ToDate);  
                     $('#MinOrder').val(data.MinOrder);  
                     $('#PointDays').val(data.PointDays);
                     $('#Status').val(data.Status).attr("selected",true);  
                     $('#OldPhoto').val(data.Photo); 
                      $('#Photo').val(''); 
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
                         $('.modal-title').html("Update <span class='font-weight-light'>Coupon Code</span>"); 
                     
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
            text: "You will not be able to recover this Coupon Code!",
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
                url:"ajax_files/ajax_coupon_code.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Coupon Code has been deleted.", "success");
              
              category_lists();

                     }  
           });
                
            } else {
                swal("Cancelled", "Coupon Code is safe :)", "error");
            }
        });

           
 });



} );
</script>
</body>
</html>
