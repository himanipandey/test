
<?php
require_once "$_SERVER[DOCUMENT_ROOT]/includes/session.php";

$gblData = array();
if(!isset($noObStart))
	ob_start();
$gblData['noAPC'] = false;
$gblData['prodServer'] = true;
$gblData['beyondServerDocRoot'] = '/';

define("FULL_IMG_VER", "?v=2");
define("THUMB_IMG_VER", "?v=9");
define("LIST_IMG_VER", "?v=1");
define("SMALL_IMG_VER", "?v=10");
define("ICON_IMG_VER","?v=5");
define("ADS_IMG_VER", "?v=1");

if($_SERVER['SERVER_NAME']=='cms.proptiger.com')
{
	$myDocRoot = dirname(__FILE__);
	define("IMG_SERVER",'http://dyjz00pttbyut.cloudfront.net/');
	define("JS_SERVER",'http://dyjz00pttbyut.cloudfront.net/');
	define("CSS_SERVER",'http://dyjz00pttbyut.cloudfront.net/');
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors','0');
	ini_set('log_errors','0');
	define("BEANSTALK_SERVER", '208.109.190.204');
	define("BEANSTALK_PORT", '11300');

	//  Curl call URL
	define("SERVER_URL", "http://www.proptiger.com");
	define("SEND_EMAIL", TRUE);
	define("RESALE_EMAIL", "ankur.dhawan@proptiger.com");
	define("RESALE_GROUP_EMAIL", "projects@proptiger.com");

}
else{
	$myDocRoot = dirname(__FILE__);
	$myDocRoot = str_replace('\\', '/', $myDocRoot);
	$gblData['beyondServerDocRoot'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $myDocRoot);
	if(empty($gblData['beyondServerDocRoot'])){
		$gblData['beyondServerDocRoot'] = '/';
	}else if($gblData['beyondServerDocRoot'] == '/'){
		// do nothing
	}else{
		if(!strstr($gblData['beyondServerDocRoot'],"/"))
			$gblData['beyondServerDocRoot'] = "/".$gblData['beyondServerDocRoot'];
		$gblData['beyondServerDocRoot'] .= '/';
	}
	// e.g. $gblData['beyondServerDocRoot'] = /proptiger/
	// notice that is prefixed, as well as suffixed with forward slash.

	$gblData['prodServer'] = false;
	$t = $_SERVER['SERVER_NAME'];
	define("IMG_SERVER",'http://www.proptiger.com/');
	define("JS_SERVER","http://$t" . $gblData['beyondServerDocRoot']);
	define("CSS_SERVER","http://$t" . $gblData['beyondServerDocRoot']);
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	ini_set('log_errors','1');

	define("BEANSTALK_SERVER", 'localhost');
	define("BEANSTALK_PORT", '11300');
	
	
	//  CRM Database
	
	define("DB_CRM_HOST", "208.109.190.204");
	define("DB_CRM_USER", "root");
	define("DB_CRM_PASS", "PropTiger1");
	define("DB_CRM_NAME", "ptigercrm");

	//  Curl call URL
	define("SERVER_URL", "http://www.proptiger.com");
	define("SEND_EMAIL", TRUE);
	define("RESALE_EMAIL", "ankur.dhawan@proptiger.com");
	define("RESALE_GROUP_EMAIL", "projects@proptiger.com");
}
?>
