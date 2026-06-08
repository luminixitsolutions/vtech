<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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
        

        <div class="main-container" style="background-color: white;">






<div class="container">
    <h4 class="font-weight-bold py-3 mb-0">Site Engineer Report</h4>
    <div class="card mb-4">
        
        <div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Date</th> 
               <th>Site Name</th> 
               <th>Work Description</th> 
               <th>Target Site</th> 
               <th>Work Completed</th> 
               <th>Target Pending Please specify the reason</th> 
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.*,tut.Name As User_Type FROM tbl_users tu 
                    LEFT JOIN tbl_user_type tut ON tu.UserType=tut.id WHERE tu.Roll=5 AND tu.FieldSurveyId='$user_id'";
             $sql.= " ORDER BY tu.id DESC";
             $row = getList($sql);
             foreach($row as $result){
                 
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php if($result['FieldSurveyDate']!='') { echo date("d/m/Y", strtotime(str_replace('-', '/',$result['FieldSurveyDate']))); } ?></td>
               <td><?php echo $result['Fname']." ".$result['Lname']; ?></td>
               <td>Survey</td>
               <td>1</td>
               <td><?php echo $result['FieldSurveyDetails'];?></td>
               <td><?php echo 1-$result['FieldSurveyDetails'];?> </td>
    
               </tr>
               <?php $i++;} ?>
               
               <?php 
            $i=$i;
            $sql = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='$user_id'";
             $sql.= " ORDER BY tu.id DESC";
             $row = getList($sql);
             foreach($row as $result){
                 if($result['Inst_Dispatcher_Otp_Verify'] == 1){
                     $Dispatch = 1;
                 }
                 else{
                     $Dispatch = 0;
                 }
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php if($result['Inst_Dispatcher_Date']!='') { echo date("d/m/Y", strtotime(str_replace('-', '/',$result['Inst_Dispatcher_Date']))); } ?></td>
               <td><?php echo $result['Fname']." ".$result['Lname']; ?></td>
               <td>Dispatch</td>
               <td>1</td>
               <td><?php echo $Dispatch;?></td>
               <td><?php echo 1-$Dispatch;?> </td>
    
               </tr>
               <?php $i++;} ?>
               
                   
               <?php 
            $i=$i;
            $sql = "SELECT * FROM tbl_users WHERE InstallerId='$user_id'";
             $sql.= " ORDER BY id DESC";
             $row = getList($sql);
             foreach($row as $result){
                 if($result['InstOtpVerify'] == 1){
                     $Dispatch = 1;
                 }
                 else{
                     $Dispatch = 0;
                 }
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php if($result['InstallationDate']!='') { echo date("d/m/Y", strtotime(str_replace('-', '/',$result['InstallationDate']))); } ?></td>
               <td><?php echo $result['Fname']." ".$result['Lname']; ?></td>
               <td>Installation</td>
               <td>1</td>
               <td><?php echo $Dispatch;?></td>
               <td><?php echo 1-$Dispatch;?> </td>
    
               </tr>
               <?php $i++;} ?>
               
               
                <?php 
            $i=$i;
            $sql = "SELECT * FROM tbl_users WHERE InspectionId='$user_id'";
             $sql.= " ORDER BY id DESC";
             $row = getList($sql);
             foreach($row as $result){
                 if($result['InspectionOtpVerify'] == 1){
                     $Dispatch = 1;
                 }
                 else{
                     $Dispatch = 0;
                 }
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php if($result['InstInspectionDate']!='') { echo date("d/m/Y", strtotime(str_replace('-', '/',$result['InstInspectionDate']))); } ?></td>
               <td><?php echo $result['Fname']." ".$result['Lname']; ?></td>
               <td>InstInspection</td>
               <td>1</td>
               <td><?php echo $Dispatch;?></td>
               <td><?php echo 1-$Dispatch;?> </td>
    
               </tr>
               <?php $i++;} ?>
            </tbody>
    </table>
    </div>
</div>








<?php include_once 'footer.php'; ?>

</div>

</main>
<br><br>
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
         paging: false,
    ordering: false,
    info: false,
    });
});
</script>
</body>
</html>
