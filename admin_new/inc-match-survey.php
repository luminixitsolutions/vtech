<?php 
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
?>