<?php
session_start();
include '../../config.php';
include 'incuserdetails.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$Roll = $_POST['Roll'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$columnSortOrder = "desc";
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (tp.TicketNo like '%".$searchValue."%' or 
        tp.CustName like '%".$searchValue."%' or
        tp.CellNo like '%".$searchValue."%' or 
        tp.Address like '%".$searchValue."%' or 
    tp.ClainStatus like '%".$searchValue."%') ";
}

    if($Roll == 1 || $Roll == 7){
            $sql = "SELECT ts.*,tu.Fname FROM tbl_rooftop_leads ts 
            LEFT JOIN tbl_users tu ON ts.CustId=tu.id WHERE ts.Status=1";
        }
        else{
            $sql = "SELECT ts.*,tu.Fname FROM tbl_rooftop_leads ts 
            LEFT JOIN tbl_users tu ON ts.CustId=tu.id WHERE ts.AllocateId='$user_id'";
        }

        if($_POST['ClainReason']){
                $ClainReason = $_POST['ClainReason'];
                if($ClainReason == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.ClainReason='$ClainReason'";
                }
            }
           
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.CreatedDate<='$ToDate'";
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
       
       $sql3 = "SELECT tpf.*,tu.Fname,tu.Lname FROM tbl_rooftop_lead_details tpf LEFT JOIN tbl_users tu ON tpf.CreatedBy=tu.id WHERE tpf.CompId='".$row['id']."' AND tpf.CreatedDate='".date('Y-m-d')."' ORDER BY tpf.id DESC LIMIT 1";
                $rncnt3 = getRow($sql3);
                $row3 = getRecord($sql3);
                if($rncnt3 > 0){
                    $bcolor = "background-color: antiquewhite;";
                }
                else{
                    $bcolor = "";
                }
                $action = '<a href="javascript:void(0)" onclick="getFeedback('.$row['id'].')" class="btn btn-primary btn-finish" style="padding: 0.5px 1rem">Open</a>';
        if($row3['CreatedDate'] == '' || $row3['CreatedDate'] == '0000-00-00'){ $createddate="";} else {$createddate= date("d/m/Y", strtotime(str_replace('-', '/',$row3['CreatedDate'])));}

        if($row3['NextDate'] == '' || $row3['NextDate'] == '0000-00-00'){ $nextdate="";} else {$nextdate= date("d/m/Y", strtotime(str_replace('-', '/',$row3['NextDate'])));}
        
    $data[] = array(
            "id"=>$i,
            "CustName"=>$row['CustName'],
            "CellNo"=>$row['CellNo'],
            "Source"=>$row['ClainReason']." ".$row['Fname'],
            "TeleCallerName"=>$row3['Fname']." ".$row3['Lname'],
            "CallingDate"=>$createddate,
            "Talk"=>$row3['Message'],
            "AfterDate"=>$nextdate,
            "Time"=>$row3['NextTime'],
            "Feedback"=>$action
        
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
