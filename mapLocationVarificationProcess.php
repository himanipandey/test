<?php

$citylist = City::CityArr();
$smarty->assign("citylist", $citylist);

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
    }else{
        
    }



    $smarty->assign("city", $city);
    $smarty->assign("projectId", $_GET['projectId']);
    $smarty->assign("locality", $locality);
    $smarty->assign("project_name", $project_name);

    print "<pre>" . print_r($_REQUEST, 1) . "</pre>";
}
?>