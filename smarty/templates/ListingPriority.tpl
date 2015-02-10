<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="csss.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script type="text/javascript" src="js/numberToWords.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/js/jss.js"></script> 


<script language="javascript">
var pid;
var bt = [];
var option = [];
var sel = [];


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
function submitButton(){ 
    var cityid = $('#citydd').val();
    var locality_id = $('#locality').val();
    var placeType = $('#placeType').val();
    var statusId = $('#statusId').val();
    window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&locality="+locality_id+"&status="+statusId+"&placeType=placeType";
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

function editListing(str){
    cleanFields();
    console.log(str.jsonDump.tower);
    //var List = $.parseJSON(str);
    //console.log(List);

    $("#listing_id").val(str.id);
    $("#cityddEdit").val(str.property.project.locality.suburb.city.id);
    //$("#bkn2").val(str.seller.id);
    $("#project").val(str.property.project.name);
    $("#proj").val(str.property.project.projectId);
    $("#seller3").val(str.seller.id);
    $("#facing2").val(str.facing);
    
    $("#floor2").val(str.floor);
    $("#tfr2").val(str.transferCharges);
    $("#flt2").val(str.flatNumber);
   

    

   var unit_name = str.property.unitName+"-"+str.property.size+" "+str.property.unitType; 

    $('#bh3').html(''); 
    $('#bh3').append($("<option selected='selected' />").val(str.propertyId).text(unit_name));
    var jsonDump = $.parseJSON(str.jsonDump);
    $("#tower2").val(jsonDump.tower);
 
    




    
    if(str.currentListingPrice.pricePerUnitArea >0){
       $("#prs5").val('2');
       $("#prs3").val(str.currentListingPrice.pricePerUnitArea );
     }
    else{
      $("#prs5").val('1');
      $("#prs3").val(str.currentListingPrice.price);
    }
    $("#othr_prs").val(str.currentListingPrice.otherCharges);

    

   
    
  




     $("#park2").val(str.noOfCarParks);
    $("#bank_list2").val(str.homeLoanBankId);
    $("#plc3").val(str.plc);
    $("#discription3").val(jsonDump.description);
   
    


   
   /* var study_room = "";
    if ($('[name="yes_study"]').is(':checked'))  {
      study_room = "YES";     
    } else {
      study_room = "NO";
    }  
    var servant_room = "";
     if ($('[name="yes_servant"]').is(':checked'))  {
      servant_room = "YES";     
    } else {
      servant_room = "NO";
    } 
    var discription = $("#discription3").val().trim();*/


    window.scrollTo(0, 0);

    if($('#create_Landmark').css('display') == 'none'){ 
     $('#create_Landmark').show('slow'); 
    }
}

jQuery(document).ready(function(){  
  var i;

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

$("#bkn2").change(function () {
    var broker_id = $("#bkn2 :selected").val().trim();   
    console.log(broker_id);
    $.ajax({
            type: "POST",
            url: '/saveSecondaryListings.php',
            data: { broker_id:broker_id, task:'get_seller'},

            success:function(msg){

              console.log(msg);
              $('#seller3').html(''); 
              var options = $("#seller3");
              //var i = 0;


              msg = $.parseJSON(msg);
              $.each(msg, function(k,v) {
                console.log(v);
                  options.append($("<option/>").val(v['user_id']).text(v['name']));
                
              });  
              
            },
          });
});

$("#lmkSave").click(function(){
    var temp = [];
    var cityid = $("#cityddEdit :selected").val().trim();
    var broker_name = $("#bkn2 :selected").text().trim();

    var broker_id = $("#bkn2 :selected").val().trim();
        


    //var projectid = $("#project :selected").text().trim();
    var project_name = $("#project").val().trim();
    var project_id = $("#proj").val().trim();
    var bhk1 = $("#bh3 :selected").text().trim();
    var option_sel = $("#bh3 :selected").val();
    var size = "";
    var bedrooms ="";
    var bathrooms = "";
    var property_id = "";
    var unit_type = "";
    
    if (parseInt(option_sel) < option.length){
      
      $.each(option, function(k,v){
        
        if (k==parseInt(option_sel)){
          console.log("here0");
          console.log(v);
          size = v['size'];
          bedrooms = v['bedrooms'];
          bathrooms = v['bathrooms'];
          property_id = v['propertyId'];
          unit_type = v['unitType'];
        }
      });
      
    }
    else{
      console.log("hereq");
       size = $("#other_input").val().trim();
       bedrooms = $("#bed2").val().trim();
      bathrooms = $("#tol3").val();
       property_id = "";
       unit_type = "";
    }

   

   

    var seller_id = $("#seller3").val().trim();
    //var projectid = $("#project :selected").text().trim();
    var projectid = $("#project").val().trim();
    var projid = $("#proj").val().trim();
    var bhk1 = $("#bh3 :selected").text().trim();    

    var facing = $("#facing2 :selected").text().trim();
    
    var tower = $("#tower2").val().trim();
    var floor = $("#floor2").val().trim();

    var price_type = parseInt($("#prs5 :selected").val());

    var price = "";
    var price_per_unit_area = "";
    if (price_type==1)
      price = $("#prs3").val().trim();
    else
      price_per_unit_area = $("#prs3").val().trim();

    if ($('[name="lkhs1"]').is(':checked'))  {
      price_new = parseFloat(price) * 100000;
      price_per_unit_area = parseFloat(price_per_unit_area) * 100000;      
    } else {
      price_new = parseFloat(price) * 10000000;
      price_per_unit_area = parseFloat(price_per_unit_area) * 10000000;
    }
  

    var transfer_new;
    var trancefer_rate = $("#tfr2").val().trim();
    var price_in = "Lakhs";    
    if ($('[name="lkhs2"]').is(':checked'))  {
      transfer_new = parseFloat(trancefer_rate) * 100000; 
    } else {
      transfer_new = parseFloat(trancefer_rate) * 10000000;
    }

    var appratment = $("#appartment3 :selected").text().trim();
    var flat_number = $("#flt2").val().trim();
    var parking = $("#park2 :selected").val();
    var loan_bank = $("#bank_list2 :selected").val().trim();
    var plc_val = $("#plc3").val().trim();
    var study_room = "";
    if ($('[name="yes_study"]').is(':checked'))  {
      study_room = "YES";     
    } else {
      study_room = "NO";
    }  
    var servant_room = "";
     if ($('[name="yes_servant"]').is(':checked'))  {
      servant_room = "YES";     
    } else {
      servant_room = "NO";
    } 
    var discription = $("#discription3").val().trim();

    /*temp[0] = cityid;
    temp[1] = broker_name;
    temp[2] = project_id;
    temp[3] = projid;
    temp[4] = bhk1;
    temp[5] = facing;
    temp[6] = size;  
    temp[7] = bathroom;
    temp[8] = toilet;
    temp[9] = tower;
    temp[10] = floor;
    temp[11] = price_type;
    temp[12] = price;
    temp[13] = trancefer_rate;
    temp[14] = price_in;
    temp[15] = flat_number;
    temp[16] = parking;
    temp[17] = loan_bank;
    temp[18] = plc_val;
    temp[19] = study_room;
    temp[20] = servant_room;

    var i;
    for(i = 0; i < temp.length; i++)  {
        console.log(i + ' - ' + temp[i]);
    }
    //alert(bt[0]+'-'+bt[1]+'-'+bt[2]+'-'+bt[3]+'-'+bt[4]);  
    console.log("---------------------------------");*/

    $.ajax({
            type: "POST",
            url: '/saveSecondaryListings.php',
            data: { cityid: cityid, broker_name:broker_name, project_id : project_id, property_id:property_id, unit_type:unit_type, bedrooms: bedrooms, facing : facing, size:size, bathrooms:bathrooms, tower:tower, floor : floor , price_type:price_type, price:price, price_per_unit_area:price_per_unit_area, trancefer_rate:trancefer_rate, flat_number:flat_number, parking:parking, loan_bank:loan_bank, plc_val:plc_val, study_room:study_room, servant_room:servant_room},

            success:function(msg){
              //alert(msg);

              console.log(msg);
              
                alert("Saved");

            },
          });



    /*id="size"
    id="errmsgsize"
    id="bhk"
    id="errmsgbhk"
    id="facing"
    id="errmsgfacing"
    id="floor"
    id="errmsgfloor"
    id="lakhs"
    id="crs"
    id="park"
    id = "park1"
    id="tower"
    id="flt_no"
    id="yes"
    id="no"
    id="bankddEdit"
    id="plcy"
    id="plcn"
    id="plc3"
    id="lmkSave"
    id="exit_button" */




    var placeid = "";
    if(!placeid)
      	var placeid = "";
    var lmkid = "";
    var lmkname = "";
    var lmkaddress = "";
    var lmklat = "";
    var lmklong = "";
    var lmkphone = "";
    var lmkweb = "";
    var lmkprio = "";
    var lmkstatus = "";
    var error = 0;
    var mode='';
    if(lmkid) mode = 'update';
    else mode='create';

  });
   
  $.widget( "custom.catcomplete", $.ui.autocomplete, {
    
    _renderItem: function( ul, item ) {
      //alert(item.label);
      var res = item.id.split("-");
          var tableName = res[1];
      return $( "<li>" )
        .append( $( "<a>" ).text( item.label + "........." +tableName ) )
        .appendTo( ul );
    },
  

  });
     
   $( "#project" ).catcomplete({
      source: function( request, response ) {
        
        $.ajax({
          url: "{$url12}"+"?query="+$("#project").val().trim()+"&typeAheadType=(project)&city="+$("#cityddEdit :selected").text().trim()+"&rows=10",
          //url: "{$url12}"+"?query="+$("#proj").val().trim()+$("#cityddEdit :selected").text().trim(),
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full", 
            name_startsWith: request.term
          }, 
           
          success: function( data ) { 
            response( $.map( data.data, function( item ) {              
                return {
                label: item.displayText,
                value: item.label,
                id:item.id,
                }
                 
            }));
          }
        });      
      },
              
      select: function( event, ui ) {
        window.selectedItem = ui.item;
        var res = ui.item.id.split("-");
          var projectId = res[2];
          pid = projectId;
          console.log(projectId);

          $("#projectId").val(projectId); 
          var data = { projectId:projectId,  task:'get_options'}; 
           
          //find_project_options();
          
          console.log("{$url13}"+projectId);
          $.ajax({
              //alert("Hello"); 
              url: "{$url13}"+projectId,
              dataType: "json",
              data: {
                featureClass: "P",
                style: "full", 
                //name_startsWith: request.term
              },
              
              success: function( data ) {
                  

                //var v1 = data.data.properties[0].unitName;
                var v1 = data.data.projectDetails.builder.name;
                var v2 = data.data.projectDetails.projectName;
                var v3 = data.data.locality.newsTag;
                
                //console.log(v1);
                //console.log(v2);
                //console.log(v3);



                var ln = data.data.properties.length;
                //console.log(ln);
                for(i = 0; i < ln; i++)  {
                  bt[i] = data.data.properties[i].unitName+', '+data.data.properties[i].size+' '+data.data.properties[i].measure;
                  option[i] =  data.data.properties[i];
                  //bt[i]['option_id'] = data.data.properties[i].unitName;
                  //console.log(data.data.properties[i]);
                }  
                //console.log(option);
                //console.log(bt);

                $('#proj').html('');
                $(function () {
                  $('.proj2 input').val(projectId);
                }); 

                $('#bh3').html(''); 
                var options = $("#bh3");
                var i = 0;

                $.each(bt, function() {
                    options.append($("<option/>").val(i).text(bt[i]));
                    i++;
                });  
                
                var bbt = [];
                bbt[0] = "Others";
                var j = 0;;
                $.each(bbt, function() {
                    options.append($("<option/>").val('other').text(bbt[j]));
                    i++;
                    j++;
                });   
                                  
 
                
                //console.log(bt);
                //$("#bhk").val(bt);
                /*response( $.map( data.data, function( item ) {              
                  return {
                  label: item.displayText,
                  value: item.label,
                  id:item.id,
                  } 
                      
                }));*/
              }
          });
          //}
      },
      

      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

    }); 


    $( "#proj" ).catcomplete({
      source: function( request, response ) {
        console.log("{$url13}"+$("#proj").val());
        $.ajax({
              //alert("Hello"); 
              url: "{$url13}"+$("#proj").val(),
              dataType: "json",
              data: {
                featureClass: "P",
                style: "full", 
                //name_startsWith: request.term
              },
              
              success: function( data ) {

               
                var ln2 = data.data.properties.length;

                var v12 = data.data.projectDetails.builder.name;
                var v22 = data.data.projectDetails.projectName;
                var v32 = data.data.locality.newsTag;
                
                console.log(v12);
                console.log(v22);
                console.log(v32);

                for(i = 0; i < ln2; i++)  {
                  bt[i] = data.data.properties[i].unitName+', '+data.data.properties[i].size+' '+data.data.properties[i].measure;
                  //console.log(i);
                }  


                $('#project').html('');
                $(function () {
                  $('.project2 input').val(v22+' '+v32);
                }); 

                $('#bh3').html(''); 
                var options = $("#bh3");
                var i = 0;

                $.each(bt, function() {
                    options.append($("<option/>").val(i).text(bt[i]));
                    i++;
                });  
                
                var bbt = [];
                bbt[0] = "Others";
                var j = 0;;
                $.each(bbt, function() {
                    options.append($("<option/>").val('other').text(bbt[j]));
                    i++;
                    j++;
                });   
     
              }
          });
          //}
      },
      

      /*open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },*/

    });  



});














</script>

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
		            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
		            	<TBODY>
		                	<TR>
		                	  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
		                    	<TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
		                    		<TBODY>
		                      			<TR>
		                        			<TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>          Properties
		                        			</TD>
		                      			</TR>
		                    		</TBODY>
		            </TABLE>
		    </TD>
		</TR>
		<TR>
		<TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		    <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
		            <td>
		        	    <div id="search-top">
		                    <table width="70%" border="0" cellpadding="0" cellspacing="0" align="center">
		                        <form method = "post">
		            	            <tr>
		                                <td width="20%" height="25" align="left" valign="top">
		                                    <select id="citydd" name="citydd" onchange = "update_locality(this.value);">
		                                       <option value=''>select city</option>
		                                       {foreach from=$cityArray key=k item=v}
		                                           <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
		                                       {/foreach}
		                                    </select>
		                                </td>
		                                <td width = "10px">&nbsp;
		                                </td>
		                                <td width="20%" height="25" align="left" valign="top">
		                                    <span id = "LocalityList">
		                                    <select id="locality" name="locality" onchange = "localitySelect(this.value);">
		                                       <option value=''>select locality</option>
		                                       {foreach from=$localityArr key=k item=v}
		                                           <option value="{$v->locality_id}" {if $localityId==$v->locality_id}
		                                              selected="selected" {/if}>{$v->label}</option>
		                                       {/foreach}
		                                    </select>
		                                    </span>
		                                </td>
		                          		<input type="hidden" name="localityId" id = "localityId" value="{$localityId}">
		                                
		                                <td width = "10px">&nbsp;
		                                </td>
		                                <td width="15%" height="25" align="left" valign="top">
		                                    <select id="placeType" name="placeType">
		                                       <option value=''>select place type</option>
		                                       {foreach from=$nearPlaceTypesArray key=k item=v}
		                                              <option value="{$v->id}" {if $placeType==$v->id}  selected="selected" {/if}>{$v->name}</option>
		                                       {/foreach}
		                                    </select>
		                                </td>
		                                <td width = "10px">&nbsp;</td>
		                                <td width="15%" height="25" align="left" valign="top">
		                                    <select name="status">
		                                       <option value='Active' {if $status == 'Active'}selected{/if}>Active</option>
		                                       <option value='Inactive' {if $status == 'Inactive'}selected{/if}>Inactive</option>
		                                    </select>
		                                </td>
		                                <td width = "10px">&nbsp;</td>
		                                <td width="20%" height="25" align="left" valign="top">
		                                    <input type = "submit" name = "submit" value = "submit" onclick="return submitButton();">
		                                </td>
		                          </tr>
		                        </form>
		                    </table>
		                </div> 
		            </td>
                </tr>
            </table>  



    <!--  --------------------------------------------------------------------------------------------------------  -->

                <div align="left" style="margin-bottom:5px;">
                    <button type="button" id="create_button" align="left">
                   		Create New Listing
                    </button> 
                </div> 
                
                <div id='create_Landmark' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  	<form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    	<input type="hidden" name="old_sub_name" value="">
                    	<div>
<!--City Tr-->         		<tr id="city">
                      			<td id="city1">
                      				*City
                      			</td>
                            <td>
                                <select id="cityddEdit" name="cityddEdit" >
                                    <option value=''>select city</option>
                                	  {foreach from=$cityArray key=k item=v}
                                    <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                    {/foreach}
                                </select>
                            </td>
                    		  </tr>


                        <tr id="bkn">
                            <td id = "bkn1">
                                *Broker Name
                            </td>
                            <td id = "bkn3">
                                <select id="bkn2" name="bkn2" onchange = "update_locality(this.value);">
                                      <option value=''>select name</option>
                                      {foreach from=$brokerArray key=k item=v} 
                                          <option value="{$v['id']}">{$v['name']}</option>
                                      {/foreach}
                                </select>
                                <input type='hidden' id='listing_id'>
                            </td>
                            <td width="100px;">

                            </td>
                            

                            <td id="seller1">
                                Seller Name:
                            </td>
                            <td id="seller2">
                              <!-- <input type=text name="seller3" id="seller3"> --> 
                              <select id="seller3" name="seller3" >
                                    <option value=''>Seller ID</option>
                                    
                                </select>      
                            </td>
                        </tr>

                    		<tr id="prj">
                      			<div class="ui-widget">
                        			<td id="project1">
                          			<font color = "red">
                              			*
                          			</font>
                          			Project
                        			</td>
                        			<td class="project2">
                            			<input type=text name="project" id="project"  style="width:210px;">
                        			</td>
                        			<td  style="text-align: center;" width="100px;">
                                  OR
                            	</td>
                              <td id="proj1">
                                Project ID:
                              </td>
                              <td class="proj2">
                                <input type=text name="proj" id="proj">       
                              </td>
                      			</div>         		
                    		</tr>
       
                    		<tr id="bhk">
                            <td id = "bh1">
                                *BHK
                            </td>
                            <td id="bh2">
                                <select id="bh3" name="bh3">
                                    <option value=''> BHK </option>    
                                    <script language="javascript" type="text/javascript"> 
                                    for(var d=0;d< bt.length;d++)  {
                                        document.write("<option value='"+option[d].propertyId+"' >"+bt[d]+"</option>");
                                    }
                                    </script>
                                </select>
                            </td>
                            <td id = "facing1">
                                *Facing
                            </td>
                            <td>
                                <select id="facing2" name="facing2">
                                    <option value=''>Facing</option>  
                                      <option value="East">East</option>
                                      <option value="West">West</option>
                                      <option value="North">North</option>
                                      <option value="South">South</option>
                                      <option value="North East">North East</option>
                                      <option value="South East">South East</option>
                                      <option value="North West">North West</option>
                                      <option value="South West">South West</option>
                                </select>
                            </td>
                        </tr>
     
                        <tr id = "othr" >
                            <td id="othr1" padding-left: 100px;>
                                  *Size
                            </td>
                            <td id="othr2">
                                  <input type=text name="other_input" id="other_input"> 
                            </td>
                            <td id="bath">
                                  *Bathroom
                            </td>
                            <td id="bath1">
                                  <input type=text name="bed2" id="bed2" style="width:60px">  
                            </td>
                            <td id="tol1">
                                  *Toilet
                            </td>
                            <td id="tol2">
                                  <input type=text name="tol3" id="tol3">
                            </td>
                            <td id="appartment1">
                                  *Apparthment
                            </td>
                            <td id="appartment2">
                                  <select name="appartment3" id="appartment3" style="height:28px">
                                    <option value=''>Apartment</option>  
                                      <option value="1">Villa</option>
                                      <option value="2">Plot</option>
                                      <option value="3">Commercial</option>
                                      <option value="4">Shop</option>
                                      <option value="5">Office</option>
                                      <option value="6">Other</option>
                                </select>
                            </td>
                        </tr>
  

                    
                        <tr id="tower_floor"> 
                            <td id="tower1">
                              *Tower
                            </td>
                            <td >
                                <input type=text name="tower2" id="tower2" style="width:100px">
                            </td>
                            <td  align="left" id="errmsgtower">
                                
                            </td>

                      			<td id="floor1">
                          			*Floor
                      			</td>
                      			<td>
                          			<input type=text name="floor2" id="floor2" style="width:100px">
                      			</td>
                      			<td  align="left" id="errmsgfloor">
                      				
                      			</td>
                      
                    		</tr>

               				  <tr id="prs_trf">

                          			<td id="prs1">
                              			*Price: 
                          			</td>
                              
                                                             
                                
                                <td id="prs4">
                                  <select id="prs5" name="prs5" style="width:100px">
                                      <option value='0'>Select</option>  
                                      <option value="1">All Inclusive</option>
                                      <option value="2">Per Sq. Ft.</option>
                                  </select>
                                </td>

                                <td id="prs2">
                                    <input type=text name="prs3" id="prs3" style="width:100px">
                                </td> 

                                <td id ="tfr1" >
                                  Transfer Rate:
                                </td>
                                <td >
                                    <input type=text name="tfr2" id="tfr2" style="width:100px">
                                </td>

                    		</tr> 

                        <tr id="prs_typ">
                            
                            <td width="110px" align="left" id="pr" style="padding-left:120px" >
                              <label  for="one" style="font-size:11px;" >
                                lkhs &nbsp;   
                                 <input type="radio" id="lkhs1" name="lkhs1" value="y" checked="checked" /> 
                                 &nbsp;&nbsp; crs &nbsp;
                                 <input type="radio" id="crs1" name="crs1" value="n" />
                              </label>    
                            </td>

                            <td width="400px" style="margin-left=-20px">
                                <input type=text name="flt2" id="flt2" style="width:100px">
                            </td>
                        
                            <td width="630px" align="left" id="tr" >
                              <label  for="one" style="font-size:11px;">
                                lkhs &nbsp;   
                                  <input type="radio" id="lkhs2" name="prstp2" value="y" checked="checked" /> 
                                  &nbsp;&nbsp; crs &nbsp;
                                  <input type="radio" id="crs2" name="prstp2" value="n" />
                              </label>    
                            </td>  

                        </tr>

		                    <tr id = "flat_park">
                            <td id="flt1">
                                Flat Number
                            </td>
                            <td>
                               <input type=text name="flt2" id="flt2" style="width:100px">
                            </td>
                            <td align="left" id="errmsgflt_no">
                                
                            </td>

		                      	<td id="park1">
		                      	   	*Car Parks
		                      	</td>
		                        <td>
		                            <select id="park2" name="park2" style="width:100px">
		                                <option value=''>Select</option>                                         
		                                <option value="0">0</option>
		                                <option value="1">1</option>
		                                <option value="2">2</option>
		                                <option value="3">3</option>
		                                <option value="4">4</option>
		                                <option value="5">5</option>
		                            </select>    
		                        </td>
		                      	<td align="left" id="errmsgpark">
		                      			
		                      	</td>
                        </tr>
                       


                    	<tr id="hln" height="40px;">
                       
                        	<td id="hln1">
                                *Home Loan
                        	</td>

                        	<td  id="hln2" >
                          		<label for="one" style="font-size:11px;">
                            		&nbsp; Yes   
                              		<input type="radio" id="yes" name="yes" value="1" /> 
                            		&nbsp; No &nbsp;
                              		<input type="radio" id="no" name="no" value="2" checked="checked" />
                          		</label>
                        	</td>
                       
                        	<td id="bank_list1" height="40px;">
                            	<select name="bank_list2" id="bank_list2" height="5px" >
                                 	<option value=''> select bank	</option>
                                    {foreach from=$bankArray key=k item=v}
                                        <option value="{$k}" {if $bankId==$k}  selected="selected" {/if}>{$v}</option>
                                    {/foreach}
                            	</select>
                        	</td> 
                       
	                        <td id = "plc1">
	                            PLC
	                        </td>
	                                            
	                        <td id="plc2" >
	                          <label  for="one" style="font-size:11px;" style="text-align: top;">
	                            Yes &nbsp;   
	                              <input type="radio" id="plcy" name="plcy" value="1" /> 
                                &nbsp;&nbsp;&nbsp;&nbsp; No &nbsp;
	                              <input type="radio" id="plcn" name="plcn" value="2" checked="checked" />
	                          </label>
	                        </td> 
	                         
	                        <td>
	                              <input type=text name="plc3" id="plc3" width="20px" style="text-align: left;">
	                        </td>
                      </tr>    

                      <tr id="study_servant">
                          <td id = "study1">
                              Study Room
                          </td>
                                              
                          <td width="200px" align="left" id="study" >
                            <label  for="one" style="font-size:11px;">
                              Yes &nbsp;   
                                <input type="radio" id="yes_study" name="yes_study" value="1" /> 
                                &nbsp;&nbsp; No &nbsp;
                                <input type="radio" id="no_study" name="no_study" value="2" checked="checked" />
                            </label>
                          </td>

                          <td>

                          </td>

                          <td id = "servant1">
                              Servant Room
                          </td>
                                              
                          <td width="200px" align="left" id="servant" >
                            <label  for="one" style="font-size:11px;">
                              Yes &nbsp;   
                                <input type="radio" id="yes_servant" name="yes_servant" value="1" /> 
                                &nbsp;&nbsp; No &nbsp;
                                <input type="radio" id="no_servant" name="no_servant" value="2" checked="checked" />
                            </label>
                          </td>  
                      </tr>

                      <tr id="discription1">
                        <td id = "discription4">
                            Description
                        </td>
                        <td id = "discription2">
                              <input type=text name="discription3" id="discription3"  />
                        </td>
                      </tr>



          </form>          
                
            			<tr>
                      <td width="400px"> </td>

                    		<td align="left" style="padding-top:900px;" >
                       			<input type="button" name="lmkSave" id="lmkSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp;     
                       			<input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">                 
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
                                  <th  width=5% align="center">City</th>
                                  <TH  width=8% align="center">Broker Name</TH>
                                  <TH  width=4% align="center">Project</TH>
                                  <TH  width=8% align="center">Listing</TH>
                                  
                                  <TH  width=4% align="center">Price
                                
                                  </TH> 
                                
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
                                {foreach from=$resaleListings key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                  <TR {$color}>
                                    <TD align=center class=td-border>{$i} </TD>
                                    <TD align=center class=td-border>{$v['val']->property->project->locality->suburb->city->label}</TD>
                                    <TD align=center class=td-border>{$v['val']->seller->fullName}</TD>
                                    <TD align=center class=td-border>{$v['val']->property->project->name}, {$v['val']->property->project->builder->name}</TD>
                                    <TD align=center class=td-border>{$v['val']->property->unitName}-{$v['val']->property->size}-{$v->val->property->unitType}
                                    <!--<a href="http://www.textfixer.com" onclick="javascript:void window.open('http://www.textfixer.com','1390911428816','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">Pop-up Window</a>-->

                                    </TD>
                                    {if $v['val']->currentListingPrice->pricePerUnitArea != 0}
                                    <TD align=center class=td-border>{$v['val']->currentListingPrice->pricePerUnitArea}</TD>
                                    {else}
                                    <TD align=center class=td-border>{$v['val']->currentListingPrice->price}</TD>
                                    {/if} 
                                    <TD align=center class=td-border><button type="button" id="edit_button_{$v->id}" onclick="return editListing({$v['json']})" align="left">Edit</button></TD>
                                
                                  
                                  
                                    
                                    
                                    
                                
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
