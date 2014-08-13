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
<script type="text/javascript" src="js/numberToWords.js"></script>

<script language="javascript">

jQuery(document).ready(function(){ 
//$("#pe").hide(''); 

	$("#create_button").click(function(){
	   cleanFields();	   
	   $('#search-bottom').hide('slow');
	   $('#create_deals').show('slow'); 	
	   $('#deal').show();  
	});

	$("#exit_button").click(function(){
	   cleanFields();
	   $('#create_deals').hide('slow'); 
	 
	    $('#search-bottom').show('slow');
	});

	$("#lmkSave").click(function(){
    var error = 0;
    var mode='';
    var dealId = parseInt($('#deal_id').val());
    var data = {};
		var compType = $('#companyTypeEdit').children(":selected").val();
    var val = $("#deal option:selected").val();
    data['deal_id'] = dealId;
  if (val==1){
    var fundCompId = $("#fundCompany").val();
    var fundCompId2 = $("#fundCompany2").val();
    var fundCompId3 = $("#fundCompany3").val();
    var fundValue = $("#fundValue").val().trim();
    var fundArticle = $("#fundArticle").val().trim();
    var fundDate = $("#img_date1").val().trim();
    if(dealId>0) mode = 'update';
    else mode='create';
    data['pe_id'] =  fundCompId;
    data['pe_id_2'] =  fundCompId2;
    data['pe_id_3'] =  fundCompId3;
    data['type'] = "Fund Raising";
    data['value'] = fundValue;
    data['article'] = fundArticle;
    data['date'] = fundDate;
    data['mode'] = mode;

    if(fundDate==''){
      $('#errmsgfunddate').html('<font color="red">Please select a Transaction Date.</font>');
      $("#img_date1").focus();
      error = 1;
    }
    else{
          $('#errmsgfunddate').html('');
    }

    if(fundValue!='' && !isNumeric(fundValue)){
      $('#errmsgfundvalue').html('<font color="red">Please select a Numeric Value.</font>');
      $("#fundValue").focus();
      error = 1;
    }
    else{
          $('#errmsgfundvalue').html('');
    }

    if(fundCompId==''){
      $('#errmsgfundname').html('<font color="red">Please select a Private Equity.</font>');
      $("#fundCompany").focus();
      error = 1;
    }
    else{
          $('#errmsgfundname').html('');
    }



    

  }
  else if (val==2){
    
    var investCompId = $("#investCompany").val();
    var investCompId2 = $("#investCompany2").val();
    var investCompId3 = $("#investCompany3").val();
    var investValue = $("#investValue").val().trim();
    var investArticle = $("#investArticle").val().trim();
    var investDate = $("#img_date2").val();
    var investBuilderId = $("#investBuilderEdit").val();
    var investProjArr = [];
    $('select[name="invest_proj[]"] ').each(function() {
      investProjArr.push($("#"+this.id+" option:selected").val());
    });
    if(dealId>0) mode = 'update';
    else mode='create';
    var extra = {};
    data['pe_id'] =  investCompId;
    data['pe_id_2'] =  investCompId2;
    data['pe_id_3'] =  investCompId3
    data['type'] = "Investment";
    data['builderId'] = investBuilderId;
    data['value'] = investValue;
    data['article'] = investArticle;
    data['date'] = investDate;
    extra['projects'] = investProjArr;
    data['extra'] = JSON.stringify(extra);
    data['mode'] = mode;

    if(investDate==''){
      $('#errmsginvestdate').html('<font color="red">Please select a Transaction Date.</font>');
      $("#img_date2").focus();
      error = 1;
    }
    else{
          $('#errmsginvestdate').html('');
    }

    if(investValue!='' && !isNumeric(investValue)){
      $('#errmsginvestvalue').html('<font color="red">Please select a Numeric Value.</font>');
      $("#investValue").focus();
      error = 1;
    }
    else{
          $('#errmsginvestvalue').html('');
    }

    if(investBuilderId==''){
      $('#errmsginvestbuilder').html('<font color="red">Please select a Builder.</font>');
      $("#investBuilderEdit").focus();
      error = 1;
    }
    else{
          $('#errmsginvestbuilder').html('');
    }


    if(investCompId==''){
      $('#errmsginvestname').html('<font color="red">Please select a Private Equity.</font>');
      $("#investCompany").focus();
      error = 1;
    }
    else{
          $('#errmsginvestname').html('');
    }
  }
  else if(val==3){

    var exitCompId = $("#exitCompany").val();
    var exitCompId2 = $("#exitCompany2").val();
    var exitCompId3 = $("#exitCompany3").val();
    var exitValue2 = $("#exitValue2").val().trim();
    var exitValue1 = $("#exitValue1").val().trim();
    var exitArticle = $("#exitArticle").val().trim();
    var exitDate = $("#img_date3").val().trim();
    var exitPeriod = $("#exitPeriod").val().trim();
    var exitBuilderId = $("#exitBuilderEdit").val().trim();
    var exitProjArr = [];
    $('select[name="exit_proj[]"] ').each(function() {
      exitProjArr.push($("#"+this.id+" option:selected").val());
      //console.log($("#"+this.id+" option:selected").val())
    });
    if(dealId>0) mode = 'update';
    else mode='create';
    var extra = {};
    data['pe_id'] =  exitCompId;
    data['pe_id_2'] =  exitCompId2;
    data['pe_id_3'] =  exitCompId3;
    data['type'] = "Exit";
    data['builderId'] = exitBuilderId;
    data['value'] = exitValue2;
    data['article'] = exitArticle;
    data['date'] = exitDate;
    extra['projects'] = exitProjArr;
    extra['period'] = exitPeriod;
    extra['investmentValue'] = exitValue1;
    extra['exitValue'] = exitValue2;
    data['extra'] = JSON.stringify(extra);
    data['mode'] = mode;

    if(exitDate==''){
      $('#errmsgexitdate').html('<font color="red">Please select a Transaction Date</font>');
      $("#img_date3").focus();
      error = 1;
    }
    else{
          $('#errmsgexitdate').html('');
    }

    if(exitValue1!='' && !isNumeric(exitValue1)){
      $('#errmsgexitvalue1').html('<font color="red">Please select a Numeric Value.</font>');
      $("#exitValue1").focus();
      error = 1;
    }
    else{
          $('#errmsgexitvalue1').html('');
    }

    if(exitValue2!='' && !isNumeric(exitValue2)){
      $('#errmsgexitvalue2').html('<font color="red">Please select a Numeric Value.</font>');
      $("#exitValue2").focus();
      error = 1;
    }
    else{
          $('#errmsgexitvalue2').html('');
    }

    if(exitBuilderId==''){
      $('#errmsgexitbuilder').html('<font color="red">Please select a Builder.</font>');
      $("#exitBuilderEdit").focus();
      error = 1;
    }
    else{
          $('#errmsgexitbuilder').html('');
    }

    if(exitCompId==''){
      $('#errmsgexitname').html('<font color="red">Please select a Private Equity</font>');
      $("#exitCompany").focus();
      error = 1;
    }
    else{
          $('#errmsgexitname').html('');
    }

  }
		
	data['task'] = "save";	
		 
	    

	    if (error==0){
      
	      	$.ajax({
	            type: "POST",
	            url: '/savePrivateEquity.php',
	            data: data,
	            success:function(msg){
	              //alert(msg);
	               if(msg == 1){
	               
	                location.reload(true);
	                //$("#onclick-create").text("Landmark Successfully Created.");
	               }
	               else if(msg == 2){
	                //$("#onclick-create").text("Landmark Already Added.");
	                   //alert("Already Saved");
	                   //location.reload(true); 
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

}); //document.ready ends

function cleanFields(){
	
  $('#deal_id').val("");
  $('#deal').val(0);
  
  $("#fundCompany").val("");
  $("#fundCompany2").val("");
  $("#fundCompany3").val("");
  $("#fundValue").val("")
  $("#fundArticle").val("");
  $("#img_date1").val("");
  $('#errmsgfunddate').html('');
  $('#errmsgfundvalue').html('');
  $('#errmsgfundname').html('');
  $("#investCompany").val("");
  $("#investCompany2").val("");
  $("#investCompany3").val("");
  $("#investValue").val("");
  $("#investArticle").val("");
  $("#img_date2").val("");
  $("#investBuilderEdit").val("");
  $('select[name="invest_proj[]"] ').val(0);
  $('#errmsginvestdate').html('');
  $('#errmsginvestvalue').html('');
  $('#errmsginvestbuilder').html('');
  $('#errmsginvestname').html('');
  $("#exitCompany").val("");
  $("#exitCompany2").val("");
  $("#exitCompany3").val("");
  $("#exitValue2").val("");
  $("#exitValue1").val("");
  $("#exitArticle").val("");
  $("#img_date3").val("");
  $("#exitPeriod").val("");
  $("#exitBuilderEdit").val("");
  $('select[name="exit_proj[]"] ').val(0);
  $('#errmsgexitdate').html('');
  $('#errmsgexitvalue1').html('');
  $('#errmsgexitvalue2').html('');
  $('#errmsgexitbuilder').html('');
  $('#errmsgexitname').html('');
  
  $("#investment").hide(); 
  $("#fundrais").hide(); 
  $("#saveDiv").hide(); 
  $("#exit").hide(); 
   
}
function edit_deal(deal_type_id, deal_id, pe_id_1, pe_id_2, pe_id_3, value, article_link, transaction_date, builder_id, deal_projects, period,investmentValue){
	cleanFields();
	$('#search-bottom').hide('slow');
	$('#create_deals').show('slow');
	$('#deal').val(deal_type_id);
	$('#deal_id').val(deal_id);
	$('#deal').hide();
	selectDeal("deal");
	
	if(deal_type_id == 1){
	  $("#fundCompany").val(pe_id_1);
	  $("#fundCompany2").val(pe_id_2);
	  $("#fundCompany3").val(pe_id_3);
	  $("#fundValue").val(value)
	  $("#fundArticle").val(article_link);
	  $("#img_date1").val(transaction_date);	
	}
	else if(deal_type_id == 2){
	  $("#investCompany").val(pe_id_1);
	  $("#investCompany2").val(pe_id_2);
	  $("#investCompany3").val(pe_id_3);
	  $("#investValue").val(value);
	  $("#investArticle").val(article_link);
	  $("#img_date2").val(transaction_date);
	  $("#investBuilderEdit").val(builder_id);	  
	  builderChanged("investBuilderEdit");	  
	  deal_project = deal_projects.split(",");
	  nop = parseInt(deal_project.length);	 	
	  setTimeout("no_proj_Edit('invest_proj',nop,deal_project)",1000);  
	}
	else if(deal_type_id == 3){
	  $("#exitCompany").val(pe_id_1);
	  $("#exitCompany2").val(pe_id_2);
	  $("#exitCompany3").val(pe_id_3);
	  $("#exitValue2").val(value);
	  $("#exitValue1").val(investmentValue);
	  $("#exitArticle").val(article_link);
	  $("#img_date3").val(transaction_date);
	  $("#exitPeriod").val(period);
	  $("#exitBuilderEdit").val(builder_id);
	  builderChanged("exitBuilderEdit");	  
	  deal_project = deal_projects.split(",");
	  nop = parseInt(deal_project.length);	 	
	  setTimeout("no_proj_Edit('exit_proj',nop,deal_project)",1000);
	}
	
	
}
function no_proj_Edit(deal_type,nop,deal_projects){
  refreshProject(nop);
  $('select[name="'+deal_type+'_no"]').val(nop);
  $.each(deal_projects,function(k,v){	      
	 // alert(k + " - " + v);
     $('#'+deal_type+'_'+k).val(v);
  });
}

function selectDeal(id){
  var val = $("#"+id+" option:selected").val();
 
  if(val=="1"){
    
    $("#investment").hide(); 
    $("#exit").hide(); 
    $("#saveDiv").show(); 
    $("#fundrais").show(); 
  }
  else if(val=="2"){
  
    $("#fundrais").hide(); 
    $("#exit").hide(); 
    $("#saveDiv").show(); 
    $("#investment").show(); 
  }
  else if(val=="3"){
   
    $("#investment").hide(); 
    $("#fundrais").hide(); 
    $("#saveDiv").show(); 
    $("#exit").show(); 
  }
}

function refreshProject(no){
  if(no==0) no=1;
  var val = $("#deal option:selected").val();
  if (val==2)
    var tableId = "invest_proj";
  else if(val==3)
    var tableId = "exit_proj";
  var table = document.getElementById(tableId);
  //var old_no = parseInt($("#projects").rows.length);
  var old_no = parseInt(table.rows.length);
  var new_no = parseInt(no);
  
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
              data: { builder_id:builderId, task:"getProject"},
              success:function(msg){		
                var d = jQuery.parseJSON(msg);
                  
                  $("#"+nodropdwn).val("0");
                  refreshProject(1);
                  sel.empty();
                  $('<option>').val("0").text("Select Project").appendTo(sel);
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
    var projClass = "investProj";
  }
  else if(val==3){
    var  fieldId = "exit_proj";
    var projClass = "exitProj";
  }
            var table = document.getElementById(tableID);
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            cell1.innerHTML = "Project :";
            cell1.width = "10%";
            cell1.style.textAlign="right";

            var cell2 = row.insertCell(1);
            //cell1.width = "20%";
            var element2 = document.createElement("select");
           
            element2.id=fieldId+"_"+rowCount;
            element2.class=projClass;
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


function deletePEDeal(id){
  alert("Are you sure you want to delete this Deal.");
  var dealId = id;
  var task = "delete";
  $.ajax({
              type: "POST",
              url: '/savePrivateEquity.php',
              data: { id:dealId, task:task },
              success:function(msg){
                //alert(msg);
                 if(msg == 1){
                 
                  location.reload(true);
                  $(window).scrollTop(0);
                  //$("#onclick-create").text("Landmark Successfully Created.");
                 }
                 else if(msg == 2){
                  //$("#onclick-create").text("Landmark Already Added.");
                    
                     //location.reload(true); 
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

function isNumeric(val) {
        var validChars = '0123456789,';
        var validCharsforfirstdigit = ',1234567890';
        if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
                return false;
        

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}

function onChangeValue(id){
  if (id=="fundValue")     
    fieldid = "fund_value_text";
  else if (id=="investmentValue")
    fieldid = "investment_value_text";
  else if (id=="exitValue1")
    fieldid = "exit_value1_text";
  else if (id=="exitValue2")
    fieldid = "exit_value2_text";
  $("#"+fieldid).innerHTML = toWords($("#"+id).val());

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
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee >
    {if $peDealsAuth == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Private Equity Deals</TD>
                      </TR>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=left class="backgorund-rt" height=450><BR>
                  
                

                  <div align="left" style="margin-bottom:5px;">
                  <button type="button" id="create_button" align="left">Create a New Deal</button>
                </div>
                  <div id='create_deals' style="display:none" align="left">
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
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <input type="hidden" name="old_sub_name" value="">
                    <tbody id='fundrais' style="display:none" align="left">
                    
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Private Equity Name: </td>
                        <td width="40%" height="25" align="left" valign="top">
                                    <select id="fundCompany" name="fundCompany" >
                                       <option value=''>Select Private Equity</option>
                                       {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgfundname"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >Private Equity Name 2: </td>
                        <td width="40%" height="25" align="left" valign="top">
                                    <select id="fundCompany2" name="fundCompany2" >
                                       <option value=''>Select Private Equity</option>
                                       {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgfundname2"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >Private Equity Name 3: </td>
                        <td width="40%" height="25" align="left" valign="top">
                                    <select id="fundCompany3" name="fundCompany3" >
                                       <option value=''>Select Private Equity</option>
                                       {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgfundname3"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" >Value (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="fundValue" id="fundValue"  style="width:250px;" onchange="onChangeValue(this.id);"></td><td width="10%" align="left" id="fund_value_text"></td><td width="40%" align="left" id="errmsgfundvalue"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">News Article:</td>
                      <td width="30%" align="left" >
                      <input type=text name="fundArticle" id="fundArticle"  style="width:250px;">
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Date of Transaction:</td>
                      <td width="30%" align="left" >
                      <input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                      <td width="20%" align="left" id="errmsgfunddate"></td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>
                  </tbody>

                    <tbody id='investment' style="display:none" align="left">
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Private Equity Name: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="investCompany" name="investCompany" >
                                       <option value=''>Select Private Equity</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsginvestname"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >Private Equity Name 2: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="investCompany2" name="investCompany2" >
                                       <option value=''>Select Private Equity</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsginvestname2"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >Private Equity Name 3: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="investCompany3" name="investCompany3" >
                                       <option value=''>Select Private Equity</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsginvestname3"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Builder: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="investBuilderEdit" name="investBuilderEdit" onchange = "builderChanged(this.id)">
                                       <option value=''>Select Builder</option>
                                       {foreach from=$builderList key=k item=v}
                                              <option value="{$k}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsginvestbuilder"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >No of Project: </td>
                      <td width="20%" height="25" align="left" valign="top">
                    <select name="invest_proj_no" id="invest_proj_no" onchange="refreshProject(this.value);">
                      <option  value="0" {if $v=="ert"} selected="selected"{else} value="0" {/if}>Select</option>
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
                                       <option value=''>select Project</option>
                                       
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    </table>
                  </td>
                  </tr>
                    <tr>
                      <td width="10%" align="right" >Value of Investment (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="investValue" id="investValue"  style="width:250px;" onchange="onChangeValue(this.id);"></td><td width="10%" align="left" id="investment_value_text"></td><td width="40%" align="left" id="errmsginvestvalue"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">News Article:</td>
                      <td width="30%" align="left" >
                      <input type=text name="investArticle" id="investArticle"  style="width:250px;">
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Date of Transaction:</td>
                      <td width="30%" align="left" >
                      <input name="img_date2" type="text" class="formstyle2" id="img_date2" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger2" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                     <td width="20%" align="left" id="errmsginvestdate"></td>
                      
                    </tr>
                      </tbody>

                       <tbody id='exit' style="display:none" align="left">
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Private Equity Name: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="exitCompany" name="exitCompany" >
                                      <option value=''>Select Private Equity</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgexitname"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >Private Equity Name 2: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="exitCompany2" name="exitCompany2" >
                                      <option value=''>Select Private Equity</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgexitname2"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >Private Equity Name 3: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="exitCompany3" name="exitCompany3" >
                                      <option value=''>Select Private Equity</option>
                                        {foreach from=$peList key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgexitname3"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Builder: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="exitBuilderEdit" name="exitBuilderEdit" onchange = "builderChanged(this.id)">
                                       <option value=''>Select Builder</option>
                                       {foreach from=$builderList key=k item=v}
                                              <option value="{$k}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgexitbuilder"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >No of Project: </td>
                      <td width="20%" height="25" align="left" valign="top">
                    <select name="exit_proj_no" id="exit_proj_no" onchange="refreshProject(this.value);">
                    <option  value="0" {if $v=="ert"} selected="selected"{else} value="0" {/if}>Select</option>
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
                                    <select id="exit_proj_0" name="exit_proj[]" >
                                       <option value=''>Select Project</option>
                                       
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    </table>
                  </td>
                  </tr>
                    <tr>
                      <td width="10%" align="right" >Value of Exit (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="exitValue2" id="exitValue2"  style="width:250px;" onchange="onChangeValue(this.id);"></td><td width="10%" align="left" id="exit_value1_text"></td><td width="40%" align="left" id="errmsgexitvalue2"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" >Value of Investment (Rs): </td>
                      <td width="40%" align="left" ><input type=text name="exitValue1" id="exitValue1"  style="width:250px;" onchange="onChangeValue(this.id);"></td>
                      <td width="10%" align="left" id="exit_value2_text"></td><td width="40%" align="left" id="errmsgexitvalue1"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">News Article:</td>
                      <td width="30%" align="left" >
                      <input type=text name="exitArticle" id="exitArticle"  style="width:250px;">
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Date of Transaction:</td>
                      <td width="30%" align="left" >
                      <input name="img_date3" type="text" class="formstyle2" id="img_date3" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger3" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                      <td width="20%" align="left" id="errmsgexitdate"></td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Holding Period:</td>
                      <td width="30%" align="left" >
                      <input type=text name="exitPeriod" id="exitPeriod"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                      </tbody>

                    <tbody id='saveDiv' style="display:none" >
                    <tr>
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
					  <input type="hidden" name="deal_id" id="deal_id" />
                      <input type="button" name="lmkSave" id="lmkSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp;                
                      </td>
                    </tr>
                    </tbody>
                    <tr>
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
                      <input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">              
                      </td>
                    </tr>
                    
                  </form>
                  </TABLE> 
                  </div> 




                   <div id="search-bottom">
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0 class="tablesorter">
                        <form name="form1" method="post" action="">
                          <thead>
                                <TR class = "headingrowcolor">
                                  <th  width=2% align="center">S.N.</th>
                                  <th  width=5% align="center">PE Names</th>
                                  <TH  width=5% align="center">Deal Type</TH>
                                  <TH  width=4% align="center">Value</TH>
                                  <TH  width=7% align="center">Transaction Date</TH>
                                  
                                  <TH  width=4% align="center">Article Link</TH> 
                                 <TH width=6% align="center">Builder</TH> 
                                 <TH width=6% align="center">Extra Data</TH>
                                <TH width=3% align="center">Action</TH>
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
                                {foreach from=$pedeals key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i} </TD>
                                  <TD align=center class=td-border>{$v['name']}{if $v['name_2']}, {$v['name_2']}{/if}{if $v['name_3']}, {$v['name_3']}{/if}</TD>
                                  <TD align=center class=td-border>{$v['type']}</TD>
                                  <TD align=center class=td-border>{$v['value']}</TD>
                                  <TD align=center class=td-border>{$v['transaction_date']}</TD>
                                  <TD align=center class=td-border>{$v['article_link']}</TD>
                                  <TD align=center class=td-border>{$v['builder_name']} </TD>
                                  <TD align=center class=td-border>{$v['extra_values']}</TD>
                                  <TD align=center class=td-border>
									  <a href="javascript:void(0);" onclick="return edit_deal('{$v['deal_type_id']}','{$v['id']}','{$v['pe_id']}','{$v['pe_id_2']}','{$v['pe_id_3']}','{$v['value']}','{$v['article_link']}','{$v['transaction_date']}','{$v['builder_id']}', '{$v['deal_projects']}', '{$v['period']}', '{$v['investmentValue']}');">Edit</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0);" onclick="return deletePEDeal('{$v['id']}');">Delete</a></TD>
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
