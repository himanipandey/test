<?php

	$bankid = $_REQUEST['bank_id'];
	$smarty->assign("bankid", $bankid);
	
	if ($_REQUEST['submit'] == "Submit") // add
	{
		$bankname	=	trim($_REQUEST['bankname']);
		$bank_detail=	trim($_REQUEST['bank_detail']);
		$logo_name	=	$_FILES['logo']['name'];
		
		
		 $banks = new BankList();
		 $banks->bank_name = $bankname;
		 $banks->bank_detail = $bank_detail;
		 $banks->status = "Active"; 
         $banks->save();
        
        $bankid = $banks->bank_id;
        
        if($logo_name != '' && $bankid != ''){

        	$params = array(
                        "image_type" => "logo",
                        "folder" => "bank_list/"

            );
        	$response 	= writeToImageService($s3, $_FILES['logo'], "bank", $bankid, $params, $newImagePath);
			/*$dest		=	$newImagePath."/bank_list/".$logo_name;
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);*/
			if($response['serviceResponse'])
			{
				/*$s3upload = new ImageUpload($dest, array("s3" =>$s3,
				  "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
				   "image_type" => "logo", "object_id" => $bankid));
				// Image id updation (next three lines could be written in single line but broken
				// in three lines due to limitation of php 5.3)
				$response = $s3upload->upload();*/
				$image_id = $response['serviceResponse']["service"]->data();
				$image_id = $image_id->id;
			}
		 }
        
        if($logo_name !='' && $image_id){
			$banks->bank_logo = $logo_name;
            $banks->service_image_id = $image_id;
        }
        
        $banks->save();
        
		header("Location:bank_list.php?page=1&sort=all");
		
	}else if ($_REQUEST['update'] == "Update") //edit
	{
		$bankname	=	trim($_REQUEST['bankname']);
		$bank_detail=	trim($_REQUEST['bank_detail']);
		$logo_name	=	$_FILES['logo']['name'];
		
		
		$banks = BankList::find($bankid);
		$bankid = $banks->bank_id;
        $service_image_id = $banks->service_image_id;
        
        if($logo_name != ''){

        	$params = array(
                        "image_type" => "logo",
                        "folder" => "bank_list/",
                        "action" => "update"
            );
        	$response 	= updateToImageService($s3, $_FILES['logo'], "bank", $bankid, $params, $newImagePath);
			/*$dest		=	$newImagePath."/bank_list/".$logo_name;
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);*/
			if($response['serviceResponse'])
			{
				/*$s3upload = new ImageUpload($dest, array("s3" =>$s3,
				  "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
				   "image_type" => "logo", "object_id" => $bankid));
				// Image id updation (next three lines could be written in single line but broken
				// in three lines due to limitation of php 5.3)
				$response = $s3upload->upload();*/
				$image_id = $response['serviceResponse']["service"]->data();
				$image_id = $image_id->id;
			}

        	/*
			$dest		=	$newImagePath."/bank_list/".$logo_name;
			
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);
			
			if($move)
			{
				$s3upload = new ImageUpload($dest, array("s3" =>$s3,
				  "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
				   "image_type" => "logo", "object_id" => $bankid,"service_image_id" => $service_image_id));
				// Image id updation (next three lines could be written in single line but broken
				// in three lines due to limitation of php 5.3)
				$response = $s3upload->update();
				$image_id = $response["service"]->data();
				$image_id = $image_id->id;
			}*/
		 }
		 
		$banks->bank_name = $bankname;
		$banks->bank_detail = $bank_detail;
		
	     if($logo_name !='' && $image_id){
			$banks->bank_logo = $logo_name;
            $banks->service_image_id = $image_id;
         }elseif(isset($_POST['bankLogo']) && $_POST['bankLogo'] == 'del-logo'){
         	$deleteVal = deleteFromImageService("bank", $bankid, $service_image_id);
			$banks->bank_logo = '';
            $banks->service_image_id = 0;
		 }
       
        
        $banks->save();
        
		header("Location:bank_list.php?page=1&sort=all");
		
	}  
	
	if($bankid != ''){
		$bank = BankList::find($bankid);
		$smarty->assign("bankname",$bank->bank_name);
		$smarty->assign("bank_detail",$bank->bank_detail);
		$smarty->assign("img",$bank->bank_logo);
		$smarty->assign("service_image_id",$bank->service_image_id);
	}


?>
