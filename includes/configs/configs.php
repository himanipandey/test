<?php
ob_start();
session_start();


/*****************config for project tower facing***************/
$arr_proj_facing	=	array("EAST","WEST","SOUTH","NORTH","NORTH EAST","NORTH WEST","SOUTH NORTH","WEST NORTH");
$smarty->assign("arr_proj_facing", $arr_proj_facing);

/*****************end config for project tower facing***************/
	/***************Array for banner location*****************/
	$BANNER_LOCATION_ARRAY	=	array(0=>"Left_Panel",1=>"Box_Panel",2=>"Header",3=>"Proj_Left_Panel",4=>"Homepage_Banner",5=>"Homepage_Flash",6=>"Homepage_Box",7=>"Footer_Panel");
	$smarty->assign("BANNER_LOCATION_ARRAY", $BANNER_LOCATION_ARRAY);

	/*************No of days for order date selection******************/
	$BackDays	=	'1000';//No of back days

/****************************** Table Information: *****************/

//code for define host name

if($_SERVER['SERVER_NAME']=='cms.proptiger.com' )
{
	$dirName = '';
	define("SERVER_IMAGE_PATH",$_SERVER['DOCUMENT_ROOT'].$dirName);
}
else
{
	$dirName = '';
	define("SERVER_IMAGE_PATH",$_SERVER['DOCUMENT_ROOT'].$dirName);
}

//end here
//include_once($_SERVER['DOCUMENT_ROOT'].$dirName.'/admin_cms/includes/function_access.php');

/*Added by siddharth for Forum module on 12th Jan 2012*/
/*Forum Paths in Project Detail page*/
if($_SERVER['SERVER_NAME']=='cms.proptiger.com' )
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
$smarty->assign("RESI_PROJECT", "resi_project");
$smarty->assign("UPDATION_CYCLE", "updation_cycle");

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


/*Added by sudhanshu for Crawler  module on 12th Mar 2012*/
/*Crawler Paths in Project Detail page*/
if($_SERVER['SERVER_NAME']=='cms.proptiger.com' )
{
	define('OFFLINE_PROJECT_INTERNET_PATH',"http://".$_SERVER['SERVER_NAME']."/");
	define('OFFLINE_PROJECT_INTERNET_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME']."/images/");
	define('OFFLINE_PROJECT_IMAGE_SAVE_PATH', $_SERVER['DOCUMENT_ROOT']."/images/");
	//define('OFFLINE_PROJECT_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME']."/admin_cms/offlineproject/");
}
else
{
	define('OFFLINE_PROJECT_INTERNET_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/");
	define('OFFLINE_PROJECT_INTERNET_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/images/");
	define('OFFLINE_PROJECT_IMAGE_SAVE_PATH', $_SERVER['DOCUMENT_ROOT'].$dirName."/images/");
	//define('OFFLINE_PROJECT_IMAGE_PATH',"http://".$_SERVER['SERVER_NAME'].$dirName."/admin_cms/offlineproject/");
}

define('OFFLINE_PROJECT_SERVER_PATH','');
define('OFFLINE_PROJECT_BASE_PATH','');
define('OFFLINE_PROJECT','offlineproject/');
define('OFFLINE_PROJECT_IMAGE_PATH','images/');
define('OFFLINE_PROJECT_POPUP_IMAGE_PATH','images/');
define('OFFLINE_PROJECT_TEMPLATE_PATH','smarty/templates/admin/offlineproject/');
define('OFFLINE_PROJECT_FRONT_TEMPLATE_PATH','smarty/templates/offlineproject/');
define('PROJECT_ADD_TEMPLATE_PATH','smarty/templates/');

define('OFFLINE_PROJECT_TABLE_PREFIX','OFFLINE_PROJECT_');

$smarty->assign("OFFLINE_PROJECT_INTERNET_PATH", OFFLINE_PROJECT_INTERNET_PATH);
$smarty->assign("OFFLINE_PROJECT_INTERNET_IMAGE_PATH", OFFLINE_PROJECT_INTERNET_IMAGE_PATH);
$smarty->assign("OFFLINE_PROJECT_IMAGE_SAVE_PATH", OFFLINE_PROJECT_IMAGE_SAVE_PATH);
$smarty->assign("OFFLINE_PROJECT_IMAGE_PATH", OFFLINE_PROJECT_IMAGE_PATH);

$smarty->assign("OFFLINE_PROJECT_SERVER_PATH", OFFLINE_PROJECT_SERVER_PATH);
$smarty->assign("OFFLINE_PROJECT_BASE_PATH", OFFLINE_PROJECT_BASE_PATH);

$smarty->assign("OFFLINE_PROJECT_POPUP_IMAGE_PATH", OFFLINE_PROJECT_POPUP_IMAGE_PATH);
//$smarty->assign("OFFLINE_PROJECT_IMAGE_PATH", OFFLINE_PROJECT_IMAGE_PATH);

$smarty->assign("OFFLINE_PROJECT_TEMPLATE_PATH", OFFLINE_PROJECT_TEMPLATE_PATH);
$smarty->assign("OFFLINE_PROJECT_FRONT_TEMPLATE_PATH", OFFLINE_PROJECT_FRONT_TEMPLATE_PATH);

$smarty->assign("OFFLINE_PROJECT_TABLE_PREFIX", OFFLINE_PROJECT_TABLE_PREFIX);
$smarty->assign("OFFLINE_PROJECT", OFFLINE_PROJECT);
$smarty->assign("PROJECT_ADD_TEMPLATE_PATH", PROJECT_ADD_TEMPLATE_PATH);
/*crawler paths ends*/
/*ends*/



DEFINE("ADMIN","proptiger_admin");
DEFINE("RESI_PROJECT","resi_project");
DEFINE("UPDATION_CYCLE","updation_cycle");
DEFINE("RESI_BUILDER","resi_builder");
DEFINE("CITY","city");
DEFINE("SUBURB","suburb");
DEFINE("RESI_PROJECT_TYPE","resi_project_type");
DEFINE("BANK_LIST","bank_list");
DEFINE("AUDIT","audit");
DEFINE("RESI_PROJECT_OPTIONS","resi_project_options");

DEFINE("AMENITIES_MASTER","amenities_master");
DEFINE("PROJECT_OPTIONS","resi_project_options");
DEFINE("ROOM_CATEGORY","room_category");




DEFINE("PROJECT_PLAN_IMAGES","project_plan_images");
DEFINE("RESI_PROJECT_AMENITIES","resi_project_amenities");
DEFINE("RESI_FLOOR_PLANS","resi_floor_plans");

DEFINE("RESI_PROJECT_TOWER_DETAILS","resi_project_tower_details");
DEFINE("RESI_PROJECT_PHASE","resi_project_phase");
DEFINE("RESI_PROJECT_PHASE_QUANTITY","resi_phase_quantity");
DEFINE("RESI_PROJ_SUPPLY","resi_proj_supply");
DEFINE("RESI_PROJ_TOWER_CONSTRUCTION_STATUS","resi_proj_tower_construction_status");
DEFINE("RESI_PROJ_SPECIFICATION","resi_proj_specification");
DEFINE("RESI_SOURCEOFINFORMATION","resi_sourceofinformation");
DEFINE("RESI_PROJECT_OPTIONS_ARC","resi_project_options_arc");
DEFINE("RESI_PROJ_EXPECTED_COMPLETION","resi_proj_expected_completion");
DEFINE("LOCALITY","locality");
DEFINE("BUILDER_CONTACT_INFO","builder_contact_info");
DEFINE("RESI_PROJECT_OTHER_PRICING","resi_project_other_pricing");
DEFINE("CALLDETAILS","CallDetails");
DEFINE("CALLPROJECT","CallProject");


/**************This always put at the end of define tables*******************/

if(basename($_SERVER["PHP_SELF"])!='home.php'){
	include("left.php");
}
/********************please dont define table ansme after this block*********/

$ARRTRACKPAGES = array( 'CITY',
						'PROJECTDETAIL',
						'HOME',
						'HOMELOAN',
						'GOOGLE',
						'GOOGLE 1',
						'GOOGLE 2',
						'GOOGLE 3',
						'GOOGLE 4',
						'GOOGLE 5',
						'GOOGLE 6',
						'GOOGLE 7',
						'BUILDER',
						'CONTACT US',
						'ABOUT US',
						'CAREER',
						'SITEMAP',
						'USER AGREEMENT',
						'PRIVACY POLICY',
						'BUILDER GALLERY',
						'FAQS',
						'HOMELOAN',
						'EMI',
						'DOCUMENTS',
						'LOAN AMORTIZATION',
						'VAASTU',
						'NRI',
						'LOAN ELIGBILITY',
						'MAP',
						'OUR SERVICES',
						'BUILDER PARTNERTS',
						'PROPTIGER MEDIA',
						'MANAGEMENT TEAM',
						'DISCLAIMER'
					);

$ARRFORMNAME = array(	"CONTACT US",
						"RIGHT ENQ",
						"FLOOR PLAN",
						"SITE VISIT",
						"NOT INTERESTED",
						"NO PROJ",
						"LEFT ENQ",
						"BOTTOM ENQ",
						"PRICE REQ",
						"PROJ ENQ",
						"GET UPDATES",
						"TOP PICKS",
						"MAP DETAIL PAGE",
						"MAP PAGE",
						"HOMELOAN REQUEST"
					);




/**************Array for multiple user login************/

$ArrModuleOld	=	array(
					'0'=>array(		// Superadmin - Array
								"project_mng"=>"project_type,project_img,project_plans,project_floor,display_order,project_coordinate,featured_project",
								"project_info"=>"pricing,payment_sch,commission,invoice",
								"order_mng"=>"new_order,pend_ord,editappord,ordmngt,clntmngt,pend_ord_inv,pend_coll,ord_doc_ver,canc_ord,app_ord_dnld,fetchord",
								"testimonial"=>"testimonial",
								"city"=>"city",
								"leads"=>"leads,duplicateleads,autoasign",
								"banner"=>"banner",
								"quick"=>"add_quick_city",
								"builder_mng"=>"builder_mng",
								"usermng"=>"usermng",
								"invoice"=>"invoice",
								"configure_admin"=>"configure_admin",
								"collections"=>"collections",
								"mis"=>"miscm,misom,closing_mon,mis_st,mis_pt,mis_lt",
								"leadreports"=>"leadreports",
								"incentive"=>"incentive,target",
								"meta"=>"meta",
								"usermng"=>"user_mng,admin_info",
								"career"=>"jobs",
								"spac_act"=>"spac_act",
								"cashback"=>"cashback",
								),
					'1'=>array(		// Business Head - Array
								"project_mng"=>"project_type,project_img,project_plans,project_floor,display_order,project_coordinate,featured_project",
								"project_info"=>"pricing,payment_sch,commission,invoice",
								"order_mng"=>"new_order,pend_ord,editappord,ordmngt,clntmngt,pend_ord_inv,pend_coll,ord_doc_ver,canc_ord,app_ord_dnld,fetchord",
								"testimonial"=>"testimonial",
								"city"=>"city",
								"leads"=>"leads,duplicateleads,autoasign",
								"banner"=>"banner",
								"quick"=>"add_quick_city",
								"builder_mng"=>"builder_mng",
								"usermng"=>"user_mng",
								"invoice"=>"invoice",
								"collections"=>"collections",
								"mis"=>"miscm,misom,closing_mon,mis_st,mis_pt,mis_lt",
								"leadreports"=>"leadreports",
								"incentive"=>"incentive,target",
								"meta"=>"meta",
								"career"=>"jobs",
								"cashback"=>"cashback",
								),
					'2'=>array(		//Sales Head - Sales Array
								"leadreports"=>"leadreports",
								"order_mng"=>"pend_ord,canc_ord",
								"leads"=>"leads,duplicateleads,autoasign",
								),
					'3'=>array(		// Sr. Manager - Sales Array
								"leadreports"=>"leadreports",
								"leads"=>"leads,duplicateleads,autoasign",

								),
					'4'=>array(		// Manager - Sales Array
								"leadreports"=>"leadreports",
								"leads"=>"leads,duplicateleads,autoasign",
								),
					'5'=>array(		// Sr. Executive - Sales Array
								"leadreports"=>"leadreports"
								),
					'7'=>array(		// Quality Analyst Array
								"leadreports"=>"leadreports",
							   ),
					'11'=>array(	// Finance Manager Array
								"project_info"=>"pricing,payment_sch,commission,invoice",
								"order_mng"=>"new_order,pend_ord,editappord,ordmngt,clntmngt,pend_ord_inv,pend_coll,ord_doc_ver,canc_ord",
								"invoice"=>"invoice",
								"mis"=>"miscm,misom,closing_mon",
								"usermng"=>"admin_info",
								"cashback"=>"cashback",
								"collections"=>"collections",
								),
					'12'=>array(	//Finance Executive Array
								"project_info"=>"pricing,payment_sch,commission,invoice",
								"order_mng"=>"new_order,pend_ord,editappord,ordmngt,clntmngt,pend_ord_inv,pend_coll,ord_doc_ver,canc_ord",
								"invoice"=>"invoice",
								"mis"=>"miscm,misom,closing_mon",
								"usermng"=>"admin_info",
								"cashback"=>"cashback",
								"collections"=>"collections",
								),
					'14'=>array(	// Payroll Array
								"incentive"=>"incentive,target",
								"usermng"=>"admin_info"
							   ),

					'15'=>array(
								"career"=>"jobs"

							   ),
					'31'=>array(	// Data Entry - Operations Array
								"project_mng"=>"project_type,project_img,project_plans,project_floor,display_order,project_coordinate,featured_project",
								"testimonial"=>"testimonial",
								"city"=>"city",
								"leads"=>"leads,duplicateleads,autoasign",
								"banner"=>"banner",
								"quick"=>"add_quick_city",
								"builder_mng"=>"builder_mng",
								"leadreports"=>"leadreports",
								"meta"=>"meta",
								),
					'41'=>array(	// Operation Manager Array
								"project_info"=>"pricing,payment_sch,commission,invoice",
								"order_mng"=>"new_order,pend_ord,editappord,ordmngt,clntmngt,pend_ord_inv,pend_coll,ord_doc_ver,canc_ord,app_ord_dnld",
								"mis"=>"miscm,misom,closing_mon",
								"usermng"=>"admin_info",

								),

					'42'=>array(	// Operation Executive Array
								"project_info"=>"pricing,payment_sch,commission,invoice",
								"order_mng"=>"new_order,pend_ord,editappord,ordmngt,clntmngt,pend_ord_inv,pend_coll,ord_doc_ver",
								"mis"=>"miscm,misom,closing_mon",
								"usermng"=>"admin_info"
								),
					'51'=>array(  //IT Networks
								"spac_act"=>"spac_act",


								),

					);

	//$ArrModule = getAccessByData($_SESSION['ACCESS_LEVEL']);
	//$smarty->assign("ArrModule", $ArrModule);
	//$smarty->assign("ACCESS_LEVEL", $_SESSION['ACCESS_LEVEL']);
/*********************************************************/

/*************Array branchs********************/
	$BranchLoc	=	array("CHN"=>"Chennai","IND"=>"Indore","AHM"=>"Ahmedabad","BNG"=>"Bangalore","PUN"=>"Pune","MUM"=>"Mumbai","DEL"=>"Delhi","GZB"=>"Ghaziabad","NOD"=>"Noida","GGN"=>"Gurgaon","KOL"=>"Kolkata");
	$smarty->assign("BranchLoc", $BranchLoc);

/***************************************/


/*************Array Regions********************/
	$RegionsOffice	=	array("E"=>"East","W"=>"West","N"=>"North","S"=>"South");
	$smarty->assign("RegionsOffice", $RegionsOffice);

/***************************************/

/*************Array Designation********************/
	$designationArray	=	array("0"=>"Superadmin","1"=>"Business Head","2"=>"Sales Head","3"=>"Sr. Manager","4"=>" Manager","5"=>"Sr. Executive","6"=>"Executive","7"=>"Quality Analyst","11"=>"Finance","12"=>"Finance Executive","13"=>"Admin", "14"=>"Payroll","15"=>"HR", "21"=>"Home Loan","22"=>"Home Loan Executive","31"=>"Data Entry","32"=>"Data Entry Executive","36"=>"Customers Support","41"=>"Operation Manager","42"=>"Opeartion Executive","51"=>"IT Network","61"=>"Product Head","62"=>"Company Secretary","63"=>"Collection","64"=>"Online Marketing","65"=>"Content Writer","66"=>"Collection Executive");
	$smarty->assign("designationArray", $designationArray);


/*************Array Department********************/

	$departmentArray	=	array("MANAGEMENT"=>"MANAGEMENT","FINANCE"=>"FINANCE","SALES"=>"SALES","HOMELOAN"=>"HOMELOAN","OPERATIONS"=>"OPERATIONS","HR"=>"HR","IT"=>"IT","DESIGN"=>"DESIGN","DATAENTRY"=>"DATAENTRY","ADMIN"=>"ADMIN","CUSTOMERSUPPORT"=>"CUSTOMERSUPPORT","COMPANYSECRETARY"=>"COMPANYSECRETARY","COLLECTION"=>"COLLECTION","ONLINEMARKETING"=>"ONLINEMARKETING","CONTENTWRITER"=>"CONTENTWRITER");
	$smarty->assign("departmentArray", $departmentArray);

	/******add service tax for invoice**********/

	//$ServiceTax	=	"10.3%";
	$ServiceTax	=	"12.36%";
	$smarty->assign("ServiceTax", $ServiceTax);

	/***************************************/


/******add tds percentage for collection**********/

$tdsPercentage	=	"10%";
$smarty->assign("tdsPercentage", $tdsPercentage);

/***************************************/

/*********************array for showing number to words**********************/


$nwords = array("Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven",
"Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen","Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
"Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty",50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty",
90 => "Ninety" );


/*************Array Area Unit********************/
$AreaUnit	=	array("sq ft");
$smarty->assign("AreaUnit", $AreaUnit);

/***************************************/
/**
 * *****************************
 * General Information
 * *****************************
 **/

define("SITE_TITLE","PropTiger.com");
define("SITE_LINK","http://PropTiger.com/");

define("MAIL_FROM",'info@PropTiger.com');
define("NAME_FROM",'Administrator');

define("CHARSET", 'ISO-8859-1');
define("SITE_URL",'http://localhost/');

define("DEF_PAGE_SIZE",10);

// TODO - Set the correct date timezone
@define("CUR_DATE_YMD",date('Y-m-d'));
@define("CUR_DATETIME",date ("Y-m-d H:m:s"));

define("SUBJECT_CREATE_ACCOUNT",'You have an account with PropTiger.com');
define("FORGOT_PASSWORD_SUBJECT_CREATE_ACCOUNT",'You account detail of PropTiger.com');


//define('SERVER_PATH','/opt/lampp/htdocs/proptiger');
define('SERVER_PATH','.');
$smarty->assign("SITETITLE", SITE_TITLE);
if(!isset($_SESSION['AdminUserName']))
	$_SESSION['AdminUserName'] = '';
if(!isset($_SESSION['adminId']))
	$_SESSION['adminId'] = '';
if(!isset($_SESSION['LAST_LOGIN_DATE']))
	$_SESSION['LAST_LOGIN_DATE'] = '';
if(!isset($_SESSION['LAST_LOGIN_IP']))
	$_SESSION['LAST_LOGIN_IP'] = '';
if(!isset($_SESSION['DEPARTMENT']))
	$_SESSION['DEPARTMENT'] = '';
$smarty->assign("AdminUserName", ucfirst($_SESSION['AdminUserName']));
$smarty->assign("adminId", $_SESSION['adminId']);
$smarty->assign("LAST_LOGIN_DATE", $_SESSION['LAST_LOGIN_DATE']);
$smarty->assign("LAST_LOGIN_IP", $_SESSION['LAST_LOGIN_IP']);
$smarty->assign("dept", $_SESSION['DEPARTMENT']);

//Banks Array

$allBankName =array("AND" =>"Andhra Bank",
"UTI" =>"AXIS Bank",
"BBK" =>"Bank of Bahrain",
"BOBCO" =>"Bank of Baroda",
"ICICI" =>"ICICI Bank",
"BOI" =>"Bank of India",
"BOM" =>"Bank of Maharashtra",
"CBIBAN" =>"Citibank Bank Account Online",
"CITIUB" =>"City Union Bank",
"DEUNB" =>"Deutsche Bank",
"FDEB" =>"Federal Bank",
"HDEB" =>"HDFC Bank",
"IIB" =>"IndusInd Bank",
"ING" =>"ING Vysya Bank",
"JKB" =>"Jammu & Kashmir Bank",
"KTKB" =>"Karnataka Bank",
"KVB" =>"Karur Vysya Bank",
"KMB" =>"Kotak Mahindra Bank",
"LVB" =>"Lakshmi Vilas Bank",
"OBPRF" =>"Oriental Bank of Commerce",
"PNBCO" =>"Punjab National Bank",
"ALLHB" =>"Allahabad Bank",
"SIB" =>"South Indian Bank",
"SCB" =>"Standard Chartered Bank",
"SBH" =>"State Bank of Hyderabad",
"SBI" =>"State Bank of India",
"SBM" =>"State Bank of Mysore",
"SBT" =>"State Bank of Travancore",
"SYNBK" =>"Syndicate Bank",
"TNMB" =>"Tamilnad Mercantile Bank",
"UNI" =>"Union Bank of India",
"UBI" =>"United Bank of India",
"VJYA" =>"Vijaya Bank",
"YES" =>"YES Bank");
$bankName = array();
ksort($allBankName);
foreach($allBankName as $k=>$v)
{
	$bankName[$k]=$v;
}
$smarty->assign('bankName',$bankName);

//define for order status

define('ORD_APPROVED','1');
define('ORD_PENDINGFORAPPROVE','6');
define('ORD_APPROVEDBYOPERATIONS','7');
define('ORD_APPROVEDBYSALES','8');
define('ORD_CANCEL','11');  //it is accually order reject and when order cancel then status is 12

//end here

// define for autoassign leads

	DEFINE('DBN','vtigercrm521');
	DEFINE('DBN_CRM','ptigercrm');
	//variable used for CRM proptiger admin table
	DEFINE('ADMIN_CRM','PROPTIGER_ADMIN');

	DEFINE('AUTOASSIGNUSER','9');
	DEFINE('DEFAULTLEADS',5);

// money difference

DEFINE('AMOUNT_DIFF','6');
$leadsStartFrm = '180000';

/******************CRM TABLE PREFIX BY SIDDHARTH**************************/
define('CRM_TABLE_PREFIX','LEAD');
define('CRM_TABLE_SEPARATOR','_');

define("USER" , "USER_LOGIN");
define("LEAD" , "LEADS");
define("L_DETAILS" , CRM_TABLE_PREFIX . CRM_TABLE_SEPARATOR. "DETAILS");
define("L_HISTORY" , CRM_TABLE_PREFIX . CRM_TABLE_SEPARATOR. "HISTORY");
define("L_ASSIGN" , CRM_TABLE_PREFIX . CRM_TABLE_SEPARATOR. "ASSIGN");
define("L_STATUS" , CRM_TABLE_PREFIX . CRM_TABLE_SEPARATOR. "STATUS");

define("TMP_UPLOAD" , CRM_TABLE_PREFIX . CRM_TABLE_SEPARATOR. "TMP_UPLOAD");

define("MASTER_STATUS" , "MASTER" .CRM_TABLE_SEPARATOR. "STATUS");
define("MASTER_SOURCE" , "MASTER" . CRM_TABLE_SEPARATOR. "SOURCE");
define("MASTER_DEAD_REASON" , "MASTER" . CRM_TABLE_SEPARATOR. "DEAD". CRM_TABLE_SEPARATOR. "REASON");
//define("LEAD_DESCRIPTION_HISTORY",LEAD_DESCRIPTION_HISTORY);

define("DUPLICATE_LEADS" , "ORIGINAL".CRM_TABLE_SEPARATOR."LEADS");
define("DUPLICATE_LEADS_NEW" , "DUPLICATE".CRM_TABLE_SEPARATOR."LEADS".CRM_TABLE_SEPARATOR."LIST");

define("LEAD_ARCHIVE","LEAD".CRM_TABLE_SEPARATOR."DESCRIPTION".CRM_TABLE_SEPARATOR."HISTORY".CRM_TABLE_SEPARATOR."ARC");

define("OWNER_ID", 442);

$MOBILE_TO_IGNORE_FOR_DUPLICATE_CHECK = array('11111','22222','33333','44444','55555','66666','77777','12345','23456','34567','45678');
$EMAIL_TO_IGNORE_FOR_DUPLICATE_CHECK  = array('wrong','abc','xyz','test','asd','zxc','dfdd','dfds','sdf','ert');

define("DEAD_LEAD_STATUS", 7);

define("IN_PROCESS_LEAD_STATUS", 3);

define('CUSTOMER_CARE_LEAD','cc');
/******************CRM TABLE PREFIX**************************/

/****************PAYMENT COLLECTION CONSTANTS BY SIDDHARTH***********************/
define('CHEQUE','0');
define('CASH','1');
define('DD','2');
/****************PAYMENT COLLECTION CONSTANTS BY SIDDHARTH***********************/

/******************** DEFAULT MANAGERS IN USER MANAGEMENT**********************/

$defaultManagersArr = array("53"=>"Prashan Agarwal","49"=>"Rohit Arora","17"=>"Vikas Wadhawan","506"=>"Ankur Dhawan");
$smarty->assign('defaultManagersArr',$defaultManagersArr);

define('COLLECTION_MGR',501);


define('APARTMENTS','1');
define('VILLA','2');
define('VILLA_APARTMENTS','3');
define('PLOTS','4');
define('PLOT_VILLAS','5');
define('PLOT_APARTMENTS','6');


$ARR_PROJ_EDIT_PERMISSION = array(
	"ADMINISTRATOR"=>array('dataCollection','newProject','audit1','audit2','complete','bulkupdate','dailymis','dcCallCenter','noPhase','noStage'),
	"DATAENTRY"=>array('dataCollection'),
	"CALLCENTER"=>array('dataCollection','dailymis','dcCallCenter'),
        "RESALE-CALLCENTER"=>array('dataCollection','dailymis'),
	"AUDIT-1"=>array('audit1','dailymis'),
	"NEWPROJECTAUDIT"=>array('newProject'),
	"AUDIT-2"=>array('audit2'),
	"SURVEY"=>array('dataCollection','newProject')
);
$dept = $_SESSION['DEPARTMENT'];
if(!isset($ARR_PROJ_EDIT_PERMISSION[$dept]))
	$ARR_PROJ_EDIT_PERMISSION[$dept] = '';
$smarty->assign("arrProjEditPermission", $ARR_PROJ_EDIT_PERMISSION[$dept]);
/*********array for images type can upload for builder project images and floor plan images*******/

$arrImg = array("image/gif","image/png","image/jpg","image/jpeg");

$arrType = array("Location Plan"=>"loc-plan","Layout Plan"=>"layout-plan","Site Plan"=>"site-plan","Master Plan"=>"master-plan","Project Image"=>"large","Cluster Plan"=>"cluster-plan","Construction Status"=>"const-status","Payment Plan"=>"payment-plan","Specification"=>"specification","Price List"=>"price-list","Application Form"=>"app-form");

$arrCampaign = 
  array(
	"Select Campaign", 
	"Delhi1", 
	"Delhi2",
	"Delhi3", 
	"Pune1", 
	"Pune_2", 
	"Mumbai_1", 
  	"Mumbai_2",
	"Mumbai_3",
	"Kolkata",
	"Chennai",
	"Bangalore_1",
	"Bangalore_2",
	"Bangalore_3",
  	"Pune_New"
	);
/**************Authentication*************/
$accessModule = array();
$arrUser      = array(547,525,588,506,53,558,582);
if(in_array($_SESSION['adminId'],$arrUser))
{
	$accessModule['urlEdit'] = $_SESSION['adminId'];
}
$smarty->assign("accessModule", $accessModule);

/******array for force migrate access*****/
$forceMigrateModule = array();
$arrForce      = array(53,506);
if(in_array($_SESSION['adminId'],$arrForce))
{
	$forceMigrateModule['urlEdit'] = $_SESSION['adminId'];
}
$smarty->assign("forceMigrateModule", $forceMigrateModule);

//$newImagePath = "/home/sysadmin/public_html/images_new/";
$newImagePath = "/home/vimlesh/public_html/images_new/";
$imgDisplayPath = "images_new/";
$smarty->assign("imgDisplayPath", $imgDisplayPath);

$analytics_credential=array("username"=>"cms","password"=>"Cms123!");
?>
