<?php 

    $callId = $_REQUEST['callId'];
    $brokerId   = $_REQUEST['brokerId'];
    $projectId  = $_REQUEST['projectId'];
    $smarty->assign("callId", $callId);
    $smarty->assign("brokerId", $brokerId);
    $smarty->assign("projectId", $projectId);
    $selectedVal = '';
    $smarty->assign("selectedVal", selectedVal);
    $ErrorMsg = array();
    if(isset($_REQUEST['exit'])){
       header("Location:secondary_price.php?projectId=$projectId");
    }
   
    if(isset($_REQUEST['submit'])){            
        include("dbConfig.php");
        $arrProjectListInValid = array();
        $flag = 0;
        foreach($_REQUEST['multiple_project'] as $k=>$v) {
            if($v !='') {  
                $flag = 1;
                $projectdetail = projectdetail($v);
               if( count($projectdetail) != 0) {
                   $arrProjectListValid[] = $v;
               }
               else {
                   $arrProjectListInValid[] = $v; 
               }
            }
        } 
        if($flag == 0)
            $ErrorMsg['noPid'] = 'Please enter atleast one project id';
        if(count($ErrorMsg['noPid']) == 0) {
            $cnt = 1;
            $comma = ',';
             $qryIns = "INSERT IGNORE INTO CallProject (CallId,ProjectId,BROKER_ID)
                                VALUES ";
            foreach($arrProjectListValid as $val) {
                 if($cnt == count($arrProjectListValid))
                    $comma = '';
                 $qryIns .= "($callId,$projectId,$val)$comma";
                 $cnt++;
            }
            $resIns = mysql_query($qryIns) or die(mysql_error());
            if($resIns)
                $ErrorMsg['success'] = "Data has been inserted successfully!";
            if(count($arrProjectListInValid)>0) {
                $str = implode(", ",$arrProjectListInValid);
                $ErrorMsg['wrongPId'] = "You cant enter wrong project ids which are following: $str";
            }   
        }
        
    }
    $smarty->assign("ErrorMsg", $ErrorMsg);

?>
