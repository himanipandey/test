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
        $altText = $bankname." "."Home Loan";
        if($logo_name != '' && $bankid != ''){

        	$params = array(
                        "image_type" => "logo",
                        "folder" => "bank_list/",
                        "image" => $logo_name,
                        "title" => $bankname,
                        "altText" => $altText,

            );
            $dest		=	$newImagePath."bank_list/".$logo_name;
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);
        	$response 	= writeToImageService(  $_FILES['logo'], "bank", $bankid, $params, $newImagePath);
			/**/
			if(empty($response['serviceResponse']["service"]->response_body->error->msg))
			{
				/*$s3upload = new ImageUpload($dest, array("s3" =>$s3,
				  "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
				   "image_type" => "logo", "object_id" => $bankid));
				// Image id updation (next three lines could be written in single line but broken
				// in three lines due to limitation of php 5.3)
				$response = $s3upload->upload();*/
				$image_id = $response['serviceResponse']["service"]->response_body->data->id;
				//$image_id = $image_id->id;
			}
			else {
				$Error = $response['serviceResponse']["service"]->response_body->error->msg;
				
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
		if($_REQUEST['image_id'])
			$service_image_id = $_REQUEST['image_id'];
		else
        	$service_image_id = 0;
        $altText = $bankname." "."Home Loan";
        

        if($logo_name != ''){
	        if($service_image_id>0){
	        	$params = array(
	                        "image_type" => "logo",
	                        "title" => $bankname,
	                        "folder" => "bank_list/",
	                        "image" => $logo_name,
	                        "update" => "update",
	                        "altText" => $altText,
	                        "service_image_id" => $service_image_id
	            );
	        }
	        else{
	        	$params = array(
	                        "image_type" => "logo",
	                        "title" => $bankname,
	                        "folder" => "bank_list/",
	                        "image" => $logo_name,
	                        
	                        "altText" => $altText,
	                        
	            );
	        }


            $dest		=	$newImagePath."bank_list/".$logo_name;
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);
        	$response 	= writeToImageService(  $_FILES['logo'], "bank", $bankid, $params, $newImagePath);
			/**/
			if(empty($response['serviceResponse']["service"]->response_body->error->msg))
			{
				/*$s3upload = new ImageUpload($dest, array("s3" =>$s3,
				  "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
				   "image_type" => "logo", "object_id" => $bankid));
				// Image id updation (next three lines could be written in single line but broken
				// in three lines due to limitation of php 5.3)
				$response = $s3upload->upload();*/
				$image_id = $response['serviceResponse']["service"]->response_body->data->id;
			
			}
			else {
				$Error = $response['serviceResponse']["service"]->response_body->error->msg;
			
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
         	$service_image_id = $_REQUEST['image_id'];
         	$deleteVal = deleteFromImageService("bank", $bankid, $service_image_id);
			$banks->bank_logo = '';
            $banks->service_image_id = 0;
		 }
       
        
        $banks->save();
        if(empty($Error))
		header("Location:bank_list.php?page=1&sort=all");
		
	}  
	
	if($bankid != ''){
		$bank = BankList::find($bankid);
		$smarty->assign("bankname",$bank->bank_name);
		$smarty->assign("bank_detail",$bank->bank_detail);
		$smarty->assign("img",$bank->bank_logo);
		//$smarty->assign("service_image_id",$bank->service_image_id);
		$smarty->assign("Error",$Error);
	}


?>
