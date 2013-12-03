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
AdminAuthentication(); 
$cityArr = City::CityArr();
$brokerArr = BrokerCompany::find('all' , array('select' => 'brokers.id,brokers.broker_name'));

$smarty->assign("cityArr", $cityArr);
$smarty->assign("brokerArr", $brokerArr);

$smarty->assign("sort", !empty($_GET['sort'])?$_GET['sort']:'all');
$smarty->assign("page", !empty($_GET['page'])?$_GET['page']:'1');

if(!empty($_GET['ruleId']))
{
    $conditions = " project_assignment_rules.id = ".$_GET['ruleId'];
    $joins = " INNER JOIN brokers ON project_assignment_rules.broker_id = brokers.id
                INNER JOIN rule_locality_mappings ON project_assignment_rules.id = rule_locality_mappings.rule_id
                INNER JOIN locality ON rule_locality_mappings.locality_id = locality.locality_id
                INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                INNER JOIN city ON suburb.city_id = city.city_id
                ";
    
    $options  = array('joins' => $joins , 'select' => 'project_assignment_rules.*,rule_locality_mappings.locality_id , brokers.broker_name , city.city_id ,city.label AS city' , 'conditions' => $conditions );
    
    $ruleAttr = ProjectAssignmentRules::find('all' , $options);
    

               
    $city_id = '';
    $broker_id = '';
    $rule_name = '';
    $locIdArr = array();
    if(!empty($ruleAttr))
    {
        foreach($ruleAttr as $key => $val)
        {
            $locIdArr[] = $val->locality_id;
            if(isset($key) && $key == "city_id")
                $city_id = $val->city_id;    
                
            if(isset($key) && $key == "broker_id")
                $broker_id = $val->broker_id; 
                
            if(isset($key) && $key == "rule_name")
                $rule_name = $val->rule_name;
        }        
    } 
    
    $locality = array();
    if(!empty($city_id))
    {
        $i = 0;
        $sql = "SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".$city_id;
        
        $locality = RuleAgentMappings::find_by_sql($sql);
    }
    
    $project = array();
    $projectIdArr = array();
    if(!empty($locIdArr))
    {
        $sql = "SELECT resi_project.id , resi_project.project_name AS label FROM resi_project LEFT JOIN rule_project_mappings ON resi_project.project_id = rule_project_mappings.project_id WHERE locality_id IN (".implode("," , $locIdArr).")";
        
        $project = RuleAgentMappings::find_by_sql($sql);
    }
    
    $sql = @mysql_query("SELECT resi_project.id , resi_project.project_name FROM resi_project LEFT JOIN rule_project_mappings ON resi_project.id = rule_project_mappings.project_id WHERE rule_project_mappings.rule_id = ".$_GET['ruleId']);
    while($row = @mysql_fetch_assoc($sql))
    {
        $projectIdArr[] = $row['id'];
            
    }
    
    $i = 0;
    $agents = array();
    if(!empty($broker_id))
    {
        $sql = "SELECT agents.id AS agent_id , broker_contacts.name AS agent_name FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".$broker_id." AND broker_contacts.type='Agent'";
        $agents = RuleAgentMappings::find_by_sql($sql);
    }
    
    
    $agentIdArr = array();
    $sql = @mysql_query("SELECT rule_agent_mappings.agent_id FROM rule_agent_mappings WHERE rule_agent_mappings.rule_id = ".$_GET['ruleId']);
    while($row = @mysql_fetch_assoc($sql))
    {
        $agentIdArr[] = $row['agent_id'];    
    }
    
    
    //print'<pre>';
//    //print_r($ruleAttr);
//    print_r($locality);
//    print_r($locIdArr);
//    print_R($project);
//    print_R($projectIdArr);
//    print_R($agents);
//    print_R($agentIdArr);
//    die;
    
    $smarty->assign("rule_name", $rule_name);
    $smarty->assign("city_id", $city_id);
    $smarty->assign("broker_id", $broker_id);
    $smarty->assign("locality", $locality);
    $smarty->assign("locIdArr", $locIdArr);
    $smarty->assign("locjIdArr", base64_encode(json_encode($locIdArr)));
    $smarty->assign("project", $project);
    $smarty->assign("projectIdArr", $projectIdArr);
    $smarty->assign("projectjIdArr", base64_encode(json_encode($projectIdArr)));
    $smarty->assign("seller_company", $agents);
    $smarty->assign("agentIdArr", $agentIdArr);
    $smarty->assign("agentjIdArr", base64_encode(json_encode($agentjIdArr)));
}

include('ruleaddProcess.php');

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."ruleadd.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	




?>