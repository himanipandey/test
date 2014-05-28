<?php 
    $smarty->assign('arrCampaign', CampaignDids::allCampaign());
    $error = '';
    if( isset($_REQUEST['callId']) ) {
        $callId  = $_REQUEST['callId'];
        $status  = $_REQUEST['status'];
        $remark  = $_REQUEST['remark'];
        $mobile  = $_REQUEST['mobile'];
         
         $ins = "UPDATE 
                        CallDetails
                    SET
                        Remark = '".$remark."',
                        CallStatus = '".$status."'
                    WHERE
                        CallId = $callId";
            $res = mysql_query($ins) or die(mysql_error());
            
        if( $status === 'success' ) {
            if($res)
                header("Location:brokeradd.php?callId=$callId&mobile=$mobile");
            else
                $error = "Problem in call detail update";
        }
        else {
            header("Location:callToBroker.php");
        }
        
    }
     $smarty->assign("error", $error);
?>
