<?php
//echo "hi";
//die(hello);
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
$cityId     = $_POST['cityid'];
//$a = $_POST['nearPlaceId'];
//die($priority.$cityId.$a);
//$sub        = $_POST['sub'];
//$loc        = $_POST['loc'];
//$autoadjust = $_POST['autoadjust'];
if(!empty($_POST['nearPlaceId']))
{
    $nearPlaceId    = $_POST['nearPlaceId'];
    $status = $_POST['status'];
    if($priority < 1 || trim($priority) == '' || $priority > 5){
	     echo 4; return;
	}
    if(!empty($sub)){
        $count = checkNearPlaceAvail($nearPlaceId, $priority, 'suburb', $sub);
        if($count > 0)
        {
            /*if($autoadjust){
                autoAdjustProjPrio($sub, $priority, 'suburb');
            }
            if(getProjectCount($sub, 'suburb') >= 15)
            {
                autoAdjustMaxCountProjPrio($sub, $priority, 'suburb');
            }*/
            updateNearPlace($nearPlaceId, $priority, 'suburb', $sub);
        }else{
            echo "2";
        }
    }else if(!empty ($loc)){
        $count = checkNearPlaceAvail($nearPlaceId, $priority, 'locality', $loc);
        if($count > 0)
        {
            /*if($autoadjust){
                autoAdjustProjPrio($loc, $priority, 'locality');
            }
            if(getProjectCount($loc, 'locality') >= 15)
            {
               autoAdjustMaxCountProjPrio($loc, $priority, 'locality'); 
            }*/
            updateNearPlace($nearPlaceId, $priority, 'locality', $loc);
        }else{
            echo "2";
        }
    }else{

        //die("here");
        //$count = checkNearPlaceAvail($nearPlaceId, $priority, 'city', $cityId);
        //if($count > 0)
        //{
            /*if($autoadjust){
                autoAdjustProjPrio($cityId, $priority, 'city');
            }
            if(getProjectCount($cityId, 'city') >= 15)
            {
               autoAdjustMaxCountProjPrio($cityId, $priority, 'city'); 
            }*/

            updateNearPlace($nearPlaceId, $priority, $status, 'city', $cityId);
        //}else{
            //echo "2";
        //}
    }
}
else
{
	/*if(!empty($sub)){
		if(!checkSubLocInCity('suburb',$cityId,$sub) || !is_numeric($sub)){
			echo 3; return;
		}
    }else if(!empty ($loc) ){
		if(!checkSubLocInCity('locality',$cityId,$loc) || !is_numeric($loc)){
			echo 3; return;
		}
    }*/
		
    if($priority < 1 || trim($priority) == '' || $priority > 5){
	     echo 4; return;
	}
    
    /*if($autoadjust)
    {
        autoAdjustPrio(SUBURB, $cityId, $priority);
        autoAdjustPrio(LOCALITY, $cityId, $priority);
    }
    */
   
}
?>
