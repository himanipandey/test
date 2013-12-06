<?php

/**
 * @author AKhan
 * @copyright 2013
 */

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
//print'<pre>';
//print_r($_POST);
//die;
//

if(!empty($_POST['pan']))
{
    $chkExist = BrokerCompany::find('all' , array('conditions' => " pan = '".mysql_real_escape_string($_POST['pan'])."'"));
    
    if(!empty($chkExist))
        echo json_encode(array('response' => 'error'));
    else
        echo json_encode(array('response' => 'success'));
    die;
    
}
?>