<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script language="javascript">

jQuery(document).ready(function(){ 
//$("#pe").hide(''); 

	$("#create_button").click(function(){
	  //cleanFields();
	  
	    $('#search-bottom').hide('slow');
	   $('#create_Landmark').show('slow'); 
	});

	$("#exit_button").click(function(){
	  //cleanFields();
	   $('#create_Landmark').hide('slow'); 
	 
	    $('#search-bottom').show('slow');
	});
/*
	$("#lmkSave").click(function(){
		var compType = $('#companyTypeEdit').children(":selected").val();
		var name = $('#name').val();        
		var des = $('#des').val();
		var address = $('#address').val();
		var pincode = $('#pincode').val();
		var person = $('#person').val();
		var phone = $('#phone').val();
		var fax = $('#fax').val();
		var email = $('#email').val();
		var web = $('#web').val();
		var pan = $('#pan').val();
		var status = $('#status').val();
		
		 var error = 0;
	    var mode='';
	    if(compid) mode = 'update';
	    else mode='create';

	    if (error==0){
      
	      	$.ajax({
	            type: "POST",
	            url: '/saveCompany.php',
	            data: { id:compid, type: compType, name : name, des : des, address : address, pincode : pincode, person : person, phone:phone, fax:fax, email:email web:web, pan:pan, status:status, task : 'createComp' , mode:mode},
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


	});*/

}); //document.ready ends

function selectDeal(id){
  var val = $("#"+id+" option:selected").val();
 
  if(val=="1"){
    console.log("1");
    $("#investment").hide(''); 
    $("#exit").hide(''); 
    $("#saveDiv").show('slow'); 
    $("#fundrais").show('slow'); 
  }
  else if(val=="2"){
    console.log("2");
    $("#fundrais").hide(''); 
    $("#exit").hide(''); 
    $("#saveDiv").show('slow'); 
    $("#investment").show('slow'); 
  }
  else if(val=="3"){
    console.log("3");
    $("#investment").hide(''); 
    $("#fundrais").hide(''); 
    $("#saveDiv").show('slow'); 
    $("#exit").show('slow'); 
  }
}

function refreshProject(no){
  var val = $("#deal option:selected").val();
  if (val==2)
    var tableId = "invest_proj";
  else if(val==3)
    var tableId = "exit_proj";
  var table = document.getElementById(tableId);
  //var old_no = parseInt($("#projects").rows.length);
  var old_no = parseInt(table.rows.length);
  var new_no = parseInt(no);
  console.log(old_no);
  console.log(new_no);
  if(new_no > old_no){
    for(old_no; old_no < new_no; old_no++){
      addRow(tableId);
    }
  }
  else if(new_no < old_no){
    for(old_no; old_no>new_no; old_no--){
      deleteRow(tableId);
    }
  }
}

function builderChanged(id){
  var val = $("#deal option:selected").val();
  if (val==2){
    var nodropdwn = "invest_proj_no";
    var projId = "invest_proj_";
    var sel = $("#"+projId+"0");
  }
  else if(val==3){
    var nodropdwn = "exit_proj_no";
    var projId = "exit_proj_";
    var sel = $("#"+projId+"0");
  }
  var builderId = $("#"+id+" option:selected").val();
   $.ajax({
              type: "POST",
              url: '/savePrivateEquity.php',
              data: { builder_id:builderId},
              success:function(msg){
                var d = jQuery.parseJSON(msg);
                  console.log(d);//d = parseJSON(msg);     
                  $("#"+nodropdwn+" option:selected").text("1");
                  refreshProject(1);
                  sel.empty();
                  $.map(d, function (v) {
                     $('<option>').val(v.id).text(v.name).appendTo(sel);
                  });
                  //for (var i=0; i<msg.length; i++) {
                    //sel.append('<option value="' + d[i].id + '">' + d[i].name + '</option>');
                  //}
              },
          });
}

function addRow(tableID) {
            var val = $("#deal option:selected").val();
  if (val==2){
    var fieldId = "invest_proj";
  }
  else if(val==3){
    var  fieldId = "exit_proj";
  }
            var table = document.getElementById(tableID);
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            cell1.innerHTML = "Project :";
            cell1.width = "10%";
            cell1.style.textAlign="right";

            var cell2 = row.insertCell(1);
            var element2 = document.createElement("select");
           
            element2.id=fieldId+"_"+rowCount;
            element2.name =fieldId+"[]";

            var $options = $("#"+fieldId+"_0 > option").clone();
            $options.appendTo(element2);
            
            cell2.appendChild(element2);
 
        }

function deleteRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
             table.deleteRow(rowCount-1);
               
 
 
            }catch(e) {
                alert(e);
            }
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
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Private Equity Deals</TD>
                      </TR>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=left class="backgorund-rt" height=450><BR>
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" align="left" border=0 >
                    <tr>  
                      <td width="20%" height="25" align="left" valign="top">
                          <select id="deal" name="deal" onchange="selectDeal(this.id)">
                             <option value='0'>Select Deal Type</option>
                             <option value='1'>Fund Raising</option>
                             <option value='2'>Investment in a Project</option>
                             <option value='3'>Exit from a Project</option>
                          </select>
                      </td>
                    </tr>
                      
                  </table>
                

                 
                  <div id='pe' align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <input type="hidden" name="old_sub_name" value="">
                    <tbody id='fundrais' style="display:none" align="left">
                    
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Private Equity Name: </td>
                        <td width="40%" height="25" align="left" valign="top">
                                    <select id="companyTypeEdit" name="companyEdit" >
                                       <option value=''>select place type</option>
                                       {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" >Value (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">News Article:</td>
                      <td width="30%" align="left" >
                      <input type=text name="des" id="des"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Date of Transaction1:</td>
                      <td width="30%" align="left" >
                      <input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>
                  </tbody>

                    <tbody id='investment' style="display:none" align="left">
                        <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Private Equity Name: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="companyTypeEdit" name="companyEdit" >
                                       <option value=''>select place type</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Builder: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="investBuilderEdit" name="investBuilderEdit" onchange = "builderChanged(this.id)">
                                       <option value=''>select place type</option>
                                       {foreach from=$builderList key=k item=v}
                                              <option value="{$k}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >No of Project: </td>
                      <td width="20%" height="25" align="left" valign="top">
                    <select name="invest_proj_no" id="invest_proj_no" onchange="refreshProject(this.value);">
              
                     <option  value="1" {if $v=="ert"} selected="selected"{else} value="1" {/if}>1</option>
                     <option  value="2" {if $v=="ert"} selected="selected"{else} value="2" {/if}>2</option> 
                     <option  value="3" {if $v=="ert"} selected="selected"{else} value="3" {/if}>3</option> 
                     <option value="4" {if $v=="ert"} selected="selected"{else} value="4" {/if}>4</option> 
                      <option  value="5" {if $v=="ert"} selected="selected"{else} value="5" {/if}>5</option> 
                     <option  value="6" {if $v=="ert"} selected="selected"{else} value="6" {/if}>6</option> 
                     <option value="7" {if $v=="ert"} selected="selected"{else} value="7" {/if}>7</option> 
                      <option  value="8" {if $v=="ert"} selected="selected"{else} value="8" {/if}>8</option> 
                     <option  value="9" {if $v=="ert"} selected="selected"{else} value="9" {/if}>9</option> 
                     <option  value="10" {if $v=="ert"} selected="selected"{else} value="10" {/if}>10</option>
                    </select></td>
                    </tr>
                  
                    <tr>
                      <td colspan="3" >
                      <table id="invest_proj" width = "100%">
                      <tr>
                      <td width="15%" align="right" >Project: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="invest_proj_0" name="invest_proj[]" >
                                       <option value=''>select place type</option>
                                       
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    </table>
                  </td>
                  </tr>
                    <tr>
                      <td width="10%" align="right" >Value of Investment (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">News Article:</td>
                      <td width="30%" align="left" >
                      <input type=text name="des" id="des"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Date of Transaction:</td>
                      <td width="30%" align="left" >
                      <input name="img_date2" type="text" class="formstyle2" id="img_date2" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger2" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>
                      </tbody>

                       <tbody id='exit' style="display:none" align="left">
                        <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Private Equity Name: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="companyTypeEdit" name="companyEdit" >
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Builder: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="exitBuilderEdit" name="exitBuilderEdit" onchange = "builderChanged(this.id)">
                                       <option value=''>select place type</option>
                                       {foreach from=$builderList key=k item=v}
                                              <option value="{$k}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >No of Project: </td>
                      <td width="20%" height="25" align="left" valign="top">
                    <select name="exit_proj_no" id="exit_proj_no" onchange="refreshProject(this.value);">
              
                     <option {if $img == 1} value="1" selected="selected"{else} value="1" {/if}>1</option>
                     <option {if $img == 2} value="2" selected="selected"{else} value="2" {/if}>2</option> 
                     <option {if $img == 3} value="3" selected="selected"{else} value="3" {/if}>3</option> 
                     <option {if $img == 4} value="4" selected="selected"{else} value="4" {/if}>4</option> 
                      <option {if $img == 5} value="5" selected="selected"{else} value="5" {/if}>5</option> 
                     <option {if $img == 6} value="6" selected="selected"{else} value="6" {/if}>6</option> 
                     <option {if $img == 7} value="7" selected="selected"{else} value="7" {/if}>7</option> 
                      <option {if $img == 8} value="8" selected="selected"{else} value="8" {/if}>8</option> 
                     <option {if $img == 9} value="9" selected="selected"{else} value="9" {/if}>9</option> 
                     <option {if $img == 10} value="10" selected="selected"{else} value="10" {/if}>10</option>
                    </select></td>
                    </tr>
                  
                    <tr>
                      <td colspan="3" >
                      <table id="exit_proj" width = "100%">
                      <tr>
                      <td width="15%" align="right" >Project: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="exitProjEdit" name="exitProjEdit" >
                                       <option value=''>select place type</option>
                                       
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    </table>
                  </td>
                  </tr>
                    <tr>
                      <td width="10%" align="right" >Value of Exit (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" >Value of Investment (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">News Article:</td>
                      <td width="30%" align="left" >
                      <input type=text name="des" id="des"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Date of Transaction:</td>
                      <td width="30%" align="left" >
                      <input name="img_date3" type="text" class="formstyle2" id="img_date3" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger3" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Holding Period:</td>
                      <td width="30%" align="left" >
                      <input type=text name="des" id="des"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                      </tbody>

                    <tbody id='saveDiv' style="display:none" >
                    <tr>
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
                      <input type="button" name="lmkSave" id="lmkSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp; <input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">                 
                      </td>
                    </tr>
                    </tbody>
                  </form>
                  </TABLE> 
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


<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {}
        
        for(i=1;i<=3;i++){
            cals_dict["img_date_trigger"+i] = "img_date"+i;
     
        };

        $.each(cals_dict, function(k, v) {
            if ($('#' + k).length > 0) {
                Calendar.setup({
                    inputField: v, // id of the input field
                    //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
                    ifFormat: "%Y-%m-%d", // format of the input field
                    button: k, // trigger for the calendar (button ID)
                    align: "Tl", // alignment (defaults to "Bl")
                    singleClick: true,
                    showsTime: true
                });
            }
        });
   
 </script>