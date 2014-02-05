<?php 
$orderBy = "ASC";
function saveAliases($aliasName)
{
	$return = 3;
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

?> 