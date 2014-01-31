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
    $nPro = array();
    /** This commented code gives all the Project of Localities in rule_locality Table */
    $chkRule = @mysql_query("SELECT rpm.project_id 
                    FROM rule_project_mappings AS rpm 
                    WHERE rpm.rule_id = '".$_GET['ruleId']."'");
                    
    if(@mysql_num_rows($chkRule) > 0)
    {
        while($row1 = @mysql_fetch_assoc($chkRule))
        {
            if(!in_array($row1['project_id'] , $nPro))
                $nPro[] = $row1['project_id'];
        }
        
    }
    else
    {        
        $chkRuleLoc = @mysql_query("SELECT rp.project_id 
                FROM resi_project AS rp 
                LEFT JOIN rule_locality_mappings AS rlm ON rp.locality_id = rlm.locality_id
                WHERE rlm.rule_id = '".$_GET['ruleId']."'");
        while($row1 = @mysql_fetch_assoc($chkRuleLoc))
        {
            if(!in_array($row1['project_id'] , $nPro))
                $nPro[] = $row1['project_id'];
        }
    }
    /** --------------------------------------------------------------------------------- */
    
    $nPro1 = array();
    if(!empty($_GET['company_id']))
    {
        $chkComp = @mysql_query("SELECT par.id
                        FROM project_assignment_rules AS par 
                        WHERE par.broker_id = '".$_GET['company_id']."'");
        while($row = @mysql_fetch_assoc($chkComp))
        {
            //echo $row['id'] ." -->". $_GET['ruleId']."<-- <br>";
            if($row['id'] == $_GET['ruleId'])
                continue;
            //echo $row['id'] ." "."<br>";
            $chkRule = @mysql_query("SELECT rpm.project_id 
                        FROM rule_project_mappings AS rpm 
                        WHERE rpm.rule_id = '".$row['id']."'");
                        
            if(@mysql_num_rows($chkRule) > 0)
            {
                while($row1 = @mysql_fetch_assoc($chkRule))
                {
                    if(!in_array($row1['project_id'] , $nPro1) && !in_array($row1['project_id'] , $nPro))
                        $nPro1[] = $row1['project_id'];
                }
            }
            else
            {
                $chkRuleLoc = @mysql_query("SELECT rp.project_id 
                        FROM resi_project AS rp 
                        LEFT JOIN rule_locality_mappings AS rlm ON rp.locality_id = rlm.locality_id
                        WHERE rlm.rule_id = '".$row['id']."'");
                while($row1 = @mysql_fetch_assoc($chkRuleLoc))
                {
                    if(!in_array($row1['project_id'] , $nPro1)&& !in_array($row1['project_id'] , $nPro))
                        $nPro1[] = $row1['project_id'];
                }
            }
            
        }
    }
    
    if(!empty($nPro1))
    {
        $nPro1 = implode("," , $nPro1);
    }
    //$nPro = '';
    //print'<pre>';
//    print_r($nPro);
//    print_r($nPro1);
//    print_r(array_diff($nPro , $nPro1));
//    die;
    
    $locIdArr = array();
    $sql = '';
    $city_id = '';
    $chkLoc = @mysql_query("SELECT * FROM rule_locality_mappings WHERE rule_id = '".$_GET['ruleId']."'");
    if(@mysql_num_rows($chkLoc) > 0)
    {
        $sql = @mysql_query("SELECT locality.locality_id,city.city_id FROM rule_locality_mappings AS rlm 
                                INNER JOIN locality ON rlm.locality_id = locality.locality_id
                                INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                INNER JOIN city ON suburb.city_id = city.city_id
                                WHERE rlm.rule_id = '".$_GET['ruleId']."'");
    }
    else
    {
        $sql = @mysql_query("SELECT locality.locality_id,city.city_id FROM rule_project_mappings AS rpm 
                                    INNER JOIN resi_project ON rpm.project_id = resi_project.project_id
                                    INNER JOIN locality ON resi_project.locality_id = locality.locality_id
                                    INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                    INNER JOIN city ON suburb.city_id = city.city_id
                                    WHERE rpm.rule_id = '".$_GET['ruleId']."' GROUP BY locality.locality_id");
    }
    
    while($row = @mysql_fetch_assoc($sql))
    {
        $locIdArr[] = $row['locality_id'];
        $city_id = $row['city_id'];
    }
    
    $conditions = " project_assignment_rules.id = '".$_GET['ruleId']."'";
    $joins = " INNER JOIN brokers ON project_assignment_rules.broker_id = brokers.id";
    $options  = array('joins' => $joins , 'select' => 'project_assignment_rules.*,brokers.broker_name' , 'conditions' => $conditions );
    
    $ruleAttr = ProjectAssignmentRules::find('all' , $options);
    //echo ProjectAssignmentRules::connection()->last_query."<br>";
    //print'<pre>';
//    print_r($locIdArr);
//    die;
    //echo ProjectAssignmentRules::connection()->last_query."<br>";
//    die;

    
    
    $broker_id = '';
    $broker_name = '';
    $rule_name = '';
    
    
    $locflag = 0;
    $projectflag = 0;
    $agentflag = 0;
    if(!empty($ruleAttr))
    {
        foreach($ruleAttr as $key => $val)
        {
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
    
    if(!empty($locality) && !empty($locIdArr) && count($locIdArr) == count($locality))
        $locflag = 1;
//    print'<pre>';
//    print_r($locIdArr);
//    die;
    $project = array();
    $projectIdArr = array();
    if(!empty($locIdArr))
    {
        $sql = "";
        
        if(is_string($nPro1) && !empty($nPro1))
        {
            $sql = "SELECT resi_project.project_id , resi_project.project_name AS label FROM resi_project LEFT JOIN rule_project_mappings ON resi_project.project_id = rule_project_mappings.project_id WHERE resi_project.locality_id IN (".implode("," , $locIdArr).") AND resi_project.project_id NOT IN (".$nPro1.") GROUP BY resi_project.project_id";
            //echo "thr1";
        }
        else
        {//echo "thr";
            $sql = "SELECT resi_project.project_id , resi_project.project_name AS label FROM resi_project LEFT JOIN rule_project_mappings ON resi_project.project_id = rule_project_mappings.project_id WHERE resi_project.locality_id IN (".implode("," , $locIdArr).")";
                    
        }
        //echo $sql."<br>";
       
        $project = RuleProjectMapping::find_by_sql($sql);
    }

    
    $sql = @mysql_query("SELECT project_id FROM rule_project_mappings WHERE rule_id = ".$_GET['ruleId']);
    while($row = @mysql_fetch_assoc($sql))
    {
        $projectIdArr[] = $row['project_id'];   
    }
    
    //print'<pre>';
//    print_r($project);
////    print_r($locIdArr);
//    print_r($projectIdArr);
//    echo count($projectIdArr) ." ". count($project)."<br>";
//    die;
    
    if(!empty($project) && ((!empty($projectIdArr) && count($projectIdArr) == count($project)) || (empty($projectIdArr) && count($projectIdArr) == 0)))
        $projectflag = 1;
    //echo $projectflag;die;
    
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
    
    if(!empty($agentIdArr) && !empty($agents) && count($agents) == count($agentIdArr))
        $agentflag = 1;
    
    
    //print'<pre>';
//    print_r($ruleAttr);
//    print_r($locality);
//    print_r($locIdArr);
//    print_R($project);
//    print_R($projectIdArr);
    //print_R($agents);
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