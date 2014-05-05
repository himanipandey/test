<?php 
  $accessDIDs = '';
    if( $campaigndidsAuth == false )
       $accessDIDs = "No Access";
    $smarty->assign("accessDIDs",$accessDIDs);
        
    //fecthing all existing campaign DIDs
    $all_camps = CampaignDids::all();

    $errorCampaign = '';
    if(isset($_POST)){	  
		$campaign_name = $_POST['campName'];
		$campaign_did = $_POST['campDid'];
		
		$attributes= array(
                        'campaign_name'=>$campaign_name, 
                        'campaign_did' => $campaign_did,
                        'created_at'=>'now()',                         
                        'updated_by'=>$_SESSION['adminId'],                        
                    );	
	}
  
    if($_POST['btnSave'] == 'Save'){
		        
        CampaignDids::transaction(function(){
			global $attributes,$errorCampaign;
			$res = CampaignDids::create($attributes);	
			//print "<pre>".print_r($res->errors->campaign_did,1)."</pre>";die;
			if(empty($res->errors->campaign_did[0])){
			  $errorCampaign = "<font color = 'green'>Campaign has been saved successfully!</font>";
			  header("Location:campagindids.php");
			}
			elseif(!empty($res->errors->campaign_did[0]))
		      $errorCampaign = "<font color = 'red'>Campaign DID must be unique!</font>"; 
		    else		   
		      $errorCampaign = "<font color = 'red'>Problem in saving Campaign DID please try again!</font>";
		});
		
	}elseif($_POST['btnSave'] == 'Update'){
	  $campaign = CampaignDids::find($_GET['v']);
      if(!empty($campaign)){
		$res = $campaign->update_attributes($attributes);
		header("Location:campagindids.php");
	  }else
	    $errorCampaign = "<font color = 'red'>Problem in Updating Campaign DID please try again!</font>"; 
		
	}elseif($_POST['btnExit'] == 'Exit'){
		header("Location:project_desktop.php");		
	}
	//campaign deletion
    if($_GET['edit'] == 'delete' && isset($_GET['v'])){
		$camp = CampaignDids::delete_all(array("conditions"=>array("id"=>mysql_real_escape_string($_GET['v']))));
		if($camp)
		  header("Location:campagindids.php");
		else
		  $errorCampaign = "<font color = 'red'>Problem in Deleting Campaign please try again!</font>"; 
	}elseif($_GET['edit'] == 'edit' && isset($_GET['v'])){
		$camp = CampaignDids::find($_GET['v']);
		$smarty->assign('campName',$camp->campaign_name);
		$smarty->assign('campDid',$camp->campaign_did);			
	}
	
	$smarty->assign('errorCampaign',$errorCampaign);
	$smarty->assign('all_camps',$all_camps);
	$smarty->assign('edit',$_GET['edit']);
 
?>
