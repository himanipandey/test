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
	    $optionId = $_POST['optionId'];
	    $price = $_POST['price'];

	    $discount = $_POST['discount'];
	    $expiryDate = $_POST['expiryDate'];
	    $redeemHr = $_POST['redeemHr'];
	    $totalInventory = $_POST['totalInventory'];
	    $remainInventory = $_POST['remainInventory'];
	    $mode = $_POST['mode'];


	if($mode=='update' && $couponId!==null){
		$query = "select count(*) as count from coupon_catalogue where id={$couponId}";
		$res = mysql_query($query) or die(mysql_error());
	    $data = mysql_fetch_assoc($res);
	    if($data['count'] > 0 ){
	        $query = "update coupon_catalogue set option_id={$optionId}, coupon_price={$price}, discount={$discount}, purchase_expiry_at='{$expiryDate}', total_inventory={$totalInventory}, inventory_left={$remainInventory}, updated_at=NOW(), updated_by= {$_SESSION['adminId']} where id={$couponId}";
	        $res = mysql_query($query) or die(mysql_error());
	        if(mysql_affected_rows()>0){
	        	echo "1";
	        }
	        else
	        	echo "3";
	    }
	    else
	    	die("No Such Coupon in Database.");

	}
	else{
		$query = "select count(*) as count from coupon_catalogue where option_id={$optionId}";
		$res = mysql_query($query) or die(mysql_error());
	    $data = mysql_fetch_assoc($res);
	    if($data['count'] > 0 ){
	        die("There can be only one catalogue Option Id can have  .");
	    }
	    else{
	    	$query = "insert into coupon_catalogue (option_id, coupon_price, discount, purchase_expiry_at, total_inventory, inventory_left, created_at, updated_at, updated_by) values({$optionId}, {$price},{$discount},'{$expiryDate}', {$totalInventory},{$remainInventory}, NOW(), NOW(), {$_SESSION['adminId']})";
			$res = mysql_query($query) or die(mysql_error());
			if($res){
				 echo "1";
			}
			else echo "3";
	    }
	}

}



?>