<?php
/**
 * User: swapnil
 * Date: 7/19/13
 * Time: 5:02 PM
 */
function getListing( $areaType ) {
    $tableName = "";
    $tableId = "";
    if ( $areaType == 'suburb' ) {
        $tableName = "suburb";
        $tableId = "SUBURB_ID";
    }
    elseif ( $areaType == 'city' ) {
        $tableName = "city";
        $tableId = "CITY_ID";
    }
    else {
        $tableName = "locality";
        $tableId = "LOCALITY_ID";
    }

    if ( $tableName != "" ) {
        if ( $tableName == "city" ) {
            $query = "SELECT CITY_ID AS id, LABEL AS name FROM city WHERE ACTIVE=1 ORDER BY name";
        }
        else {
            $query = "SELECT A.$tableId AS id, A.LABEL AS name, C.LABEL AS city FROM $tableName AS A LEFT JOIN city AS C ON C.CITY_ID=A.CITY_ID WHERE A.ACTIVE=1 ORDER BY city";
        }
        //echo $query;die();
        $dataSet = dbQuery( $query );
        return $dataSet;
    }
}

function addImageToDB( $columnName, $areaId, $imageName ) {
    if ( in_array( $columnName, array( 'LOCALITY_ID', 'SUBURB_ID', 'CITY_ID' ) ) ) {

    }
    else {
        $columnName = 'LOCALITY_ID';
    }
    $imageName = mysql_escape_string( $imageName );
    $insertQuery = "INSERT INTO `locality_image` ( `$columnName`, `IMAGE_NAME` ) VALUES ( '$areaId', '$imageName' )";

    dbExecute( $insertQuery );
}