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
                $bank_id = mysql_insert_id();
                $s3upload = new ImageUpload($dest, array("s3" =>$s3,
                    "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
                    "image_type" => "logo", "object_id" => $bank_id));
                // Image id updation (next three lines could be written in single line but broken
                // in three lines due to limitation of php 5.3)
                $response = $s3upload->upload();
                $image_id = $response["service"]->data();
                $image_id = $image_id->id;
				header("Location:bank_list.php?page=1&sort=all");
			}
		}
	} 



?>
