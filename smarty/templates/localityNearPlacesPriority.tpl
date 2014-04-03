<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>


<script language="javascript">
function chkConfirm() 
{
    return confirm("Are you sure! you want to delete this record.");
}
function selectCity(value){
  window.location.href="{$dirname}/locality_near_places_priority.php?&citydd="+value;
}
function selectSuburb(value){
  var cityid = $('#citydd').val();
    window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&suburb="+value;
}
function selectLocality(value){ 
    var cityid = $('#citydd').val();
  window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&locality="+value;
}
function selectNearPlaceTypes(value){ 
    var cityid = $('#citydd').val();
    var locality_id = $('#loc').val();
    var suburb_id = $('#sub').val();
  window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&locality="+locality_id+"&near_place_type="+value;
}

function isNumeric(val) {
        var validChars = '0123456789.';
        var validCharsforfirstdigit = '-01234567890';
        if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
                return false;
        

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}

function isPhnumber(val) {
        var validChars = '0123456789+-';
        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }
        if(val.length >14 || val.length < 6)
          return false;


        return true;
}

function cleanFields(){
    $("#lmkid").val('');
    $('#cityddEdit').val('');
    $("#placeTypeEdit").val('');
    $("#lmkname").val('');
    $("#lmkaddress").val('');
    $("#lmklat").val('');
    $("#lmklong").val('');
    $("#lmkphone").val('');
    $("#lmkweb").val('');
    $("#lmkprio").val('');
    $("#lmkstatus").val('');

    $('#errmsgcity').html('');
    $('#errmsgplacetype').html('');
    $('#errmsgname').html('');
    $('#errmsgaddress').html('');
    $('#errmsglat').html('');
    $('#errmsglong').html('');
    $('#errmsgphone').html('');
    $('#errmsgweb').html('');

}

function landmarkEdit(id,cityid,placeid,lmkname,lmkaddress,lmklat,lmklong,lmkphone,lmkweb,lmkprio,lmkstatus){
    cleanFields();
    $("#lmkid").val(id);
    $('#cityddEdit').val(cityid);
    $("#placeTypeEdit").val(placeid);
    $("#Edit").val(placeid);
    $("#lmkname").val(lmkname);
    $("#lmkaddress").val(lmkaddress);
    $("#lmklat").val(lmklat);
    $("#lmklong").val(lmklong);
    $("#lmkphone").val(lmkphone);
    $("#lmkweb").val(lmkweb);
    $("#lmkprio").val(lmkprio);
    $("#lmkstatus").val(lmkstatus);
    $('#search-top').hide('slow');
    $('#search-bottom').hide('slow');
    window.scrollTo(0, 0);

    if($('#create_Landmark').css('display') == 'none'){ 
     $('#create_Landmark').show('slow'); 
    }
}

jQuery(document).ready(function(){  

$('#search-top').show('slow');
    $('#search-bottom').show('slow');

 String.prototype.isMatch = function(s){
   var b = this.match(s)!==null
   return b;
}
 
$("#create_button").click(function(){
  cleanFields();
  $('#search-top').hide('slow');
    $('#search-bottom').hide('slow');
   $('#create_Landmark').show('slow'); 
});

$("#exit_button").click(function(){
  cleanFields();
   $('#create_Landmark').hide('slow'); 
   $('#search-top').show('slow');
    $('#search-bottom').show('slow');
});

  $("#lmkSave").click(function(){

    var cityid      = $('#cityddEdit').children(":selected").val();
    var placeid = $('#placeTypeEdit').children(":selected").val();
    if(!placeid)
      var placeid = $('#placeTypeHidden').val();
    var lmkid = $('#lmkid').val();
    var lmkname = $("#lmkname").val().trim();
    var lmkaddress = $("#lmkaddress").val().trim();
    var lmklat = $("#lmklat").val().trim();
    var lmklong = $("#lmklong").val().trim();
    var lmkphone = $("#lmkphone").val().trim();
    var lmkweb = $("#lmkweb").val().trim();
    var lmkprio = $("#lmkprio").val().trim();
    var lmkstatus = $("#lmkstatus").val().trim();
    var error = 0;
    var mode='';
    if(lmkid) mode = 'update';
    else mode='create';


    

    

    
    
     //longitude      
     if(lmkweb!=''){
      {literal}
      if (!lmkweb.match(/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/)) {
        $('#errmsgweb').html('<font color="red">Please enter full valid website address with http.</font>');
        $("#lmkweb").focus();
        error = 1;
      }
      else{
          $('#errmsgweb').html('');
      }
      {/literal}
    }
    else{
          $('#errmsgweb').html('');
    }

    if(lmkphone!=''){
      if(!isPhnumber(lmkphone)) {
        $('#errmsgphone').html('<font color="red">Phone No. must be numeric and 6-14 characters</font>');
        $("#lmkphone").focus();
        error=1;
      }
      else{
          $('#errmsgphone').html('');
          }
      }
    else{
          $('#errmsgphone').html('');
    }

    


    
    if (lmklong!=''){
      if(!isNumeric(lmklong)) {
        $('#errmsglong').html('<font color="red">Please enter numeric Longitude</font>');
        $("#lmklong").focus();
        error = 1;
      }
      else   {
        if (!(parseInt(lmklong)<=180) || !(parseInt(lmklong)>=-180)) {
          $('#errmsglong').html('<font color="red">Please enter Longitude between -180 and +180</font>');
          $("#lmklong").focus();
          error = 1;
        }
        else{
          $('#errmsglong').html('');
        }
      }
    }
  else{
        $('#errmsglong').html('');
  }
      
    //latitude   
    

    if(lmklat!=''){
      if(!isNumeric(lmklat)) {
        $('#errmsglat').html('<font color="red">Please enter Numeric Latitude</font>');
        $("#lmklat").focus();
        error=1;
      }
      else   {
        
        if (!(parseInt(lmklat)<=180) || !(parseInt(lmklat)>=-180)) {
          $('#errmsglat').html('<font color="red">Please enter Latitude between -180 and +180</font>');
          $("#lmklat").focus();
          error = 1;
        }
        else{
          $('#errmsglat').html('');
          }
      }
    }
  else{
        $('#errmsglat').html('');
  }

    //address
    if(lmkaddress==''){
      $('#errmsgaddress').html('<font color="red">Please enter Landmark Address</font>');
      $("#lmkaddress").focus();
      error = 1;
    }
    else if (/[^\w#,.\- ]/.test(lmkaddress)){
      
      $('#errmsgaddress').html('<font color="red">Special characters are not allowed in Landmark name</font>');
      $("#lmkaddress").focus();
      error = 1;
    }
    else   {
      $('#errmsgaddress').html('');
    }

    //name

    if(lmkname==''){
      $('#errmsgname').html('<font color="red">Please enter Landmark name</font>');
      $("#lmkname").focus();
      error = 1;
    }
    else if (/[^\w.\- ]/i.test(lmkname)){
      
      $('#errmsgname').html('<font color="red">Special characters are not allowed in Landmark name</font>');
      $("#lmkname").focus();
      error = 1;
    }
    else   {
      $('#errmsgname').html('');
    }

    if(!placeid){
      $('#errmsgplacetype').html('<font color="red">Please select Place Type.</font>');
        $("#placeTypeEdit").focus();
      error=1;
    }
    else $('#errmsgplacetype').html('');

    if(!cityid){
      $('#errmsgcity').html('<font color="red">Please select City.</font>');
        $("#cityddEdit").focus();
      error=1;
    }
    else $('#errmsgcity').html('');
    


    if (error==0){
      
      $.ajax({
            type: "POST",
            url: '/saveNearPlacePriority.php',
            data: { id:lmkid, cid: cityid, placeid:placeid, name : lmkname, address : lmkaddress, lat : lmklat, lon : lmklong, phone:lmkphone, web:lmkweb, prio:lmkprio, status:lmkstatus, task : 'createLandmarkAlias' , mode:mode},
            success:function(msg){
              //alert(msg);
               if(msg == 1){
                alert("Saved");
                location.reload(true);
                //$("#onclick-create").text("Landmark Successfully Created.");
               }
               else if(msg == 2){
                //$("#onclick-create").text("Landmark Already Added.");
                   alert("Already Saved");
                   location.reload(true); 
               }
               else if(msg == 3){
                //$("#onclick-create").text("Error in Adding Landmark.");
                   alert("error");
               }
               else if(msg == 4){
                //$("#onclick-create").text("No Landmark Selected.");
                   alert("no data");
               }
               else alert(msg);
            },
          });

    }
    

  });



});





function openProjectPriorityAdd()
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var url = '/setProjectPriority.php?cityId='+cityid+'&localityid='+localityid+'&suburbid='+suburbid;
    $.fancybox({
        'width'                : 720,
        'height'               : 200,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                 : 'iframe'
    })
}


function nearPlacePriorityEdit(id,type)
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var priority    = $('#priority'+id).val();
    var status      =  $('#status'+id).val();
//alert(cityid+priority+status);
    $.ajax({
            type: "POST",
            url: '/saveNearPlacePriority.php',
            data: { nearPlaceId: id, prio:priority, cityId:cityid, loc:localityid, sub:suburbid, status:status, task:'editpriority' },
            success:function(msg){
               if(msg == 1){
                   alert("Successfully updated");
                   location.reload(true); 
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

    /*
    var url = '/setNearPlacePriority.php?cityId='+cityid+'&localityid='+localityid+'&suburbid='+suburbid+'&type='+type+'&id='+id+'&priority='+priority+'&status='+status+'&mode=edit';
    $.fancybox({
        'width'                :720,
        'height'               :200,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                : 'iframe'
    })  */
}

function projectPriorityDelete(id,type)
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var r = confirm("Are you sure you want to reset");
    if (r == true)
    {
        $.ajax({
          type: "POST",
          url: '/deletePriority.php',
          data: { mode:'project', cityId:cityid, localityid:localityid, suburbid: suburbid, type:type, id:id },
          success:function(msg){
            if(msg == 1){
                 alert("Priority Successfully deleted");
                 window.location.reload(true); 
             }
          }
      })
    }
    else
    {
        alert("OK");
    } 
    
}


function openMap(lat, lon)
{
var url = 'https://maps.google.com/maps?q= '+lat+','+lon;
window.open(url,'1390911428816','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;

 //alert (lat+lon);
    /*var url = '/https://maps.google.com/maps?q= '+lat+','+lon;
    alert (url);
    $.fancybox({
        'width'                :800,
        'height'               :800,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                : 'iframe'
    })*/
}

function show_loc_inst(){
    var wid = screen.width/3;
    var hei = (screen.height/2+25);
    var w = window.open("Surprise1", "_blank","toolbar=no ,left=300, top=200, scrollbar=yes, location=0, status=no,titlebar=no,menubar=no,width="+wid +",height=" +hei);
    var d = w.document.open();
    d.write("<!DOCTYPE html><html><body><h1>Instructions</h1><p>1. Select the city and optinally locality and suburb to see the current projects in that area.</p><p>2. You can set at the max 15 projects for a given area.</p><p>3. Click Add Project to add a project.In that popup box, type in either project name, or project id to insert that project. </p><p>3.1. Type in the priority in the priority field.Lower numeric value is higher priority.</p><p>3.2. Check the checkbox to auto shift the projects, if desired.If this is selected, then projects at and below specified priority are shifted down 1 priority level. If this is not selected, then multiple projects could be at the same priority(which is fine, if that is what you want.)</p><p>4. Projects after first 15 would be  automatically reset to default priority.</p></body></html>");
    d.close();
}
</script>
</TD>
  </TR>
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
        <TR>
          <TD width=224 height=25>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=866>&nbsp;</TD>
  </TR>
        <TR>
          <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
    </TD>
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
    {if $priorityMgmtPermissionAccess == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Landmarks Priority</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <div id="search-top">
                        <table width="70%" border="0" cellpadding="0" cellspacing="0" align="center">
                          <tr>
                                <td width="20%" height="25" align="left" valign="top">
                                    <select id="citydd" name="citydd" onchange="selectCity(this.value)">
                                       <option value=''>select city</option>
                                       {foreach from=$cityArray key=k item=v}
                                           <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                                
                                <td width="20%" height="25" align="left" valign="top">
                                    <select id="loc" name="loc" onchange="selectLocality(this.value)">
                                       <option value=''>select locality</option>
                                       {foreach from=$localityArr key=k item=v}
                                           <option value="{$v->locality_id}" {if $localityId==$v->locality_id}
                                              selected="selected" {/if}>{$v->label}</option>
                                       {/foreach}
                                    </select>
                                </td>

                                
                                <td width="20%" height="25" align="left" valign="top">
                                    <select id="placeType" name="loc" onchange="selectNearPlaceTypes(this.value)">
                                       <option value=''>select place type</option>
                                       {foreach from=$nearPlaceTypesArray key=k item=v}
                                              <option value="{$v->id}" {if $nearPlaceTypesId==$v->id}  selected="selected" {/if}>{$v->display_name}</option>
                                       {/foreach}
                                    </select>
                                </td>
                                
                          </tr>
                        </table>
                      </div>
                      </td>
                    </tr>
                  </table>

                  <div align="left" style="margin-bottom:5px;">
                  <button type="button" id="create_button" align="left">Create New Landmark</button>
                </div>
                  <div id='create_Landmark' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <input type="hidden" name="old_sub_name" value="">
                    <div>
                    <tr>
                      <td width="10%" align="right" >*City : </td>
                                  <td width="20%" height="25" align="left" valign="top">
                                      <select id="cityddEdit" name="cityddEdit" >
                                         <option value=''>select city</option>
                                         {foreach from=$cityArray key=k item=v}
                                             <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                         {/foreach}
                                      </select>
                                  </td>
                      <td width="40%" align="left" id="errmsgcity"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >*Place Type: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="placeTypeEdit" name="placeEdit" >
                                       <option value=''>select place type</option>
                                       {foreach from=$nearPlaceTypesArray key=k item=v}
                                              <option value="{$v->id}" {if $nearPlaceTypesId==$v->id}  selected="selected" {/if}>{$v->display_name}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >*Name : </td>
                      <td width="40%" align="left" ><input type=text name="lmkname" id="lmkname"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">*Address :</td>
                      <td width="30%" align="left" >
                      <textarea name="lmkaddress" rows="10" cols="35" id="lmkaddress" style="width:250px;"></textarea><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Latitude : </td>
                      <td width="30%" align="left"><input type=text name="lmklat" id="lmklat"  style="width:250px;"></td> <td width="20%" align="left" id="errmsglat"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Longitude : </td>
                      <td width="30%" align="left"><input type=text name="lmklong" id="lmklong"  style="width:250px;"></td> <td width="20%" align="left" id="errmsglong"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="lmkphone" id="lmkphone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgphone"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="lmkweb" id="lmkweb" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >*Priority : </td>
                      <td width="30%" align="left"><select id="lmkprio" name="lmkprio"  >
                        <option name=one value=1  >1</option>
                        <option name=two value=2  >2</option>
                        <option name=three value=3 >3</option>
                        <option name=four value=4 >4</option>
                        <option name=five value=5 >5</option>
                        </select>
                      </td> 
                      
                    </tr>

                    <tr>
                      <td width="20%" align="right" >*Status : </td>
                      <td width="30%" align="left"><select id="lmkstatus" name="lmkstatus" >
                        <option name=one value='Active'> Active </option>
                        <option name=two value='Inactive' > Inactive </option>
                                
                        </select>
                      </td> 
                    </tr>

                    <tr>
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
                      <input type="button" name="lmkSave" id="lmkSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp; <input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">                 
                      </td>
                    </tr>
                    </div>
                  </form>
                  </table> 
                  </div> 




                    <div id="search-bottom">
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0 class="tablesorter">
                        <form name="form1" method="post" action="">
                          <thead>
                                <TR class = "headingrowcolor">
                                  <th  width=1% align="center">Serial</th>
                                  <th  width=5% align="center">Name</th>
                                  <TH  width=8% align="center">Vicinity</TH>
                                  <TH  width=4% align="center">Place Type</TH>
                                  <TH  width=8% align="center">Location in Map</TH>
                                  
                                  <TH  width=4% align="center">Priority
                                 <!-- {if (!isset($smarty.post) || !empty($smarty.post.desc_x) )}
                                      <span style="clear:both;margin-left:10px"><input type="image" name="asc" value="asc" src="images/arrow-up.png" width="16"></span>
                                  {else}
                                      <span style="clear:both;margin-left:10px"><input type="image" name="desc" value="desc" src="images/arrow-down.png"></span>
                                  {/if}-->
                                  </TH> 
                                 <TH width=6% align="center">Status</TH> 
				 <TH width=3% align="center">Save</TH>
                                </TR>
                              
                          </thead>
                          <tbody>
                                <!--<TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>-->
                                {$i=0}
                                <!--{if isset($suburbId)}
                                    {$type = DISPLAY_ORDER_SUBURB}
                                {else if isset($localityId)}
                                    {$type = DISPLAY_ORDER_LOCALITY}
                                {else}
                                    {$type = DISPLAY_ORDER}
                                {/if}-->
                                {foreach from=$nearPlacesArr key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i} </TD>
                                  <TD align=center class=td-border>{$v.name}</TD>
                                  <TD align=center class=td-border>{$v.vicinity}</TD>
                                  <TD align=center class=td-border>{$v.display_name}</TD>
                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return openMap('{$v.latitude}','{$v.longitude}');">https://maps.google.com/maps?q= {$v.latitude},{$v.longitude}</a>
                  <!--<a href="http://www.textfixer.com" onclick="javascript:void window.open('http://www.textfixer.com','1390911428816','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">Pop-up Window</a>-->

                                  </TD>
                                  
                                  <!--<TD align=center class=td-border>{$v.priority}</TD>-->
                                   
                                   <TD align=center class=td-border>
                                    <select id="priority{$v.id}" value="" >
          <option name=one value=1  {if $v.priority == 1} selected="selected"  {/if}>1</option>
          <option name=two value=2  {if $v.priority == 2} selected="selected"  {/if}>2</option>
          <option name=three value=3 {if $v.priority == 3} selected="selected"  {/if}>3</option>
          <option name=four value=4 {if $v.priority == 4} selected="selected"  {/if}>4</option>
          <option name=five value=5 {if $v.priority == 5} selected="selected"  {/if}>5</option>
          </select>
          </TD>
        <TD align=center class=td-border>  
	<select id="status{$v.id}" value=''>
          <option name=one value='Active' {if $v.status == 'Active'} selected="selected"  {/if}> Active </option>
          <option name=two value='Inactive' {if $v.status == 'Inactive'} selected="selected" {/if}> Inactive </option>
                  
        </select>
      

      </TD>
                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return nearPlacePriorityEdit('{$v.id}','{$type}','{$v.priority}','{$v.status}');">Save</a> <button type="button" id="edit_button{$v.id}" onclick="return landmarkEdit('{$v.id}', '{$v.city_id}', '{$v.place_type_id}', '{$v.name}', '{$v.vicinity}', '{$v.latitude}', '{$v.longitude}', '{$v.phone_number}', '{$v.website}', '{$v.priority}', '{$v.status}')" align="left">Edit</button></TD>
                                </TR>
                                {/foreach}
                                <!--<TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD></TR>-->
                          </tbody>
                          <tfoot>
                                                        <tr>
                                                            <th colspan="21" class="pager form-horizontal" style="font-size:12px;">
                                                                
                                                                <button class="btn first"><i class="icon-step-backward"></i></button>
                                                                <button class="btn prev"><i class="icon-arrow-left"></i></button>
                                                                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                                                <button class="btn next"><i class="icon-arrow-right"></i></button>
                                                                <button class="btn last"><i class="icon-step-forward"></i></button>
                                                                <select class="pagesize input-mini" title="Select page size">
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option>
                                                                    <option selected="selected" value="100">100</option>
                                                                </select>
                                                                <select class="pagenum input-mini" title="Select page number"></select>
                                                            </th>
                                                        </tr>
                           </tfoot>
                        </form>
                    </TABLE>
                  </div>
                 </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
        {/if}
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
