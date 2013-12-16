<?php

/**
 * @author AKhan
 * @copyright 2013
 */

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php"); 
include("modelsConfig.php");
include("includes/configs/configs.php");
include("s3upload/s3_config.php");
include("SimpleImage.php");
AdminAuthentication();
$cityArr = City::CityArr();
$brokerArr = BrokerCompany::find('all' , array('select' => 'brokers.id,brokers.broker_name'));
$qualification = SellerCompany::getQualification();

$smarty->assign("cityArr", $cityArr);
$smarty->assign("brokerArr", $brokerArr);
$smarty->assign("qualification", $qualification);
$smarty->assign("sort", !empty($_GET['sort'])?$_GET['sort']:'all');
$smarty->assign("page", !empty($_GET['page'])?$_GET['page']:'1');
include('sellercompanyaddProcess.php');
if(!empty($_GET['sellerCompanyId'])) 
{
    $sellerDet = SellerCompany::getByid($_GET['sellerCompanyId']);
    
    print'<pre>';
    print_r($sellerDet);
    print'</pre>';
    
    if(!empty($sellerDet))
    {
        
        $img = json_decode(file_get_contents('http://nightly.proptiger-ws.com:8080/data/v1/entity/image?objectType=brokerCompany&objectId='.$sellerDet));
        $imgurl = '';
        $imgid = '';
        if(!empty($img))
        {
            foreach($img as $k1 => $v1)
            {
                if($k1 == "data" && !empty($v1))
                {
                    $imgurl = $v1[0]->absolutePath;
                    $imgid = $v1[0]->id;
                    break;
                }
            }
        }
        $smarty->assign("imgid", !empty($imgid)?$imgid:'');
        $smarty->assign("sellerCompanyId", !empty($sellerDet['id'])?$sellerDet['id']:'');
        $smarty->assign("status", !empty($sellerDet['status'])?$sellerDet['status']:'');
        $smarty->assign("broker_id", !empty($sellerDet['broker_id'])?$sellerDet['broker_id']:'');
        $smarty->assign("brkr_cntct_id", !empty($sellerDet['brkr_cntct_id'])?$sellerDet['brkr_cntct_id']:'');
        $smarty->assign("qualification_id", !empty($sellerDet['academic_qualification_id'])?$sellerDet['academic_qualification_id']:'');
        $smarty->assign("rating", !empty($sellerDet['rating'])?$sellerDet['rating']:'');
        $smarty->assign("rateoption", !empty($sellerDet['rate_option'])?$sellerDet['rate_option']:'');
        $smarty->assign("seller_type", !empty($sellerDet['seller_type'])?$sellerDet['seller_type']:'');
        $smarty->assign("active_since", (!empty($sellerDet['active_since']) && $sellerDet['active_since'] !== '0000-00-00')?date('d/m/Y' , strtotime($sellerDet['active_since'])):'');
        
        $smarty->assign("seller_name", !empty($sellerDet['name'])?$sellerDet['name']:'');
        $smarty->assign("addressid", !empty($sellerDet['addressid'])?$sellerDet['addressid']:'');
        $smarty->assign("addressline1", !empty($sellerDet['address_line_1'])?$sellerDet['address_line_1']:'');
        $smarty->assign("addressline2", !empty($sellerDet['address_line_2'])?$sellerDet['address_line_2']:'');        
        $smarty->assign("cityhiddenArr", !empty($sellerDet['city_id'])?$sellerDet['city_id']:'');
        $smarty->assign("pincode", !empty($sellerDet['pincode'])?$sellerDet['pincode']:'');
        $smarty->assign("mobile", !empty($sellerDet['mobile'])?$sellerDet['mobile']:'');
        $smarty->assign("phone1", !empty($sellerDet['phone1'])?$sellerDet['phone1']:'');
        $smarty->assign("phone2", !empty($sellerDet['phone1'])?$sellerDet['phone2']:'');
        $smarty->assign("email", !empty($sellerDet['contact_email'])?$sellerDet['contact_email']:'');
    }
    //print'<pre>';
//    print_r($sellerDet);
//    die;
}






$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."sellercompanyadd.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	




?>