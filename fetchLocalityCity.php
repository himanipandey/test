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

if(!empty($_POST['city']))
{
    $allloc = mysql_escape_string(trim($_POST['city']));
    $allloc = explode("," , $_POST['city']); 
    $data = array();
    
    if(!empty($allloc))
    {
        foreach($allloc as $key => $val)
        {
            $sql = @mysql_query("SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".$val." AND locality.locality_id NOT IN (SELECT locality_id FROM rule_locality_mappings)");
            while($row = @mysql_fetch_assoc($sql))
            {
                $data[$row['locality_id']] = $row['label'];    
            }
        }
    }
    echo json_encode($data);
    exit();
}

?>