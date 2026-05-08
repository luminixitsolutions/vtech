<?php
include '../config.php';

## Read value
$ProjectId = $_POST['ProjectId'];
$ProjectSubHeadId = $_POST['SubHeadId'];
$InstallStatus = $_POST['InstallStatus'];
$DispatchStatus = $_POST['DispatchStatus'];
$PoInspection = $_POST['PoInspection'];
$DataUploadStatus = $_POST['DataUploadStatus'];
$DataUploadNational = $_POST['DataUploadNational'];
$PaymentDone = $_POST['PaymentDone'];
$SubsidyRedeemed = $_POST['SubsidyRedeemed'];
$SubsidyAproved = $_POST['SubsidyAproved'];
$SubsidyDisbursed = $_POST['SubsidyDisbursed'];
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
$sql = "SELECT tu.* FROM tbl_rooftop_sell tdo 
        LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}

else if($DispatchStatus == 'No'){
    $sql3 = "SELECT GROUP_CONCAT(tdo.CustId) AS CustId FROM tbl_rooftop_sell tdo LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
    $row33 = getRecord($sql3);
    if($row33['CustId']!=''){
        $CustId = $row33['CustId'];
    }
    else{
        $CustId = 0;
    }
$sql = "SELECT tu.*,tu.Lattitude AS InstLattitude,tu.Longitude AS InstLongitude FROM tbl_users tu WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND id NOT IN ($CustId) AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}

else if($InstallStatus == 'Yes'){
$sql = "SELECT tu.*,ti.Lattitude AS InstLattitude,ti.Longitude AS InstLongitude,ti.id AS InstId FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.InstallStatus='Yes' AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}
else if($InstallStatus == 'No'){
$sql = "SELECT tu.*,tu.Lattitude AS InstLattitude,tu.Longitude AS InstLongitude FROM tbl_users tu WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND tu.InstOtpVerify=0 AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}

else if($PoInspection == 'Yes'){
$sql = "SELECT tu.*,ti.Lattitude AS InstLattitude,ti.Longitude AS InstLongitude,ti.id AS InstId FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.PoInspection='Yes' AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}
else if($PoInspection == 'No'){
$sql = "SELECT tu.* FROM tbl_users tu WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND tu.InspectionOtpVerify=0 AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
}

else{
    $sql = "SELECT tu.*,ti.Lattitude AS InstLattitude,ti.Longitude AS InstLongitude,ti.id AS InstId FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='$ProjectId' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND tu.ProjectType=2 AND tu.ProjectSubHeadId='$ProjectSubHeadId' ";
    if($_POST['DataUploadStatus']!=''){
        $sql.=" AND ti.DataUploadStatus='$DataUploadStatus'";
    }  
    if($_POST['DataUploadNational']!=''){
        $sql.=" AND ti.DataUploadNational='$DataUploadNational'";
    }   
    if($_POST['PaymentDone']!=''){
        $sql.=" AND ti.PaymentStatus='$PaymentDone'";
    } 
    if($_POST['SubsidyRedeemed']!=''){
        $sql.=" AND ti.SubsidyRedeemed='$SubsidyRedeemed'";
    } 
    if($_POST['SubsidyAproved']!=''){
        $sql.=" AND ti.SubsidyAproved='$SubsidyAproved'";
    } 
    if($_POST['SubsidyDisbursed']!=''){
        $sql.=" AND ti.SubsidyDisbursed='$SubsidyDisbursed'";
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

if($InstallStatus == 'No' || $PoInspection == 'No' || $DispatchStatus == 'Yes' || $DispatchStatus == 'No'){
    $Action = "";
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
