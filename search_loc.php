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



if(!empty($_POST['search']))
{
    $cityLocArr = CityLocationRel::CityLocArr('' , mysql_escape_string(trim($_POST['search'])));
    
    if(!empty($cityLocArr))
        echo json_encode($cityLocArr);
    else
        echo json_encode(array("response" => "error"));
    
    die;
    //print'<pre>';
//    print_r($cityLocArr);
//    die;
}

?>