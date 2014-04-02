 <script type="text/javascript" src="js/jquery.js"></script>
 <script type="text/javascript" src="js/common.js"></script>
 <script type="text/javascript" src="jscal/calendar.js"></script>
 <script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
 <script type="text/javascript" src="jscal/calendar-setup.js"></script>
 <script type="text/javascript" src="js/jquery.js"></script>
 

 <SCRIPT language=Javascript>

/*******function for deletion confirmation***********/
 function chkConfirm(TotRow) 
  {
    var chk = 0;
    var lp_select = TotRow;
    var rowChk = 0;
    for(var i=1;i<=lp_select;i++)
    {      

        var towernm = "tower_name_"+i;

        if($("#"+towernm).val() != '')
        {
            rowChk = 1;
             var no_of_floor =  "no_of_floor_"+i;
             var stilt       =  "stilt_"+i;
             
             if($("#"+no_of_floor).val() == '')
            {
                alert("Number of floor cant blank!");
                $("#"+no_of_floor).focus();
                return false;

            }
            else if($("#"+stilt).val() == '')
            {

                alert("Please choose stilt!");
                $("#"+stilt).focus();
                return false;
            }
            else
            {
              if($("#"+i).attr('checked'))
              {
                chk = 1;
              }
            }
        }
    }
    if(rowChk == 0)
    {
      alert("All towers name are blank!");
      return false;
    }
    if(chk == 1)
      return confirm("Are you sure! you want to delete records which are checked.");
    else
      return true;
  }

  function refreshSelectedRow(totRow)
  {
      if(totRow != '')
      {
        var pid = document.getElementById("projectId").value;
        window.location = "tower_detail_delete.php?totRow="+totRow+"&projectId="+pid;
      }
      else
        return false;

  }

   function isNumberKey(evt)
  {
   var charCode = (evt.which) ? evt.which : event.keyCode;
   if(charCode == 99 || charCode == 118)
  	 return true;
   if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
    return false;

   return true;
  }
</SCRIPT>



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

          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Add/Edit/Delete Tower Detail ({$projectDetail[0]['BUILDER_NAME']} {$projectDetail[0]['PROJECT_NAME']})</TD>
                      <TD align=right ></TD>
                    </TR>
      </TBODY></TABLE>
    </TD>
        </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height="450"><BR>

      <form method="post" enctype="multipart/form-data">

      <div id="mainDiv">


        <div style="overflow:auto;">
                      <TABLE cellSpacing=2 cellPadding=4 width="100%" align=center  style="border:1px solid #c2c2c2;">

                      <div>

                        <tr>
                            <td align = "center" nowrap><b>Number Of Towers:</b></td>
                            <td align = "center">
                                {$lastVal = 100}
                                  <select name="lp" onchange = "refreshSelectedRow(this.value);" id ="lp_select">
                                         <option value="">Select rows</option>
                                      {section name=lp start=1 loop=$lastVal step=1}
                                       
                                         <option value="{$smarty.section.lp.index}" {if ($smarty.section.lp.index)== $TotRow} selected {/if}>{$smarty.section.lp.index}</option>
                                       {/section}
                                  </select>
                               
                            </td>
                            <td align = "center" nowrap><b>Last Updated Date:</b>
                                {$last_updated_date}
                           </td>
                        </tr>

                        <tr>
							<td colspan=7>
								{if $ErrorMsg1}
										<font color="red">{$ErrorMsg1}</font>
								{/if}
							</td>
						</tr>

                        <tr class = "headingrowcolor" >
                          <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >
                             Delete
                          </td>  
                          <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
                          <td nowrap="nowrap" width="2%" align="left" class=whiteTxt><font color = red>*</font>Tower Name</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>No of floors</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>No. of Flats</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Remarks</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Please Chooose Tower Facing Direction </td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Stilt On Ground Floor</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Actual Completion Date </td>


                        </tr>
                          <form method = "post" action = "">
                           
                          {section name=rowLoop start=1 loop=$TotRow+1 step=1}

                             

                              {if ($smarty.section.rowLoop.index)%2 == 0}
                                  {$color = "bgcolor = '#F7F7F7'"}
                              {else}
                                  {$color = "bgcolor = '#FCFCFC'"}
                              {/if}

                              {$cnt = ($smarty.section.rowLoop.index)-1}

                              {$tower_name      = $towerDetail[$cnt]['TOWER_NAME']}
                              {$tower_id        = $towerDetail[$cnt]['TOWER_ID']}
                              {$no_of_floor     = $towerDetail[$cnt]['NO_OF_FLOORS']}
                              {$no_of_flats     = $towerDetail[$cnt]['NO_OF_FLATS']}
                              {$remarks         = $towerDetail[$cnt]['REMARKS']}                              
                              {$towerface       = $towerDetail[$cnt]['TOWER_FACING_DIRECTION']}
                              {$stilt           = $towerDetail[$cnt]['STILT']}
                              {$completion_date = $towerDetail[$cnt]['ACTUAL_COMPLETION_DATE']}

                          <tr id="row_1" {$color}>

                            <td align="center" valign = "top"><input type="checkbox" name="delete_{$smarty.section.rowLoop.index}" id = "{$smarty.section.rowLoop.index}"></td>
                             <td align="center" valign= "top">
                                       {$smarty.section.rowLoop.index}
                              </td>

                               <td align="center" valign = "top">
                                       
                                    <input type = "text" name = "tower_name[]" id = "tower_name_{$smarty.section.rowLoop.index}" value = "{$tower_name}" style = "width:150px">

                                    <input type = "hidden" name = "tower_name_old[]" value = "{$tower_name}" style = "width:150px">

                                    <input type = "hidden" name = "tower_id[]" id = "tower_id_{$smarty.section.rowLoop.index}" value = "{$tower_id}" style = "width:150px">
                              </td>

                               <td align="center" valign = "top">
                                      <input type = "text" name = "no_of_floor[]" id = "no_of_floor_{$smarty.section.rowLoop.index}" value = "{$no_of_floor}" style = "width:50px"  onkeypress = "return isNumberKey(event);">

                                      <input type = "hidden" name = "no_of_floor_old[]" value = "{$no_of_floor}" style = "width:150px">
                              </td>

                               <td align="center" valign = "top">
                                       <input type = "text" name = "no_of_flats[]" id = "no_of_flats_{$smarty.section.rowLoop.index}" value = "{$no_of_flats}" style = "width:80px"  onkeypress = "return isNumberKey(event);">
                                        <input type = "hidden" name = "no_of_flats_old[]" value = "{$no_of_flats}" style = "width:150px">
                              </td>
                               <td align="center" valign = "top">
                                      
                                       <textarea name = "remark[]" id = "remark_{$smarty.section.rowLoop.index}">{$remarks}</textarea>
                              </td>
                               <td align="center" valign = "top">
                                      <select name="face[]" class="face">
                                        <option value="">Choose Direction</option>
                                        <option value="N" {if $towerface == 'N'} selected {/if}>N</option>
                                        <option value="E" {if $towerface == 'E'} selected {/if}>E</option>
                                        <option value="W" {if $towerface == 'W'} selected {/if}>W</option>
                                        <option value="S" {if $towerface == 'S'} selected {/if}>S</option>
                                        <option value="NE" {if $towerface == 'NE'} selected {/if}>NE</option>
                                        <option value="NW" {if $towerface == 'NW'} selected {/if}>NW</option>
                                        <option value="SE" {if $towerface == 'SE'} selected {/if}>SE</option>
                                        <option value="SW" {if $towerface == 'SW'} selected {/if}>SW</option>
                                    </select>
                                    <input type = "hidden" name = "face_old[]" value = "{$towerface}" style = "width:150px">
                              </td>
                               <td align="center" valign = "top">
                                    
                                    <select name="stilt[]" class="stilt" id = "stilt_{$smarty.section.rowLoop.index}">
                                      <option value="">Choose Atleast One</option>
                                      <option value="True" {if $stilt === 'True'} selected {/if}>Yes</option>
                                      <option value="False" {if $stilt === 'False'} selected {/if}>No</option>
                                    </select>
                                    <input type = "hidden" name = "stilt_old[]" value = "{$stilt}" style = "width:150px">
                              </td>
                               <td align="center" valign = "top" nowrap>
                                    <input name="eff_date[]"  type="text" class="formstyle2" id="f_date_c_{$smarty.section.rowLoop.index}"  value="{$completion_date}" size="8">  <img src="../images/cal_1.jpg" id="f_trigger_c_{$smarty.section.rowLoop.index}" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />

                                    <input type = "hidden" name = "eff_date_old[]" value = "{$completion_date}" style = "width:150px">

                              </td>
                             
                           </tr>

                               <script type="text/javascript">

                                Calendar.setup({

                                  inputField     :    "f_date_c_{$smarty.section.rowLoop.index}",     // id of the input field
                                //    ifFormat       :    "%Y/%m/%d %l:%M %P",      // format of the input field
                                  ifFormat       :    "%Y-%m-%d",      // format of the input field
                                  button         :    "f_trigger_c_{$smarty.section.rowLoop.index}",  // trigger for the calendar (button ID)
                                  align          :    "Tl",           // alignment (defaults to "Bl")
                                  singleClick    :    true,
                                  showsTime   : true

                                });
                              </script>

                            


                          {/section}

                        </div>
                    </TABLE>



          <table width = "100%">
            <tr class = "headingrowcolor">
                <td align="left">
                 <input type="hidden" name="projectId" value="{$projectId}" id ="projectId"/>
                
                 <input type="submit" name="btnSave" id="btnSave" value="Save" onclick = "return chkConfirm({$TotRow});" />
                 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
               </td>
            </tr>
          </table>

        </div>
        </div>

        </form>
<!--      </fieldset>-->

       </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>
