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
    
    $ruleIdArr = array();

    if($_POST['search']!='' && ($_POST['broker']!='')){   
        $Offset = 0;

    }else{
        $Offset = ($PageNum - 1) * $RowsPerPage;
    }
    $rule_id = '';
    $chkLoc = 0;
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
        
        $finallocidArr = json_decode(base64_decode($dlocjIdArr));
        $finalprojidArr = json_decode(base64_decode($dprojectjIdArr));
        $finalagentidArr = json_decode(base64_decode($dagentjIdArr));

        
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
        
        
        if(!empty($ErrorMsg)) {
                 //Do Nothing
        } 
        else if (empty($ruleId)){	
            
            ResiProject::transaction(function(){
                
                global $broker_cmpny , $rule_name , $city_id,$locality,$project,$agent , $broker_cmpny_id, $rule_id;
                
                $sql_project_assignment_rules = @mysql_query("INSERT INTO `project_assignment_rules` SET
                                            `broker_id` = '".mysql_escape_string($broker_cmpny_id)."',
                                            `rule_name` = '".mysql_escape_string($rule_name)."',
                                            `updated_by` = '".$_SESSION['adminId']."',
                                            `created_at` = '".date('Y-m-d H:i:s')."'") or die(mysql_error());
                $rule_id = mysql_insert_id();          
                //$rule_id = 4;
                $chkPAll = 0;
                $countProject1 = 0;
                $countProject2 = 0;
                if($rule_id != false) {
                    if(!empty($project) && $project != 'all')
                    {
                        $insertLocpa = ' INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) values';
                        $str1 = '';
                        
                        $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                            LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                            LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                            LEFT JOIN city ON suburb.city_id = city.city_id
                                            WHERE city.city_id = '".mysql_escape_string($city_id)."'
                                            AND resi_project.project_id NOT IN (SELECT rpm.project_id FROM rule_project_mappings AS rpm INNER JOIN project_assignment_rules AS par ON rpm.rule_id = par.id WHERE par.broker_id = '".$broker_cmpny_id."')");
                        $countProject1 = @mysql_num_rows($fetchProQuery);
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkPAll = 1;
                                break;
                                 
                            }
                            else
                            {
                                $str1 .= " ('".$rule_id."','".$val."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                $countProject2 += 1;
                            }
                                  
                        }
                        
                        if($countProject1 == $countProject2)
                            $chkPAll = 1;
                        
                        if($chkPAll == 0 && $countProject1 != $countProject2 && !empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            //echo $insertLocpa."<br>";
                            $sql_rule_project_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    else
                    {
                        $chkPAll = 1;
                    }
                    //echo " chkPAll : ".$chkPAll."<br>";
                    $chkLAll = 0;
                    $countLocality1 = 0;
                    $countLocality2 = 0;
                    if(!empty($locality) && $locality != 'all')
                    {
                        $insertLocpa = 'INSERT INTO `rule_locality_mappings` (rule_id,locality_id,updated_by,created_at) values';
                        $str1 = '';
                        
                        $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".mysql_escape_string($city_id)." AND locality.locality_id NOT IN (SELECT rlm.locality_id FROM rule_locality_mappings AS rlm INNER JOIN project_assignment_rules AS par ON rlm.rule_id = par.id WHERE par.broker_id = '".$broker_cmpny_id."')");
                        
                        $countLocality1 = @mysql_num_rows($fetchLocQuery);
                        //echo " -->".$countLocality1."<-- <br>";
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all' && $chkPAll == 1)
                            {
                                $chkLAll = 1;
                                while($row = @mysql_fetch_assoc($fetchLocQuery))
                                {
                                    $str1 .= " ('".$rule_id."','".$row['locality_id']."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                }
                                break;                                                                
                            }
                            else
                            {
                                if($chkPAll != 1)
                                    break;
                                $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                $countLocality2 += 1; 
                            }
                            
                            if($countLocality1 == $countLocality2)
                                $chkLAll = 1;
                                    
                        }
                        //echo $chkLAll." ".$str1;
                        if(!empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            //echo $insertLocpa."<br>";
                            $sql_rule_locality_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                    if(!empty($agent) && $agent != 'all')
                    {
                        $insertLocpa = ' INSERT INTO `rule_agent_mappings` (rule_id,agent_id,updated_by,created_at) values';
                        $str1 = '';
                        $fetchAgentQuery = @mysql_query("SELECT agents.id FROM agents WHERE agents.broker_id = ".$broker_cmpny_id." AND agents.id NOT IN (SELECT ram.agent_id FROM rule_agent_mappings AS ram INNER JOIN project_assignment_rules AS par ON ram.rule_id = par.id WHERE ram.rule_id = '".$broker_cmpny_id."')");
                        $countAgent1 = @mysql_num_rows($fetchAgentQuery);
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                             
                            if($val == 'all')
                            {
                                while($row = @mysql_fetch_assoc($fetchAgentQuery))
                                {
                                    $str1 .= " ('".$rule_id."','".mysql_escape_string($row['id'])."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                }
                                break;
                            }
                            else
                            {
                                $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                            }    
                        }
                        
                        //echo $chkSql." ".$countAgents;
//                        die;
                        if(!empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            $sql_rule_agent_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                        //echo $insertLocpa;die;
                        
                    }
                    
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                } 
                
                
            });
            
            if(!empty($rule_id))
            {
                header("Location:ruleadd.php?city_id=".$city_id."&broker_cmpny=".trim($broker_cmpny)."&page=1&sort=all");
                echo $rule_id;
                die;
            }
            
        }
        else {
            
            ResiProject::transaction(function(){
                
                global $broker_cmpny_id , $broker_cmpny , $rule_name , $city_id,$locality,$project,$agent,$ruleId , $finallocidArr ,$finalprojidArr,$finalagentidArr,$locjIdArr,$projectjIdArr,$agentjIdArr;
               $locality_id = '';
               //echo $broker_cmpny;die;
                //print'<pre>';
//                print_r($_POST);
//                echo "<br>==========Locality==========<br>";
//                print_r($finallocidArr);
//                echo "<br>==========Project==========<br>";
//                print_r($finalprojidArr); 
//                print_r($project);               
//                echo "<br>==========Agent==========<br>";
//                print_r($finalagentidArr);
//                print_r($agent);
                //die;            
                $sql_project_assignment_rules = @mysql_query("UPDATE `project_assignment_rules` SET
                                                `broker_id` = '".mysql_escape_string($broker_cmpny_id)."',
                                                `rule_name` = '".mysql_escape_string(trim($rule_name))."',
                                                `updated_by` = '".$_SESSION['adminId']."',
                                                `updated_at` = '".date('Y-m-d H:i:s')."' WHERE id=".mysql_escape_string($ruleId));
                $rule_id = $ruleId;          

                if($rule_id != false) {
                    if(!empty($finalprojidArr))
                    {
                        $delall = "DELETE FROM `rule_project_mappings` WHERE project_id IN (";
                        $str1 = '';
                        foreach($finalprojidArr as $key => $val)
                        {
                            if($val == 'all')
                                break;
                            else
                            {
                                $str1 .= "'".$val."'".",";
                            }
                        }
                        
                        if(!empty($str1))
                        {
                            $delall .= trim($str1 , ",").") AND rule_id = '".$rule_id."'";
                            //echo "Delete for some $delall<br>";
                            $sql_rule_project_mappings = @mysql_query($delall);
                        }
                    }
                    $chkPAll = 0;
                    if(!empty($project))
                    {
                        $insertLocpa = ' INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) values';
                        $str1 = '';
                        
                        $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                            LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                            LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                            LEFT JOIN city ON suburb.city_id = city.city_id
                                            WHERE city.city_id = '".mysql_escape_string($city_id)."'
                                            AND resi_project.project_id NOT IN (SELECT rpm.project_id FROM rule_project_mappings AS rpm INNER JOIN project_assignment_rules AS par ON rpm.rule_id = par.id WHERE par.broker_id = '".$broker_cmpny_id."')");
                        $countProject1 = @mysql_num_rows($fetchProQuery);
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkPAll = 1;
                                break;
                                 
                            }
                            else
                            {
                                $chkPro = @mysql_query("SELECT * FROM rule_project_mappings WHERE rule_id = '".$rule_id."' AND project_id = '".$val."'");
                                if(!(@mysql_num_rows($chkPro) > 0))
                                    $str1 .= " ('".$rule_id."','".$val."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                $countProject2 += 1;
                            }
                                  
                        }
                        
                        if($countProject1 == $countProject2)
                            $chkPAll = 1;
                        
                        if($chkPAll == 0 && $countProject1 != $countProject2 && !empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            //echo $insertLocpa."<br>";
                            $sql_rule_project_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    else
                    {
                        $chkPAll = 1;
                    }
                    
                    //echo " ChkPAll ".$chkPAll."<br>";
                    
                    if(!empty($finallocidArr))
                    {
                        $delall = 'DELETE FROM `rule_locality_mappings` WHERE locality_id IN (';
                        $str1 = '';                        
                        foreach($finallocidArr as $key => $val)
                        {
                            if($val == 'all')
                            {
                                $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = '".mysql_escape_string($city_id)."'");
                                while($row = @mysql_fetch_assoc($fetchLocQuery))
                                {
                                    $str1 .= "'".$row['locality_id']."'".",";
                                           
                                }
                                break;
                            }
                            else
                            {
                                $str1 .= "'".mysql_escape_string($val)."'".",";
                            }
                        }
                        if(!empty($str1))
                        {
                            $delall .= trim($str1 , ",").") AND rule_id = '".$rule_id."'";
                            //echo " Delete some".$delall."<br>";
                            //die;
                            $sql_rule_locality_mappings = @mysql_query($delall);
                        }
                    }
                    else if(empty($finallocidArr) && $chkPAll == 0 && !empty($locality))
                    {
                        $delall = 'DELETE FROM `rule_locality_mappings` WHERE locality_id IN (';
                        $str1 = '';                        
                        foreach($locality as $key => $val)
                        {
                            if($val == 'all')
                            {
                                $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = '".mysql_escape_string($city_id)."'");
                                while($row = @mysql_fetch_assoc($fetchLocQuery))
                                {
                                    $str1 .= "'".$row['locality_id']."'".",";
                                           
                                }
                                break;
                            }
                            else
                            {
                                $str1 .= "'".mysql_escape_string($val)."'".",";
                            }
                        }
                        
                        if(!empty($str1))
                        {
                            $delall .= trim($str1 , ",").") AND rule_id = '".$rule_id."'";
                            //echo " Delete All".$delall."<br>";
                            //die;
                            $sql_rule_locality_mappings = @mysql_query($delall);
                        }
                    }
                    
                    $chkLAll = 0;
                    $countLocality1 = 0;
                    $countLocality2 = 0;
                    if(!empty($locality) && $locality != 'all')
                    {
                        $insertLocpa = 'INSERT INTO `rule_locality_mappings` (rule_id,locality_id,updated_by,created_at) values';
                        $str1 = '';
                        
                        $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".mysql_escape_string($city_id)." AND locality.locality_id NOT IN (SELECT rlm.locality_id FROM rule_locality_mappings AS rlm INNER JOIN project_assignment_rules AS par ON rlm.rule_id = par.id WHERE par.broker_id = '".$broker_cmpny_id."')");
                        
                        $countLocality1 = @mysql_num_rows($fetchLocQuery);
                        //echo " -->".$countLocality1."<-- <br>";
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all' && $chkPAll == 1)
                            {
                                $chkLAll = 1;
                                while($row = @mysql_fetch_assoc($fetchLocQuery))
                                {
                                    $chkLoc = @mysql_query("SELECT * FROM rule_locality_mappings WHERE rule_id = '".$rule_id."' AND locality_id = '".$row['locality_id']."'");
                                    if(!(@mysql_num_rows($chkLoc) > 0))
                                        $str1 .= " ('".$rule_id."','".$row['locality_id']."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                }
                                break;                                                                
                            }
                            else
                            {
                                if($chkPAll != 1)
                                    break;
                                $chkLoc = @mysql_query("SELECT * FROM rule_locality_mappings WHERE rule_id = '".$rule_id."' AND locality_id = '".$val."'");
                                if(!(@mysql_num_rows($chkLoc) > 0))
                                    $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                $countLocality2 += 1; 
                            }
                            
                            if($countLocality1 == $countLocality2)
                                $chkLAll = 1;
                                    
                        }
                        //echo $chkLAll." ".$str1;
                        if(!empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            //echo $insertLocpa."<br>";
                            $sql_rule_locality_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                    
                    if(!empty($finalagentidArr))
                    {
                        $delall = "DELETE FROM `rule_agent_mappings` WHERE agent_id IN (";
                        $str = '';
                        foreach($finalagentidArr as $key => $val)
                        {
                            if($val == 'all')
                            {
                                $chkSql = @mysql_query("SELECT ram.* FROM `rule_agent_mappings` AS ram INNER JOIN project_assignment_rules AS par ON ram.rule_id = par.id WHERE par.id = '".$rule_id."' AND par.broker_id = '".$broker_cmpny_id."'");
                                
                                if(@mysql_num_rows($chkSql) > 0)
                                {
                                    while($row = @mysql_fetch_assoc($chkSql))
                                    {
                                        $str .= "'".$row['agent_id']."'".",";
                                    }
                                }
                                break;
                            }
                            else
                            {
                                $str .= "'".$val."'".",";
                            }
                        }
                        
                        if(!empty($str))
                        {
                            $delall .= trim($str , ",").") AND rule_id = '".$rule_id."'";
                            //echo "For some agent ".$delall."<br>";
                            $sql_rule_locality_mappings = @mysql_query($delall) or die(mysql_error());
                        }
                    }
                    //print'<pre>';
//                    print_r($agent);
//                    print_r($finalagentidArr);
//                    die;
                    if(!empty($agent) && $agent != 'all')
                    {
                        $insertLocpa = ' INSERT INTO `rule_agent_mappings` (rule_id,agent_id,updated_by,created_at) values';
                        $str1 = '';
                        $fetchAgentQuery = @mysql_query("SELECT agents.id FROM agents WHERE agents.broker_id = ".$broker_cmpny_id." AND agents.id NOT IN (SELECT ram.agent_id FROM rule_agent_mappings AS ram INNER JOIN project_assignment_rules AS par ON ram.rule_id = par.id WHERE ram.rule_id = '".$broker_cmpny_id."')");
//                        $countAgent1 = @mysql_num_rows($fetchAgentQuery);
                        //echo $countAgent1;die;
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                             
                            if($val == 'all')
                            {
                                while($row = @mysql_fetch_assoc($fetchAgentQuery))
                                {
                                    $chkAgent = @mysql_query("SELECT * FROM rule_agent_mappings WHERE rule_id = '".$rule_id."' AND agent_id = '".$row['id']."'");
                                    if(!(@mysql_num_rows($chkAgent) > 0))
                                        $str1 .= " ('".$rule_id."','".mysql_escape_string($row['id'])."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                                }
                                break;
                            }
                            else
                            {
                                 $chkAgent = @mysql_query("SELECT * FROM rule_agent_mappings WHERE rule_id = '".$rule_id."' AND agent_id = '".$val."'");
                                 if(!(@mysql_num_rows($chkAgent) > 0))
                                    $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d H:i:s')."')".",";
                            }    
                        }
                        
                        //echo $chkSql." ".$countAgents;
//                        die;
                        if(!empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            //echo $insertLocpa."<br>";
                            $sql_rule_agent_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                    
                    //echo "heer";die;
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                }
            
            });
            
            
        }
         
            
        if(count($ErrorMsg)>0) {
            $smarty->assign("ErrorMsg", $ErrorMsg);    
        }
        else if(!empty($ruleId))
        {
            //header("Location:ruleadd.php?city_id=".$city_id."&page=1&sort=all");
            header("Location:ruleadd.php?city_id=".$city_id."&broker_cmpny=".trim($broker_cmpny)."&page=1&sort=all");
            //echo $rule_id;
//                die;
        }
        else {
            header("Location:ruleadd.php?page=1&sort=all"); 
        }
        /**********end code project add******************/        
    }
    else if(!empty($_POST['city_id']) || !empty($_GET['city_id']))
    {
        $cityID = '';
        $companyID = '';
        if(!empty($_POST['city_id']))
        {
            $cityID = $_POST['city_id'];
            $companyID = !empty($_POST['broker_cmpny'])?$_POST['broker_cmpny']:'';
        }
        else if(!empty($_GET['city_id']))
        {
            $cityID = $_GET['city_id'];
            $companyID = !empty($_GET['broker_cmpny'])?$_GET['broker_cmpny']:'';
        }  
        $data = array();
        $ruleIdArr = array();
        $agentOptions = array();
        $i = 0;
        $chkExist = BrokerCompany::find('id' , array('conditions' => " broker_name = '".mysql_real_escape_string($companyID)."'"));
        if(!empty($chkExist->id))
        {
            $allloc = mysql_escape_string(trim($chkExist->id));
            $sql = @mysql_query("SELECT agents.id AS agent_id, broker_contacts.name AS agent_name FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".$allloc." AND broker_contacts.type='Agent' AND agents.id");
            while($row = @mysql_fetch_assoc($sql))
            {
                $agentOptions[$i]['agent_id'] = $row['agent_id'];
                $agentOptions[$i]['agent_name'] = $row['agent_name']; 
                $i++;    
            }
        }
        
        if(!empty($agentOptions))
        {
            $agentOptions = json_decode(json_encode($agentOptions), FALSE);
        }
        
        
        $ruleAttr = ProjectAssignmentRules::find('all' , array('conditions' => "broker_id = '".$chkExist->id."'"));
        //print'<pre>';
//        print_r($ruleAttr);
//        die;
        $data = array();
        if(!empty($ruleAttr))
        {
            $fetchLocQuery = @mysql_query("SELECT COUNT(locality.locality_id) AS locount FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = '".mysql_escape_string($cityID)."'");
            $locount = @mysql_fetch_assoc($fetchLocQuery);
            $locount = $locount['locount'];
            $locounter = 0;
            
            $fetchProQuery = @mysql_query("SELECT COUNT(resi_project.project_id) AS procount FROM resi_project 
                                INNER JOIN locality ON resi_project.locality_id = locality.locality_id
                                INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                INNER JOIN city ON suburb.city_id = city.city_id
                                WHERE city.city_id = '".mysql_escape_string($cityID)."'");
            $procount = @mysql_fetch_assoc($fetchProQuery);
            $procount = $procount['procount'];
            $procounter = 0;                  
            
            $fetchAgentQuery = @mysql_query("SELECT COUNT(agents.id) agentcount FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".mysql_escape_string($_POST['broker_cmpny_id'])." AND broker_contacts.type='Agent'");
            $agentcount = @mysql_fetch_assoc($fetchAgentQuery);
            $agentcount = $agentcount['agentcount'];
            $agentcounter = 0;
            
            $i = 0;
            //print'<pre>';
//            print_r($ruleAttr);
            foreach($ruleAttr as $key => $val)
            {
                                                
                $locality = array();
                $localityAttr = array();
                 
                $projectflag = 0;
                
                $conditions = " rule_locality_mappings.rule_id = ".$val->id." and city.city_id = '".mysql_escape_string($cityID)."'";
                
                $joins = " INNER JOIN locality ON rule_locality_mappings.locality_id = locality.locality_id
                            INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                            INNER JOIN city ON suburb.city_id = city.city_id";
                            
                $options = array('joins' => $joins , 'select' => "locality.label AS locality" , 'conditions' => $conditions);
                $localityAttr = RuleLocalityMappings::find('all',$options);
                $NumRows = count($localityAttr);
                $locounter += $NumRows;
                
                if(!empty($RowsPerPage) && !empty($Offset))
                {
                    $options = array('joins' => $joins , 'select' => " locality.locality_id,locality.label AS locality" , 'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => $conditions);
                    $localityAttr = RuleLocalityMappings::find('all',$options);
                }
                else
                {
                    $options = array('joins' => $joins , 'select' => " locality.locality_id,locality.label AS locality" , 'limit' => $RowsPerPage ,'conditions' => $conditions);
                    $localityAttr = RuleLocalityMappings::find('all',$options);
                }
                
                //echo " locount : $locount    NumRows ".$NumRows."  locounter : $locounter<br>";
                
                if(!empty($localityAttr) && !empty($locount) && ($locount == $NumRows || $locounter == $locount))
                {
                    $localityAttr = array();
                    $localityAttr[] = 'all';
                }
                
                
                //print'<pre>';
//                echo RuleLocalityMappings::connection()->last_query."<br>";
//                print_r($localityAttr);
                //continue;
               // die;
                
                if(!empty($localityAttr))
                {
                    foreach($localityAttr as $k => $v)
                    {
                        if($v == 'all')
                        {
                            $locality = array();
                            $locality[] = 'All';
                            $chkLoc = 1;
                            break;
                        }
                        else
                            $locality[] = $v->locality;
                    }
                }
                else
                {
                    $conditions = " rule_project_mappings.rule_id = '".$val->id."' and city.city_id = '".mysql_escape_string($cityID)."'";
                
                    $joins = "  INNER JOIN resi_project ON rule_project_mappings.project_id = resi_project.project_id
                                INNER JOIN locality ON resi_project.locality_id = locality.locality_id
                                INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                INNER JOIN city ON suburb.city_id = city.city_id";
                                
                    $options = array('joins' => $joins , 'select' => "locality.label AS locality" , 'conditions' => $conditions , 'group' => 'locality.locality_id');
                    $localityAttr = RuleProjectMapping::find('all',$options);
                    //echo RuleProjectMapping::connection()->last_query."<br>";
                    $NumRows = count($localityAttr);
                    $locounter += $NumRows;
                    //echo $NumRows;
                    if(!empty($RowsPerPage) && !empty($Offset))
                    {
                        $options = array('joins' => $joins , 'select' => " locality.locality_id,locality.label AS locality" , 'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => $conditions, 'group' => 'locality.locality_id');
                        $localityAttr = RuleProjectMapping::find('all',$options);
                    }
                    else
                    {
                        $options = array('joins' => $joins , 'select' => " locality.locality_id,locality.label AS locality" , 'limit' => $RowsPerPage ,'conditions' => $conditions, 'group' => 'locality.locality_id');
                        $localityAttr = RuleProjectMapping::find('all',$options);
                    }
                    
                    //print'<pre>';
//                    print_r($localityAttr);
//                    die;
                    if(!empty($localityAttr))
                    {
                        foreach($localityAttr as $k => $v)
                        {
                            if($v == 'all')
                            {
                                $locality = array();
                                $locality[] = 'All';
                                $chkLoc = 1;
                                break;
                            }
                            else
                                $locality[] = $v->locality;
                        }
                    }
                }
                //die("here");
                
                
                $conditions = " rule_project_mappings.rule_id = ".$val->id;
                $joins = " LEFT JOIN resi_project ON rule_project_mappings.project_id = resi_project.project_id";
                $options = array('joins' => $joins , 
                                'select' => " resi_project.project_id,resi_project.project_name" , 
                                'conditions' => $conditions
                            );
                
                $projectAttr = RuleProjectMapping::find('all',$options);
                //echo RuleProjectMapping::connection()->last_query."<br>";
                $project_count = count($projectAttr);
                $procounter += $project_count;
                //echo " procount : $procount    procounter ".$procounter."  Project Count : $project_count<br>";
                if(!empty($procount) && ($procount == $project_count || $procounter == $procount))
                {
                    $projectAttr = array();
                    $projectAttr[] = 'all';
                }
                else if(empty($project_count))
                    $projectAttr[] = 'all';
                $project = array();                
                
                //print'<pre>';
//                print_r($projectAttr);
//                continue;
                if(!empty($projectAttr))
                {
                    foreach($projectAttr as $k => $v)
                    {
                        if($v == 'all')
                        {
                            $project = array();
                            $project[] = 'All';
                            break;
                        }
                        else
                        {
                            $project[] = $v->project_name;    
                        }
                        
                    }
                }
                
                
                $conditions = " rule_agent_mappings.rule_id = '".$val->id."' AND broker_contacts.type ='Agent'";
                $joins = " LEFT JOIN broker_contacts ON rule_agent_mappings.agent_id = broker_contacts.broker_id";
                $options = array('joins' => $joins , 'select' => " broker_contacts.name" , 'conditions' => $conditions);
                $agentAttr = RuleAgentMappings::find('all',$options);
                $agent_count = count($agentAttr);
                $agentcounter += $agent_count;
                //print'<pre>';
//                print_r($agentAttr);
//                echo "agentcount ".$agentcount." agent_count ".$agent_count." agentcounter ".$agentcounter;
//                die;
                if(!empty($agentcount) && ($agentcount == $agent_count || $agentcounter == $agentcount))
                {
                    $agentAttr = array();
                    $agentAttr[] = 'all';
                }
                else if(empty($agent_count))
                {
                    $agentAttr = array();
                    $agentAttr[] = 'all';
                }
                $agent = array();
                if(!empty($agentAttr))
                {
                    foreach($agentAttr as $k => $v)
                    {
                        if($v == 'all')
                        {
                            $agent = array();
                            $agent[] = 'All';
                            break;    
                        }
                        else
                        {
                            $agent[] = $v->name;
                        }
                        
                    }
                }
                //$relCity = '';
//                $chkR = @mysql_query("SELECT city.city_id FROM rule_project_mappings AS rpm LEFT JOIN resi_project AS rp ON rpm.project_id = rp.project_id LEFT JOIN locality AS l ON rp.locality_id = l.locality_id LEFT JOIN suburb AS s ON l.suburb_id = s.suburb_id LEFT JOIN city ON s.city_id = city.city_id LEFT JOIN project_assignment_rules AS par ON rpm.rule_id = par.id WHERE par.broker_id = '".$chkExist->id."'");
//                echo "SELECT city.city_id FROM rule_project_mappings AS rpm LEFT JOIN resi_project AS rp ON rpm.project_id = rp.project_id LEFT JOIN locality AS l ON rp.locality_id = l.locality_id LEFT JOIN suburb AS s ON l.suburb_id = s.suburb_id LEFT JOIN city ON s.city_id = city.city_id LEFT JOIN project_assignment_rules AS par ON rpm.rule_id = par.id WHERE par.broker_id = '".$chkExist->id."'<br>";
//                
//                if(!@mysql_num_rows($chkR) > 0)
//                {
//                    $chkR = @mysql_query("SELECT city.city_id FROM rule_locality_mappings AS rpm locality AS l ON rp.locality_id = l.locality_id LEFT JOIN suburb AS s ON l.suburb_id = s.suburb_id LEFT JOIN city ON s.city_id = city.city_id  LEFT JOIN project_assignment_rules AS par ON rlm.rule_id = par.id  WHERE par.broker_id = '".$chkExist->id."'");
//                    
//                    if(@mysql_num_rows($chkR) > 0)
//                    {
//                        $relCity = @mysql_fetch_assoc($chkR);
//                        $relCity = $relCity['city_id'];
//                    }
//                    
//                }
//                else
//                {
//                    $relCity = @mysql_fetch_assoc($chkR);
//                    $relCity = $relCity['city_id'];
//                }
//                //print'<pre>';
////                    print_r($relCity);
//                echo $relCity ." ". $cityID."<br>";
//                if(!empty($relCity) && $relCity == $cityID)
//                {
//                    
//                }
                    $ruleIdArr[] = $val->id;
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
                
                
//                continue;
                //print'<pre>';
//                print_r($data);
                //print_r($localityAttr);
                //die;
                //continue;
            }
            
            $NumRows = count($data);
            if($NumRows == 0)   
                $NumRows = $count;
        }
        //print'<pre>';
//        print_r($data);
//        die;
        
        $locarr = array();
        if($chkLoc != 1)
        {
            $sql = @mysql_query("SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".mysql_escape_string($cityID)." AND locality.locality_id NOT IN (SELECT rlm.locality_id FROM rule_locality_mappings AS rlm INNER JOIN project_assignment_rules AS par ON rlm.rule_id = par.id WHERE par.broker_id = '".$chkExist->id."')");
            while($row = @mysql_fetch_assoc($sql))
            {
                $locarr[$row['locality_id']] = $row['label'];    
            }
        }
        
        if(empty($locarr))
            $chkLoc = 1;
        //print'<pre>';
//        print_r($locarr);
//        echo count($locarr);
//        print_r($data);
//        die;
        
        $smarty->assign("city_id" , $cityID);
        $smarty->assign("broker_name" , $companyID);
        $smarty->assign("broker_id" , !empty($chkExist->id)?$chkExist->id:'');
        $smarty->assign("seller_company" , $agentOptions);
        $smarty->assign("locality" , $locarr);
    }
    //echo $NumRows;
    
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
    $smarty->assign("chkLoc", $chkLoc); 
    
    
?>


