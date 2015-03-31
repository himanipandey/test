<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("imageService/image_upload.php");
include("includes/configs/configs.php");
include("builder_function.php");
$objectType = $_POST['objectType'];
$objectId = $_POST['objectId'];

//print "objectType: " . $objectType . "  ObjectID: " . $objectId;

if ($objectType == 'property') {
    $ImageDataListingArrFloor = array();
    $optionsArr = getAllProjectOptionsExceptPlot($objectId);

    foreach ($optionsArr as $k1 => $v1) {
        $objectType = "property";


        $image_type = "floor_plan";
        $objectId = $v1['OPTION_ID'];

        $url = ImageServiceUpload::$image_upload_url . "?objectType=$objectType&objectId=" . $objectId;
        //echo $url;
        $content = file_get_contents($url);
        $imgPath = json_decode($content);
        $data = array();
        foreach ($imgPath->data as $k => $v) {
            $data = array();
            $data['OPTION_ID'] = $v1['OPTION_ID'];
            $data['UNIT_TYPE'] = $v1['UNIT_TYPE'];
            $data['SIZE'] = $v1['SIZE'];
            $data['CARPET_AREA'] = $v1['CARPET_AREA'];
            $data['UNIT_NAME'] = $v1['UNIT_NAME'];


            $data['SERVICE_IMAGE_ID'] = $v->id;
            //$data['objectType'] = $v->imageType->objectType->type;
            //$data['objectId'] = $v->objectId; 
            $arr = preg_split('/(?=[A-Z])/', $v->imageType->type);
            $str = ucfirst(implode(" ", $arr));
            $data['PLAN_TYPE'] = "View " . $str;
            $data['DISPLAY_ORDER'] = $v->priority;
            $data['IMAGE_DESCRIPTION'] = $v->description;
            $data['IMAGE_URL'] = $v->absolutePath;
            $data['NAME'] = $v->title;

            $data['STATUS'] = $v->active;
            $data['thumb_path'] = $v->absolutePath . "?width=130&height=100";
            $data['alt_text'] = $v->altText;
            array_push($ImageDataListingArrFloor, $data);
        }
    }

    $cnt = 0;
    $html = '';
    foreach ($ImageDataListingArrFloor as $data) {

        $partsFloor = explode('.', $$data['IMAGE_URL']);
        $lastFloor = array_pop($partsFloor);
        $strFloor1 = implode('.', $partsFloor);
        $strFloor1 = $strFloor1 . '-thumb';
        $strFloor2 = $strFloor1 . '.';
        $finalStrWithThumbFloor = $strFloor2 . $last;

        if ($cnt != 0 && $cnt % 4 == 0) {
            $html .= "</tr><tr bgcolor='#ffffff'>";
        }

        $html .= '<td class = "tdcls_' . $cnt . '" >';
        $html .= '<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">';
        $html .= '<a class="pt_reqflrplan" href="' . $data['IMAGE_URL'] . '" target="_blank">';
        $html .= '<img src="' . $data['thumb_path'] . '" height="70px" width="70px" title = "' . $data['IMAGE_URL'] . '" alt ="' . $data['alt_text'] . '" />';
        $html .= '</a>';
        $html .= '<br/>';
        $html .= '<b>	Image Title : </b>' . $data['NAME'] . '<br><br>';
        $html .= '<b> Unit :</b> ' . $data['UNIT_NAME']." (";
        $html .= ($data['SIZE'] != '') ? $data['SIZE'] : '';
        $html .=  ($data['CARPET_AREA'] != '' && $data['SIZE'] != '') ? ', ' .$data['CARPET_AREA'] . '(Carpet)' : '';
        $html .= $data['MEASURE'] ;
        $html .= ', ' . $data['UNIT_TYPE'].")";

        $html .= '</div>';
        $html .= '</td>';

        $cnt++;
    }
} elseif ($objectType == 'project') {
    $url = ImageServiceUpload::$image_upload_url . "?objectType=$objectType&objectId=" . $objectId;

    $content = file_get_contents($url);
    $imgPath = json_decode($content);
    $ImageDataListingArr = array();
    foreach ($imgPath->data as $k => $v) {

        $data = array();
        $data['SERVICE_IMAGE_ID'] = $v->id;
        $data['objectType'] = $v->imageType->objectType->type;
        $data['objectId'] = $v->objectId;


        $arr = preg_split('/(?=[A-Z])/', $v->imageType->type);
        $str = ucfirst(implode(" ", $arr));
        if ($str == 'Main')
            $data['PLAN_TYPE'] = "Project Image";
        else
            $data['PLAN_TYPE'] = $str;

        if ($data['PLAN_TYPE'] == "Project Image" && $v->priority == 0)
            $data['display_order'] = 5;
        else
            $data['display_order'] = $v->priority;
        $data['TITLE'] = $v->title;
        $data['IMAGE_DESCRIPTION'] = $v->description;
        $data['SERVICE_IMAGE_ID'] = $v->id;
        $data['PLAN_IMAGE'] = $v->absolutePath;

        if (isset($v->takenAt)) {
            $t = $v->takenAt / 1000;
            $data['tagged_month'] = date("Y-m-d", $t);
        }



        $str = trim(trim($v->jsonDump, '{'), '}');
        $towerarr = explode(":", $str);
        if (trim($towerarr[1], "\"") == "null")
            $data['tower_id'] = null;
        else if (trim($towerarr[1], "\"") == "0") {
            $data['tower_id'] = "0";
            $data['TOWER_NAME'] = "Other";
        } else
            $data['tower_id'] = (int) trim($towerarr[1], "\"");

        foreach ($towerDetail as $k1 => $v1) {
            if ($v1['TOWER_ID'] == $data['tower_id'])
                $data['TOWER_NAME'] = $v1['TOWER_NAME'];
        }
       
        $data['PROJECT_ID'] = $v->objectId;
        $data['STATUS'] = $v->active;
        $data['thumb_path'] = $v->absolutePath . "?width=130&height=100";
        $data['alt_text'] = $v->altText;
        array_push($ImageDataListingArr, $data);
    }
    $cnt = 0;
    $html = '';
    foreach ($ImageDataListingArr as $data) {

        $parts = explode('.', $data['PLAN_IMAGE']);
        $last = array_pop($parts);
        $str1 = implode('.', $parts);
        $str1 = $str1 . '-thumb';
        $str2 = $str1 . '.';
        $finalStrWithThumb = $str2 . $last;

        if ($cnt != 0 && $cnt % 4 == 0) {
            $html .= "</tr><tr bgcolor='#ffffff'>";
        }

        $html .= '<td class = "tdcls_' . $cnt . '" >';
        $html .= '<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">';

        $html .= '<a class="pt_reqflrplan" href="' . $data['PLAN_IMAGE'] . '" target="_blank">';
        $html .= '<img src="' . $data['thumb_path'] . '" height="70px" width="70px" title = "' . $data['PLAN_IMAGE'] . '" alt ="' . $data['alt_text'] . '" />';
        $html .= '</a>';

        $html .= '<br/>';
        $html .= '<b>Image Type : </b>' . $data['PLAN_TYPE'] . '<br><br>';
        $html .= '<br/>';
        $html .= '<b>Image Title : </b>' . $data['TITLE'] . '<br><br>';
        
        if ($data['PLAN_TYPE'] == 'Construction Status') {
            $html .= '<b>Tagged Date : </b>' . date('F Y', strtotime($data['tagged_month'])) . '<br><br>';
            $html .= '<b>Tagged Tower : </b>' . ($data['tower_id'] >= 0) ? $data['TOWER_NAME'] : '' . '<br><br>';
        }

        if ($data['PLAN_TYPE'] == 'Project Image') {
            $html .= '<b>Display Order : </b>' . $data['display_order'] . '<br><br>';
           
        }

        if ($data['PLAN_TYPE'] == 'Cluster Plan') {            
            $html .= '<b>Tagged Tower : </b>' . ($data['tower_id'] >= 0) ? $data['TOWER_NAME'] : '' . '<br><br>';
        }


        $html .= '</div>';
        $html .= '</td>';

        $cnt++;
    }
}

?>
<?php
    if($html == ''){
       echo '<td>Data not found!</td>'; 
    }else{
       echo $html; 
    }
        
?>
