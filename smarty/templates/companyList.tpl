<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>

<script language="javascript">

jQuery(document).ready(function(){ 
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


	});


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
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
    {if $priorityMgmtPermissionAccess == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Company Management</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  

                  <div align="left" style="margin-bottom:5px;">
                  <button type="button" id="create_button" align="left">Create New Company</button>
                </div>
                  <div id='create_Landmark' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <input type="hidden" name="old_sub_name" value="">
                    <div>
                    
                    <tr>
                      <td width="10%" align="right" >*Company Type: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="companyTypeEdit" name="companyEdit" >
                                       <option value=''>select place type</option>
                                       {foreach from=$comptype key=k item=v}
                                              <option value="{$v}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgplacetype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" >*Name : </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="placeTypeHidden"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Description :</td>
                      <td width="30%" align="left" >
                      <input type=text name="des" id="des"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">*Address :</td>
                      <td width="30%" align="left" >
                      <textarea name="address" rows="10" cols="35" id="address" style="width:250px;"></textarea></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">City :</td>
                      <td width="30%" align="left" >
                      <input type=text name="city" id="city"  style="width:250px;"><td width="20%" align="left" id="errmsgaddress"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Pincode : </td>
                      <td width="30%" align="left"><input type=text name="pincode" id="pincode"  style="width:250px;"></td> <td width="20%" align="left" id="errmsglat"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Contact Person :</td>
                      <td width="30%" align="left"><input type=text name="person" id="person" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                      </td>
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="phone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgphone"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">fax :</td>
                     <td width="30%" align="left"><input type=text name="fax" id="fax" style="width:250px;"></td> 
                  
                    </tr>

                    <tr>
                      <td width="20%" align="right" >email : </td>
                      <td width="30%" align="left"><input type=text name="email" id="email" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="web" id="web" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Pancard No : </td>
                      <td width="30%" align="left"><input type=text name="pan" id="pan" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >*Status : </td>
                      <td width="30%" align="left"><select id="status" name="status" >
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