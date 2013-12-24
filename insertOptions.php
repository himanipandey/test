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
							`OPTION_NAME`					=	'".$txtUnitName."',
							`OPTION_TYPE`					=	'".$unitType."',
							`SIZE`						=	'".$txtSize."',
							`MEASURE`					=	'sq ft',
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
                                                        `LENGTH_OF_PLOT`           =	'".$txtSizeLen."',
                                                        `BREADTH_OF_PLOT`           =	'".$txtSizeBre."',
							`created_at`				=	DATE_FORMAT(NOW() ,'%Y-%m-01 00:00:00'),
                                                        updated_by = ".$_SESSION['adminId']."";

		$res=mysql_query($sql) or die(mysql_error());
		$optionId = mysql_insert_id();
		audit_insert($optionId,'insert','resi_project_options',$projectId);
	}
	else
	{
			
		$sql	=	"UPDATE  ".PROJECT_OPTIONS."
						SET
							`OPTION_NAME`					=	'".$txtUnitName."',
							`OPTION_TYPE`					=	'".$unitType."',
							`SIZE`						=	'".$txtSize."',
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
                                                         `LENGTH_OF_PLOT`           =	'".$txtSizeLen."',
                                                        `BREADTH_OF_PLOT`           =	'".$txtSizeBre."',
							updated_by = ".$_SESSION['adminId']."
						WHERE
								`PROJECT_ID`	=	'".$projectId."'
							 AND
								OPTIONS_ID		=	'".$typeid_edit."'";

		$res=mysql_query($sql) or die(mysql_error());
		$optionId = $typeid_edit;
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
						
						//fetch existing room sizes for the current option
						$sql_room_sizes = mysql_query("SELECT * FROM ".PROJECT_OPTIONS_ROOM_SIZE." WHERE `OPTIONS_ID` = '$optionId'");
						$room_sizes = '#';
						
						if($sql_room_sizes){
						
							$room_sizes_arr = array();
							
								while($sizes_row = mysql_fetch_object($sql_room_sizes)){
									
									if($sizes_row->ROOM_CATEGORY_ID == 1 || $sizes_row->ROOM_CATEGORY_ID == 2){ //bed
										$room_sizes_arr['beds'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
										$room_sizes_arr['bedscat'][] = $sizes_row->ROOM_CATEGORY_ID;
									}
									if($sizes_row->ROOM_CATEGORY_ID == 3) //bath
										$room_sizes_arr['bathrooms'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 4) //Kitchen
										$room_sizes_arr['kitchen'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 5) //Living
										$room_sizes_arr['living'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 6) //Dining
										$room_sizes_arr['dining'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 7) //balcony
										$room_sizes_arr['balconys'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 8) //Servant
										$room_sizes_arr['servantrooms'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 9) //study
										$room_sizes_arr['studyroom'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 10) //pooja
										$room_sizes_arr['poojaroom'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 11) //powder_room
										$room_sizes_arr['powderroom'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 12) //Terrace
										$room_sizes_arr['terrace'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 13) //Utility Room
										$room_sizes_arr['utilityroom'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									if($sizes_row->ROOM_CATEGORY_ID == 14) //Family Room
										$room_sizes_arr['familyroom'][] = $sizes_row->ROOM_LENGTH.'@'.$sizes_row->ROOM_BREATH;
									
								}
														
						}
						
						echo $str."_"."beds#".implode('#',$room_sizes_arr['beds'])."_"."bedscat#".implode('#',$room_sizes_arr['bedscat'])."_"."bathrooms#".implode('#',$room_sizes_arr['bathrooms'])."_"."balconys#".implode('#',$room_sizes_arr['balconys'])."_"."servantrooms#".implode('#',$room_sizes_arr['servantrooms'])."_"."studyroom#".implode('#',$room_sizes_arr['studyroom'])."_"."poojaroom#".implode('#',$room_sizes_arr['poojaroom'])."_"."dining#".implode('#',$room_sizes_arr['dining'])."_"."kitchen#".implode('#',$room_sizes_arr['kitchen'])."_"."living#".implode('#',$room_sizes_arr['living'])."_"."powderroom#".implode('#',$room_sizes_arr['powderroom'])."_"."terrace#".implode('#',$room_sizes_arr['terrace'])."_"."utilityroom#".implode('#',$room_sizes_arr['utilityroom'])."_"."familyroom#".implode('#',$room_sizes_arr['familyroom']);




?>

