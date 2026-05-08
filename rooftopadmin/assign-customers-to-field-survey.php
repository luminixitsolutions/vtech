<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Pump-Customers";
$Page = "Assign-Customers-Field-Survey";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | State</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
<!-- Libs -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'header.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php

if(isset($_POST['submit'])){

   $number = count($_POST['CheckId']);

   $FieldSurveyId = $_POST['FieldSurveyId'];
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $CustId = addslashes(trim($_POST['CustId'][$i]));
                $sql = "UPDATE tbl_users SET FieldSurveyStatus='1',FieldSurveyId='$FieldSurveyId',FieldSurveyAssignDate='$CreatedDate' WHERE id='$CustId'";
                $conn->query($sql);

                $sql = "SELECT PumpCapacity FROM tbl_users WHERE id='$CustId'";
                $row = getRecord($sql);
                $PumpCapacity = $row['PumpCapacity'];

                $sql2 = "SELECT FieldSurveyVal FROM tbl_contractor_commision WHERE UserId='$FieldSurveyId' AND Capacity='$PumpCapacity'";
                $row2 = getRecord($sql2);
                $Amount = $row2['FieldSurveyVal'];

                $sql = "DELETE FROM tbl_made_contractor_commision WHERE CustId='$CustId' AND Roll=2";
                $conn->query($sql);

                $sql = "INSERT INTO tbl_made_contractor_commision SET ContractorId='$FieldSurveyId',CustId='$CustId',Capacity='$PumpCapacity',ScopeOfWork='Field Survey',Amount='$Amount',CreatedDate='$CreatedDate',Roll=2";
                $conn->query($sql);

                }
              }
            }
        }
        
   

        echo "<script>alert('Customer Assign To Co-ordinator');window.location.href='assign-customers-to-field-survey.php?FieldSurveyStatus=0';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Customers To Field Survey
</h4><br>

<div class="card" style="padding:10px;">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                
  <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                   
                                                    <div class="form-group col-md-3">
        <label class="form-label">State <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="StateId" name="StateId">
<option selected="" value="all">All State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

<div class="form-group col-md-2">
        <label class="form-label">Village <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="Village" name="Village">
<option selected="" value="all">All Village</option>
 <?php 
        $q = "select DISTINCT(Village) AS Village from tbl_users WHERE Village!='' AND ProjectType='2'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Village']==$rw['Village']){ ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
              <?php } ?>
</select>
    </div>
    
    <div class="form-group col-md-2">
        <label class="form-label">District <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="District" name="District">
<option selected="" value="all">All District</option>
 <?php 
        $q = "select DISTINCT(District) AS District from tbl_users WHERE District!='' AND ProjectType='2'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
    </div>

     <div class="form-group col-md-2">
                                                        <label class="form-label">Assign Status </label>
                                                        <select class="form-control" id="FieldSurveyStatus" name="FieldSurveyStatus">
                                                        <option value="all" selected>All</option>
                                    <option value="1" <?php if($_REQUEST['FieldSurveyStatus']==1){ ?> selected <?php } ?> >Assign</option>
                                    <option value="0"  <?php if($_REQUEST['FieldSurveyStatus']==0){ ?> selected <?php } ?> >Not Assign</option>
                                                          
                                                        </select>
                                                    </div>
    
                                                    <input type="hidden" id="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:30px;">
                                                        <button type="button" onclick="search()" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_REQUEST['Search'])) { ?>
                                                        <div class="form-group col-md-1">
                                                            <label class="form-label">&nbsp;</label>
                                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </form>

       

 


                                            </div>
                                        </div>
                                    </div>
   </div>
   <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
               <th>Consumer No</th>
                <th>Customer Name</th>
                <th>Contact No</th>
               
                <th>State</th>
                <th>Village</th>
                <th>District</th>
                <th>Surveyor</th>
                <th>Assign Date</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
           
            $sql = "SELECT ts.*,tu.Fname AS CoName,tcm.Name As Pump_Capacity,ts2.Name As StateName FROM tbl_users ts 
                    LEFT JOIN tbl_users tu ON tu.id=ts.FieldSurveyId 
                    LEFT JOIN tbl_rooftop_common_master tcm ON tcm.id=ts.PumpCapacity 
                    LEFT JOIN tbl_state ts2 ON ts2.id=ts.StateId 
                    WHERE ts.Status=1 AND ts.ProjectType=2 AND ts.SurveyDetails=1";

            if($_REQUEST['FieldSurveyStatus']!=''){
                if($_REQUEST['FieldSurveyStatus'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.FieldSurveyStatus='".$_REQUEST['FieldSurveyStatus']."'";
                }
            }
                    
            /*if($_REQUEST['PumpCapacity']!=''){
                if($_REQUEST['PumpCapacity'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.PumpCapacity='".$_REQUEST['PumpCapacity']."'";
                }
            }*/
            if($_REQUEST['StateId']!=''){
                if($_REQUEST['StateId'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.StateId='".$_REQUEST['StateId']."'";
                }
            }
            if($_REQUEST['Village']!=''){
                if($_REQUEST['Village'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.Village='".$_REQUEST['Village']."'";
                }
            }
            if($_REQUEST['District']!=''){
                if($_REQUEST['District'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND ts.District='".$_REQUEST['District']."'";
                }
            }
            
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
                $sql22 = "SELECT * FROM tbl_users WHERE FieldSurveyStatus=1 AND id='".$row['id']."'";
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
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                 <input type="hidden" value="<?php echo $row['id']; ?>" name="CustId[]">
               <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
               <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
               <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">
                <td><?php echo $row['BeneficiaryId']; ?></td>
              <td><?php echo $row['Fname']; ?></td>
              <td><?php echo $row['Phone']; ?></td>
            
            <td><?php echo $row['StateName']; ?></td>
            <td><?php echo $row['Village']; ?></td>
            <td><?php echo $row['District']; ?></td>
              <td><?php echo $row['CoName']; ?></td>
               <td><?php if($row['FieldSurveyAssignDate']==''){echo "";} else { echo date("d/m/Y", strtotime(str_replace('-', '/',$row['FieldSurveyAssignDate']))); } ?></td>  
          
             
          
           
              
            </tr>
           <?php  $i++;} ?>
        </tbody>
    </table>
</div>
<div class="form-row">
 <div class="form-group col-lg-4">
<label class="form-label"> Field Survey/Contractor<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="FieldSurveyId" id="FieldSurveyId" required>
<option selected="" value="">Select</option>
 <?php 
//   $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(34,35,36,37) AND Options LIKE '%85%'";
$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(40)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
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


    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
<script type="text/javascript">
function search(){
    var PumpCapacity = $('#PumpCapacity').val();
    var StateId = $('#StateId').val();
    var District = $('#District').val();
    var Village = $('#Village').val();
    var Search = $('#Search').val();
    var FieldSurveyStatus = $('#FieldSurveyStatus').val();
    window.location.href="assign-customers-to-field-survey.php?PumpCapacity="+PumpCapacity+"&StateId="+StateId+"&District="+District+"&Village="+Village+"&Search="+Search+"&FieldSurveyStatus="+FieldSurveyStatus;
}
     function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
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
