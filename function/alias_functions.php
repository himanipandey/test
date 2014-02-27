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
		$qry = "INSERT INTO ".place_alias_mapping." (place_table_name, place_table_id, alias_name) VALUES ('$pl_tb_name', '$pl_tb_id', '$al_name')";
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

function getAllAliases()
{
	//$where = "pa.place_table_name='$pl_tb_name' and pa.place_table_id='$pl_tb_id'";
	$query = "SELECT c.LABEL as c_label, s.LABEL as s_label, l.LABEL as l_label, pa.alias_name FROM ".place_alias_mapping." pa 
		left join city c on pa.place_table_id = c.CITY_ID and pa.place_table_name = 'CITY'
		left join locality l on pa.place_table_id = l.LOCALITY_ID and pa.place_table_name = 'LOCALITY'
		left join suburb s on pa.place_table_id = s.SUBURB_ID and pa.place_table_name = 'SUBURB'
		"; 
		//echo $query;
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
			inner join locality_near_places l on pl.landmark_id = l.id 
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

		case "locality_near_places":
		$query = "SELECT l.id, l.name FROM ".place_alias_mapping." pa 
			inner join locality_near_places l on pa.alias_table_id = l.id 
			WHERE pa.alias_table_name='locality_near_places' and ".$where;
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
	$query = "INSERT INTO ".place_landmark_mapping." (place_table_name, place_table_id, landmark_id) VALUES ('$pl_tb_name', '$pl_tb_id', '$al_tb_id')";
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
    $arr = Array();  //return array

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
    $arr['data'] = $tmpArray;
    $tmpid = $subid;
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
    	$subArray['data'] = $tmpArray1;
    	$child1Array = Array();
    	
    	$childArray = child_suburb($subid, $lowerSubArr, $locArr);
  		if(count($childArray)>0){
  			$subArray['children'] =  print_suburb($childArray, $lowerSubArr, $locArr);
  			$subArray1 = Array();
  			array_push($subArray1, $subArray);
  			$arr['children'] = $subArray1;
  		}
    
    else{
   	 if(count($suburbSelect)>0){  
   	 	$arr['children'] =  print_suburb($suburbArr, $lowerSubArr, $locArr);
     }
   }
   
  return $arr;
}


function print_suburb($childArray, $lowerSubArr, $locArr){
    global $counter;
    $counter +=1000;
    $returnArr = Array();
    foreach ($childArray as $k => $v) {
     $counter++;
     $tmpArray = Array();
     $tmpArray['id'] = "node".$counter;
    $tmpArray['name'] = $v['label']; 
  	 $tmpArray1 = Array();
  	 if($v['type'] == 'suburb')
   		 $tmpArray1['placeType'] = 'suburb';
	else if ($v['type'] == 'locality')
		$tmpArray1['placeType'] = 'locality';
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