<?php session_start();
require_once 'config.php';
$PageName = "Offers";
$Page = "Shop";
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->

        <div class="main-container">
           

            <div class="container mb-4">
            <div class="card">
                    <div class="card-body text-center ">
                        <div class="row">
                             <?php 
                          $sql2 = "SELECT * FROM tbl_coupon_code WHERE Status='1'";
                          $res2 = $conn->query($sql2);
                          while($row2 = $res2->fetch_assoc()){
                          
                        ?>
                            <div class="col-12 col-md-4 col-lg-3">
                        <div class="card border-0 mb-4 overflow-hidden">
                            <div class="position-relative" style="padding: 5px;">
                              
                                <a href="offer-details.php?id=<?php echo $row2["id"];?>&pageval=<?php echo $_GET['pageval'];?>" class="">
                                     <?php if($row2["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 100%;height: 100%;"> 
                 <?php } else if(file_exists('../uploads/'.$row2["Photo"])){?>
                 <img src="../uploads/<?php echo $row2["Photo"];?>" alt="" style="width: 100%;height: 100%;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 100%;height: 100%;"> 
             <?php } ?>
                                   
                                </a>
                            </div>
                          
                        </div>
                    </div>
                              <?php } ?>

                           
                            
                        </div>
                        
                    </div>
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

 <script>
    "use strict"
        $(document).ready(function() {
 $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var CatId = $('#CatId').val();
      var StateId = $('#StateId').val();
      var CityId = $('#CityId').val();
      var LanguageId = $('#LanguageId').val();
      window.location.href="vendor-lists.php?id="+CatId+"&StateId="+StateId+"&CityId="+CityId+"&LanguageId="+LanguageId;
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

    /* calander picker */
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#daterangeadminux2 span').html(start.format('MMM D, YY') + ' - ' + end.format('MMM D, YY'));
                $('#daterangeadminux span').html(start.format('MMM D, YY') + ' - ' + end.format('MMM D, YY'));
            }

            $('#daterangeadminux2').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left'
            }, cb);

            $('#daterangeadminux').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);
            /* calander picker ends */

            /* calander single  picker ends */
            $('.datepicker').daterangepicker({
                dateFormat: 'yy-mm-dd',
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901
            }, function(start, end, label) {});

            /* calander single picker ends */


        });
    </script>
</body>

</html>
