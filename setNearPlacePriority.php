<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();
$cityId = $_GET['cityId'];
$localityid = $_GET['localityid'];
$suburbid = $_GET['suburbid'];
$nearPlaceId = $_GET['id'];
$priority = $_GET['priority'];
$status = $_GET['status'];
$highPrio = getAvaiHighProjectPriority($cityId, $localityid, $suburbid);
?>
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript"> 
/*jQuery(document).ready(function(){ 
    var cityId          = $('#cityId').val();
    var localityid      = $('#localityid').val();
    var suburbid        = $('#suburbid').val();
    if(suburbid!=''){
       $('#projectsearch').autocomplete({source:"suggest_auto.php?mode=suburb&type=project&id="+suburbid, minLength:1});
    }
    else if(localityid!=''){
       $('#projectsearch').autocomplete({source:"suggest_auto.php?mode=locality&type=project&id="+localityid, minLength:1});
    }
    else{
        $('#projectsearch').autocomplete({source:"suggest_auto.php?mode=city&type=project&id="+cityId, minLength:1});
    }
});*/
jQuery(document).ready(function(){
    $( "#priority_form").submit(function() {
        var nearPlaceId   = $('#nearPlaceId').val();
        var prior       = $('#priority').val();
        var cityId      = $('#cityId').val();
        var localityid  = $('#localityid').val();
        var suburbid    = $('#suburbid').val();
        var status      =  $('#status').val();
        //var autoadjust  = $("#autoadjust").is(':checked') ? 1 : 0;
        if($('#nearplacesearch').val() === ''){
            alert("Please add a Near Place");
            return false;
        }
        if(isNaN(prior)){
            alert("Select a Priority");
            return false;
        }
        //alert (prior+cityId);
        $.ajax({
            type: "POST",
            url: '/saveNearPlacePriority.php',
            data: { nearPlaceId: nearPlaceId, prio:prior, cityId:cityId, loc:localityid, sub:suburbid, status:status },
            success:function(msg){
               if(msg == 1){
                   alert("Priority Successfully updated");
                   parent.location.reload(true); 
               }
               if(msg == 2){
                   alert("Error Wrong Near Place selected");
                   return false;
               }
               if(msg == 4){
                   alert("Please enter valid Priority. Priority should be numeric and between 0 to 6.");
                   return false;
               }
            }
        })
  });
});
function show_loc_inst(){
    var w = window.open("Surprise", "_blank",'width=300,height=120,top=350,left=500');
    var d = w.document.open();
    d.write("<!DOCTYPE html><html><body>Check the checkbox to auto shift the projects, if desired.If this is not selected, then multiple projects could be at the same priority(which is fine, if that is what you want)</body></html>");
    d.close();
}
</script>
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.2.custom.css" />
<style type="text/css">
<!--
/* style the auto-complete response */
li.ui-menu-item { font-size:12px !important; }
-->
</style> 
<TABLE cellSpacing=1 cellPadding=4 width="80%" align=center border=0>
<form id="priority_form" onsubmit="return false;">
  <TBODY>
    <TR class = "headingrowcolor">
        <TD class=whiteTxt width=12% align="right">Near Place Name or ID:</TD>
        <TD class=whiteTxt width=15% align="left"><?php if(!empty($_GET['mode'])){ echo $_GET['id'];}?></TD>
    </TR>
    <TR>
        <TD class=whiteTxt width=12% align="right">Priority:</TD>
        <!--<TD class=whiteTxt width=15% align="left"><input type="text" id="priority" value="<?php //if(!empty($_GET['mode'])){ echo $_GET['priority'];}else{ echo '5';}?>" /></TD>-->
        <TD class=whiteTxt width=15% align="left">
          <select id="priority" value="<?php if(!empty($_GET['mode'])){ echo $_GET['priority'];}else{ echo '5';}?>" >
          <option name=one value=1 <?php if($_GET['priority']==1) { echo "selected = 'selected'";}?> > one </option>
          <option name=two value=2 <?php if($_GET['priority']==2) { echo "selected = 'selected'";}?> > two </option>
          <option name=three value=3 <?php if($_GET['priority']==3) { echo "selected = 'selected'";}?> > three </option>
          <option name=four value=4 <?php if($_GET['priority']==4) { echo "selected = 'selected'";}?> > Four </option>
          <option name=five value=5 <?php if($_GET['priority']==5) { echo "selected = 'selected'";}?> > Five </option>
          
        </select>
      </TD>
    <TR>
        <TD class=whiteTxt width=12% align="right">Status:</TD>
        <TD class=whiteTxt width=15% align="left">
          <select id="status" value="<?php if(!empty($_GET['mode'])){ echo $status;}?>" >
          <option name=one value='Active' <?php if($_GET['status']=='Active') { echo "selected = 'selected'";}?> > Active </option>
          <option name=two value='Inactive' <?php if($_GET['status']=='Inactive') { echo "selected = 'selected'";}?> > Inactive </option>
                  
        </select>
      </TD>
    </TR>
    <!--<TR>
        <TD class=whiteTxt width=12% align="right"><input type="checkbox" name="autoadjust" id="autoadjust" value="" /></TD>
        <TD class=whiteTxt width=15% align="left">Auto Adjust Priorities&nbsp;<img src="images/exclamation.png" id="autoimg" border="0" onclick="show_loc_inst();" style="cursor:pointer;" /></TD>
    </TR>-->
    <TR>
        <TD class=whiteTxt width=12% align="center">
            <input type="hidden" name="nearPlaceId" id="nearPlaceId" value="<?php echo $nearPlaceId;?>" />
            <input type="hidden" name="cityId" id="cityId" value="<?php echo $cityId;?>" />
            <input type="hidden" name="localityid" id="localityid" value="<?php echo $localityid;?>" />
            <input type="hidden" name="suburbid" id="suburbid" value="<?php echo $suburbid;?>" />
        </TD>
        <TD class=whiteTxt width=15% align="center"><input type="submit" id="submit" name="submit" value="Submit" /></TD>
    </TR>
    
    
  </TBODY>
</FORM>
</TABLE>
