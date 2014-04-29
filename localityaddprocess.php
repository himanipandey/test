<?php

    $accessLocality = '';
    if( $localityAuth == false )
       $accessLocality = "No Access";
    $smarty->assign("accessLocality",$accessLocality);
    
    include_once("function/locality_functions.php");

    $localityid = $_REQUEST['localityid'];
    $smarty->assign("localityid", $localityid);

    $cityId = $_REQUEST['c'];
    $smarty->assign("cityid", $cityId);
//echo "<pre>";
//print_r($_REQUEST);//die("herer");
    $suburbSelect = Array();
    $QueryMember = "SELECT SUBURB_ID as id, LABEL as label, parent_suburb_id FROM ".SUBURB." WHERE 
            CITY_ID ='".$cityId ."'  ORDER BY LABEL ASC";

    $QueryExecute   = mysql_query($QueryMember) or die(mysql_error());
    while ($dataArr = mysql_fetch_array($QueryExecute))
    {
       array_push($suburbSelect, $dataArr);
    }
    $smarty->assign("suburbSelect", $suburbSelect);

    $parent_id = $localityDetailsArray['parent_suburb_id'];
    foreach ($suburbSelect as $k1 => $v1) {       
        if ($v1['id']==$parent_id) {
            $parent_name = $v1['label'];
        }
    }
    $smarty->assign("parent_id", $parent_id);
    $smarty->assign("parent_name", $parent_name);

    if(isset($_POST['btnExit'])){
            header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
    }
    if (isset($_POST['btnSave'])) {
                $txtCityName = trim($_POST['txtCityName']);
                $txtCityUrl	= trim($_POST['txtCityUrl']);
                $txtMetaTitle = trim($_POST['txtMetaTitle']);
                $txtMetaKeywords = trim($_POST['txtMetaKeywords']);
                $txtMetaDescription	= trim($_POST['txtMetaDescription']);
                $status = trim($_POST['status']);
                $desc = trim($_POST['desc']);
                $oldDesc = trim($_POST['oldDesc']);
                $parent_subId = trim($_POST['parentId']);
                $content_flag = trim($_POST['content_flag']);
                $old_loc_url = trim($_POST['old_loc_url']);
                $visibleInCms = trim($_POST['visibleInCms']);
                $txtLocalityLattitude = trim($_POST['txtLocalityLattitude']);
                $txtLocalityLongitude = trim($_POST['txtLocalityLongitude']);

                $smarty->assign("txtCityName", $txtCityName);
                $smarty->assign("old_loc_url", $old_loc_url);
                $smarty->assign("txtMetaTitle", $txtMetaTitle);
                $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
                $smarty->assign("txtMetaDescription", $txtMetaDescription);
                $smarty->assign("status", $status);	
                $smarty->assign("desc", $desc);
                $smarty->assign("parent_sub_id", $parent_subId);
                $smarty->assign("visibleInCms", $visibleInCms);
                
                  if( $txtCityName == '')   {
                         $ErrorMsg["txtCityName"] = "Please enter locality name.";
                   }

                   if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtCityName)){
                        $ErrorMsg["txtCityName"] = "Special characters are not allowed";
                   }
                    $maxLatitude = '';
                    $minLatitude = '';
                    $maxLongitude = '';
                    $minLongitude = '';
                   if($localityid != '') {
                        $localityDetailsArray = ViewLocalityDetails($localityid);
                        $maxLatitude = trim($localityDetailsArray['MAX_LATITUDE']);
                        $minLatitude = trim($localityDetailsArray['MAX_LATITUDE']);
                        $maxLongitude = trim($localityDetailsArray['MAX_LATITUDE']);
                        $minLongitude = trim($localityDetailsArray['MAX_LATITUDE']);
                        $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL'], $localityid, 'locality');
                   }
                    $smarty->assign("maxLatitude", $maxLatitude);
                    $smarty->assign("minLatitude", $minLatitude);
                    $smarty->assign("maxLongitude", $maxLongitude);	
                    $smarty->assign("minLongitude", $minLongitude);
                    $smarty->assign("txtLocalityLattitude", $txtLocalityLattitude);
                    $smarty->assign("txtLocalityLongitude", $txtLocalityLongitude);
                   if( trim($txtMetaTitle) == '')   {
                     $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
                   }
                   if( trim($txtMetaKeywords) == '')  {
                     $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
                   }
                   if( trim($txtMetaDescription) == '')  {
                     $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
                   }
                   if($txtLocalityLattitude == ''){
                     $ErrorMsg["txtLattitude"] = "Lattitude can not blank";
                   }
                   if($txtLocalityLongitude == ''){
                     $ErrorMsg["txtLongitude"] = "Longitude can not blank";
                   }
                   if(!empty($txtLocalityLattitude) && ($txtLocalityLattitude <-90 || $txtLocalityLattitude>90)){
                     $ErrorMsg["txtLattitude"] = "Lattitude range should be between -90 to 90.";
                   }
                   if(!empty($txtLocalityLattitude) && !is_numeric($txtLocalityLattitude)){
                     $ErrorMsg["txtLattitude"] = "Lattitude value should be numeric.";
                   }
                   if(!empty($txtLocalityLongitude) && ($txtLocalityLongitude <-180 || $txtLocalityLongitude>180)){
                     $ErrorMsg["txtLongitude"] = "Longitude range should be between -180 to 180.";
                   }
                   if(!empty($txtLocalityLongitude) && !is_numeric($txtLocalityLongitude)){
                     $ErrorMsg["txtLongitude"] = "Lattitude value should be numeric.";
                   }
                   if($_REQUEST['parentId'] == ''){
                     $ErrorMsg["txtMetaParent"] = "Please add atleast one suburb and than select it.";
                   }
                   
                /*******locality url already exists**********/
                    $locURL = "";
                    if($localityid != ''){
                        $locURL = " and LOCALITY_ID!=".$localityid;    
                    }
                    $qryLocality = "SELECT l.* FROM ".LOCALITY." l inner join suburb s
                         on l.suburb_id = s.suburb_id
                        WHERE l.LABEL = '".$txtCityName."' 
                        and s.city_id=".$cityId.$locURL;         
                    $res = mysql_query($qryLocality) or die(mysql_error());
                    if(mysql_num_rows($res)>0){
                        $ErrorMsg["txtCityName"] = "This Locality Already exists";
                    }

                /*******end locality url already exists*******/ 
                   if(!is_array($ErrorMsg))
                   {
                       if($localityid != '') { //code for update a locality
                       echo $qryCity = "SELECT C.LABEL as cityname FROM locality L 
                            inner join suburb s on L.suburb_id = s.suburb_id
                            inner join city C on (C.city_id = s.city_id) 
                            where L.locality_id = $localityid";
                        $resCity = mysql_query($qryCity) or die(mysql_error());
                        $dataCity = mysql_fetch_assoc($resCity);
                        mysql_free_result($resCity);
                        echo $txtCityName, $dataCity['cityname'], $localityid;die;
                        $txtCityUrl = createLocalityURL($txtCityName, $dataCity['cityname'], $localityid, 'locality');
                        if($txtLocalityLattitude == '')
                             $txtLocalityLattitude = null;
                         if($txtLocalityLongitude == '')
                             $txtLocalityLongitude = null;
                           $updateQry = "UPDATE ".LOCALITY." SET 
                                           LABEL = '".$txtCityName."',
                                           STATUS = '".$status."',
                                           URL = '".$txtCityUrl."',
                                           DESCRIPTION = '".$desc."',
                                           SUBURB_ID = '".$parent_subId."',
                                           LATITUDE = '".$txtLocalityLattitude."',
                                           LONGITUDE = '".$txtLocalityLongitude."',
                                           updated_at = now()
                                      WHERE
                                         LOCALITY_ID='".$localityid."'";
                                 $up = mysql_query($updateQry);
                                 if($up)
                                 {
                                     $seoData['meta_title'] = $txtMetaTitle;
                                     $seoData['meta_keywords'] = $txtMetaKeywords;
                                     $seoData['meta_description'] = $txtMetaDescription;
                                     $seoData['table_id'] = $localityid;
                                     $seoData['table_name'] = 'locality';
                                     $seoData['updated_by'] = $_SESSION['adminId'];
                                     SeoData::insetUpdateSeoData($seoData);
                                        ## - desccripion content flag handeling
                                        $cont_flag = TableAttributes::find('all',array('conditions' => array('table_id' => $localityid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'locality' )));					   
                                        if($cont_flag){
                                                $content_flag = '';
                                                if($_SESSION['DEPARTMENT'] == 'DATAENTRY'){
                                                        if(strcasecmp($desc,$oldDesc) != 0)
                                                                $content_flag = 0;								
                                                }elseif($_SESSION['DEPARTMENT'] == 'ADMINISTRATOR'){
                                                  $content_flag = ($_POST["content_flag"])? 1 : 0;
                                                }
                                                if(is_numeric($content_flag)){
                                                        $cont_flag = TableAttributes::find($cont_flag[0]->id);
                                                        $cont_flag->updated_by = $_SESSION['adminId'];
                                                        $cont_flag->attribute_value = $content_flag;
                                                        $cont_flag->save();		
                                                }
                                        }elseif($_SESSION['DEPARTMENT'] == 'DATAENTRY' && strcasecmp($desc,$oldDesc)!= 0){
                                                $cont_flag = new TableAttributes();
                                                $cont_flag->table_name = 'locality';
                                                $cont_flag->table_id = $localityid;
                                                $cont_flag->attribute_name = 'DESC_CONTENT_FLAG';
                                                $cont_flag->attribute_value = 0;
                                                $cont_flag->updated_by = $_SESSION['adminId'];
                                                $cont_flag->save();				
                                        }
                                     if ( $txtCityName != trim( $localityDetailsArray['LABEL'] ) ) {
                                         //  locality name modified
                                         addToNameChangeLog( 'locality', $localityid, $localityDetailsArray['LABEL'], $txtCityName );
                                     }
                                         if($txtCityUrl != $old_loc_url)
                                         {
                                             $arrLocId['locality_id'] = $localityid;
                                            $projList = ResiProject::getAllSearchResult($arrLocId); //all project of a locality
                                            foreach($projList as $value) {
                                                $projUrl = createProjectURL($dataCity['cityname'],$txtCityName,$value->builder_name,$value->project_name, $value->project_id);
                                                $qryProUrl = "update resi_project set 
                                                              project_url = '".$projUrl."',updated_by = '".$_SESSION['adminId']."' where project_id = '".$value->project_id."'";
                                                $resProjUrl = mysql_query($qryProUrl) or die(mysql_error());
                                            }
                                         }      
                                             
                                         header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
                                 }
                            }
                            else{
                                //code for insert new locality
                                $qry = "INSERT INTO ".LOCALITY." (LABEL,SUBURB_ID,status,LATITUDE,LONGITUDE,DESCRIPTION,updated_by,created_at,PRIORITY)
                                      value('".$txtCityName."','".$parent_subId."','".$status."','".$txtLocalityLattitude."','".$txtLocalityLongitude."','".$desc."','".$_SESSION['adminId']."',now(),999)";
                                $res = mysql_query($qry) or die(mysql_error());
                                $locId = mysql_insert_id();
                                $cityFind = City::find($cityId);  
                                $url = createLocalityURL($txtCityName, $cityFind->label, $locId, 'locality');
                                $qry = "UPDATE ".LOCALITY." SET URL = '$url',updated_by = '".$_SESSION['adminId']."',updated_at = now()
                                  WHERE LOCALITY_ID=".$locId;
                                $res = mysql_query($qry) or die(mysql_error());
                                
                                $seoData['meta_title'] = $txtMetaTitle;
                                $seoData['meta_keywords'] = $txtMetaKeywords;
                                $seoData['meta_description'] = $txtMetaDescription;
                                $seoData['table_id'] = $locId;
                                $seoData['table_name'] = 'locality';
                                $seoData['updated_by'] = $_SESSION['adminId'];
                                SeoData::insetUpdateSeoData($seoData);
                                 header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
                            }
                }
                else
                {
                   $smarty->assign("ErrorMsg", $ErrorMsg);
                }
            }	
    elseif($localityid!=''){

            $localityDetailsArray =   ViewLocalityDetails($localityid);
            $getSeoData           =   SeoData::getSeoData($localityid, 'locality');
            $txtCityName	  =	trim($localityDetailsArray['LABEL']);
            $locUrl = trim($localityDetailsArray['URL']);
            $old_loc_url = trim($localityDetailsArray['URL']);
            $txtMetaTitle	  =	$getSeoData[0]->meta_title;
            $txtMetaKeywords	  =	$getSeoData[0]->meta_keywords;
            $txtMetaDescription	  =	$getSeoData[0]->meta_description;
            $status		  =	trim($localityDetailsArray['status']);
            $desc		  =	trim($localityDetailsArray['DESCRIPTION']);
            $parent_sub_id = trim($localityDetailsArray['SUBURB_ID']);
            //print_r($localityDetailsArray);
            $maxLatitude	  =	trim($localityDetailsArray['MAX_LATITUDE']);
            if($maxLatitude == '')
                $maxLatitude = 'No Entry';
            $minLatitude	  =	trim($localityDetailsArray['MIN_LATITUDE']);
            if($minLatitude == '')
                $minLatitude = 'No Entry';
            $maxLongitude	  =	trim($localityDetailsArray['MAX_LONGITUDE']);
            if($maxLongitude == '')
                $maxLongitude = 'No Entry';
            $minLongitude	  =	trim($localityDetailsArray['MIN_LONGITUDE']);
            if($minLongitude == '')
                $minLongitude = 'No Entry';

            $smarty->assign("txtCityName", $txtCityName);
            $smarty->assign("locUrl", $locUrl);
            $smarty->assign("old_loc_url", $old_loc_url);
            $smarty->assign("txtMetaTitle", $txtMetaTitle);
            $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
            $smarty->assign("txtMetaDescription", $txtMetaDescription);
            $smarty->assign("status", $status);	
            $smarty->assign("desc", $desc);
            $smarty->assign("maxLatitude", $maxLatitude);
            $smarty->assign("minLatitude", $minLatitude);
            $smarty->assign("maxLongitude", $maxLongitude);	
            $smarty->assign("minLongitude", $minLongitude);
            $smarty->assign("parent_sub_id", $parent_sub_id);
            $smarty->assign("txtLocalityLattitude", $localityDetailsArray['LATITUDE']);
            $smarty->assign("txtLocalityLongitude", $localityDetailsArray['LONGITUDE']);
                
           $getLandmarkAliasesArr = getLandmarkAliases('locality', $localityid);
           $landmarkJson = json_encode($getLandmarkAliasesArr);
           $smarty->assign("landmarkAliases", $getLandmarkAliasesArr);
           $smarty->assign("landmarkJson", $landmarkJson);
            // get suburb to display hierarchy
            $qry = "select s.SUBURB_ID, s.LABEL, s.parent_suburb_id from locality l 
                    inner join suburb s on l.SUBURB_ID = s.SUBURB_ID
                    WHERE l.LOCALITY_ID=$localityid";
            $res = mysql_query($qry) or die(mysql_error());
            $suburb = Array();
            while ($data = mysql_fetch_array($res))
            {
              array_push($suburb, $data);
            }
            $smarty->assign("sub_id", $suburb[0]['SUBURB_ID']);
            $smarty->assign("sub_label", $suburb[0]['LABEL']);
            $smarty->assign("sub_pid", $suburb[0]['parent_suburb_id']);

            $contentFlag = TableAttributes::find('all',array('conditions' => array('table_id' => $localityid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'locality')));   
            $smarty->assign("contentFlag", $contentFlag[0]->attribute_value);
            $smarty->assign("dept", $_SESSION['DEPARTMENT']);
    }
 
?>
