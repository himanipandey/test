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

if(!empty($_POST['locality']))
{
    $alloc = mysql_escape_string(trim($_POST['locality']));
    $alloc = explode("," , $alloc); 
    $data = array();
    $chkData = '';
    
    if(!empty($alloc))
    {
        foreach($alloc as $key => $val)
        {
            $sql = @mysql_query("SELECT resi_project.id , resi_project.project_name FROM resi_project WHERE locality_id = ".$val." AND resi_project.id NOT IN (SELECT project_id FROM rule_project_mappings)");
            while($row = @mysql_fetch_assoc($sql))
            {
                $data[$row['id']] = $row['project_name'];    
            }
        }
    }
    echo json_encode($data);
    exit();
}

?>