<?php
/**
 * User: swapnil
 * Date: 7/19/13
 * Time: 5:02 PM
 */
function getListing( $dataArr = array() ) {
    $cityId = isset( $dataArr['city'] ) ? $dataArr['city'] : "";
    $suburbId = isset( $dataArr['suburb'] ) ? $dataArr['suburb'] : "";
    $query = "";

    if ( count( $dataArr ) == 0 ) {
        //  City data as list
        $cityQuery = "SELECT CITY_ID AS id, LABEL AS name
                      FROM city
                      WHERE status='Active'
                      ORDER BY name";
    }
    elseif( $cityId ) {
        $suburbQuery = "SELECT A.SUBURB_ID AS id, A.LABEL AS name, C.LABEL AS city
                        FROM suburb AS A
                        LEFT JOIN city AS C ON A.CITY_ID=C.CITY_ID
                        WHERE A.status='Active' AND C.CITY_ID=$cityId
                        ORDER BY city";

        if ( $suburbId ) {
            $localityQuery = "SELECT A.LOCALITY_ID AS id, A.LABEL AS name, C.LABEL AS city
                              FROM locality AS A
                              LEFT JOIN suburb AS S ON A.SUBURB_ID=S.SUBURB_ID
                              LEFT JOIN city AS C ON S.CITY_ID=C.CITY_ID
                              WHERE A.status='Active' AND S.SUBURB_ID=$suburbId AND C.CITY_ID=$cityId
                              ORDER BY city";
        }
        else {
            $localityQuery = "SELECT A.LOCALITY_ID AS id, A.LABEL AS name, C.LABEL AS city
                              FROM locality AS A
                              LEFT JOIN suburb AS S ON A.SUBURB_ID=S.SUBURB_ID
                              LEFT JOIN city AS C ON S.CITY_ID=C.CITY_ID
                              WHERE A.status='Active' AND C.CITY_ID=$cityId
                              ORDER BY city";
        }
    }

    $dataSet = array();
    if ( isset( $cityQuery ) ) {
        $dataSet['city'] = dbQuery( $cityQuery );
    }
    if ( isset( $suburbQuery ) ) {
        $dataSet['suburb'] = dbQuery( $suburbQuery );
    }
    if ( isset( $localityQuery ) ) {
        $dataSet['locality'] = dbQuery( $localityQuery );
    }

    return $dataSet;
}

function getPhoto( $data = array() ) {
    $column = "";
    $id = "";
    if ( !empty( $data['locality'] ) ) {
        $column = "LOCALITY_ID";
        $id = $data['locality'];
    }
    elseif ( !empty( $data['suburb'] ) ) {
        $column = "SUBURB_ID";
        $id = $data['suburb'];
    }
    elseif ( !empty( $data['city'] ) ) {
        $column = "CITY_ID";
        $id = $data['city'];
    }
    else {
        return NULL;
    }
   $query = "SELECT IMAGE_ID, $column, IMAGE_NAME, IMAGE_CATEGORY, IMAGE_DISPLAY_NAME, IMAGE_DESCRIPTION,SERVICE_IMAGE_ID
        ,priority FROM locality_image WHERE $column = $id";
    $data = dbQuery( $query );
   return $data;
}

function getPhotoById( $id ) {
    if ( !$id ) {
        return null;
    }
    $query = "";
    if ( is_array( $id ) && count( $id ) ) {
        $id = implode( ', ', $id );
        $query = "SELECT * FROM locality_image WHERE IMAGE_ID IN ( $id )";
    }
    else {
        $query = "SELECT * FROM locality_image WHERE IMAGE_ID = $id ";
    }
    $data = dbQuery( $query );
    return $data;
}

function updateThisPhotoProperty( $data = array() ) {
    if ( empty( $data['IMAGE_ID'] ) ) {
        return false;
    }
    else {
        $__id = $data['IMAGE_ID'];
        $setField = array();
        foreach( $data as $__columnName => $__columnValue ) {
            if ( $__columnName != 'IMAGE_ID' ) {
                $setField[] = "$__columnName = '".mysql_real_escape_string( $__columnValue )."'";
            }
        }
        if ( count( $setField ) > 0 ) {
            $setField = implode( ', ', $setField );
            $query = "UPDATE locality_image SET $setField WHERE IMAGE_ID = $__id";
            dbExecute( $query );
        }
    }
    return true;
}

function addImageToDB( $columnName, $areaId, $imageName, $imgCategory, $imgDisplayName, $imgDescription, $serviceImgId, $displayPriority ) {
    if ( in_array( $columnName, array( 'LOCALITY_ID', 'SUBURB_ID', 'CITY_ID', 'LANDMARK_ID' ) ) ) {

    }
    else {
        $columnName = 'LOCALITY_ID';
    }
    $imageName = mysql_escape_string( $imageName );
    if(!empty($imgDescription))
        $insertQuery = "INSERT INTO `locality_image` 
            ( `$columnName`, `IMAGE_NAME`, IMAGE_CATEGORY, IMAGE_DISPLAY_NAME, IMAGE_DESCRIPTION, SERVICE_IMAGE_ID ) 
           VALUES ( '$areaId', '$imageName', '$imgCategory', '$imgDisplayName', '$imgDescription', $serviceImgId )";
    else $insertQuery = "INSERT INTO `locality_image` 
            ( `$columnName`, `IMAGE_NAME`, IMAGE_CATEGORY, IMAGE_DISPLAY_NAME, SERVICE_IMAGE_ID ) 
           VALUES ( '$areaId', '$imageName', '$imgCategory', '$imgDisplayName', $serviceImgId )";
//echo $insertQuery;
    dbExecute( $insertQuery );
    mysql_insert_id();
    return mysql_insert_id();
}
/********code for find current assigned cycle of a project************/
function currentCycleOfProject($projectId,$projectPhase,$projectStage) {
    $currentCycle = '';
    $qry = "select a.department from resi_project rp join project_assignment pa
            on (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id or pa.updation_cycle_id is null))
            left join proptiger_admin a
            on pa.assigned_to = a.adminid 
            inner join master_project_stages pstg on rp.PROJECT_STAGE_ID = pstg.id
            inner join master_project_phases pphs on rp.PROJECT_PHASE_ID = pphs.id
            where ((pstg.name = '".NewProject_stage."' and pphs.name = '".DcCallCenter_phase."') or 
                (pstg.name = '".UpdationCycle_stage."' and pphs.name = '".DataCollection_phase."')) and
            rp.project_id = $projectId and version = 'Cms' order by pa.id desc limit 1";
    $resUpdationCycle = mysql_query($qry) or die(mysql_error());
    $updationCycle = mysql_fetch_assoc($resUpdationCycle);
    if(mysql_num_rows($resUpdationCycle)>0){
        if($updationCycle['department'] == 'SURVEY')
            $currentCycle = 'Survey';
        elseif($updationCycle['department'] == 'CALLCENTER')
            $currentCycle = 'Call Center';
        else
            $currentCycle = 'Not Assigned';
    }
    else{
            if(($projectPhase == DcCallCenter_phase && $projectStage == NewProject_stage) ||
                   $projectPhase == DataCollection_phase && $projectStage == UpdationCycle_stage ){
                $currentCycle = 'Call Center';
            }
            else
                $currentCycle = 'Not Assigned';
    }
    return $currentCycle;
}


/*********************Write Image to image service*************************************************************/

function writeToImageService($imageParams){

         //print'<pre>';
           //     print_r($imageParams);
   //echo $imageParams['image_type'];
    $postArr = array();;
    $result = array();
    foreach ($imageParams as $k => $v) {
        # code...
          //print'<pre>';
          //print_r($v); die();
        $params = $v['params'];
        $IMG = $v['img'];
        $objectId = $v['objectId'];
        $objectType = $v['objectType'];
        $newImagePath = $v['newImagePath'];

        $service_extra_paramsArr = array( 
            "priority"=>$params['priority'],"title"=>$params['title'],"description"=>$params['description'],"takenAt"=>$params['tagged_date'],"altText"=>$params['altText'], "jsonDump"=>json_encode($params['jsonDump']));

        if(!isset($params['tagged_date']) || empty($params['tagged_date']))
                    unset($service_extra_paramsArr["takenAt"]);
        if(!isset($params['jsonDump']) || empty($params['jsonDump']))
                    unset($service_extra_paramsArr["jsonDump"]);
         if(!isset($params['priority']) || empty($params['priority']))
                    unset($service_extra_paramsArr["priority"]);
        if(!isset($params['description']) || empty($params['description']))
                  $service_extra_paramsArr["description"] = null;  //unset($service_extra_paramsArr["description"]);
        if(!isset($params['title']) || empty($params['title']))
                    unset($service_extra_paramsArr["title"]);
        if(!isset($params['altText']) || empty($params['altText']))
                    unset($service_extra_paramsArr["altText"]);

               // print'<pre>';
               // print_r($service_extra_paramsArr);//die();

        if($params['delete']=="yes"){
             $s3upload = new ImageUpload(NULL, array("object" => $objectType,"object_id" => $objectId, "service_image_id" => $params['service_image_id']));
                $postArr[$k] = $s3upload->delete();
        }        
        else if($IMG==""){
                    //print'<pre>';
                    //print_r($params);//die();
                    //die("here");
            $s3upload = new ImageUpload(NULL, array("object" => $objectType,"object_id" => $objectId,
                         "service_image_id"=>$params['service_image_id'],"image_type" => strtolower($params['image_type']), "service_extra_params" => $service_extra_paramsArr));
            $postArr[$k] = $s3upload->updateWithoutImage();
            //$returnValue['serviceResponse'] =  $s3upload->updateWithoutImage();
        }
             
        else{
            $returnValue = array();
            $extension = explode( "/", $IMG['type'] );
            $extension = $extension[ count( $extension ) - 1 ];
            $imgType = "";
            if ( strtolower( $extension ) == "jpg" || strtolower( $extension ) == "jpeg" ) {
                $imgType = IMAGETYPE_JPEG;
            }
            elseif ( strtolower( $extension ) == "gif" ) {
                $imgType = IMAGETYPE_GIF;
            }
            elseif ( strtolower( $extension ) == "png" ) {
                $imgType = IMAGETYPE_PNG;
            }
            else {
                //  unknown format !!
            }
            if ( $imgType == "" ) {
                $returnValue['error'] = "format not supported";
            }
            else {
                //  no error
                if($params['image']){
                    
                    $imgName = $params['image']; 
                    $dest = $params['folder'].$imgName;
                    $source = $newImagePath.$dest;
                    //echo "here".$imgName.$dest.$source;
                }
                else{
                    $imgName = $objectType."_".$objectId."_".$params['count']."_".time().".".strtolower( $extension );
                    
                    $dest = $params['folder'].$imgName;
                    $source = $newImagePath.$dest;
                    
                    
                    $move = move_uploaded_file($IMG['tmp_name'],$source);
                }

                //print'<pre>';
                //print_r($params); //die();
                //echo "here";
                
                $s3upload = new ImageUpload($source, array( "image_path" => $dest, "object" => $objectType,"object_id" => $objectId,
                    "image_type" => strtolower($params['image_type']), "service_image_id"=>$params['service_image_id'],
                    "service_extra_params" => $service_extra_paramsArr));
               
                if(isset($params['update']))
                    $postArr[$k] = $s3upload->update();
                    //$returnValue['serviceResponse'] =  $s3upload->update();
                else{
                    $postArr[$k] = $s3upload->upload();
                    //$returnValue['serviceResponse'] =  $s3upload->upload();
                }
                
                
                
            }
        }

    }

     
  // print'<pre>';   print_r($postArr);die();
  // array of curl handles
    $curly = array();
  // data to be returned
    
 //echo "curl-start:".microtime(true)."<br>"; 
  // multi handle
  $mh = curl_multi_init();
 
  // loop through $data and create curl handles
  // then add them to the multi-handle

//die();
////print'<pre>';
  //  print_r($postArr); die();
//if(count($postArr)>1){
  foreach ($postArr as $id => $d) {
    $url = $d['url'];
    $method = $d['method'];
    $post = $d['params'];
    $curly[$id] = curl_init();
 
    //$url = (is_array($d) && !empty($url) ? $url : "");
    curl_setopt($curly[$id], CURLOPT_URL,            $url);
    curl_setopt($curly[$id], CURLOPT_VERBOSE, 1);
    curl_setopt($curly[$id], CURLOPT_HEADER,         1);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curly[$id], CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $post);
    // post?
     curl_multi_add_handle($mh, $curly[$id]);
  }
 
  // execute the handles
  $running = null;
  do {
    curl_multi_exec($mh, $running);
    
  } while($running > 0);
 
 
  // get content and remove handles
  foreach($curly as $id => $c) {
    $response = curl_multi_getcontent($c);
    $pos = mb_strpos($response, "{");
    //echo $pos;
    $result[$id] = json_decode(substr($response, $pos));
    //var_dump($result[$id]);
    //$header_size = curl_getinfo_read($c, CURLINFO_HEADER_SIZE);
    //echo "headx:".$header_size;
    //$header_size = curl_multi_info_read($c, CURLINFO_HEADER_SIZE);
        //$response_header = substr($result[$id], 0, $header_size);
        //$response_body = json_decode(substr($result[$id], $header_size));
    //$result[$id] = $response_body;     
    curl_multi_remove_handle($mh, $c);
  }
 
    // all done
    curl_multi_close($mh);
    //echo "curl-end:".microtime(true)."<br>"; 
    //echo "loop-end:".microtime(true)."<br>"; 
    //print("<pre>");   var_dump($result);die("here");

//}
/*
else if(count($postArr)==1){
    foreach ($postArr as $id => $d) {
        $url = $d['url'];
        $method = $d['method'];
        $post = $d['params'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
        if($method == "POST" || $method == "PUT")
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response= curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response_header = substr($response, 0, $header_size);
        $response_body = json_decode(substr($response, $header_size));
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $result[0] = $response_body;

    }

}*/
    return $result;
}


/*********************update/delete  Image from image service*************************************************************/
function deleteFromImageService($objectType="", $objectId=0, $service_image_id){
    //die($service_image_id);
    $s3upload = new ImageUpload(NULL, array("object" => $objectType,"object_id" => $objectId, "service_image_id" => $service_image_id));
    return $s3upload->delete();
}



/*********************Read Images from image service*************************************************************/

function readFromImageService($objectType, $objectId){
    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
    return $url;
}



function getDBDistanceQueryString($lon1Col, $lat1Col, $lon2Col, $lat2Col){
    return "((ACOS(SIN($lat1Col * PI() / 180) * SIN($lat2Col * PI() / 180) + COS($lat1Col * PI() / 180) * COS($lat2Col * PI() / 180) * COS(($lon1Col - $lon2Col) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1609.34)";

}


function getProjectFromOption($optionId){
    $qry = "SELECT PROJECT_ID FROM resi_project_options WHERE OPTIONS_ID={$optionId}";
    $res = mysql_query($qry);
    $Result = mysql_fetch_assoc($res);
    return $Result['PROJECT_ID'];

}

function configSizeCheckFlag($projectId){
  $flagSql = mysql_fetch_object(mysql_query("select count(options_id) as cnt from resi_project_options where project_id = '$projectId' and (size is null or size=0) and option_category = 'Actual'"));
  if($flagSql->cnt > 0)
    return 1;
  else 
    return 0;
}

function checkCompanyExist($email,$id,$mode,$status){
  if($status == 'Active'){		
	  if($mode=='update' && $id!==null)	
		$comp = mysql_query("select * from company where status = 'Active' and primary_email = '$email' and id!='$id'");   
	  else
		$comp = mysql_query("select * from company where status = 'Active' and primary_email = '$email'");   
	 
	  if(mysql_num_rows($comp)){
		return false;
	  }
	  else{
		return true;	
	  }
  }else
	  return true;
}

