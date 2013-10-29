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

    if (isset($_POST['btnSave'])) {
                    $txtCityName			=	trim($_POST['txtCityName']);
                    $txtMetaTitle			=	trim($_POST['txtMetaTitle']);
                    $txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
                    $txtMetaDescription		=	trim($_POST['txtMetaDescription']);
                    $status					=	trim($_POST['status']);
                    $desc					=	trim($_POST['desc']);	
                    $old_sub_url			=	trim($_POST['old_sub_url']);
                    $old_sub_name			=	trim($_POST['old_sub_name']);

                    $smarty->assign("txtCityName", $txtCityName);
                    $smarty->assign("txtMetaTitle", $txtMetaTitle);
                    $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
                    $smarty->assign("txtMetaDescription", $txtMetaDescription);
                    $smarty->assign("status", $status);
                    $smarty->assign("desc", $desc);
                    $smarty->assign("txtCityUrl", $txtCityUrl);
                    $smarty->assign("old_sub_url", $old_sub_url);

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
                            DESCRIPTION	=	'".$desc."'
                            WHERE SUBURB_ID ='".$suburbid."'";

                            mysql_query($updateQry);
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
                    }
                    else
                    {
                            $smarty->assign("ErrorMsg", $ErrorMsg);
                    }
            }	

    else if($suburbid!=''){

            $localityDetailsArray =   ViewSuburbDetails($suburbid);
            $getSeoData           =   SeoData::getSeoData($suburbid, 'suburb');
            $txtCityName	  =	trim($localityDetailsArray['LABEL']);
            $txtMetaTitle	  =	$getSeoData[0]->meta_title;
            $txtMetaKeywords	  =	$getSeoData[0]->meta_keywords;
            $txtMetaDescription	  =	$getSeoData[0]->meta_description;
            $txtCityName	  =	trim($localityDetailsArray['LABEL']);
            $status		  =	trim($localityDetailsArray['status']);
            $desc		  =	trim($localityDetailsArray['DESCRIPTION']);

            $smarty->assign("txtCityName", $txtCityName);
            $smarty->assign("txtMetaTitle", $txtMetaTitle);
            $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
            $smarty->assign("txtMetaDescription", $txtMetaDescription);
            $smarty->assign("status", $status);	
            $smarty->assign("desc", $desc);
    }
 
?>
