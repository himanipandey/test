<?php

    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("modelsConfig.php");
    include("/includes/configs/configs.php");
     $ctid = $_REQUEST["ctid"];
    if($ctid != '') {             
        $getLocality = Locality::getLocalityByCity($ctid);
        echo  "<select name = 'locality' id = 'locality'>";
        echo  "<option value=''>Select locality</option>";  	
        foreach( $getLocality as $value )
        {
           echo "<option value=".$value->locality_id.">".$value->label . "</option>";
        }
        echo  "</select>";
    }
    else {
        echo  "<select name = 'locality' id = 'locality'>";
        echo  "<option value=''>Select locality</option>";  
        echo  "</select>";
    }
?>