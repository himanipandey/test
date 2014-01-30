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

if(!empty($_POST['broker']))
{
    $allloc = mysql_escape_string(trim($_POST['broker']));
    $allloc = explode("," , $_POST['broker']); 
    $data = array();
    
    if(!empty($allloc))
    {
        foreach($allloc as $key => $val)
        {
            $sql = @mysql_query("SELECT agents.id , broker_contacts.name FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".$val." AND broker_contacts.type='Agent'");
            //$sql = @mysql_query("SELECT agents.id , broker_contacts.name FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".$val." AND broker_contacts.type='Agent' AND agents.id NOT IN (SELECT ram.agent_id FROM rule_agent_mappings AS ram INNER JOIN project_assignment_rules AS par ON ram.rule_id = par.id)");
            while($row = @mysql_fetch_assoc($sql))
            {
                $data[$row['id']] = $row['name'];    
            }
        }
    }
    echo json_encode($data);
    exit();
}

?>