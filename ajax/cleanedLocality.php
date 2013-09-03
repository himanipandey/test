<?php
    session_start();
    include("../dbConfig.php");
    include("../appWideConfig.php");
    include("../builder_function.php");
    include("../modelsConfig.php"); 

    $latLongList = '0,1,2,3,4,5,6,7,8,9';
    $localityId	= $_REQUEST['localityId'];
    
    $allProject = ResiProject::find('all', array('conditions' => array("latitude not in($latLongList) 
                    and longitude not in($latLongList) and locality_id = '".$localityId."'"),'order' => 'LONGITUDE,LATITUDE ASC'));
    //print_r($allProject->latitude);
    if( count($allProject)>0 ) {
        $arrLatitude = array();
        $arrLongitude = array();
       foreach($allProject as $val) {
           $arrLatitude[] = $val->latitude;
           $arrLongitude[] = $val->longitude;
       }
        $option = Locality::find($localityId);

        $option->max_latitude = max($arrLatitude);
        $option->max_longitude = max($arrLongitude);
        $option->min_latitude = min($arrLatitude);
        $option->min_longitude = min($arrLongitude);
        $option->locality_cleaned = '1';

        $result = $option->save();
        if($result) {

            echo "<font color = 'green'>Latitude/Longitude has been cleaned successfully.<br>&nbsp;&nbsp;".
                "Max Latitide = ".   max($arrLatitude) ."&nbsp;&nbsp;
                 Min Latitide = ".   min($arrLatitude) ."<br>   
                 Max Longitude = ".   max($arrLongitude) ."&nbsp;&nbsp;
                 Min Longitude = ".   min($arrLongitude)."</font>" ;
        }
    }
    else {
        echo "<font color = 'red'>No recond found in project table</font>";
    }
     
  ?>
