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