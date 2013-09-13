<?php
/**
 * User: swapnil
 * Date: 7/6/13
 * Time: 11:40 PM
 * To change this template use File | Settings | File Templates.
 */
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
$res = array( 'result' => 'fail' );
include_once("appWideConfig.php");
if ( isset( $_REQUEST['action'] ) ) {

    require_once("common/start.php");

    switch ( $_REQUEST['action'] ) {
        case 'get_project':
            $query = $_REQUEST['query'];
            $parameters = array(
                'proonly' => 1,
                'query'   => $query
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, SERVER_URL."/typeahead.php");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
            break;

        case 'get_project_detail':
            $id = $_REQUEST['id'];
            $parameters = array(
                'proid' => $id
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, SERVER_URL."/typeahead.php");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $projectDetails = curl_exec($ch);
            curl_close($ch);
            $projectDetails = json_decode( $projectDetails, TRUE );

            //  get Available Properties and Tower Info
            require_once("includes/class_project.php");
            $towerInfo = getTowerInfoByProjectId( $id, $db_project );
            $availablePropInfo = getAvailablePropInfo( $id, $db_project );

            $result = array();
            $result['projDetail'] = $projectDetails[0];
            $result['towerDetail'] = $towerInfo;
            $result['availableDetail'] = $availablePropInfo;

            $res = json_encode( $result );
            break;
        default:
            # code...
            $res = json_encode( $result );
            break;
    }
}

function getAvailablePropInfo( $projectId, $db_project ) {
    $proObj = new Project( $db_project );
    $res = $proObj->getAvailableProjectInfo( $projectId );
    return $res;
}

function getTowerInfoByProjectId( $projectId, $db_project ) {
    $proObj = new Project( $db_project );
    $res = $proObj->getTowerInfoByProjectId( $projectId );
    return $res;
}

echo $res;
exit;