<?php
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("common/function.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();

    if ( isset( $_REQUEST['upImg'] ) && $_REQUEST['upImg'] == 1 ) {
        //echo "<pre>"; print_r( $_REQUEST ); print_r( $_FILES ); die();
        $city     = !empty( $_REQUEST['cityId'] ) ? $_REQUEST['cityId'] : 0;
        $suburb   = !empty( $_REQUEST['suburbId'] ) ? $_REQUEST['suburbId'] : 0;
        $locality = !empty( $_REQUEST['localityId'] ) ? $_REQUEST['localityId'] : 0;

        $errMsg = "";
        $columnName = "";
        //$areaType = isset( $_REQUEST['areaType'] ) ? trim( $_REQUEST['areaType'] ) : "";
        if ( $city || $suburb || $locality ) {
            $selectedAreaType[ $areaType ] = "selected";
            $columnName = strtoupper( $areaType )."_ID";

            if ( $locality > 0 ) {
                $columnName = "LOCALITY_ID";
                $areaType = 'locality';
                $areaId = $locality;
            }
            elseif ( $suburb > 0 ) {
                $columnName = "SUBURB_ID";
                $areaType = 'suburb';
                $areaId = $suburb;
            }
            else {
                $columnName = "CITY_ID";
                $areaType = 'city';
                $areaId = $city;
            }
        }
        else {
            $errMsg = "Please select the area type (Locality/Suburb/City)";
        }

    }

    $response = getListing();   //  get City List

    $cityList = !empty( $response['city'] ) ? $response['city'] : "";

    if ( is_array( $cityList ) && count( $cityList ) ) {
        $smarty->assign( 'cityList', $cityList );
    }

    $smarty->assign( 'photoCSS', 1 );

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."update-photo.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");


?>
