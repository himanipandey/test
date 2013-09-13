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
?>
     
    <tr class="latLong">
       <td width="20%" align="right">Max Latitude  : </td>
       <td width="30%" align="left" >
           <?php echo max($arrLatitude); ?>
           <input type = "hidden" name ="maxLatitude" value="<?php echo max($arrLatitude); ?>">
      </td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>

     <tr class="latLong">
       <td width="20%" align="right">Min Latitude  : </td>
       <td width="30%" align="left" >
           <?php echo min($arrLatitude); ?>
           <input type = "hidden" name ="minLatitude" value="<?php echo min($arrLatitude); ?>">
      </td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>

     <tr class="latLong">
       <td width="20%" align="right">Max Longitude  : </td>
       <td width="30%" align="left" >
           <?php echo max($arrLongitude); ?>
           <input type = "hidden" name ="maxLongitude" value="<?php echo max($arrLongitude); ?>">
      </td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>

      <tr class="latLong">
       <td width="20%" align="right">Min Longitude  : </td>
       <td width="30%" align="left" >
           <?php echo min($arrLongitude); ?>
           <input type = "hidden" name ="minLongitude" value="<?php echo min($arrLongitude); ?>">
      </td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>
<?php
        }
    }
    else {        
        ?>
        <tr class="latLong">
       <td width="20%" align="right">Max Latitude  : </td>
       <td width="30%" align="left" >No Entry</td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>

     <tr class="latLong">
       <td width="20%" align="right">Min Latitude  : </td>
       <td width="30%" align="left" >No Entry</td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>

     <tr class="latLong">
       <td width="20%" align="right">Max Longitude  : </td>
       <td width="30%" align="left" >No Entry</td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>

      <tr class="latLong">
       <td width="20%" align="right">Min Longitude  : </td>
       <td width="30%" align="left" >No Entry</td>				   
      <td width="50%" align="left">&nbsp;</td>
     </tr>
     <?php 
        }
     
  ?>
