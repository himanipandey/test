<?php
$projectid = $_REQUEST['projectid'];
$proptigerID = $_REQUEST['proptigerID'];

$smarty->assign("projectid", $projectid);
$smarty->assign("proptigerID", $proptigerID);

if ($_POST['btnSave'] == "Save") 
{
	$propertynID=$_POST['projectID'];
	$projectPreID=$_POST['projectID'];

	
	if(isset($_POST['ifnotprojectTypes']))
	{
		if(!empty($_POST['txtProjectTypes']))
		{
				$insertprojectType=trim($_POST['txtProjectTypes']);
				$ptype=explode(",",$insertprojectType);			
				for($i=0;$i<count($ptype);$i++)
				{
					$unitNAME=strtolower($ptype[$i]);
					$typePlan='Apartment';
					$sizetype='';					
					$sql1="INSERT INTO ".CRAWLER_PROJECT_TYPES." SET PROPERTY_ID ='".$projectPreID."', UNIT_NAME='".$unitNAME."', TYPE='".$typePlan."', SIZE='".$sizetype."'";
					$result1=mysql_query($sql1) or die("Stopping here in images".mysql_error());					
				}
		}
    }


	$projectid='';
	$txtProjectName				=	trim($_POST['txtProjectName']);
	$builderId					=	trim($_POST['builderId']);
	$cityId						=	trim($_POST['cityId']);
	$suburbId					=	trim($_POST['suburbId']);
	$localityId					=	trim($_POST['localityId']);
	$txtProjectDescription		=	trim($_POST['txtProjectDescription']);
	$txtAddress					=	trim($_POST['txtProjectAddress']);
	$txtProjectTypes			=	trim($_POST['txtProjectTypes']);
	$txtProjectLocation			=	trim($_POST['txtProjectLocation']);
	$txtProjectLattitude		=	trim($_POST['txtProjectLattitude']);
	$txtProjectLongitude		=	trim($_POST['txtProjectLongitude']);
	$txtProjectMetaTitle		=	trim($_POST['txtProjectMetaTitle']);
	$txtMetaKeywords			=	trim($_POST['txtMetaKeywords']);
	$txtMetaDescription			=	trim($_POST['txtMetaDescription']);
	$DisplayOrder				=	trim($_POST['DisplayOrder']);
	$Active						=	trim($_POST['Active']);
	$Status						=	trim($_POST['Status']);
	$txtProjectURL				=	trim($_POST['txtProjectURL']);
	$Featured					=	trim($_POST['Featured']);
	$Completion					=	trim($_POST['Completion']);
	$txtDisclaimer				=	trim($_POST['txtDisclaimer']);
	$folderId					=	trim($_POST['folderId']);
	$PaymentPlan				=	trim($_POST['PaymentPlan']);
	$no_of_towers				=	trim($_POST['no_of_towers']);
	$no_of_flats				=	trim($_POST['no_of_flats']);
	$eff_date_to				=	trim($_POST['eff_date_to']);
	$display_flag				=	trim($_POST['display_flag']);
	$others						=   trim($_POST["others"]);
	
	/***************Query for suburb selected************/
	if($_POST['cityId'] != '')
	{
		$suburbSelect = Array();
		$sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM SUBURB AS A WHERE A.CITY_ID = " . $_POST['cityId'] . "";
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

		$sql .= " AND A.ACTIVE=1 ";


		$data = mysql_query($sql, $db);

		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localitySelect, $dataArr);
		 }	
	}	 
		 $smarty->assign("localitySelect", $localitySelect);
	/***************end Query for Locality selected************/
	
	
	$smarty->assign("txtProjectName", $txtProjectName);
	$smarty->assign("builderId", $builderId);
	$smarty->assign("cityId", $cityId);
	$smarty->assign("suburbId", $suburbId);
	$smarty->assign("localityId", $localityId);
	$smarty->assign("txtProjectDescription", $txtProjectDescription);
	$smarty->assign("txtAddress", $txtAddress);	
	$smarty->assign("txtProjectTypes", $txtProjectTypes);
	$smarty->assign("txtProjectSmallImg", $txtProjectSmallImg);
	$smarty->assign("txtProjectLocation", $txtProjectLocation);
	$smarty->assign("txtProjectLattitude", $txtProjectLattitude);
	$smarty->assign("txtProjectLongitude", $txtProjectLongitude);
	$smarty->assign("txtProjectMetaTitle", $txtProjectMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("DisplayOrder", $DisplayOrder);
	$smarty->assign("Active", $Active);
	$smarty->assign("Status", $Status);
	$smarty->assign("txtProjectURL", $txtProjectURL);
   $smarty->assign("Featured", $Featured);
	$smarty->assign("Completion", $Completion);
	$smarty->assign("txtDisclaimer", $txtDisclaimer);
	$smarty->assign("PaymentPlan", $PaymentPlan);
	$smarty->assign("display_flag", $display_flag);
	$smarty->assign("no_of_towers", $no_of_towers);
	$smarty->assign("no_of_flats", $no_of_flats);
	$smarty->assign("eff_date_to", $eff_date_to);
	
	
	/***********Folder name**********/
	$qry			=	"SELECT BUILDER_IMAGE,BUILDER_NAME FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$builderId."'";
	$res			=	mysql_query($qry,$db);
	$data			=	mysql_fetch_array($res);
	$folderName	=	explode("/",$data['BUILDER_IMAGE']);

	/********************************/		
	$folder_project	=	str_replace(" ","-",strtolower($txtProjectName));
	$BuilderName		=	$data['BUILDER_NAME'];	

	$qryprojectchk		=	"SELECT PROJECT_NAME FROM ".PROJECT." WHERE PROJECT_NAME = '".$txtProjectName."'";
	$resprojectchk		=	mysql_query($qryprojectchk);
			
	if ($projectid=='')
	 {
	 if(mysql_num_rows($resprojectchk) >0)
	 {
		$ErrorMsg["txtProjectName"] = "Project already exist.";
	}
	if( $builderId == '') 
   {
	 $ErrorMsg["builderId"] = "Please enter Builder name.";
   }
	if( $cityId == '') 
   {
	 $ErrorMsg["cityId"] = "Please select city.";
   }
	if( $suburbId == '') 
   {
	 $ErrorMsg["suburbId"] = "Please select suburb.";
   }
	if( $localityId == '') 
   {
	 $ErrorMsg["localityId"] = "Please select locality.";
   }
	if( $display_flag == '') 
   {
	 $ErrorMsg["display_flag"] = "Please select display flag";
   } 
	   
	} 
	$smarty->assign("ErrorMsg", $ErrorMsg);	
	
	if(is_array($ErrorMsg)) {
		// Do Nothing
	} 
	else if ($projectid == '')
	{	$BuilderFolder		=	$folderName[1];
		$buildFolderPath	=	 OFFLINE_PROJECT_IMAGE_SAVE_PATH.$BuilderFolder;			
			/*********Check if builder folder not exist************/
		if(!is_dir($buildFolderPath))
		mkdir($buildFolderPath, 0777);		
		$createFolder		=	 OFFLINE_PROJECT_IMAGE_SAVE_PATH.$BuilderFolder."/".$folder_project;
		$drr	=	mkdir($createFolder, 0777);			
		if($proptigerID=="")
		{
			InsertOfflineProject($txtProjectName, $txtAddress, $txtProjectDescription,$builderId,$cityId,$suburbId,$localityId,$txtProjectTypes,$img_path,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$Completion,$txtDisclaimer,$BuilderName,$PaymentPlan,$no_of_towers,$no_of_flats,$eff_date_to,$propertynID,$display_flag,$others);
		}
	
			UpdateProertyDetailsAfterInsert($propertynID);
			header("Location:ProjectList.php?page=1&sort=all");
	}

} 
else if($_POST['btnExit'] == "Exit")
{
	  header("Location:ProjectList.php?page=1&sort=all");
}
else if ($projectid!='')
 {		

		$smarty->assign("proptigerID", $proptigerID);
		$ProjectDetail 	= ViewCrawlerProjectDetails($projectid);
		$propertyID=$ProjectDetail['PROJECT_ID'];
		$projectUrl=str_replace('http://www.commonfloor.com','',$ProjectDetail['PROJECT_URL']);
		$builderName=substr($ProjectDetail['BUILDER_NAME'],0,3);		//print_r($ProjectDetail);die("here");
		$smarty->assign("txtProjectId", stripslashes($ProjectDetail['PROJECT_ID']));
		$smarty->assign("txtProjectName", stripslashes($ProjectDetail['PROJECT_NAME']));
		$smarty->assign("txtAddress", stripslashes($ProjectDetail['PROJECT_ADDRESS']));	
		$smarty->assign("txtProjectDescription", stripslashes($ProjectDetail['PROJECT_DESCRIPTION']));
		$smarty->assign("txtProjectName", stripslashes($ProjectDetail['PROJECT_NAME']));
		$smarty->assign("txtAddress", stripslashes($ProjectDetail['PROJECT_ADDRESS']));	
		$smarty->assign("txtProjectDescription", stripslashes($ProjectDetail['PROJECT_DESCRIPTION']));
		$smarty->assign("builderName", stripslashes($ProjectDetail['BUILDER_NAME']));
		$smarty->assign("builderId", stripslashes($ProjectDetail['BUILDER_ID']));
		$smarty->assign("cityId", stripslashes($ProjectDetail['CITY_ID']));	
		$smarty->assign("suburbId", stripslashes($ProjectDetail['SUBURB_ID']));
		$smarty->assign("localityId", stripslashes($ProjectDetail['LOCALITY_ID']));
		$smarty->assign("txtProjectTypes", $unitTypeNew);	
		$smarty->assign("txtProjectSmallImg", stripslashes($ProjectDetail['PROJECT_SMALL_IMAGE']));
		$smarty->assign("BUILDER_NAME", stripslashes($ProjectDetail['BUILDER_NAME']));
		$smarty->assign("txtProjectLocation", stripslashes($ProjectDetail['LOCATION_DESC']));	
		$smarty->assign("txtProjectLattitude", stripslashes($ProjectDetail['LATITUDE']));
		$smarty->assign("txtProjectLongitude", stripslashes($ProjectDetail['LONGITUDE']));
		$smarty->assign("txtProjectMetaTitle", stripslashes($ProjectDetail['META_TITLE']));	
		$smarty->assign("txtMetaKeywords", stripslashes($ProjectDetail['META_KEYWORDS']));
		$smarty->assign("txtMetaDescription", stripslashes($ProjectDetail['META_DESCRIPTION']));
		$smarty->assign("DisplayOrder", stripslashes($ProjectDetail['DISPLAY_ORDER']));	
		$smarty->assign("Active", stripslashes($ProjectDetail['ACTIVE']));
		$smarty->assign("Status", stripslashes($ProjectDetail['PROJECT_STATUS']));
		$smarty->assign("txtProjectURL", '');
		$smarty->assign("txtUrl",$projectUrl);
		$smarty->assign("Featured", stripslashes($ProjectDetail['FEATURED']));
		$completion =	explode(" ",$ProjectDetail['COMPLETION_DATE']);
		$smarty->assign("Completion", stripslashes($completion[0]));
		$smarty->assign("txtDisclaimer", stripslashes($ProjectDetail['PRICE_DISCLAIMER']));
		$smarty->assign("PaymentPlan", stripslashes($ProjectDetail['PAYMENT_PLAN_IMAGE']));
		$smarty->assign("no_of_towers", stripslashes($ProjectDetail['NO_OF_TOWERS']));
		$smarty->assign("no_of_flats", stripslashes($ProjectDetail['NO_OF_FLATES']));
		$smarty->assign("eff_date_to", stripslashes($ProjectDetail['LAUNCH_DATE']));
		$smarty->assign("display_flag", stripslashes($ProjectDetail['DISPLAY_FLAG']));
	 
	 
	 
	 /***************Query for suburb selected************/
		$suburbSelect = Array();
		$sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM SUBURB AS A WHERE A.CITY_ID = " . $ProjectDetail['CITY_ID'] . "";
		$data = mysql_query($sql);

		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($suburbSelect, $dataArr);
		 }
		 $smarty->assign("suburbSelect", $suburbSelect);
		
	/***************end Query for suburb selected************/
	
	/***************Query for Locality selected************/
		$localitySelect = Array();
		$sql = "SELECT A.LOCALITY_ID, A.SUBURB_ID, A.CITY_ID, A.LABEL FROM LOCALITY AS A WHERE A.CITY_ID = " . $ProjectDetail['CITY_ID'];

		if ($ProjectDetail['SUBURB_ID'] != null) {
		$sql .= " AND A.SUBURB_ID = " . $ProjectDetail['SUBURB_ID'];
		}

		$sql .= " AND A.ACTIVE=1 ";

		$data = mysql_query($sql);

		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localitySelect, $dataArr);
		 }	
		 $smarty->assign("localitySelect", $localitySelect);
	/***************end Query for Locality selected************/
	 
 }
 
	 /*****************City Data************/
	 $CityDataArr	=	array();
	 $qry	=	"SELECT CITY_ID,LABEL FROM ".CITY;
	 $res = mysql_query($qry);
	 while($data	=	mysql_fetch_array($res))
	 {
		$CityDataArr[]	=	$data;		
	 }
	 $smarty->assign("CityDataArr", $CityDataArr);
 

 /*****************Builder Data************/


	 $BuilderDataArr = array();
	 $qry	=	"SELECT BUILDER_ID,BUILDER_NAME FROM ".RESI_BUILDER." WHERE BUILDER_NAME LIKE '".$builderName."%' ORDER BY BUILDER_NAME ASC";
	 $res = mysql_query($qry);
	 while($data1	=	mysql_fetch_array($res))
	 {
		$BuilderDataArr1[]	=	$data1;		
	 }

	 $qry2	=	"SELECT BUILDER_ID,BUILDER_NAME FROM ".RESI_BUILDER." WHERE BUILDER_NAME NOT LIKE '".$builderName."%' ORDER BY BUILDER_NAME ASC";
	 $res2 = mysql_query($qry2);
	 while($data2	=	mysql_fetch_array($res2))
	 {
		$BuilderDataArr2[]	=	$data2;		
	 }
	 if($BuilderDataArr1=='')
	 {
		$BuilderDataArr=$BuilderDataArr2;
	 }
	 else
	 {
		$BuilderDataArr=array_merge($BuilderDataArr1,$BuilderDataArr2);
	 }

	$smarty->assign("BuilderDataArr", $BuilderDataArr);
?>
