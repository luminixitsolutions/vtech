<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "My Customers";
$UserId = $_SESSION['User']['id']; ?>
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

       
            <div class="container mb-4" style="padding-right: 1px;
padding-left: 1px;">

                   
                <div class="card">
                   
                <div class="card">
                    <div class="card-body">
                        <div class="form-group float-label position-relative mb-1">
                            
                            <input type="text" class="form-control" id="search_text3">
                            <label class="form-control-label">Search</label>
                        </div>
                    </div>
                </div>
           
                    <div class="card-body px-0 pt-0">
                        <ul class="list-group list-group-flush" id="show_prod">
                             <?php 
                                $sql2 = "SELECT * FROM customers WHERE CreatedBy='$UserId' ORDER BY id DESC"; 
                                $res2 = $conn->query($sql2);
                                $row_cnt = mysqli_num_rows($res2);
                                    if($row_cnt > 0){
                                    while($row = $res2->fetch_assoc()){
                                        $Roll = $row['Roll'];
                                       if($Roll == 4){
                                        $AccName = "Doctor";
                                        }
                                        if($Roll == 5){
                                        $AccName = "Optician";
                                        }
                                        if($Roll == 6){
                                        $AccName = "Wholesaler";
                                        }
                                        if($Roll == 7){
                                        $AccName = "Customer";
                                        }
                                        if($Roll == 8){
                                        $AccName = "Retailer";
                                        }

                                        if($row['Status'] == 1){
                                        $Status = '<h6 class="text-success">Active</h6>';
                                        }   
                                        else{
                                        $Status = '<h6 class="text-danger">Pending</h6>';
                                        }
                                     ?>
                            <li class="list-group-item">
                                <a href="view-cust-details.php?id=<?php echo $row['id'];?>"><div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background">
                                     <?php if($row['Photo'] == ''){?>
                    <img src="no_profile.jpg" alt="" style="width: 40px;height: 40px;">
                <?php } else if(file_exists("../uploads/".$row['Photo'])) {?>
                     <img src="../uploads/<?php echo $row['Photo']; ?>" alt="" style="width: 40px;height: 40px;">
                 <?php } else{?>
                     <img src="no_profile.jpg" alt="" style="width: 40px;height: 40px;">
                 <?php } ?>           
                                             
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1" style="color: #212529"><?php echo $row['Fname']." ".$row['Lname']; ?><br> (<?php echo $AccName; ?>)</h6>
                                       
                                        <p class="small text-secondary"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate'])))?></p>
                                    </div>
                                    <div class="col-auto">
                                        <?php echo $Status; ?>
                                    </div>
                                </div></a>
                            </li>
                        <?php }} else{ ?><br>
                           <div class="col-auto">
                                        <h6 class="text-danger">Oops! No Customer Found..</h6>
                                    </div>
                           <?php } ?>
                        </ul>
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
        $(document).ready(function() {
            function load_data3(query)
 {

  $.ajax({
   url:"ajax_files/ajax_search.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    console.log(data);
    $('#show_prod').html(data);
    
   }
  });
 }
 $('#search_text3').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data3(search);
  }
  else
  {
   load_data3();
  }
 });
            });
    </script>
    </body>

</html>
