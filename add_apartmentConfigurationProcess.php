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
    $prnt = "";
	/*************************************/
	$flag=0;
	$flg_edit=0;
	$flg_delete=0;


	$smarty->assign("projectId", $projectId);
    $ErrorMsg = array();
    $ErrorMsg2 = array();
    
    //only to get available unique project option combinations so to avoid duplicate on add and edit 
    if($_REQUEST['edit'] == 'edit')
    {
        /**********************Query for select values according project type for update**********************/
        
        $ProjectType = ProjectType($projectId);
        $optionTxtStrArray = array();
        foreach($arrProjectType['OPTION_NAME'] AS $key=>$val){
             $optionTxtStr = $arrProjectType['BEDROOMS'][$key]."-".$arrProjectType['BATHROOMS'][$key]."-".$arrProjectType['OPTION_NAME'][$key]."-".$arrProjectType['SIZE'][$key];
             array_push($optionTxtStrArray, $optionTxtStr);
        }
        foreach($arrProjectType_VA['OPTION_NAME'] AS $key=>$val){
             $optionTxtStr = $arrProjectType_VA['BEDROOMS'][$key]."-".$arrProjectType_VA['BATHROOMS'][$key]."-".$arrProjectType_VA['OPTION_NAME'][$key]."-".$arrProjectType_VA['SIZE'][$key];
             array_push($optionTxtStrArray, $optionTxtStr);
        }

        
    }

if($_REQUEST['edit'] == 'edit')
    {
        /**********************Query for select values according project type for update**********************/
        
        //$ProjectType = ProjectType($projectId);
        //$optionTxtStrArray = array();
       
        //echo "<pre>";
        //($arrProjectType_V);
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
        $smarty->assign("txtDisplayCarpetArea",$arrProjectType['DISPLAY_CARPET_AREA']);

        
        
        //echo "<pre>";print_r($arrProjectType_P); die;

        $smarty->assign("TYPE_ID_P", $arrProjectType_P['OPTIONS_ID']);
        $smarty->assign("unitType_P", $arrProjectType_P['UNIT_TYPE']);
        $smarty->assign("txtUnitNameval_P", $arrProjectType_P['OPTION_NAME']);
        $smarty->assign("txtSizeval_P", $arrProjectType_P['SIZE']);
        $smarty->assign("txtPricePerUnitAreaval_P", $arrProjectType_P['PRICE_PER_UNIT_AREA']);
        $smarty->assign("txtPlotArea_P", $arrProjectType_P['SIZE']);
        $smarty->assign("txtSizeLenval_P", $arrProjectType_P['LENGTH_OF_PLOT']);
        $smarty->assign("txtSizeBreval_P", $arrProjectType_P['BREADTH_OF_PLOT']);
        $smarty->assign("statusval_P",$arrProjectType_P['STATUS']);


 //echo "<pre>";print_r($arrProjectType_VA); die;
        $smarty->assign("TYPE_ID_VA", $arrProjectType_VA['OPTIONS_ID']);
        $smarty->assign("txtUnitNameval_VA", $arrProjectType_VA['OPTION_NAME']);
        $smarty->assign("txtSizeval_VA", $arrProjectType_VA['SIZE']);
        $smarty->assign("txtCarpetAreaInfo_VA", $arrProjectType_VA['DISPLAY_CARPET_AREA']);
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
	
	$option_txt_array = array();
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
                //echo (bool)$_REQUEST['txtCarpetAreaInfo_'.$key];
               
                $txtDisplayCarpetArea = (bool)($_REQUEST['txtCarpetAreaInfo_'.$key] == "on");
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
                $txtDisplayCarpetAreaval[]  =   $txtDisplayCarpetArea;     
                $txtVillaPlotAreaval[]      =    $txtVillaPlotArea;
                $txtVillaFloorsval[]        =    $txtVillaFloors;
                $txtVillaTerraceAreaval[]   =    $txtVillaTerraceArea;
                $txtVillaGardenAreaval[]    =    $txtVillaGardenArea;
                $txtCarpetAreaInfoval[]     =    $txtCarpetAreaInfo;
                $smarty->assign("pid", $pid);
                
                $smarty->assign("txtUnitNameval", $txtUnitNameval);
                $smarty->assign("txtSizeval", $txtSizeval);
                $smarty->assign("txtCarpetAreaInfo", $txtCarpetAreaInfoval);
                $smarty->assign("txtPricePerUnitAreaval", $txtPricePerUnitAreaval);
                $smarty->assign("txtPricePerUnitAreaDpval", $txtPricePerUnitAreaDpval);
                $smarty->assign("txtPricePerUnitHighval", $txtPricePerUnitHigh);
                $smarty->assign("txtPricePerUnitLowval", $txtPricePerUnitLow);
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
                $smarty->assign("txtDisplayCarpetArea",$txtDisplayCarpetAreaval);

                //incase of villa
                $smarty->assign("txtUnitNameval_VA", $txtUnitNameval);
                $smarty->assign("txtSizeval_VA", $txtSizeval);
                $smarty->assign("txtCarpetAreaInfo_VA", $txtCarpetAreaInfoval);
                $smarty->assign("txtPricePerUnitAreaval_VA", $txtPricePerUnitAreaval);
                $smarty->assign("txtPricePerUnitAreaDpval_VA", $txtPricePerUnitAreaDpval);
                $smarty->assign("txtPricePerUnitHighval_VA", $txtPricePerUnitHighval);
                $smarty->assign("txtPricePerUnitLowval_VA", $txtPricePerUnitLowval);
                $smarty->assign("no_of_floors_VA", $txtNoOfFloor);
                $smarty->assign("txtVillaPlotArea_VA", $txtVillaPlotAreaval);
                $smarty->assign("txtVillaFloors_VA", $txtVillaFloorsval);
                $smarty->assign("txtVillaTerraceArea_VA", $txtVillaTerraceAreaval);
                $smarty->assign("txtVillaGardenArea_VA", $txtVillaGardenAreaval);

                $smarty->assign("txtPlotArea", $txtPlotArea);
                $smarty->assign("txtSizeLen", $txtSizeLen);
                $smarty->assign("txtSizeBre", $txtSizeBre);

                $smarty->assign("bedval_VA", $bedval);
                $smarty->assign("bathroomsval_VA",$bathroomsval);
                $smarty->assign("balconysval_VA",$Balconysval);
                $smarty->assign("studyroomsval_VA",$studyroomsval);
                $smarty->assign("servantroomsval_VA",$servantroomsval);
                $smarty->assign("poojaroomsval_VA",$poojaroomsval);
                $smarty->assign("statusval_VA",$statusval);
                $smarty->assign("txtDisplayCarpetArea",$txtDisplayCarpetArea);
                
                //array_push($ErrorMsg2, $key);
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


            if(empty($ErrorMsg))
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
                    $option->display_carpet_area = $txtCarpetAreaInfo;
//                    if($txtCarpetAreaInfo) $option->carpet_area = $option->size;
					
					$optionTxt = $option->bedrooms."-".$option->bathrooms."-".$option->option_name."-".$option->size;
                    /*foreach ($optionTxtStrArray as $key1 => $value1) {
                        if($key!=$key1 && $optionTxt==$value1){
                           $ErrorMsg1 = 'Duplicate Option!';//die();
                            $tmparr = array();
                            $tmparr['key']=$key1;
                            $tmparr['dupkey']=$key;
                            $tmparr['error']='Duplicate Option!';
                           
                            array_push($ErrorMsg2, $tmparr);
                        }
                    }*/

                    foreach ($option_txt_array as $key1 => $value1) {
                        if($key!=$key1 && $optionTxt==$value1){
                           $ErrorMsg1 = 'Duplicate Option!';//die();
                            $tmparr = array();
                            $tmparr['key']=$key1;
                            $tmparr['dupkey']=$key;
                            $tmparr['error']='Duplicate Option!';
                           
                            array_push($ErrorMsg2, $tmparr);
                        }
                    }
					/*if(in_array($optionTxt,$option_txt_array)){
                        $ErrorMsg1 = 'Duplicate Option!';
                    }else{
                      $option_txt_array[] = $optionTxt;
                      $result = $option->save();*/
                    if(empty($ErrorMsg2)){
					  $option_txt_array[] = $optionTxt;
					  $result = $option->save();
                      if ($action == 'insert') {
                        $phases = ResiProjectPhase::find('all', array('conditions' => array('project_id' => $projectId, 'phase_type' => 'Logical')));
                        $listing = new Listings();
                        $listing->option_id = $option->options_id;
                        $listing->phase_id = $phases[0]->phase_id;
                        $listing->listing_category = 'Primary';
                        $listing->status = 'Active';
                        $listing->updated_at = date('Y-m-d H:i:s');
                        $listing->updated_by = $_SESSION['adminId'];
                        $listing->created_at = date('Y-m-d H:i:s');
                        $listing->save();
                      }
						
					}
                    
                }
                else
                {
                    
					$list_option_id = $_REQUEST['typeid_edit'][$key]; 
					
					//print $list_option_id; die;
					
					############## Transaction Start##############
					ResiProject::transaction(function(){
						
						global $list_option_id,$projectId,$flg_delete,$ErrorMsg1;
																	
						$list_id_res = mysql_query("SELECT lst.id from ".LISTINGS." lst left join ".RESI_PROJECT_PHASE." rpp on lst.phase_id = rpp.phase_id where lst.option_id = ".$list_option_id." and (rpp.phase_type = 'Logical' or rpp.status = 'Inactive' or lst.status = 'Inactive')");
						
						while($list_id = mysql_fetch_object($list_id_res)){
							$qryDel_list = "DELETE FROM ".LISTINGS." WHERE  ID = '".$list_id->id."'";
                            $resDel_list = mysql_query($qryDel_list);
						}
																																	
						                                                  
						$qryDel = "DELETE FROM ".RESI_PROJECT_OPTIONS." 
                    WHERE
                        OPTIONS_ID = '".$list_option_id."'
                    AND
                        PROJECT_ID = '".$projectId."'";
						$resDel	= mysql_query($qryDel);
						$flg_delete = 1;
						if(!$resDel){
							$ErrorMsg1 = 'Could not delete!';
                            //$ErrorMsg1 = mysql_error().$list_option_id;
						}
								
					});					
					############## Transaction End ##############
                }
            }  

        }
    }

    //if(($flgins == 0) && (count($_REQUEST['bed']) == 15))
    if($flgins == 0)
    {
        $ErrorMsg1 = 'Please select atleast one unit name';
    }
    
    
    
    if(empty($ErrorMsg) && empty($ErrorMsg2) && $ErrorMsg1 == '')
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
     
//print "<pre>--".print_r($ErrorMsg,1); die;
    
    $smarty->assign("ErrorMsg", $ErrorMsg);
    $smarty->assign("ErrorMsg1", $ErrorMsg1);
    $smarty->assign("ErrorMsg2", $ErrorMsg2);
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
