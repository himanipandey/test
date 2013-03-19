<?php
$bannerid = $_REQUEST['bannerid'];
//echo $_REQUEST['suburb'];die("here");
//print_r($_REQUEST);//die();
//print_r($_FILES);
$smarty->assign("bannerid", $bannerid);


if ($_POST['btnSave'] == "Save")
{
	
	$txtalt				=	trim($_POST['txtalt']);
	$txtBannerUrl		=	trim($_POST['txtBannerUrl']);
	$cityId				=	trim($_POST['cityId']);
	$eff_date_from		=	trim($_POST['eff_date_from']);
	$eff_date_to		=	trim($_POST['eff_date_to']);
	$projectId			=	trim($_POST['projectId']);
	$builderId			=	trim($_POST['builderId']);
	$BannerLoc			=	trim($_POST['BannerLoc']);	
	$imgexist			=	trim($_POST['imgexist']);
	$bannerStatus		=	trim($_POST['bannerStatus']);	
	
	
	
	
	$smarty->assign("txtalt", $txtalt);
	$smarty->assign("txtBannerUrl", $txtBannerUrl);
	$smarty->assign("cityId", $cityId);
	$smarty->assign("eff_date_from", $eff_date_from);
	$smarty->assign("eff_date_to", $eff_date_to);
	$smarty->assign("projectId", $projectId);
	$smarty->assign("builderId", $builderId);	
	$smarty->assign("BannerLoc", $BannerLoc);
	$smarty->assign("imgexist", $imgexist);	
	$smarty->assign("bannerStatus", $bannerStatus);	
	
		
		
	if ($bannerid=='')
	 {
	 if( $txtalt == '') 
	   {
	     $ErrorMsg["txtalt"] = "Please enter Banner Alt Text.";
	   }
	if( $txtBannerUrl == '') 
	   {
	     $ErrorMsg["txtBannerUrl"] = "Please enter Baaner URL.";
	   }   
	if( $cityId == '') 
	   {
	     $ErrorMsg["cityId"] = "Please select City.";
	   }
	if( $eff_date_from == '') 
	   {
	     $ErrorMsg["eff_date_from"] = "Please enter expiry date.";
	   }
	if( $eff_date_to == '') 
	   {
	     $ErrorMsg["eff_date_to"] = "Please enter start date.";
	   }
	if( $BannerLoc == '') 
	   {
	     $ErrorMsg["BannerLoc"] = "Please enter banner location.";
	   }   
	            
   } 
	
	
	if(is_array($ErrorMsg)) {
		// Do Nothing
	} 
	else if ($bannerid == '')
	{		
		if (($_FILES["txtBannerImg"]["type"]))
   	{
   	
  			$name				=	$_FILES["txtBannerImg"]["name"];
			
			$path	=	'../images/banners';
			$return 			=	 move_uploaded_file($_FILES["txtBannerImg"]["tmp_name"], "".$path."/" . $_FILES["txtBannerImg"]["name"]);
			if($return)
			{
				$imgurl		=	"/banners/".$_FILES["txtBannerImg"]["name"];
				
				InsertBanner($txtalt, $txtBannerUrl, $cityId,$eff_date_from,$eff_date_to,$projectId,$builderId,$imgurl,$BannerLoc);
				header("Location:BannerList.php?status=0&mainpg=mainpg");
		
			}	
			else 
			{
				$ErrorMsg['img'] = "Please insert image";
			}
			
		}
		else {
				$ErrorMsg['img'] = "Please select image";
		}	
	}else 
	{
		 if( $txtalt == '') 
	   {
	     $ErrorMsg["txtalt"] = "Please enter Banner Alt Text.";
	   }
	if( $txtBannerUrl == '') 
	   {
	     $ErrorMsg["txtBannerUrl"] = "Please enter Baaner URL.";
	   }   
	if( $cityId == '') 
	   {
	     $ErrorMsg["cityId"] = "Please select City.";
	   }
	if( $eff_date_from == '') 
	   {
	     $ErrorMsg["eff_date_from"] = "Please enter expiry date.";
	   }
	if( $eff_date_to == '') 
	   {
	     $ErrorMsg["eff_date_to"] = "Please enter start date.";
	   }
	if( $BannerLoc == '') 
	   {
	     $ErrorMsg["BannerLoc"] = "Please enter banner location.";
	   }   
	   
		if(is_array($ErrorMsg)) {
		// Do Nothing
		} 
		else {
		if($imgexist != '' && $_FILES["txtBannerImg"]["name"] != '')
		{
			
			if (($_FILES["txtBannerImg"]["type"]))
   		{
   
  			$name				=	$_FILES["txtBannerImg"]["name"];
			
			$path	=	'../images/banners';
			$return 			=	 move_uploaded_file($_FILES["txtBannerImg"]["tmp_name"], "".$path."/" . $_FILES["txtBannerImg"]["name"]);
			if($return)
			{
				$imgurl		=	"/banners/".$_FILES["txtBannerImg"]["name"];
				updateBanner($txtalt, $txtBannerUrl, $cityId,$eff_date_from,$eff_date_to,$projectId,$builderId,$imgurl,$BannerLoc,$bannerid,$bannerStatus);
				header("Location:BannerList.php?status=0&mainpg=mainpg");
		
			}	
			else 
			{
				
					$ErrorMsg['img'] = "Please insert image";
				
			}
			
		}
		else {
					$ErrorMsg['img'] = "Please select image";
				
			}	
		}
		else {
					updateBanner($txtalt, $txtBannerUrl, $cityId,$eff_date_from,$eff_date_to,$projectId,$builderId,$imgexist,$BannerLoc,$bannerid,$bannerStatus);
					header("Location:BannerList.php?status=0&mainpg=mainpg");		
			}
		}	
	}	
	
	 $smarty->assign("ErrorMsg", $ErrorMsg);	
}
else if($_POST['btnExit'] == "Exit")
{
      header("Location:BannerList.php?status=0&mainpg=mainpg");
}
else if ($bannerid!='')
{
 	
 		
    $BannerDetail 	= ViewBannerDetails($bannerid);
	//print_r($ProjectDetail);die("here");
    	$smarty->assign("cityId", stripslashes($BannerDetail['CITY_ID']));
		$smarty->assign("cityId", $cityId);
		$smarty->assign("suburbId", $suburbId);
		$smarty->assign("localityId", $localityId);
	
	
	 
}	

 
 /*****************City Data************/
	$CityDataArr	=	array();
 	
 	$qry	=	"SELECT CITY_ID,LABEL FROM ".CITY." ORDER BY LABEL ASC";
 	$res = mysql_query($qry,$db);
 	
 	while($data	=	mysql_fetch_array($res))
 	{
 		$CityDataArr[]	=	$data;		
 	}
 	$smarty->assign("CityDataArr", $CityDataArr);
 
 
   /***************Query for suburb selected************/
	if($_POST['cityId'] != '')
	{
	$suburbSelect = Array();
	$sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM SUBURB AS A WHERE A.CITY_ID = " . $_POST['cityId'] . " ";

		$data = mysql_query($sql, $db);

		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($suburbSelect, $dataArr);
		 }
		 $smarty->assign("suburbSelect", $suburbSelect);
		
	/***************end Query for suburb selected************/

	/***************Query for Locality selected************/

	$localitySelect = Array();
		$sql = "SELECT A.LOCALITY_ID, A.SUBURB_ID, A.CITY_ID, A.LABEL FROM LOCALITY AS A WHERE A.CITY_ID = " . $_POST['cityId'];

		if ($suburbId != null) {
		$sql .= " AND A.SUBURB_ID = " . $suburbId;
		}


		$data = mysql_query($sql, $db);

		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localitySelect, $dataArr);
		 }	
	}	 
		 $smarty->assign("localitySelect", $localitySelect);
	/***************end Query for Locality selected************/
?>
