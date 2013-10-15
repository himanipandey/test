<?php
        $accessUrl = '';
        if( $urlAuth == false )
           $accessUrl = "No Access";
        $smarty->assign("accessUrl",$accessUrl);
    
	$fromUrl = $_REQUEST['fromUrl'];
	$toUrl   = $_REQUEST['toUrl'];
	$smarty->assign("fromUrl", $fromUrl);
	$smarty->assign("toUrl", $toUrl);
	
	if(isset($_REQUEST['submit']))
	{
		
		$qryChkUrl = "SELECT COUNT(*) as cnt FROM resi_project WHERE  PROJECT_URL  = '".$toUrl."' and version = 'cms'";
		$resChkUrl = mysql_query($qryChkUrl);
		$dataChkUrl= mysql_fetch_assoc($resChkUrl);
		
		$qryChkUrlLoc = "SELECT COUNT(*) as cnt FROM locality WHERE  URL  = '".$toUrl."'";
		$resChkUrlLoc = mysql_query($qryChkUrlLoc);
		$dataChkUrlLoc= mysql_fetch_assoc($resChkUrlLoc);
		
		$qryChkUrlCity = "SELECT COUNT(*) as cnt FROM city WHERE  URL  = '".$toUrl."'";
		$resChkUrlCity = mysql_query($qryChkUrlCity);
		$dataChkUrlCity= mysql_fetch_assoc($resChkUrlCity);
		
		$qryChkUrlSuburb = "SELECT COUNT(*) as cnt FROM suburb WHERE  URL  = '".$toUrl."'";
		$resChkUrlSuburb = mysql_query($qryChkUrlSuburb);
		$dataChkUrlSuburb= mysql_fetch_assoc($resChkUrlSuburb);
		
		$qryChkUrlBuilder = "SELECT COUNT(*) as cnt FROM resi_builder WHERE  URL  = '".$toUrl."'";
		$resChkUrlBuilder = mysql_query($qryChkUrlBuilder);
		$dataChkUrlBuilder= mysql_fetch_assoc($resChkUrlBuilder);
		
		$msg = '';
		if(trim($fromUrl) == '')
		{
			$msg = "<font color = 'red'>Please enter From url!</font>";
		}
		if(trim($fromUrl) == '/')
		{
			$msg = "<font color = 'red'>Please enter full From url!</font>";
		}
		if($fromUrl!='')
		{
			if(!preg_match('/^[a-z0-9\-]+\.php$/',$fromUrl)){
				$msg = "<font color = 'red'>Please enter a valid From url that contains only small characters, numerics & hyphen</font>";
			}
		}
		if(trim($toUrl) == '')
		{
			$msg = "<font color = 'red'>Please enter To url!</font>";
		}
		if($fromUrl == $toUrl)
		{
			$msg = "<font color = 'red'>From url and To url can't equal!</font>";
		}
		if($toUrl != '')
		{
			if(!preg_match('/^[a-z0-9\-]+\.php$/',$toUrl)){
				$msg = "<font color = 'red'>Please enter a valid To url that contains only small characters, numerics & hyphen</font>";
			}
		}
		if($dataChkUrl['cnt'] == 0 && $dataChkUrlLoc['cnt'] == 0 && $dataChkUrlCity['cnt'] == 0 && $dataChkUrlSuburb['cnt'] == 0 && $dataChkUrlBuilder['cnt'] == 0)
		{
			$msg = "<font color = 'red'>To URL does not exists in project,locality,city,builder and suburb tables</font>";
		}
		if($msg == '')
		{
			$return = insertUpdateInRedirectTbl($toUrl,$fromUrl);
			if($return)
				$msg = "<font color = 'green'>URL $return successfully!</font>";
			else
				$msg = "<font color = 'red'>Problem in data Updation/Insertion!</font>";
			
			
		}
		$smarty->assign("msg",$msg);

	}

?>