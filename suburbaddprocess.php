<?php

    $accessSuburb = '';
    if( $suburbAuth == false )
       $accessSuburb = "No Access";
    $smarty->assign("accessSuburb",$accessSuburb);
    
    include_once("function/locality_functions.php");
    $cityId = $_REQUEST['c'];
    $smarty->assign("cityId", $cityId);

    // add parent suburb
    $suburbSelect = Array();
    $QueryMember = "SELECT SUBURB_ID as id, LABEL as label, parent_suburb_id FROM ".SUBURB." WHERE 
            CITY_ID ='".$cityId ."'  ORDER BY LABEL ASC";

    $QueryExecute   = mysql_query($QueryMember) or die(mysql_error());
    while ($dataArr = mysql_fetch_array($QueryExecute))
    {
       array_push($suburbSelect, $dataArr);
    }
    $smarty->assign("suburbSelect", $suburbSelect);
//echo "<pre>";
//print_r($_REQUEST);die;
    if($_REQUEST['suburbid'] != '') {
        $suburbid = $_REQUEST['suburbid'];
        $smarty->assign("suburbid", $suburbid);    
        $localityDetailsArray = ViewSuburbDetails($suburbid);
        $parent_id = $localityDetailsArray['parent_suburb_id'];
        foreach ($suburbSelect as $k1 => $v1) {       
            if ($v1['id'] == $parent_id) {
                $parent_name = $v1['label'];
            }
        }
        $smarty->assign("parent_id", $parent_id);
        $smarty->assign("parent_name", $parent_name);
    }
    // hierarchy map
    $txtCityName = trim($localityDetailsArray['LABEL']);

    if(isset($_POST['btnExit'])){
       header("Location:suburbList.php?page=1&sort=all&citydd={$cityId}");
    }
    if (isset($_POST['btnSave'])) {
                    $txtCityName = trim($_POST['txtCityName']);
                    $txtMetaTitle = trim($_POST['txtMetaTitle']);
                    $txtMetaKeywords = trim($_POST['txtMetaKeywords']);
                    $txtMetaDescription	= trim($_POST['txtMetaDescription']);
                    $status = trim($_POST['status']);
                    $desc = trim($_POST['desc']);	
                    $oldDesc = trim($_POST['oldDesc']);
		    $content_flag = trim($_POST['content_flag']);			
                    $old_sub_url = trim($_POST['old_sub_url']);
                    $old_sub_name = trim($_POST['old_sub_name']);
                    $parent_id = trim($_POST['parentId']);
                    $parent_name = trim($_POST['parent_name']);
 
                    $smarty->assign("txtCityName", $txtCityName);
                    $smarty->assign("txtMetaTitle", $txtMetaTitle);
                    $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
                    $smarty->assign("txtMetaDescription", $txtMetaDescription);
                    $smarty->assign("status", $status);
                    $smarty->assign("desc", $desc);
                    $smarty->assign("txtCityUrl", $txtCityUrl);
                    $smarty->assign("old_sub_url", $old_sub_url);
                    $smarty->assign("parent_id", $parent_id);
                    $smarty->assign("parent_name", $parent_name);

                    if( $txtCityName == '')   {
                            $ErrorMsg["txtCityName"] = "Please enter suburb name.";
                    }

                    if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtCityName)){
                            $ErrorMsg["txtCityName"] = "Special characters are not allowed in suburb name";
                    }

                    if( $txtMetaTitle == '')   {
                             $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
                       }
                    if( $txtMetaKeywords == '')  {
                             $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
                       }
                    if( $txtMetaDescription == '')  {
                             $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
                       }
                  /*  if( $parent_id == '')  {
                             $ErrorMsg["txtMetaParent"] = "Please select parent suburb.";
                       }*/
                    if($suburbid != '') {
                        if(checkifParentIsChild($parent_id, $suburbid, $suburbSelect)){
                            $ErrorMsg["txtMetaParent"] = "Selected Parent is already a child suburb. Choose other Parent.";
                        }
                    }
                    if(!is_array($ErrorMsg))
                    {
                        if($suburbid != '') {
                            $qryCity = "SELECT C.LABEL FROM suburb S join city C on (C.city_id = S.city_id) where S.suburb_id = $suburbid";
                            $resCity = mysql_query($qryCity);
                            $dataCity = mysql_fetch_assoc($resCity);
                            mysql_free_result($resCity);
                            $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL'], $suburbid, 'suburb');

                            $updateQry = "UPDATE ".SUBURB." SET 
                            LABEL = '".$txtCityName."',
                            STATUS = '".$status."',
                            URL	= '".$txtCityUrl."',
                            DESCRIPTION	= '" . d_($desc) . "',
                            updated_at	= now(),
                            parent_suburb_id = '".$parent_id."'
                            WHERE SUBURB_ID ='".$suburbid."'";

                             $update_flag = mysql_query($updateQry);
                          if($update_flag){ 
                            $seoData['meta_title'] = $txtMetaTitle;
                            $seoData['meta_keywords'] = $txtMetaKeywords;
                            $seoData['meta_description'] = $txtMetaDescription;
                            $seoData['table_id'] = $suburbid;
                            $seoData['table_name'] = 'suburb';
                            $seoData['updated_by'] = $_SESSION['adminId'];
                            SeoData::insetUpdateSeoData($seoData);

                                ## - desccripion content flag handeling
                                $cont_flag = TableAttributes::find('all',array('conditions' => array('table_id' => $suburbid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'suburb' )));					   
                                 if($cont_flag){
                                        $content_flag = '';
                                        if($_SESSION['DEPARTMENT'] == 'DATAENTRY'){
                                            if(strcasecmp($desc,$oldDesc) != 0)
                                                $content_flag = 0;								
                                        }elseif($_SESSION['DEPARTMENT'] == 'ADMINISTRATOR' || $_SESSION['DEPARTMENT'] == 'CONTENT'){
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
                                        $cont_flag->table_name = 'suburb';
                                        $cont_flag->table_id = $suburbid;
                                        $cont_flag->attribute_name = 'DESC_CONTENT_FLAG';
                                        $cont_flag->attribute_value = 0;
                                        $cont_flag->updated_by = $_SESSION['adminId'];
                                        $cont_flag->save();				
                                }

                                if ( $old_sub_name != $txtCityName ) {
                                    //  add to name change log
                                    addToNameChangeLog( 'suburb', $suburbid, $old_sub_name, $txtCityName );
                                }
                            /*
                                if($txtCityUrl != $old_sub_url)
                                        insertUpdateInRedirectTbl($txtCityUrl,$old_sub_url);
                            //*/
                                header("Location:suburbList.php?page=1&sort=all&citydd={$cityId}");
                            }else{
                                    $ErrorMsg["txtCityName"] = "Suburb Name already exist.";
                                    $smarty->assign("ErrorMsg", $ErrorMsg);
                            }
                        }
                        else {
                            //insert new suburb
                             $qry = "INSERT INTO ".SUBURB." (LABEL,CITY_ID,status,parent_suburb_id, created_at,updated_by,DESCRIPTION) 
                                        value('".$txtCityName."','".$cityId."','".$_REQUEST['status']."','".$parent_id."', NOW(), '".$_SESSION['adminId']."','" . d_($desc) . "')";
                                     $res = mysql_query($qry) or die(mysql_error()." insert");
                                     $suburbId = mysql_insert_id();
                                     $cityName = City::find($cityId);
                                     $url = createLocalityURL($txtCityName, $cityName->label, $suburbId, 'suburb'); 
                            $seldata = "UPDATE ".SUBURB." 
                                    SET URL = '$url',updated_by = '".$_SESSION['adminId']."'
                                        WHERE SUBURB_ID='".$suburbId."'";
                            $resdata = mysql_query($seldata);
                            $c = mysql_affected_rows();
                            $seoData['meta_title'] = $txtMetaTitle;
                            $seoData['meta_keywords'] = $txtMetaKeywords;
                            $seoData['meta_description'] = $txtMetaDescription;
                            $seoData['table_id'] = $suburbId;
                            $seoData['table_name'] = 'suburb';
                            $seoData['updated_by'] = $_SESSION['adminId'];
                            SeoData::insetUpdateSeoData($seoData);
                            header("Location:suburbList.php?page=1&sort=all&citydd={$cityId}");
                        }
                    }
                    else
                    {
                            $smarty->assign("ErrorMsg", $ErrorMsg);
                    }
            }	

    else if($suburbid!=''){

            
            $getSeoData           =   SeoData::getSeoData($suburbid, 'suburb');
            $txtCityName	  =	trim($localityDetailsArray['LABEL']);
            $txtMetaTitle	  =	$getSeoData[0]->meta_title;
            $txtMetaKeywords	  =	$getSeoData[0]->meta_keywords;
            $txtMetaDescription	  =	$getSeoData[0]->meta_description;
            $txtCityName	  =	trim($localityDetailsArray['LABEL']);
            $status		  =	trim($localityDetailsArray['ACTIVE']);
            $desc		  =	trim($localityDetailsArray['DESCRIPTION']);
            $url		  =	trim($localityDetailsArray['URL']);
            $smarty->assign("txtCityName", $txtCityName);
            $smarty->assign("txtMetaTitle", $txtMetaTitle);
            $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
            $smarty->assign("txtMetaDescription", $txtMetaDescription);
            $smarty->assign("status", $status);	
            $smarty->assign("desc", $desc);
            $smarty->assign("url", $url);
            $contentFlag = TableAttributes::find('all',array('conditions' => array('table_id' => $suburbid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'suburb')));   
            
			$smarty->assign("contentFlag", $contentFlag[0]->attribute_value);
			$smarty->assign("dept", $_SESSION['DEPARTMENT']);
    }
        

     $getLandmarkAliasesArr = getLandmarkAliases('suburb', $suburbid);
           $landmarkJson = json_encode($getLandmarkAliasesArr);
           $smarty->assign("landmarkAliases", $getLandmarkAliasesArr);
            $smarty->assign("landmarkJson", $landmarkJson);


            

    


            
            
            

            //$str = json_encode(getHierArr($cityId, $suburbarr));
            //echo $str;
            
            //$smarty->assign("suburb_str", $str);
 

 function checkifParentIsChild($p_id, $c_id, $suburbSelect){
        $bool = true;
        
        while($bool){
            if ($p_id==$c_id) return true;
            $bool1 = true;
            foreach ($suburbSelect as $k => $v) {  
                
                if($v['id']==$p_id){
                    if($v['parent_suburb_id'] <1 )
                        return false;
                    else if($v['parent_suburb_id'] == $c_id){
                        return true;
                    }
                    else{
                        $p_id = $v['parent_suburb_id'];
                    }
                      $bool1 = false;
                }

            }
            if($bool1) $bool = false;

        }  
        return false; 
 }
?>
