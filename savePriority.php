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
$priority   = $_POST['prio'];
$cityId     = $_POST['cityId'];
$sub        = $_POST['sub'];
$loc        = $_POST['loc'];
$autoadjust = $_POST['autoadjust'];
if(!empty($_POST['projectId']))
{
    $projectId    = $_POST['projectId'];
    if($priority < 1 || trim($priority) == '' || $priority > 15){
	     echo 4; return;
	}
    if(!empty($sub)){
        $count = checkProjAvail($projectId, $priority, 'suburb', $sub);
        if($count > 0)
        {
            if($autoadjust){
                autoAdjustProjPrio($sub, $priority, 'suburb');
            }
            if(getProjectCount($sub, 'suburb') >= 15)
            {
                autoAdjustMaxCountProjPrio($sub, $priority, 'suburb');
            }
            updateProj($projectId, $priority, 'suburb', $sub);
        }else{
            echo "2";
        }
    }else if(!empty ($loc)){
        $count = checkProjAvail($projectId, $priority, 'locality', $loc);
        if($count > 0)
        {
            if($autoadjust){
                autoAdjustProjPrio($loc, $priority, 'locality');
            }
            if(getProjectCount($loc, 'locality') >= 15)
            {
               autoAdjustMaxCountProjPrio($loc, $priority, 'locality'); 
            }
            updateProj($projectId, $priority, 'locality', $loc);
        }else{
            echo "2";
        }
    }else{
        $count = checkProjAvail($projectId, $priority, 'city', $cityId);
        if($count > 0)
        {
            if($autoadjust){
                autoAdjustProjPrio($cityId, $priority, 'city');
            }
            if(getProjectCount($cityId, 'city') >= 15)
            {
               autoAdjustMaxCountProjPrio($cityId, $priority, 'city'); 
            }
            updateProj($projectId, $priority, 'city', $cityId);
        }else{
            echo "2";
        }
    }
}
else
{
	if(!empty($sub)){
		if(!checkSubLocInCity('suburb',$cityId,$sub)){
			echo 3; return;
		}
    }else if(!empty ($loc)){
		if(!checkSubLocInCity('locality',$cityId,$loc)){
			echo 3; return;
		}
    }
		
    if($priority < 1 || trim($priority) == '' || $priority > 15){
	     echo 2; return;
	}
    
    if($autoadjust)
    {
        autoAdjustPrio(SUBURB, $cityId, $priority);
        autoAdjustPrio(LOCALITY, $cityId, $priority);
    }
    
    if(!empty($sub)){
        updateSuburb($sub, $priority);
    }else if(!empty ($loc)){
        updateLocality($loc, $priority);
    }
}
?>
