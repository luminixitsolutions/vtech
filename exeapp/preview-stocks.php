<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Stock";
$Page = "View-Available-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Stock List</title>
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
        

        <div class="main-container">










<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Preview Product Stock
    <span style="float: right;">
<a href="add-stock.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Quantity</th>
                <th>Company</th>
                <th>Category</th>
                <th>Model</th>
                 <th>Series</th>
                <th>Date</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tc.Name AS Category,tsc.Name AS Brand FROM tbl_stocks ts 
                    LEFT JOIN tbl_category tc ON tc.id=ts.CatId 
                    LEFT JOIN tbl_sub_category tsc ON tsc.id=ts.BrandId WHERE ts.Status=1 AND ts.InvId='".$_GET['id']."'";
           
            $sql.=" GROUP BY ts.ModelNo ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT SUM(Qty) As Qty FROM tbl_stocks WHERE ModelNo='".$row['ModelNo']."' AND BuyStatus=0 AND InvId='".$_GET['id']."'";
               $row2 = getRecord($sql2);
               if($row2['Qty'] == ''){
                    $Qty = 0;
               }
               else{
                    $Qty = $row2['Qty'];
               }

               $sql3 = "SELECT GROUP_CONCAT(ProductNo) AS ProductNo FROM `tbl_stocks` WHERE ModelNo='".$row['ModelNo']."' AND InvId='".$_GET['id']."'";
               $row3 = getRecord($sql3);
               if($row3['ProductNo'] != ''){
                    $ProductNo = $row3['ProductNo'];
               }
               else{
                    $ProductNo = "";
               }
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                 <td><?php echo $Qty;?></td>
                <td><?php echo $row['Category']; ?></td>
               
              <td><?php echo $row['Brand']; ?></td>
              <td><?php echo $row['ModelNo']; ?></td>
              <td><?php echo $ProductNo; ?></td>
         <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
                <!--   <td><a href="javascript:void(0)" onclick="availableSeries(<?php echo $row['ModelNo']; ?>)" class="badge badge-pill badge-primary">Show</a></td> -->
           
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
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

    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            
            'pdfHtml5'
        ]
    });

  
});
</script>
</body>
</html>
