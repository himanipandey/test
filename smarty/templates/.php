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
    $cityLocArr = CityLocationRel::CityLocArr();
}

?>