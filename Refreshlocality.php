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
		}/*else if($_REQUEST["suburb"] == 'include'){	
			$getLocality = Locality::getLocalityByCity($ctid);			
			$getSuburb = Suburb::SuburbArr($ctid);					
		}*/
		else
			$getLocality = Locality::getLocalityByCity($ctid);							  
						
        
        if($_REQUEST["suburb"] == 'include'){
			echo  "<select style='min-height:200px' name = 'locality' id = 'locality' onchange = 'localitySelect(this.value);'  multiple>";
			echo  "<option value=".$ctid.">ALL Localities</option>"; 
			/*foreach($getSuburb as $key=>$value)
			{
				echo "<option value=".$key.">". "suburb-" . $value . "</option>";
			}*/
		}else{
			echo  "<select name = 'locality' id = 'locality' onchange = 'localitySelect(this.value);'>";
			echo  "<option value=''>Select locality</option>"; 
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
    elseif(isset($_REQUEST["locArr"]) && $_REQUEST["compOrder"]==1){
		
		if($_REQUEST["locArr"]){
			$locArr = explode(",",$_REQUEST["locArr"]);
			$city_arr = array();
			foreach($locArr as $k=>$v){
			  if($v<1000){
				//City
				$city = City::getCityById($v);
				$city_arr[$city[0]->label]["ALL"]= $v;
								 
			  }	
			  if($v < 50000 && $v>1000){
				//suburb
				$city = Suburb::getSuburbCity($v);
				$city_arr[$city[0]->cityname]["suburb-".$city[0]->suburb_name]= $v;
								 
			  }elseif($v > 50000){
				//locs
				$city = Locality::getLocalityCity($v);
				$city_arr[$city[0]->cityname][$city[0]->locname]= $v;
							
			  }	
			}
			print '<TABLE>';
			foreach($city_arr as $city=>$data){
			  print '<TR>';
			  print '<TD><b>'.$city.'</b></TD>';
			  print '<TD>';	
			  $exclude = 0;
			  ksort($data);
			  foreach($data as $label=>$id){				 
				 if(!$exclude)   
				   print '<div name="'.$id.'" style="background:#ccc;float:left;padding:2px;margin:1px;" id="locID-'.$id.'">'.$label.'&nbsp;&nbsp;<img src="images/stop.gif" style="position:relative;top:2px" id="'.$id.'" onclick="remove_locality(this.id)"></div>'; 
				 if($label == 'ALL')
				   $exclude = 1; 				 
			  }
			  print '</TD>';
			  print '</TR>';
			   
			}
			print '</TABLE>';
		}else{
			print '';
		}		  
		
    }
    else {
        echo  "<select name = 'locality' id = 'locality'>";
        echo  "<option value=''>Select locality</option>";  
        echo  "</select>";
    }
?>
