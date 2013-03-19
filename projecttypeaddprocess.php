<?php

set_time_limit(0);
ini_set("memory_limit","32M");
 $projectid_type = $_REQUEST['projectid_type'];
$smarty->assign("projectid_type", $projectid_type);
//print_r($_REQUEST);//die('here');


if ($_POST['btnSave'] == "Save")
{


	/*************Add new project type if projectid is blank*********************************/

			$flgins	=	0;

		//$ErrorMsg[]  = array();
		$projectId	=	$_REQUEST['projectId'];
		$apartmentType	=	$_REQUEST['apartmentType'];
		$measure	=	$_REQUEST['measure'];
		
		if($projectId	== '')
		{
			$projecteror	=	"Please select project";    
		}

		foreach($_REQUEST['txtUnitName'] AS $key=>$val)
		{
			
			if($val != '')
				$flgins	=	1;	
			if($_REQUEST['txtUnitName'][$key] != '')
			{
				//$projectId					=	$val;
				$txtUnitName				=	$_REQUEST['txtUnitName'][$key];
				$txtSize					=	$_REQUEST['txtSize'][$key];
				$txtPricePerUnitArea		=	$_REQUEST['txtPricePerUnitArea'][$key];
				$txtPricePerUnitAreaDp		=	$_REQUEST['txtPricePerUnitAreaDp'][$key];
				$txtPricePerUnitAreaFp		=	$_REQUEST['txtPricePerUnitAreaFp'][$key];
				$bed						=	$_REQUEST['bed'][$key];
				$CLPV						=	$_REQUEST["CLPV_$key"];
				$DPV						=	$_REQUEST["DPV_$key"];
				$FPV						=	$_REQUEST["FPV_$key"];
				$CLPD						=	$_REQUEST["CLPD_$key"];
				$DPD						=	$_REQUEST["DPD_$key"];
				$FPD						=	$_REQUEST["FPD_$key"];
				$bathrooms					=	$_REQUEST['bathrooms'][$key];
				$status						=	$_REQUEST['status'][$key];
				$pid[]						=	trim($txtUnitName);
				$txtUnitNameval[]			=	trim($txtUnitName);
				$txtSizeval[]				=	trim($txtSize);
				$txtPricePerUnitAreaval[]	=	trim($txtPricePerUnitArea);
				$txtPricePerUnitAreaDpval[]	=	trim($txtPricePerUnitAreaDp);
				$txtPricePerUnitAreaFpval[]	=	trim($txtPricePerUnitAreaFp);
				$bedval[]					=	$bed;
				$CLPVval[]					=	$CLPV;
				$DPVval[]					=	$DPV;
				$FPVval[]					=	$FPV;
				$CLPDval[]					=	$CLPD;
				$DPDval[]					=	$DPD;
				$FPDval[]					=	$FPD;
				$bathroomsval[]				=	$bathrooms;
				$statusval[]				=	$status;

				
				$smarty->assign("pid", $pid);
				
				$smarty->assign("txtUnitNameval", $txtUnitNameval);	
				$smarty->assign("txtSizeval", $txtSizeval);	
				$smarty->assign("txtPricePerUnitAreaval", $txtPricePerUnitAreaval);	
				$smarty->assign("txtPricePerUnitAreaDpval", $txtPricePerUnitAreaDpval);	
				$smarty->assign("txtPricePerUnitAreaFpval", $txtPricePerUnitAreaFpval);	
				$smarty->assign("bedval", $bedval);	
				$smarty->assign("CLPVval", $CLPVval);	
				$smarty->assign("DPVval", $DPVval);	
				$smarty->assign("FPVval", $FPVval);	
				$smarty->assign("CLPDval", $CLPDval);	
				$smarty->assign("DPDval",$DPDval);	
				$smarty->assign("FPDval",$FPDval);	
				$smarty->assign("bathroomsval",$bathroomsval);	
				$smarty->assign("statusval",$statusval);	


				/*if(trim($txtUnitName) == '')
				{
					$ErrorMsg[$key]				.=	"<br>Please enter unit name";
				}*/
			

				if(!is_array($ErrorMsg))
				{

					$insertlist.=	 "(NULL, '$projectId', '$txtUnitName', '$apartmentType', '$txtSize', '$measure', '$txtPricePerUnitArea', '$txtPricePerUnitAreaDp', '$txtPricePerUnitAreaFp','$status',  '$bed', '$CLPV', '$DPV', '$FPV', '$CLPD', '$DPD', '$FPD', now(), '$bathrooms')   ,   ";
					
				}

							
			}
		}
		$exp	=	explode("   ,   ",$insertlist);
		
		$contqry	=	 count($exp);
		if($contqry == 2)
		{
			$qrylast	=	$exp[0];

		}
		else
		{
			$i = 0;
			//print_r($exp);
			foreach($exp as $val)
			{
			
				if($i == 0)
				{
					$qrylast	.=	$val;
				}
				else if ($i == (count($exp)-1))
				{
					$qrylast	.=	'';
				}
				else
				{
					$qrylast	.=	','.$val;
				}
				$i++;
				
			}
			
			$smarty->assign("projectId", $projectId);
		}
		
		

		if(!is_array($ErrorMsg) && $ErrorMsg1 == '')
		{
			
			$returnval = InsertProjectType($qrylast,$projectId);

			if($returnval)
			{
				header("Location:ProjectTypeList.php?page=1&sort=all");
			}
		}
		
	
	
}

if(isset($_POST['btnExit']))
{
	header("Location:ProjectTypeList.php?page=1&sort=all");
}


  $smarty->assign("ErrorMsg1", $ErrorMsg1);
  $smarty->assign("projecteror", $projecteror);
if ($_REQUEST['projectid_type'] != '' AND $_REQUEST['txtUnitName'][0] == '')
{
	/**********************Query for select values according project type for update**********************/
	 $qry	=	"SELECT ID,PROPERTY_ID,UNIT_NAME,TYPE,SIZE,MEASURE,PRICE FROM  ".CRAWLER_PROJECT_TYPES." WHERE PROPERTY_ID = '".$projectid_type."'";
		$res	=	mysql_query($qry);
		while($data	=	mysql_fetch_array($res))
		{
			
			$size=$data['SIZE'];

			if(!empty($data['PRICE']))
			{
				
				if(strstr($data['PRICE'],'Lakhs'))
				{
					$priceArray=explode('Lakhs',$data['PRICE']);
					$price=trim(str_replace("Lakhs",'',$priceArray[0]));
					$price=intval(($price*100000)/$size);
				}
				elseif(strstr($data['PRICE'],'Crores'))
				{
					$priceArray=explode('Crores',$data['PRICE']);
					$price=trim(str_replace("Crores",'',$priceArray[0]));
					$price=intval(($price*10000000)/$size);

				}

			}
			else
			{
					echo $price='';
			}

			
		$qryProptigerID	=	"SELECT PROPTIGER_PROJECT_ID FROM  ".CRAWLER_PROJECT." WHERE ID = '".$projectid_type."'";
		$resProptigerID	=	mysql_query($qryProptigerID);
		
		$dataresProptigerID	=	mysql_fetch_array($resProptigerID);
			
		
			$projectId						=	$dataresProptigerID['PROPTIGER_PROJECT_ID'];
			$TYPE_IDedit[]					=	$data['ID'];
			$txtUnitNameval[]				=	strtoupper ($data['UNIT_NAME']);
			$txtSizeval[]					=	strtolower($data['SIZE']);
			$txtPricePerUnitAreaval[]		=	$price;
			$txtPricePerUnitAreaDpval[]		=	'';
			$txtPricePerUnitAreaFpval[]		=	'';
			$bedval[]						=	'';
			$DPVval[]						=	'';
			$FPVval[]						=	'';
			$CLPDval[]						=	'';
			$DPDval[]						=	'';
			$FPDval[]						=	'';
			$bathroomsval[]					=	'';
			$statusval[]					=	1;
			$type							=  $data['TYPE'];
			$measure						=  strtolower($data['MEASURE']);
		}
		
	
		$smarty->assign("projectId",$projectId);
		$smarty->assign("txtUnitNameval",$txtUnitNameval);
		$smarty->assign("txtSizeval",$txtSizeval);
		$smarty->assign("txtPricePerUnitAreaval",$txtPricePerUnitAreaval);
		$smarty->assign("txtPricePerUnitAreaDpval",$txtPricePerUnitAreaDpval);
		$smarty->assign("txtPricePerUnitAreaFpval",$txtPricePerUnitAreaFpval);
		$smarty->assign("bedval",$bedval);
		$smarty->assign("DPVval",$DPVval);
		$smarty->assign("FPVval",$FPVval);
		$smarty->assign("CLPDval",$CLPDval);
		$smarty->assign("DPDval",$DPDval);
		$smarty->assign("FPDval",$FPDval);
		$smarty->assign("bathroomsval",$bathroomsval);
		$smarty->assign("statusval",$statusval);
		$smarty->assign("TYPE_ID",$TYPE_IDedit);
		$smarty->assign("TYPE",$type);
		$smarty->assign("MEASURE",$measure);

		/***************query for project name display if edit********************/
		
		 $qrypname		=	"SELECT PROPERTY_NAME ,BUILDER_NAME FROM ".CRAWLER_PROJECT." WHERE ID = '".$projectId."'";
		$respname		=	mysql_query($qrypname);
		$dataArrpname	=	mysql_fetch_array($respname);
		$smarty->assign("ProjectName", $dataArrpname['PROPERTY_NAME']);
		$smarty->assign("BuilderName", $dataArrpname['BUILDER_NAME']);



}
 /***************Project dropdown*************/
	 $Project	=	array();
 	 $qry		=	"SELECT PROPTIGER_PROJECT_ID,PROPERTY_NAME,BUILDER_NAME FROM ".CRAWLER_PROJECT." ORDER BY BUILDER_NAME ASC";
 	$res		=	mysql_query($qry);
 	
 		while ($dataArr = mysql_fetch_array($res))
		 {
			array_push($Project, $dataArr);
		 }
		 $smarty->assign("Project", $Project);
?>
