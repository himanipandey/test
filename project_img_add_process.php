<?php

set_time_limit(0);
ini_set("memory_limit", "256M");

include("ftp.new.php");
$ErrorMsg = '';

//$projectplansid = $_REQUEST['projectplansid'];
$watermark_path = "images/pt_shadow1.png";
$projectId = $_REQUEST['projectId'];
$projectDetail = ProjectDetail($projectId);
$smarty->assign("ProjectDetail", $projectDetail);

$linkShowHide = 0;
if (isset($_REQUEST['auth'])) {
    $linkShowHide = 1;
}
$smarty->assign("linkShowHide", $linkShowHide);

$smarty->assign("imagetype", $_REQUEST['imagetype']);

$sec_image_types = ImageServiceUpload::$sec_image_types;
$sec_image_types = $sec_image_types['project']['project_image'];
$smarty->assign("sec_image_types", $sec_image_types);
//print_r($sec_image_types);


$Amenities = AmenitiesMaster::arrAmenitiesMaster();
$smarty->assign("amenities", $Amenities);


//tower dropdown
$towerDetail_object = ResiProjectTowerDetails::find("all", array("conditions" => "project_id = {$projectId}"));
$towerDetail = array();
$tower_div = "<select name= 'txtTowerId[]' id='tower_dropdown' onchange='tower_change(this)'>";
$tower_div .="<option value='Select'>--Select Tower--</option>";

foreach ($towerDetail_object as $s) {
    $s = $s->to_array();
    foreach ($s as $key => $value) {
        $s[strtoupper($key)] = $value;
        unset($s[$key]);
    }
    $tower_div .="<option value='" . $s['TOWER_ID'] . "' >" . $s['TOWER_NAME'] . "</option>";
}
if (count($towerDetail_object) < 1)
    $tower_div .= "<option value='0'>Other</option>";
$tower_div .= "</select>";
$smarty->assign("towerDetailDiv", $tower_div);

//display order
$display_order_div = "<select name='txtdisplay_order[]' id='display_order_dropdown'>";
for ($cmt = 1; $cmt <= 5; $cmt++) {
    if ($cmt == 5)
        $display_order_div .="<option value='$cmt' selected >$cmt</option>";
    else
        $display_order_div .="<option value='$cmt'>$cmt</option>";
}
$display_order_div .= "</select>";
$smarty->assign("display_order_div", $display_order_div);

$builderDetail = fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
if (isset($_REQUEST['edit']))
    $edit_project = $projectId;
else
    $edit_project = $projectId;
$smarty->assign("edit_project", $edit_project);
//echo $projectplansid = $edit_project;

$watermark_path = 'images/pt_shadow1.png';
$source = array();
$dest = array();
//$smarty->assign("projectplansid", $projectplansid);
if (isset($_POST['Next'])) {
    $smarty->assign("projectId", $projectId);
    $folderName = $projectDetail[0]['PROJECT_NAME'];

    /*     * *********Folder name********* */
    $builderNamebuild = explode("/", $builderDetail['BUILDER_IMAGE']);

    /*     * ***************************** */
    $BuilderName = $builderNamebuild[1];
    $ProjectName = str_replace(" ", "-", $projectDetail[0]['PROJECT_NAME']);

    $arrValue = array();
    $arrTitle = array();
    $arrTaggedDate = array();
    $arrTowerId = array();
    $arrDisplayOrder = array();
  
    foreach ($_FILES['txtlocationplan']['name'] as $k => $v) {
        if ($v != '') {
            if (!in_array(strtolower($_FILES['txtlocationplan']['type'][$k]), $arrImg)) {
                $ErrorMsg["ImgError"] = "You can upload only " . ucwords(implode(" / ", $arrImg)) . " images.";
            }

            $arrValue[$k] = $v;
            $arrTitle[$k] = $_REQUEST['title'][$k];

            if (isset($_REQUEST['img_date' . ($k + 1)]) && !null == $_REQUEST['img_date' . ($k + 1)]) {

                $tagged_date = substr($_REQUEST['img_date' . ($k + 1)], 0, 7);
                $arrTaggedDate[$k] = $tagged_date . "-01T00:00:00Z";

                //$arrTaggedDate[$k] = null;
            } else
                $arrTaggedDate[$k] = null;

            //echo $arrTaggedDate[$k]
            if ($_REQUEST['txtTowerId'][$k + 1] == "Select")
                $arrTowerId[$k] = null;
            else
                $arrTowerId[$k] = $_REQUEST['txtTowerId'][$k + 1];
            //echo $arrTowerId[$k]; die();
            $arrDisplayOrder[$k] = $_REQUEST['txtdisplay_order'][$k + 1];
            //die($arrTaggedDate[$k].$arrTowerId[$k]);
        }
    }

    if (count($arrValue) == 0) {
        $ErrorMsg["blankerror"] = "Please select atleast one image.";
    } else if ($projectId == '') {
        $ErrorMsg["projectId"] = "Please select Project name.";
    } else if ($_REQUEST['PType'] == '') {
        $ErrorMsg["ptype"] = "Please select project type.";
    } else if (!array_filter($_REQUEST['title'])) {
        $ErrorMsg["ptype"] = "Please enter Image Title.";
    }

    //validations for tagged months
    if ($_REQUEST['PType'] == 'Construction Status') {
        $count = 1;
        while ($count <= $_REQUEST['img']) {
            if ($_REQUEST['img_date' . $count] == '')
                $ErrorMsg["ptype"] = "Please enter Tagged Date.";
            $count++;
        }
    }
    //print_r($_REQUEST['txtTowerId']); die();
    if ($_REQUEST['PType'] == 'Cluster Plan') {
        $count = 1;
        while ($count <= $_REQUEST['img']) {

            if ($_REQUEST['txtTowerId'][$count] == "Select" || $_REQUEST['txtTowerId'][$count] < 0) {
                $ErrorMsg["ptype"] = "Please select a Tower for every Cluster Plan.";
            }
            $count++;
        }
    }

    //checking uniqness display order of elevation images
    if ($_REQUEST['PType'] == 'Elevation' || $_REQUEST['PType'] == 'Amenities' || $_REQUEST['PType'] == 'Main Other') {
        $count = 1;
        $temp_arr = array();

        while ($count <= $_REQUEST['img']) {

            if (trim($_REQUEST['txtdisplay_order'][$count]) == '') {
                $ErrorMsg["ptype"] = "Please enter Display Order.";
                break;
            } else {

                if (array_key_exists($_REQUEST['txtdisplay_order'][$count], $temp_arr)) {
                    $ErrorMsg["ptype"] = "Display order must be unique.";
                    break;
                } else {//checking duplicacy
                    $ext_vlinks = checkDuplicateDisplayOrder($projectId, $_REQUEST['txtdisplay_order'][$count], $_REQUEST['PType']);
                    if ($ext_vlinks) {
                        $ErrorMsg["ptype"] = "Display order '" . $_REQUEST['txtdisplay_order'][$count] . "' already exist.";
                        break;
                    }
                }
                if ($_REQUEST['txtdisplay_order'][$count] != 5)
                    $temp_arr[$_REQUEST['txtdisplay_order'][$count]] = $_REQUEST['txtdisplay_order'][$count];
            }

            if ($_REQUEST['PType'] == 'Amenities') {
                if ($_REQUEST['SType'][$count] == '') {
                    $ErrorMsg["stype"] = "Please enter an Amenities Type.";
                }
            }

            $count++;
        }
    }
    $smarty->assign("PType", $_REQUEST['PType']);
    if (is_array($ErrorMsg)) {
        // Do Nothing
    } else {

        $flag = 0;
        $projectFolderCreated = 0;
 
        $postArr = array(); // array to store image data to send with http request
        $fileEndName = array();

        foreach ($arrValue as $key => $val) {
            $unitImageArr = array();

            if (!$_FILES["txtlocationplan"]["tmp_name"][$key]) {
                $ErrorMsg["ImgError"] .= "Problem in Image Upload Please Try Again.";
                break;
            } else {

                if ($_REQUEST['PType'] == "Location Plan")
                    $image_type = "locationPlan";
                elseif ($_REQUEST['PType'] == "Layout Plan")
                    $image_type = "layoutPlan";
                elseif ($_REQUEST['PType'] == "Site Plan")
                    $image_type = "sitePlan";
                elseif ($_REQUEST['PType'] == "Master Plan")
                    $image_type = "masterPlan";
                elseif ($_REQUEST['PType'] == "Cluster Plan")
                    $image_type = "clusterPlan";
                elseif ($_REQUEST['PType'] == "Construction Status")
                    $image_type = "constructionStatus";
                elseif ($_REQUEST['PType'] == "Payment Plan")
                    $image_type = "paymentPlan";
                elseif ($_REQUEST['PType'] == "Elevation")
                    $image_type = "main";
                elseif ($_REQUEST['PType'] == "Amenities")
                    $image_type = "amenities";
                elseif ($_REQUEST['PType'] == "Main Other")
                    $image_type = "mainOther";

                $img = array();
                $img['error'] = $_FILES["txtlocationplan"]["error"][$key];
                $img['type'] = $_FILES["txtlocationplan"]["type"][$key];
                $img['name'] = $_FILES["txtlocationplan"]["name"][$key];
                $img['tmp_name'] = $_FILES["txtlocationplan"]["tmp_name"][$key];

                $altText = $BuilderName . " " . strtolower($ProjectName) . " " . $arrTitle[$key];

                $tmp = array();
                $tmp['image'] = "@" . $img['tmp_name'];                
                $tmp['objectId'] = $projectId;
                $tmp['objectType'] = "project";
                $tmp['imageType'] = $image_type;
                $tmp['priority'] = $arrDisplayOrder[$key];
                $tmp['title'] = $arrTitle[$key];
                $tmp['altText'] = $altText;
                $unitImageArr['upload_from_tmp'] = "yes";
                $unitImageArr['method'] = "POST";
                $unitImageArr['url'] = IMAGE_SERVICE_URL;
                $unitImageArr['params'] = $tmp;
                $postArr[$key] = $unitImageArr;

            }
        }

        $serviceResponse = writeToImageService($postArr);
        
        foreach ($serviceResponse as $k => $v) {

            if (empty($v->error->msg)) {
                $image_id = $v->data->id;

                $add_tower = '';

                if ($arrTowerId[$k] > 0)
                    $add_tower = " TOWER_ID = $arrTowerId[$k], ";

                $imgDbPath = explode("/images_new", $img_path);

                if ($image_id > 0) {
                    $qryinsert = "INSERT INTO " . PROJECT_PLAN_IMAGES . "
									SET PLAN_IMAGE		=	'" . $imgDbPath[1] . "',
										PROJECT_ID		=	'" . $projectId . "',
										PLAN_TYPE		=	'" . $_REQUEST['PType'] . "',
										BUILDER_ID		=	'" . $builderDetail['BUILDER_ID'] . "',
										SERVICE_IMAGE_ID        =    " . $image_id . ",
										TITLE			=	'" . addslashes($arrTitle[$k]) . "', 
										DISPLAY_ORDER = '" . $arrDisplayOrder[$k] . "',
										TAGGED_MONTH = '" . $arrTaggedDate[$k] . "',
										" . $add_tower . "
										SUBMITTED_DATE	=	now()";
                    //echo "query".$qryinsert;
                    $resinsert = mysql_query($qryinsert) or die(mysql_error());

                    //}
                }
                $image_id = 0;
            } else {
                $strErr = " Error in uploading Image No " . ($k + 1) . " ";
                $ErrorMsg["ImgError"] .= $strErr . $v->error->msg . "<br>";
                $file = $_FILES["txtlocationplan"]["name"][$k];
                $tmp_path = $tmpDir . $file;
                unlink($tmp_path);
            }
        }

        //die("here0");
        if (empty($ErrorMsg)) {
            if ($_POST['Next'] == 'Add More')
                header("Location:project_img_add.php?projectId=" . $projectId);
            else if ($_POST['Next'] == 'Save')
                header("Location:ProjectList.php?projectId=" . $projectId);
            else
                header("Location:add_specification.php?projectId=" . $projectId);
        }
    }
}
else if (isset($_POST['Skip'])) {
    header("Location:add_specification.php?projectId=" . $projectId);
} else if (isset($_POST['exit'])) {
    header("Location:ProjectList.php?projectId=" . $projectId);
}


$smarty->assign("ErrorMsg", $ErrorMsg);
/* * *************Project dropdown************ */
$Project = array();
$qry = "SELECT PROJECT_ID,PROJECT_NAME,BUILDER_NAME FROM " . RESI_PROJECT . " ORDER BY BUILDER_NAME ASC";
$res = mysql_query($qry);

while ($dataArr = mysql_fetch_array($res)) {
    array_push($Project, $dataArr);
}
$smarty->assign("Project", $Project);
?>
