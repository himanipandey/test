<?php

/**
 * @author AKhan
 * @copyright 2013
 */
    $accessSeller = '';
    
    $smarty->assign("accessSeller",$accessSeller);
    
    $ruleId = '';
    if(!empty($_REQUEST['ruleId']))
        $ruleId = $_REQUEST['ruleId'];
    
    $smarty->assign("ruleId", $ruleId);
    
    $sellerIdForMapping = '';
    
    $sort = 'all';
    $page = '1';
    if(isset($_GET['sort']) && !empty($_GET['sort']))
        $sort = $_GET['sort'];
    
    if(isset($_GET['page']) && !empty($_GET['page']))
        $page = $_GET['page'];
        
    $smarty->assign("sort",$sort);
    $smarty->assign("page",$page);
            
    if(isset($_GET['page'])) {
        $Page = $_GET['page'];
    } else {
        $Page = 1;
    }
    $RowsPerPage = '10';
    $PageNum = 1;
    if(isset($_GET['page'])) {
        $PageNum = $_GET['page'];
    }

    if($_POST['search']!='' && ($_POST['broker']!='')){   
        $Offset = 0;

    }else{
        $Offset = ($PageNum - 1) * $RowsPerPage;
    }
    
    if ($_POST['btnSave'] == "Submit Rule"){

        @extract($_POST);
        $smarty->assign("broker_cmpny", $broker_cmpny);
        $smarty->assign("rule_name", $rule_name);
        $smarty->assign("city_id", $city_id);
        $smarty->assign("locality", $locality);
        $smarty->assign("project", $project);
        $smarty->assign("agent", $agent);
        $smarty->assign("locjIdArr", $locjIdArr);
        $smarty->assign("projectjIdArr", $projectjIdArr);
        $smarty->assign("agentjIdArr", $agentjIdArr);
        
        $finallocidArr = $locality;
        if(!empty($locjIdArr))
        {
            $finallocidArr = array_merge($locality, json_decode(base64_decode($locjIdArr)));
        }
        
        
        $finalprojidArr = $project;
        if(!empty($projectjIdArr))
        {
            $finalprojidArr = array_merge($project, json_decode(base64_decode($projectjIdArr)));
        }
        
        $finalagentidArr = $agent;
        if(!empty($locjIdArr))
        {
            $finalagentidArr = array_merge($agent, json_decode(base64_decode($agentjIdArr)));
        }
        //print'<pre>';
//        
//        print_r($finalagentidArr);
//        print_r($finalprojidArr);
//        print_r($finallocidArr);
//        die;
        $smarty->assign("ruleId", $ruleId);
        
        if(empty($broker_cmpny)) {
             $ErrorMsg["broker_cmpny"] = "Please enter Company name.";
        }
        else if(empty($rule_name)){
             $ErrorMsg["rule_name"] = "Please enter Rule.";
        }
        else if(empty($city_id)){
             $ErrorMsg["city_id"] = "Please select City.";
        }
        
        //print'<pre>';
//        print_r($_POST);
//        print_r($ErrorMsg);
//        die;

        if(!empty($ErrorMsg)) {
                 //Do Nothing
        } 
        else if (empty($ruleId)){	
            
            ResiProject::transaction(function(){
                
                global $broker_cmpny , $rule_name , $city_id,$locality,$project,$agent;
               
                  
                $sql_project_assignment_rules = @mysql_query("INSERT INTO `project_assignment_rules` SET
                                                `broker_id` = '".mysql_escape_string($broker_cmpny)."',
                                                `rule_name` = '".mysql_escape_string($rule_name)."',
                                                `updated_by` = '".$_SESSION['adminId']."',
                                                `created_at` = '".date('Y-m-d H:i:s')."'") or die(mysql_error());
                $rule_id = mysql_insert_id();          

                if($rule_id != false) {
                    
                    
                    if(!empty($locality))
                    {
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                                
                            $sql_rule_locality_mappings = @mysql_query("INSERT INTO `rule_locality_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `locality_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());        
                        }
                    }
                    
                    if(!empty($project))
                    {
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                             
                            $sql_rule_project_mappings = @mysql_query("INSERT INTO `rule_project_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `project_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());      
                        }
                    }
                    
                    if(!empty($agent))
                    {
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                             
                            $sql_rule_agent_mappings = @mysql_query("INSERT INTO `rule_agent_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `agent_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());    
                        }
                    }
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                } 
            });
        }
        else {
            
            ResiProject::transaction(function(){
                
                global $broker_cmpny , $rule_name , $city_id,$locality,$project,$agent,$ruleId , $finallocidArr ,$finalprojidArr,$finalagentidArr;
               
                                
                $sql_project_assignment_rules = @mysql_query("UPDATE `project_assignment_rules` SET
                                                `broker_id` = '".mysql_escape_string($broker_cmpny)."',
                                                `rule_name` = '".mysql_escape_string($rule_name)."',
                                                `updated_by` = '".$_SESSION['adminId']."',
                                                `updated_at` = '".date('Y-m-d H:i:s')."' WHERE id=".mysql_escape_string($ruleId));
                $rule_id = $ruleId;          

                if($rule_id != false) {
                    
                    
                    if(!empty($finallocidArr))
                    {
                        foreach($finallocidArr as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            $chkSql = @mysql_query("SELECT * FROM `rule_locality_mappings` WHERE locality_id = ".mysql_escape_string($val)." AND rule_id = ".mysql_escape_string($rule_id));
                            
                            if(@mysql_num_rows($chkSql) > 0)
                            {
                                $sql_rule_locality_mappings = @mysql_query("UPDATE `rule_locality_mappings` SET 
                                                        `locality_id` = '".mysql_escape_string($val)."',
                                                        `updated_by` = '".$_SESSION['adminId']."',
                                                        `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));    
                            }
                            else
                            {
                                $sql_rule_locality_mappings = @mysql_query("INSERT INTO `rule_locality_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `locality_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                            }
                            
                                    
                        }
                    }
                    
                    if(!empty($finalprojidArr))
                    {
                        foreach($finalprojidArr as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            $chkSql = @mysql_query("SELECT * FROM `rule_project_mappings` WHERE project_id = ".mysql_escape_string($val)." AND rule_id = ".mysql_escape_string($rule_id));
                            
                            if(@mysql_num_rows($chkSql) > 0)
                            {
                                $sql_rule_project_mappings = @mysql_query("UPDATE `rule_project_mappings` SET 
                                                            `project_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));
                            }
                            else
                            {
                                $sql_rule_project_mappings = @mysql_query("INSERT INTO `rule_project_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `project_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                            }
                                  
                        }
                    }
                    
                    if(!empty($finalagentidArr))
                    {
                        foreach($finalagentidArr as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            $chkSql = @mysql_query("SELECT * FROM `rule_agent_mappings` WHERE agent_id = ".mysql_escape_string($val)." AND rule_id = ".mysql_escape_string($rule_id));
                            
                            if(@mysql_num_rows($chkSql) > 0)
                            {
                                $sql_rule_agent_mappings = @mysql_query("UPDATE INTO `rule_agent_mappings` SET 
                                                            `agent_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));
                            }
                            else
                            {
                                $sql_rule_agent_mappings = @mysql_query("INSERT INTO `rule_agent_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `agent_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error()); 
                            }
                            
                            
                                
                        }
                    }
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                }
            
            });
        }
         
            
        if(count($ErrorMsg)>0) {
            $smarty->assign("ErrorMsg", $ErrorMsg);    
        }
        else {
            header("Location:ruleadd.php?page=1&sort=all"); 
        }
        /**********end code project add******************/        
    }
    else
    {
        $ruleAttr = ProjectAssignmentRules::find('all');
        $NumRows = count($ruleAttr);
        if(!empty($RowsPerPage) && !empty($Offset))
        {
            $options = array('joins' => $join , 'limit' => $RowsPerPage , 'offset' => $Offset);
            $ruleAttr = ProjectAssignmentRules::find('all' , $options);
        }
        else
        {
            $options = array('joins' => $join ,'limit' => $RowsPerPage);
            $ruleAttr = ProjectAssignmentRules::find('all' , $options);
        }
        
        
        $data = array();
        if(!empty($ruleAttr))
        {
            $i = 0;
            foreach($ruleAttr as $key => $val)
            {
                $locality = array();
                $conditions = " rule_locality_mappings.rule_id = ".$val->id;
                $joins = " LEFT JOIN locality ON rule_locality_mappings.locality_id = locality.locality_id";
                $options = array('joins' => $joins , 'select' => " locality.label AS locality" , 'conditions' => $conditions);
                $localityAttr = RuleLocalityMappings::find('all',$options);
                
                if(!empty($localityAttr))
                {
                    foreach($localityAttr as $k => $v)
                    {
                        $locality[] = $v->locality;
                    }
                }
                
                
                $project = array();
                $conditions = " rule_project_mappings.rule_id = ".$val->id;
                $joins = " LEFT JOIN resi_project ON rule_project_mappings.project_id = resi_project.id";
                $options = array('joins' => $joins , 'select' => " resi_project.project_name" , 'conditions' => $conditions);
                $projectAttr = RuleProjectMapping::find('all',$options);
                
                if(!empty($projectAttr))
                {
                    foreach($projectAttr as $k => $v)
                    {
                        $project[] = $v->project_name;
                    }
                }
                
                $agent = array();
                $conditions = " rule_agent_mappings.rule_id = ".$val->id." AND broker_contacts.type ='Agent'";
                $joins = " LEFT JOIN broker_contacts ON rule_agent_mappings.agent_id = broker_contacts.broker_id";
                $options = array('joins' => $joins , 'select' => " broker_contacts.name" , 'conditions' => $conditions);
                $agentAttr = RuleAgentMappings::find('all',$options);
                
                if(!empty($agentAttr))
                {
                    foreach($agentAttr as $k => $v)
                    {
                        $agent[] = $v->name;
                    }
                }
                
                $data[$i]['id'] = $val->id;
                $data[$i]['rule_name'] = $val->rule_name;
                
                
                foreach($val->created_at as $k => $v)
                {
                    if($k == "date")
                    {
                        $data[$i]['created_at'] = date('d/m/Y' ,strtotime($v));
                        break;
                    }
                }
                
                $count = (count($locality) > count($project))?count($locality):count($project);
                $count = (count($agent) > $count)?count($agent):$count;
                $data[$i]['locality'] = $locality;
                $data[$i]['project'] = $project;
                $data[$i]['agent'] = $agent;
                $data[$i]['count'] = $count;
                $i++;
            }
        }
        //print'<pre>';
//        print_r($data);
//        die;
        
        $MaxPage = (ceil($NumRows/$RowsPerPage))?ceil($NumRows/$RowsPerPage):'1' ;
        $Num = $_GET['num'];
        $Sort = $_GET['sort'];
        if ($PageNum > 1) {
                $Page = $PageNum - 1;
                $Prev = " <a href=\"$Self?page=$Page&sort=$Sort$link\">[Prev]</a> ";
                $First = " <a href=\"$Self?page=1&sort=$Sort$link\">[First Page]</a> ";
        } else {
                $Prev  = ' [Prev] ';
                $First = ' [First Page] ';
        }
        if ($PageNum < $MaxPage) {
                $Page = $PageNum + 1;
                $Next = " <a href=\"$Self?page=$Page&sort=$Sort$link\">[Next]</a> ";
                $Last = " <a href=\"$Self?page=$MaxPage&sort=$Sort$link\">[Last Page]</a> ";
        } else {
                $Next = ' [Next] ';
                $Last = ' [Last Page] ';
        }
        $Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";
        $smarty->assign("Pagginnation", $Pagginnation);
        $smarty->assign("Sorting", $Sorting);
        $smarty->assign("NumRows",$NumRows);
        $smarty->assign("NumRows" , $NumRows);
        $smarty->assign("ruleDataArr" , $data);
                                
    }
?>

