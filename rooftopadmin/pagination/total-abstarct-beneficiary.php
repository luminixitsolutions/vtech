<?php
include '../config.php';

## Read value
$ProjectId = $_POST['ProjectId'];
$ProjectSubHeadId = $_POST['subheadid'];
$roll = $_POST['roll'];
$dist = $_POST['dist'];
$val2 = $_POST['val'];
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

if($roll == 'totapp'){
$sql = "SELECT * FROM tbl_users WHERE ProjectType=2 AND ProjectId='$ProjectId' AND District!='' AND ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND District='$dist'";
}
}
if($roll == 'capacity'){
$sql = "SELECT * FROM tbl_users WHERE PumpCapacity='$val2' AND ProjectType=2 AND  ProjectId='$ProjectId' AND District!='' AND ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND District='$dist'";
}
}
if($roll == 'surveydone'){
$sql = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=2 AND ProjectId='$ProjectId' AND District!='' AND ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND District='$dist'";
}
}
if($roll == 'surveyrejected'){
$sql = "SELECT * FROM tbl_users WHERE SurveyMatch='$val2' AND ProjectType=2 AND ProjectId='$ProjectId' AND District!='' AND ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND District='$dist'";
}
}
if($roll == 'surveypending'){
$sql = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=2 AND ProjectId='$ProjectId' AND District!='' AND ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND District='$dist'";
}
}
if($roll == 'dispatch'){
$sql = "SELECT * FROM tbl_rooftop_sell ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
}
}
if($roll == 'installation'){
$sql = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND ts.InstallStatus='$val2' AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'"; 
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
}
}
if($roll == 'meterinstall'){
$sql = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND ts.MeterInstDiscom='$val2' AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
}
}
if($roll == 'dataupload'){
$sql = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND ts.DataUploadStatus='$val2' AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
}
}
if($roll == 'inspection'){
$sql = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND ts.PoInspection='$val2' AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
}
}
if($roll == 'inspectiondis'){
$sql = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND ts.InspectionDiscrepancy='$val2' AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
}
}

if($roll == 'paymentstatus'){
$sql = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND ts.PaymentStatus='$val2' AND tu.ProjectId='$ProjectId' AND tu.District!='' AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
if($dist !=''){
    $sql.=" AND tu.District='$dist'";
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
     $Action = "<a href='add-pump-installation.php?id=".$row['InstId']."&ProjectId=".$ProjectId."'><i class='lnr lnr-pencil mr-2'></i></a>";
   
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
