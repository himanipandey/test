<?php
		include("smartyConfig.php");
		include("appWideConfig.php");
		include("dbConfig.php");
		include("includes/configs/configs.php");
		include("builder_function.php");

	$str	='';
	$rowId=$_REQUEST['d'];

	$txtVillaPlotArea			=	'0';
	$txtVillaFloors				=	'0';
	$txtVillaTerraceArea		=	'0';
	$txtVillaGardenArea			=	'0';

	$projectId=$_REQUEST['projectId'];
	$balconys=$_REQUEST['Balconys'];
	$bathrooms=$_REQUEST['bathrooms'];
	$bed=$_REQUEST['bed'];
	$poojarooms=$_REQUEST['poojarooms'];
	$servantrooms=$_REQUEST['servantrooms'];
	$studyrooms=$_REQUEST['studyrooms'];
	$terraces=$_REQUEST['terraces'];
	$txtPricePerUnitArea=$_REQUEST['txtPricePerUnitArea'];
	$txtPricePerUnitAreaDp=$_REQUEST['txtPricePerUnitAreaDp'];
	$txtPricePerUnitHigh=$_REQUEST['txtPricePerUnitHigh'];
	$txtPricePerUnitLow=$_REQUEST['txtPricePerUnitLow'];

	$txtPlotArea = $_REQUEST['txtPlotArea'];
    $txtSizeLen  = $_REQUEST['txtSizeLen'];
    $txtSizeBre  = $_REQUEST['txtSizeBre'];

	$txtVillaPlotArea=$_REQUEST['txtVillaPlotArea'];
	$txtVillaFloors=$_REQUEST['txtVillaFloors'];
	$txtVillaTerraceArea=$_REQUEST['txtVillaTerraceArea'];
	$txtVillaGardenArea=$_REQUEST['txtVillaGardenArea'];
	$unitType=$_REQUEST['unitType'];
	$txtSize=$_REQUEST['txtSize'];
	$typeid_edit=$_REQUEST['typeid_edit'];
	$txtUnitName=$_REQUEST['txtUnitName'];

	$txtUnitName=str_replace("@","+",$txtUnitName);
	$status='Available';
	$optionId = '';
	if($typeid_edit == '')
	{
		$sql	=	"INSERT INTO  ".PROJECT_OPTIONS."
						SET
							`PROJECT_ID`				=	'".$projectId."',
							`UNIT_NAME`					=	'".$txtUnitName."',
							`UNIT_TYPE`					=	'".$unitType."',
							`SIZE`						=	'".$txtSize."',
							`MEASURE`					=	'sq ft',
							`PRICE_PER_UNIT_AREA`		=	'".$txtPricePerUnitArea."',
							`PRICE_PER_UNIT_AREA_DP`	=	'".$txtPricePerUnitAreaDp."',
							`PRICE_PER_UNIT_LOW`		=	'".$txtPricePerUnitLow."',
							`PRICE_PER_UNIT_HIGH`		=	'".$txtPricePerUnitHigh."',
							`VILLA_PLOT_AREA`			=	'".$txtVillaPlotArea."',
							`VILLA_NO_FLOORS`			=	'".$txtVillaFloors."',
							`VILLA_TERRACE_AREA`		=	'".$txtVillaTerraceArea."',
							`VILLA_GARDEN_AREA`			=	'".$txtVillaGardenArea."',
							`BEDROOMS`					=	'".$bed."',
							`BATHROOMS`					=	'".$bathrooms."',
							`STUDY_ROOM`				=	'".$studyrooms."',
							`SERVANT_ROOM`				=	'".$servantrooms."',
							`BALCONY`					=	'".$balconys."',
							`POOJA_ROOM`				=	'".$poojarooms."',
                            `TOTAL_PLOT_AREA`           =	'".$txtPlotArea."',
                            `LENGTH_OF_PLOT`           =	'".$txtSizeLen."',
                            `BREADTH_OF_PLOT`           =	'".$txtSizeBre."',
							`CLP_VISIBLE`				=	'1',
							`CREATED_DATE`				=	now(),
							 `STATUS`					=	'".$status."'";

		$res=mysql_query($sql) or die(mysql_error());
		$optionId = mysql_insert_id();
		audit_insert($optionId,'insert','resi_project_options',$projectId);
	}
	else
	{
		$sql	=	"UPDATE  ".PROJECT_OPTIONS."
						SET
							`UNIT_NAME`					=	'".$txtUnitName."',
							`UNIT_TYPE`					=	'".$unitType."',
							`SIZE`						=	'".$txtSize."',
							`PRICE_PER_UNIT_AREA`		=	'".$txtPricePerUnitArea."',
							`PRICE_PER_UNIT_AREA_DP`	=	'".$txtPricePerUnitAreaDp."',
							`PRICE_PER_UNIT_LOW`		=	'".$txtPricePerUnitLow."',
							`PRICE_PER_UNIT_HIGH`		=	'".$txtPricePerUnitHigh."',
							`VILLA_PLOT_AREA`			=	'".$txtVillaPlotArea."',
							`VILLA_NO_FLOORS`			=	'".$txtVillaFloors."',
							`VILLA_TERRACE_AREA`		=	'".$txtVillaTerraceArea."',
							`VILLA_GARDEN_AREA`			=	'".$txtVillaGardenArea."',
							`BEDROOMS`					=	'".$bed."',
							`BATHROOMS`					=	'".$bathrooms."',
							`STUDY_ROOM`				=	'".$studyrooms."',
							`SERVANT_ROOM`				=	'".$servantrooms."',
							`BALCONY`					=	'".$balconys."',
							`POOJA_ROOM`				=	'".$poojarooms."',
                            `TOTAL_PLOT_AREA`           =	'".$txtPlotArea."',
                            `LENGTH_OF_PLOT`           =	'".$txtSizeLen."',
                            `BREADTH_OF_PLOT`           =	'".$txtSizeBre."',
							`CLP_VISIBLE`				=	'1',
							 `STATUS`					=	'".$status."'
						WHERE
								`PROJECT_ID`	=	'".$projectId."'
							 AND
								OPTIONS_ID		=	'".$typeid_edit."'";

		$res=mysql_query($sql) or die(mysql_error());
		$optionId = $typeid_edit;
		audit_insert($typeid_edit,'update','resi_project_options',$projectId);
	}
						if($bed=='')
						{
							$bed=0;
						}
						if($bathrooms=='')
						{
							$bathrooms=0;
						}
						if($balconys=='')
						{
							$balconys=0;
						}

						if($studyrooms=='')
						{
							$studyrooms=0;
						}

						if($servantrooms=='')
						{
							$servantrooms=0;
						}
						if($poojarooms=='')
						{
							$poojarooms=0;
						}

						$str=$optionId."_".$bed."_".$bathrooms."_".$balconys."_".$servantrooms."_".$studyrooms."_".$poojarooms."_".$rowId;

						echo $str;




?>

