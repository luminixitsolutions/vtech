<?php session_start();
require_once 'config.php';?>
<?php if($_GET['aid']) { $PageName = "Update Address";} else{$PageName = "Add Address";}?>
<?php
$user_id = $_SESSION['User']['id'];?>
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->
<?php 
$aid = $_GET['aid'];
$sql22 = "SELECT * FROM customer_address WHERE id='$aid'";
$row22 = getRecord($sql22);
 ?>
        <div class="main-container">
            <div class="container">
            <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>
                
                <div class="card">
                    <form method="post">
                    <div class="card-header">
                        <h6 class="subtitle mb-0">
                            <div class="avatar avatar-40 bg-primary-light text-primary rounded mr-2"><span class="material-icons vm">add_location_alt</span></div>
                            Add your address
                        </h6>
                    </div>

                    <div class="card-body">
                         <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                        <input type="hidden" id="aid" value="<?php echo $_GET['aid']; ?>">
                        <input type="hidden" id="page" value="<?php echo $_GET['page'];?>">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Fname" autofocus required value="<?php echo $row22['Fname']; ?>" id="Fname3">
                            <label class="form-control-label">First Name</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Lname" id="Lname3" required value="<?php echo $row22['Lname']; ?>">
                            <label class="form-control-label">Last Name</label>
                        </div>
                         <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Phone" id="Phone3" required value="<?php echo $row22['Phone']; ?>">
                            <label class="form-control-label">Phone Number</label>                            
                        </div>
                         <div class="form-group float-label active">
                            <input type="text" class="form-control" name="EmailId" id="EmailId3" value="<?php echo $row22['EmailId']; ?>">
                            <label class="form-control-label">Email Id</label>                            
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Address" id="Address3" required value="<?php echo $row22['Address']; ?>">
                            <label class="form-control-label">Address</label>                            
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Pincode" id="Pincode3" required value="<?php echo $row22['Pincode']; ?>">
                            <label class="form-control-label">Pincode</label>                            
                        </div>

                        <div class="form-group float-label active">
                           <select class="form-control" id="StateId3" name="StateId" required>
                               <option value="" selected="" disabled="">Select State</option>
                               <?php 
                                        $q = "SELECT * FROM tbl_state WHERE Status=1";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                    <option <?php if($row22['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">State</label>
                        </div>
                        <div class="form-group float-label active">
                           <select class="form-control" id="CityId3" name="CityId" required>
                               <option selected="" disabled="">Select City</option>
                                <?php 
                                         $StateId = $row22['StateId'];
                                        $q = "select * from tbl_city where StateId = '$StateId'";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if($row22['CityId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                              <?php } ?>
                            </select>
                            <label class="form-control-label">City</label>
                        </div>

                        <!-- <div class="form-group float-label active">
                           <select class="form-control" id="AreaId3" name="AreaId" required>
                               <option value="" selected="" disabled="">Select Area</option>
                                <?php 
                                    $CityId = $row22['CityId'];
                                        $q2 = "SELECT * FROM tbl_area WHERE Status=1 AND CityId='$CityId'";
                                        $r2 = $conn->query($q2);
                                        while($rw2 = $r2->fetch_assoc())
                                    {
                                ?>
                                    <option <?php if($row22['AreaId'] == $rw2['id']) {?> selected <?php } ?> value="<?php echo $rw2['id']; ?>"><?php echo $rw2['Name']; ?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">Area</label>
                        </div>-->
                       
                    </div>
                    
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="button" name="submit" id="addnewaddr">
                            <?php if($_GET['aid']) {?>Update <?php } else{?> Add <?php } ?> Address</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>


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
         $(document).ready(function(){
        $(document).on("change", "#StateId3", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId3').html(data);
    }
    });
    });
         $(document).on("change", "#CityId3", function(event){
  var val = this.value;
   var action = "getArea";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AreaId3').html(data);
    }
    });
    });

         $(document).on("click", "#addnewaddr", function(event){
                var action = "addNewAddress";
                var aid = $('#aid').val();
                var Fname = $('#Fname3').val();
                var Lname = $('#Lname3').val();
                var Phone = $('#Phone3').val();
                var EmailId = $('#EmailId3').val();
                var CountryId = 1;
                var StateId = $('#StateId3').val();
                var CityId = $('#CityId3').val();
                var AreaId = $('#AreaId3').val();
                var Pincode = $('#Pincode3').val();
                var Address = $('#Address3').val();
                var page = $('#page').val();
                if(Fname.trim() == ''){
                    //alert("Please Enter First Name");
                     $('#danger_message').css('display','block').html("Please Enter First Name");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Fname3').focus();
                }
                else if(Lname.trim() == ''){
                   // alert("Please Enter Last Name");
                   $('#danger_message').css('display','block').html("Please Enter Last Name");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Lname3').focus();
                }
                else if(Phone.trim() == ''){
                    //alert("Please Enter Phone No");
                    $('#danger_message').css('display','block').html("Please Enter Phone No");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Phone3').focus();
                }
                else if(! /^([0-9]{10})+$/.test(Phone)) {
                  //alert("Mobile Number must be 10 Digit!");
                  $('#danger_message').css('display','block').html("Mobile Number must be 10 Digit!");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                  $('#Phone3').focus();
                }
                /*else if(EmailId.trim() == ''){
                    alert("Please Enter Email Id");
                    $('#EmailId3').focus();
                }*/
                else if(Address.trim() == ''){
                    //alert("Please Enter Address");
                     $('#danger_message').css('display','block').html("Please Enter Address");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Address3').focus();
                }
                else if(StateId == null){
                    //alert("Please Select State");
                    $('#danger_message').css('display','block').html("Please Select State");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#StateId3').focus();
                }
                else if(CityId == null){
                    //alert("Please Select City");
                    $('#danger_message').css('display','block').html("Please Select City");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#CityId3').focus();
                }
                /*else if(AreaId == null){
                    //alert("Please Select Area");
                    $('#danger_message').css('display','block').html("Please Select Area");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#AreaId3').focus();
                }*/
                else if(Pincode.trim() == ''){
                    //alert("Please Enter Pincode");
                    $('#danger_message').css('display','block').html("Please Enter Pincode");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                    $('#Pincode3').focus();
                }

                else{
                      $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:{action:action,aid:aid,Fname:Fname,Lname:Lname,Phone:Phone,EmailId:EmailId,CountryId:CountryId,StateId:StateId,CityId:CityId,AreaId:AreaId,Pincode:Pincode,Address:Address},
                 beforeSend:function(){
     $('#addnewaddr').attr('disabled','disabled');
     $('#addnewaddr').text('Please Wait...');
    },
                success:function(data){ 
                 //alert(data);exit();
                 $('#success_message').css('display','block').html(data);
                      setTimeout(function(){  
                        $('#success_message').fadeOut("Slow");
                        if(page == 'hall'){
                         window.location.href="my-address.php?page=hall";
                        }
                        else{
                        window.location.href="my-address.php";
                        }
                    }, 2000); 
                    
                   $('#addnewaddr').attr('disabled',false);
                    $('#addnewaddr').text('Register');
                }
});    
                }


            }); 
        });
    </script>
</body>

</html>
