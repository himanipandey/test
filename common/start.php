<?php
/**
 * User: swapnil
 * Date: 7/6/13
 * Time: 11:51 PM
 * To change this template use File | Settings | File Templates.
 */

require_once("common.php");
include_once(dirname(__FILE__) . "/../configs/configs.php");

$env = 'DEV';
$env_project = 'DEV';

$db_crm = new Db( 'master',  DB_CRM_HOST, DB_CRM_USER, DB_CRM_PASS, DB_CRM_NAME, ( $env == "DEV" ? TRUE : FALSE ) );

$db_project = new Db( 'master',  DB_PROJECT_HOST, DB_PROJECT_USER, DB_PROJECT_PASS, DB_PROJECT_NAME, ( $env_project == "DEV" ? TRUE : FALSE ) );

function AdminAuth() {
    global $db_crm;
    if($_SESSION['CRMAdminLogin'] != "Y") {
        header("Location: index.php");
    }else {
        $query = "SELECT COUNT(1) AS CNT FROM ".USER." WHERE ID ='".$_SESSION['ID']."'";
        $row = $db_crm->Row( $query );
        if($row['CNT']==0) {
            session_start();
            session_destroy();
            session_unset();
            header("Location: index.php");
        }
    }
}