<?php

/**
 * @author AKhan
 * @copyright 2013
 */

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("modelsConfig.php");
include("s3upload/s3_config.php");
include("SimpleImage.php");
	
AdminAuthentication();
$cityArr = City::CityArr();
$cityLocArr = CityLocationRel::CityLocArr(); 
$smarty->assign("cityArr", $cityArr);
$smarty->assign("cityLocArr", $cityLocArr);
$smarty->assign("sort", !empty($_GET['sort'])?$_GET['sort']:'');
$smarty->assign("page", !empty($_GET['page'])?$_GET['page']:'');

if(!empty($_GET['brokerCompanyId']))
{
    $brkrDet = BrokerCompany::getById($_GET['brokerCompanyId']);
    //echo BrokerCompany::connection()->last_query."<br>";
    $cityLocIDArr = BrokerCompanyLocation::CityLocIDArr($_GET['brokerCompanyId']);
    
    $citypkidArr = array();
    if(!empty($cityLocIDArr))
    foreach($cityLocIDArr as $key => $val)
    {
        $citypkidArr[] = $val->pkid;
    }
    $smarty->assign("citypkidArr", !empty($citypkidArr)?base64_encode(json_encode($citypkidArr)):'');
    $smarty->assign("cityLocIDArr", $cityLocIDArr);
    
    $contactIDArr = BrokerCompanyContact::ContactArr($_GET['brokerCompanyId']);
    //print('<pre>');
//    print_R($contactIDArr);
//    
//    die;
    $contactIDArr = array_merge($contactIDArr , !empty($brkrDet)?$brkrDet:array());
    
    $smarty->assign("name", !empty($contactIDArr['name'])?$contactIDArr['name']:'');
    $smarty->assign("status", !empty($contactIDArr['status'])?$contactIDArr['status']:'');
    $smarty->assign("description", !empty($contactIDArr['description'])?$contactIDArr['description']:'');
    $smarty->assign("pan", !empty($contactIDArr['pan'])?$contactIDArr['pan']:'');
    $smarty->assign("active_since", !empty($contactIDArr['active_since'])?$contactIDArr['active_since']:'');
    
    $smarty->assign("addressline1", !empty($contactIDArr['addressline1'])?$contactIDArr['addressline1']:'');
    $smarty->assign("addressline2", !empty($contactIDArr['addressline2'])?$contactIDArr['addressline2']:'');
    $smarty->assign("city_id", !empty($contactIDArr['city_id'])?$contactIDArr['city_id']:'');
    $smarty->assign("pincode", !empty($contactIDArr['pincode'])?$contactIDArr['pincode']:'');        
    $smarty->assign("phone1", !empty($contactIDArr['phone1'])?$contactIDArr['phone1']:'');
    $smarty->assign("phone2", !empty($contactIDArr['phone2'])?$contactIDArr['phone2']:'');
    $smarty->assign("mobile", !empty($contactIDArr['mobile'])?$contactIDArr['mobile']:'');
    $smarty->assign("fax", !empty($contactIDArr['fax'])?$contactIDArr['fax']:'');
    $smarty->assign("email", !empty($contactIDArr['primary_email'])?$contactIDArr['primary_email']:'');
    $smarty->assign("cc_phone", !empty($contactIDArr['cc_phone'])?$contactIDArr['cc_phone']:'');
    $smarty->assign("cc_mobile", !empty($contactIDArr['cc_mobile'])?$contactIDArr['cc_mobile']:'');
    $smarty->assign("cc_fax", !empty($contactIDArr['cc_fax'])?$contactIDArr['cc_fax']:'');
    $smarty->assign("cc_email", !empty($contactIDArr['cc_email'])?$contactIDArr['cc_email']:'');
    
    $smarty->assign("primary_address_id", !empty($contactIDArr['primary_address_id'])?$contactIDArr['primary_address_id']:'');
    $smarty->assign("fax_number_id", !empty($contactIDArr['fax_number_id'])?$contactIDArr['fax_number_id']:'');
    $smarty->assign("primary_email", !empty($contactIDArr['primary_email'])?$contactIDArr['primary_email']:'');
    $smarty->assign("primary_broker_contact_id", !empty($contactIDArr['primary_broker_contact_id'])?$contactIDArr['primary_broker_contact_id']:'');
    $smarty->assign("primary_contact_number_id", !empty($contactIDArr['primary_contact_number_id'])?$contactIDArr['primary_contact_number_id']:'');
    
    $smarty->assign("contactids", $contactIDArr);    
    $smarty->assign("contacts", !empty($contactIDArr['contacts'])?$contactIDArr['contacts']:'');
}
include('brokercompanyaddProcess.php');



$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."brokercompanyadd.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	




?>