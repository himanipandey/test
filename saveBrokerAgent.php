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
					"cityId" => $city,
					"pinCode" => $pin,
					"countryId" => "+91",
					"sellerType" => $role,
					"academicQualificationId" => $qualification,
					"activeSince" => $active_since,
					"checkAddress" => "on",
					"parentId" => 0,	
					"status" => $status,
					"companyId" => $brokerId,
					"updatedBy" => $_SESSION ['adminId'],
					"user" => $user 
			);
			$companyUserPutApi = COMPANY_USER_POST_API_URL . "/" . $agentId;
			$postJson = json_encode ( $post );
			$cookie = getJsessionId ();
			$opts = array (
					'http' => array (
							'method' => 'PUT',
							'header' => "Content-type: application/json\r\n" . "Cookie: $cookie",
							'content' => $postJson 
					) 
			);
			$tokenResponse = file_get_contents ( $companyUserPutApi, false, stream_context_create ( $opts ) );
			$reponseData = json_decode ( $tokenResponse, true );
			if ($reponseData ['statusCode'] == "2XX") {
				echo "1";
			} else {
				echo "3";
			}
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
				"cityId" => $city,
				"pinCode" => $pin,
				"countryId" => "+91",
				"sellerType" => $role,
				"academicQualificationId" => $qualification,
				"activeSince" => $active_since,
				"status" => $status,
				"companyId" => $brokerId,
				"checkAddress" => "on",
				"parentId" => 0,
				"updatedBy" => $_SESSION ['adminId'],
				"user" => $user 
		);
		$companyUserPostApi = COMPANY_USER_POST_API_URL;
		$postJson = json_encode ( $post );
		$cookie = getJsessionId ();
		$opts = array (
				'http' => array (
						'method' => 'POST',
						'header' => "Content-type: application/json\r\n" . "Cookie: $cookie",
						'content' => $postJson 
				) 
		);
		$tokenResponse = file_get_contents ( $companyUserPostApi, false, stream_context_create ( $opts ) );
		$reponseData = json_decode ( $tokenResponse, true );
		if ($reponseData ['statusCode'] == "2XX") {
			echo "1";
		} else {
			echo "3";
		}
	}
}
function getJsessionId() {
	$uriLogin = ADMIN_USER_LOGIN_API_URL;
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $uriLogin );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 1 );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
			'Content-Type: application/json' 
	) );
	curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, "" );
	$response = curl_exec ( $ch );
	curl_close ( $ch );
	preg_match ( '/Set-Cookie: JSESSIONID=(.*?);/', $response, $matches );
	$cookie = "JSESSIONID=$matches[1]";
	return $cookie;
}
?>