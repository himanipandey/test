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
    
    echo "<pre>";print_r($_REQUEST);//die;
    $projectName = $_REQUEST['projectName'];
    $projectId = $_REQUEST['projectId'];
    $builderName = $_REQUEST['builderName'];
    $cityName = $_REQUEST['cityName'];
    $localityName = $_REQUEST['localityName'];
    $projectDesc = $_REQUEST['projectDesc'];
    $metaTitle = $_REQUEST['metaTitle'];
    $metaKeywords = $_REQUEST['metaKeywords'];
    $metaDescription = $_REQUEST['metaDescription'];
    
    $arrProjectConfig = array();
    foreach($_REQUEST['configId'] as $k=>$v){
        $arrProjectConfig[$v]['price_unitName'] = $_REQUEST['price_unitName'][$k];
        $arrProjectConfig[$v]['price_PerUnitArea'] = $_REQUEST['price_PerUnitArea'][$k];
        $arrProjectConfig[$v]['price_size'] = $_REQUEST['price_size'][$k];
        $arrProjectConfig[$v]['price_budget'] = $_REQUEST['price_size'][$k];
    }
    $smarty->assign("arrProjectConfig",$arrProjectConfig);
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
    $windows = $_REQUEST['windows'];
    $electrical_fitting = $_REQUEST['electrical_fitting'];
    $others = $_REQUEST['others'];
    
    $smarty->assign("projectId",$projectId);
    $smarty->assign("builderName",$builderName);
    $smarty->assign("suburbName",$suburbName);
    $smarty->assign("localityName",$localityName);
    $smarty->assign("projectName",$projectName);
    $smarty->assign("projectDesc",$projectDesc);
    $smarty->assign("cityName",$cityName);
    
    $smarty->assign("metaTitle",$metaTitle);
    $smarty->assign("metaKeywords",$metaKeywords);
    $smarty->assign("metaDescription",$metaDescription);
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

    $ErrorMsg = array();
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
    
    if(empty($metaTitle)){
       $ErrorMsg["metaTitle"] = "Meta title can't be blank.";
    }elseif(stristr(strtolower($metaTitle),'proptiger')){
        $ErrorMsg["metaTitle"] = "Proptiger word is not allowed.";
    }
     if(empty($metaKeywords)){
       $ErrorMsg["metaKeywords"] = "Meta keywords can't be blank.";
    }elseif(stristr(strtolower($metaKeywords),'proptiger')){
        $ErrorMsg["metaKeywords"] = "Proptiger word is not allowed.";
    }
    if(empty($metaDescription)){
       $ErrorMsg["metaDescription"] = "Meta description can't be blank.";
    }elseif(stristr(strtolower($metaDescription),'proptiger')){
        $ErrorMsg["metaDescription"] = "Proptiger word is not allowed.";
    }
    if(empty($projectDesc)){
       $ErrorMsg["projectDesc"] = "Project description can't be blank.";
    }elseif(stristr(strtolower($projectDesc),'proptiger')){
        $ErrorMsg["projectDesc"] = "Proptiger word is not allowed.";
    }
    if(empty($gaCode)){
       $ErrorMsg["gaCode"] = "GA code can't be blank.";
    }elseif(stristr(strtolower($gaCode),'proptiger')){
        $ErrorMsg["gaCode"] = "Proptiger word is not allowed.";
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
     if(count($ErrorMsg)>0) {
            // Do Nothing
       }
       else {
           
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
