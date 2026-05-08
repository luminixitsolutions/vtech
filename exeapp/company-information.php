<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Account";
$Page = "Company";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - College Information</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
 <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            


            

                

<?php 
$sql7 = "SELECT * FROM tbl_company_profile WHERE id='1'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Company Information</h4>

<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" autocomplete="off">
  <input type="hidden" name="id" value="1" id="userid">
<div class="form-row">
 
 <div class="form-group col-md-12">
<label class="form-label">Company Name <span class="text-danger">*</span></label>
<input type="text" name="Sname" id="Sname" class="form-control" placeholder="Company Name" value="<?php echo $row7["Sname"]; ?>" autocomplete="off" required>
</div>

<div class="form-group col-md-12">
<label class="form-label">Owner Name <span class="text-danger">*</span></label>
<input type="text" name="Oname" id="Oname" class="form-control" placeholder="Owner Name" value="<?php echo $row7["Oname"]; ?>" autocomplete="off" required>
</div>

<!-- <div class="form-group col-md-12">
<label class="form-label">Qualification <span class="text-danger">*</span></label>
<input type="text" name="Qualification" id="Qualification" class="form-control" placeholder="" value="<?php echo $row7["Qualification"]; ?>" autocomplete="off" required>
</div>

<div class="form-group col-md-12">
<label class="form-label"> C.P.C. NO. <span class="text-danger">*</span></label>
<input type="text" name="CpcNo" id="CpcNo" class="form-control" placeholder="" value="<?php echo $row7["CpcNo"]; ?>" autocomplete="off" required>
</div> -->

<div class="form-group col-md-12">
<label class="form-label">Address <span class="text-danger">*</span></label>
<textarea name="Address" class="form-control" placeholder="Address" autocomplete="off" required><?php echo str_replace("<br />"," ",$row7["Address"]); ?></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Address 2</label>
<textarea name="Address2" class="form-control" placeholder="Address2" autocomplete="off"><?php echo str_replace("<br />"," ",$row7["Address2"]); ?></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Google Map Code <span class="text-danger">*</span></label>
<textarea name="GoogleMap" class="form-control" placeholder="Google Map Code" autocomplete="off" rows="5"><?php echo $row7["GoogleMap"]; ?></textarea>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Email Id <span class="text-danger">*</span></label>
<input type="email" name="EmailId" id="EmailId" class="form-control" placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Another Email Id </label>
<input type="email" name="EmailId2" id="EmailId2" class="form-control" placeholder="Email Id" value="<?php echo $row7["EmailId2"]; ?>" autocomplete="off">
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">WhatsApp Mobile No <span class="text-danger">*</span></label>
<input type="text" name="Phone1" id="Phone1" class="form-control" placeholder="Mobile No" value="<?php echo $row7["Phone1"]; ?>" required>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Another Mobile No</label>
<input type="text" name="Phone2" class="form-control" placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
<div class="clearfix"></div>
</div>



<div class="form-group col-md-6">
<label class="form-label">Bank Holder Name <span class="text-danger">*</span></label>
<input type="text" name="AccName" id="AccName" class="form-control" placeholder="" value="<?php echo $row7["AccName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Account No <span class="text-danger">*</span></label>
<input type="text" name="AccNo" id="AccNo" class="form-control" placeholder="" value="<?php echo $row7["AccNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Branch <span class="text-danger">*</span></label>
<input type="text" name="BranchName" id="BranchName" class="form-control" placeholder="" value="<?php echo $row7["BranchName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">IFSC Code <span class="text-danger">*</span></label>
<input type="text" name="Ifsc" id="Ifsc" class="form-control" placeholder="" value="<?php echo $row7["Ifsc"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">PAN No <span class="text-danger">*</span></label>
<input type="text" name="PanNo" id="PanNo" class="form-control" placeholder="" value="<?php echo $row7["PanNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">GSTIN No <span class="text-danger">*</span></label>
<input type="text" name="GstNo" id="GstNo" class="form-control" placeholder="" value="<?php echo $row7["GstNo"]; ?>">
<div class="clearfix"></div>
</div>



</div>
<!-- <button id="growl-default" class="btn btn-default">Default</button> -->
<button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
</form>
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
<script type="text/javascript">

  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Saved Successfully...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
   $(document).ready(function(){
    //$(document).on("click", ".btn-finish", function(event){
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
    if ($('#validation-form').valid()){ 
      
         $.ajax({  
                url :"ajax_files/ajax_company.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                    //console.log(data);exit();
                     if(data == 0){
                      error_toast();
                     
                     }
                     else{
                   success_toast();
                   /*setTimeout(function(){  
                   window.location.href = 'company-information.php';
                    }, 2000);*/
                     }
                      $('#submit').attr('disabled',false);
                     $('#submit').text('Save');
                }  
           })  



    }
else{
  //$('#Fname').focus();
    return false;
}
  });

});
</script>
</body>
</html>
