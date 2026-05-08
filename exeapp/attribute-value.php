<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="E-Commerce";
$Page="Attributes";
$Page2 = "AttrValue";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Attribute Value</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Empire is one of the unique admin template built on top of Bootstrap 4 framework. It is easy to customize, flexible code styles, well tested, modern & responsive are the topmost key factors of Empire Dashboard Template" />
<meta name="keywords" content="bootstrap admin template, dashboard template, backend panel, bootstrap 4, backend template, dashboard template, saas admin, CRM dashboard, eCommerce dashboard">
<meta name="author" content="" />
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
<h4 class="font-weight-bold py-3 mb-0">List Of Attribute Value
  <span style="float: right;">
<button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#modals-default" id="add_button"><i class="ion ion-md-add mr-2"></i> Add More</button></span></h4><br>
<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light">Attribute Value</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 

<!-- <div class="form-row">
<div class="form-group col">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from category WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
</div> -->

<!-- <div class="form-row">
<div class="form-group col">
<label class="form-label">Sub Category<span class="text-danger">*</span></label>
  <select class="form-control" id="SubCatId" name="SubCatId" required="">
<option selected="" disabled="" value="">Select Sub Category</option>
</select>
<div class="clearfix"></div>
</div>
</div> -->

      <div class="form-row">
<div class="form-group col">
<label class="form-label">Attribute Name <span class="text-danger">*</span></label>
  <select class="form-control" id="AttrNameId" name="AttrNameId" required="">
<option selected="" disabled="" value="">Select Attribute Name</option>
<?php 
        $q = "select * from attribute_name WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
</div>

  <div class="form-row">
<div class="form-group col">
<label class="form-label">Attribute Value <span class="text-danger">*</span></label>
<input type="text" name="Name" class="form-control" id="Name" placeholder="Attribute Value" required>
<div class="clearfix"></div>
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
function category_lists(){
  var action = 'view';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_attribute_value.php",
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
      message:  'Attribute Value Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'New Attribute Value Added Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }
function update_toast(){
             var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Attribute Value Updated Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }

/* function getAttrName(CatId,AttrNameId){
      var action = "getAttrName";
        $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:CatId},
    success:function(data)
    {
      $('#AttrNameId').html(data);
        $('#AttrNameId').val(AttrNameId).attr("selected",true);  
    }
    });
    }*/
    function getSubCat(CatId,SubCatId){
      var action = "getSubCat";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:CatId},
    success:function(data)
    {
      $('#SubCatId').html(data);
       $('#SubCatId').val(SubCatId).attr("selected",true);  
    }
    });
    }
  $(document).ready(function() {
      category_lists();
    $('#example').DataTable( {
      
    } );

      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Attribute Value</span>");  
           $('#action').val("Add");  
           $('#id').val('');

      $('#Name').val('');
      $('#Status').attr("selected","selected").val(1);
        $('#CatId').attr("selected","selected").val(null);
         //$('#SubCatId').attr("selected","selected").val(null);
       $('#AttrNameId').attr("selected","selected").val(null);
       $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var action = $('#action').val();
    if ($('#validation-form').valid()){ 
         $.ajax({  
                url :"ajax_files/ajax_attribute_value.php",  
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
                url:"ajax_files/ajax_attribute_value.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                   
                     $('#Name').val(data.Name);  
                     
                    $('#Status').val(data.Status).attr("selected",true);  
                      $('#CatId').val(data.CatId).attr("selected",true);   
                 $('#AttrNameId').val(data.AttrNameId).attr("selected",true); 
                  //var CatId = data.CatId;
                 //var SubCatId = data.SubCatId;
                 //getSubCat(CatId,SubCatId); 
                   
                     $('#action').val('Edit'); 
                   
                       $('#id').val(id);  
                       $('#submit').text("Update");   
                       $('.insert_frm').modal('show');
                         $('.modal-title').html("Update <span class='font-weight-light'>Attribute Value</span>"); 
                     
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
            text: "You will not be able to recover this Attribute Value!",
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
                url:"ajax_files/ajax_attribute_value.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Attribute Value has been deleted.", "success");
              
              category_lists();

                     }  
           });
                
            } else {
                swal("Cancelled", "Attribute Value is safe :)", "error");
            }
        });

           
 });

$(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getSubCat";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#SubCatId').html(data);
    }
    });

 });
/*$(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getAttrName";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AttrNameId').html(data);
    }
    });

 });*/

} );
</script>
</body>
</html>
