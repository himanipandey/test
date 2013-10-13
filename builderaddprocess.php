
<?php
include_once('./function/locality_functions.php');
    $accessBuilder = '';
    //if( $builderAuth == false )
     //  $accessBuilder = "No Access";
    $smarty->assign("accessBuilder",$accessBuilder);
    
    $builderid = $_REQUEST['builderid'];

include("ftp.new.php");
$watermark_path = 'images/pt_shadow1.png';
//echo $_REQUEST['suburb'];die("here");
$smarty->assign("builderid", $builderid);
$ProjectList = project_list($builderid);
$smarty->assign("ProjectList", $ProjectList);
if ($_POST['btnExit'] == "Exit")
{
	header("Location:BuilderList.php");
}
if ($_POST['btnSave'] == "Save")
{
	$txtBuilderName			=	trim($_POST['txtBuilderName']);
        $legalEntity			=	trim($_POST['legalEntity']);
	$txtBuilderDescription          =	trim($_POST['txtBuilderDescription']);
	$txtBuilderUrl			=	'';
	$txtBuilderUrlOld		=	trim($_POST['txtBuilderUrlOld']);
	$DisplayOrder			=	trim($_POST['DisplayOrder']);
	$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
	$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
	$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
        $img				=	trim($_POST['img']);
        $oldbuilder			=	trim($_POST['oldbuilder']);
        $imgedit			=	trim($_POST['imgedit']);
        $address			=	trim($_POST['address']);
        $city				=	trim($_POST['city']);
        $pincode			=	trim($_POST['pincode']);
        $ceo				=	trim($_POST['ceo']);
        $employee			=	trim($_POST['employee']);
	$established			=	trim($_POST['established']);

	$employee			=	trim($_POST['employee']);
	$delivered_project		=	trim($_POST['delivered_project']);
	$area_delivered			=	trim($_POST['area_delivered']);
	$ongoing_project		=	trim($_POST['ongoing_project']);
	$website			=	trim($_POST['website']);
	$revenue			=	trim($_POST['revenue']);
	$debt				=	trim($_POST['debt']);
	
	$smarty->assign("txtBuilderName", $txtBuilderName);
        $smarty->assign("legalEntity", $legalEntity);
	$smarty->assign("txtBuilderDescription", $txtBuilderDescription);
	$smarty->assign("txtBuilderUrl", $txtBuilderUrl);
	$smarty->assign("txtBuilderUrlOld", $txtBuilderUrlOld);
	$smarty->assign("DisplayOrder", $DisplayOrder);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);	
	$smarty->assign("img", $img);	
	$smarty->assign("oldval", $oldbuilder);	
	$smarty->assign("imgedit", $imgedit);	
	$smarty->assign("address", $address);	
	$smarty->assign("city", $city);	
	$smarty->assign("pincode", $pincode);	
	$smarty->assign("ceo", $ceo);	
	$smarty->assign("employee", $employee);
	$smarty->assign("established", $established);
	$smarty->assign("employee", $employee);	
	$smarty->assign("delivered_project", $delivered_project);	
	$smarty->assign("area_delivered", $area_delivered);	
	$smarty->assign("ongoing_project", $ongoing_project);	
	$smarty->assign("website", $website);	
	$smarty->assign("revenue", $revenue);
	$smarty->assign("debt", $debt);

	if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtBuilderName)){
		$ErrorMsg["txtBuilderName"] = "Special characters are not allowed";
	 }
	
        if( $txtBuilderName == '') 
	   {
	     $ErrorMsg["txtBuilderName"] = "Please enter Builder name.";
	   }
         if( $legalEntity == '') 
	   {
	     $ErrorMsg["legalEntity"] = "Please enter legal entity name.";
	   }
	if( $txtBuilderDescription == '') 
	   {
	     $ErrorMsg["txtBuilderDescription"] = "Please enter Builder description.";
	   }   

	if( $DisplayOrder == '') 
	   {
	     $ErrorMsg["DisplayOrder"] = "Please enter Builder Display Order.";
	   }
	if( $txtMetaTitle == '') 
	   {
	     $ErrorMsg["txtMetaTitle"] = "Please enter Builder meta title.";
	   }
	if( $txtMetaKeywords == '') 
	   {
	     $ErrorMsg["txtMetaKeywords"] = "Please enter Builder meta keywords.";
	   }
	if( $txtMetaDescription == '') 
	   {
	     $ErrorMsg["txtMetaDescription"] = "Please enter Builder meta description.";
	   } 
	    /******code for builder url already exists******/
        $bldrURL = "";
        if($builderid != ''){
            $bldrURL = " and BUILDER_ID!=".$builderid;
        }
	    $qryUrl = "SELECT * FROM ".RESI_BUILDER." WHERE BUILDER_NAME = '".$txtBuilderName."'".$bldrURL;
	    $resUrl = mysql_query($qryUrl) or die(mysql_error());
	    if(mysql_num_rows($resUrl)>0){
	 	    $ErrorMsg["txtBuilderName"] = "This Builder already exists.";
	    }
	    /******end code for builder url already exists******/
	//  die; 
	if($_FILES['txtBuilderImg']['type'] != '')
            {
                if(!in_array(strtolower($_FILES['txtBuilderImg']['type']), $arrImg))
                {
                   $ErrorMsg["ImgError"] = "You can upload only jpg / jpeg gif png images.";
                } 
            }  

                    /*******code for no of contacts*******/
            $contactArr	=	array();
            foreach($_REQUEST['contact_name'] as $k=>$v)
            {
                $contactArr['Name'][] = $v;
                if($_REQUEST['contact_ph'][$k] !='')
                        $contactArr['Phone'][] = $_REQUEST['contact_ph'][$k];
                if($_REQUEST['contact_email'][$k] !='')
                        $contactArr['Email'][] = $_REQUEST['contact_email'][$k];

                $key = "projects_".($k+1);
                $contactArr['Projects'][] = implode($_REQUEST[$key],"#");

            }
            /**code for duplicate builder name or entity name***/
            if($builderid == '' && $txtBuilderName != '' && $legalEntity != '') {
                $qryBuilder = "SELECT * FROM ".RESI_BUILDER." 
                                WHERE
                                    BUILDER_NAME = '".$txtBuilderName."' OR ENTITY = '".$legalEntity."'";
                $resBuilder = mysql_query($qryBuilder);
                $dataBuilder = mysql_fetch_assoc($resBuilder);

                if(count($dataBuilder)>0) {
                    if($txtBuilderName == $dataBuilder['BUILDER_NAME'])
                         $ErrorMsg["txtBuilderName"] = "This builder already exists.";
                    if($legalEntity == $dataBuilder['ENTITY'])
                         $ErrorMsg["legalEntity"] = "This entity already exists.";
                }
            }
            /**code for duplicate builder name or entity name***/

            if(is_array($ErrorMsg)) {
                    // Do Nothing
            } 	
            else if ($builderid == '')
            {		
                    if (($_FILES["txtBuilderImg"]["type"]))
                    {   

                       $name    =	$_FILES["txtBuilderImg"]["name"];
                       if(!preg_match('/^[a-zA-Z0-9\-_\.]+$/',$name)){
                            $ErrorMsg['ImgError']='Only letters [A-Z, a-z, 0-9, -, ., _] are allowed';
                       }else{
                            $foldername		=	str_replace(' ','-',strtolower($txtBuilderName));
                            $createFolder	=	 $newImagePath.$foldername;
                            mkdir($createFolder, 0777);
                            $imgdestpath = $createFolder."/" . $name;
                            $return 	= move_uploaded_file($_FILES["txtBuilderImg"]["tmp_name"], $imgdestpath);
                           $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                           $s3upload->upload();
                            if($return)
                            {				
                                $imgurl	=   "/".$foldername."/".$name;
                                $newBuilderId = InsertBuilder($txtBuilderName, $legalEntity, $txtBuilderDescription, $txtBuilderUrl,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgurl,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr);		
                                $txtBuilderUrl = createBuilderURL($txtBuilderName, $newBuilderId);
                                $updateQuery = 'UPDATE '.RESI_BUILDER.' set URL="'.$txtBuilderUrl.'" WHERE BUILDER_ID='.$newBuilderId;
                                mysql_query($updateQuery) or die(mysql_error());

                                $createFolder = $newImagePath.$foldername;
                                if ($handle = opendir($createFolder))
                                {
                                    rewinddir($handle);
                                    while (false !== ($file = readdir($handle)))
                                    {
                                        /************Working for large***********************/

                                        if(strstr($file,$_FILES["txtBuilderImg"]["name"]))
                                        {
                                            $image = new SimpleImage();
                                            $path	=	$createFolder."/".$file;
                                            $image->load($path);

                                            /************Working for large Img Backup***********************/
                                            $image->load($path);

                                            $image->resize(477,247);

                                            $imgdestpath = $createFolder."/". str_replace('.jpg','-rect.jpg',$file);
                                            $image->save($imgdestpath);
                                            $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
                                            /************Resize and large to small*************/
                                            $image->resize(95,65);
                                            $newimg	=	str_replace('.jpg','-sm-rect.jpg',$file);
                                            $image->save($createFolder."/".$newimg);

                                            $image->resize(80,36);
                                            $newimg	=	str_replace('.jpg','-thumb.jpg',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
                                            $image->save($imgdestpath);
                                            $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
                                            /**********Working for watermark*******************/
                                            // Image path
                                                    $image_path = $createFolder."/".$file;
                                                    // Where to save watermarked image
                                                    $imgdestpath = $createFolder."/".$file;
                                             // Watermark image
                                            $img = new Zubrag_watermark($image_path);
                                            $img->ApplyWatermark($watermark_path);
                                            $img->SaveAsFile($imgdestpath);
                                            $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
                                            $img->Free();
                                        }	
                                    } 

                                    header("Location:BuilderList.php");
                                }	
                            }	
                            else 
                            {
                                $ErrorMsg['ImgError'] = "Problem in image upload";
                            }
                        }
                    }
                    else {
                            $ErrorMsg['ImgError'] = "Please insert image";
                    }	
            }
            else
            {

                $name	=	$_FILES["txtBuilderImg"]["name"];
                $cutpath	=	explode("/",$imgedit);
                $newfold	=	$newImagePath.$cutpath[1];

                if (($_FILES["txtBuilderImg"]["type"]))
                {
                        $imgdestpath = $newfold."/" . $name;
                        $return  =	 move_uploaded_file($_FILES["txtBuilderImg"]["tmp_name"], $imgdestpath);
                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                        $s3upload->upload();
                        if($return)
                        {				
                            $imgurl = "/".$cutpath[1]."/".$name;
                            $newBuilderId = InsertBuilder($txtBuilderName, $legalEntity, $txtBuilderDescription, $txtBuilderUrl,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgurl,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr);			
                            $txtBuilderUrl = createBuilderURL($txtBuilderName, $newBuilderId);
                            $updateQuery = 'UPDATE '.RESI_BUILDER.' set URL="'.$txtBuilderUrl.'" WHERE BUILDER_ID='.$newBuilderId;
                            mysql_query($updateQuery) or die(mysql_error());

                            $createFolder = $newImagePath.$cutpath[1];//die
                            if ($handle = opendir($createFolder))
                            {
                                rewinddir($handle);
                                while (false !== ($file = readdir($handle)))
                                {
                                    /************Working for large***********************/
                                    if(strstr($file,$_FILES["txtBuilderImg"]["name"]))
                                    {
                                        $image = new SimpleImage();
                                        $path = $createFolder."/".$file;
                                        $image->load($path);

                                        /************Working for large Img Backup***********************/
                                        $image->resize(477,247);
                                        $imgdestpath = $newImagePath.$cutpath[1]."/". str_replace('.jpg','-rect.jpg',$file);
                                        $image->save($imgdestpath);
                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();
                                         /************Resize and large to small*************/
                                        $image->resize(95,65);
                                        $newimg	=	str_replace('.jpg','-sm-rect.jpg',$file);
                                        $imgdestpath = $newImagePath.$cutpath[1]."/".$newimg;
                                        $image->save($imgdestpath);
                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();

                                        $image->resize(80,36);
                                        $newimg	=	str_replace('.jpg','-thumb.jpg',$file);
                                        $imgdestpath = $createFolder."/".$newimg;
                                        $image->save($imgdestpath);
                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();
                                        /**********Working for watermark*******************/
                                        // Image path
                                        $image_path =$newImagePath.$cutpath[1]."/".$file;
                                        // Where to save watermarked image
                                        $imgdestpath = $newImagePath.$cutpath[1]."/".$file;
                                         // Watermark image
                                        $img = new Zubrag_watermark($image_path);
                                        $img->ApplyWatermark($watermark_path);
                                        $img->SaveAsFile($imgdestpath);
                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();
                                        $img->Free();
                                    }	
                                }	 		
                                header("Location:BuilderList.php");
                            }	
                        }	
                        else 
                        {
                           $ErrorMsg['img'] = "Problem in image upload";
                        }
                }
                else 
                {
                    $txtBuilderUrl = createBuilderURL($txtBuilderName, $builderid);
                    $return = UpdateBuilder($txtBuilderName, $legalEntity, $txtBuilderDescription, $txtBuilderUrl,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgedit,$builderid,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr,$oldbuilder);
                    if($return)
                    {
                       header("Location:BuilderList.php?page=1&sort=all");
                    }
                    else
                        $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";	
                }

            }
            $smarty->assign("ErrorMsg", $ErrorMsg);
    }
    else if($builderid	!= '')
    {
            $qryedit	=	"SELECT * FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$builderid."'";
            $resedit	=	mysql_query($qryedit);
            $dataedit	=	mysql_fetch_array($resedit);

            $smarty->assign("txtBuilderName", $dataedit['BUILDER_NAME']);
            $smarty->assign("oldval", $dataedit['BUILDER_NAME']);
            $smarty->assign("legalEntity", $dataedit['ENTITY']);
            $smarty->assign("txtBuilderDescription", $dataedit['DESCRIPTION']);
            $smarty->assign("txtBuilderUrl", $dataedit['URL']);
            $smarty->assign("txtBuilderUrlOld", $dataedit['URL']);
            $smarty->assign("DisplayOrder", $dataedit['DISPLAY_ORDER'] ? $dataedit['DISPLAY_ORDER'] : 100);
            $smarty->assign("txtMetaTitle", $dataedit['META_TITLE']);
            $smarty->assign("txtMetaKeywords", $dataedit['META_KEYWORDS']);
            $smarty->assign("txtMetaDescription", $dataedit['META_DESCRIPTION']);
            $smarty->assign("img", $dataedit['BUILDER_IMAGE']);
            $smarty->assign("imgedit", $dataedit['BUILDER_IMAGE']);
            $smarty->assign("oldval", $dataedit['BUILDER_NAME']);
            $smarty->assign("address", $dataedit['ADDRESS']);
            $smarty->assign("city", $dataedit['CITY']);
            $smarty->assign("pincode", $dataedit['PINCODE']);
            $smarty->assign("ceo", $dataedit['CEO_MD_NAME']);
            $smarty->assign("employee", $dataedit['TOTAL_NO_OF_EMPL']);
            $smarty->assign("established", $dataedit['ESTABLISHED_DATE']);

            $smarty->assign("delivered_project", $dataedit['TOTAL_NO_OF_DELIVERED_PROJECT']);
            $smarty->assign("area_delivered", $dataedit['AREA_DELIVERED']);
            $smarty->assign("ongoing_project", $dataedit['ONGOING_PROJECTS']);
            $smarty->assign("website", $dataedit['WEBSITE']);
            $smarty->assign("revenue", $dataedit['REVENUE']);
            $smarty->assign("debt", $dataedit['DEBT']);

            $arrContact = BuilderContactInfo($builderid);
            $smarty->assign("Contact", count($arrContact));
            $smarty->assign("arrContact", $arrContact);

    }
    else {
        $smarty->assign("DisplayOrder", 100);
    }	


     /*****************City Data************/
            $CityDataArr	=	array();

            $qry	=	"SELECT CITY_ID,LABEL FROM ".CITY." WHERE ACTIVE = 1 ORDER BY LABEL ASC";
            $res = mysql_query($qry,$db);

            while($data	=	mysql_fetch_array($res))
            {
                    $CityDataArr[]	=	$data;		
            }
            $smarty->assign("CityDataArr", $CityDataArr);


       /***************Project dropdown*************/
            $Project	=	array();
            $qry	=	"SELECT PROJECT_ID,PROJECT_NAME FROM ".PROJECT." ORDER BY PROJECT_NAME ASC";
            $res	=	mysql_query($qry);

            while ($dataArr = mysql_fetch_array($res))
            {
                    array_push($Project, $dataArr);
            }
            $smarty->assign("Project", $Project);

            /*****************Builder Data************/
     $BuilderDataArr = array();
     $qry	=	"SELECT BUILDER_ID,BUILDER_NAME FROM ".RESI_BUILDER." ORDER BY BUILDER_NAME ASC";
     $res = mysql_query($qry,$db);
     while($data	=	mysql_fetch_array($res))
     {
            $BuilderDataArr[]	=	$data;		
     }
     $smarty->assign("BuilderDataArr", $BuilderDataArr);




      /*****************City Data************/
     $CityDataArr	=	array();
     $qry	=	"SELECT CITY_ID,LABEL FROM ".CITY;
     $res = mysql_query($qry,$db);
     while($data	=	mysql_fetch_array($res))
     {
            $CityDataArr[]	=	$data;		
     }
     $smarty->assign("CityDataArr", $CityDataArr);
?>
