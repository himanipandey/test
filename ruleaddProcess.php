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
                
                global $broker_cmpny , $rule_name , $city_id,$locality,$project,$agent , $broker_cmpny_id;
                $locality_id = '';
                
                $chkSql = @mysql_query("SELECT * FROM rule_locality_mappings WHERE city_id = '".mysql_escape_string($city_id)."' AND locality_id = '-1'");
                
                if(!@mysql_num_rows($chkSql) > 0)
                {
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
                                    $locality_id = @mysql_insert_id();    
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
                                                                `rule_locality_mapping_id` = '".$locality_id."',
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
                                                                `rule_locality_mapping_id` = '".$locality_id."',
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
                        
                    }
                    else{
                        $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                    } 
                }
                
            });
        }
        else {
            
            ResiProject::transaction(function(){
                
                global $broker_cmpny_id , $rule_name , $city_id,$locality,$project,$agent,$ruleId , $finallocidArr ,$finalprojidArr,$finalagentidArr,$locjIdArr,$projectjIdArr,$agentjIdArr;
               $locality_id = '';
//                print'<pre>';
//                print_r($_POST);
//                echo "<br>==========Locality==========<br>";
//                print_r($finallocidArr);
//                echo "<br>==========Project==========<br>";
//                print_r($finalprojidArr);                
//                echo "<br>==========Agent==========<br>";
//                print_r($finalagentidArr);
//                die;            
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
                            if($val == 'all')
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_locality_mappings` WHERE locality_id = '-1' AND rule_id = '".$rule_id."'");
                            else
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_locality_mappings` WHERE locality_id = '".mysql_escape_string($val)."' AND rule_id = '".$rule_id."'");
                            //echo "DELETE FROM `rule_locality_mappings` WHERE locality_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>";
                             
                        }
                    }
                    
                    if(!empty($locality))
                    {
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_locality_mappings` WHERE locality_id = '-1' AND rule_id = '".mysql_escape_string($rule_id)."'");
                            
                                if(@mysql_num_rows($chkSql) > 0)
                                {
                                    $sql_rule_locality_mappings = @mysql_query("UPDATE `rule_locality_mappings` SET 
                                                            `locality_id` = '-1',
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
                                                                `locality_id` = '-1',
                                                                `city_id` = '".mysql_escape_string($city_id)."',
                                                                `updated_by` = '".$_SESSION['adminId']."',
                                                                `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                    $locality_id = @mysql_insert_id();
                                    //echo "INSERT INTO `rule_locality_mappings` SET 
//                                                                `rule_id` = '".$rule_id."',
//                                                                `locality_id` = '-1',
//                                                                `city_id` = '".mysql_escape_string($city_id)."',
//                                                                `updated_by` = '".$_SESSION['adminId']."',
//                                                                `created_at` = '".date('Y-m-d')."'"."<br>";
                                }
                            }
                            else
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_locality_mappings` WHERE locality_id = '".mysql_escape_string($val)."' AND rule_id = '".mysql_escape_string($rule_id)."'");
                            
                                if(@mysql_num_rows($chkSql) > 0)
                                {
                                    $sql_rule_locality_mappings = @mysql_query("UPDATE `rule_locality_mappings` SET 
                                                            `locality_id` = '".mysql_escape_string($val)."',
                                                            `updated_by` = '".$_SESSION['adminId']."',
                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));
                                    $locality_id = $val;
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
                                    $locality_id = @mysql_insert_id();
                                    //echo "INSERT INTO `rule_locality_mappings` SET 
    //                                                            `rule_id` = '".$rule_id."',
    //                                                            `locality_id` = '".mysql_escape_string($val)."',
    //                                                            `updated_by` = '".$_SESSION['adminId']."',
    //                                                            `created_at` = '".date('Y-m-d')."'"."<br>";
                                }
                            }
                            
                            
                                    
                        }
                    }
                    
                    
                    if(!empty($finalprojidArr))
                    {
                        foreach($finalprojidArr as $key => $val)
                        {
                            if($val == 'all')
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_project_mappings` WHERE project_id = '-1' AND rule_id = '".$rule_id."'");
                            else
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_project_mappings` WHERE project_id = '".mysql_escape_string($val)."' AND rule_id = '".$rule_id."'"); 
                            //echo "DELETE FROM `rule_project_mappings` WHERE project_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>";
                        }
                    }
                    
                    
                    if(!empty($project))
                    {
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_project_mappings` WHERE project_id = '-1' AND rule_id = '".mysql_escape_string($rule_id)."'");
                            
                                if(@mysql_num_rows($chkSql) > 0)
                                {
                                    $sql_rule_project_mappings = @mysql_query("UPDATE `rule_project_mappings` SET 
                                                                `project_id` = '-1',
                                                                `rule_locality_mapping_id` = '".$locality_id."',
                                                                `updated_by` = '".$_SESSION['adminId']."',
                                                                `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));
    
                                    //echo "UPDATE `rule_project_mappings` SET 
//                                                                `project_id` = '-1',
//                                                                    `rule_locality_mapping_id` = '".$locality_id."',
//                                                                `updated_by` = '".$_SESSION['adminId']."',
//                                                                `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id)."<br>";
                                }
                                else
                                {
                                    $sql_rule_project_mappings = @mysql_query("INSERT INTO `rule_project_mappings` SET 
                                                                `rule_id` = '".$rule_id."',
                                                                `project_id` = '-1',
                                                                `rule_locality_mapping_id` = '".$locality_id."',
                                                                `updated_by` = '".$_SESSION['adminId']."',
                                                                `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                    //echo "INSERT INTO `rule_project_mappings` SET 
    //                                                            `rule_id` = '".$rule_id."',
    //                                                            `project_id` = '".mysql_escape_string($val)."',
    //                                                            `updated_by` = '".$_SESSION['adminId']."',
    //                                                            `created_at` = '".date('Y-m-d')."'"."<br>";
                                }
                            }
                            else
                            {
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
                    }
                    
                    
                    if(!empty($finalagentidArr))
                    {
                        foreach($finalagentidArr as $key => $val)
                        {
                            if($val == 'all')
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_agent_mappings` WHERE agent_id = '-1' AND rule_id = '".$rule_id."'");
                            else
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_agent_mappings` WHERE agent_id = '".mysql_escape_string($val)."' AND rule_id = '".$rule_id."'");
                            //echo "DELETE FROM `rule_agent_mappings` WHERE agent_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>"; 
                        }
                    }
                    
                    if(!empty($agent))
                    {
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                
                                $chkSql = @mysql_query("SELECT * FROM `rule_agent_mappings` WHERE agent_id = '-1' AND rule_id = '".mysql_escape_string($rule_id)."'");
                                
                                if(@mysql_num_rows($chkSql) > 0)
                                {
                                    $sql_rule_agent_mappings = @mysql_query("UPDATE  `rule_agent_mappings` SET 
                                                                `agent_id` = '-1',
                                                                `rule_id` = '".$rule_id."',
                                                                `updated_by` = '".$_SESSION['adminId']."',
                                                                `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id));
                                    //echo "UPDATE `rule_agent_mappings` SET 
//                                                                `agent_id` = '-1',
//                                                                `rule_id` = '".$rule_id."',
//                                                                `updated_by` = '".$_SESSION['adminId']."',
//                                                                `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id)."<br>";
                                }
                                else
                                {
                                    $sql_rule_agent_mappings = @mysql_query("INSERT INTO `rule_agent_mappings` SET 
                                                                `rule_id` = '".$rule_id."',
                                                                `agent_id` = '-1',
                                                                `updated_by` = '".$_SESSION['adminId']."',
                                                                `created_at` = '".date('Y-m-d')."'") or die(mysql_error());
                                    //echo "INSERT INTO `rule_agent_mappings` SET 
    //                                                            `rule_id` = '".$rule_id."',
    //                                                            `agent_id` = '".mysql_escape_string($val)."',
    //                                                            `updated_by` = '".$_SESSION['adminId']."',
    //                                                            `created_at` = '".date('Y-m-d')."'"."<br>"; 
                                }
                            }
                            else
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_agent_mappings` WHERE agent_id = '".mysql_escape_string($val)."' AND rule_id = '".mysql_escape_string($rule_id)."'");
                            
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
        $ruleAttr = ProjectAssignmentRules::find('all');
        $data = array();
        if(!empty($ruleAttr))
        {
            $i = 0;
            foreach($ruleAttr as $key => $val)
            {
                $locality = array();
                $localityAttr = array();
                $resultLocality = '';
                $projectflag = 0;
                
                $sqlQuery = @mysql_query("SELECT * FROM rule_locality_mappings 
                                            WHERE rule_locality_mappings.rule_id = '".$val->id."'");
                
                if(@mysql_num_rows($sqlQuery))
                {
                    $resultLocality = @mysql_fetch_assoc($sqlQuery);
                    if($resultLocality['locality_id'] == '-1' && $resultLocality['city_id'] == $_POST['city_id'])
                    {
                        $conditions = " rule_locality_mappings.rule_id = '".$val->id."'";
                        $options = array('select' => " rule_locality_mappings.id,locality_id" , 'conditions' => $conditions);
                        $localityAttr = RuleLocalityMappings::find('all',$options);
                        $NumRows = count($localityAttr);
                        
                        if(!empty($RowsPerPage) && !empty($Offset))
                        {
                            $conditions = " rule_locality_mappings.rule_id = '".$val->id."'";
                            $options = array('select' => " rule_locality_mappings.id,locality_id" ,'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => $conditions);
                            $localityAttr = RuleLocalityMappings::find('all',$options);
                        }
                        else
                        {
                            $conditions = " rule_locality_mappings.rule_id = '".$val->id."'";
                            $options = array('select' => " rule_locality_mappings.id,locality_id" ,'limit' => $RowsPerPage , 'conditions' => $conditions);
                            $localityAttr = RuleLocalityMappings::find('all',$options);
                        }
                    }
                    else
                    {
                        $conditions = " rule_locality_mappings.rule_id = ".$val->id." and city.city_id = '".mysql_escape_string($_POST['city_id'])."'";
                
                        $joins = " INNER JOIN locality ON rule_locality_mappings.locality_id = locality.locality_id
                                    INNER JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                    INNER JOIN city ON suburb.city_id = city.city_id";
                                    
                        $options = array('joins' => $joins , 'select' => "locality.label AS locality" , 'conditions' => $conditions);
                        $localityAttr = RuleLocalityMappings::find('all',$options);
                        $NumRows = count($localityAttr);
                        
                        if(!empty($RowsPerPage) && !empty($Offset))
                        {
                            $options = array('joins' => $joins , 'select' => " rule_locality_mappings.id,locality.locality_id,locality.label AS locality" , 'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => $conditions);
                            $localityAttr = RuleLocalityMappings::find('all',$options);
                        }
                        else
                        {
                            $options = array('joins' => $joins , 'select' => " rule_locality_mappings.id,locality.locality_id,locality.label AS locality" , 'limit' => $RowsPerPage ,'conditions' => $conditions);
                            $localityAttr = RuleLocalityMappings::find('all',$options);
                        }
                    }
                }
                
                //print'<pre>';
//                echo RuleLocalityMappings::connection()->last_query."<br>";
//                print_r($localityAttr);
//                continue;
                
                $rule_locality_mapping_id = '';
                if(!empty($localityAttr))
                {
                    foreach($localityAttr as $k => $v)
                    {
                        if($v->locality_id == '-1')
                        {
                            $locality[] = 'All';
                            $rule_locality_mapping_id = $v->id;
                            $projectflag = 1;
                        }
                        else
                        {
                            $locality[] = $v->locality;
                            $rule_locality_mapping_id = $v->id;
                        }
                    }
                }
                
                
                $conditions = " rule_id = '".$val->id."'";
                $options = array( 'select' => " project_id" , 'conditions' => $conditions);
                $projectAttrtest = RuleProjectMapping::find('all',$options);
                if(!empty($projectAttrtest))
                {
                    foreach($projectAttrtest as $k1 => $v1)
                    {
                        //echo "project_id ".$v1->project_id." ". "rule_locality_mapping_id" . $rule_locality_mapping_id."<br>";
                        if($v1->project_id == '-1')
                        {
                            $conditions = " rule_id = '".$val->id."' AND rule_locality_mapping_id = '$rule_locality_mapping_id'";
                            $options = array( 'select' => " project_id" , 'conditions' => $conditions);
                            $projectAttr = RuleProjectMapping::find('all',$options);
                        }
                        else
                        {
                            $conditions = " rule_project_mappings.rule_id = ".$val->id;
                            $joins = " LEFT JOIN resi_project ON rule_project_mappings.project_id = resi_project.project_id";
                            $options = array('joins' => $joins , 
                                            'select' => " resi_project.project_id,resi_project.project_name" , 
                                            'conditions' => $conditions
                                        );
                            $projectAttr = RuleProjectMapping::find('all',$options);
                        }
                    }
                }
                
                $project = array();                
                
                //print'<pre>';
//                print_r($projectAttr);
                if(!empty($projectAttr))
                {
                    foreach($projectAttr as $k => $v)
                    {
                        if(isset($v->project_id) && $v->project_id == '-1')
                            $project[] = 'All';
                        else
                            $project[] = $v->project_name;
                    }
                }
                
                //print'<pre>';
//                print_r($project);
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
                    
                    if(!empty($agentAttr))
                    {
                        foreach($agentAttr as $k => $v)
                        {
                            $agent[] = $v->name;
                        }
                    }
                    
                }
                
                if(!empty($localityAttr))
                {
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
                }
                //print_r($localityAttr);
//                continue;
                //print'<pre>';
//                print_r($data);
//                continue;
            }
            
            if($NumRows == 0)   
                $NumRows = $count;
        }
        //print'<pre>';
//        print_r($data);
//        die;
        $locarr = array();
        $chkLoc = 0;
        $sql = @mysql_query("SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = '".$_POST['city_id']."'");
        while($row = @mysql_fetch_assoc($sql))
        {
            $chkSql = @mysql_query("SELECT rp.project_id FROM resi_project AS rp LEFT JOIN locality AS l ON rp.locality_id = l.locality_id WHERE l.locality_id = '".$row['locality_id']."'");
            
            $total_projects = @mysql_num_rows($chkSql);
            
            //echo "Locality ".$row['locality_id']."<br>";
//            echo "Total Projects :".$total_projects."<br>";
            $chkLoc = 0;
            $chkFlag = 0;
            $chkSql = @mysql_query("SELECT locality_id FROM rule_locality_mappings AS rlm WHERE rlm.locality_id = '-1' AND rlm.city_id = '".$_POST['city_id']."'");
            $chkLoc = @mysql_num_rows($chkSql);
            if($chkLoc == 1)
                continue;
            
            $chkSql = @mysql_query("SELECT rpm.project_id FROM rule_project_mappings AS rpm LEFT JOIN rule_locality_mappings AS rlm ON rpm.rule_id = rlm.rule_id WHERE rlm.locality_id = '".$row['locality_id']."'");
            $total_projects_mapped = @mysql_num_rows($chkSql);
            
            if($total_projects_mapped == 1)
                while($row1 = @mysql_fetch_assoc($chkSql))
                {
                    if($row1['project_id'] == '-1')
                    {
                        $chkFlag = 1;
                        break;
                    }        
                }
            
            //echo "Total Projects Mapped :".$total_projects_mapped."<br>";
            if($chkFlag == 1 || $total_projects == $total_projects_mapped)
            {
                continue;
            }
            
            
            $locarr[$row['locality_id']] = $row['label'];    
        }
        //print'<pre>';
//        print_r($locarr);
        //print_r($data);
        //die;
        $smarty->assign("city_id" , $_POST['city_id']);
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

