<?php 
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Under-Production-Beneficiary";
$Page = "Under-Production-Beneficiary";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Sell List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php

if(isset($_POST['submit'])){

   
   $DispatchOfficerId = $_POST['DispatchOfficerId'];
   $CreatedDate = date('Y-m-d H:i:s');
   $rncnt = $_POST['rncnt'];
    $rncnt2 = $_POST['rncnt2'];

if($rncnt2 > 0){
    $number2 = count($_POST['CheckId2']);
   $DispatchOfficerId = $_POST['DispatchOfficerId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number2 > 0)  
      {  
        for($i2=0; $i2<$number2; $i2++)  
          {  
            if(trim($_POST["CheckId2"][$i2] != ''))  
              {
                $CheckId2 = addslashes(trim($_POST['CheckId2'][$i2]));
                if($CheckId2 == 1){
                $QtnId = addslashes(trim($_POST['QtnId2'][$i2]));
                $sql = "UPDATE tbl_users SET UnderProdStatus='1',UnderProdDate='$CreatedDate' WHERE id='$QtnId'";
                $conn->query($sql);

                }
              }
            }
        }
}
       
        echo "<script>alert('Record Saved');window.location.href='under-production-beneficiary.php?UnderProdStatus=0';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Under Construction Beneficiary
<!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
</h4>

<div class="card" style="padding: 10px;">
<form id="validation-form" method="post" enctype="multipart/form-data" action="">
      <div id="accordion2">
<div class="card mb-2">
                                        
  <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                
<div class="form-row">

<div class="form-group col-md-2">
                                                        <label class="form-label">Assign Status </label>
                                                        <select class="form-control" id="UnderProdStatus" name="UnderProdStatus">
                                                        <option value="all" selected>All</option>
                                    <option value="1" <?php if($_REQUEST['UnderProdStatus']==1){ ?> selected <?php } ?> >Done</option>
                                    <option value="0"  <?php if($_REQUEST['UnderProdStatus']==0){ ?> selected <?php } ?> >Pending</option>
                                                          
                                                        </select>
                                                    </div>

 <input type="hidden" id="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:20px;">
                                                        <button type="button" onclick="search()" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    
</div>
</div>
 </div>
</div>
   </div>
</form>

 <form id="validation-form" method="post" enctype="multipart/form-data" action="">   
  
        <?php 
            $i=1;
            $sql = "SELECT tp.* FROM tbl_users tp 
                    WHERE tp.SurveyDetails=1 AND tp.FieldSurveyDetails=1 AND tp.ProjectType=2 ";
            if($_REQUEST['UnderProdStatus']!=''){
                    $SurveyMatch = $_REQUEST['UnderProdStatus'];
                    if($SurveyMatch == 'all'){
                        $sql.= " ";
                    }
                    else{
                       
                    $sql.= " AND tp.UnderProdStatus='$SurveyMatch'";
                    }
            }
            $sql.= " ORDER BY tp.CreatedDate DESC";
            $rncnt2 = getRow($sql);?>
        <input type="hidden" name="rncnt2" value="<?php echo $rncnt2;?>">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Under Production Status</th>
                <th>Beneficiary Id</th> 
                <th>Customer Name</th> 
                <th>Contact No</th>
                <th>Address</th>
               
              
                
               
            </tr>
        </thead>
        <tbody>
           <?php 
            $i=1;
           
            $sql = "SELECT tp.* FROM tbl_users tp WHERE tp.SurveyDetails=1 AND tp.FieldSurveyDetails=1 AND tp.ProjectType=2";
            if($_REQUEST['UnderProdStatus']!=''){
                    $SurveyMatch = $_REQUEST['UnderProdStatus'];
                    if($SurveyMatch == 'all'){
                        $sql.= " ";
                    }
                    else{
                       
                    $sql.= " AND tp.UnderProdStatus='$SurveyMatch'";
                    }
            }
            $sql.=" ORDER BY tp.CreatedDate DESC";
       // echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
               $sql22 = "SELECT * FROM tbl_users WHERE UnderProdStatus=1 AND id='".$row['id']."'";
                $rncnt22 = getRow($sql22);
                if($rncnt22 > 0){
                     $bcolor = "background-color: #b9efb9;";
                }
                else{
                    $bcolor = "";
                }

             ?>
            <tr style="<?php echo $bcolor;?>">
               <td><?php if($rncnt22 > 0){} else{?>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id2<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured2(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId2[]" id="CheckId2<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId2[]">
               <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">
            <td><?php if($row['UnderProdStatus']=='1'){echo "<span style='color:green;'>Done</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
            <td><?php echo $row['BeneficiaryId']; ?></td> 
            <td><?php echo $row['Fname']; ?></td> 
            <td><?php echo $row['Phone']; ?></td>
            <td><?php echo $row['Address']; ?></td>
               
             
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>

<div class="form-row">


<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</div>
</div>
</form>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
     function search(){
    var UnderProdStatus = $('#UnderProdStatus').val();
    var Search = $('#Search').val();
    window.location.href="under-production-beneficiary.php?UnderProdStatus="+UnderProdStatus+"&Search="+Search;
}

     function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }

        function featured2(id) {
            if ($('#Check_Id2' + id).prop('checked') == true) {
                $('#CheckId2' + id).val(1);
            } else {
                $('#CheckId2' + id).val(0);
            }
        }
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>
</html>
