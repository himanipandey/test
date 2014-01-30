<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("../../dbConfig.php");
	include("../includes/configs/configs.php");

		$proArr		=	explode('-',$_REQUEST["project_id"]);
		$project_id	=$proArr[0];
		$proptigerID=$proArr[1];
		$smarty->assign("project_id", $project_id);
		$smarty->assign("proptigerID", $proptigerID);
		$countPropImages=0;
		$newImagesArr=array();
		if($proptigerID!=0)
		{
			$Sql = "SELECT OFFLINE_TYPE FROM ".PROJECT." WHERE PROJECT_ID ='".$proptigerID."'";
			$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function ViewprojectDetails()');
			if(mysql_num_rows($ExecSql)>=1)
			{
				$dataPropTigerListingArr = mysql_fetch_array($ExecSql);
				$offlineType=$dataPropTigerListingArr['OFFLINE_TYPE'];

				if($offlineType=='2')
				{
					
					$SqlProptigerImages="Select PPI.PROJECT_PLAN_ID,PPI.PLAN_IMAGE,PPI.PLAN_TYPE,PPI.TITLE FROM ".PROJECT_PLAN_IMAGES." AS PPI WHERE PPI.STATUS='1' AND PPI.PROJECT_ID='".$proptigerID."'";
					$ExecSql = mysql_query($SqlProptigerImages) or die(mysql_error());

						while ($dataProptigerImagesArr = mysql_fetch_array($ExecSql))
						{
							$ProptigerImagesDataListingArr [] = $dataProptigerImagesArr;
						}	

						$countPropImages=count($ProptigerImagesDataListingArr);


							$SqlProptigerFlooorImages="Select PI.FLOOR_PLAN_ID,PI.NAME,PI.IMAGE_URL FROM ".FLOOR_PLANS." AS PI WHERE PI.TYPE_ID IN (Select TYPE_ID FROM ".PROJECT_TYPES." WHERE PROJECT_ID='".$proptigerID."')";
							$ExecSql2 = mysql_query($SqlProptigerFlooorImages) or die(mysql_error());
								while ($dataProptigerFloorImagesArr = mysql_fetch_array($ExecSql2))
								{
									$ProptigerFloorImagesDataListingArr [] = $dataProptigerFloorImagesArr;
								}	
							$countPropImages+=count($ProptigerFloorImagesDataListingArr);
								array_push($newImagesArr,$ProptigerImagesDataListingArr,$ProptigerFloorImagesDataListingArr);						
				}

				
			}

		}

		
	
	// $suburb_id	   =	$_REQUEST["suburb_id"];die("here");
	if($project_id != '')
	{
	
 


	 $sqlListingImages = "SELECT L.FOLDER_NAME, L.ID AS PROPERTY_ID, L.NEW_IMAGE_NAME AS PROPERTY_IMAGE FROM ".CRAWLER_PROJECT." AS L WHERE  L.ID = " . $project_id. "";

	  $data = mysql_query($sqlListingImages);
	
		while ($dataListingArr = mysql_fetch_array($data))
		 {
			$ImageDataListingArr [] = $dataListingArr;
		 }	

		 	 $smarty->assign("ImageDataListingArr", $ImageDataListingArr);
		 	
   	
   

 $sqlFloorImages = "SELECT L.FOLDER_NAME ,FI.ID, FI.NEW_IMAGE_NAME AS FLOOR_IMAGE,FI.PLAN_TYPE,FI.ID AS IMAGE_ID FROM ".CRAWLER_PROJECT." AS L, ".CRAWLER_PROJECT_FLOOR_IMAGES." AS FI WHERE   L.ID=FI.PROPERTY_ID AND FI.PROPERTY_ID = " . $project_id. "";

	  $data = mysql_query($sqlFloorImages);
	
		while ($dataFloorArr = mysql_fetch_array($data))
		 {
			$ImageDataFloorArr [] = $dataFloorArr;
		 }	

		 	 $smarty->assign("ImageDataFloorArr", $ImageDataFloorArr);
		 	
   	


 $sqlLocationImages = "SELECT L.FOLDER_NAME, LI.ID, LI.NEW_IMAGE_NAME AS LOCATION_IMAGE,LI.ID AS LOCATION_ID FROM ".CRAWLER_PROJECT." AS L, ".CRAWLER_LOCATION_MAP_IMAGES." AS LI  WHERE L.ID=LI.PROPERTY_ID AND LI.PROPERTY_ID = " . $project_id. "";

	  $data = mysql_query($sqlLocationImages);
	
		while ($dataLocationArr = mysql_fetch_array($data))
		 {
			$ImageDataLocationArr [] = $dataLocationArr;
		 }	

		 	 $smarty->assign("ImageDataLocationArr", $ImageDataLocationArr);
			
			  $smarty->assign("newImagesArr", $newImagesArr);
		 	
   	
    }
 /*echo "----------------------------LOCATION---------------------------";
	echo "<pre>";
	print_r($ImageDataLocationArr);

	echo "<br>";
	 echo "----------------------------------FLOOR---------------------";
	echo "<pre>";
	print_r($ImageDataFloorArr);

	echo "<br>";
	 echo "-------------------------------------------------------LISTTING";
	echo "<pre>";
	print_r($ImageDataListingArr);

	echo "<br>";

	
*/  $count=count($ImageDataLocationArr);
	$count+=count($ImageDataFloorArr);
	$count+=count($ImageDataListingArr);

	
	 $smarty->assign("countPropImages", $countPropImages);
	 $smarty->assign("count", $count);

	  $Project	=	array();
	  
  $qry	=	"SELECT B.BUILDER_NAME,A.TYPE_ID,B.PROJECT_NAME,A.UNIT_NAME,A.UNIT_TYPE,A.SIZE,A.MEASURE,A.PRICE_PER_UNIT_AREA,B.PROJECT_ID FROM ".PROJECT_TYPES." AS A ,".PROJECT." AS B WHERE A.PROJECT_ID = B.PROJECT_ID AND A.PROJECT_ID='".$proptigerID."' ORDER BY B.BUILDER_NAME ASC";
 	$res	=	mysql_query($qry);
 
 		while ($dataArr = mysql_fetch_array($res))
		 {
			array_push($Project, $dataArr);
		 }

		
		 $smarty->assign("Project", $Project);
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."loadimages.tpl");
   	
?>