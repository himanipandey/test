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
                      WHERE ACTIVE=1
                      ORDER BY name";
    }
    elseif( $cityId ) {
        $suburbQuery = "SELECT A.SUBURB_ID AS id, A.LABEL AS name, C.LABEL AS city
                        FROM suburb AS A
                        LEFT JOIN city AS C ON C.CITY_ID=A.CITY_ID
                        WHERE A.ACTIVE=1 AND C.CITY_ID=$cityId
                        ORDER BY city";

        if ( $suburbId ) {
            $localityQuery = "SELECT A.LOCALITY_ID AS id, A.LABEL AS name, C.LABEL AS city
                              FROM locality AS A
                              LEFT JOIN city AS C ON C.CITY_ID=A.CITY_ID
                              LEFT JOIN suburb AS S ON S.SUBURB_ID=A.SUBURB_ID
                              WHERE A.ACTIVE=1 AND S.SUBURB_ID=$suburbId AND C.CITY_ID=$cityId
                              ORDER BY city";
        }
        else {
            $localityQuery = "SELECT A.LOCALITY_ID AS id, A.LABEL AS name, C.LABEL AS city
                              FROM locality AS A
                              LEFT JOIN city AS C ON C.CITY_ID=A.CITY_ID
                              WHERE A.ACTIVE=1 AND C.CITY_ID=$cityId
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
    $query = "SELECT IMAGE_ID, $column, IMAGE_NAME, IMAGE_CATEGORY, IMAGE_DISPLAY_NAME, IMAGE_DESCRIPTION FROM locality_image WHERE $column = $id";
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
        return null;
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
    return mysql_insert_id();
}