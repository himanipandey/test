<script type="text/javascript" src="javascript/jquery.js"></script>
<script type="text/javascript" src="javascript/usermanagement.js"></script>
<script type="text/javascript" src="javascript/ajax.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>


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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $userid == ''} Add New {else} Edit {/if} User</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                    {if $accessUserManage == ''} 
			 <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" id="frmusermgt" name="frmusermgt" action="">
			      <div>
				<tr>
				  <td width="20%" align="right" >*Employee Code : </td>
				  <td width="30%" align="left" ><input type="text" name="txt_empcode" id="txt_empcode" value="{$txtempcode}" style="width:220px;"></td>
				  {if $ErrorMsg["EmpCodeErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["EmpCodeErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				 </tr>
				<tr>
				  <td width="20%" align="right" >*Contact Name : </td>
				  <td width="30%" align="left" ><input type="text" name="txt_name" id="txt_name" value="{$txtfname}" style="width:220px;"></td>
				  {if $ErrorMsg["ContactNameErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["ContactNameErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Email Address : </td>
				  <td width="30%" align="left"><input type="text" name="txt_email" id="txt_email" value="{$adminemail}" style="width:220px;"></td>
				   {if $ErrorMsg["EmailErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["EmailErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">*Mobile No. : </td>
				  <td width="30%" align="left" ><input type="text" name="txt_mobile" id="txt_mobile" value="{$mobile}" style="width:220px;"></td>
				  {if $ErrorMsg["MobileNoErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["MobileNoErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*UserName : </td>
				  <td width="30%" align="left" ><input type="text" name="txt_username" id="txt_username" value="{$txtusername}" style="width:220px;"></td>
				  {if $ErrorMsg["UserNameErr"] != ''} <td width="50%" align="left"><font color = "red">{$ErrorMsg["UserNameErr"]}</font></td>{else} <td width="50%" align="left"><span id="errmsg" style="display:none"></span></td>{/if}


				</tr>
                                 
				<tr >
				  <td width="20%" align="right">Password: </td>
				  <td width="30%" align="left"><input type="password" name="txt_password" id="txt_password" value="{$txtadminpassword}" style="width:220px;">
				  {if $ErrorMsg["UserPasswordErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["UserPasswordErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
                                    <input type = "hidden" name = "oldPass" value="{$txtadminpasswordOld}">
                                </tr>
				
				<tr>
				  <td width="20%" align="right">*Region : </td>
				  <td width="30%" align="left" >

				   <select name="region"  id="region" class="field" style="width:222px;" onchange="selectedCityByRegion(this.value)">
					<option value="">Select </option>
						{foreach from=$RegionsOffice key=k item=v}
						<option {if $txtregion == {$k}} value ='{$txtregion}' selected="selected" {else} value ='{$k}' {/if}>{$v}</option>
						{/foreach}
					</select>
				  </td>
				   {if $ErrorMsg["SelectRegionErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["SelectRegionErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>

				<!--<tr>
				  <td width="20%" align="right">*Branch :</td>
				  <td width="30%" align="left" >
					<select name="branch"  id="branch" class="field" style="width:222px;" onchange="SelectTargetLevelForSales(this.value,'branch')">
					   <option value="">Select </option>
						{foreach from=$BranchLoc key=k item=v}
						<option {if $branchlocation == {$v}} value ='{$branchlocation}' selected="selected" {else} value ='{$v}' {/if}>{$v}</option>
						{/foreach}

					</select>
				  </td>
				   {if $ErrorMsg["SelectBranchErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["SelectBranchErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>-->
				<tr>
				  <td width="20%" align="right">*Department : </td>
				  <td width="30%" align="left" >

                                      <select name="dept" style="width:222px;" onchange="showHideCity(this.value);">
				   <option value="">Select </option>

						{foreach from=$departmentArray key=k item=v}
						<option {if $txtdepartment == {$k}} value ='{$txtdepartment}' selected="selected" {else} value ='{$k}' {/if}>{$v}</option>
						{/foreach}
					</select>

				  </td>
				    {if $ErrorMsg["selectDepartmentErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["selectDepartmentErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                {if $txtdepartment == 'SURVEY'}
                                    {$stye = ""}
                                {else}
                                     {$stye = 'style = "display:none;"'}
                                {/if}
				<tr id = 'showhide' {$stye}>
				  <td width="20%" align="right" valign = "top">Mapped City : </td>
                                  <td width="30%" align="left" >
                                      <select name="city[]" multiple="" style="width:220px;">
                                          <option value="">Select City</option>
                                          {foreach from = $arrCity key = key item = item}
                                            <option {if in_array($key,$arrExistingCity)} selected {/if} value="{$key}">{$item}</option>
                                          {/foreach}
                                          <option value="other" {if $otherCityChk == 1}selected{/if}>All Other Cities</option>
                                      </select>
                                  </td>
				  <td width="50%" align="left"></td>
                                </tr>
				<tr>
				  <td width="20%" align="right">*Role : </td>
				  <td width="30%" align="left" >

				   <select name="department"  id="department" class="field" style="width:222px;" onchange="hideBlockFunc(this.value)">
				   <option value="">Select </option>

                                    {foreach from=$designationArray key=k item=v}
                                        <option {if $txtdesignation == {$k}} value ='{$txtdesignation}' selected="selected" {else} value ='{$k}' {/if}>{$v}</option>
                                    {/foreach}
                                    </select>

				  </td>
				    {if $ErrorMsg["selectDepartmentErr"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["selectDepartmentErr"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
		    	
				<tr>
				  <td width="20%" align="right" >Joining Date : </td>
				  <td width="30%" align="left" ><input name="joiningdate"  type="text" class="formstyle2" id="joiningdate"  value="{$joiningdate}" size="8" readonly style="width:200px">  <img src="images/cal_1.jpg" id="f_trigger_start" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" /></td>

				 </tr>

				<tr>
				  <td width="20%" align="right" >Resignation Date : </td>
				  <td width="30%" align="left" ><input name="resignationdate"  type="text" class="formstyle2" id="resignationdate"  value="{$resignationdate}" size="8" readonly style="width:200px">  <img src="images/cal_1.jpg" id="f_trigger_end" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
				  </td>
				 </tr>

				<tr style="dispaly:none" class="pt_changestatus">
				  <td width="20%" align="right">*Status : </td>
				  <td width="30%" align="left">
				  {if $status=="Y"}
					{$checkActive = "checked=checked"}
				{else}
					{$uncheckActive = "checked=checked"}
					{/if}

						 <input type="radio" name="active" id="activebtn" value="Y" {$checkActive}>&nbsp;Active &nbsp;&nbsp;
						 <input type="radio" name="active" id="unactivebtn" value="N" {$uncheckActive}>&nbsp;Deactive
				  </td>
			   </tr>
                           <tr>
				  <td width="20%" align="right" >Cloud Agent Id : </td>
				  <td width="30%" align="left" ><input type="text" name="cloudAgentId" id="cloudAgentId" value="{$cloudAgentId}" style="width:220px;"></td>
				  <td width="50%" align="left"></td>
			   </tr>
                           
			   <tr>
				  <input type="hidden" id="userid" name="userid" value="{$userid}">
				  <input type="hidden" id="managerids"  name="managerids" value="">
				  <td>&nbsp;</td>
				  <td align="left" style="padding-top:20px;">
				  <input type="hidden" name="catid" value="<?php echo $catid ?>" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
				  </td>
			    </tr>
                            
			      </div>
			    </form>
			    </TABLE>
				</td>
		  </tr>
		 </table>
                                  <script type="text/javascript">

	Calendar.setup({
		inputField     :    "joiningdate",     // id of the input field
		ifFormat       :    "%Y-%m-%d",      // format of the input field
		button         :    "f_trigger_start",  // trigger for the calendar (button ID)
		align          :    "Tl",           // alignment (defaults to "Bl")
		singleClick    :    true,
		showsTime	   :	true

	});

	Calendar.setup({
		inputField     :    "resignationdate",     // id of the input field
		ifFormat       :    "%Y-%m-%d",      // format of the input field
		button         :    "f_trigger_end",  // trigger for the calendar (button ID)
		align          :    "Tl",           // alignment (defaults to "Bl")
		singleClick    :    true,
		showsTime	   :	true

	});

</script>

                  {else}
                    <font color = "red">No Access</font>
                 {/if}
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
<script type="text/javascript">
//function for show hide department only display city list when department is survey
function showHideCity(department) {
    if(department == 'SURVEY') {
        jQuery("#showhide").show();
    }else {
        jQuery("#showhide").hide();
    }
}
jQuery(document).ready(function(){

	jQuery("#txt_username").blur(function(){

		var username = jQuery("#txt_username").val();
		username = username.trim();

		if(username==''){
			jQuery("#errmsg").show();
			jQuery("#errmsg").html('<font color = "red">Username cann\'t be left blank.</font>');
			return false;

		}

		jQuery.ajax({

			type: "POST",
			url:'ajax/checkUserName.php',
			data: "username="+username,
			success: function(resonsedata){

				if(resonsedata>0){
					jQuery("#errmsg").show();
					jQuery("#errmsg").html('<font color = "red">Username <b>'+username+'</b> already exist.</font>');
				}else{
					jQuery("#errmsg").show();
					jQuery("#errmsg").html('<font color = "green">Username <b>'+username+'</b> available.</font>');
				}
			}

		});


	});

});



</script>