<?php 
  $accessDIDs = '';
    if( $campaigndidsAuth == false )
       $accessDIDs = "No Access";
    $smarty->assign("accessDIDs",$accessDIDs);
    
    
    print "<pre>".print_r($_POST,1)."</pre>";
    if($_POST['btnSave'] == 'Save'){
		
		$campaign_name = $_POST['campName'];
		$campaign_did = $_POST['campDid'];
		$errorCampaign = '';
		$attributes= array(
                        'campaign_name'=>$campaign_name, 
                        'campaign_did' => $campaign_did,
                        'created_at'=>'now()',                         
                        'updated_by'=>$_SESSION['adminId'],                        
                    );
        
        CampaignDids::transaction(function(){
			global $attributes,$errorCampaign;
			$res = CampaignDids::insertUpdate($attributes);
			if($res)
				$errorCampaign = "<font color = 'green'>Campaign has been inserted/updated successfully!</font>";
			else
		    	$errorCampaign = "<font color = 'red'>Problem in Campaign insertion/updation please try again!</font>"; 
		});
		
	}
	if($_POST['btnExit'] == 'Exit'){
		header("Location:project_desktop.php");		
	}
 
?>
