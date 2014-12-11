<?php
	$RoomCategoryArr = RoomCategory::categoryList();
	$projectId =	$_REQUEST['projectId'];
        $project = ResiProject::virtual_find($projectId);
	$projectDetail = $project->to_custom_array();
        $projectDetail = array($projectDetail);

	$smarty->assign("ProjectDetail", $projectDetail);
	$smarty->assign("typeA", APARTMENTS);
	$smarty->assign("typeV", VILLA);
	$smarty->assign("typeVA", VILLA_APARTMENTS);
	$smarty->assign("typeP", PLOTS);
	$smarty->assign("typePV", PLOT_VILLAS);
	$smarty->assign("typePA", PLOT_APARTMENTS);
        $smarty->assign("typeC", COMMERCIAL);
        $smarty->assign("typeSHOP", SHOP);
	$smarty->assign("typeOFFICE", OFFICE);
	$smarty->assign("typeSHOP_OFFICE", SHOP_OFFICE);
        $smarty->assign("typeOTHER", OTHER);
    
	$smarty->assign("RoomCategoryArr",$RoomCategoryArr);
	
	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);
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

        /**********************Query for select values according project type for update**********************/

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
        //echo "<pre>";print_r($arrProjectType['BEDROOMS']);
        $smarty->assign("bathroomsval",$arrProjectType['BATHROOMS']);
        $smarty->assign("balconysval",$arrProjectType['BALCONY']);
        $smarty->assign("studyroomsval",$arrProjectType['STUDY_ROOM']);
        $smarty->assign("servantroomsval",$arrProjectType['SERVANT_ROOM']);
        $smarty->assign("poojaroomsval",$arrProjectType['POOJA_ROOM']);
        $smarty->assign("statusval",$arrProjectType['STATUS']);
        $smarty->assign("txtNoOfFloor",$arrProjectType['NO_OF_FLOORS']);
        $smarty->assign("txtDisplayCarpetArea",$arrProjectType['DISPLAY_CARPET_AREA']);
        $smarty->assign("TYPE_ID_P", $arrProjectType_P['OPTIONS_ID']);
        $smarty->assign("unitType_P", $arrProjectType_P['UNIT_TYPE']);
        $smarty->assign("txtUnitName_P", $arrProjectType_P['OPTION_NAME']);
        $smarty->assign("txtSizeval_P", $arrProjectType_P['SIZE']);
        $smarty->assign("txtPricePerUnitAreaval_P", $arrProjectType_P['PRICE_PER_UNIT_AREA']);
        $smarty->assign("txtPlotArea_P", $arrProjectType_P['SIZE']);
        $smarty->assign("txtSizeLen_P", $arrProjectType_P['LENGTH_OF_PLOT']);
        $smarty->assign("txtSizeBre_P", $arrProjectType_P['BREADTH_OF_PLOT']);
        $smarty->assign("statusval_P",$arrProjectType_P['STATUS']);

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
    //echo "<pre>";print_r($_REQUEST);die;
/*************Add new project type if projectid is blank*********************************/
    $flgins = 0;
    $projectId	= $_REQUEST['projectId'];
    if($projectId	== '')
    {
        $projecteror = "Please select project";
    }
    $txtVillaPlotArea = '0';
    $txtVillaFloors = '0';
    $txtVillaTerraceArea = '0';
    $option_txt_array = array(); 
    //echo "<pre>";print_r($_REQUEST);die;
    $plotIncrease = 1;
    foreach($_REQUEST['txtUnitName'] AS $key=>$val)
    {
            if($val != '')
                $flgins	= 1;
            if($_REQUEST['txtUnitName'][$key] != '')
            {
                $txtUnitName = $_REQUEST['txtUnitName'][$key];
                $txtSize = $_REQUEST['txtSize'][$key];
                
                $txtCarpetAreaInfo = (int)($_REQUEST['txtCarpetAreaInfo_'.$key] == "on");               
                $txtDisplayCarpetArea = (bool)($_REQUEST['txtCarpetAreaInfo_'.$key] == "on");
                $txtPricePerUnitArea = $_REQUEST['txtPricePerUnitArea'][$key];
                $txtPricePerUnitAreaDp = $_REQUEST['txtPricePerUnitAreaDp'][$key];
                $txtPricePerUnitHigh = $_REQUEST['txtPricePerUnitHigh'][$key];
                $txtPricePerUnitLow = $_REQUEST['txtPricePerUnitLow'][$key];
                $txtNoOfFloor =	$_REQUEST['txtNoOfFloor'][$key];
                $txtVillaPlotArea = $_REQUEST['txtVillaPlotArea'][$key];
                $txtVillaFloors	= $_REQUEST['txtVillaFloors'][$key];
                $txtVillaTerraceArea = $_REQUEST['txtVillaTerraceArea'][$key];
                $txtVillaGardenArea = $_REQUEST['txtVillaGardenArea'][$key];
                $bed =	$_REQUEST['bed'][$key];
                $Balconys = $_REQUEST['Balconys'][$key];

                $txtPlotArea[$key] = $_REQUEST['txtPlotArea'][$key];
                $txtSizeLen[$key] = $_REQUEST['txtSizeLen'][$key];
                $txtSizeBre[$key] = $_REQUEST['txtSizeBre'][$key];
                $txtUnitName_P[$key] = $_REQUEST['txtUnitName'][$key];

                $studyrooms= $_REQUEST['studyrooms'][$key];
                $servantrooms = $_REQUEST['servantrooms'][$key];
                $poojarooms = $_REQUEST['poojarooms'][$key];
                $bathrooms = $_REQUEST['bathrooms'][$key];
                $unitType = $_REQUEST['unitType'][$key];
                $status	= $_REQUEST['propstatus'][$key];
                $apartmentType	= $_REQUEST['apartmentType'][$key];
                
                if($unitType != 'Plot' && $unitType != 'Shop' && $unitType != 'Office' && $unitType != 'Other')
                    $pid[] = trim($txtUnitName);
           
                $txtUnitNameval[$key] =	trim($txtUnitName);
                $txtSizeval[$key] = trim($txtSize);
                $txtPricePerUnitAreaval[$key] =	trim($txtPricePerUnitArea);
                $txtPricePerUnitAreaDpval[$key]	= trim($txtPricePerUnitAreaDp);

                $bedval[$key] =	$bed;
                $bathroomsval[$key]= $bathrooms;
                $Balconysval[$key] = $Balconys;
                $studyroomsval[$key] = $studyrooms;
                $servantroomsval[$key] = $servantrooms;
                $poojaroomsval[$key] = $poojarooms;
                $statusval[$key] = $status;
                $smarty->assign("pid", $pid);
                $smarty->assign("txtUnitNameval", $txtUnitNameval);
                $smarty->assign("txtSizeval", $txtSizeval);
                $smarty->assign("txtCarpetAreaInfo", $txtCarpetAreaInfo);
                $smarty->assign("txtPricePerUnitAreaval", $txtPricePerUnitAreaval);
                $smarty->assign("txtPricePerUnitAreaDpval", $txtPricePerUnitAreaDpval);
                $smarty->assign("txtPricePerUnitHighval", $txtPricePerUnitHigh);
                $smarty->assign("txtPricePerUnitLowval", $txtPricePerUnitLow);
                $smarty->assign("txtNoOfFloor", $txtNoOfFloor);
                $smarty->assign("txtVillaPlotArea", $txtVillaPlotArea);
                $smarty->assign("txtVillaFloors", $txtVillaFloors);
                $smarty->assign("txtVillaTerraceArea", $txtVillaTerraceArea);
                $smarty->assign("txtVillaGardenArea", $txtVillaGardenArea);
                $smarty->assign("txtPlotArea_P", $txtPlotArea);
                $smarty->assign("txtSizeLen_P", $txtSizeLen);
                $smarty->assign("txtUnitName_P", $txtUnitName_P);
                
               // echo "<pre>";print_r($txtSizeLen);
                $smarty->assign("txtSizeBre_P", $txtSizeBre);
                $smarty->assign("bedval", $bedval);
                $smarty->assign("bathroomsval",$bathroomsval);
                $smarty->assign("balconysval",$Balconysval);
                $smarty->assign("studyroomsval",$studyroomsval);
                $smarty->assign("servantroomsval",$servantroomsval);
                $smarty->assign("poojaroomsval",$poojaroomsval);
                $smarty->assign("statusval",$statusval);
                $smarty->assign("txtDisplayCarpetArea",$txtDisplayCarpetArea);
                //incase of villa
                $smarty->assign("txtUnitNameval_VA", $txtUnitNameval);
                $smarty->assign("txtSizeval_VA", $txtSizeval);
                $smarty->assign("txtCarpetAreaInfo_VA", $txtCarpetAreaInfo);
                $smarty->assign("txtPricePerUnitAreaval_VA", $txtPricePerUnitAreaval);
                $smarty->assign("txtPricePerUnitAreaDpval_VA", $txtPricePerUnitAreaDpval);
                $smarty->assign("txtPricePerUnitHighval_VA", $txtPricePerUnitHigh);
                $smarty->assign("txtPricePerUnitLowval_VA", $txtPricePerUnitLow);
                $smarty->assign("no_of_floors_VA", $txtNoOfFloor);
                $smarty->assign("txtVillaPlotArea_VA", $txtVillaPlotArea);
                $smarty->assign("txtVillaFloors_VA", $txtVillaFloors);
                $smarty->assign("txtVillaTerraceArea_VA", $txtVillaTerraceArea);
                $smarty->assign("txtVillaGardenArea_VA", $txtVillaGardenArea);
               // $smarty->assign("txtPlotArea", $txtPlotArea);
              //  $smarty->assign("txtSizeLen", $txtSizeLen);
                //$smarty->assign("txtSizeBre", $txtSizeBre);

                $smarty->assign("bedval_VA", $bedval);
                $smarty->assign("bathroomsval_VA",$bathroomsval);
                $smarty->assign("balconysval_VA",$Balconysval);
                $smarty->assign("studyroomsval_VA",$studyroomsval);
                $smarty->assign("servantroomsval_VA",$servantroomsval);
                $smarty->assign("poojaroomsval_VA",$poojaroomsval);
                $smarty->assign("statusval_VA",$statusval);
                $smarty->assign("txtDisplayCarpetArea",$txtDisplayCarpetArea);
                //array_push($ErrorMsg2, $key);
               // echo $_REQUEST['unitType'][$key]."<br>";
                if ($_REQUEST['unitType'][$key]!='Plot' && $_REQUEST['unitType'][$key]!='Commercial'
                        && $_REQUEST['unitType'][$key]!='Office' && $_REQUEST['unitType'][$key]!='Shop'
                        && $_REQUEST['unitType'][$key]!='Shop Office' && $_REQUEST['unitType'][$key]!='Other') {
                   /* if(trim($txtSize) == '' OR (!is_numeric(trim($txtSize))))
                    {
                        $ErrorMsg[$key] .= "<br>Please enter unit size";
                    }*/
                    if(trim($txtSize) <= 0 && trim($txtSize) != '')
                    {
                        $ErrorMsg[$key] .= "<br>Size should be greater than zero";
                    }
                }
                else{
					 
                  /* if(trim($txtSizeBre[$key]) == '' OR (!is_numeric(trim($txtSizeBre[$key]))))
                    {
                       if(!array_key_exists($key,$ErrorMsg))
                        $ErrorMsg[$key] .= "<br>Please enter unit size breadth of ".$_REQUEST['unitType'][$key]." in row ".$plotIncrease ;
                    }
                    else*/ if(trim($txtSizeBre[$key]) <= 0 && trim($txtSizeBre[$key]) != '')
                    { 
                        if(!array_key_exists($key,$ErrorMsg))
                        $ErrorMsg[$key] .= "<br>Size breadth should be greater than zero OR Blank of ".$_REQUEST['unitType'][$key]." in row ".$plotIncrease ;
                    }
                    
                   /* if(trim($txtSizeLen[$key]) == '' OR (!is_numeric(trim($txtSizeLen[$key]))))
                    {
                        
                        $ErrorMsg[$key] .= "<br>Please enter unit size length of ".$_REQUEST['unitType'][$key]." in row ".$plotIncrease;
                    }
                    else*/ if(trim($txtSizeLen[$key]) <= 0 && trim($txtSizeLen[$key]) != '')
                    {
                        
                        $ErrorMsg[$key] .= "<br>Size length should be greater than zero OR Blank of ".$_REQUEST['unitType'][$key]." in row ".$plotIncrease ;
                    }
                    
                    else if(trim($txtPlotArea[$key]) <= 0 && trim($txtPlotArea[$key]) != '')
                    {
                        
                        $ErrorMsg[$key] .= "<br>Plot area should be greater than zero of ".$_REQUEST['unitType'][$key]." in row ".$plotIncrease ;
                    }
                    
                   // $txtSize = 0;
                    $txtSize = $txtPlotArea[$key];                  
                   
                }
                if($unitType != 'Plot' && $unitType != 'Shop' && $unitType != 'Office' && $unitType != 'Other' && $unitType != 'Shop Office'){
                    if($bed == '')
                    {
                        $ErrorMsg[$key]	.= "<br>Please select bedroom";
                    }

                }
              /*  }
                else
                {
                    $txtSize = $txtPlotArea;
                }*/
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
                //echo "<pre>";print_r(array_unique($ErrorMsg));
            if(empty($ErrorMsg))
            {
                
                if($_REQUEST['delete'][$key] == '' && !isset($_REQUEST['delete'][$key]))
                {
                    if($_REQUEST['typeid_edit'][$key] == '')
                    {
                        $option = new ResiProjectOptions();
                        $option->project_id = $projectId;
                        $action = "insert";
                    }
                    else
                    {
                        if($txtPlotArea[$key] != 0)
                           $txtPlotArea[$key] = $txtSize[$key];
                        $option_id = $_REQUEST['typeid_edit'][$key];
                        $option = ResiProjectOptions::find($option_id);
                        $action = "update";
                    }
                   if($_REQUEST['unitType'][$key]=='Office')
                       $unitType = 'Office';
                   elseif($_REQUEST['unitType'][$key]=='Shop')
                       $unitType = 'Shop';
                   elseif($_REQUEST['unitType'][$key]=='Shop Office')
                       $unitType = $txtUnitName;
                   elseif($_REQUEST['unitType'][$key]=='Other')
                       $unitType = 'Other';
                    $option->option_type = $unitType;
                    $option->option_name = $txtUnitName;
                    if($txtSize == '' || $txtSize == 0)$txtSize = null;
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
                    $option->length_of_plot = (is_numeric($txtSizeLen[$key]))?(int)$txtSizeLen[$key]:NULL;
                    $option->breadth_of_plot = (is_numeric($txtSizeBre[$key]))?(int)$txtSizeBre[$key]:NULL;
                    $option->updated_by = $_SESSION["adminId"];
                    $option->display_carpet_area = $txtCarpetAreaInfo;					
		   /* $optionTxt = $option->bedrooms."-".$option->bathrooms."-".$option->option_name."-".$option->size;
                    foreach ($option_txt_array as $key1 => $value1) {
                        if($key!=$key1 && $optionTxt==$value1){
                           $ErrorMsg1 = 'Duplicate Option!';//die();
                            $tmparr = array();
                            $tmparr['key']=$key1;
                            $tmparr['dupkey']=$key;
                            $tmparr['error']='Duplicate Option!';
                           
                            array_push($ErrorMsg2, $tmparr);
                        }
                    }*/

                    if(empty($ErrorMsg2)){
					 // $option_txt_array[] = $optionTxt;
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
                    //apartment type add
                         $apartmentsType = TableAttributes::find('all',array('conditions' => array('table_id' => $option->options_id, 'attribute_name' => 'APARTMENTS_TYPE', 'table_name' => 'resi_project_options' )));
                         if(!$apartmentsType && $apartmentType != ''){
                          //add mode by dataEntry
                                 $apartmentsType = new TableAttributes();
                                 $apartmentsType->table_name = 'resi_project_options';
                                 $apartmentsType->table_id = $option->options_id;
                                 $apartmentsType->attribute_name = 'APARTMENTS_TYPE';
                                 $apartmentsType->attribute_value = $apartmentType;
                                 $apartmentsType->updated_by = $_SESSION['adminId'];
                                 $apartmentsType->save();	 
                         }else{
                             //echo $apartmentType[0]->id."=".$apartmentType."<br>";
                                $apartmentsType = TableAttributes::find($apartmentType[0]->id);
                                $apartmentsType->updated_by = $_SESSION['adminId'];
                                $apartmentsType->attribute_value = $apartmentType;
                                $apartmentsType->save();		
                        }						
		    }
                    
                }
                else
                {
					$list_option_id = $_REQUEST['typeid_edit'][$key]; 
					
                                        if($_REQUEST['unitType'][$key]=='Office')
                                            $unitType = 'Office';
                                        elseif($_REQUEST['unitType'][$key]=='Shop')
                                            $unitType = 'Shop';
                                        elseif($_REQUEST['unitType'][$key]=='Shop Office')
                                            $unitType = $txtUnitName;
                                        elseif($_REQUEST['unitType'][$key]=='Other')
                                            $unitType = 'Other';
                                         ############## Transaction Start##############
					 ResiProject::transaction(function(){
						
						global $list_option_id,$projectId,$flg_delete,$ErrorMsg1,$bed,$unitType;
						
						if($unitType == 'Plot' || $unitType == 'Office' || $unitType == 'Shop')
							$bed = 0;
																		
						       $flag = 0;
						try{						
										
						$actual_listing = Listings::find_by_sql("SELECT lst.id from ".LISTINGS." lst left join ".RESI_PROJECT_PHASE." rpp on lst.phase_id = rpp.phase_id where lst.option_id = ".$list_option_id." and phase_type = 'Actual' 
											and lst.status = 'Active' and rpp.version = 'Cms' and lst.listing_category='Primary'");											
						if(!$actual_listing){
							Listings::update_all(array('set' => 'status = "Inactive"','conditions' => array('option_id' => $list_option_id, 'listing_category' => 'Primary')));
						}						
						
						//fetch all the options of bedrooms----------
						$all_bed_options = ResiProjectOptions::find('all',array('conditions'=>array('bedrooms'=>$bed,'project_id'=>$projectId,'option_category'=>'Actual','option_type'=>$unitType)));
																								
						$all_options = array();
						if($all_bed_options){
						  foreach($all_bed_options as $key => $val){
							  $all_options[] = $val->options_id;
						  }
						  $all_options = implode(",",$all_options);
						  $all_active_listing = Listings::find('all',array('conditions'=>array("option_id in ($all_options) and status='Active' and listing_category='Primary'")));
						  if(!$all_active_listing){
							  $log_option_ids = '';
							  //now inactive the logical
							  $logical_bed_options = ResiProjectOptions::find('all',array('conditions'=>array('bedrooms'=>$bed,'project_id'=>$projectId,'option_category'=>'Logical','option_type'=>$unitType)));
							  $log_option_ids = $logical_bed_options[0]->id;
							 							  
							  //deleting supplies
							  if($log_option_ids){
								  $log_lst_ids = Listings::find('all',array('conditions'=>array("option_id in ($log_option_ids)")));	
								  $all_log_lst_ids = array();
													
								  foreach($log_lst_ids as $k => $v){
										$all_log_lst_ids[] = $v->id;
								  }
								  $all_log_lst_ids = implode(",",$all_log_lst_ids);
															 
								  $all_log_supplies = mysql_query("select * from project_supplies where listing_id in (".$all_log_lst_ids.")");

								  if(mysql_num_rows($all_log_supplies)){
									  $log_supplies = array();
									  while($val = mysql_fetch_object($all_log_supplies)){
										$log_supplies[] = $val->id;
									  }
									  $log_supplies = implode(",",$log_supplies);
																		 
									 ProjectAvailability::delete_all(array('conditions'=>array("project_supply_id in (".$log_supplies.")")));
									 ProjectSupply::delete_all(array('conditions'=>array("id in (".$log_supplies.")"))); 
									  
								  }
								 
								  Listings::delete_all(array('conditions'=>array("option_id in ($log_option_ids)")));
								
								  ResiProjectOptions::delete_all(array('conditions' => array('options_id = ? and project_id = ?', $log_option_ids,$projectId)));		
							  }
							 }
						}
		
						$list_id_res = mysql_query("SELECT lst.id from ".LISTINGS." lst left join ".RESI_PROJECT_PHASE." rpp on lst.phase_id = rpp.phase_id where lst.option_id = ".$list_option_id." and lst.listing_category='Primary' and (rpp.phase_type = 'Logical' or rpp.status = 'Inactive' or lst.status = 'Inactive') and rpp.version = 'Cms'");
						
						$all_listings = array();
						while($list_id = mysql_fetch_object($list_id_res)){
							$all_listings[] = $list_id->id;
						}

						if(count($all_listings) > 0){
							$all_listings = implode(",",$all_listings);
							Listings::delete_all(array('conditions'=>array("id in ($all_listings)")));												

						}
																	
						ResiProjOptionsRoomSize::delete_all(array('conditions' => array('options_id' => $list_option_id)));	
						
						ResiProjectOptions::delete_all(array('conditions' => array('options_id = ? and project_id = ?', $list_option_id,$projectId)));
                                                
						}catch(Exception $e)
						{
							if(strstr($e, 'listing_prices_fk_1'))
							  $ErrorMsg1 = "Couuld not delete! Prices exists for the config($txtUnitName).";
							elseif(strstr($e, 'listings_fk_1'))
							  $ErrorMsg1 = "Couuld not delete! Mapping exists for the config($txtUnitName).";
							else
							  $ErrorMsg1 = 'Couuld not delete!';							
							return false;
						}							
					});					
					############## Transaction End ##############							  
					
                }
                
            }  

        }
         $plotIncrease++;
    }

    //if(($flgins == 0) && (count($_REQUEST['bed']) == 15))
    if($flgins == 0)
    {
        $ErrorMsg1 = 'Please select atleast one unit name';
    }
    
    
    if(empty($ErrorMsg) && empty($ErrorMsg2) && $ErrorMsg1 == '')
    {
		updateD_Availablitiy($projectId); // update D_availability 
		
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
    }elseif(!empty($ErrorMsg1)){
	   //header("Location:add_apartmentConfiguration.php?projectId=".$projectId."&edit=edit&error1=1");	
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
  if($_REQUEST['error1'] == 1 && empty($ErrorMsg) && empty($ErrorMsg2)){
     $ErrorMsg1 = "Could not delete!";
}

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
