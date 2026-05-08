<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$sql11 = "SELECT * FROM tbl_rec_operator WHERE id='$id'";
$row11 = getRecord($sql11);
$PageName = "".$row11['Name'];
$Page = "Recharge";
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
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->

        <div class="main-container">
            <div class="container">
               <div class="alert alert-success">
                    For Change Operator? Please <b><a href="recharge-operator.php?catid=<?php echo $_GET['catid']; ?>">Click here.</a></b>
                   
                </div>
                <div class="card">
                     <form id="validation-form" method="post" autocomplete="off"> 
                    <input type="hidden" id="user_id" value="<?php echo $_SESSION['User']['id']; ?>">
                              <input type="hidden" id="orderid" name="orderid" value="<?php echo rand(10000000,9999999999); ?>">
                              <input type="hidden" id="operatorid" name="optid" value="<?php echo $_GET['optid']; ?>">
                    <div class="card-body">
                      <input type="hidden" name="CatId" id="CatId" value="<?php echo $_GET['id']; ?>">
                        <div class="form-group float-label active">
                            <input class="form-control" id="MobileNo" name="MobileNo" type="number" min="0" placeholder="" value="<?php echo $_GET['MobileNo']; ?>" autofocus>
                            <label class="form-control-label">DTH No</label>
                        </div>
                        <div class="form-group float-label active">
                            <input class="form-control" id="Amount" name="Amount" type="number" placeholder="" min="0" required oninput="amount();" value="<?php echo $_GET['rate']; ?>">
                            <label class="form-control-label">Amount</label>
                            <span id="error_msg"></span>
                        </div>
                         
                         
                       
                    </div>
                    
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="button" name="submit" onclick="rec_button()" id="recButton">Recharge</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>
 <?php include_once 'footer.php';?>

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

  <script src="vendor/daterangepicker-master/moment.min.js"></script>
    <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- page level custom script -->
    <script src="js/app.js"></script>

 <script type="text/javascript">
 const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const catid = urlParams.get('catid');
      const id = urlParams.get('id');
    function myPlans(){
      var MobileNo = $('#MobileNo').val();
      var optid = $('#operatorid').val();
      if(MobileNo == ''){
        alert("Please Enter Mobile No");
      }
       else if(! /^([0-9]{10})+$/.test(MobileNo))
        {
            alert("Mobile Number must be 10 Digit!");
        }
        else{
          window.location.href="my-plans.php?id="+optid+"&phone_no="+MobileNo;
        }
    }
        function getWallet(){
              var action = "getWalletBal";
            var user_id = $('#user_id').val();
             var Amount = $('#Amount').val();
            
    $.ajax({
    url:"ajax_files/ajax_customers.php",
    method:"POST",
    data : {action:action,user_id:user_id,Amount:Amount},
    
    success:function(data)
    {
        if(data == 0){
            $('#error_msg').show();
            $('#recButton').attr('disabled',true);
            //$('#recButton').prop('type','button');
          $('#error_msg').html('<span style="color:red;">Insuffiecient Balance in your Wallet</span>');  
        }
        else if(data == 1){
            $('#error_msg').hide();
            $('#recButton').attr('disabled',false);
            //$('#recButton').prop('type','submit');

        }
        
    }
    });
        }

 function amount(){
getWallet();

 }
     function rec_button(){
        //var State = $('#RecStateId').val();
        var MobileNo = $('#MobileNo').val();
        var Amount = $('#Amount').val();
         var user_id = $('#user_id').val();
         var RecStateId = $('#RecStateId').val();
        var orderid = $('#orderid').val();
        var optid = $('#operatorid').val();
        //alert(MobileNo);alert(user_id);alert(Amount);alert(optid);

        /*if(State == null){
            alert("Please Select State");
        }
        else */
        if(MobileNo == ''){
            alert("Please Enter a DTH No");
        }
        
        else if(Amount == ''){
            alert("Please Enter Amount");
        }
        else{
            getWallet();
            var action = "rechargeProcess";
             $.ajax({
    url:"ajax_files/ajax_customers.php",
    method:"POST",
    data : {action:action,user_id:user_id,Amount:Amount,RecStateId:RecStateId,MobileNo:MobileNo,orderid:orderid,optid:optid},
    beforeSend:function(){
     $('#recButton').attr('disabled','disabled');
     $('#recButton').text('Please Wait...');
    },
    success:function(data)
    {
      //window.location.href=data;
      //alert(data);
      $('#recButton').attr('disabled',false);
             $('#recButton').text('Recharge');
      //console.log(data);exit();
      res = JSON.parse(data);
      var status = res.status;
      var msg = res.msg;
       if(status == 0){
       setTimeout(function () { 
swal({
  title: "Recharge Failed",
  text: msg,
  type: "error",
  confirmButtonText: "Try Again"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="pay-dth-recharge.php?optid="+optid+"&catid="+catid+"&id="+id;
  }
}); });
       }

       else if(status == 1){
        setTimeout(function () { 
swal({
  title: "Recharge Success!",
  text: "Thank you for Recharge. Visit Again!",
  type: "success",
  confirmButtonText: "ok"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="my-recharge-transaction.php";
  }
}); });
       }
       else if(status == 2){
         setTimeout(function () { 
swal({
  title: "Recharge Failed/Pending",
  text: msg,
  type: "error",
  confirmButtonText: "Try Again"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="pay-dth-recharge.php?optid="+optid+"&catid="+catid+"&id="+id;
  }
}); });
       }
       
       else if(status == 4){
        setTimeout(function () { 
swal({
  title: "Recharge Pending!",
  text: "Please Wait For Some Time!",
  type: "warning",
  confirmButtonText: "ok"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="my-recharge-transaction.php";
  }
}); });
       }

       else{
        alert(msg);
       }


        
    }

});
          
        }
     }
    </script>   </body>

</html>
