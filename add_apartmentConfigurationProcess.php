<?php
	$RoomCategoryArr	=	RoomCategory::categoryList();
	$projectId			=	$_REQUEST['projectId'];
    $project = ResiProject::virtual_find($projectId);
	$projectDetail		=	$project->to_custom_array();
    $projectDetail = array($projectDetail);

	$smarty->assign("ProjectDetail", $projectDetail);
	$smarty->assign("typeA", APARTMENTS);
	$smarty->assign("typeV", VILLA);
	$smarty->assign("typeVA", VILLA_APARTMENTS);
	$smarty->assign("typeP", PLOTS);
	$smarty->assign("typePV", PLOT_VILLAS);
	$smarty->assign("typePA", PLOT_APARTMENTS);
    $smarty->assign("typeC", COMMERCIAL);

	$smarty->assign("RoomCategoryArr",$RoomCategoryArr);
	
	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);

	/*************************************/
	$flag=0;
	$flg_edit=0;
	$flg_delete=0;


	$smarty->assign("projectId", $projectId);

if ($_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Save")
{
/*************Add new project type if projectid is blank*********************************/

    $flgins	=	0;
    $projectId	=	$_REQUEST['projectId'];
    if($projectId	== '')
    {
            $projecteror	=	"Please select project";
    }
    $txtVillaPlotArea			=	'0';
    $txtVillaFloors				=	'0';
    $txtVillaTerraceArea		=	'0';
    $txtVillaGardenArea			=	'0';

//echo '<pre>';print_r($_REQUEST);echo "<pre>";exit;

    foreach($_REQUEST['txtUnitName'] AS $key=>$val)
    {
            if($val != '')
                    $flgins	=	1;
            if($_REQUEST['txtUnitName'][$key] != '')
            {

                //$projectId				=	$val;
                $txtUnitName			=	$_REQUEST['txtUnitName'][$key];
                $txtSize			=	$_REQUEST['txtSize'][$key];
                $txtCarpetAreaInfo  =   (int)($_REQUEST['txtCarpetAreaInfo_'.$key] == "on");
                $txtPricePerUnitArea		=	$_REQUEST['txtPricePerUnitArea'][$key];
                $txtPricePerUnitAreaDp		=	$_REQUEST['txtPricePerUnitAreaDp'][$key];
                $txtPricePerUnitHigh		=	$_REQUEST['txtPricePerUnitHigh'][$key];
                $txtPricePerUnitLow		=	$_REQUEST['txtPricePerUnitLow'][$key];
                $txtNoOfFloor			=	$_REQUEST['txtNoOfFloor'][$key];

                $txtVillaPlotArea		=	$_REQUEST['txtVillaPlotArea'][$key];
                $txtVillaFloors			=	$_REQUEST['txtVillaFloors'][$key];
                $txtVillaTerraceArea		=	$_REQUEST['txtVillaTerraceArea'][$key];
                $txtVillaGardenArea		=	$_REQUEST['txtVillaGardenArea'][$key];
                $bed				=	$_REQUEST['bed'][$key];
                $Balconys			=	$_REQUEST['Balconys'][$key];

                $txtPlotArea                =	$_REQUEST['txtPlotArea'][$key];
                $txtSizeLen                 =	$_REQUEST['txtSizeLen'][$key];
                $txtSizeBre                 =	$_REQUEST['txtSizeBre'][$key];

                $studyrooms					=	$_REQUEST['studyrooms'][$key];
                $servantrooms				=	$_REQUEST['servantrooms'][$key];
                $poojarooms					=	$_REQUEST['poojarooms'][$key];
                $bathrooms					=	$_REQUEST['bathrooms'][$key];
                $unitType					=	$_REQUEST['unitType'][$key];

                $status						=	$_REQUEST['propstatus'][$key];
                $pid[]						=	trim($txtUnitName);
                $txtUnitNameval[]			=	trim($txtUnitName);
                $txtSizeval[]				=	trim($txtSize);
                $txtPricePerUnitAreaval[]	=	trim($txtPricePerUnitArea);
                $txtPricePerUnitAreaDpval[]	=	trim($txtPricePerUnitAreaDp);

                $bedval[]					=	$bed;
                $bathroomsval[]				=	$bathrooms;
                $Balconysval[]				=	$Balconys;
                $studyroomsval[]			=	$studyrooms;
                $servantroomsval[]			=	$servantrooms;
                $poojaroomsval[]			=	$poojarooms;
                $statusval[]				=	$status;
                
                $smarty->assign("pid", $pid);

                $smarty->assign("txtUnitNameval", $txtUnitNameval);
                $smarty->assign("txtSizeval", $txtSizeval);
                $smarty->assign("txtPricePerUnitAreaval", $txtPricePerUnitAreaval);
                $smarty->assign("txtPricePerUnitAreaDpval", $txtPricePerUnitAreaDpval);
                $smarty->assign("txtPricePerUnitHigh", $txtPricePerUnitHigh);
                $smarty->assign("txtPricePerUnitLow", $txtPricePerUnitLow);
                $smarty->assign("txtNoOfFloor", $txtNoOfFloor);
                $smarty->assign("txtVillaPlotArea", $txtVillaPlotArea);
                $smarty->assign("txtVillaFloors", $txtVillaFloors);
                $smarty->assign("txtVillaTerraceArea", $txtVillaTerraceArea);
                $smarty->assign("txtVillaGardenArea", $txtVillaGardenArea);

                $smarty->assign("txtPlotArea", $txtPlotArea);
                $smarty->assign("txtSizeLen", $txtSizeLen);
                $smarty->assign("txtSizeBre", $txtSizeBre);

                $smarty->assign("bedval", $bedval);
                $smarty->assign("bathroomsval",$bathroomsval);
                $smarty->assign("balconysval",$Balconysval);
                $smarty->assign("studyroomsval",$studyroomsval);
                $smarty->assign("servantroomsval",$servantroomsval);
                $smarty->assign("poojaroomsval",$poojaroomsval);
                $smarty->assign("statusval",$statusval);
                

                if ($_REQUEST['unitType'][$key]!='Plot' && $_REQUEST['unitType'][$key]!='Commercial') {
                    if(trim($txtSize) == '' OR (!is_numeric(trim($txtSize))))
                    {
                        $ErrorMsg[$key] .= "<br>Please enter unit size";
                    }

                    if($bed == '')
                    {
                        $ErrorMsg[$key]	.= "<br>Please select bedroom";
                    }
                }
                else
                {
                    $txtSize = $txtPlotArea;
                }
                
                $currentPropertyObject = null;
                $sizeChanging = true;
                if ($_REQUEST['typeid_edit'][$key]) {
                    $sizeChanging = false;
                    $currentPropertyObject = getProperty($_REQUEST['typeid_edit'][$key]);
                    if ($currentPropertyObject[$_REQUEST['typeid_edit'][$key]]['SIZE'] != $txtSize || $currentPropertyObject[$_REQUEST['typeid_edit'][$key]]['BEDROOMS'] != $bed) {
                        $sizeChanging = true;
                    }
                }
                
                if ($sizeChanging && !isUserPermitted('size', 'overrideValidation')) {
                    if ($bed == 1 && $txtSize > 1200) {
                        $ErrorMsg[$key]	.=	"<br>Unit Size can't greater then 1200 sqft for 1 BHK";
                    }
                    if ($bed == 2 && $txtSize > 2000) {
                        $ErrorMsg[$key]	.=	"<br>Unit Size can't greater then 2000 sqft for 2 BHK";
                    }
                    if ($bed == 3 && $txtSize > 4000) {
                        $ErrorMsg[$key]	.=	"<br>Unit Size can't greater then 4000 sqft for 3 BHK";
                    }
                    if ($bed == 4 && $txtSize > 6000) {
                        $ErrorMsg[$key]	.=	"<br>Unit Size can't greater then 6000 sqft for 4 BHK";
                    }
                    if ($bed == 5 && $txtSize > 7000) {
                        $ErrorMsg[$key]	.=	"<br>Unit Size can't greater then 7000 sqft for 5 BHK";
                    }
                    if ($bed == 6 && $txtSize > 10000) {
                        $ErrorMsg[$key]	.=	"<br>Unit Size can't greater then 10000 sqft for 6 BHK";
                    }
                }


            if(!is_array($ErrorMsg))
            {
                if($_REQUEST['delete'][$key] == '')
                {
                    if($_REQUEST['typeid_edit'][$key] == '')
                    {
                        $option = new ResiProjectOptions();
                        $option->project_id = $projectId;
                        $action = "insert";
                    }
                    else
                    {
                        if($txtPlotArea != 0)
                           $txtPlotArea = $txtSize;
                        $option_id = $_REQUEST['typeid_edit'][$key];
                        $option = ResiProjectOptions::find($option_id);
                        $action = "update";
                    }
                    $option->option_type = $unitType;
                    $option->option_name = $txtUnitName;
                    $option->size = $txtSize;
//                    $option->carpet_area_info = $txtCarpetAreaInfo;
//                    $option->price_per_unit_area = $txtPricePerUnitArea;
//                    $option->price_per_unit_area_dp = (int)$txtPricePerUnitAreaDp;
//                    $option->status = $status;
                    $option->bedrooms = (int)$bed;
                    $option->bathrooms = (int)$bathrooms;
//                    $option->price_per_unit_high = $txtPricePerUnitHigh;
//                    $option->price_per_unit_low = $txtPricePerUnitLow;
//                    $option->no_of_floors = $txtNoOfFloor;
                    $option->villa_plot_area = (int)$txtVillaPlotArea;
                    $option->villa_no_floors = (int)$txtVillaFloors;
                    $option->villa_terrace_area = (int)$txtVillaTerraceArea;
                    $option->villa_garden_area = (int)$txtVillaGardenArea;
//                    $option->total_plot_area = (int)$txtPlotArea;
                    $option->balcony = (int)$Balconys;
                    $option->study_room = (int)$studyrooms;
                    $option->servant_room = (int)$servantrooms;
                    $option->pooja_room = (int)$poojarooms;
                    $option->length_of_plot = (int)$txtSizeLen;
                    $option->breadth_of_plot = (int)$txtSizeBre;
                    $option->updated_by = $_SESSION["adminId"];
//                    if($txtCarpetAreaInfo) $option->carpet_area = $option->size;
                    $result = $option->save();
                }
                else
                {
                    /**********code for deletion options*************/
                    $qryDel = "DELETE FROM ".RESI_PROJECT_OPTIONS." 
                    WHERE
                        OPTIONS_ID = '".$_REQUEST['typeid_edit'][$key]."'
                    AND
                        PROJECT_ID = '".$projectId."'";
                    $resDel	= mysql_query($qryDel) or die(mysql_error()." error in deletion");
                    $flg_delete = 1;
                }
            }  

        }
    }

    if(($flgins == 0) && (count($_REQUEST['bed']) == 15))
    {
        $ErrorMsg1 = 'Please select atleast one unit name';
    }

    if(!is_array($ErrorMsg) && $ErrorMsg1 == '')
    {
        if($flg_edit == 1)
        {
            if($preview == 'true')
                header("Location:show_project_details.php?projectId=".$projectId);
            else
                header("Location:ProjectList.php?projectId=".$projectId);
        }
        else
        {
            if($flg_delete === 1)
                header("Location:add_apartmentConfiguration.php?projectId=".$projectId."&edit=edit");
            else
                header("Location:add_apartmentFloorPlan.php?projectId=".$projectId);
        }
    }

}
    else if($_POST['btnExit'] == "Exit")
    {
          if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
    }
    else if($_POST['Skip'] == "Skip")
    {
          header("Location:add_apartmentFloorPlan.php?projectId=".$projectId);
    }
    if($_REQUEST['edit'] == 'edit')
    {
        /**********************Query for select values according project type for update**********************/
    	
        $ProjectType = ProjectType($projectId);
        //echo "<pre>";
        //print_r($arrProjectType_P);
        //echo "</pre>";
        $smarty->assign("edit_project", $projectId);
        $smarty->assign("TYPE_ID", $arrProjectType['OPTIONS_ID']);
        $smarty->assign("txtUnitNameval", $arrProjectType['OPTION_NAME']);
        $smarty->assign("txtSizeval", $arrProjectType['SIZE']);
        $smarty->assign("txtCarpetAreaInfo", $arrProjectType['CARPET_AREA_INFO']);
        $smarty->assign("txtPricePerUnitAreaval", $arrProjectType['PRICE_PER_UNIT_AREA']);
        $smarty->assign("txtPricePerUnitAreaDpval", $arrProjectType['PRICE_PER_UNIT_AREA_DP']);
        $smarty->assign("txtPricePerUnitHighval", $arrProjectType['PRICE_PER_UNIT_HIGH']);
        $smarty->assign("txtPricePerUnitLowval", $arrProjectType['PRICE_PER_UNIT_LOW']);
        $smarty->assign("txtVillaPlotArea", $arrProjectType['VILLA_PLOT_AREA']);
        $smarty->assign("txtVillaFloors", $arrProjectType['VILLA_NO_FLOORS']);
        $smarty->assign("txtVillaTerraceArea", $arrProjectType['VILLA_TERRACE_AREA']);
        $smarty->assign("txtVillaGardenArea", $arrProjectType['VILLA_GARDEN_AREA']);
        $smarty->assign("bedval", $arrProjectType['BEDROOMS']);
        $smarty->assign("bathroomsval",$arrProjectType['BATHROOMS']);
        $smarty->assign("balconysval",$arrProjectType['BALCONY']);
        $smarty->assign("studyroomsval",$arrProjectType['STUDY_ROOM']);
        $smarty->assign("servantroomsval",$arrProjectType['SERVANT_ROOM']);
        $smarty->assign("poojaroomsval",$arrProjectType['POOJA_ROOM']);
        $smarty->assign("statusval",$arrProjectType['STATUS']);
        $smarty->assign("txtNoOfFloor",$arrProjectType['NO_OF_FLOORS']);

        //echo "<pre>";print_r($arrProjectType_P);

        $smarty->assign("TYPE_ID_P", $arrProjectType_P['OPTIONS_ID']);
        $smarty->assign("unitType_P", $arrProjectType_P['UNIT_TYPE']);
        $smarty->assign("txtUnitNameval_P", $arrProjectType_P['UNIT_NAME']);
        $smarty->assign("txtSizeval_P", $arrProjectType_P['SIZE']);
        $smarty->assign("txtPricePerUnitAreaval_P", $arrProjectType_P['PRICE_PER_UNIT_AREA']);
        $smarty->assign("txtPlotArea_P", $arrProjectType_P['TOTAL_PLOT_AREA']);
        $smarty->assign("txtSizeLenval_P", $arrProjectType_P['LENGTH_OF_PLOT']);
        $smarty->assign("txtSizeBreval_P", $arrProjectType_P['BREADTH_OF_PLOT']);
        $smarty->assign("statusval_P",$arrProjectType_P['STATUS']);

        $smarty->assign("TYPE_ID_VA", $arrProjectType_VA['OPTIONS_ID']);
        $smarty->assign("txtUnitNameval_VA", $arrProjectType_VA['UNIT_NAME']);
        $smarty->assign("txtSizeval_VA", $arrProjectType_VA['SIZE']);
        $smarty->assign("txtCarpetAreaInfo_VA", $arrProjectType_VA['CARPET_AREA_INFO']);
        $smarty->assign("txtPricePerUnitAreaval_VA", $arrProjectType_VA['PRICE_PER_UNIT_AREA']);
        $smarty->assign("txtPricePerUnitAreaDpval_VA", $arrProjectType_VA['PRICE_PER_UNIT_AREA_DP']);
        $smarty->assign("txtPricePerUnitHighval_VA", $arrProjectType_VA['PRICE_PER_UNIT_HIGH']);
        $smarty->assign("txtPricePerUnitLowval_VA", $arrProjectType_VA['PRICE_PER_UNIT_LOW']);
        $smarty->assign("txtVillaPlotArea_VA", $arrProjectType_VA['VILLA_PLOT_AREA']);
        $smarty->assign("txtVillaFloors_VA", $arrProjectType_VA['VILLA_NO_FLOORS']);
        $smarty->assign("txtVillaTerraceArea_VA", $arrProjectType_VA['VILLA_TERRACE_AREA']);
        $smarty->assign("txtVillaGardenArea_VA", $arrProjectType_VA['VILLA_GARDEN_AREA']);
        $smarty->assign("bedval_VA", $arrProjectType_VA['BEDROOMS']);
        $smarty->assign("bathroomsval_VA",$arrProjectType_VA['BATHROOMS']);
        $smarty->assign("balconysval_VA",$arrProjectType_VA['BALCONY']);
        $smarty->assign("studyroomsval_VA",$arrProjectType_VA['STUDY_ROOM']);
        $smarty->assign("servantroomsval_VA",$arrProjectType_VA['SERVANT_ROOM']);
        $smarty->assign("poojaroomsval_VA",$arrProjectType_VA['POOJA_ROOM']);
        $smarty->assign("unitType_VA",$arrProjectType_VA['UNIT_TYPE']);
	$smarty->assign("no_of_floors_VA",$arrProjectType_VA['NO_OF_FLOORS']);
        $smarty->assign("statusval_VA",$arrProjectType_VA['STATUS']);

        /***************query for project name display if edit********************/
    }

    $smarty->assign("ErrorMsg", $ErrorMsg);
    $smarty->assign("ErrorMsg1", $ErrorMsg1);
    $smarty->assign("projecteror", $projecteror);
    
    function getProperty($typeId) {
        $property = array();
        $qry = "SELECT * FROM ".RESI_PROJECT_OPTIONS." WHERE OPTIONS_ID = $typeId";
        $resource = mysql_query($qry) or die(mysql_error()." error in select qry");
        if ($resource) {
            while ($property1= mysql_fetch_assoc($resource)) {
                $property[$typeId] = $property1;
            }
        }
        return $property;
    }

?>
