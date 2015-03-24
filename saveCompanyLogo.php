<?php

error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();



if ($_REQUEST['submit'] == "Upload") // add
{

	
	$logo_name = $_FILES['companyImg']['name'];


//$id = $_POST['imgid'];

//echo "id:".$id.$img['tmp_name'];
//print_r($_FILES);


	$folder	=	"company";
			$newdirlow	=	$newImagePath.$folder;
			if((!is_dir($newdirlow)))
			{
				//$lowerdir	=	strtolower($BuilderName);
				//$newdir		=	$newImagePath."".$lowerdir;
				
				 mkdir($newdirlow, 0777);
				//$flag=1;
			}

	$dest		=	$newImagePath."company/".$logo_name;

	$response = array(
	'status'=>0,
	'message'=>'',
	'image'=>''
	);
	if(isset($_FILES['companyImg']))
	{
		$move		=	move_uploaded_file($_FILES['companyImg']['tmp_name'],$dest);
		if($move){
			$response['status'] = 1;
			$response['message'] = 'Success!';
			$response['image'] = $logo_name;
		}
		else{
			$response['status'] = 0;
			$response['message'] = 'Failure!';
		}
		
	}
	elseif(@$_FILES["companyImg"]["error"])
	{
	    $response['message'] = 'Error code: '.$_FILES["companyImg"]["error"].'.';
	}
	elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0)
	{
	    $response['message'] = sprintf('The server was unable to handle that much POST data (%s bytes) due to its current configuration', $_SERVER['CONTENT_LENGTH']).'.';
	}
	else
	{
	    $response['message'] = 'Unknown error.';
	}
	echo json_encode($response);
}

//upload signup form

if ($_REQUEST['submit'] == "uploadSignUp") // add
{

	
	$logo_name = $_FILES['signUpForm']['name'];


//$id = $_POST['imgid'];

//echo "id:".$id.$img['tmp_name'];
//print_r($_FILES);


	$folder	=	"company";
			$newdirlow	=	$newImagePath.$folder;
			if((!is_dir($newdirlow)))
			{
				//$lowerdir	=	strtolower($BuilderName);
				//$newdir		=	$newImagePath."".$lowerdir;
				
				 mkdir($newdirlow, 0777);
				//$flag=1;
			}

	$dest		=	$newImagePath."company/".$logo_name;

	$response = array(
	'status'=>0,
	'message'=>'',
	'image'=>''
	);
	if(isset($_FILES['signUpForm']))
	{
		$move		=	move_uploaded_file($_FILES['signUpForm']['tmp_name'],$dest);
		if($move){
			$response['status'] = 1;
			$response['message'] = 'Success!';
			$response['image'] = $logo_name;
		}
		else{
			$response['status'] = 0;
			$response['message'] = 'Failure!';
		}
		
	}
	elseif(@$_FILES["signUpForm"]["error"])
	{
	    $response['message'] = 'Error code: '.$_FILES["signUpForm"]["error"].'.';
	}
	elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0)
	{
	    $response['message'] = sprintf('The server was unable to handle that much POST data (%s bytes) due to its current configuration', $_SERVER['CONTENT_LENGTH']).'.';
	}
	else
	{
	    $response['message'] = 'Unknown error.';
	}
	echo json_encode($response);
}


?>