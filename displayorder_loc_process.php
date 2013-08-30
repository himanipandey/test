<?php
		
		if(isset($_REQUEST['submit']))	{

			$cityid		=	$_REQUEST['cityId'];
			$qrybid		=	"SELECT LOCALITY_ID,LABEL FROM locality WHERE CITY_ID = '".$cityid."' AND AND VISIBLE_IN_CMS = '1' ORDER BY LABEL ASC";
			$resbid		=	mysql_query($qrybid) or die(mysql_error()."Error in builder select query");
			$arr = array();
			while($data = mysql_fetch_assoc($resbid)) {
				$arr[]	 = $data;
			}
			$smarty->assign("arr",$arr);
			
			if($_REQUEST['locId'] == '')
				$_REQUEST['locId'] = $_REQUEST['locality_id'];
				$smarty->assign("locId",$_REQUEST['locId']);
		
			$qry =	"SELECT P.PROJECT_ID,P.PROJECT_NAME,P.BUILDER_NAME
			FROM resi_project AS P LEFT JOIN locality L ON (P.LOCALITY_ID = L.LOCALITY_ID) WHERE P.CITY_ID = '".$cityid."' AND P.LOCALITY_ID = '".$_REQUEST['locId']."' AND P.PROJECT_NAME != 'NULL' AND P.ACTIVE= 1 AND P.DISPLAY_FLAG = 1 ORDER BY P.DISPLAY_ORDER_LOCALITY ASC";
			$res		=	mysql_query($qry) or die(mysql_error());
			
			while($data1	=	mysql_fetch_array($res)){
				$dataArr[]	=	$data1;
			}
			$smarty->assign("dataArr",$dataArr);
			$smarty->assign("cityId",$_REQUEST['cityId']);
		}

		/********************************************************/

	/*****************City Data************/
	$CityDataArr	=	array();
 	
 	$qry	=	"SELECT CITY_ID,LABEL FROM ".CITY." WHERE ACTIVE = 1 ORDER BY LABEL ASC";
 	$res = mysql_query($qry,$db);
 	
 	while($data	=	mysql_fetch_array($res))	{
 		$CityDataArr[]	=	$data;		
 	}
 	$smarty->assign("CityDataArr", $CityDataArr);


 
 
  
?>