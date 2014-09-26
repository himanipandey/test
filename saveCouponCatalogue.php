<?php
//echo "here"; //die;
error_reporting(1);
ini_set('display_errors','1');
set_time_limit(0);
ini_set("memory_limit","256M");
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
//include("builder_function.php");
//include("function/functions_priority.php");
//include("common/function.php");
//include("imageService/image_upload.php");
//include_once("includes/send_mail_amazon.php");

AdminAuthentication();
$task = $_POST['task'];

if($task=='get_options'){
	$options = array();
	$projectId = $_POST['projectId'];
	$query = "select * from resi_project_options rpo where project_id={$projectId} and option_category='Actual'";
	$res = mysql_query($query) or die(mysql_error());
	while($data = mysql_fetch_assoc($res)){
		array_push($options, $data);
	}
	echo htmlentities(json_encode($options));

}

if($task=='create_coupon'){
		$couponId = $_POST['id'];
		$projectId = $_POST['projectId'];
	    $optionId = $_POST['optionId'];
	    $price = $_POST['price'];
	    $discountType = $_POST['discountType'];
	    $discount = $_POST['discount'];
	    $expiryDate = $_POST['expiryDate'];
	    $redeemHr = $_POST['redeemHr'];
	    $totalInventory = $_POST['totalInventory'];
	    $remainInventory = $_POST['remainInventory'];
	    $mode = $_POST['mode'];

	    if($optionId==0){
	    	//die("here");
		    $options = array();
		    $query = "select rpo.OPTIONS_ID, rpo.SIZE from resi_project_options rpo where project_id={$projectId} and option_category='Actual'";
			$res = mysql_query($query) or die(mysql_error());
			while($data = mysql_fetch_assoc($res)){
				array_push($options, $data);
			}
		}



	if($mode=='update' && $couponId!==null){
		if($optionId==0){

			foreach ($options as $k => $v) {
				$query = "select count(*) as count from coupon_catalogue where option_id={$v['OPTIONS_ID']}";
				$res = mysql_query($query) or die(mysql_error());
			    $data = mysql_fetch_assoc($res);
			    if($data['count'] > 0 ){
			    	$discounttmp =0;
			    	$discounttmp = $discount*$v['SIZE'];
				    $query = "update coupon_catalogue set option_id={$v['OPTIONS_ID']}, coupon_price={$price}, discount={$discounttmp}, purchase_expiry_at='{$expiryDate}', total_inventory={$totalInventory}, inventory_left={$remainInventory}, updated_at=NOW(), updated_by= {$_SESSION['adminId']} where id={$couponId}";
			        $res = mysql_query($query) or die(mysql_error());
			        if(mysql_affected_rows()>0){
			        	echo "option id {$v['OPTIONS_ID']} updated.<br>";
			        }
			        else
			        	echo "option id {$v['OPTIONS_ID']} could not be updated.<br>";
			    }
			    else
			    	echo "option id {$v['OPTIONS_ID']} does not exist. <br>";
			}
		}
		else{

			$query = "select count(*) as count from coupon_catalogue where id={$couponId}";
			$res = mysql_query($query) or die(mysql_error());
		    $data = mysql_fetch_assoc($res);
		    if($data['count'] > 0 ){
		    	if($discountType==1){
		    		$query = "select SIZE from resi_project_options where options_id={$optionId}";
					$res = mysql_query($query) or die(mysql_error());
				    $data = mysql_fetch_assoc($res);
				    //$discounttmp =0;
		    		$discount = $discount*$data['SIZE'];
		    	}


		        $query = "update coupon_catalogue set option_id={$optionId}, coupon_price={$price}, discount={$discount}, purchase_expiry_at='{$expiryDate}', total_inventory={$totalInventory}, inventory_left={$remainInventory}, updated_at=NOW(), updated_by= {$_SESSION['adminId']} where id={$couponId}";
		        $res = mysql_query($query) or die(mysql_error());
		        if(mysql_affected_rows()>0){
		        	echo "coupon successfully updated.";
		        }
		        else
		        	echo "coupon could not be updated.";
		    }
		    else
		    	die("No Such Coupon in Database.");
		}

	}
	else{
		if($optionId==0){
			foreach ($options as $k => $v) {
				$query = "select count(*) as count from coupon_catalogue where option_id={$v['OPTIONS_ID']}";
				$res = mysql_query($query) or die(mysql_error());
			    $data = mysql_fetch_assoc($res);
			    if($data['count'] > 0 ){
			        echo "option id {$v['OPTIONS_ID']} already exist. <br>";
			    }
			    else{
			    	$discounttmp =0;
			    	$discounttmp = $discount*$v['SIZE'];
			    	$query = "insert into coupon_catalogue (option_id, coupon_price, discount, purchase_expiry_at, total_inventory, inventory_left, created_at, updated_at, updated_by) values({$v['OPTIONS_ID']}, {$price},{$discounttmp},'{$expiryDate}', {$totalInventory},{$remainInventory}, NOW(), NOW(), {$_SESSION['adminId']})";
					$res = mysql_query($query) or die(mysql_error());
					if($res){
					 echo "coupon created for {$v['OPTIONS_ID']} <br>";
					}
					else echo "coupon could not be created for {$v['OPTIONS_ID']} <br>";
					
			    }


			}

		}
		else{

			$query = "select count(*) as count from coupon_catalogue where option_id={$optionId}";
			$res = mysql_query($query) or die(mysql_error());
		    $data = mysql_fetch_assoc($res);
		    if($data['count'] > 0 ){
		        die("There can be only one catalogue against an Option.");
		    }
		    else{
		    	if($discountType==1){
		    		$query = "select SIZE from resi_project_options where options_id={$optionId}";
					$res = mysql_query($query) or die(mysql_error());
				    $data = mysql_fetch_assoc($res);
				    //$discounttmp =0;
		    		$discount = $discount*$data['SIZE'];
		    	}
		    	$query = "insert into coupon_catalogue (option_id, coupon_price, discount, purchase_expiry_at, total_inventory, inventory_left, created_at, updated_at, updated_by) values({$optionId}, {$price},{$discount},'{$expiryDate}', {$totalInventory},{$remainInventory}, NOW(), NOW(), {$_SESSION['adminId']})";
				$res = mysql_query($query) or die(mysql_error());
				if($res){
					 echo "coupon sucessfully created.";
				}
				else echo "coupon not created.";
		    }
		}
	}

}



?>