<script type="text/javascript" src="js/jquery.js"></script>

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
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $builderid == ''} Add New {else} Edit {/if} Builder</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		{if $accessBuilder == ''}
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			     <tr>
                                <form method = "post">
                              
                                <td align = "left" colspan="2">
                                    <fieldset>
                                    <b>Project Id</b>:&nbsp;
                                    <input type="text" name = "projectId" value="{$projectId}">
                                    &nbsp;
                                    <input type = "submit" name = "searchProject" value="Search Project">
                                     </fieldset>
                                </td>
                                </form>
                            
                            </tr>  
                             <form method="post" enctype="multipart/form-data">    
                             <tr>
                                    <td  align = "center" colspan = "2">
                                       <font color = "red" style="font-size:17px;">{$ErrorMsg2}</font><br>
                                    </td>
				</tr>

				<tr>
                                    <td width="20%" align="right" ><font color = "red">*</font><b>Project Name :</b> </td>
                                    <input type=hidden name="projectNameOld" id="projectNameOld" value="{$projectName}" style="width:357px;">
                                    <td width="30%" align="left"><input type=text name=projectName id=projectName value="{$projectName}" style="width:357px;"></td>
                                    {if $ErrorMsg["projectName"] != ''}
                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["projectName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                <tr>
                                    <td width="20%" align="right" ><font color = "red">*</font><b>Builder Name :</b> </td>
                                    <td width="30%" align="left"><input type=text name="builderName" id="builderName" value="{$builderName}" style="width:357px;"></td>
                                    {if $ErrorMsg["builderName"] != ''}
                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["builderName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font><b>City :</b></td>
				  <td width="30%" align="left" ><input type=text name="city" id="city" value="{$city}" style="width:357px;">
                                      {if $ErrorMsg["city"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["city"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                <tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font><b>Locality :</b></td>
				  <td width="30%" align="left" ><input type=text name="locality" id="locality" value="{$locality}" style="width:357px;">
                                      {if $ErrorMsg["locality"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["locality"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" > <b>Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:360px;"></td>
				   <td width="50%" align="left"<td width="50%" align="left"></td>
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><b>Meta Keywords :</b></td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="45">{$txtMetaKeywords}</textarea>
                                    </td>
                                    <td width="50%" align="left"></td>
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><b>Meta Description :</b></td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="45">{$txtMetaDescription}</textarea>
				  </td>
                                    <td width="50%" align="left"></td>
				</tr>
                                <tr style ="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
				  <td colspan = "2" align="right" style="padding-left:152px;" >
				  <input type="submit" name="btnSave" id="btnSave" value="Generate Microsite Code">
				  </td>
				</tr>
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
