<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "My Profile";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; ?>
<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                     
                  <form id="validation-form" method="post" autocomplete="off">
                   
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                         <div class="row">
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Fname" id="Fname" value="<?php echo $row11['Fname']; ?>" autofocus required>
                            <label class="form-control-label">Full Name</label>
                        </div>
                    </div>
                   <!--  <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Lname" id="Lname" value="<?php echo $row11['Lname']; ?>" required>
                            <label class="form-control-label">Last Name</label>
                        </div>
                    </div> -->
                </div>

                 <div class="row">
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                         <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone" id="Phone" value="<?php echo $row11['Phone']; ?>" required>
                            <label class="form-control-label">Phone Number</label>                            
                        </div>
                    </div>
                     <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                         <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone2" id="Phone2" value="<?php echo $row11['Phone2']; ?>">
                            <label class="form-control-label">Whatsapp Number</label>                            
                        </div>
                    </div></div>
                         <div class="form-group float-label active">
                            <input type="email" class="form-control" name="EmailId" id="EmailId" value="<?php echo $row11['EmailId']; ?>" required>
                            <label class="form-control-label">Email Id</label>                            
                        </div>
                       
                        
                         
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Address" name="Address" value="<?php echo $row11['Address']; ?>">
                            <label class="form-control-label">Address</label>                            
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Pincode" name="Pincode" value="<?php echo $row11['Pincode']; ?>">
                            <label class="form-control-label">Pincode</label>                            
                        </div>

                          <div class="form-group float-label active">
                            <input type="file" class="form-control" id="Photo" name="Photo">
                            <label class="form-control-label">Profile Photo</label>                            
                        </div>
                         <input type="hidden" name="OldPhoto" id="OldPhoto" value="<?php echo $row11['Photo']; ?>">
                        <?php if($row11['Photo'] == '') {} else{?>
                        <img src="../uploads/<?php echo $row11['Photo']; ?>" style="width: 100px;height: 100px;"><?php } ?>

                     
                  
                
                    </div>
                        
                          <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="Edit" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Update</button>
                    </div>
                </form>
                </div>
            </div>
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
    
    <script type="text/javascript">
    $(document).ready(function() {

             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var Fname = $('#Fname').val();
       var Mname = $('#Mname').val();
                var Lname = $('#Lname').val();
                var Phone = $('#Phone').val();

                var EmailId = $('#EmailId').val();
                var CountryId = $('#CountryId').val();
                var StateId = $('#StateId').val();
                var CityId = $('#CityId').val();
                var AreaId = $('#AreaId').val();
                var Pincode = $('#Pincode').val();
                var Address = $('#Address').val();
                if(Fname.trim() == ''){
                   $('#danger_message').css('display','block').html("Please Enter Name");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Fname').focus();
                }
               /* else if(Lname.trim() == ''){
                   $('#danger_message').css('display','block').html("Please Enter Last Name");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Lname').focus();
                }*/
                else if(Phone.trim() == ''){
                      $('#danger_message').css('display','block').html("Please Enter Phone No");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Phone').focus();
                }
                else if(! /^([0-9]{10})+$/.test(Phone)) {
                 $('#danger_message').css('display','block').html("Mobile Number must be 10 Digit!");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                  $('#Phone').focus();
                }
               /* else if(EmailId.trim() == ''){
                    alert("Please Enter Email Id");
                    $('#EmailId').focus();
                }*/
               /* else if(CountryId == null){
                     $('#danger_message').css('display','block').html("Please Select Country");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                 
                    $('#CountryId').focus();
                }
                else if(StateId == null){
                   $('#danger_message').css('display','block').html("Please Select State");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#StateId').focus();
                }
                else if(CityId == null){
                     $('#danger_message').css('display','block').html("Please Select City");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#CityId').focus();
                }
                else if(AreaId == null){
                    $('#danger_message').css('display','block').html("Please Select Area");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#AreaId').focus();
                }*/
                else if(Pincode.trim() == ''){
                   $('#danger_message').css('display','block').html("Please Enter Pincode");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Pincode').focus();
                }
                else if(Address.trim() == ''){
                    $('#danger_message').css('display','block').html("Please Enter Address");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Address').focus();
                }
               
    else { 
      
         $.ajax({  
                url :"ajax_files/ajax_customers.php",  
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
                        toastr.error('EmailId / Phone Already Exist', 'Error', {timeOut: 1000}); 
                     
                     }
                     else{
                    toastr.success('Profile Update Successfully!', 'Success', {timeOut: 5000}); 
                    //window.location.href="profile.php";
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           })  



    }

  });

           $(document).on("change", "#CountryId", function(event){
  var val = this.value;
   var action = "getState";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {

      $('#StateId').html(data);
    }
    });

 });


     $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId').html(data);
    }
    });
 });

     $(document).on("change", "#CityId", function(event){
  var val = this.value;
   var action = "getArea";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AreaId').html(data);
    }
    });
    });
});
</script>
</body>

</html>
