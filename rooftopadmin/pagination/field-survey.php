<?php
session_start();
include '../config.php';
include 'incuserdetails.php';
## Read value
$ProjectType = $_POST['ProjectType'];
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
    $searchQuery = " and (tu.BeneficiaryId like '%".$searchValue."%' or 
        tu.Fname like '%".$searchValue."%' or
        tu.Phone like '%".$searchValue."%' or 
        tu.Taluka like '%".$searchValue."%' or 
        tu.Village like '%".$searchValue."%' or 
        tu.District like '%".$searchValue."%' or 
        tu.Address like '%".$searchValue."%') ";
}
if($Roll==1 || $Roll==7){
$sql = "SELECT tu.*,tut.Name As User_Type FROM tbl_users tu 
                    LEFT JOIN tbl_user_type tut ON tu.UserType=tut.id WHERE tu.Roll=5 AND tu.LeadCust=0 AND tu.ProjectType='2' AND tu.FieldSurveyStatus=1 AND tu.SurveyDetails=1";
 		}
else{
    $sql = "SELECT tu.*,tut.Name As User_Type FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tu.UserType=tut.id WHERE tu.Roll=5 AND tu.FieldSurveyId='$user_id' AND tu.ProjectType='2' AND tu.FieldSurveyStatus=1 AND tu.SurveyDetails=1"; 
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
$totalRecords = getRow($sql);

## Total number of records with filtering
$totalRecordwithFilter = getRow($sql." ".$searchQuery);

## Fetch records
$empQuery = $sql." ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();
$i=1;
while ($row = mysqli_fetch_assoc($empRecords)) {

    if($ProjectType == 1){
    include '../inc-match-survey.php';
    if ($row['FieldSurveyDetails'] == 0) {
        $SurveyDetails = '<a href="field-update-survey-status.php?id='.$row['id'].'" class="btn btn-primary btn-finish">Survey Not Done</a>';
    }
    else{
        $SurveyDetails = '<a href="field-update-survey-status.php?id='.$row['id'].'" class="btn btn-success btn-finish">Survey Done</a>';
    }

    if ($row['SurveyMatch'] == '1') {
        $SurveyMatch = "<span style='color:green;'>Matched</span>";
    }
    else{
        $SurveyMatch = "<span style='color:red;'>Not Matched</span>";
    }
}
else{
    if ($row['FieldSurveyDetails'] == 0) {
        $SurveyDetails = '<a href="rooftop-field-update-survey-status.php?id='.$row['id'].'" class="btn btn-primary btn-finish">Survey Not Done</a>';
    }
    else{
        $SurveyDetails = '<a href="rooftop-field-update-survey-status.php?id='.$row['id'].'" class="btn btn-success btn-finish">Survey Done</a>';
    }

    if ($row['SurveyMatch'] == '1') {
        $SurveyMatch = "";
    }
    else{
        $SurveyMatch = "";
    }
}

    if ($row['Status'] == '1') {
        $Status = "<span style='color:green;'>Approved</span>";
    } else {
        $Status = "<span style='color:red;'>Pending</span>";
    }

   
    $data[] = array(
    		"id"=>$i,
            "SurveyDetails"=>$SurveyDetails,
            "BeneficiaryId"=>$row['BeneficiaryId'],
    		"Fname"=>'<a href="user_management/customer-profile.php?id='.$row['id'].'" target="_new">'.$row['Fname'] . ' ' . $row['Lname'].'</a>',
            "Phone"=>$row['Phone'],
            "Taluka"=>$row['Taluka'],
            "Village"=>$row['Village'],
            "District"=>$row['District'],
            "Address"=>$row['Address'],
            "Status"=>$Status,
    		"CreatedDate"=>date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))),
    		
    	);
$i++;}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
