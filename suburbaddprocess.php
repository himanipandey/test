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
                    $oldDesc				=	trim($_POST['oldDesc']);
					$content_flag			=	trim($_POST['content_flag']);			
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
            $status		  =	trim($localityDetailsArray['ACTIVE']);
            $desc		  =	trim($localityDetailsArray['DESCRIPTION']);
            $smarty->assign("txtCityName", $txtCityName);
            $smarty->assign("txtMetaTitle", $txtMetaTitle);
            $smarty->assign("txtMetaKeywords", $txtMetaKeywords);
            $smarty->assign("txtMetaDescription", $txtMetaDescription);
            $smarty->assign("status", $status);	
            $smarty->assign("desc", $desc);
            $contentFlag = TableAttributes::find('all',array('conditions' => array('table_id' => $suburbid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'suburb')));   
            
			$smarty->assign("contentFlag", $contentFlag[0]->attribute_value);
			$smarty->assign("dept", $_SESSION['DEPARTMENT']);
    }
 
?>
