<?php
		
	$dataArr	=	array();
	if(!isset($_REQUEST['cityId']))
		$_REQUEST['cityId'] = '';
	if(isset($_REQUEST['submit']))	{
		$qry =	"SELECT 
					P.PROJECT_ID,P.PROJECT_NAME,P.BUILDER_NAME 
				  FROM 
					".RESI_PROJECT." P
				  WHERE 
				  	P.CITY_ID = '".$_REQUEST['cityId']."'
				  AND 
				    P.PROJECT_NAME != 'NULL'
				  AND 
				  	P.DISPLAY_FLAG = 1
				  ORDER BY
				    P.DISPLAY_ORDER  ASC";
		$res		=	mysql_query($qry);
		
		while($data1	=	mysql_fetch_array($res,MYSQL_ASSOC)){
			$dataArr[]	=	$data1;
		}
		
		
	}
	$smarty->assign("dataArr",$dataArr);
	$smarty->assign("cityId",$_REQUEST['cityId']);

	$CityDataArr	=	array();
 	$qry	=	"SELECT CITY_ID,LABEL FROM ".CITY." WHERE ACTIVE = 1 ORDER BY LABEL ASC";
 	$res = mysql_query($qry,$db);
 	
 	while($data	=	mysql_fetch_array($res))	{
 		$CityDataArr[]	=	$data;		
 	}
 	$smarty->assign("CityDataArr", $CityDataArr);
 ?>