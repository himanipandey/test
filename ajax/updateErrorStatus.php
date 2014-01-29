<?php
include("../dbConfig.php");
$adminId = $_POST['adminid'];
$completeData = $_POST['completeData'];
$completeDataArray = explode(',',$completeData);
$order_current_status = $_POST['current_status_val'];
$orderCurrStatusArray = explode(',',$order_current_status);
$cnt = count($completeDataArray);
if($completeDataArray[0]=='_undefined@undefined'){
	unset($completeDataArray[0]);
	$completeDataArray = array_values($completeDataArray);
}
for ($i=0; $i<$cnt; $i++){
	$errIDAndCommentMsg = explode('_',$completeDataArray[$i]);
	$errid = $errIDAndCommentMsg[0];
	$comments = $errIDAndCommentMsg[1];
    $orderCurrStatus = explode('#',$orderCurrStatusArray[$i]);
    $ord_status = $orderCurrStatus[0];
        
	//qry for inserting comments for ERROR DETAILS
    $insertCode = "INSERT INTO proptiger.RESI_PROJECT_ERROR_DETAILS SET
                   ERROR_ID		 = '".$errid."',
                   USER_ID		 = '".$adminId."',
                   STATUS_ID	 = '".$ord_status."',
                   DATE          = now(),
                   COMMENTS		 ='".$comments."'";
    mysql_query($insertCode) or die(mysql_error());
	if(mysql_affected_rows()>0){
		$status[] = "updated";
	}else{
        $status[] = "error";
    }
}
if(!in_array("error" , $status)){
    echo "Success";
}else{
    echo "Error";
}
?>
