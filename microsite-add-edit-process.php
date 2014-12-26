<?php

/*
 * code is related to generate microsites for proptiger 
 * Created by Vimlesh Rajput on 16th Dec 2014
 * project id for testing 647719
 */
//echo "<pre>";print_r($_REQUEST);DIE;
if(isset($_REQUEST['searchProject'])){ 
    $projectId = $_REQUEST['projectId'];
    $smarty->assign("projectId",$projectId);
    $projectUrl = "http://nightly.proptiger-ws.com:8080/app/v4/project-detail/$projectId";
    $data = get_data($projectUrl);
    $obj = json_decode($data);
//echo "<pre>";print_r($obj);
    //project detail basic info
    $builderName = $obj->data->builder->name;
    $suburbName = $obj->data->locality->suburb->label;
    $localityName = $obj->data->locality->label;;
    $projectName = $obj->data->name;
    $projectDesc = $obj->data->description;
    $cityName = $obj->data->locality->suburb->city->label;

    $smarty->assign("builderName",$builderName);
    $smarty->assign("suburbName",$suburbName);
    $smarty->assign("localityName",$localityName);
    $smarty->assign("projectName",$projectName);
    $smarty->assign("projectDesc",$projectDesc);
    $smarty->assign("cityName",$cityName);

    //code for project configuration
    $arrProjectConfig = array();
    foreach($obj->data->properties as $k=>$v){
        $arrProjectConfig[$v->propertyId]['price_unitName'] = $v->unitName;
        $arrProjectConfig[$v->propertyId]['price_PerUnitArea'] = $v->pricePerUnitArea;
        $arrProjectConfig[$v->propertyId]['price_size'] = $v->size;
        $arrProjectConfig[$v->propertyId]['price_budget'] = $v->budget;
    }
    $smarty->assign("arrProjectConfig",$arrProjectConfig);
    //ECHO "<PRE>";
//print_r($obj->data->specifications);//die;
    //array for project specification
        foreach($obj->data->specifications->Flooring as $k=>$v){
           // echo "<br>".$k."=>".$v;
            if($k == 'Master Bedroom')
                $smarty->assign("master_bedroom_flooring",$v);
           if($k == 'Other Bedroom')
                $smarty->assign("other_bedroom_flooring",$v);
            if($k == 'Living/Dining')
                $smarty->assign("living_room_flooring",$v);
            if($k == 'Balcony')
                $smarty->assign("balcony_flooring",$v);
            if($k == 'Kitchen')
                $smarty->assign("kitchen_flooring",$v);
            if($k == 'Toilets')
                $smarty->assign("toilets_flooring",$v);
           
        }
        
        foreach($obj->data->specifications->Walls as $k=>$v){
            if($k == 'Kitchen')
                $smarty->assign("kitchen_walls",$v);
           if($k == 'Toilets')
                $smarty->assign("$toilets_walls",$v);
           if($k == 'Interior')
                $smarty->assign("interior_walls",$v);
           if($k == 'Exterior')
                $smarty->assign("exterior_walls",$v);
        }
        
        foreach($obj->data->specifications->Fittings as $k=>$v){
            if($k == 'Kitchen')
                $smarty->assign("kitchen_fixtures",$v);
           if($k == 'Toilets')
                $smarty->assign("toilets_fixtures",$v);
           if($k == 'Electrical')
                $smarty->assign("electrical_fitting",$v);
        }
        
        foreach($obj->data->specifications->Doors as $k=>$v){
            if($k == 'Internal')
                $smarty->assign("internal_doors",$v);
           if($k == 'Main')
                $smarty->assign("main_doors",$v);
        }
        $smarty->assign("Windows",$obj->data->specifications->Windows);

    //echo "<pre>";
    //print_r($arrProjectSpec);die;
    //print $obj->text;
    /* gets the data from a URL */
}elseif($_REQUEST['generateMicrosite']){
    
   // echo "<pre>";print_r($_REQUEST);die;
    $projectName = $_REQUEST['projectName'];
    $projectId = $_REQUEST['projectId'];
    $builderName = $_REQUEST['builderName'];
    $cityName = $_REQUEST['cityName'];
    $localityName = $_REQUEST['localityName'];
    $contactNumber = $_REQUEST['contactNumber'];
    $projectDesc = $_REQUEST['projectDesc'];
    $projectDisclaimer = $_REQUEST['projectDisclaimer'];
    $metaTitle = $_REQUEST['metaTitle'];
    $metaKeywords = $_REQUEST['metaKeywords'];
    $metaDescription = $_REQUEST['metaDescription'];
    
    $metaTitleSpecification = $_REQUEST['metaTitleSpecification'];
    $metaTitleFloorPlan = $_REQUEST['metaTitleFloorPlan'];
    $metaTitlePaymentPlan = $_REQUEST['metaTitlePaymentPlan'];
    $metaTitlePriceList = $_REQUEST['metaTitlePriceList'];
    $metaTitleSitePlan = $_REQUEST['metaTitleSitePlan'];
    $metaTitleLocationMap = $_REQUEST['metaTitleLocationMap'];
    $metaTitleContactus = $_REQUEST['metaTitleContactus'];
    
    $ErrorMsg = array();
    
    $arrProjectConfig = array();
    foreach($_REQUEST['configId'] as $k=>$v){
        if(stristr(strtolower($_REQUEST['price_unitName'][$k]),'proptiger'))
               $ErrorMsg['configName'] = "Proptiger word is not allowed."; 
        $arrProjectConfig[$v]['price_unitName'] = $_REQUEST['price_unitName'][$k];
        $arrProjectConfig[$v]['price_PerUnitArea'] = $_REQUEST['price_PerUnitArea'][$k];
        $arrProjectConfig[$v]['price_size'] = $_REQUEST['price_size'][$k];
        $arrProjectConfig[$v]['price_budget'] = $_REQUEST['price_size'][$k];
    }
    $smarty->assign("arrProjectConfig",$arrProjectConfig);
    
    $arrImage = array();
    foreach($_REQUEST['imageName'] as $k=>$v){
        if(stristr(strtolower($_REQUEST['imageTitle'][$k]),'proptiger') || stristr(strtolower($_REQUEST['imageAlt'][$k]),'proptiger'))
           $ErrorMsg['imgTitleName'] = "Proptiger word is not allowed."; 
        //$ErrorMsg['configName'] = "Proptiger word is not allowed.";
        $arrImage[$k]['imageTitle'] = $_REQUEST['imageTitle'][$k];
        $arrImage[$k]['imageAlt'] = $_REQUEST['imageAlt'][$k];
    }
    $smarty->assign("arrImage",$arrImage);
    $master_bedroom_flooring = $_REQUEST['master_bedroom_flooring'];
    $gaCode = $_REQUEST['gaCode'];
    $other_bedroom_flooring = $_REQUEST['other_bedroom_flooring'];
    $living_room_flooring = $_REQUEST['living_room_flooring'];
    $kitchen_flooring = $_REQUEST['kitchen_flooring'];
    $toilets_flooring = $_REQUEST['toilets_flooring'];
    $balcony_flooring = $_REQUEST['balcony_flooring'];
    $interior_walls = $_REQUEST['interior_walls'];
    $exterior_walls = $_REQUEST['exterior_walls'];
    $kitchen_walls = $_REQUEST['kitchen_walls'];
    $toilets_walls = $_REQUEST['toilets_walls'];
    $kitchen_fixtures = $_REQUEST['kitchen_fixtures'];
    $toilets_fixtures = $_REQUEST['toilets_fixtures'];
    $main_doors = $_REQUEST['main_doors'];
    $internal_doors = $_REQUEST['internal_doors'];
    $Windows = $_REQUEST['Windows'];
    $electrical_fitting = $_REQUEST['electrical_fitting'];
    $others = $_REQUEST['others'];
    
    $smarty->assign("projectId",$projectId);
    $smarty->assign("builderName",$builderName);
    $smarty->assign("suburbName",$suburbName);
    $smarty->assign("localityName",$localityName);
    $smarty->assign("contactNumber",$contactNumber);    
    $smarty->assign("projectName",$projectName);
    $smarty->assign("projectDesc",$projectDesc);
    $smarty->assign("projectDisclaimer",$projectDisclaimer);
    $smarty->assign("cityName",$cityName);
    
    $smarty->assign("metaTitle",$metaTitle);
    $smarty->assign("metaKeywords",$metaKeywords);
    $smarty->assign("metaDescription",$metaDescription);

    $smarty->assign("metaTitleSpecification",$metaTitleSpecification);
    $smarty->assign("metaTitleFloorPlan",$metaTitleFloorPlan);
    $smarty->assign("metaTitlePaymentPlan",$metaTitlePaymentPlan);
    $smarty->assign("metaTitlePriceList",$metaTitlePriceList);
    $smarty->assign("metaTitleSitePlan",$metaTitleSitePlan);
    $smarty->assign("metaTitleLocationMap",$metaTitleLocationMap);
    $smarty->assign("metaTitleContactus",$metaTitleContactus);
    
    $smarty->assign("gaCode",$gaCode);
    $smarty->assign("master_bedroom_flooring",$master_bedroom_flooring);
    $smarty->assign("other_bedroom_flooring",$other_bedroom_flooring);
    $smarty->assign("living_room_flooring",$living_room_flooring);
    $smarty->assign("kitchen_flooring",$kitchen_flooring);
    $smarty->assign("toilets_flooring",$toilets_flooring);
    $smarty->assign("balcony_flooring",$balcony_flooring);
    $smarty->assign("interior_walls",$interior_walls);
    $smarty->assign("exterior_walls",$exterior_walls);
    $smarty->assign("kitchen_walls",$kitchen_walls);
    $smarty->assign("toilets_walls",$toilets_walls);
    $smarty->assign("kitchen_fixtures",$kitchen_fixtures);
    $smarty->assign("toilets_fixtures",$toilets_fixtures);
    $smarty->assign("main_doors",$main_doors);
    $smarty->assign("internal_doors",$internal_doors);
    
    $smarty->assign("Windows",$Windows);
    $smarty->assign("electrical_fitting",$electrical_fitting);
    $smarty->assign("others",$others);

    if(empty($projectName)){
       $ErrorMsg["projectName"] = "Project name can't be blank.";
    }elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $projectName)){
       $ErrorMsg["projectName"] = "Special characters are not allowed.";
    }elseif(stristr(strtolower($projectName),'proptiger')){
        $ErrorMsg["projectName"] = "Proptiger word is not allowed.";
    }
  
    
    if(empty($builderName)){
       $ErrorMsg["builderName"] = "Builder name can't be blank.";
    }elseif(stristr(strtolower($builderName),'proptiger')){
        $ErrorMsg["builderName"] = "Proptiger word is not allowed.";
    }
    if(empty($cityName)){
       $ErrorMsg["cityName"] = "City name can't be blank.";
    }elseif(stristr(strtolower($cityName),'proptiger')){
        $ErrorMsg["cityName"] = "Proptiger word is not allowed.";
    }
    if(empty($localityName)){
       $ErrorMsg["localityName"] = "Locality name can't be blank.";
    }elseif(stristr(strtolower($localityName),'proptiger')){
        $ErrorMsg["localityName"] = "Proptiger word is not allowed.";
    }
    
    if(empty($contactNumber)){
       $ErrorMsg["contactNumber"] = "Contact number can't be blank.";
    }elseif(stristr(strtolower($contactNumber),'proptiger')){
        $ErrorMsg["contactNumber"] = "Proptiger word is not allowed.";
    }
    elseif(!preg_match("/^[0-9]{11}$/",$contactNumber)) {
        $ErrorMsg["contactNumber"] = "Please enter a valid mobile number.";
    }
    
    if(empty($metaTitle)){
       $ErrorMsg["metaTitle"] = "Meta title can't be blank.";
    }elseif(stristr(strtolower($metaTitle),'proptiger')){
        $ErrorMsg["metaTitle"] = "Proptiger word is not allowed.";
    }
    
    if(empty($projectDisclaimer)){
       $ErrorMsg["projectDisclaimer"] = "Project disclaimer can't be blank.";
    }elseif(stristr(strtolower($projectDisclaimer),'proptiger')){
        $ErrorMsg["projectDisclaimer"] = "Proptiger word is not allowed.";
    }
    if(empty($projectDesc)){
       $ErrorMsg["projectDesc"] = "Project description can't be blank.";
    }elseif(stristr(strtolower($projectDesc),'proptiger')){
        $ErrorMsg["projectDesc"] = "Proptiger word is not allowed.";
    }
    
    //specification proptiger word searching
    if(stristr(strtolower($master_bedroom_flooring),'proptiger')){
       $ErrorMsg["master_bedroom_flooring"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($other_bedroom_flooring),'proptiger')){
       $ErrorMsg["other_bedroom_flooring"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($living_room_flooring),'proptiger')){
       $ErrorMsg["living_room_flooring"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($living_room_flooring),'proptiger')){
       $ErrorMsg["living_room_flooring"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($toilets_flooring),'proptiger')){
       $ErrorMsg["toilets_flooring"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($balcony_flooring),'proptiger')){
       $ErrorMsg["balcony_flooring"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($interior_walls),'proptiger')){
       $ErrorMsg["interior_walls"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($interior_walls),'proptiger')){
       $ErrorMsg["interior_walls"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($exterior_walls),'proptiger')){
       $ErrorMsg["exterior_walls"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($kitchen_walls),'proptiger')){
       $ErrorMsg["kitchen_walls"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($toilets_walls),'proptiger')){
       $ErrorMsg["toilets_walls"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($kitchen_fixtures),'proptiger')){
       $ErrorMsg["kitchen_fixtures"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($toilets_fixtures),'proptiger')){
       $ErrorMsg["toilets_fixtures"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($main_doors),'proptiger')){
       $ErrorMsg["main_doors"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($internal_doors),'proptiger')){
       $ErrorMsg["internal_doors"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($Windows),'proptiger')){
       $ErrorMsg["Windows"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($electrical_fitting),'proptiger')){
       $ErrorMsg["electrical_fitting"] = "Proptiger word is not allowed.";
    }
    if(stristr(strtolower($others),'proptiger')){
       $ErrorMsg["others"] = "Proptiger word is not allowed.";
    }    
  
    $smarty->assign("ErrorMsg", $ErrorMsg);
    //echo"<pre>"; 
    // echo json_encode($ErrorMsg);die;
     if(count($ErrorMsg)>0) {
            // Do Nothing
       }
    else {
        //json array for home page
        $jsonArr = array();
        $jsonArr['pricelist']['project'] = $_REQUEST['projectName'];
        $jsonArr['pricelist']['builder'] = $_REQUEST['builderName'];
        $jsonArr['pricelist']['builderLogo'] = 'builder-logo.jpg';
        $jsonArr['pricelist']['disclaimer'] = $_REQUEST['projectDisclaimer'];
        $jsonArr['pricelist']['locality'] = $_REQUEST['localityName'];
        $jsonArr['pricelist']['city'] = $_REQUEST['cityName'];
        $jsonArr['pricelist']['contact'] = $_REQUEST['contactNumber'];

        $jsonArr['pricelist']['home']['description'] = $_REQUEST['projectDesc'];
        $jsonArr['pricelist']['home']['title'] = $_REQUEST['metaTitle'];
        $jsonArr['pricelist']['home']['metaKeyword'] = $_REQUEST['metaKeywords'];
        $jsonArr['pricelist']['home']['metaDescription'] = $_REQUEST['metaDescription'];
        $arrHomeImg = array("locationmap-01.jpg","siteplan-01.jpg","floorplan-01.jpg");
        $jsonArr['pricelist']['home']['homePagePlanImages'] = $arrHomeImg;

        //json array for price page
        $jsonArr['pricelist']['pricetable']['title'] = $_REQUEST['metaTitlePriceList'];
        $arrConfig = array();
        $arrFloorImg = array();
        $cntCont = 1;
        foreach($_REQUEST['configId'] as $k=>$v){
            if($_REQUEST['price_unitName'][$k] != ''){
                 $arrConfig[$k]['type'] = $_REQUEST['price_unitName'][$k];
                 $arrConfig[$k]['area'] = $_REQUEST['price_PerUnitArea'][$k];
                 $arrConfig[$k]['rate'] = $_REQUEST['price_size'][$k];
                 $arrConfig[$k]['BSP'] = "".$_REQUEST['price_size'][$k]*$_REQUEST['price_PerUnitArea'][$k]."";
                 if($cntCont <= 9)
                    $arrFloorImg[] = "floorplan-0$cntCont.jpg";
                 else
                     $arrFloorImg[] = "floorplan-$cntCont.jpg";
                 $cntCont++;
            }
         }
         $jsonArr['pricelist']['pricetable'] = $arrConfig;
         $arrImgTitle = array();
         $arrImgName = array();
         $arrImgAlt = array();
         foreach($_REQUEST['imageName'] as $k=>$v){
             if($_REQUEST['imageAlt'][$k] != ''){
                 $arrImgTitle[$k] = $_REQUEST['imageTitle'][$k];
                 $arrImgName[$k] = $_REQUEST['imageName'][$k];
                 $arrImgAlt[$k] = $_REQUEST['imageAlt'][$k];
             }
         }       
         $jsonArr['pricelist']['slidingImages']['imgs'] = $arrImgName;
         $jsonArr['pricelist']['slidingImages']['imgstitle'] = $arrImgTitle;
         $jsonArr['pricelist']['slidingImages']['imgAlt'] = $arrImgAlt;
        //json array for specification page
       //  echo "<pre>";print_r($_REQUEST);die;
        $jsonArr['pricelist']['specification']['title'] = $_REQUEST['metaTitleSpecification'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Flooring']['MasterBedroom'] = $_REQUEST['master_bedroom_flooring'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Flooring']['OtherBedroom'] = $_REQUEST['other_bedroom_flooring'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Flooring']['LivingRoom'] = $_REQUEST['living_room_flooring'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Flooring']['Kitchen'] = $_REQUEST['kitchen_flooring'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Flooring']['Toilets'] = $_REQUEST['toilets_flooring'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Flooring']['Balcony'] = $_REQUEST['balcony_flooring'];

        $jsonArr['pricelist']['specification']['specificationInfo']['Walls']['Interior'] = $_REQUEST['interior_walls'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Walls']['Exterior'] = $_REQUEST['exterior_walls'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Walls']['Kitchen'] = $_REQUEST['kitchen_walls'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Walls']['Toilets'] = $_REQUEST['toilets_walls'];

        $jsonArr['pricelist']['specification']['specificationInfo']['Fittings']['Kitchen'] = $_REQUEST['kitchen_fixtures'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Fittings']['Toilets'] = $_REQUEST['toilets_fixtures'];

        $jsonArr['pricelist']['specification']['specificationInfo']['Doors']['Main'] = $_REQUEST['main_doors'];
        $jsonArr['pricelist']['specification']['specificationInfo']['Doors']['Internal'] = $_REQUEST['internal_doors'];

        $jsonArr['pricelist']['specification']['specificationInfo']['Windows']['Window'] = $_REQUEST['Windows'];

        $jsonArr['pricelist']['specification']['specificationInfo']['Electrical']['Fitting'] = $_REQUEST['electrical_fitting'];

        $jsonArr['pricelist']['specification']['specificationInfo']['Others']['Other'] = $_REQUEST['others'];

        //json array for floor plan page
        $jsonArr['pricelist']['floor-plan']['title'] = $_REQUEST['metaTitleFloorPlan'];
        $jsonArr['pricelist']['floor-plan']['floorimage'] = $arrFloorImg;

        //json array for payment plan
        $jsonArr['pricelist']['payment-plan']['title'] = $_REQUEST['metaTitlePaymentPlan'];
        $arrPaymentImg = array('paymentplan-01.jpg','paymentplan-02.jpg','paymentplan-03.jpg');
        $jsonArr['pricelist']['payment-plan']['paymentplan'] = $arrPaymentImg;   
        //json array for site plan
        $jsonArr['pricelist']['site-plan']['title'] = $_REQUEST['metaTitleSitePlan'];
        $arrSiteImg = array('siteplan-01.jpg','siteplan-02.jpg','siteplan-03.jpg');
        $jsonArr['pricelist']['site-plan']['siteplan'] = $arrSiteImg;
        //json array for location map
        $jsonArr['pricelist']['location-map']['title'] = $_REQUEST['metaTitleLocationMap'];
        $arrLocationImg = array('locationmap-01.jpg','locationmap-02.jpg','locationmap-03.jpg');
        $jsonArr['pricelist']['location-map']['locationmap'] = $arrLocationImg;
        
         //json array for location map
        $jsonArr['pricelist']['price-list']['title'] = $_REQUEST['metaTitlePriceList'];
        //$arrLocationImg = array('locationmap-01.jpg','locationmap-02.jpg','locationmap-03.jpg');
        //$jsonArr['pricelist']['location-map']['locationmap'] = $arrLocationImg;

        //json array for contact us
        $jsonArr['pricelist']['contact-us']['title'] = $_REQUEST['metaTitleContactus'];
      //encoding the PHP array
        if(is_dir('/tmp/microsite.json'))
           rmdir('/tmp/microsite.json');
        touch('/tmp/microsite.json',0777);
        $fp = fopen('/tmp/microsite.json', 'w');
        fwrite($fp, json_encode($jsonArr));
        fclose($fp);//die;
        $smarty->assign("succesMsg","<font color = green>Microsite data has been generated successfully</font>"); 
    
      //  $filename ="microsite.json";
      /*  $file = 'microsite.json';
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename={$file}");
        header("Content-Type: application/json");
        header("Content-Transfer-Encoding: binary");

        readfile($file);
        */
    }
}

function get_data($url)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
