<?php

$citylist = City::CityArr();
$smarty->assign("citylist", $citylist);

$errorMsg = '';

if (isset($_REQUEST['generateMap'])) {
    if (!isset($_REQUEST['city']))
        $_REQUEST['city'] = '';
    $city = $_REQUEST['city'];

    if (!isset($_REQUEST['locality']))
        $_REQUEST['locality'] = '';
    $locality = $_REQUEST['locality'];

    if (!isset($_REQUEST['projectId']))
        $_REQUEST['projectId'] = '';
    $projectId = $_REQUEST['projectId'];

    if ($city != '') {
        $getLocality = Array();
        if ($city == 'othercities') {
            foreach ($arrOtherCities as $key => $value) {
                $cityLocality = Locality::getLocalityByCity($key);
                if (!empty($cityLocality))
                    $getLocality = array_merge($getLocality, $cityLocality);
            }
        } else
            $getLocality = Locality::getLocalityByCity($city);

        $smarty->assign("getLocality", $getLocality);
    }
    
    $project = ResiProject::virtual_find($projectId);

    //fetching project name
    if ($project) {
        $projectId = $project->project_id;
        $projectName = $project->project_name;
        $projectLatitude = $project->latitude;
        $projectLongitude = $project->longitude; 
        
        if($projectLatitude || $projectLatitude){             
            $smarty->assign("project_name", $projectName);
            $smarty->assign("projectLatitude", $projectLatitude);
            $smarty->assign("projectLongitude", $projectLongitude);
        }else{
            $errorMsg = "Project Lat or Long not available!";
        }
        
        //fetching locality details
        $localityDetail = Locality::getLocalityById($locality);
        
        $localityLatitude = $localityDetail[0]->latitude;
        $localityLongitude = $localityDetail[0]->longitude;
        $localityLatMax = $localityDetail[0]->max_latitude;
        $localityLongMax = $localityDetail[0]->max_longitude;
        $localityLongMin = $localityDetail[0]->min_longitude;
        $localityLatMin = $localityDetail[0]->min_latitude;
        
        if($localityLatMax || $localityLongMax || $localityLongMin || $localityLatMin ){           
            $smarty->assign("localityLatitude", $localityLatitude);
            $smarty->assign("localityLongitude", $localityLongitude);
            $smarty->assign("localityLatMax", $localityLatMax);
            $smarty->assign("localityLongMax", $localityLongMax);
            $smarty->assign("localityLongMin", $localityLongMin);
            $smarty->assign("localityLatMin", $localityLatMin);
            
        }else{
            $errorMsg = "Locality Boundaries not available!";
        }
        
        
        
    }else{
        $errorMsg = "Project not found!";
    }


    $smarty->assign("errorMsg", $errorMsg);
    $smarty->assign("city", $city);
    $smarty->assign("projectId", $_GET['projectId']);
    $smarty->assign("locality", $locality);    
 
}
?>