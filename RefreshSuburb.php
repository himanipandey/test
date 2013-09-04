<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
        
        if($_REQUEST['part']=='refreshLoc') {
            
            $city_id        =   $_REQUEST["id"];
            $locality_id    =   $_REQUEST["locality_id"];
            if($locality_id == '')  {
                if($city_id != '') {
                       $sql = "SELECT A.LOCALITY_ID, A.CITY_ID, A.LABEL FROM ".LOCALITY." AS A WHERE A.CITY_ID = " . $city_id . " AND 
                               A.VISIBLE_IN_CMS = '1' ORDER BY A.LABEL ASC";
                       $data = mysql_query($sql);
                       ?>
                       <option value=''>Select</option>
                       <?php
                           while ($dataArr = mysql_fetch_array($data)) { ?>
                              <option value="<?php echo $dataArr["LOCALITY_ID"];?>"><?php echo $dataArr["LABEL"]; ?> </option>;
                   <?php   }
                }
                else 
                    echo "<option value=''>Select</option>"; 
            }

            if($locality_id != '') {
                $sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL, B.LABEL AS SUBURB FROM ".LOCALITY." AS A INNER JOIN ".SUBURB." AS B ON (A.SUBURB_ID = B.SUBURB_ID) WHERE A.LOCALITY_ID = '" . $locality_id."' ORDER BY B.LABEL ASC";
                $data = mysql_query($sql);
                while ($dataArr = mysql_fetch_array($data))
             { ?>
                     <option value="<?php echo $dataArr["SUBURB_ID"];?>"><?php echo $dataArr["SUBURB"]; ?> </option>;
            <?php  }	
            }		
        } else {

            $city_id		=	$_REQUEST["id"];
            $suburb_id          =	$_REQUEST["suburb_id"];
            if($suburb_id == '')  {
                   if($city_id != '')
                   {
                           $suburbArr = Array();
                           $sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM ".SUBURB." AS A WHERE A.CITY_ID = " . $city_id . " ORDER BY A.LABEL ASC";

                           $data = mysql_query($sql);

                           while ($dataArr = mysql_fetch_array($data))
                            {
                                   array_push($suburbArr, $dataArr);
                            }
                           echo "<option value=''>Select</option>";
                           foreach($suburbArr as $val)
                           {
                            echo "<option value=".$val["SUBURB_ID"].">".$val["LABEL"] . "</option>";
                           }
                    }
                   else
                        echo "<option value=''>Select</option>"; 
            }
        if($suburb_id != '')	
        {
           $localityArr = Array();
                   $sql = "SELECT A.LOCALITY_ID, A.SUBURB_ID, A.CITY_ID, A.LABEL FROM ".LOCALITY." AS A WHERE A.CITY_ID = " . $city_id." AND VISIBLE_IN_CMS = '1'";
                   if ($suburb_id != null) {
                   $sql .= " AND A.SUBURB_ID = " . $suburb_id;
                   }

                   $data = mysql_query($sql);
                   while ($dataArr = mysql_fetch_array($data))
                    {
                           array_push($localityArr, $dataArr);
                    }	
                    echo  "<option value=''>Select</option>";  	
                    foreach($localityArr as $val)
                    {
                   echo "<option value=".$val["LOCALITY_ID"].">".$val["LABEL"] . "</option>";
                    }
        }	
 }
?>