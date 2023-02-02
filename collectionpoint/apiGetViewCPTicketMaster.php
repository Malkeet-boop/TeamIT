<?php
session_start();
// Include class definition
include_once "function.php";
$sign=new Signup();
include_once("commonFunctions.php");
$commonfunction=new Common();

$settingValueOngoingStatus=$commonfunction->getSettingValue("Ongoing Status"); 
$settingValuePendingStatus=$commonfunction->getSettingValue("Pending Status"); 
$cp_id = $_SESSION["cp_id"];
/* $qry="select ts.id,ts.subject,ts.ticket_date,tvm.verification_status from tw_ticket_system ts INNER JOIN tw_verification_status_master tvm ON ts.status = tvm.id where module_id=" . $cp_id . " order by id desc";

$qry1="select count(*) as cnt from tw_ticket_system where module_id='".$cp_id ."'";
 */
$qry="Select ts.id,ts.sender_id,ts.subject,ts.ticket_date,vsm.verification_status,ts.panel from tw_ticket_system ts INNER JOIN tw_verification_status_master vsm ON ts.status=vsm.id where (ts.status='".$settingValueOngoingStatus."' or ts.status='".$settingValuePendingStatus."') and ts.sender_id='".$cp_id."' and ts.panel='CollectionPoint'";

$qry1="select count(*) as cnt from tw_ticket_system where (status='".$settingValueOngoingStatus."' or status='".$settingValuePendingStatus."') and sender_id='".$cp_id."' and panel='CollectionPoint'";


$retVal = $sign->FunctionJSON($qry);
$retVal1 = $sign->Select($qry1);

$decodedJSON2 = json_decode($retVal);
$count = 0;
$i = 1;
$x=$retVal1;
$table="";
$it=1;
$table.="<thead><tr><th>SR.NO</th><th>Subject</th><th>Ticket date</th><th>Status</th><th>View</th></tr></thead><tbody>";

while($x>=$i){
	$id = $decodedJSON2->response[$count]->id;
	$count=$count+1;
	$sender_id = $decodedJSON2->response[$count]->sender_id;
	$count=$count+1;
	$subject = $decodedJSON2->response[$count]->subject;
	$count=$count+1;
	$ticket_date = $decodedJSON2->response[$count]->ticket_date;
	$count=$count+1;
	$status = $decodedJSON2->response[$count]->verification_status;
	$count=$count+1;
	$panel = $decodedJSON2->response[$count]->panel;
	$count=$count+1;
	
		$table.="<tr>";
		$table.="<td>".$it."</td>"; 
		$table.="<td>".$subject."</td>";
		$table.="<td>".date("d-m-Y H:i:s",strtotime($ticket_date))."</td>";
		$table.="<td>".$status."</td>";
		$table.="<td><a href='javascript:void(0)' onclick='ViewRecord(".$id.")'><i class='ti-eye'></a></td>";
		$it++;
		$table.="</tr>";

	$i=$i+1;
}
$table.="</tbody>";
echo $table;
?>
	