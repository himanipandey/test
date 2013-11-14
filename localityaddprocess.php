<?php

    $accessLocality = '';
    if( $localityAuth == false )
       $accessLocality = "No Access";
    $smarty->assign("accessLocality",$accessLocality);
    
    include_once("function/locality_functions.php");

    $localityid = $_REQUEST['localityid'];
    $smarty->assign("localityid", $localityid);

    $cityId = $_REQUEST['c'];

    if(isset($_POST['btnExit'])){
            header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
    }
    if (isset($_POST['btnSave'])) {
        //echo "<pre>";print_r($_REQUEST);die();
                    $txtCityName	=	trim($_POST['txtCityName']);
                    $txtCityUrl		=	trim($_POST['txtCityUrl']);
                    $txtMetaTitle	=	trim($_POST['txtMetaTitle']);
                    $txtMetaKeywords	=	trim($_POST['txtMetaKeywords']);
                    $txtMetaDescription	=	trim($_POST['txtMetaDescription']);
                    $status		=	trim($_POST['status']);
                    $desc		=	trim($_POST['desc']);
                    $old_loc_url	=	trim($_POST['old_loc_url']);
                    $visibleInCms	=	trim($_POST['visibleInCms']);
                    
                    $localityDetailsArray =   ViewLocalityDetails($localityid);
                    $maxLatitude = trim($localityDetailsArray['MAX_LATITUDE']);
                    $minLatitude = trim($localityDetailsArray['MAX_LATITUDE']);
                    $maxLongitude = trim($localityDetailsArray['MAX_LATITUDE']);
                    $minLongitude = trim($localityDetailsArray['MAX_LATITUDE']);
                    
                    $smarty->assign("txtCityName", $txtCityName);
                    $smarty->assign("old_loc_url", $old_loc_url);
                    $smarty->assign("txtMetaTitle", $txtMetaTitle);
                    $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
                    $smarty->assign("txtMetaDescription", $txtMetaDescription);
                    $smarty->assign("status", $status);	
                    $smarty->assign("desc", $desc);
                    $smarty->assign("visibleInCms", $visibleInCms);
                    $smarty->assign("maxLatitude", $maxLatitude);
                    $smarty->assign("minLatitude", $minLatitude);
                    $smarty->assign("maxLongitude", $maxLongitude);	
                    $smarty->assign("minLongitude", $minLongitude);

                      if( $txtCityName == '')   {
                             $ErrorMsg["txtCityName"] = "Please enter locality name.";
                       }

                       if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtCityName)){
                            $ErrorMsg["txtCityName"] = "Special characters are not allowed";
                       }

                       $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL'], $localityid, 'locality');

                       if( trim($txtMetaTitle) == '')   {
                            $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
                       }

                    if( trim($txtMetaKeywords) == '')  {
                             $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
                       }
                    if( trim($txtMetaDescription) == '')  {
                             $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
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
                        $res     = mysql_query($qryLocality) or die(mysql_error());
                        if(mysql_num_rows($res)>0){
                            $ErrorMsg["txtCityName"] = "This Locality Already exists";
                        }
 
                    /*******end locality url already exists*******/ 

                       if(!is_array($ErrorMsg))
                       {
                           $qryCity = "SELECT C.LABEL FROM locality L 
                               inner join suburb s on L.suburb_id = s.suburb_id
                               inner join city C on (C.city_id = s.city_id) 
                               where L.locality_id = $localityid";
                           $resCity = mysql_query($qryCity);
                           $dataCity = mysql_fetch_assoc($resCity);
                           mysql_free_result($resCity);
                           $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL'], $localityid, 'locality');

                              $updateQry = "UPDATE ".LOCALITY." SET 

                                              LABEL		=	'".$txtCityName."',
                                              STATUS		=	'".$status."',
                                              URL		=	'".$txtCityUrl."',
                                              DESCRIPTION	=	'".$desc."'
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
                                        if ( $txtCityName != trim( $localityDetailsArray['LABEL'] ) ) {
                                            //  locality name modified
                                            addToNameChangeLog( 'locality', $localityid, $localityDetailsArray['LABEL'], $txtCityName );
                                        }
                                            if($txtCityUrl != $old_loc_url)
                                                    insertUpdateInRedirectTbl($txtCityUrl,$old_loc_url);
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
            $txtMetaTitle	  =	$getSeoData[0]->meta_title;
            $txtMetaKeywords	  =	$getSeoData[0]->meta_keywords;
            $txtMetaDescription	  =	$getSeoData[0]->meta_description;
            $status		  =	trim($localityDetailsArray['status']);
            $desc		  =	trim($localityDetailsArray['DESCRIPTION']);
            
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
            $smarty->assign("txtMetaTitle", $txtMetaTitle);
            $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
            $smarty->assign("txtMetaDescription", $txtMetaDescription);
            $smarty->assign("status", $status);	
            $smarty->assign("desc", $desc);
            $smarty->assign("maxLatitude", $maxLatitude);
            $smarty->assign("minLatitude", $minLatitude);
            $smarty->assign("maxLongitude", $maxLongitude);	
            $smarty->assign("minLongitude", $minLongitude);
    }
 
?>
