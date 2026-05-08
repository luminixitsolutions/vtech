<?php
include '../config.php';

## Read value
$AgencyId = $_POST['AgencyId'];
$SchemeId = $_POST['SchemeId'];
$District = $_POST['District'];
$FromDate = $_POST['FromDate'];
$ToDate = $_POST['ToDate'];
$SurveyMatch = $_POST['SurveyMatch'];
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
	$searchQuery = " and (Fname like '%".$searchValue."%' or 
        Phone like '%".$searchValue."%' or 
        Taluka like'%".$searchValue."%' or 
    	Village like'%".$searchValue."%' or 
    	District like'%".$searchValue."%' or 
    	Address like'%".$searchValue."%' or 
    	BeneficiaryId like'%".$searchValue."%') ";
}

$sql = "SELECT tu.*,tut.Name As User_Type,ts.Name As StateName FROM tbl_users tu 
                    LEFT JOIN tbl_user_type tut ON tu.UserType=tut.id 
                     LEFT JOIN tbl_state ts ON tu.StateId=ts.id WHERE tu.Roll=5 AND tu.LeadCust=0  ";
 		if ($_POST['ProjectType']) {
            $ProjectType = $_POST['ProjectType'];
            if ($ProjectType == 'all') {
            	$sql .= " ";
            } else {
                $sql .= " AND tu.ProjectType='$ProjectType'";
            }
       	}
        
        if ($_POST['AgencyId']) {
            $AgencyId = $_POST['AgencyId'];
           	if ($AgencyId == 'all') {
                $sql .= " ";
            } else {
               	$sql .= " AND tu.AgencyId='$AgencyId'";
            }
        }
       	
       	if ($_POST['SchemeId']) {
            $SchemeId = $_POST['SchemeId'];
            if ($SchemeId == 'all') {
                $sql .= " ";
            } else {
                $sql .= " AND tu.SchemeId='$SchemeId'";
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
           
           if($_POST['StateId']){
            $StateId = $_POST['StateId'];
            if($StateId == 'all'){
                $sql.= " ";
            }
            else{
               $sql.= " AND tu.StateId='$StateId'";
            }
        }
        
           
        if($_POST['SurveyMatch']){
                $SurveyMatch = $_POST['SurveyMatch'];
                if($SurveyMatch == 'all'){
                    $sql.= " ";
                }
                else{
                    if($SurveyMatch == 2){
                        $status = 0;
                    }
                    else{
                        $status = 1;
                    }
                $sql.= " AND tu.SurveyMatch='$status'";
                }
        }
            
        if ($_POST['FromDate']) {
            $FromDate = $_POST['FromDate'];
            $sql .= " AND tu.CreatedDate>='$FromDate'";
        }

        if ($_POST['ToDate']) {
            $ToDate = $_POST['ToDate'];
            $sql .= " AND tu.CreatedDate<='$ToDate'";
        }
## Total number of records without filtering
//$sel = mysqli_query($conn,$sql);
//$records = mysqli_fetch_assoc($sel);
$totalRecords = getRow($sql);

## Total number of records with filtering
//$sel = mysqli_query($conn,$sql." ".$searchQuery);
//$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {

	$sql33 = "SELECT tu.DoneBy,tu2.Fname FROM tbl_users tu 
            LEFT JOIN tbl_users tu2 ON tu.DoneByUserId=tu2.id 
            WHERE tu.id='" . $row['id'] . "'";
    $row33 = getRecord($sql33);

if ($row['ProjectType'] == 1) {
    $sql96 = "SELECT * FROM tbl_users WHERE id='".$row['id']."' AND SurveyDetails=FieldSurveyDetails AND TelWaterSource=FieldWaterSource AND TelBoreDia=FieldBoreDia AND TelTotalDepth=FieldTotalDepth AND TelSummerWaterLevel=FieldSummerWaterLevel AND TelPumpHead=FieldPumpHead";
    $rncnt96 = getRow($sql96);
    if($rncnt96 > 0){
    $sql55 = "UPDATE tbl_users SET SurveyMatch=1 WHERE id='".$row['id']."'";
    		$conn->query($sql55);
    }
    else{
    	$sql55 = "UPDATE tbl_users SET SurveyMatch=0 WHERE id='".$row['id']."'";
    		$conn->query($sql55);
    }
    
	if ($row['SurveyDetails'] == 0) {
	$SurveyDetails = '<a href="update-survey-status.php?id='.$row['id'].'" class="btn btn-primary btn-finish">Survey Not Done</a>';
	}
    if ($row['SurveyDetails'] == 1) {
	$SurveyDetails = '<a href="update-survey-status.php?id='.$row['id'].'" class="btn btn-success btn-finish">Survey Done</a>';
    }

    if ($row['FieldSurveyDetails'] == 0) {
    	$FieldSurveyDetails = '<a href="field-update-survey-status.php?id='.$row['id'].'" class="btn btn-primary btn-finish">Survey Not Done</a>';
    }
    if ($row['FieldSurveyDetails'] == 1) {
    	$FieldSurveyDetails = '<a href="field-update-survey-status.php?id='.$row['id'].'" class="btn btn-success btn-finish">Survey Done</a>';
    }

    if ($row['SurveyMatch'] == '1') {
        $SurveyMatch = "<span style='color:green;'>Matched</span>";
    } else {
        $SurveyMatch = "<span style='color:red;'>Not Matched</span>";
    }
}
else{
    if ($row['SurveyDetails'] == 0) {
	$SurveyDetails = '<a href="rooftop-update-survey-status.php?id='.$row['id'].'" class="btn btn-primary btn-finish">Survey Not Done</a>';
	}
    if ($row['SurveyDetails'] == 1) {
	$SurveyDetails = '<a href="rooftop-update-survey-status.php?id='.$row['id'].'" class="btn btn-success btn-finish">Survey Done</a>';
    }

    if ($row['FieldSurveyDetails'] == 0) {
    	$FieldSurveyDetails = '<a href="rooftop-field-update-survey-status.php?id='.$row['id'].'" class="btn btn-primary btn-finish">Survey Not Done</a>';
    }
    if ($row['FieldSurveyDetails'] == 1) {
    	$FieldSurveyDetails = '<a href="rooftop-field-update-survey-status.php?id='.$row['id'].'" class="btn btn-success btn-finish">Survey Done</a>';
    }

    if ($row['SurveyMatch'] == '1') {
        $SurveyMatch = "";
    } else {
        $SurveyMatch = "";
    }
}
    if ($row['Status'] == '1') {
        $Status = "<span style='color:green;'>Approved</span>";
    } else {
        $Status = "<span style='color:red;'>Pending</span>";
    }

if ($row['ProjectType'] == 1) {
    $actionurl = "add-customer.php";
    $profileurl = "customer-profile.php";
}
else{
   $actionurl = "add-rooftop-customer.php";
    $profileurl = "rooftop-customer-profile.php"; 
}
    $Action = "<a href='".$actionurl."?id=".$row['id']."'><i class='lnr lnr-pencil mr-2'></i></a>&nbsp;&nbsp;
    	<a href='javascript:void(0)' onclick='deleteCust(".$row['id'].")'><i class='lnr lnr-trash text-danger'></i></a>";
    $data[] = array(
    		"SurveyDetails"=>$SurveyDetails,
    		"FieldSurveyDetails"=>$FieldSurveyDetails,
    		"SurveyMatch"=>$SurveyMatch,
    		"BeneficiaryId"=>$row['BeneficiaryId'],
    		"CheckStatus"=>'<a href="check-status.php?id='.$row['id'].'" target="_new">Check Status</a>',
    		"Fname"=>'<a href="'.$profileurl.'?id='.$row['id'].'" target="_new">'.$row['Fname'] . " " . $row['Lname'].'</a>',
    		"Phone"=>$row['Phone'],
    			"State"=>$row['StateName'],
    		"Taluka"=>$row['Taluka'],
    		"Village"=>$row['Village'],
    		"District"=>$row['District'],
    		"Address"=>$row['Address'],
    		"Status"=>$Status,
    		"Source"=>"By ".$row33['DoneBy'] . " " . $row33['Fname'],
    		"CreatedDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))),
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
