<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/resale_functions.php");
AdminAuthentication();

$callId = $_POST['callId'];
$brokerId = $_POST['brokerId'];

//adding project to broker for specific call
if($brokerId)
{
	$ErrorMsg = '';
   /**********project add code start here***********/
   $arrProjectListInValid = array();
   $arrProjectListValid = array();
   $flag = 0;
   $projectList = getProjectByBroker($brokerId);
   $projectExist = array();
   foreach($projectList as $key=>$val) {
     $projectExist[] = $val['PROJECT_ID'];
   } 
   foreach($_REQUEST['multiple_project'] as $k=>$v) {
      if($v !='') {  
        $flag = 1;
        $projectdetail = projectdetail($v);
        if( count($projectdetail) != 0 ) {
          if( !in_array($v,$projectExist) ) {
            $arrProjectListValid[] = $v;
          }
        }
        else {
          $arrProjectListInValid[] = $v; 
        }
     }
   } 
   if($flag == 1) {
     $cnt = 1;
     $comma = ',';
     $qryIns = "INSERT IGNORE INTO broker_project_mapping (PROJECT_ID,BROKER_ID,ACTION_DATE) VALUES ";
     if( !empty($_REQUEST['callId']) ) {
        $qryCallProject = 'INSERT INTO CallProject (CallId, ProjectId, BROKER_ID) VALUES ';
     }
     if(count($arrProjectListValid) > 0) {
       foreach($arrProjectListValid as $val) {
         if($cnt == count($arrProjectListValid))
           $comma = '';
         $qryIns .= "($val,$brokerId, now())$comma";
         if( !empty($_REQUEST['callId']) ) {
           $qryCallProject .= "(".$_REQUEST['callId'].",$val, $brokerId)$comma";
         }
         $cnt++;
       }
       if( !empty($_REQUEST['callId']) ) {
        $resInsCall = mysql_query($qryCallProject) or die(mysql_error()." call detail");
       }
       $resIns = mysql_query($qryIns) or die(mysql_error());
       if($resIns)
         $ErrorMsg['success'] = "Data has been inserted successfully!";
       if(count($arrProjectListInValid)>0) {
         $str = implode(", ",$arrProjectListInValid);
         $ErrorMsg['wrongPId'] = "You cant enter wrong project ids which are following: $str";
       }  
     }
     else{
         $ErrorMsg['wrongPId'] = "All Project ids are Invalid or Duplicate!";
     }
   }
}
?>
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.2.custom.css" />
<script type="text/javascript">
    function addMoreProject(ct) {
        for(i=1;i<=99;i++)
        {
         document.getElementById('addId_'+i).style.display='none';
        }	
        for(i=1;i<=ct;i++)
        {
         document.getElementById('addId_'+i).style.display='block';
        }		
    }
  function isNumberKey(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;
 	 if (charCode > 31 && (charCode < 48 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }
</script>

<TABLE cellSpacing=1 cellPadding=4 width="80%" align=center border=0>
<form method="post">
  <TBODY>
	  <?php if ($ErrorMsg["dataInsertionError"] != ''): ?>
        <tr><td colspan = "2" align ="center"><font color = "red"><?php print $ErrorMsg["dataInsertionError"] ?></font></td></tr>
      <?php endif ?>
      <?php if ($ErrorMsg["success"] != ''): ?>
        <tr><td colspan = "2" align ="center"><font color = "green"><?php print $ErrorMsg["success"] ?></font></td></tr>
       <?php endif ?>
      <?php if ($ErrorMsg["wrongPId"] != ''): ?>
        <tr><td colspan = "2" align ="center"><font color = "red"><?php print $ErrorMsg["wrongPId"] ?></font></td></tr>
       <?php endif ?>
     <tr>
	   <td width="60%" align="right" valign="top">How many project ids would you like to add? :</td>
	   <td width="40%" align="left" >
		  <select name="addMore" onchange="addMoreProject(this.value);">
             <?php 
				   for($i=1;$i<100;$i++){
					print  '<option value="'.$i.'">'.$i.'</option>';
				   }
             ?>
          </select>
        </td>       
    </tr>
    <tr>
        <td width="20%" align="right" valign="top">Project Ids :</td>
        <td width="30%" align="left" >
			 <?php 
				   for($i=1;$i<100;$i++){
					 if($i!=1)
						$style = "style='display:none'";
					 else
						$style = "style=''";
					 print  '<div id="addId_'.$i.'" '.$style.'>
                               <input maxlength="10" onkeypress="return isNumberKey(event);" type="text" name ="multiple_project[]" value="">
                            </div>';
				   }
             ?>
        </td>
   </tr>
   <tr>
		<td>&nbsp;</td><td>&nbsp;</td>
   </tr> 
   <tr>
		<td></td>
		<td>
		  <input type="hidden" value="<?php print $_GET['callId']; ?>" name="callId" />
		  <input type="hidden" value="<?php print $_GET['brokerId']; ?>" name="brokerId" />
		  <input type="submit" value="submit" name="btnSave" />
		</td>
   </tr>    
  </TBODY>
</FORM>
</TABLE>
