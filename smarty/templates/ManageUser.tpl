<script language="javascript">
function chkConfirm() 
	{
		return confirm("Are you sure! you want to delete this record.");
	}

/*************Ajax code************/	
		function GetXmlHttpObject()
		{
			var xmlHttp=null;
			try
			{
                                // Firefox, Opera 8.0+, Safari
                                xmlHttp=new XMLHttpRequest();
			}
			catch (e)
			{
				//Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		return xmlHttp;
	}
	var idfordiv = 0;
	
	
	function statuschange(userid)
	{
	idfordiv = userid;
			xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		var url="RefreshBanStat.php?part=userstatus&userId="+userid;
		//alert(url);
		xmlHttp.onreadystatechange=stateChanged
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
	
	function stateChanged()
	{
	
		if (xmlHttp.readyState==4)
		{
		//alert("here");
			document.getElementById('statusRefresh'+idfordiv).innerHTML=xmlHttp.responseText;
		
		}
	}
	/*******************End Ajax Code*************/

    
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
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>User List</TD>
                      <TD align=right colSpan=3>
                           {if $accessUserManage == ''} 
                             <a href="useradd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add New User</b></a>
                          {/if}
                         </TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                     {if $accessUserManage == ''}
                  <!--<table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="left">
                             {$Sorting} 
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>-->

				   <table cellSpacing=1 cellPadding=4 width="40%" align="center"  style = "border:1px solid" >
                                      
						<form name = "frm" id="frm" method = "post">
						  
						 <!-- <tr bgcolor = '#F7F7F7'><td align = "right"><b>Order No:</b></td><td align = "left"><input type = "text" name = "orderid" id = "orderid" value = "{$orderid}"></td></tr>-->
						
							  <!--<tr bgcolor = '#F7F7F7'>
								<td align = "right"><b>Builder Name:</b></td><td align = "left">
									<select name = "buildername" id="buildername" onchange = "refreshprojectlist(this.value);" style="width:147px;">
										<option value = "">Select Builder</option>
										 {section name=data loop=$Builder}
											  <option {if $buildername == {$Builder[data].BUILDER_ID}} value ='{$Builder[data].BUILDER_ID}' selected="selected" {else} value ='{$Builder[data].BUILDER_ID}'{/if} >{$Builder[data].BUILDER_NAME}</option>
										 {/section}
									</select>
								</td>
							</tr>
							
							 <tr bgcolor = '#FCFCFC'>
								
								<td align = "right"><b>Project Name:</b></td><td align = "left">
									<select name = "projectname" id = "projectname" style="width:147px;">
										<option value = "">Select Project</option>
										{section name=dataproj loop=$project}
											  <option {if $projectname == {$project[dataproj].PROJECT_ID}} selected="selected" {else} value ='{$project[dataproj].PROJECT_ID}'{/if} >{$project[dataproj].PROJECT_NAME}</option>
										 {/section}

										 
									</select>
								</td>
								
							</tr>-->

							 <tr  class = "headingrowcolor"><td align = "center" colspan = "2"><font color="#FFFFFF" ><strong>Search</strong></font></td></tr>
							<tr><td style="padding-top:3px;">&nbsp;</td></tr>
							
							<tr>
							  <td  align="right"><b>Employee Id :</b></td>
							  <td  align="left"><input type="text" id="empid" name="empid" value="{$empid}"></td>
							</tr>

							<tr>
							  <td  align="right"><b>Admin Name :</b></td>
							  <td  align="left"><input type="text" id="name" name="name" value="{$name}"></td>
							</tr>	
							
							<tr>
							  <td  align="right"><b>UserName :</b></td>
							  <td  align="left"><input type="text" id="username" name="username" value="{$username}"></td>
							</tr>
							
							<tr>
							  <td  align="right"><b>Email Address:</b></td>  
							  <td  align="left"><input type="text" id="email" name="email" value="{$email}"></td>
							</tr>

							<tr>
							  <td  align="right"><b>Mobile No :</b></td>
							  <td  align="left"><input type="text" id="mobile" name="mobile" value="{$mobile}"></td>
							</tr>
							
							<tr><td style="padding-top:3px;">&nbsp;</td></tr>
							<tr  class = "headingrowcolor">
                                <td align = "right" colspan = "2">
                                    <input type = "submit" value = "Search" name = "search" id="search" />
                                    
                                </td>
                            </tr>
							
						</form>
                          
					</table>
				    
                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0 style="padding-top:20px;">
                    <form name="form1" method="post" action="">
                      <TBODY>
                        
                      <TR class = "headingrowcolor">
                        <TD class=whiteTxt width=5% align="center">S No.</TD>
                        <TD class=whiteTxt width=22% align="center">User Details</TD>
                        <TD class=whiteTxt width=13% align="center">Region</TD>
                        <TD class=whiteTxt width=10% align="center">Role / Department</TD>
			<TD class=whiteTxt width=9% align="center">Created Date</TD>
                        <TD class=whiteTxt width=6% align="center">Status</TD>
                         <TD class=whiteTxt width=12% align="center">Action</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>
                      {$count = 0}
                       {section name=data loop=$userDataArr}
                       	{$count = $count+1}
                       		{if $count%2 == 0}
                       			{$color = "bgcolor = '#F7F7F7'"}
                       		{else}
                       			{$color = "bgcolor = '#FCFCFC'"}	
                       		{/if}	
                      <TR {$color}>
                        <TD align=center class=td-border>{$count} </TD>
                        <TD align=left class=td-border>{if $userDataArr[data].EMP_CODE != ''} <b>Emp. Id : </b> {$userDataArr[data].EMP_CODE}<br>{/if}
						{if $userDataArr[data].FNAME != ''}<b>Name : </b>{$userDataArr[data].FNAME}&nbsp;&nbsp;<img height="10" width="10" alt="{$userDataArr[data].ADMINEMAIL}" title="{$userDataArr[data].ADMINEMAIL}" src="images/mailicon.png"><br>{/if}

						{if $userDataArr[data].MOBILE != ''}<b>Mobile : </b>{$userDataArr[data].MOBILE}{/if}
						
						</TD>
                        <TD align=center class=td-border>
                        	{$regionArray[$userDataArr[data].REGION]}
                        </TD>

			<TD align=center class=td-border>{if $userDataArr[data].DESIGNATION != ""}{strtoupper($userDataArr[data].DESIGNATION)}{else} NA {/if} / {$userDataArr[data].DEPARTMENT}</TD>
						

                        <TD align=center class=td-border>                        	
						{if $userDataArr[data].ADMINADDDATE == '0000-00-00'} 0000-00-00
                                                {else}{$userDataArr[data].ADMINADDDATE|date_format:"%d-%m-%Y"}
                                                {/if}
                        </TD>
						
                        <TD align=center class=td-border>                        	
							{if $userDataArr[data].STATUS == 'Y'}
                                Active
                                {else}
                                    Resigned
                            {/if}	
                        </TD>
                        
                        <TD  class="td-border" align=center  nowrap = 'nowrap'>
			<span id="statusRefresh{$userDataArr[data].ADMINID}" >
                      	<a href = "javascript:void(0); onclick= statuschange({$userDataArr[data].ADMINID})">{if  (ucwords($userDataArr[data].STATUS) == 'Y')}Active{else}Deactive{/if}</a></span>
									|
						<a href="useradd.php?userid={$userDataArr[data].ADMINID}&sort={$sort}" title="{$userDataArr[data].BUILDER_NAME}">Edit</a>
                          </TD>
                      </TR>
                       {/section}
                       
                        {if $NumRows<=0}
	                        <TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
                        {/if}
                         
                      <TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD>
                      </TR>
                     
                      </TBODY>
                    </FORM>
                    </TABLE>
                    {if $NumRows>0}
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="center">
                            	{if $NumRows>30}
                                        {$Pagginnation}
                                {/if}
                              
                            </td>
                            <td align="right">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  {/if}
                  {else}
                    <font color = "red">No Access</font>
                 {/if}
	      </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>

