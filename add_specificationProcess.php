<?php
        $projectId  = $_REQUEST['projectId'];
	$AmenitiesArr = AmenitiesList();
        $resProjectAmenityId = findHouseClubAmenityId($projectId);
        $attr_temp= TableAttributes::find(array('conditions'=>array('table_name'=>'resi_project_amenities','table_id'=>$resProjectAmenityId,'attribute_name'=>'CLUB_HOUSE_AREA')));
        
        $smarty->assign('clubHouseArea', $attr_temp->attribute_value);
	$smarty->assign("AmenitiesArr",$AmenitiesArr);

	/*************************************/
	$sourcepath=array();
	$destinationpath=array();
	$flag=0;
	$projectFolderCreated=0;
	$projectId  = $_REQUEST['projectId'];
	$edit = $_REQUEST['edit'];
	if($edit != '')
	{
            $ProjectAmenities = ProjectAmenities($projectId,$arrNotninty,$arrDetail,$arrninty);
            $project = ResiProject::virtual_find($projectId,array('get_extra_scope'=>true));
            $smarty->assign("arrSpecification", $arrSpecification);
            $smarty->assign("arrNotninty", $arrNotninty);
            $smarty->assign("arrninty", $arrninty);
            $smarty->assign("edit_project", $projectId);
           // echo "<pre>";print_r($arrNotninty);
	}
	else $smarty->assign("edit_project", '');
    
        $smarty->assign("projectId", $projectId);
        $project = ResiProject::virtual_find($projectId,array('get_extra_scope'=>true));
        $projectDetail = $project->to_custom_array();
        $arrSpecification = (array)$projectDetail;
        $smarty->assign("arrSpecification", $arrSpecification);
        $builderDetail = fetch_builderDetail($projectDetail['BUILDER_ID']);
        $smarty->assign("projectDetail", $projectDetail);
        $smarty->assign("builderDetail", $builderDetail);

        $preview = $_REQUEST['preview'];
        $smarty->assign("preview", $preview);
      // echo "<pre>";print_r($_REQUEST);//die;
        if (isset($_POST['btnSave']))
        {
                
                $qryIns_one = "INSERT INTO ".RESI_PROJECT_AMENITIES." (PROJECT_ID,AMENITY_DISPLAY_NAME,AMENITY_ID) VALUES ";	
                $qryIns = '';
                $ErrMsg     = '';
                $ErrMsg1     = '';
                $ErrMsg2     = '';
                $qryUpdateAmnt = array();
                $newArr = array();
                $AmenityListngFlg = 0;
                $amenityToDel = array();
               // echo "<pre>";print_r($_REQUEST);
               // echo "<pre>";print_r($arrNotninty);//die;
                foreach($_REQUEST as $key=>$val)
                {
                    if(strstr($key,'#'))
                    {	
                        if($val != 0)
                        {
                            $amenity_name = '';
                            $amenity_id   = '';

                            $exp = explode("#",$key);
                            $amenity_name = $exp[0];
                            $amenity_id   = $exp[1];

                            $key_display = "display_name_".$amenity_id;
                            if(array_key_exists($key_display,$_REQUEST))
                            {
                                if($_REQUEST[$key_display][0] != '')
                                {  
                                        $amntChk = amenityCheck($projectId, $amenity_id);
                                        if($amntChk == true ){
                                            $qryUpdateAmnt[] = "update resi_project_amenities set amenity_display_name = '".str_replace("_"," ",addslashes($_REQUEST[$key_display][0]))."' where project_id = $projectId and amenity_id = $amenity_id and verified = 1";
                                        }else{
                                                $qryIns .= "('".$projectId."','".str_replace("_"," ",addslashes($_REQUEST[$key_display][0]))."','".$amenity_id."'),";
                                        }
                                }
                                else
                                {
                                    $amntChk = amenityCheck($projectId, $amenity_id);
                                    if($amntChk == true){ 
                                        $qryUpdateAmnt[] = "update resi_project_amenities set amenity_display_name = '".str_replace("_"," ",addslashes($amenity_name))."' where project_id = $projectId and amenity_id = $amenity_id and verified = 1";

                                     }else  {   
                                            $qryIns .= "('".$projectId."','".str_replace("_"," ",addslashes($amenity_name))."','".$amenity_id."'),";
                                     }
                                }
                            }
                        }
                    }
                }
                // echo $qryIns;
                //echo $qryUpdateAmnt;die;
                //check in listing amenity
                foreach($_REQUEST as $key=>$val)
                {
                    if(strstr($key,'#'))
                    {	
                        $exp = explode("#",$key);
                       $amenity_name = $exp[0];
                        $amenity_id   = $exp[1];
                        if($val == 0 && array_key_exists($amenity_id, $arrNotninty))
                        { 
                            $qryAmntLstng = "select a.id from resi_project_amenities a join listing_amenities l
                                                on a.id = l.project_amenity_id where a.project_id = $projectId and a.amenity_id =$amenity_id and verified = 1";
                            $resAmntLstng = mysql_query($qryAmntLstng) or die(mysql_error());
                            $dataAmnt = mysql_fetch_assoc($resAmntLstng);
                            if($dataAmnt['id'] != ''){
                                $AmenityListngFlg = 1;
                            }else{
                                $amenityToDel[] = $amenity_id;
                            }
                        }
                    }
                 }
                    if($AmenityListngFlg == 0){
                        //delete other amenities of 99 id
                        $toDelAmenity = implode(",",$amenityToDel);
                        if(count($amenityToDel)>0)
                            $qryAnd = "  or amenity_id in($toDelAmenity)";
                        else
                            $qryAnd = '';
                            $qryDel = "delete from resi_project_amenities where project_id = $projectId and verified = 1 and (amenity_id = 99$qryAnd)";
                            $resDel = mysql_query($qryDel) or die(mysql_error()." error in listing deletion");
                        if($resDel){
                            foreach($qryUpdateAmnt as $updtQry){
                                $resUpdt = mysql_query($updtQry) or die(mysql_error()." error in update amenity");
                            }
                            foreach($_REQUEST['newAmenity'] as $key=>$val)
                            {
                                if($val != '')
                                    $qryIns .= "('".$projectId."','".addslashes(str_replace("_"," ",$val))."','99'),";
                            }
                            //echo $qryIns_one.$qryIns;die("here");
                            if($qryIns != '')
                            {
                                $qryIns = $qryIns_one.$qryIns;
                                $qryins = substr($qryIns,0,-1);
                                mysql_query($qryins) or die(mysql_error());
                            }


                            $master_bedroom_flooring =	trim($_POST['master_bedroom_flooring']);
                            $other_bedroom_flooring = trim($_POST['other_bedroom_flooring']);
                            $living_room_flooring = trim($_POST['living_room_flooring']);
                            $kitchen_flooring =	trim($_POST['kitchen_flooring']);
                            $toilets_flooring =	trim($_POST['toilets_flooring']);
                            $balcony_flooring =	trim($_POST['balcony_flooring']);
                            $interior_walls = trim($_POST['interior_walls']);
                            $exterior_walls = trim($_POST['exterior_walls']);
                            $kitchen_walls = trim($_POST['kitchen_walls']);
                            $toilets_walls = trim($_POST['toilets_walls']);
                            $kitchen_fixtures =	trim($_POST['kitchen_fixtures']);		
                            $toilets_fixtures =	trim($_POST['toilets_fixtures']);
                            $main_doors = trim($_POST['main_doors']);
                            $internal_doors = trim($_POST['internal_doors']);
                            $windows = trim($_POST['windows']);
                            $electrical_fitting	= trim($_POST['electrical_fitting']);
                            $others = trim($_POST['others']);

                            if($master_bedroom_flooring == '' && $other_bedroom_flooring == ''&& $living_room_flooring == ''&& $kitchen_flooring == ''&& $toilets_flooring == ''&& $balcony_flooring == ''&& $interior_walls == ''&& $exterior_walls == ''&& $kitchen_walls == ''&& $toilets_walls == ''&& $kitchen_fixtures == ''&& $toilets_fixtures == ''&& $main_doors == ''&& $internal_doors == ''&& $windows == ''&& $electrical_fitting == ''&& $others == '')
                            {
                               $ErrMsg2 = '2';
                            }
                            else
                            {
                                $specInsert = ResiProject::virtual_find($projectId);
                                $specInsert->FLOORING_MASTER_BEDROOM = $master_bedroom_flooring;
                                $specInsert->FLOORING_OTHER_BEDROOM = $other_bedroom_flooring;
                                $specInsert->FLOORING_LIVING_DINING = $living_room_flooring;
                                $specInsert->FLOORING_KITCHEN = $kitchen_flooring;
                                $specInsert->FLOORING_TOILETS = $toilets_flooring;
                                $specInsert->FLOORING_BALCONY = $balcony_flooring;
                                $specInsert->WALLS_INTERIOR = $interior_walls;
                                $specInsert->WALLS_EXTERIOR = $exterior_walls;
                                $specInsert->WALLS_KITCHEN = $kitchen_walls;
                                $specInsert->WALLS_TOILETS = $toilets_walls;
                                $specInsert->FITTINGS_AND_FIXTURES_KITCHEN = $kitchen_fixtures;
                                $specInsert->FITTINGS_AND_FIXTURES_TOILETS = $toilets_fixtures;
                                $specInsert->DOORS_MAIN = $main_doors;
                                $specInsert->DOORS_INTERNAL = $internal_doors;
                                $specInsert->WINDOWS = $windows;
                                $specInsert->ELECTRICAL_FITTINGS = $electrical_fitting;
                                $specInsert->OTHER_SPECIFICATIONS = $others;
                                $specInsert->project_id = $projectId;
                                $specInsert->set_attr_updated_by($_SESSION['adminId']);
                                $specInsert->virtual_save();
                            }
                        }
                    }else
                    {
                        $ErrMsg1 = 3;
                    }

                    if($ErrMsg1 == 3){
                         $ErrMsg = 'Please delete existing listing amenities';
                         $smarty->assign("ErrMsg", $ErrMsg);
                    }
                    elseif($ErrMsg1 != '' && $ErrMsg2 != '')
                    {
                            $ErrMsg = 'Please select atleast one value!';
                            $smarty->assign("ErrMsg", $ErrMsg);
                    }
                    else
                    {
                        if($_POST['btnSave'] == "Save")
                        {
                            insertclubAreaAttribute($projectId);
                            if($preview == 'true'){
                                header("Location:show_project_details.php?projectId=".$projectId);
                            }
                            else
                               header("Location:ProjectList.php?projectId=".$projectId);
                        }
                        else
                        {
                             header("Location:add_apartmentConfiguration.php?projectId=".$projectId);
                        }
                    }
        } 
        else if(isset($_POST['btnExit']))
        {
                  if($preview == 'true')
                        header("Location:show_project_details.php?projectId=".$projectId);
                  else
                        header("Location:ProjectList.php?projectId=".$projectId);
        }
        else if(isset($_POST['Skip']))
        {
                  header("Location:add_apartmentConfiguration.php?projectId=".$projectId);
        }

        function amenityCheck($projectId, $amntId){
            $qrySel = "select * from resi_project_amenities where project_id = $projectId and amenity_id = '".$amntId."' and verified = 1";
            $resSel = mysql_query($qrySel) or die(mysql_error());
            if(mysql_num_rows($resSel)>0)
                return true;
            else
                return false;

        }
        
        // @ jitendra pathak
        function insertclubAreaAttribute($projectId){
            
            $clubHouseArea = trim($_REQUEST['club_house_area']);
            $clubHouse = $_REQUEST[current(preg_grep('/^Club_House/', array_keys($_REQUEST)))];
            
            $resProjectAmenityId = findHouseClubAmenityId($projectId);
            
            TableAttributes::delete_all(array('conditions'=>array('table_name'=>'resi_project_amenities','table_id'=>$resProjectAmenityId,'attribute_name'=>'CLUB_HOUSE_AREA')));
            if($clubHouseArea && $clubHouse>0){
                $tableAttr = new TableAttributes();
                $tableAttr->table_name = 'resi_project_amenities';
                $tableAttr->table_id = $resProjectAmenityId;
                $tableAttr->attribute_name = 'CLUB_HOUSE_AREA';
                $tableAttr->attribute_value = $clubHouseArea;
                $tableAttr->updated_by = $_SESSION['adminId'];
                $tableAttr->save();
            }
        }
        function findHouseClubAmenityId($projectId){
            $qryAmenityId = "SELECT id FROM resi_project_amenities where project_id = $projectId and amenity_id =4";
            $resAmenityId = mysql_query($qryAmenityId) or die(mysql_error());
            $dataAmenityId = mysql_fetch_assoc($resAmenityId);
            if(count($dataAmenityId)>0){
                return $dataAmenityId['id'];
            }
            return FALSE;
        }


	/**************************************/
	

?>
