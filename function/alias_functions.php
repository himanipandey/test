<?php 
$orderBy = "ASC";
function saveAliases($aliasName)
{
	//$return = 3;
	$sql = "SELECT * FROM ".aliases. " where name = '$aliasName'";
	$result=mysql_query($sql);
    
    if(mysql_fetch_array($result) !== false)
	{
		echo "2";
	}
	else
	{
		$qry = "INSERT INTO ".aliases." (name) VALUES ('$aliasName')";
		//echo $qry;
		//echo $aliasName;
		mysql_query($qry);
    	if(mysql_affected_rows()>0)
    	{
        	echo "1";//$return = 1;
    	}
    	else
    	{
        	echo "3";//$return = 3;
    	}
 	}
 	//return $return;
}

function createAliases($pl_tb_name, $pl_tb_id, $al_name)
{
	//$return = 3;

	$sql = "SELECT * FROM ".place_alias_mapping. " where place_table_name = '$pl_tb_name' and place_table_id = '$pl_tb_id' and alias_name='$al_name'";
	$result=mysql_query($sql);
    
    if(mysql_fetch_array($result) !== false)
	{
		echo "2";
	}
	else
	{
		$qry = "INSERT INTO ".place_alias_mapping." (place_table_name, place_table_id, alias_name, created_at, updated_by) VALUES ('$pl_tb_name', '$pl_tb_id', '$al_name', NOW(), ".$_SESSION["adminId"]." )";
		//echo $qry; die();
		//echo $aliasName;
		mysql_query($qry);
    	if(mysql_affected_rows()>0)
    	{
        	echo "1";//$return = 1;
    	}
    	else
    	{
        	echo "3";//$return = 3;
    	}
 	}
 	//return $return;
}

function getAllAliases()
{
	//$where = "pa.place_table_name='$pl_tb_name' and pa.place_table_id='$pl_tb_id'";
	/*$query = "SELECT c.LABEL as c_label, s.LABEL as s_label, s.SUBURB_ID as s_id, s.parent_suburb_id as s_pid, s.CITY_ID as s_cid, c1.LABEL as s_clabel, l.LABEL as l_label, c2.LABEL as l_clabel, pa.alias_name FROM ".place_alias_mapping." pa 	left join city c on pa.place_table_id = c.CITY_ID and pa.place_table_name = 'CITY'
		left join (locality l inner join (suburb s1 inner join city c2 on s1.CITY_ID=c2.CITY_ID) on pa.place_table_id = l.LOCALITY_ID and pa.place_table_name = 'LOCALITY'
		left join (suburb s inner join city c1 on s.CITY_ID=c1.CITY_ID) on pa.place_table_id = s.SUBURB_ID and pa.place_table_name = 'SUBURB'
		"; */
		 $query = "SELECT c.LABEL as c_label, s.LABEL as s_label, s.SUBURB_ID as s_id, s.parent_suburb_id as s_pid, s.CITY_ID as s_cid, c1.LABEL as s_clabel, l.LABEL as l_label, c2.LABEL as l_clabel, pa.alias_name FROM place_alias_mapping pa left join city c on pa.place_table_id = c.CITY_ID and pa.place_table_name = 'CITY' left join (locality l inner join (suburb s1 inner join city c2 on s1.CITY_ID=c2.CITY_ID) on l.SUBURB_ID=s1.SUBURB_ID) on pa.place_table_id = l.LOCALITY_ID and pa.place_table_name = 'LOCALITY' left join (suburb s inner join city c1 on s.CITY_ID=c1.CITY_ID) on pa.place_table_id = s.SUBURB_ID and pa.place_table_name = 'SUBURB'";
	$res = mysql_query($query); //or die(mysql_error());
	
	$arr = array(); //echo 'yes';
     while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        array_push($arr, $data);
    }
    //echo 'yes';
    return $arr;

}

/*
$query = "SELECT c.LABEL, pa.alias_name FROM ".place_alias_mapping." pa 
		inner join city c on pa.place_table_id = c.CITY_ID and pa.place_table_name = 'CITY'"; 
inner join locality l on pa.place_table_id = l.LOCALITY_ID and pa.place_table_name = 'LOCALITY'
inner join suburb s on pa.place_table_id = s.SUBURB_ID and pa.place_table_name = 'SUBURB'

function getAllAliases($pl_tb_name, $pl_tb_id)
{
	$where = "pa.place_table_name='$pl_tb_name' and pa.place_table_id='$pl_tb_id'";
	$query = "SELECT l.id, l.name, a.id, a.name, s.SUBURB_ID, s.LABEL FROM ".place_alias_mapping." pa 
		inner join aliases a on pa.alias_table_id = a.id and pa.alias_table_name = 'aliases'
		inner join locality_near_places l on pa.alias_table_id = l.id and pa.alias_table_name = 'locality_near_places'
		inner join suburb s on pa.alias_table_id = s.SUBURB_ID and pa.alias_table_name = 'suburb'
		WHERE ".$where; 
		echo "----------1".$query;
	$res = mysql_query($query); //or die(mysql_error());
	
	$arr = array(); echo 'yes';
     while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        array_push($arr, $data);
    }
    //echo 'yes';
    return $arr;

}
*/
/*
function getSpecificAliases($pl_tb_name, $pl_tb_id, $al_tb_name)
{
	$where = "pa.place_table_name='$pl_tb_name' and pa.place_table_id='$pl_tb_id'";
	$query ='';
	switch($al_tb_name)
	{
		case "aliases":
		$query = "SELECT a.id, a.name FROM ".place_alias_mapping." pa 
			inner join aliases a on pa.alias_table_id = a.id 
			WHERE pa.alias_table_name='aliases' and ".$where; 
			//echo "----------".$query;
		break;

		case "locality_near_places":
		$query = "SELECT l.id, l.name FROM ".place_alias_mapping." pa 
			inner join locality_near_places l on pa.alias_table_id = l.id 
			WHERE pa.alias_table_name='locality_near_places' and ".$where; 
		break;

		case "suburb":
		$query = "SELECT s.SUBURB_ID, s.LABEL FROM ".place_alias_mapping." pa 
			inner join suburb s on pa.alias_table_id = s.SUBURB_ID
			WHERE pa.alias_table_name='suburb' and ".$where; 
		break;
	}
	//echo "----------".$query;
	$res = mysql_query($query) ;//or die(mysql_error());
	//print_r($res);
	$arr = array();
     while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        //echo $data;
       // print_r($data);
        array_push($arr, $data);
    }
    return $arr;

}
*/

function getLandmarkAliases($pl_tb_name, $pl_tb_id)
{
	$where = "pl.place_table_name='$pl_tb_name' and pl.place_table_id='$pl_tb_id'";
	$query ='';
	
		$query = "SELECT l.id, l.name FROM ".place_landmark_mapping." pl 
			inner join landmarks l on pl.landmark_id = l.id 
			WHERE ".$where; 
		

		
	$res = mysql_query($query) ;//or die(mysql_error());
	//print_r($res);
	$arr = array();
     while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        //echo $data;
       // print_r($data);
        array_push($arr, $data);
    }
    return $arr;

}

function getAliasesbyId($pl_tb_name, $pl_tb_id, $al_tb_name, $al_tb_id)
{
	$where = "pa.place_table_name=$pl_tb_name and pa.place_table_id=$pl_tb_id and pa.alias_table_id=$al_tb_id";

	switch($al_tb_name)
	{
		case "aliases":
		$query = "SELECT a.id, a.name FROM ".place_alias_mapping." pa 
			inner join aliases a on pa.alias_table_id = a.id 
			WHERE pa.alias_table_name='aliases' and ".$where; 
		break;

		case "landmarks":
		$query = "SELECT l.id, l.name FROM ".place_alias_mapping." pa 
			inner join landmarks l on pa.alias_table_id = l.id 
			WHERE pa.alias_table_name='landmarks' and ".$where;
		break;

		case "suburb":
		$query = "SELECT s.SUBURB_ID, s.LABEL FROM ".place_alias_mapping." pa 
			inner join suburb s on pa.alias_table_id = s.id 
			WHERE pa.alias_table_name='suburb' and ".$where;
		break;
	}
	$res = mysql_query($query) or die(mysql_error());
	$arr = array();
     while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        array_push($arr, $data);
    }
    return $arr;
}



function attachAliases($pl_tb_name, $pl_tb_id, $al_tb_id)
{

	$sql = "SELECT * FROM ".place_landmark_mapping. " where place_table_name = '$pl_tb_name' and place_table_id = '$pl_tb_id'  and landmark_id='$al_tb_id'";
	$result=mysql_query($sql);
    
    if(mysql_fetch_array($result) !== false)
	{
		echo "2";
	}
	else
	{	
	$query = "INSERT INTO ".place_landmark_mapping." (place_table_name, place_table_id, landmark_id, created_at, updated_by) VALUES ('$pl_tb_name', '$pl_tb_id', '$al_tb_id', NOW(), ".$_SESSION['adminId'].")";
	//echo $query;
	$res = mysql_query($query) or die(mysql_error());
	if(mysql_affected_rows()>0){
        echo "1";
    }
    else{
        echo "3";
    }
	}

}


function dettachAliases($pl_tb_name, $pl_tb_id, $al_tb_id)
{

	$sql = "DELETE FROM ".place_landmark_mapping. " where place_table_name = '$pl_tb_name' and place_table_id = '$pl_tb_id' and landmark_id='$al_tb_id'";
	$result=mysql_query($sql);
    
   if(mysql_affected_rows()>0){
        echo "1";
    }
    else{
        echo "3";
    }
	

}


function getHierArr($cid, $subarr1){
	$suburbArr = Array();   // only first level suburbs
    $lowerSubArr = Array();    // only second and third level suburbs
    $parent_id = 0;
    $counter = 0;
    $loc_counter=0;
    $node_max = 1000;

    $arr = Array(); 
    $return_arr = Array(); //return array

	$cityArray = getAllCities();
	$suburbSelect = Array();
    $QueryMember = "SELECT SUBURB_ID as id, LABEL as label, parent_suburb_id as parent_id FROM ".SUBURB." WHERE 
            CITY_ID ='".$cid ."'  ORDER BY LABEL ASC";

    $QueryExecute   = mysql_query($QueryMember) or die(mysql_error());
    while ($dataArr = mysql_fetch_array($QueryExecute))
    {
           array_push($suburbSelect, $dataArr);
    }

    //get all localities in the city
    $locArr = Array();
    $qry = "SELECT a.LOCALITY_ID as id, a.LABEL as label, a.SUBURB_ID as parent_id FROM " . locality . " a
              inner join suburb b
                on a.SUBURB_ID = b.SUBURB_ID
              inner join city c
                on b.CITY_ID = c.CITY_ID
             WHERE 
                c.CITY_ID = '" . $cid . "' 
            ORDER BY a.LABEL ASC";
    $res = mysql_query($qry);
    while ($dataArr = mysql_fetch_array($res))
    {
           array_push($locArr, $dataArr);
    }

    foreach ($suburbSelect as $k => $v) {     
        
        if($v['parent_id']>0){
           array_push($lowerSubArr, $v);
           
        }
        else{    
        	$v['type'] = 'suburb';        
            array_push($suburbArr, $v);
        }  
               
    }

 
    foreach ($cityArray as $k1 => $v1) {       

        if ($v1['CITY_ID']==$cid) {
            $cityName = $v1['LABEL'];
        }
        
    }

    $arr['id'] = 'node02';
    $arr['name'] = $cityName; 
    
    $tmpArray = Array();
    $tmpArray['placeType'] = 'city';
    $aliasArray = getLandmarkAliases('city', $cid);
    $aliasString = '';
    if(count($aliasArray)<1)$aliasString = 'No Landmarks tagged';
    else{
      foreach ($aliasArray as $k => $v) {
       $aliasString .= $v['name'].", ";
      }
      //echo $aliasString;
      $aliasString = rtrim(trim($aliasString), ",");
      //echo rtrim($aliasString, ",");
    }
    
    $tmpArray['alias'] = $aliasString;
    $arr['data'] = $tmpArray;

    if(isset($subarr1)){
    
    $bool = true;
    $subid = $subarr1['id'];
    $subname = $subarr1['label'];
    $parentid = $subarr1['parent_id'];
    	
    	while($bool){
       
    		$bool1 = true;
    		foreach ($suburbSelect as $k => $v) {  
        // echo $v['id'];
      		    if($v['id']==$parentid){
          		 $subid = $v['id'];
               $subname = $v['label'];
               $parentid = $v['parent_id'];
          		 $bool1 = false;
       			 }

  		    }
  		    if($bool1) $bool = false;

    	}	

    	$subArray = Array();
    	 $subArray['id'] = 'node1001';
    	  global $counter;
    	 $counter +=1000;
   		 $subArray['name'] = $subname;
   		   $tmpArray1 = Array();
    	$tmpArray1['placeType'] = 'suburb';
      $aliasArray = getLandmarkAliases('suburb', $subid);

    $aliasString = '';
    if(count($aliasArray)<1)$aliasString = 'No Landmarks tagged';
      else{
      foreach ($aliasArray as $k => $v) {
       $aliasString .= $v['name'].", ";
      }
      $aliasString = rtrim(trim($aliasString), ",");
      }
      $tmpArray1['alias'] = $aliasString;
    	$subArray['data'] = $tmpArray1;
    	$child1Array = Array();
    	
    	$childArray = child_suburb($subid, $lowerSubArr, $locArr);
  		if(count($childArray)>0){
  			$subArray['children'] =  print_suburb($childArray, $lowerSubArr, $locArr);
  			 			
  		}
      $finsubArray = Array();
      array_push($finsubArray, $subArray);
      $arr['children'] = $finsubArray;
    }
    else{
   	 if(count($suburbSelect)>0){  
   	 	$arr['children'] =  print_suburb($suburbArr, $lowerSubArr, $locArr);
     }
   }
   //echo $GLOBALS['loc_counter'];
   array_push($return_arr, $arr);
   //echo $GLOBAL['node_max'];
   array_push($return_arr, $GLOBALS['loc_counter']);
   array_push($return_arr, (int)($GLOBALS['node_max']/1000));
  return $return_arr;
}


function print_suburb($childArray, $lowerSubArr, $locArr){
    global $counter;
    
    $counter +=1000;
    if($GLOBALS['node_max']<$counter) $GLOBALS['node_max'] = $counter;
    global $loc_counter;
    $returnArr = Array();
    foreach ($childArray as $k => $v) {
     $counter++;
     $tmpArray = Array();
     $tmpArray['id'] = "node".$counter;
    $tmpArray['name'] = $v['label']; 
  	 $tmpArray1 = Array();
  	 if($v['type'] == 'suburb'){
   		 $tmpArray1['placeType'] = 'suburb';
       $aliasArray = getLandmarkAliases('suburb', $v['id']);
    $aliasString = '';
    if(count($aliasArray)<1)$aliasString = 'No Landmarks tagged';
    else {
      foreach ($aliasArray as $k => $v) {
       $aliasString .= $v['name'].", ";
      }   
      $aliasString = rtrim(trim($aliasString), ",");
    }
      $tmpArray1['alias'] = $aliasString;
    }
	else if ($v['type'] == 'locality'){
		$tmpArray1['placeType'] = 'locality';
    $aliasArray = getLandmarkAliases('locality', $v['id']);
    $aliasString = '';
    if(count($aliasArray)<1)$aliasString = 'No Landmarks tagged';
    else{
      foreach ($aliasArray as $k => $v) {
       $aliasString .= $v['name'].", ";
      }
    
    $aliasString = rtrim(trim($aliasString), ",");
    }
    $tmpArray1['alias'] = $aliasString;
    $GLOBALS['loc_counter']++;
    //echo $loc_counter;
  }
    $tmpArray['data'] = $tmpArray1;
  //echo $v['label'];
  $child1Array = Array();
  $child1Array1 = Array();
  $tmpArray['children'] = Array();
  $child1Array = child_suburb($v['id'], $lowerSubArr, $locArr);
  if(count($child1Array)>0)
  $tmpArray['children'] =  print_suburb($child1Array, $lowerSubArr, $locArr);
 /* 
  if($type == 'suburb'){
  if(count($child1Array)>0){   
  //echo "hi:".$v['label']; 
   array_push($tmpArray['children'], print_suburb($child1Array, $lowerSubArr, $locArr, 'suburb'));
   // $tmpArray['children'] =  print_suburb($child1Array, $lowerSubArr, $locArr, 'suburb');
    
  }
  $child1Array1 = child_suburb($v['id'], $locArr);
  if(count($child1Array1)>0){ 
  //echo "10";   
   array_push($tmpArray['children'], print_suburb($child1Array1, $lowerSubArr, $locArr, 'locality'));
    //$tmpArray['children'] =  print_suburb($child1Array1, $lowerSubArr, $locArr, 'locality');
    }
  }
  
   */
   array_push($returnArr, $tmpArray);
 }
 $counter -=1000;
 //echo json_encode($returnArr);
 return $returnArr;
}




function child_suburb($p_id, $arr1, $arr2){
    $returnArr = Array();
    
    foreach ($arr1 as $k1 => $v1) {       
        
    
        if ($v1['parent_id']==$p_id) {
            //echo $v1['label'];
            
            $v1['type'] = 'suburb';
            array_push($returnArr, $v1);
        }
        
    }

    foreach ($arr2 as $k1 => $v1) {       
        
    
        if ($v1['parent_id']==$p_id) {
            //echo $v1['label'];
            
            $v1['type'] = 'locality';
            array_push($returnArr, $v1);
        }
        
    }
    //print_r($returnArr);
    return $returnArr;
 }

?> 