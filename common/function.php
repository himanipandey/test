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
    if ( in_array( $columnName, array( 'LOCALITY_ID', 'SUBURB_ID', 'CITY_ID' ) ) ) {

    }
    else {
        $columnName = 'LOCALITY_ID';
    }
    $imageName = mysql_escape_string( $imageName );
    $insertQuery = "INSERT INTO `locality_image` 
            ( `$columnName`, `IMAGE_NAME`, IMAGE_CATEGORY, IMAGE_DISPLAY_NAME, IMAGE_DESCRIPTION, SERVICE_IMAGE_ID ) 
           VALUES ( '$areaId', '$imageName', '$imgCategory', '$imgDisplayName', '$imgDescription', $serviceImgId )";

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

function writeToImageService($IMG, $objectType, $objectId){
    $imageCount = count( $IMG['name'] );
    $uploadStatus = array();   
    $addedImgIdArr = array();
     include("SimpleImage.php");
     $thumb = new SimpleImage();

    for( $__imgCnt = 0; $__imgCnt < $imageCount; $__imgCnt++ ) {
        if ( $IMG['error'][ $__imgCnt ] == 0 ) {
            $extension = explode( "/", $IMG['type'][ $__imgCnt ] );
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
                $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "format not supported";
            }
            else {
                //  no error
                $__width = "592";
                $__height = "444";
                $__thumbWidth = "91";
                $__thumbHeight = "68";
                $imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension );
                $thumb->load( $IMG['tmp_name'][ $__imgCnt ] );

                $thumb->resize( $__width, $__height );
                $thumb->save($newImagePath.'locality/'.$imgName, $imgType);
                $dest = 'locality/'.$imgName;
                $source = $newImagePath.$dest;
                
                $s3upload = new ImageUpload($source, array("s3" => $s3,
                    "image_path" => $dest, "object" => "$areaType","object_id" => $areaId,
                    "image_type" => strtolower($imgCategory),
                    "service_extra_params" => 
                        array("priority"=>$displayPriority,"title"=>$imgDisplayName,"description"=>$imgDescription)));
               $serviceResponse =  $s3upload->upload();
               die("here");
                $thumb->resize( $__thumbWidth, $__thumbHeight );
                $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                $dest = 'locality/thumb_'.$imgName;
                $source = $newImagePath.$dest;
                $s3upload = new S3Upload($s3, $bucket, $source, $dest);
                $s3upload->upload();
                
                //  add image to DB
                /*$addedImgIdArr[] = addImageToDB( $columnName, $areaId, $imgName,
                        $imgCategory, $imgDisplayName, $imgDescription,$serviceResponse['service']->response_body->data->id,$displayPriority );*/
              
                $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "uploaded";
                
            }
        }
        else {
            $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "Error#".$IMG['error'][ $__imgCnt ];
        }
    }
}







/*********************Read Image to image service*************************************************************/

function readToImageService(){
    
}