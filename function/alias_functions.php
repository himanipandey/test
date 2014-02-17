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


?> 