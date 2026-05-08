<?php session_start();
require_once '../config.php';
require_once '../auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$CustomerId = $row11['CustomerId'];
 ?>
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
    <link rel="apple-touch-icon" href="../img/favicon180.png" sizes="180x180">
    <link rel="icon" href="../img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="../vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet" id="style">
     <?php include_once 'header_script.php'; ?>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once '../sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once '../top_header.php'; ?>
    
   
    
        <div class="main-container">
           


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"> My Customers
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               
                <th>Customer Name</th> 
                <th>Contact No</th>
               
                <th>City</th>
               <!--  <th>Stock</th> -->
               <th>State</th>
               <th> Status</th>
             
                <th>Commission Approved</th>
                <th>Commission Due</th>
               
                 
               <th>Commission Received</th>
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.*,ts.Name As State,tc.Name As City FROM tbl_users tu 
                    LEFT JOIN tbl_state ts ON tu.StateId=ts.id 
                    LEFT JOIN tbl_city tc ON tu.CityId=tc.id WHERE tu.Roll=5 AND tu.DealerCode='$CustomerId'";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT * FROM tbl_steps WHERE CustId='".$row['id']."'";
                $row2 = getRecord($sql2);
               if($row2['SrNo']==5){
                   $Status = "Completed";
               }
               else{
                   $Status = "In Progress";
               }
               
              $sql3 = "SELECT * FROM tbl_show_dealer_comm_amt WHERE CustId='".$row['id']."' AND DealerId='$UserId'";
            // $sql3 = "SELECT * FROM tbl_show_dealer_comm_amt WHERE CustId='".$row['id']."'";
               $row3 = getRecord($sql3);
               
               $sql4 = "SELECT * FROM wallet WHERE UserId='$UserId' AND CustId='".$row['id']."' AND Status='Cr'";
            // $sql4 = "SELECT * FROM wallet WHERE CustId='".$row['id']."' AND Status='Cr'";
            $rncnt4 = getRow($sql4);
               $row4 = getRecord($sql4);
               if($rncnt4 > 0){
                   $ReceivedAmt = $row4['Amount'];
                   $DueAmt = 0;
               }
               else{
                   $ReceivedAmt = 0;
                   $DueAmt = $row4['Amount'];
               }
               
               $TotComm+=$row3['Amount'];
               $TotDueAmt+=$DueAmt;
               $TotReceivedAmt+=$ReceivedAmt;
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
                
               <td><?php echo $row['Fname']; ?></td> 
              
                <td><?php echo $row['Phone']; ?></td>
                <td><?php echo $row['City']; ?></td>
                 <td><?php echo $row['State']; ?></td>
                 
                 <td><?php echo $Status; ?></td>
                  <td><?php echo $row3['Amount']; ?></td>
                  <td><?php echo $DueAmt; ?></td>
                  <td><?php echo $ReceivedAmt; ?></td>
            
             
               
           
        
              
            </tr>
           <?php $i++;} ?>
           
           <tr>
               <td><?php echo $i; ?> </td>
                
               <td></td> 
              
                <td></td>
                <td></td>
                 <td></td>
                 
                 <td>Total</td>
                  <td><?php echo $TotComm; ?></td>
                  <td><?php echo $TotDueAmt; ?></td>
                  <td><?php echo $TotReceivedAmt; ?></td>
            
             
               
           
        
              
            </tr>
        </tbody>
    </table>
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>
            
        </div>
    </main>

    <?php include_once '../footer.php';?>


    <!-- color settings style switcher -->
    



    <!-- Required jquery and libraries -->
   <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="../js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="../vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="../js/main.js"></script>
    <script src="../js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="../js/app.js"></script>
     <?php include_once 'footer_script.php'; ?>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
      </script>
 <script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable( {
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
      });
});
</script>
    
</body>

</html>
