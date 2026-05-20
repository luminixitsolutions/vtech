<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "View-Service-Complaint";

$abstractScope = isset($_REQUEST['scope']) ? $_REQUEST['scope'] : '';
$abstractProjid = isset($_REQUEST['projid']) ? (int) $_REQUEST['projid'] : 0;
$abstractSubheadid = isset($_REQUEST['subheadid']) ? (int) $_REQUEST['subheadid'] : 0;
$abstractDistrict = isset($_REQUEST['District']) ? trim($_REQUEST['District']) : '';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Vendors Account List</title>
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

<?php include_once 'service-sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Service Complaint List
   
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               
               <th>Complaint No</th> 
               
                <th>Customer Name</th> 
                <th>Contact No</th>
               
                <th>Address</th>
             
               <th>Service Related Issue</th>
               <th>Issue/Problems</th>
                <th>Service Type</th>
             
                <th>Status</th>
                <th>Complaint Date</th>
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tc.Name As IssueName,tb.Name As BranchName FROM tbl_service_complaint tp 
            INNER JOIN tbl_users tu ON tu.id = tp.CustId
            LEFT JOIN tbl_issues tc ON tc.id=tp.Issue 
            LEFT JOIN tbl_branch tb ON tp.BranchId=tb.id WHERE tu.ProjectType=1";
            if ($abstractSubheadid > 0) {
                $sql .= " AND tu.ProjectSubHeadId = '$abstractSubheadid'";
            } elseif ($abstractProjid > 0) {
                $sql .= " AND tu.ProjectId = '$abstractProjid'";
            } elseif ($_REQUEST['subheadid'] != '') {
                $sql .= " AND tu.ProjectSubHeadId = '".$conn->real_escape_string($_REQUEST['subheadid'])."'";
            } elseif ($abstractScope === 'mtskpy') {
                $sql .= " AND tu.ProjectSubHeadId IN (SELECT id FROM tbl_project_sub_head WHERE UnderBy = 103 AND Name LIKE '%MTSKPY%')";
            } elseif ($abstractScope === 'msedcl') {
                $sql .= ' AND tu.ProjectId = 103';
            }
            if ($abstractDistrict !== '' && $abstractDistrict !== 'TOTAL') {
                if ($abstractDistrict === 'NASHIK (MALEGAON)') {
                    $sql .= " AND UPPER(TRIM(COALESCE(NULLIF(tp.District,''), tu.District,''))) IN ('NASHIK','MALEGAON')";
                } elseif ($abstractDistrict === 'AHMEDNAGAR') {
                    $sql .= " AND UPPER(TRIM(COALESCE(NULLIF(tp.District,''), tu.District,''))) IN ('AHMEDNAGAR','AHMEDNAAGAR')";
                } else {
                    $distEsc = $conn->real_escape_string(strtoupper($abstractDistrict));
                    $sql .= " AND UPPER(TRIM(COALESCE(NULLIF(tp.District,''), tu.District,''))) = '".$distEsc."'";
                }
            }
            if($_REQUEST['Status']=='Resolved'){
                $sql.=" AND tp.ClainStatus='Close'";
            } elseif($_REQUEST['Status']=='Pending'){
                $sql.=" AND tp.ClainStatus<>'Close'";
            }
            if($_REQUEST['ClainStatus']!=''){
                $sql.=" AND tp.ClainStatus='".$conn->real_escape_string($_REQUEST['ClainStatus'])."'";
            }
            if($_REQUEST['ServiceType']!=''){
                $sql.=" AND tp.ServiceType='".$conn->real_escape_string($_REQUEST['ServiceType'])."'";
            }
            if($_REQUEST['val']=='today'){
                $sql.=" AND tp.CreatedDate='".date('Y-m-d')."'";
            }
            $sql.=" ORDER BY tp.CreatedDate DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               if($row['ServiceType'] == 'Insurance'){
                $sql2 = "SELECT * FROM tbl_common_master WHERE id='".$row['InsuranceComplaint']."'";
                $row2 = getRecord($sql2);
                $Problems = $row2['Name'];
               }
               else{
                $Problems = $row['Problem']; 
               }
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><a href="view-service-complaint-action.php?id=<?php echo $row['id']; ?>"><?php echo $row['TicketNo']; ?></a></td>
              
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                 <td><?php echo $row['Address']; ?></td>
                 <td><?php echo $row['RelatedIssue']; ?></td>
                 <td><?php echo $row['IssueName']; ?></td>
                 
            <td>
               
<?php echo $row['ServiceType']; ?>
            
            </td>
                  <td><?php echo $row['ClainStatus']; ?></td>
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ComplaintDate']))); ?></td>
           
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
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
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>
</html>
