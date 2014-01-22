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
//        print'<pre>';
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
                //$rule_id = 51;
                
                if($rule_id != false) {
                     
                    
                    if(!empty($project) && $project != 'all')
                    {
                        $chkAll = 0;
                        $insertLocpa = ' INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) 
                                                                values';
                        $str1 = '';
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkAll = 1;
                                if(!empty($locality) && $locality[0] == 'all')
                                {
                                    $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                            LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                            LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                            LEFT JOIN city ON suburb.city_id = city.city_id
                                            WHERE city.city_id = '".mysql_escape_string($city_id)."'
                                            AND resi_project.project_id NOT IN (SELECT project_id FROM rule_project_mappings)
                                            ");
                                    $insertLoc = 'INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) 
                                                                values ';
                                    $str = '';
                                    while($row = @mysql_fetch_assoc($fetchProQuery))
                                    {
                                        $str .= " ('".$rule_id."','".$row['project_id']."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                        
                                        //echo "INSERT INTO `rule_project_mappings` SET 
    //                                                                    `rule_id` = '".$rule_id."',
    //                                                                    `project_id` = '".$row['project_id']."',
    //                                                                    `updated_by` = '".$_SESSION['adminId']."',
    //                                                                    `created_at` = '".date('Y-m-d')."'<br>";
                                    }  
                                    
                                    $insertLoc .= trim($str , ',');
                                    $sql_rule_project_mappings = @mysql_query($insertLoc) or die(mysql_error()); 
                                }
                                else
                                {
                                    
                                    $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name AS label FROM resi_project WHERE resi_project.locality_id IN (".implode("," , $locality).")");
                                    $insertLoc = ' INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) 
                                                                values';
                                    $str = '';
                                    while($row = @mysql_fetch_assoc($fetchProQuery))
                                    {
                                        $str .= " ('".$rule_id."','".$row['project_id']."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                        
                                        //echo "INSERT INTO `rule_project_mappings` SET 
    //                                                                    `rule_id` = '".$rule_id."',
    //                                                                    `project_id` = '".$row['project_id']."',
    //                                                                    `updated_by` = '".$_SESSION['adminId']."',
    //                                                                    `created_at` = '".date('Y-m-d')."'<br>";
                                    }
                                    $insertLoc .= trim($str , ',');
                                    $sql_rule_project_mappings = @mysql_query($insertLoc) or die(mysql_error());
                                }
                                
                                break;
                                 
                            }
                            else
                            {
                                $str1 .= " ('".$rule_id."','".$val."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                //echo "Project ID : ".$val."<br>";
                                  
                                //echo "INSERT INTO `rule_project_mappings` SET 
//                                                        `rule_id` = '".$rule_id."',
//                                                        `project_id` = '".mysql_escape_string($val)."',
//                                                        `updated_by` = '".$_SESSION['adminId']."',
//                                                        `created_at` = '".date('Y-m-d')."'<br>";
                                  
                            }
                                  
                        }
                        
                        if($chkAll == 0 && !empty($insertLocpa))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            $sql_rule_project_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                    if(!empty($locality) && $locality != 'all')
                    {
                        $chkAll = 0;
                        $insertLocpa = 'INSERT INTO `rule_locality_mappings` (rule_id,locality_id,updated_by,created_at) values';
                        $str1 = '';
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkAll= 1;
                                $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".mysql_escape_string($city_id)." AND locality.locality_id NOT IN (SELECT locality_id FROM rule_locality_mappings)");
                                $insertLoc = 'INSERT INTO `rule_locality_mappings` (rule_id,locality_id,updated_by,created_at) values';
                                $str = '';
                                while($row = @mysql_fetch_assoc($fetchLocQuery))
                                {
                                    $str .= " ('".$rule_id."','".$row['locality_id']."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                                        
                                    
                                    
                                    //echo "INSERT INTO `rule_locality_mappings` SET 
//                                                                        `rule_id` = '".$rule_id."',
//                                                                        `locality_id` = '".$row['locality_id']."',
//                                                                        `updated_by` = '".$_SESSION['adminId']."',
//                                                                        `created_at` = '".date('Y-m-d')."'<br>";

                                }
                                $insertLoc .= trim($str , ',');
                                $sql_rule_locality_mappings = @mysql_query($insertLoc) or die(mysql_error());
                                
                                break;                                                                
                            }
                            else
                            {
                                $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                
                                //echo "Locality ID : ".$val."<br>";
                                
                                //echo "INSERT INTO `rule_locality_mappings` SET 
//                                                                    `rule_id` = '".$rule_id."',
//                                                                    `locality_id` = '".mysql_escape_string($val)."',
//                                                                    `updated_by` = '".$_SESSION['adminId']."',
//                                                                    `created_at` = '".date('Y-m-d')."'<br>";    
                            }
                                    
                        }
                        if($chkAll == 0 && !empty($insertLocpa))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            $sql_rule_locality_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                    //echo "<br>Agent<br>";
                    if(!empty($agent) && $agent != 'all')
                    {
                        $chkAll = 0;
                        $insertLocpa = ' INSERT INTO `rule_agent_mappings` (rule_id,agent_id,updated_by,created_at) values';
                        $str1 = '';
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                             
                            if($val == 'all')
                            {
                                $chkAll = 1 ;
                                break;
                            }
                            else
                            {
                                //echo "Agent ID : ".$val."<br>";
                                $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                               // echo "INSERT INTO `rule_agent_mappings` SET 
//                                                            `rule_id` = '".$rule_id."',
//                                                            `agent_id` = '".mysql_escape_string($val)."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `created_at` = '".date('Y-m-d')."'<br><br>";
                            }    
                        }
                        if($chkAll == 0 && !empty($insertLocpa))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            $sql_rule_agent_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                } 
                
                
            });
            
            if(!empty($rule_id))
            {
                header("Location:ruleadd.php?city_id=".$city_id."&page=1&sort=all");
                echo $rule_id;
                die;
            }
            
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
                        $delall = 'DELETE FROM `rule_locality_mappings` WHERE locality_id IN (';
                        $str1 = '';                        
                        foreach($finallocidArr as $key => $val)
                        {
                            $del = 'DELETE FROM `rule_locality_mappings` WHERE locality_id IN (';
                            $str = '';
                            if($val == 'all')
                            {
                                $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = '".mysql_escape_string($city_id)."'");
                                while($row = @mysql_fetch_assoc($sql))
                                {
                                    $str .= "'".$row['locality_id']."'".",";
                                           
                                }
                                
                                if(!empty($str))
                                {
                                    $del .= trim($str , ',').") AND rule_id = '".$rule_id."'";
                                    //echo " Delete here".$del."<br>";
                                    //die;
                                    $sql_rule_locality_mappings = @mysql_query($del);
                                }
                                break;
                            }
                            else
                            {
                                $str1 .= "'".mysql_escape_string($val)."'".",";
                            }
                                
                            //echo "DELETE FROM `rule_locality_mappings` WHERE locality_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>";
                             
                        }
                        if(!empty($str1))
                        {
                            $delall .= trim($str1 , ",").") AND rule_id = '".$rule_id."'";
                            //echo " Delete thr".$delall."<br>";
                            //die;
                            $sql_rule_locality_mappings = @mysql_query($delall);
                        }
                    }
                    //print'<pre>';
//                    print_r($locality);
                    if(!empty($locality))
                    {
                        $chkAll = 0;
                        $insertLocpa = ' INSERT INTO `rule_locality_mappings` (rule_id,locality_id,updated_by,created_at) values';
                        $str1 = '';
                        foreach($locality as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            //echo " Loc Val ".$val."<br>";
                            if($val == 'all')
                            {
                                $chkAll = 1;
                                $fetchLocQuery = @mysql_query("SELECT locality.locality_id FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".mysql_escape_string($city_id)." AND locality.locality_id NOT IN (SELECT locality_id FROM rule_locality_mappings)");
                                $insertLoc = ' INSERT INTO `rule_locality_mappings` (rule_id,locality_id,updated_by,created_at) values';
                                $str = '';
                                while($row = @mysql_fetch_assoc($fetchLocQuery))
                                {
                                    $chkSql = @mysql_query("SELECT * FROM `rule_locality_mappings` WHERE locality_id = '".mysql_escape_string($row['locality_id'])."' AND rule_id = '".mysql_escape_string($rule_id)."'");
                            
                                    if(!@mysql_num_rows($chkSql) > 0)
                                    {
                                        $str .= " ('".$rule_id."','".mysql_escape_string($row['locality_id'])."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                    }
                                    //$sql_rule_locality_mappings = @mysql_query("UPDATE `rule_locality_mappings` SET 
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id = '".mysql_escape_string($rule_id)."' AND `locality_id` = '".mysql_escape_string($row['locality_id'])."'");
                                    //echo "UPDATE `rule_locality_mappings` SET 
//                                                            `locality_id` = '".mysql_escape_string($row['locality_id'])."',
//                                                            `updated_by` = '".$_SESSION['adminId']."',
//                                                            `updated_at` = '".date('Y-m-d')."' WHERE rule_id=".mysql_escape_string($rule_id)."<br>"; 
                                }
                                
                                if(!empty($str))
                                {
                                    $insertLoc .= trim($str , ',');
                                    //echo "<br> For All ".$insertLoc."<br>";
//                                    die;
                                    $sql_rule_locality_mappings = @mysql_query($insertLoc) or die(mysql_error());    
                                }
                                break;
                                
                            }
                            else
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_locality_mappings` WHERE locality_id = '".mysql_escape_string($val)."' AND rule_id = '".mysql_escape_string($rule_id)."'")or die(mysql_error());
                                echo " Loc num rows ".@mysql_num_rows($chkSql)."<br>";
                                if(!@mysql_num_rows($chkSql) > 0)
                                {
                                    $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                    //echo "INSERT INTO `rule_locality_mappings` SET 
    //                                                            `rule_id` = '".$rule_id."',
    //                                                            `locality_id` = '".mysql_escape_string($val)."',
    //                                                            `updated_by` = '".$_SESSION['adminId']."',
    //                                                            `created_at` = '".date('Y-m-d')."'"."<br>";
                                }
                            }           
                        }
                        //echo $chkAll ." ". $insertLocpa." <br>".$str1."<br>";
                        if($chkAll == 0 && !empty($insertLocpa) && !empty($str1))
                        {
                            $insertLocpa .= trim($str1 , ',');
                            //echo "For Selected ".$insertLocpa."<br>";
//                            die; 
                            $sql_rule_locality_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    //echo "here";
//                    die;
                    
                    if(!empty($finalprojidArr))
                    {
                        foreach($finalprojidArr as $key => $val)
                        {
                            if($val == 'all')
                            {
                                $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                            LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                            LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                            LEFT JOIN city ON suburb.city_id = city.city_id
                                            WHERE city.city_id = '".mysql_escape_string($city_id)."'");
                                while($row = @mysql_fetch_assoc($fetchProQuery))
                                {
                                    $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_project_mappings` WHERE project_id = '".$row['project_id']."' AND rule_id = '".$rule_id."'");    
                                }
                            }
                            else
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_project_mappings` WHERE project_id = '".mysql_escape_string($val)."' AND rule_id = '".$rule_id."'"); 
                            //echo "DELETE FROM `rule_project_mappings` WHERE project_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>";
                        }
                    }
                    
                    
                    if(!empty($project))
                    {
                        $chkAll = 0;
                        $insertLocpa = 'INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) 
                                                                values';
                        $str1 = '';
                        foreach($project as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkAll = 1;
                                if(!empty($locality) && $locality[0] == 'all')
                                {
                                    $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                            LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                            LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                            LEFT JOIN city ON suburb.city_id = city.city_id
                                            WHERE city.city_id = '".mysql_escape_string($city_id)."'
                                            AND resi_project.project_id NOT IN (SELECT project_id FROM rule_project_mappings)
                                            ");
                                    $insertLoc = 'INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) 
                                                                values';
                                    $str = '';
                                    while($row = @mysql_fetch_assoc($fetchProQuery))
                                    {
                                        $chkSql = @mysql_query("SELECT * FROM `rule_project_mappings` WHERE project_id = ".mysql_escape_string($row['project_id'])." AND rule_id = ".mysql_escape_string($rule_id));
                            
                                        if(!@mysql_num_rows($chkSql) > 0)
                                        {
                                            $str .= " ('".$rule_id."','".$row['project_id']."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                            
                                        }
                                        
                                        //echo "INSERT INTO `rule_project_mappings` SET 
    //                                                                    `rule_id` = '".$rule_id."',
    //                                                                    `project_id` = '".$row['project_id']."',
    //                                                                    `updated_by` = '".$_SESSION['adminId']."',
    //                                                                    `created_at` = '".date('Y-m-d')."'<br>";
                                    }   
                                    
                                    if(!empty($str))
                                    {
                                        $insertLoc .= trim($str , ',');
                                        $sql_rule_project_mappings = @mysql_query($insertLoc) or die(mysql_error());    
                                    }
                                    
                                    
                                    
                                }
                                else
                                {
                                    
                                    $fetchProQuery = @mysql_query("SELECT resi_project.project_id , resi_project.project_name AS label FROM resi_project WHERE resi_project.locality_id IN (".implode("," , $locality).")");
                                    $insertLoc = 'INSERT INTO `rule_project_mappings` (rule_id,project_id,updated_by,created_at) 
                                                                values';
                                    $str = '';
                                    while($row = @mysql_fetch_assoc($fetchProQuery))
                                    {
                                        $chkSql = @mysql_query("SELECT * FROM `rule_project_mappings` WHERE project_id = ".mysql_escape_string($row['project_id'])." AND rule_id = ".mysql_escape_string($rule_id));
                            
                                        if(!@mysql_num_rows($chkSql) > 0)
                                        {
                                            $str .= " ('".$rule_id."','".$row['project_id']."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                            
                                        }
                                        
                                        //echo "INSERT INTO `rule_project_mappings` SET 
    //                                                                    `rule_id` = '".$rule_id."',
    //                                                                    `project_id` = '".$row['project_id']."',
    //                                                                    `updated_by` = '".$_SESSION['adminId']."',
    //                                                                    `created_at` = '".date('Y-m-d')."'<br>";
                                    }
                                    
                                    if(!empty($str))
                                    {
                                        $insertLoc .= trim($str , ',');
                                        $sql_rule_project_mappings = @mysql_query($insertLoc) or die(mysql_error());
                                    }
                                }
                                
                            }
                            else
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_project_mappings` WHERE project_id = ".mysql_escape_string($val)." AND rule_id = ".mysql_escape_string($rule_id));
                            
                                if(!@mysql_num_rows($chkSql) > 0)
                                {
                                    $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                    
                                    //echo "INSERT INTO `rule_project_mappings` SET 
    //                                                            `rule_id` = '".$rule_id."',
    //                                                            `project_id` = '".mysql_escape_string($val)."',
    //                                                            `updated_by` = '".$_SESSION['adminId']."',
    //                                                            `created_at` = '".date('Y-m-d')."'"."<br>";
                                }
                            }
                                  
                        }
                        
                        if($chkAll == 0 && !empty($insertLocpa) && !empty($str1))
                        {
                            $insertLocpa .= trim($str1, ',');
                            $sql_rule_project_mappings = @mysql_query($insertLocpa) or die(mysql_error());
                        }
                    }
                    
                    
                    if(!empty($finalagentidArr))
                    {
                        foreach($finalagentidArr as $key => $val)
                        {
                            if($val == 'all')
                                break;
                            else
                                $sql_rule_locality_mappings = @mysql_query("DELETE FROM `rule_agent_mappings` WHERE agent_id = '".mysql_escape_string($val)."' AND rule_id = '".$rule_id."'");
                            //echo "DELETE FROM `rule_agent_mappings` WHERE agent_id=".mysql_escape_string($val)." AND rule_id = ".$rule_id."<br>"; 
                        }
                    }
                    //print'<pre>';
//                    print_r($agent);
//                    die;
                    
                    if(!empty($agent))
                    {
                        $chkAll = 0;
                        $insertLocpa = ' INSERT INTO `rule_agent_mappings` (rule_id,agent_id,updated_by,created_at) values';
                        $str1 = '';
                        foreach($agent as $key => $val)
                        {
                            if(empty($val))
                                continue;
                            
                            if($val == 'all')
                            {
                                $chkAll = 1;
                                break;
                            }
                            else
                            {
                                $chkSql = @mysql_query("SELECT * FROM `rule_agent_mappings` WHERE agent_id = '".mysql_escape_string($val)."' AND rule_id = '".mysql_escape_string($rule_id)."'");
                                    
                                if(!@mysql_num_rows($chkSql) > 0)
                                {
                                    $str1 .= " ('".$rule_id."','".mysql_escape_string($val)."','".$_SESSION['adminId']."','".date('Y-m-d')."')".",";
                                    
                                }
                                
                            }    
                        }
                        
                        if($chkAll == 0 && !empty($insertLocpa) && !empty($str1) )
                        {
                            $insertLocpa .= trim($str1, ',');
                            $sql_rule_agent_mappings = @mysql_query($insertLocpa) or die(mysql_error());
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
        else if(!empty($ruleId))
        {
            header("Location:ruleadd.php?city_id=".$city_id."&page=1&sort=all");
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
        if(!empty($_POST['city_id']))
            $cityID = $_POST['city_id'];
        else if(!empty($_GET['city_id']))
            $cityID = $_GET['city_id'];  
        $ruleAttr = ProjectAssignmentRules::find('all');
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
            
            $fetchAgentQuery = @mysql_query("SELECT COUNT(agents.id) agentcount FROM agents LEFT JOIN broker_contacts ON agents.id = broker_contacts.broker_id WHERE agents.broker_id = ".mysql_escape_string($_POST['broker_cmpny_id'])." AND broker_contacts.type='Agent' AND agents.id NOT IN (SELECT agent_id FROM rule_agent_mappings)");
            $agentcount = @mysql_fetch_assoc($fetchAgentQuery);
            $agentcount = $agentcount['agentcount'];
            $agentcounter = 0;
            
            $i = 0;
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
                //echo RuleLocalityMappings::connection()->last_query."<br>";
                //print_r($localityAttr);
                //continue;
                //die;
                
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
                
                if(!empty($locality) && $locality == 'all')
                {
                    $conditions = " rule_project_mappings.rule_id = ".$val->id;
                    $joins = " LEFT JOIN resi_project ON rule_project_mappings.project_id = resi_project.project_id";
                    $options = array('joins' => $joins , 
                                    'select' => " resi_project.project_id,resi_project.project_name" , 
                                    'conditions' => $conditions
                                );
                }
                else
                {
                    $conditions = " rule_project_mappings.rule_id = ".$val->id;
                    $joins = " LEFT JOIN resi_project ON rule_project_mappings.project_id = resi_project.project_id";
                    $options = array('joins' => $joins , 
                                    'select' => " resi_project.project_id,resi_project.project_name" , 
                                    'conditions' => $conditions
                                );    
                }
                
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
            
            $NumRows = count($data);
            if($NumRows == 0)   
                $NumRows = $count;
        }
        //print'<pre>';
//        print_r($data);
        //die;
        $locarr = array();
        
        //$sql = @mysql_query("SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = '".mysql_escape_string($cityID)."' AND locality.locality_id NOT IN (SELECT locality_id FROM rule_locality_mappings)");
        
        $sql = @mysql_query("SELECT locality.locality_id , locality.label 
                                        FROM locality 
                                        LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id 
                                        LEFT JOIN city ON suburb.city_id = city.city_id  
                                        WHERE city.city_id = '".mysql_escape_string($cityID)."'");
                                         
                                         
        while($row = @mysql_fetch_assoc($sql))
        {
            $chkSql = @mysql_query("SELECT resi_project.project_id 
                                                FROM resi_project  
                                                LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                                WHERE locality.locality_id = '".$row['locality_id']."'");
            $c1 = @mysql_num_rows($chkSql);
            $chkSql = @mysql_query("SELECT resi_project.project_id 
                                                FROM resi_project  
                                                LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                                WHERE locality.locality_id = '".$row['locality_id']."'");
            $c2 = @mysql_num_rows($chkSql);
            $locarr[$row['locality_id']] = $row['label'];    
        }
        //print'<pre>';
//        print_r($locarr);
        //print_r($data);
        //die;
        $smarty->assign("city_id" , $cityID);
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

