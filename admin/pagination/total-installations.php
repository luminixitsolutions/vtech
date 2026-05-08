<?php
include '../config.php';
include 'incuserdetails.php';
## Read value
$ProjectId = $_POST['ProjectId'];
$ProjectSubHeadId = $_POST['SubHeadId'];
$InstallStatus = $_POST['InstallStatus'];
$DispatchStatus = $_POST['DispatchStatus'];
$PoInspection = $_POST['PoInspection'];
$DgmApproval = $_POST['DgmApproval'];
$InsuranceApproval = $_POST['InsuranceApproval'];
$PaymentDone = $_POST['PaymentDone'];
$CircleOfficeStatus = $_POST['CircleOfficeStatus'];
$RmsIntegrationStatus = $_POST['RmsIntegrationStatus'];
$RmsIntegration7Days = isset($_POST['RmsIntegration7Days']) ? $_POST['RmsIntegration7Days'] : '';
$RmsIntegration90Days = isset($_POST['RmsIntegration90Days']) ? $_POST['RmsIntegration90Days'] : '';
$IcrSignDoOffice = $_POST['IcrSignDoOffice'];
$BillForward = $_POST['BillForward'];
$RoToRoAccts = $_POST['RoToRoAccts'];
$RoAcctsToZo = $_POST['RoAcctsToZo'];
$ZoToHo = $_POST['ZoToHo'];
$HoToHoAccts = $_POST['HoToHoAccts'];
$ForwardToPayment = $_POST['ForwardToPayment'];
$SentToHo = $_POST['SentToHo'];
$FileInHand = $_POST['FileInHand'];
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = " and (tu.Fname like '%".$searchValue."%' or 
        tu.Phone like '%".$searchValue."%' or 
        tu.Taluka like'%".$searchValue."%' or 
        tu.Village like'%".$searchValue."%' or 
        tu.District like'%".$searchValue."%' or 
        tu.Address like'%".$searchValue."%') ";
}

if($DispatchStatus == 'Yes'){
$sql = "SELECT tu.* FROM tbl_sell tdo 
        LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.ProjectType=1 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}

else if($DispatchStatus == 'No'){
    $sql3 = "SELECT GROUP_CONCAT(tdo.CustId) AS CustId FROM tbl_sell tdo LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.ProjectType=1 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
    $row33 = getRecord($sql3);
    if($row33['CustId']!=''){
        $CustId = $row33['CustId'];
    }
    else{
        $CustId = 0;
    }
$sql = "SELECT tu.*,tu.Lattitude AS InstLattitude,tu.Longitude AS InstLongitude FROM tbl_users tu WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND id NOT IN ($CustId) AND tu.ProjectType=1 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}

else if($InstallStatus == 'Yes'){
$sql = "SELECT tu.*,ti.Lattitude AS InstLattitude,ti.Longitude AS InstLongitude,ti.id AS InstId FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.InstallStatus='Yes' AND ti.Type=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}
else if($InstallStatus == 'No'){
$sql = "SELECT tu.* FROM tbl_users tu WHERE tu.ProjectId = '$ProjectId' AND tu.ProjectSubHeadId = '$ProjectSubHeadId' AND tu.Roll = 5 AND tu.FieldSurveyDetails = 1 AND tu.ProjectType = 1 AND tu.id NOT IN ( SELECT ti.CustId FROM tbl_installations ti WHERE ti.InstallStatus = 'Yes' AND ti.Type = 2 )";
}

else if($PoInspection == 'Yes'){
$sql = "SELECT tu.*,ti.Lattitude AS InstLattitude,ti.Longitude AS InstLongitude,ti.id AS InstId FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.PoInspection='Yes' AND ti.Type=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}
else if($PoInspection == 'No'){
    $sql = "SELECT tu.* FROM tbl_users tu WHERE tu.ProjectId = '$ProjectId' AND tu.ProjectSubHeadId = '$ProjectSubHeadId' AND tu.Roll = 5 AND tu.FieldSurveyDetails = 1 AND tu.ProjectType = 1 AND tu.id NOT IN ( SELECT ti.CustId FROM tbl_installations ti WHERE ti.PoInspection = 'Yes' AND ti.Type = 2 )";
 
}

else{
    $sql = "SELECT tu.*,ti.Lattitude AS InstLattitude,ti.Longitude AS InstLongitude,ti.id AS InstId FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.Type=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId' ";
    if($_POST['DgmApproval']!=''){
        $sql.=" AND ti.DgmApproval='$DgmApproval'";
    }  
    if($_POST['InsuranceApproval']!=''){
        $sql.=" AND ti.InsuranceApproval='$InsuranceApproval'";
    }   
    if($_POST['PaymentDone']!=''){
        $sql.=" AND ti.PaymentDone='$PaymentDone'";
    } 
    if($_POST['CircleOfficeStatus']!=''){
        $sql.=" AND ti.CircleOfficeStatus='$CircleOfficeStatus'";
    } 
    if($_POST['RmsIntegrationStatus']!=''){
        $sql.=" AND ti.RmsIntegrationStatus='$RmsIntegrationStatus'";
    } 
    if(!empty($_POST['RmsIntegration7Days'])){
        $sql.=" AND ti.RmsIntegration7Days='$RmsIntegration7Days'";
    } 
    if(!empty($_POST['RmsIntegration90Days'])){
        $sql.=" AND ti.RmsIntegration90Days='$RmsIntegration90Days'";
    } 
    if($_POST['IcrSignDoOffice']!=''){
        $sql.=" AND ti.IcrSignDoOffice='$IcrSignDoOffice'";
    }  
    if($_POST['BillForward']!=''){
        $sql.=" AND ti.BillForward='$BillForward'";
    } 
    if($_POST['RoToRoAccts']!=''){
        $sql.=" AND ti.RoToRoAccts='$RoToRoAccts'";
    } 
    if($_POST['RoAcctsToZo']!=''){
        $sql.=" AND ti.RoAcctsToZo='$RoAcctsToZo'";
    }
    if($_POST['ZoToHo']!=''){
        $sql.=" AND ti.ZoToHo='$ZoToHo'";
    } 
    if($_POST['HoToHoAccts']!=''){
        $sql.=" AND ti.HoToHoAccts='$HoToHoAccts'";
    } 
    if($_POST['ForwardToPayment']!=''){
        $sql.=" AND ti.ForwardToPayment='$ForwardToPayment'";
    } 
    if($_POST['SentToHo']!=''){
        $sql.=" AND ti.SentToHo='$SentToHo'";
    } 
    if($_POST['FileInHand']!=''){
        $sql.=" AND ti.FileInHand='$FileInHand'";
    } 
}

 if($_POST['District']){
            $District = $_POST['District'];
            if($District == 'all'){
                $sql.= " ";
            }
            else{
               $sql.= " AND tu.District='$District'";
            }
        }

## Total number of records without filtering
$totalRecords = getRow($sql);

## Total number of records with filtering
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {

   


    if ($row['ProjectType'] == '1') {
        $ProjectType = "Pump Projects";
    } else {
        $ProjectType = "Rooftop Projects";
    }
    
    // $userid = $row['id'];
    // $sql2 = "SELECT id AS InstId FROM tbl_installations WHERE CustId='$userid'";
    // $row2 = getRecord($sql2);

if($InstallStatus == 'No' || $PoInspection == 'No' || $DispatchStatus == 'Yes' || $DispatchStatus == 'No'){
    $Action = "<a href='add-pump-installation.php?id=".$row['id']."&ProjectId=".$ProjectId."&ProjectSubHeadId=".$ProjectSubHeadId."'><i class='lnr lnr-pencil mr-2'></i></a>";
}
else{
    $Action = "<a href='add-pump-installation.php?id=".$row['InstId']."&ProjectId=".$ProjectId."&ProjectSubHeadId=".$ProjectSubHeadId."'><i class='lnr lnr-pencil mr-2'></i></a>";
   
}


     $data[] = array(
            "ProjectType"=>$ProjectType,
            "BeneficiaryId"=>$row['BeneficiaryId'],
            "Fname"=>$row['Fname'] . " " . $row['Lname'],
            "Phone"=>$row['Phone'],
            "Taluka"=>$row['Taluka'],
            "Village"=>$row['Village'],
            "District"=>$row['District'],
            "Address"=>$row['Address'],
             "Lattitude"=>$row['InstLattitude'],
            "Longitude"=>$row['InstLongitude'],
            "Action"=>$Action
            
        );
}


## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
