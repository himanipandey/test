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

                       $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL']);

                       if( $txtMetaTitle == '')   {
                            $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
                       }

                    if( $txtMetaKeywords == '')  {
                             $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
                       }
                    if( $txtMetaDescription == '')  {
                             $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
                       }
                    /*******locality url already exists**********/
                       if($localityid == '')
                       {
                                    $qryLocality = "SELECT * FROM ".LOCALITY." WHERE LABEL = '".$txtCityName."'";

                                    $res     = mysql_query($qryLocality) or die(mysql_error());
                                    if(mysql_num_rows($res)>0)
                                    {
                                            $ErrorMsg["txtCityName"] = "This Locality Already exists";
                                    }
                       }
                    /*******end locality url already exists*******/ 

                       if(!is_array($ErrorMsg))
                       {
                           $qryCity = "SELECT C.LABEL FROM locality L join city C on (C.city_id = L.city_id) where L.locality_id = $localityid";
                           $resCity = mysql_query($qryCity);
                           $dataCity = mysql_fetch_assoc($resCity);
                           mysql_free_result($resCity);
                           $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL']);

                                     $updateQry = "UPDATE ".LOCALITY." SET 

                                              LABEL		=	'".$txtCityName."',
                                              META_TITLE	=	'".$txtMetaTitle."',		
                                              META_KEYWORDS	=	'".$txtMetaKeywords."',
                                              META_DESCRIPTION	=	'".$txtMetaDescription."',
                                              ACTIVE		=	'".$status."',
                                              URL		=	'".$txtCityUrl."',
                                              DESCRIPTION	=	'".$desc."',
                                              VISIBLE_IN_CMS    = '".$visibleInCms."'
                                         WHERE
                                            LOCALITY_ID='".$localityid."'";

                                    $up = mysql_query($updateQry);
                                    if($up)
                                    {
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
            $txtCityName	  =	trim($localityDetailsArray['LABEL']);
            $txtMetaTitle	  =	trim($localityDetailsArray['META_TITLE']);
            $txtMetaKeywords	  =	trim($localityDetailsArray['META_KEYWORDS']);
            $txtMetaDescription	  =	trim($localityDetailsArray['META_DESCRIPTION']);
            $status		  =	trim($localityDetailsArray['ACTIVE']);
            $desc		  =	trim($localityDetailsArray['DESCRIPTION']);
            $visibleInCms	  =	trim($localityDetailsArray['VISIBLE_IN_CMS']);
            
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
            $localityCleaned	  =	trim($localityDetailsArray['LOCALITY_CLEANED']);

            $smarty->assign("txtCityName", $txtCityName);
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
    }
 
?>
