<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/bootstrap-tagsinput/bootstrap-tagsinput.css">

<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<?php



?>
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>{if $cityid == ''} Add New {else} Edit {/if} City</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		{if $accessCity == ''}

			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data" id="frmcity" name="frmcity">
			      <div>
				<tr>
				  <td width="20%" align="right" >*City Name : </td>
				  <td width="30%" align="left"><input type=text name=txtCityName id=txtCityName value="{$txtCityName}" style="width:250px;"></td> {if $ErrorMsg["txtCityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCityName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
								<tr>
				  <td width="20%" align="right" >*City URL : </td>
				  <td width="30%" align="left" ><input type=text disabled name=txtCityUrl id=txtCityUrl value="{$txtCityUrl}" style="width:250px;"></td>				   
					<input type = "hidden" name = "txtCityUrlOld" value = "{$txtCityUrlOld}">
				 
				  	<td width="50%" align="left" >
				  		<font color = "red">

				  			 {if $ErrorMsg["txtCityUrl"] != ''} 
				  			 	{$ErrorMsg["txtCityUrl"]}
				  			 {/if}

				  			 {if $ErrorMsg["CtUrl"] != ''} 

				  				{$ErrorMsg["CtUrl"]}
				  			 {/if}

				  			</font>
				  </td>
				  


				</tr>	
				<tr>
				  <td width="20%" align="right">*Display Order : </td>
				  <td width="30%" align="left" >				  				  				 						
				  
				  <select name="DisplayOrder"  id="DisplayOrder" class="field" style="width:150px;">						 						 	
					 <option value="">Select </option>   							
					{section name=foo start=1 loop=51 step=1}
						<option {if $DisplayOrder == {$smarty.section.foo.index}} value="{$DisplayOrder}" selected = 'selected' {else} value="{$smarty.section.foo.index}"{/if} >{$smarty.section.foo.index}</option>  							
					 {/section}  							 	
 					</select>
				 	 </td>{if $ErrorMsg["DisplayOrder"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["DisplayOrder"]}</font></td>{else} <td width="50%" align="left" id="errmsgdispord"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" >* Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:250px;"></td>				   {if $ErrorMsg["txtMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaTitle"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetatitle"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top">*Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="35" id="txtMetaKeywords" style="width:250px;">{$txtMetaKeywords}</textarea>
                  </td>{if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetakey"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top">*Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="35" id="txtMetaDescription" style="width:250px;">{$txtMetaDescription}</textarea>
                  </td>{if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetades"></td>{/if}
				</tr>									
								
				<tr>
				  <td width="20%" align="right" valign = top >*Description  : </td>
				  <td width="30%" align="left" ><textarea name = 'desc' id = 'desc' cols = "35" rows = "10" style="width:250px;">{$desc}</textarea></td>	{if $ErrorMsg["desc"] != ''} <td valign = 'top' width="50%" align="left" ><font color = "red">{$ErrorMsg["desc"]}</font></td>{else} <td width="50%" align="left" id="errmsgdes"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Status  : </td>
				  <td width="30%" align="left" >
				    <select name = "status" id="status" style="width:150px;"> 
					  <option {if $status == 'Inactive'} value = "Inactive" selected = 'selected' {else} value = "Inactive" {/if}>Inactive</option>
					  <option {if $status == 'Active'} value = "Active" selected = 'selected' {else} value = "Active" {/if}>Active</option>		
					 </select>
				 </td>				   
				 <td width="50%" align="left"></td>
				</tr>
				<tr>
					<td width="20%" align="right">Aliases  : </td>
					<td width="30%" align="left" id='aliases' data-role="tagsinput"></td><td><label id="removetext1" style="color:red; font-weight: bold;"></label></td>
				</tr>
				<tr>
					<!--<td width="20%" align="right" style="vertical-align: top;">Add New Aliases  : </td>-->
					<div class="ui-widget"><td width="20%" align="right"><label for="search">Search: </label></td>
					<td width="30%" align="left"><input id="search"></td></div>
					<td width="10%" align="left"><button type="button" id="button" align="left">Save Alias</button></td><td><label align="left" id="onclicktext" style="color:red; font-weight: bold;"></label> </td>
					
					
 


					<!--<td width="30%" align="left">
						<div style="position: relative; height: 80px; width: auto" >
                   			<input type="text" name="query" id="query"/>
                  		 <div id="contain" style="width: auto"></div>
                  		 </div>
                    </td>-->
                <!--</td>-->
				</tr>


				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >
				  <input type="hidden" name="catid" value="<?php echo $catid ?>" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style="cursor:pointer">
				  </td>
				</tr>
			      </div>
			    </form>
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
                {else}
                    <font color="red">No Access</font>
                {/if}                         
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>



<script type="text/javascript">



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

if({$landmarkJson}!=''){
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
/*
if({$genericJson}!=''){
var b = {$genericJson};
var genericAliases = [];
for(var i in b){
    genericAliases.push([i, b [i]]);
}
for (index = 0; index < genericAliases.length; ++index) {
    var elm = genericAliases[index];
    //alert(elm[1].name);
    $('#aliases').tagsinput('add', { "value": elm[1].id , "text": elm[1].name, "type": "generic"    }); 
}
}

if({$suburbJson}!=''){
var c = {$suburbJson};
var suburbAliases = [];
for(var i in c){
    suburbAliases.push([i, c[i]]);
}
for (index = 0; index < suburbAliases.length; ++index) {
	 var elm = suburbAliases[index];
    $('#aliases').tagsinput('add', { "value": elm[1].SUBURB_ID , "text": elm[1].LABEL, "type": "suburb"    }); 
}
}
*/
$("#aliases").on('itemRemoved', function(e) {
    //alert(e.item.text);
    var tableName = 'city';
    var tableId = {$cityid};
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
            name_startsWith: request.term
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
   		else if($('#search').val() ==''){
   			$("#onclicktext").text("Empty Alias field.");
    		
        }
        else if($('#search').val() !== selectedItem.label){
        	$("#onclicktext").text("Alias filed and Alias selected should be same.");
        	
        }
        else if($('#search').val() == selectedItem.label && $('#search').val()!=''){
        	
        	var tableName = 'city';
        	var tableId = {$cityid};
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

//var genericAliases = $.parseJSON(b);
//var a =genericAliases.toString();
//alert(a);

//landmarkAliases = <?php echo json_encode($getLandmarkAliasesArr); ?>;
//suburbAliases = <?php echo json_encode($getSuburbAliasesArr); ?>;
//alert(genericAliases['name']);



/*genericAliases.forEach(function(element, index, array){
	alert(element);
	//obj = JSON.parse(element);
	//alert(obj.name);
	$('#aliases').tagsinput('add', { "value": 1 , "text": "obj.name"    , "type": "generic"    });
});*/

//$('#aliases').tagsinput('add', { "value": 1 , "text": "obj.name"    , "type": "generic"    }); 	
//$('#aliases').tagsinput('add', { "value": 4 , "text": 'landmarkAliases'   , "type": "landmark"   });
//$('#aliases').tagsinput('add', { "value": 7 , "text": 'suburbAliases'       , "type": "suburb" });






	jQuery("#btnSave").click(function(){
	
		var cityname = jQuery("#txtCityName").val();
		var CityUrl = jQuery("#txtCityUrl").val();
		var DisplayOrder = jQuery("#DisplayOrder").val();
		var txtMetaTitle = jQuery("#txtMetaTitle").val();
		var MetaKeywords = jQuery("#txtMetaKeywords").val();
		var MetaDescription = jQuery("#txtMetaDescription").val();
		var status = jQuery("#status").val();
		var desc = jQuery("#desc").val();
		
		if(cityname==''){
		
			jQuery('#errmsgname').html('<font color="red">Please enter City name</font>');
			jQuery("#txtCityName").focus();
			return false;
		}else{
			jQuery('#errmsgname').html('');
		}
		
		if(DisplayOrder==''){
		
			jQuery('#errmsgdispord').html('<font color="red">Please select display order</font>');
			jQuery("#DisplayOrder").focus();
			return false;
		}else{
			jQuery('#errmsgdispord').html('');
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
			
		if(desc==''){
		
			jQuery('#errmsgdes').html('<font color="red">Please enter the description</font>');
			jQuery("#desc").focus();
			return false;
		}else{
			jQuery('#errmsgdes').html('');
		}

	});

});  

</script>
