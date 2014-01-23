<?php

	$bankid = $_REQUEST['bank_id'];
	$smarty->assign("bankid", $bankid);
	if ($_REQUEST['submit'] == "Submit")
	{
		$bankname	=	trim($_REQUEST['bankname']);
		$bank_detail=	trim($_REQUEST['bank_detail']);
		$logo_name	=	$_FILES['logo']['name'];
		
		if($logo_name != ''){
			$dest		=	$newImagePath."/bank_list/".$logo_name;
			$move		=	move_uploaded_file($_FILES['logo']['tmp_name'],$dest);
			if($move)
			{
				$s3upload = new ImageUpload($dest, array("s3" =>$s3,
				  "image_path" => str_replace($newImagePath, "", $destpath), "object" => "bank",
				   "image_type" => "logo", "object_id" => $bankid));
				// Image id updation (next three lines could be written in single line but broken
				// in three lines due to limitation of php 5.3)
				$response = $s3upload->upload();
				$image_id = $response["service"]->data();
				$image_id = $image_id->id;
			}
		 }
		 if($bankid == '')   
			$banks = new BankList();
		 else
			$banks = BankList::find($bankid);
		
		 $banks->bank_name = $bankname;
		 $banks->bank_detail = $bank_detail;
		 
		 if($logo_name !='' && $image_id){
			$banks->bank_logo = $logo_name;
            $banks->service_image_id = $image_id;
         }elseif(isset($_POST['bankLogo']) && $_POST['bankLogo'] == 'del-logo'){
			 $banks->bank_logo = '';
            $banks->service_image_id = 0;
		 }
         $banks->status = "Active"; 
        $banks->save();
		header("Location:bank_list.php?page=1&sort=all");
		
	} 
	if($bankid != ''){
		$bank = BankList::find($bankid);
		$smarty->assign("bankname",$bank->bank_name);
		$smarty->assign("bank_detail",$bank->bank_detail);
		$smarty->assign("img",$bank->bank_logo);
	}


?>
