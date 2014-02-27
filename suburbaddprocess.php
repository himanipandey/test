<?php

    $accessSuburb = '';
    if( $suburbAuth == false )
       $accessSuburb = "No Access";
    $smarty->assign("accessSuburb",$accessSuburb);
    
    include_once("function/locality_functions.php");

    $suburbid = $_REQUEST['suburbid'];
    $smarty->assign("suburbid", $suburbid);

    $cityId = $_REQUEST['c'];

    if(isset($_POST['btnExit'])){
            header("Location:suburbList.php?page=1&sort=all&citydd={$cityId}");
    }

    $localityDetailsArray =   ViewSuburbDetails($suburbid);

    if (isset($_POST['btnSave'])) {
                    $txtCityName			=	trim($_POST['txtCityName']);
                    $txtMetaTitle			=	trim($_POST['txtMetaTitle']);
                    $txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
                    $txtMetaDescription		=	trim($_POST['txtMetaDescription']);
                    $status					=	trim($_POST['status']);
                    $desc					=	trim($_POST['desc']);	
                    $old_sub_url			=	trim($_POST['old_sub_url']);
                    $old_sub_name			=	trim($_POST['old_sub_name']);
                    $parent_id              =    trim($_POST['parent_id']);
                    $parent_name              =    trim($_POST['parent_name']);
 
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

                    if(!is_array($ErrorMsg))
                    {
                        $qryCity = "SELECT C.LABEL FROM suburb S join city C on (C.city_id = S.city_id) where S.suburb_id = $suburbid";
                        $resCity = mysql_query($qryCity);
                        $dataCity = mysql_fetch_assoc($resCity);
                        mysql_free_result($resCity);
                        $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL'], $suburbid, 'suburb');

                            $updateQry = "UPDATE ".SUBURB." SET 
                            LABEL 		=	'".$txtCityName."',
                            STATUS		=	'".$status."',
                            URL		=	'".$txtCityUrl."',
                            DESCRIPTION	=	'".$desc."',
                            parent_suburb_id = '".$parent_id."'
                            WHERE SUBURB_ID ='".$suburbid."'";
echo $updateQry;
                          $update_flag = mysql_query($updateQry); 
                          if($update_flag){ 
                            $seoData['meta_title'] = $txtMetaTitle;
                            $seoData['meta_keywords'] = $txtMetaKeywords;
                            $seoData['meta_description'] = $txtMetaDescription;
                            $seoData['table_id'] = $suburbid;
                            $seoData['table_name'] = 'suburb';
                            $seoData['updated_by'] = $_SESSION['adminId'];
                            SeoData::insetUpdateSeoData($seoData);
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
            $smarty->assign("txtCityName", $txtCityName);
            $smarty->assign("txtMetaTitle", $txtMetaTitle);
            $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
            $smarty->assign("txtMetaDescription", $txtMetaDescription);
            $smarty->assign("status", $status);	
            $smarty->assign("desc", $desc);

            }


             $getLandmarkAliasesArr = getLandmarkAliases('suburb', $suburbid);
           $landmarkJson = json_encode($getLandmarkAliasesArr);
           $smarty->assign("landmarkAliases", $getLandmarkAliasesArr);
            $smarty->assign("landmarkJson", $landmarkJson);


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

    $parent_id = $localityDetailsArray['parent_suburb_id'];
    foreach ($suburbSelect as $k1 => $v1) {       
        if ($v1['id']==$parent_id) {
            $parent_name = $v1['label'];
        }
    }
    $smarty->assign("parent_id", $parent_id);
    $smarty->assign("parent_name", $parent_name);


            // hierarchy map
            $txtCityName      = trim($localityDetailsArray['LABEL']);
            $suburbarr = Array();
            $suburbarr['id'] = $suburbid;
            $suburbarr['label'] = $txtCityName;

            $suburbarr['parent_id'] = $parent_id;

            $str = json_encode(getHierArr($cityId, $suburbarr));
            //echo $str;
            
            $smarty->assign("suburb_str", $str);


    

   
 
?>
