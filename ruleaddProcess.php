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
    $smarty->assign("sort",$sort);
    $smarty->assign("page",$page);
    
    
    $sellerIdForMapping = '';
    
    $sort = 'all';
    $page = '1';
    if(isset($_GET['sort']) && !empty($_GET['sort']))
        $sort = $_GET['sort'];
    
    if(isset($_GET['page']) && !empty($_GET['page']))
        $page = $_GET['page'];
      
    
            
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
    //print'<pre>';
//    print_r($_POST);
//    die;
    
    if ($_POST['btnSave'] == "Submit Rule"){
        //print'<pre>';
//        print_r($_POST);
//        die;

        @extract($_POST);
        $smarty->assign("broker_cmpny_id", $broker_cmpny_id);
        $smarty->assign("broker_cmpny", $broker_cmpny);
        $smarty->assign("rule_name", $rule_name);
        $smarty->assign("city_id", $city_id);
        $smarty->assign("locality", $locality);
        $smarty->assign("project", $project);
        $smarty->assign("agent", $agent);
        $smarty->assign("locjIdArr", $locjIdArr);
        $smarty->assign("projectjIdArr", $projectjIdArr);
        $smarty->assign("agentjIdArr", $agentjIdArr);
        
        $finallocidArr = json_decode(base64_decode($locjIdArr));
        $finalprojidArr = json_decode(base64_decode($projectjIdArr));
        $finalagentidArr = json_decode(base64_decode($agentjIdArr));

        
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
                
                global $broker_cmpny , $rule_name , $city_id,$locality,$project,$agent , $broker_cmpny_id;
                $locality_id = '';
                  
                $sql_project_assignment_rules = @mysql_query("INSERT INTO `project_assignment_rules` SET
                                                `broker_id` = '".mysql_escape_string($broker_cmpny_id)."',
                                                `rule_name` = '".mysql_escape_string($rule_name)."',
                                                `updated_by` = '".$_SESSION['adminId']."',
                                                `created_at` = '".date('Y-m-d H:i:s')."'") or die(mysql_error());
                $rule_id = mysql_insert_id();          
                //$rule_id = 1;
                if($rule_id != false) {
                     
                    
                    if(!empty($locality) && $locality != 'all')
                    {
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $sql_rule_locality_mappings = @mysql_query("INSERT INTO `rule_locality_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `locality_id` = '-1',
                                                            `city_id` = '".mysql_escape_string($city_id)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                $locality_id = @mysql_insert_id();
                                //$locality_id = '1';
//                                echo "INSERT INTO `rule_locality_mappings` SET 
//                                                                    `rule_id` = '".$rule_id."',
//                                                                    `locality_id` = '-1',
//                                                                    `city_id` = '".mysql_escape_string($city_id)."',
//                                                                    `updated_by` = '".$_SESSION['adminId']."',
//                                                                    `created_at` = '".date('Y-m-d')."'<br>";
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
                    
                    
                    if(!empty($project) && $project != 'all')
                    {
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $sql_rule_project_mappings = @mysql_query("INSERT INTO `rule_project_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `project_id` = '-1',
                                                            `locality_id` = '".$locality_id."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                //echo "INSERT INTO `rule_project_mappings` SET 
//                                                                    `rule_id` = '".$rule_id."',
//                                                                    `project_id` = '-1',
//                                                                    `locality_id` = '".$locality_id."',
//                                                                    `updated_by` = '".$_SESSION['adminId']."',
//                                                                    `created_at` = '".date('Y-m-d')."'<br>"; 
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
                    
                    
                    if(!empty($agent) && $agent != 'all')
                    {
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                             
                            if($val == 'all')
                            {
                                $sql_rule_agent_mappings = @mysql_query("INSERT INTO `rule_agent_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `agent_id` = '-1',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                //echo "INSERT INTO `rule_agent_mappings` SET 
//                                                                    `rule_id` = '".$rule_id."',
//                                                                    `agent_id` = '-1',
//                                                                    `updated_by` = '".$_SESSION['adminId']."',
//                                                                    `created_at` = '".date('Y-m-d')."'<br>";
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
                    
                    //echo "here";
//                    die;
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                } 
            });
        }
        else {
            
            ResiProject::transaction(function(){
                
                global $broker_cmpny_id , $rule_name , $city_id,$locality,$project,$agent,$ruleId , $finallocidArr ,$finalprojidArr,$finalagentidArr,$locjIdArr,$projectjIdArr,$agentjIdArr;
               
                                
                $sql_project_assignment_rules = @mysql_query("UPDATE `project_assignment_rules` SET
                                                `broker_id` = '".mysql_escape_string($broker_cmpny_id)."',
                                                `rule_name` = '".mysql_escape_string($rule_name)."',
                                                `updated_by` = '".$_SESSION['adminId']."',
                                                `updated_at` = '".date('Y-m-d H:i:s')."' WHERE id=".mysql_escape_string($ruleId));
                $rule_id = $ruleId;          

                if($rule_id != false) {
                    
                    if(!empty($finallocidArr))
                    {
                        foreach($finallocidArr as $key => $val)
                        {
                            $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_locality_mappings` WHERE locality_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id);
                            
                            //echo "DELETE FROM `rule_locality_mappings` WHERE locality_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>";
                             
                        }
                    }
                    
                    if(!empty($locality))
                    {
                        foreach($locality as $key => $val)
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

                                //echo "UPDATE `rule_locality_mappings` SET 
//                                                        `locality_id` = '".mysql_escape_string($val)."',
//                                                        `updated_by` = '".$_SESSION['adminId']."',
//                                                        `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id)."<br>";    
                            }
                            else
                            {
                                $sql_rule_locality_mappings = @mysql_query("INSERT INTO `rule_locality_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `locality_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());

                                //echo "INSERT INTO `rule_locality_mappings` SET 
//                                                            `rule_id` = '".$rule_id."',
//                                                            `locality_id` = '".mysql_escape_string($val)."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `created_at` = '".date('Y-m-d')."'"."<br>";
                            }
                            
                                    
                        }
                    }
                    
                    
                    if(!empty($finalprojidArr))
                    {
                        foreach($finalprojidArr as $key => $val)
                        {
                            $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_project_mappings` WHERE project_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id); 
                            //echo "DELETE FROM `rule_project_mappings` WHERE project_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>";
                        }
                    }
                    if(!empty($project))
                    {
                        foreach($project as $key => $val)
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

                                //echo "UPDATE `rule_project_mappings` SET 
//                                                            `project_id` = '".mysql_escape_string($val)."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id)."<br>";
                            }
                            else
                            {
                                $sql_rule_project_mappings = @mysql_query("INSERT INTO `rule_project_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `project_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                //echo "INSERT INTO `rule_project_mappings` SET 
//                                                            `rule_id` = '".$rule_id."',
//                                                            `project_id` = '".mysql_escape_string($val)."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `created_at` = '".date('Y-m-d')."'"."<br>";
                            }
                                  
                        }
                    }
                    
                    if(!empty($finalagentidArr))
                    {
                        foreach($finalagentidArr as $key => $val)
                        {
                            $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_agent_mappings` WHERE agent_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id);
                            //echo "DELETE FROM `rule_agent_mappings` WHERE agent_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>"; 
                        }
                    }
                    
                    if(!empty($agent))
                    {
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            $chkSql = @mysql_query("SELECT * FROM `rule_agent_mappings` WHERE agent_id = ".mysql_escape_string($val)." AND rule_id = ".mysql_escape_string($rule_id));
                            
                            if(@mysql_num_rows($chkSql) > 0)
                            {
                                $sql_rule_agent_mappings = @mysql_query("UPDATE  `rule_agent_mappings` SET 
                                                            `agent_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));
                                //echo "UPDATE `rule_agent_mappings` SET 
//                                                            `agent_id` = '".mysql_escape_string($val)."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id)."<br>";
                            }
                            else
                            {
                                $sql_rule_agent_mappings = @mysql_query("INSERT INTO `rule_agent_mappings` SET 
                                                            `rule_id` = '".$rule_id."',
                                                            `agent_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                //echo "INSERT INTO `rule_agent_mappings` SET 
//                                                            `rule_id` = '".$rule_id."',
//                                                            `agent_id` = '".mysql_escape_string($val)."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `created_at` = '".date('Y-m-d')."'"."<br>"; 
                            }
                            
                            
                                
                        }
                    }
                    
                   //die("here");
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
    else if(!empty($_POST['city_id']))
    {
        $conditions = " rule_locality_mappings.city_id = '".mysql_escape_string($_POST['city_id'])."'";
                
        $options = array('conditions' => $conditions);        
        $ruleAttr = RuleLocalityMappings::find('all' ,$options);
//        print'<pre>';
//        print_r($ruleAttr);
//        die;
        $data = array();
        
        if(!empty($ruleAttr))
        {
            $i = 0;
            foreach($ruleAttr as $key => $val)
            {
                $locality = array();
                
                
                $projectflag = 0;
                $sqlQuery = @mysql_query("SELECT * FROM rule_locality_mappings WHERE rule_id = '".$val->id."' AND city_id='".mysql_escape_string($_POST['city_id'])."'");
                
                if(@mysql_num_rows($sqlQuery))
                {
                    $conditions = " rule_locality_mappings.rule_id = ".$val->id." and rule_locality_mappings.city_id = '".mysql_escape_string($_POST['city_id'])."'";
                
                    $options = array('select' => " locality_id" , 'conditions' => $conditions);
                    $localityAttr = RuleLocalityMappings::find('all',$options);
                }
                else
                {
                    $conditions = " rule_locality_mappings.rule_id = ".$val->id." and rule_locality_mappings.city_id = '".mysql_escape_string($_POST['city_id'])."'";
                
                    $joins = " LEFT JOIN locality ON rule_locality_mappings.locality_id = locality.locality_id
                                LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                LEFT JOIN city ON suburb.city_id = city.city_id";
                                
                    $options = array('joins' => $joins , 'select' => " locality.label AS locality" , 'conditions' => $conditions);
                    $localityAttr = RuleLocalityMappings::find('all',$options);
                    $NumRows = count($localityAttr);
                    
                    if(!empty($RowsPerPage) && !empty($Offset))
                    {
                        $options = array('joins' => $joins , 'select' => " locality.label AS locality" , 'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => $conditions);
                        $localityAttr = RuleLocalityMappings::find('all',$options);
                    }
                    else
                    {
                        $options = array('joins' => $joins , 'select' => " locality.label AS locality" , 'limit' => $RowsPerPage ,'conditions' => $conditions);
                        $localityAttr = RuleLocalityMappings::find('all',$options);
                    }
                }
                
                
                
                print'<pre>';
                echo RuleLocalityMappings::connection()->last_query."<br>";
                print_r($localityAttr);
                continue;
                
                
                if(!empty($localityAttr))
                {
                    foreach($localityAttr as $k => $v)
                    {
                        if($v->locality_id == '-1')
                        {
                            $locality[] = 'All';
                            $projectflag = 1;
                        }
                        else
                            $locality[] = $v->locality;
                    }
                }
                

                $project = array();
                
                if($projectflag == '1')
                {
                    $conditions = " rule_id = '".$val->id."' AND locality_id = '-1'";
                    $options = array( 'select' => " project_id" , 'conditions' => $conditions);
                    $projectAttr = RuleProjectMapping::find('all',$options);
                }
                else
                {
                    $conditions = " rule_project_mappings.rule_id = ".$val->id;
                    $joins = " LEFT JOIN resi_project ON rule_project_mappings.project_id = resi_project.id";
                    $options = array('joins' => $joins , 'select' => " resi_project.project_name" , 'conditions' => $conditions);
                    $projectAttr = RuleProjectMapping::find('all',$options);
                }
                
                if(!empty($projectAttr))
                {
                    foreach($projectAttr as $k => $v)
                    {
                        if(isset($v->project_id) && $v->project_id = '-1')
                            $project[] = 'All';
                        else
                            $project[] = $v->project_name;
                    }
                }
               
                
                $conditions = " rule_id = '".$val->id."' AND agent_id = '-1'";
                $options = array('select' => " agent_id" , 'conditions' => $conditions);
                $agentAttr = RuleAgentMappings::find('all',$options);
                $agent = array();
                if(!empty($agentAttr))
                {
                    foreach($agentAttr as $k => $v)
                    {
                        $agent[] = 'All';
                    }
                }
                else
                {
                    $conditions = " rule_agent_mappings.rule_id = '".$val->id."' AND broker_contacts.type ='Agent' AND rule_agent_mappings.agent_id !='-1'";
                    $joins = " LEFT JOIN broker_contacts ON rule_agent_mappings.agent_id = broker_contacts.broker_id";
                    $options = array('joins' => $joins , 'select' => " broker_contacts.name" , 'conditions' => $conditions);
                    $agentAttr = RuleAgentMappings::find('all',$options);
                    //echo "<br>here -->".RuleAgentMappings::connection()->last_query."<br>";
//                    print'<pre>';
//                    print_r($localityAttr);
//                    print_r($agentAttr);
                    
                    if(!empty($agentAttr))
                    {
                        foreach($agentAttr as $k => $v)
                        {
                            $agent[] = $v->name;
                        }
                    }
                    
                }
                //continue;
                //print'<pre>';
//                print_r($localityAttr);
//                continue;
                if(!empty($localityAttr))
                {
                    
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
                //print_r($localityAttr);
//                continue;
                print_r($data);
                continue;
            }
        }
        die;
        $locarr = array();
        
        $sql = @mysql_query("SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".mysql_escape_string($_POST['city_id'])." AND locality.locality_id NOT IN (SELECT locality_id FROM rule_locality_mappings)");
        while($row = @mysql_fetch_assoc($sql))
        {
            $locarr[$row['locality_id']] = $row['label'];    
        }
        
//        print'<pre>';
//        print_r($locarr);
//        print_r($data);
//        die;
        $smarty->assign("city_id" , $_POST['city_id']);
        $smarty->assign("locality" , $locarr);
    }
    
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
    $smarty->assign("ruleDataArr" , $data);
    $smarty->assign("ruleId", $ruleId); 
    
?>

