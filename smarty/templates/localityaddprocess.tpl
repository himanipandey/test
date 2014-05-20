<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/bootstrap-tagsinput/bootstrap-tagsinput.css">
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        //mode : "textareas",
        mode : "specific_textareas",
        editor_selector : "myTextEditor",
        theme : "advanced"
    });
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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Edit Locality</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                {if $accessLocality == ''}
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data" id="frmcity" name="frmcity">
			      <div>
				<tr>
				  <td width="20%" align="right" ><b>*Locality Name :</b> </td>
				  <td width="30%" align="left"><input type=text name=txtCityName id=txtCityName value="{$txtCityName}" style="width:250px;"></td> {if $ErrorMsg["txtCityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCityName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
                                <tr>
				  <td width="20%" align="right" ><b>Locality URL :</b> </td>
                                  <td width="30%" align="left"><input type=text name=locUrl id=locUrl value="{$locUrl}" readonly="" style="width:250px;"></td> {if $ErrorMsg["txtCityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCityName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr><input type = "hidden" name = "old_loc_url" value = "{$old_loc_url}">
				<tr>
                                    <td width="20%" align="right" ><b>* Meta Title :</b> </td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:250px;"></td>				   {if $ErrorMsg["txtMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaTitle"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetatitle"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top"><b>*Meta Keywords :</b></td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="35" id="txtMetaKeywords" style="width:250px;">{$txtMetaKeywords}</textarea>
                  </td>{if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetakey"></td>{/if}
				</tr>				<tr>
                                    <td width="20%" align="right" valign="top"><b>*Meta Description :</b></td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="35" id="txtMetaDescription" style="width:250px;">{$txtMetaDescription}</textarea>
                  </td>{if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetades"></td>{/if}
				</tr>									
				<tr>
                                    <td width="20%" align="right" valign = top ><b>Description  :</b> </td>
				  <td width="30%" align="left" ><textarea name = 'desc' id = 'desc' class ="myTextEditor" cols = "35" rows = "10" style="width:250px;">{$desc}</textarea>
				   {if $desc != ''}
                                      <input type="hidden" name="oldDesc" value="yes" />
                                  {else}
                                      <input type="hidden" name="oldDesc" value="no" />
                                  {/if}
				  {if ($dept=='ADMINISTRATOR' && isset($contentFlag)) || ($dept=='CONTENT' && isset($contentFlag))}
                   <br/><br/>
                   <input type="checkbox" name="content_flag" {if $contentFlag}checked{/if}/> Reviewed?
				  {/if}
				  </td>
				</tr>
                                <tr>
                                    <td width="20%" align="right"><b>*Locality Latitude :</b> </td>
                                    <td width="30%" align="left"><input type="text" name="txtLocalityLattitude" id="txtLocalityLattitude" value="{$txtLocalityLattitude}" style="width:250px;" /></td>
                                    <td width="50%" align="left">
                                      {if $ErrorMsg['txtLattitude']}<font color="red">{$ErrorMsg['txtLattitude']}</span></font>{/if}
                                    </td>
                             </tr>
                             <tr>
                                    <td width="20%" align="right"><b>*Locality Longitude :</b> </td>
                                    <td width="30%" align="left"><input type="text" name="txtLocalityLongitude" id="txtLocalityLongitude" value="{$txtLocalityLongitude}" style="width:250px;" /></td>
                                    <td width="50%" align="left">
                                            {if $ErrorMsg['txtLongitude']}<font color="red">{$ErrorMsg['txtLongitude']}</span></font>{/if}
                                    </td>
                             </tr>

        <tr>
          <td  height="25" align="right" style="padding-left:5px;">
              <b>*Parent Suburb:</b>
                            </td>
                           <td height="50%" align="left">
                            <div id="mainsubcity">
                             
                            <select name="parentId" id = "parentSelect" class="suburbId" STYLE="width: auto" onchange= "return changeParent();">
                            
                            {foreach from=$suburbSelect key=k item=v}
                                <option value="{$v.id}" {if $v.id==$parent_sub_id} selected = "selected" {/if}>{$v.label}</option>
                            {/foreach}
                            </select> 
                            
                            </div>
                            </td>
                            {if $ErrorMsg["txtMetaParent"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaParent"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetaparent"></td>{/if}
                            <td height="25" align="left">
                            <div id="mainsubcity_txtbox">
                               <input type="hidden" name="parent_id" id="parent_id" value="{$parent_id}">     
                            </div>
         </tr>

				<tr>
                                    <td width="20%" align="right"><b>Landmarks Attached:</b> </td>

          <td width="100" align="left" >
            <div id='aliases' data-role="tagsinput"></div>
            <div><label id="removetext1" style="color:green; font-weight: bold;"></label><a href="#" onclick="showHier();"><b>See Hierarchy</b></a></div>
          </td>
         
        </tr>
				<tr>
					<!--<td width="20%" align="right" style="vertical-align: top;">Add New Aliases  : </td>-->
                                <div class="ui-widget"><td width="20%" align="right"><label for="search"><b>Search Landmarks:</b> </label></td>
                                    <td width="30%" align="left"><input id="search"><button type="button" id="button" align="left"><b>Save Landmark</b></button> <label align="left" id="onclicktext" style="color:green; font-weight: bold;"></label></td></div>
					          
					
				</tr>
				
				<tr>
                                    <td width="20%" align="right"><b>*Status  :</b> </td>
				  <td width="30%" align="left" >
				    <select name = "status" id="status" style="width:150px;"> 
					  <option  value = "Active" {if $status == 'Active'}selected{/if}>Active</option>
                                          <option  value = "Inactive" {if $status == 'Inactive'}selected{/if}>Inactive</option>		
					 </select>
				 </td>				   
				 <td width="50%" align="left"></td>
				</tr>
                                         
				<!--<tr>
				  <td width="20%" align="right">Visible In CMS :</td>
				  <td width="30%" align="left" >
                                  {if $specialAccess == 1}
				    <select name = "visibleInCms" id="visibleInCms" style="width:150px;"> 
                                        <option value = "0" {if $visibleInCms == '0'}  selected {/if}>Not Visible</option>
                                        <option value = "1" {if $visibleInCms == '1'}  selected {/if}>Visible</option>		
                                    </select>
                                  {else}
                                      <input type="hidden" name = "visibleInCms" value="{$visibleInCms}">
                                      {if $visibleInCms == '0'}  Not Visible {/if}
                                      {if $visibleInCms == '1'}  Visible {/if}
                                  {/if}
				 </td>				   
				 <td width="50%" align="left"></td>
				</tr>-->
                                {if $localityCleanedAccess == 1}
                                <tr>
                                    <td width="20%" align="right"><b>Locality Cleaned  :</b> </td>
				  <td width="30%" align="left" >
                                      <input type = "button" name ="localityCleaned" onclick = "cleanedLocality({$localityid});" value="Click To Save">
				 </td>				   
				 <td width="50%" align="left">&nbsp;</td>
				</tr>
                                   
                                <tr class="latLong">
                                    <td width="20%" align="right"><b>Max Latitude  :</b> </td>
                                  <td width="30%" align="left" >
                                      {$maxLatitude}
                                      <input type = "hidden" name ="maxLatitude" value="{$maxLatitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>

                                <tr class="latLong">
                                    <td width="20%" align="right"><b>Min Latitude  :</b> </td>
                                  <td width="30%" align="left" >
                                      {$minLatitude}
                                      <input type = "hidden" name ="minLatitude" value="{$minLatitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>

                                <tr class="latLong">
                                    <td width="20%" align="right"><b>Max Longitude  :</b> </td>
                                  <td width="30%" align="left" >
                                      {$maxLongitude}
                                      <input type = "hidden" name ="maxLongitude" value="{$maxLongitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>

                                 <tr class="latLong">
                                     <td width="20%" align="right"><b>Min Longitude  :</b> </td>
                                  <td width="30%" align="left" >
                                      {$minLongitude}
                                      <input type = "hidden" name ="minLongitude" value="{$minLongitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>
                                {/if}
                                
                                <tr class="save_row">
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style="cursor:pointer">
				  </td>
				</tr>
			    </form>
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
	      </TD>
              {else}
                    <font color="red">No Access</font>
                {/if}
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<script type="text/javascript">

function showHier(){
  
  $.fancybox({
        'width'                :800,
        'height'               :800,
        'scrolling'            : 'no',
        'href'                 : "/showHierarchy.php?cityid={$cityid}&subid={$sub_id}&label={$sub_label}&pid={$sub_pid}",
        'type'                : 'iframe',
        
    })
}

jQuery(document).ready(function(){

$('#aliases').tagsinput({

	tagClass: function(item) {
    	switch (item.type) {
     		 case 'generic'   : return 'label label-info';
     		 case 'landmark'  : return 'label label-important';
     		 case 'suburb': return 'label label-success';
        }
  	},
  	itemValue: 'value',
  	itemText: 'text',
  	
  	
});

if(!jQuery.isEmptyObject({$landmarkJson})){
var a= {$landmarkJson};
var landmarkAliases = [];
for(var i in a){
    landmarkAliases.push([i, a [i]]);
}
for (index = 0; index < landmarkAliases.length; ++index) {
	var elm = landmarkAliases[index];
    $('#aliases').tagsinput('add', { "value": elm[1].id , "text": elm[1].name, "type": "landmark"    }); 
}
}

$("#aliases").on('itemRemoved', function(e) {
    //alert(e.item.text);
    var tableName = 'locality';
    var tableId = {$localityid};
    var aliasTableName ='';
    
    var aliasTableId = e.item.value;
    $.ajax({
            type: "POST",
            url: '/saveAliases.php',
            data: { tableName : tableName, tableId : tableId, aliasTableId : aliasTableId, task : 'dettachAlias' },
            success:function(msg){
            	//alert(msg);
               if(msg == 1){
               	//alert(msg);
               	$("#removetext1").text("Alias Successfully Removed.");
              	 
               }
               if(msg == 2){
               	$("#removetext1").text("Alias Already Removed.");
                   
                   //location.reload(true); 
               }
               if(msg == 3){
               	$("#removetext1").text("Error in Removing Alias.");
                   
               }
               if(msg == 4){
               	$("#removetext1").text("No Alias Selected.");
                   
               }
            },
        	});
});


var options, d, selectedItem;





 $.widget( "custom.catcomplete", $.ui.autocomplete, {
    _renderMenu: function( ul, items ) {
      var that = this,
        currentCategory = "";
      $.each( items, function( index, item ) {
        if ( item.table != currentCategory ) {
          ul.append( "<li class='ui-autocomplete-category'><strong>" + item.table + "</strong></li>" );
          currentCategory = item.table;
        }
        that._renderItemData( ul, item );
      });
    }
  });
 
  

  	


   $(function() {
    function selectedValue( message ) {
      alert("selected");
      //$( "<div>" ).text( message ).prependTo( "#log" );
      //$( "#log" ).scrollTop( 0 );
    };
 
    $( "#search" ).catcomplete({
      source: function( request, response ) {
        $.ajax({
          url: "/findSpecificAliases.php",
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 10,
            name_startsWith: request.term,
            cityId: {$cityid}
          },
          success: function( data ) {
          	
            response( $.map( data, function( item ) {
              return {
                label: item.name,
                value: item.name,
                table: item.table,
                id: item.id,
              }
            }));
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {
      	selectedItem = ui.item;
      	//alert(selectedItem.label);
        //log( ui.item ?
         // "Selected: " + ui.item.label :
          //"Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
 


  
  



    $("#button").click(function(){
    	if(jQuery.isEmptyObject(selectedItem)){
    		//$("#onclicktext").style.display='block';
        	$("#onclicktext").text("NO Alias selected");
    		//return false;
        }
   		else if($('#search').val().trim() ==''){
   			$("#onclicktext").text("Empty Alias field.");
    		
        }
        else if($('#search').val() !== selectedItem.label){
        	$("#onclicktext").text("Alias filed and Alias selected should be same.");
        	
        }
        else if($('#search').val() == selectedItem.label && $('#search').val()!=''){
        	
        	var tableName = 'locality';
        	var tableId = {$localityid};
        	//var aliasTableName = selectedItem.table;
        	var aliasTableId = selectedItem.id;
        	//alert("item :"+selectedItem.label);
        	$.ajax({
            type: "POST",
            url: '/saveAliases.php',
            data: { tableName : tableName, tableId : tableId, aliasTableId : aliasTableId, task : 'attachAlias' },
            success:function(msg){
            	//alert(msg);
               if(msg == 1){
               	//alert("saved");
               	$("#onclicktext").text("Alias Successfully Added.");
              	$('#aliases').tagsinput('add', { "value": aliasTableId , "text": selectedItem.label , "type": "landmark"    });
                   //location.reload(true); 
               }
               if(msg == 2){
               	$("#onclicktext").text("Alias Already Added.");
                   
                   //location.reload(true); 
               }
               if(msg == 3){
               	$("#onclicktext").text("Error in Adding Alias.");
                   
               }
               if(msg == 4){
               	$("#onclicktext").text("No Alias Selected.");
                   
               }
            },
        	});
        }

        
        else
        	alert("Wrong Entry");
    
	});
});











	jQuery("#btnSave").click(function(){
	
		var cityname = jQuery("#txtCityName").val();
		var CityUrl = jQuery("#txtCityUrl").val();
		var txtMetaTitle = jQuery("#txtMetaTitle").val();
		var MetaKeywords = jQuery("#txtMetaKeywords").val();
		var MetaDescription = jQuery("#txtMetaDescription").val();
		var status = jQuery("#status").val();
		var desc = jQuery("#desc").val();
		
		if(cityname==''){
		
			jQuery('#errmsgname').html('<font color="red">Please enter Suburb name</font>');
			jQuery("#txtCityName").focus();
			return false;
		}else		{
			jQuery('#errmsgname').html('');
		}
		
		if(CityUrl==''){
		
			jQuery('#errmsgurl').html('<font color="red">Please enter Suburb url</font>');
			jQuery("#txtCityUrl").focus();
			return false;
		}else{
			jQuery('#errmsgurl').html('');
		
		}
		
		if(txtMetaTitle==''){
		
			jQuery('#errmsgmetatitle').html('<font color="red">Please enter meta title</font>');
			jQuery("#txtMetaTitle").focus();
			return false;
		}else{
			jQuery('#errmsgmetatitle').html('');
		}
		
		if(MetaKeywords==''){
		
			jQuery('#errmsgmetakey').html('<font color="red">Please enter meta keywords</font>');
			jQuery("#txtMetaKeywords").focus();
			return false;
		}else{
		
			jQuery('#errmsgmetakey').html('');
		}
		
		if(MetaDescription==''){
		
			jQuery('#errmsgmetades').html('<font color="red">Please enter meta description</font>');
			jQuery("#txtMetaDescription").focus();
			return false;
		}else{
			jQuery('#errmsgmetades').html('');
		}

		
	});

});


function cleanedLocality(localityId) {
$.ajax({
         type: "POST",
         url: 'ajax/cleanedLocality.php',
         data: { localityId:localityId },
         success:function(msg){
           if(msg){
             if(msg.length > 823) {
                alert("New locality boundaries have been saved");
             }
             else{
                alert("No valid record in project table");
             }
              $(".latLong").remove();
              $(msg).insertBefore($('.save_row'));
            }
         }
     })
}

</script>
