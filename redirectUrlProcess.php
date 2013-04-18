<?php

	$fromUrl = $_REQUEST['fromUrl'];
	$toUrl   = $_REQUEST['toUrl'];
	$smarty->assign("fromUrl", $fromUrl);
	$smarty->assign("toUrl", $toUrl);
	
	if(isset($_REQUEST['submit']))
	{
		
		$qryChkUrl = "SELECT COUNT(*) as cnt FROM resi_project WHERE  PROJECT_URL  = '".$toUrl."'";
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
		if($fromUrl == '')
		{
			$msg = "<font color = 'red'>Please enter From url!</font>";
		}
		if($fromUrl!='')
		{
			if(!preg_match('/^[a-z0-9\-]+\.php$/',$fromUrl)){
				$msg = "<font color = 'red'>Please enter a valid From url that contains only small characters, numerics & hyphen</font>";
			}
		}
		if($toUrl == '')
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
			$qrySel = "SELECT * FROM redirect_url_map WHERE FROM_URL = '$fromUrl'";
			$resSel = mysql_query($qrySel) or die(mysql_error()." error");
			$action = '';
			if(mysql_num_rows($resSel)==0)
			{
				$qry = "INSERT INTO redirect_url_map 
						SET
							FROM_URL		=	'$fromUrl',
							TO_URL			=	'$toUrl',
							SUBMITTED_DATE	=	now(),
							SUBMITTED_BY	=	".$_SESSION['adminId'];
				$action = 'Insertion';
			}
			else
			{
				$qry = "UPDATE redirect_url_map
						SET
							TO_URL			=	'$toUrl',
							MODIFIIED_DATE	=	now(),
							MODIFIED_BY		=	".$_SESSION['adminId']."
						WHERE
							FROM_URL		=	'$fromUrl'";
				$action = 'Updation';
			}
			
			$res   = mysql_query($qry) or die(mysql_error());
			if($res)
				$msg = "<font color = 'green'>URL $action successfully!</font>";
			else
				$msg = "<font color = 'red'>Problem in data $action!</font>";
			
			
		}
		$smarty->assign("msg",$msg);

	}

?>