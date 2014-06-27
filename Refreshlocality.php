<?php

    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("modelsConfig.php");
    include("includes/configs/configs.php");
     $ctid = $_REQUEST["ctid"];
             
    if($ctid != '') {       
		$getLocality = Array();      
        if($ctid == 'othercities'){
			foreach($arrOtherCities as $key => $value){
				$cityLocality = Locality::getLocalityByCity($key);
				if(!empty($cityLocality))
					$getLocality = array_merge($getLocality,$cityLocality);
			}
		}else if($_REQUEST["suburb"] == 'include'){	
			$getLocality = Locality::getLocalityByCity($ctid);			
			$getSuburb = Suburb::SuburbArr($ctid);					
		}
		else
			$getLocality = Locality::getLocalityByCity($ctid);				  
						
        echo  "<select name = 'locality' id = 'locality' onchange = 'localitySelect(this.value);'>";
        echo  "<option value=''>Select locality</option>"; 
        if($_REQUEST["suburb"] == 'include'){
			foreach($getSuburb as $key=>$value)
			{
				echo "<option value=".$key.">". "suburb-" . $value . "</option>";
			}
		} 	
        foreach( $getLocality as $value )
        {
			if($ctid == 'othercities')
				echo "<option value=".$value->locality_id.">".$value->cityname." - ".$value->label . "</option>"; 
			else
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
