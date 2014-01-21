<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
        include("modelsConfig.php");
	include("includes/configs/configs.php");
        
        if($_REQUEST['part']=='refreshLoc') {
            
            $city_id = $_REQUEST["id"];
            $loc_id = $_REQUEST["loc_id"];
            if($loc_id == '')  {
                if($city_id != '') {
        
                        $getSub = Suburb::find('all',array('conditions'=>
                                  array('city_id = ? and status = ?',$city_id, 'active'),'order' => 'label asc'));
                       ?>
                       <option value=''>Select</option>
                       <?php
                           foreach ($getSub  as $value) { 
								 $getLocality = Locality::find('all',array('conditions'=>
                    array('suburb_id = ? and status = ?',$value->suburb_id, 'active'),'order' => 'label asc')); 
								foreach ($getLocality as $value){
								?>
                              <option value="<?php echo $value->locality_id;?>"><?php echo $value->label; ?> </option>;
                   <?php  		}
							}
                }
                else 
                    echo "<option value=''>Select</option>"; 
            }

            if($loc_id != '') {
                 $getLocality = Locality::find('all',array('conditions'=>
                    array('locality_id = ? and status = ?',$loc_id, 'active'),'order' => 'label asc')); 
                   $getSub = Suburb::find('all',array('conditions'=>
                                  array('suburb_id = ? and status = ?',$getLocality[0]->suburb_id, 'active'),'order' => 'label asc'));   
                      ?>            
                        <option value="<?php echo $getSub[0]->suburb_id;?>"><?php echo $getSub[0]->label; ?> </option>;           
                  <?php
            }		
        } else if($_REQUEST['part']=='addquickcity') {
            
            $city_id = $_REQUEST["id"];
            //$locality_id    =   $_REQUEST["locality_id"];
            
                if($city_id != '') {
                    if($_REQUEST['flg'] == 'locality')  {
                       $sql = "SELECT A.LOCALITY_ID, c.CITY_ID, A.LABEL 
                            FROM ".LOCALITY." AS A
                            inner join suburb s on A.suburb_id = s.suburb_id
                            inner join city c on s.city_id = c.city_id
                           WHERE 
                           c.CITY_ID = " . $city_id ." ORDER BY A.LABEL ASC";
                       $data = mysql_query($sql);
                       ?>
                       <option value=''>Select</option>
                       <?php
                           while ($dataArr = mysql_fetch_array($data)) { ?>
                              <option value="<?php echo $dataArr["LOCALITY_ID"];?>"><?php echo $dataArr["LABEL"]; ?> </option>
                    <?php   }
                  } else 
                        echo "<option value=''>Select</option>"; 
            

            if($_REQUEST['flg'] == 'suburb')  {
                $sql = "SELECT B.SUBURB_ID, A.CITY_ID, A.LABEL, B.LABEL AS SUBURB FROM ".CITY." AS A INNER JOIN ".SUBURB." AS B ON (A.CITY_ID = B.CITY_ID) WHERE B.CITY_ID = '" . $city_id."' ORDER BY B.LABEL ASC";
                $data = mysql_query($sql);
                while ($dataArr = mysql_fetch_array($data))
             { ?>
                     <option value="<?php echo $dataArr["SUBURB_ID"];?>"><?php echo $dataArr["SUBURB"]; ?> </option>
            <?php  }	
            }
          }
        } else if ($_REQUEST['part']=='autofillsub'){
            $locality = $_REQUEST['loc'];
            $cityid = $_REQUEST['cityid'];
            $sql = "SELECT A.SUBURB_ID, A.LABEL FROM ".SUBURB." AS A INNER JOIN ".LOCALITY." AS B ON (A.SUBURB_ID = B.SUBURB_ID) WHERE B.LOCALITY_ID = '".$locality."' AND A.CITY_ID = " . $cityid ;
            $data = mysql_query($sql);
            $response = mysql_fetch_assoc($data);
            $json = array($response['SUBURB_ID'], $response['LABEL']);
            header('Content-Type: application/json');
            echo json_encode($json);            
            
        }else {
            $city_id = $_REQUEST["id"];
            $suburb_id = $_REQUEST["suburb_id"];
            if($suburb_id == '')  {
                   if($city_id != '')
                   {
                        $suburbArr = Array();
                        
                        if($city_id == 'othercities'){
                                $group_city_ids = array();
                                foreach($arrOtherCities as $key => $value){
                                        $group_city_ids[] = $key;
                                }
                                $city_id = implode(",",$group_city_ids);
                        }
                        $sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM ".SUBURB." AS A WHERE A.CITY_ID in (" . $city_id . ") ORDER BY A.LABEL ASC";

                        $data = mysql_query($sql);

                        while ($dataArr = mysql_fetch_array($data))
                         {
                                array_push($suburbArr, $dataArr);
                         }
                        echo "<option value=''>Select Suburb</option>";
                        foreach($suburbArr as $val)
                        {
                         echo "<option value=".$val["SUBURB_ID"].">".$val["LABEL"] . "</option>";
                        }
                    }
                   else
                        echo "herer<option value=''>Select Suburb</option>"; 
            }
        if($suburb_id != '')	
        {
           $localityArr = Array();
                   $sql = "SELECT A.LOCALITY_ID, s.SUBURB_ID, s.CITY_ID, A.LABEL 
                       FROM ".LOCALITY." AS A
                       inner join suburb s on A.suburb_id = s.suburb_id
                       inner join city c on s.city_id = c.city_id
                       WHERE
                        c.CITY_ID = " . $city_id;
                   if ($suburb_id != null) {
                   $sql .= " AND A.SUBURB_ID = " . $suburb_id;
                   }

                   $data = mysql_query($sql);
                   while ($dataArr = mysql_fetch_array($data))
                    {
                       array_push($localityArr, $dataArr);
                    }	
                    echo  "<option value=''>Select Locality</option>";  	
                    foreach($localityArr as $val)
                    {
                        echo "<option value=".$val["LOCALITY_ID"].">".$val["LABEL"] . "</option>";
                    }
        }	
 }

?>
