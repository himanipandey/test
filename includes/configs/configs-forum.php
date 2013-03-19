<?php
if($_SERVER['SERVER_NAME']=='proptiger.com' || $_SERVER['SERVER_NAME']=='www.proptiger.com' || $_SERVER['SERVER_NAME']=='staging.proptiger.com' )
{
	$dirName = '';
	define("SERVER_IMAGE_PATH",$_SERVER['DOCUMENT_ROOT'].$dirName);
}
else
{
	$dirName = '/proptiger';
	define("SERVER_IMAGE_PATH",$_SERVER['DOCUMENT_ROOT'].$dirName);
}
//end here

/*Added by siddharth for Forum module on 12th Jan 2012*/
/*Forum Paths in Project Detail page*/
if($_SERVER['SERVER_NAME']=='proptiger.com' || $_SERVER['SERVER_NAME']=='www.proptiger.com' || $_SERVER['SERVER_NAME']=='staging.proptiger.com' )
{
	define('FORUM_INTERNET_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/");
	define('FORUM_INTERNET_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/images/");
	define('FORUM_FB_USER_IMAGE_SAVE_PATH', $_SERVER['DOCUMENT_ROOT'].$dirName."/administrator/tmp/user/");
	define('FORUM_FB_USER_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/administrator/tmp/user/");
}
else
{
	define('FORUM_INTERNET_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/");
	define('FORUM_INTERNET_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/images/");
	define('FORUM_FB_USER_IMAGE_SAVE_PATH', $_SERVER['DOCUMENT_ROOT'].$dirName."/administrator/tmp/user/");
	define('FORUM_FB_USER_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/administrator/tmp/user/");
}

define('FORUM_SERVER_PATH','');
define('FORUM_BASE_PATH','');
define('FORUM_PATH','forum/');
define('FORUM_IMAGE_PATH','images/');
define('FORUM_POPUP_IMAGE_PATH','images/');
define('FORUM_TEMPLATE_PATH','smarty/templates/admin/forum/');
define('FORUM_FRONT_TEMPLATE_PATH','smarty/templates/forum/');

define('FORUM_TABLE_PREFIX','FORUM_');

$smarty->assign("FORUM_INTERNET_PATH", FORUM_INTERNET_PATH);
$smarty->assign("FORUM_INTERNET_IMAGE_PATH", FORUM_INTERNET_IMAGE_PATH);
$smarty->assign("FORUM_FB_USER_IMAGE_SAVE_PATH", FORUM_FB_USER_IMAGE_SAVE_PATH);
$smarty->assign("FORUM_FB_USER_IMAGE_PATH", FORUM_FB_USER_IMAGE_PATH);

$smarty->assign("FORUM_SERVER_PATH", FORUM_SERVER_PATH);
$smarty->assign("FORUM_BASE_PATH", FORUM_BASE_PATH);

$smarty->assign("FORUM_POPUP_IMAGE_PATH", FORUM_POPUP_IMAGE_PATH);
$smarty->assign("FORUM_IMAGE_PATH", FORUM_IMAGE_PATH);

$smarty->assign("FORUM_TEMPLATE_PATH", FORUM_TEMPLATE_PATH);
$smarty->assign("FORUM_FRONT_TEMPLATE_PATH", FORUM_FRONT_TEMPLATE_PATH);

$smarty->assign("FORUM_TABLE_PREFIX", FORUM_TABLE_PREFIX);
$smarty->assign("FORUM_PATH", FORUM_PATH);
/*Forum paths ends*/
/*ends*/


/*Added by siddharth for Forum Module Tables on 12th Jan 2012*/
DEFINE("FUSER",FORUM_TABLE_PREFIX."USER");

DEFINE("FU_LIKES",FORUM_TABLE_PREFIX."USER_LIKES");

DEFINE("FU_COMMENTS",FORUM_TABLE_PREFIX."USER_COMMENTS");
DEFINE("F_B_USER",FORUM_TABLE_PREFIX."BLOCKED_USER");

DEFINE("FTAG",FORUM_TABLE_PREFIX."TAGS");
DEFINE("FCAT",FORUM_TABLE_PREFIX."CATEGORY");

DEFINE("FCOM_TAG",FORUM_TABLE_PREFIX."COMMENT_TAG");
DEFINE("FCOM_CAT",FORUM_TABLE_PREFIX."COMMENT_CATEGORY");
/*ends*/

DEFINE("PROJECT","RESI_PROJECT");

if($_SERVER['SERVER_NAME']=='proptiger.com' || $_SERVER['SERVER_NAME']=='www.proptiger.com')
    DEFINE("CONTENT_ADMIN_USERID",'1810');
else
    DEFINE("CONTENT_ADMIN_USERID",'1426');
?>