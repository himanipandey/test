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
$cityArr = City::CityArr($BranchLoc);
$brokerArr = BrokerCompany::find('all' , array('select' => 'brokers.id,brokers.broker_name'));

$result = array();
foreach($brokerArr as $key => $val)
{
    $broker_name  = '';
    if(!empty($val->broker_name) && strlen($val->broker_name) > 30)
        $broker_name = substr($val->broker_name , 0  ,30).'...';
    else
        $broker_name = $val->broker_name;
    array_push($result , array("id" => $val->id , "value" => $broker_name));
}
$brokerArr = json_encode($result);

//print'<pre>';
//print_r($BranchLoc);
//die;


$smarty->assign("cityArr", $cityArr);
$smarty->assign("brokerArr", $brokerArr);

$smarty->assign("sort", !empty($_GET['sort'])?$_GET['sort']:'all');
$smarty->assign("page", !empty($_GET['page'])?$_GET['page']:'1');

if(!empty($_GET['ruleId']))
{
    $conditions = " project_assignment_rules.id = '".$_GET['ruleId']."'";
    $joins = " LEFT JOIN brokers ON project_assignment_rules.broker_id = brokers.id
                LEFT JOIN rule_locality_mappings ON project_assignment_rules.id = rule_locality_mappings.rule_id
                LEFT JOIN locality ON rule_locality_mappings.locality_id = locality.locality_id
                LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                LEFT JOIN city ON suburb.city_id = city.city_id
                LEFT JOIN city AS cityrel ON rule_locality_mappings.city_id = cityrel.city_id
                ";
    
    $options  = array('joins' => $joins , 'select' => 'project_assignment_rules.*,rule_locality_mappings.locality_id , brokers.broker_name , city.city_id ,city.label AS city , cityrel.city_id  AS cityrelid' , 'conditions' => $conditions );
    
    $ruleAttr = ProjectAssignmentRules::find('all' , $options);
    //print'<pre>';
//    print_r($ruleAttr);
//    die;
    //echo ProjectAssignmentRules::connection()->last_query."<br>";
//    die;

               
    $city_id = '';
    $broker_id = '';
    $broker_name = '';
    $rule_name = '';
    $locIdArr = array();
    
    $locflag = 0;
    $projectflag = 0;
    $agentflag = 0;
    if(!empty($ruleAttr))
    {
        foreach($ruleAttr as $key => $val)
        {
            if(!empty($val->locality_id) && $val->locality_id != '-1')
                $locIdArr[] = $val->locality_id;
            else if(!empty($val->locality_id) && $val->locality_id == '-1')
                $locflag = 1;
            
            
            if(isset($key) && $key == "city_id" && !empty($val->city_id))
                $city_id = $val->city_id;
            else if(isset($key) && $key == "cityrelid" && !empty($val->cityrelid))
                $city_id = $val->cityrelid;    
            
            if(isset($key) && $key == "broker_name")
                $broker_name = $val->broker_name;
                
            if(isset($key) && $key == "broker_id")
                $broker_id = $val->broker_id; 
                
            if(isset($key) && $key == "rule_name")
                $rule_name = $val->rule_name;
        }        
    }  
    else
    {
        header('Location:ruleadd.php');
    }
    
    
    
    $locality = array();
    if(!empty($city_id))
    {
        $i = 0;
        $sql = @mysql_query("SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".$city_id) or die(mysql_error());
        
        while($row = @mysql_fetch_assoc($sql))
        {
            $locality[$row['locality_id']] = $row['label'];
        }        
    }
    
    $project = array();
    $projectIdArr = array();
    if(!empty($locIdArr))
    {
        $sql = "SELECT resi_project.project_id , resi_project.project_name AS label FROM resi_project LEFT JOIN rule_project_mappings ON resi_project.project_id = rule_project_mappings.project_id WHERE resi_project.locality_id IN (".implode("," , $locIdArr).")";
        
        $project = RuleAgentMappings::find_by_sql($sql);
    }
    //print'<pre>';
//    print_r($project);
//    die;
    $sql = @mysql_query("SELECT * FROM rule_project_mappings WHERE rule_id = '".mysql_escape_string($_GET['ruleId'])."' AND project_id = '-1'");
    if(@mysql_num_rows($sql) > 0)
        $projectflag = 1;
    
    $sql = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project LEFT JOIN rule_project_mappings ON resi_project.project_id = rule_project_mappings.project_id WHERE rule_project_mappings.rule_id = ".$_GET['ruleId']);
    while($row = @mysql_fetch_assoc($sql))
    {
        $projectIdArr[] = $row['project_id'];   
    }
    
    $i = 0;
    $agents = array();
    if(!empty($broker_id))
    {
        $sql = "SELECT agents.id AS agent_id , broker_contacts.name AS agent_name FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".$broker_id." AND broker_contacts.type='Agent'";
        $agents = RuleAgentMappings::find_by_sql($sql);
    }
    
    $sql = @mysql_query("SELECT * FROM rule_agent_mappings WHERE rule_id = '".mysql_escape_string($_GET['ruleId'])."' AND agent_id = '-1'");
    if(@mysql_num_rows($sql) > 0)
        $agentflag = 1;
        
    $agentIdArr = array();
    $sql = @mysql_query("SELECT rule_agent_mappings.agent_id FROM rule_agent_mappings WHERE rule_agent_mappings.rule_id = ".$_GET['ruleId']);
    while($row = @mysql_fetch_assoc($sql))
    {
        $agentIdArr[] = $row['agent_id'];    
    }
    
    
    //print'<pre>';
//    print_r($ruleAttr);
//    print_r($locality);
//    print_r($locIdArr);
//    print_R($project);
//    print_R($projectIdArr);
//    print_R($agents);
//    print_R($agentIdArr);
//    die;
   // echo $projectflag;die;
    
    $smarty->assign("broker_name", $broker_name);
    $smarty->assign("rule_name", $rule_name);
    $smarty->assign("city_id", $city_id);
    $smarty->assign("broker_id", $broker_id);
    $smarty->assign("locflag", $locflag);
    $smarty->assign("locality", $locality);
    $smarty->assign("locIdArr", $locIdArr);
    
    if($locflag == 1 && empty($locIdArr))
        $smarty->assign("locjIdArr", base64_encode(json_encode(array('all'))));
    else
        $smarty->assign("locjIdArr", base64_encode(json_encode($locIdArr)));
    
    $smarty->assign("projectflag", $projectflag);
    $smarty->assign("project", $project);
    $smarty->assign("projectIdArr", $projectIdArr);
    
    if($projectflag == 1 && empty($projectIdArr))
        $smarty->assign("projectjIdArr", base64_encode(json_encode(array('all'))));
    else
        $smarty->assign("projectjIdArr", base64_encode(json_encode($projectIdArr)));
        
    
    $smarty->assign("seller_company", $agents);
    
    $smarty->assign("agentflag", $agentflag);
    $smarty->assign("agentIdArr", $agentIdArr);
    
    if($agentflag == 1 && empty($agentIdArr))
        $smarty->assign("agentjIdArr", base64_encode(json_encode(array('all'))));
    else
        $smarty->assign("agentjIdArr", base64_encode(json_encode($agentIdArr)));
    
}

include('ruleaddProcess.php');

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."ruleadd.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	




?>