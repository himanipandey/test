<?php
error_reporting ( 1 );
ini_set ( 'display_errors', '1' );
set_time_limit ( 0 );
ini_set ( "memory_limit", "256M" );
include ("smartyConfig.php");
include ("appWideConfig.php");
include ("dbConfig.php");
include ("includes/configs/configs.php");
include ("builder_function.php");
include ("function/functions_priority.php");
include ("common/function.php");
include ("imageService/image_upload.php");
include_once ("includes/send_mail_amazon.php");

$host = "http://localhost:8080/userservice/";

AdminAuthentication ();

// /echo "here"; die;
if ($_POST ['task'] == 'office_locations') {
	$cityId = $_POST ['cityId'];
	// $locList = Locality::getLocalityByCity($cityId);
	$query = "select l.locality_id, l.label from locality l 
        inner join suburb s on s.suburb_id=l.suburb_id
    where s.city_id='{$cityId}'";
	$res = mysql_query ( $query ) or die ( mysql_error () );
	
	$html = "";
	while ( $data = mysql_fetch_assoc ( $res ) ) {
		$html .= "<option value='" . $data ['locality_id'] . "' >" . $data ['label'] . "</option>";
	}
	
	echo $html;
}

if ($_POST ['task'] == 'createAgent') {
	
	$agentId = $_POST ['id'];
	$userId = $_POST ['userId'];
	$brokerId = $_POST ['brokerId'];
	$name = $_POST ['name'];
	$address = $_POST ['address'];
	$address = preg_replace ( '!\s+!', ' ', $address );
	$city = $_POST ['city'];
	$pin = $_POST ['pincode'];
	$compphone = $_POST ['compphone'];
	$phone = $_POST ['phone'];
	$email = $_POST ['email'];
	$role = $_POST ['agent_role'];
	$qualification = $_POST ['qualification'];
	$active_since = $_POST ['active_since'];
	$status = $_POST ['status'];
	$mode = $_POST ['mode'];
	
	if ($mode == 'update' && $agentId !== null) {
		$sql_comp = mysql_query ( "select * from company_users where id='{$agentId}'" ) or die ( mysql_error () );
		if (mysql_num_rows ( $sql_comp ) > 0) {
			$contactNumbers = array ();
			
			$personalContact = array (
					"contactNumber" => $phone,
					"priority" => 1 
			);
			array_push ( $contactNumbers, $personalContact );
			if (! empty ( $compphone )) {
				$companyContact = array (
						"contactNumber" => $compphone,
						"priority" => 2 
				);
				array_push ( $contactNumbers, $companyContact );
			}
			$user = array (
					"contactNumbers" => $contactNumbers 
			);
			
			$post = array (
					"name" => $name,
					"email" => $email,
					"address" => $address,
					"city" => $city,
					"pinCode" => $pin,
					"countryId" => "+91",
					"sellerType" => $role,
					"academicQualificationId" => $qualification,
					"activeSince" => $active_since,
					"status" => $status,
					"companyId" => $brokerId,
					"updatedBy" => $_SESSION ['adminId'],
					"user" => $user 
			);
			
			$companyUserPutApi = $host . "data/v1/entity/company/company-users/companyUserId/$agentId";
			$response = curl_request ( json_encode ( $post ), 'PUT', $companyUserPutApi );
			echo "1";
		} else if (! mysql_error ())
			echo "2";
		else
			echo "3";
	}
	if ($mode == 'create') {
		$contactNumbers = array ();
		$personalContact = array (
				"contactNumber" => $phone,
				"priority" => 1 
		);
		array_push ( $contactNumbers, $personalContact );
		if (! empty ( $compphone )) {
			$companyContact = array (
					"contactNumber" => $compphone,
					"priority" => 2 
			);
			array_push ( $contactNumbers, $companyContact );
		}
		$user = array (
				"contactNumbers" => $contactNumbers,
				"password" => $pass,
				"countryId" => "+91" 
		);
		$post = array (
				"name" => $name,
				"email" => $email,
				"address" => $address,
				"city" => $city,
				"pinCode" => $pin,
				"countryId" => "+91",
				"sellerType" => $role,
				"academicQualificationId" => $qualification,
				"activeSince" => $active_since,
				"status" => $status,
				"companyId" => $brokerId,
				"checkAddress" => "on",
				"updatedBy" => $_SESSION ['adminId'],
				"user" => $user 
		);
		$companyUserPostApi = $host . "data/v1/entity/company/company-users";
		
		$response = curl_request ( json_encode ( $post ), 'POST', $companyUserPostApi );
		if ($response ['statusCode'] == "2XX") {
			echo "1";
		} else {
			echo "3";
		}
	}
}
function createUserInProptiger() {
	$query = "SELECT USER_ID FROM proptiger.FORUM_USER WHERE EMAIL='{$email}' and STATUS='1'";
	$res = mysql_query ( $query );
	$data = mysql_fetch_assoc ( $res );
	if (! $data ['USER_ID'] > 0) {
		$pass = randomPassword ();
		
		$contactNumbers = array ();
		$contact = array (
				"contactNumber" => $phone 
		);
		array_push ( $contactNumbers, $contact );
		
		$post = array (
				"fullName" => $name,
				"email" => $email,
				"contactNumbers" => $contactNumbers,
				"password" => $pass,
				"confirmPassword" => $pass,
				"countryId" => "+91" 
		);
		
		$url = USER_API_URL;
		$response = curl_request ( json_encode ( $post ), 'POST', $url );
		if ($response ['statusCode'] == "2XX") {
			$user_id = $response ['id'];
			$to = 'mohit.dargan@proptiger.com';
			$subject = "New Broker User Account created!";
			$email_message = "Hi,<br/><br/> New account has been created at Proptiger.com.<br/>
              User = " . $email . "<br/>" . "Password = " . $pass . "<br/><br/>Regards,<br/>Proptiger.com";
			
			$sender = "no-reply@proptiger.com";
			$cc = "karanvir.singh@proptiger.com";
			sendMailFromAmazon ( $to, $subject, $email_message, $sender, $cc, null, false );
		} 

		else
			die ( "error in user mapping : " . $response ['error'] );
	} else
		$user_id = $data ['USER_ID'];
}
?>