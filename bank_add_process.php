<?php

	$bankid = $_REQUEST['bankid'];
	$smarty->assign("bankid", $bankid);
	if ($_REQUEST['submit'] == "Submit")
	{
		if($bankid == '')
		{
			$bankname	=	trim($_REQUEST['bankname']);
			$bank_detail=	trim($_REQUEST['bank_detail']);
			$logo_name	=	$_FILES['logo']['name'];
			$dest		=	$newImagePath."/bank_list/".$logo_name;
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);
			if($move)
			{
				$qry	=	"INSERT INTO ".BANK_LIST." SET 
								BANK_NAME	=	'".$bankname."',
								BANK_LOGO	=	'".$logo_name."',
								BANK_DETAIL	=	'".$bank_detail."'";
				$res	=	mysql_query($qry) or die(mysql_error()." Error in data insertion");
				header("Location:bank_list.php?page=1&sort=all");
			}
		}
	} 



?>
