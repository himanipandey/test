<script type="text/javascript" src="js/jquery.js"></script>
<script language="javascript">
    function generate_excel()
    {
        /*
        var user		= $('#user').val();
        var frmdate		= $('#frmdate').val();
        var todate		= $('#todate').val();	
        ?user="+user+"&frmdate="+frmdate+"&todate="+todate
        */
        var team = $("#team").val();
        var user = $("#user").val();
        var frmdate = $("#frmdate").val();
        var todate = $("#todate").val();
        
        document.frm.action = "ajax/download-daily-report.php?team="+team+"&user="+user+"&frmdate="+frmdate+"&todate="+todate;
        document.frm.submit();
        document.frm.action = 'daily-performance-report.php'
    }
    
    function refreshUser(team) {
     var string = 'team='+team;
        $.ajax
	({
            type: "POST",
            url: "ajax/refreshUser.php",
            data: string,
            cache: false,
            success: function(html)
            {
                 $(".userList").html(html);
            }
	});
    }
    
    function submitForm() {
        var team = $("#team").val();
        var user = $("#user").val();
        var frmdate = $("#frmdate").val();
        var todate = $("#todate").val();
        window.location.href = "daily-performance-report.php?team="+team+"&user="+user+"&frmdate="+frmdate+"&todate="+todate;
    }
</script>
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
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
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
                        <TBODY>
                            <TR>
                              <TD class="h1" width="67%"><img height="18" hspace="5" src="images/arrow.gif" width="18">Daily Performance Report</TD>
                              <TD width="33%" align ="right"></TD>

                            </TR>
                        </TBODY>
                  </TABLE>
                </TD>
	      </TR>
                <tr>
                  <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
                    {if $accessDailyPerform == ''}
                      <table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
                          <tr  bgcolor='#f2f2f2'>
                              <td nowrap align ="center" colspan="3">
                                  <table width="100%" align = "center">
                                      {if count($errorMsg)>0}
                                          <tr><td colspan="3" align = "center">{$errorMsg['dateDiff']}</td></tr>
                                      {/if}
                                      <form id='frm' name='frm' method='post' action='daily-performance-report.php'>
                                          <tr height="30px;" bgcolor="#FCFCFC">
                                              <td nowrap align = "left" colspan="3">
                                                  <b>Select Team:</b>&nbsp;&nbsp;
                                                  <select onchange = "refreshUser(this.value);" name='team' id='team' style='width:120px;'>
                                                      <option value='' selected>Select Team</option>
                                                      {foreach from = $teamArr key= key item = department}
                                                          <option value="{$department}" {if $team == $department} selected {/if}>{$department}</option>
                                                      {/foreach}
                                                  </select>
                                                  &nbsp;&nbsp;<b>Select User:</b>
                                                  &nbsp;&nbsp;
                                                  <span class = "userList">
                                                      <select name='user' id='user' style='width:120px;'>
                                                          <option value='' selected>Select User</option>
                                                          {foreach from = $adminDetailArr key= adminId item = fName}
                                                              <option value="{$adminId}" {if $user == $adminId} selected {/if}>{$fName}</option>
                                                          {/foreach}
                                                      </select>
                                                  </span>
                                                  &nbsp;&nbsp;<b>From Date:</b>
                                                  &nbsp;&nbsp;
                                                  <select name='frmdate' id='frmdate' style='width:120px;'>
                                                      <option value='' selected>Select Date</option>
                                                      {foreach from = $dateArr key= dateValue item = dateShow}
                                                          <option value="{$dateValue}" {if $frmdate == $dateValue} selected {/if}>{$dateShow}</option>
                                                      {/foreach}
                                                  </select>
                                                  &nbsp;&nbsp;<b>To Date:</b>
                                                  &nbsp;&nbsp;
                                                  <select name='todate' id='todate' style='width:120px;' >
                                                      <option value='' selected>Select Date</option>
                                                      {foreach from = $dateArr key= dateValue item = dateShow}
                                                          <option value="{$dateValue}" {if $todate == $dateValue} selected {/if}>{$dateShow}</option>
                                                      {/foreach}
                                                  </select>
                                                  &nbsp;&nbsp;       
                                                  <a href='javascript:void(0);' onClick='submitForm();' style='padding:3px;text-decoration:none;border:1px solid #c2c2c2;background:#f2f2f2;'>Search</a>
                                                  &nbsp;<a href='javascript:void(0);' onClick='generate_excel();return false;' style='padding:3px;text-decoration:none;border:1px solid #c2c2c2;background:#f2f2f2;' name='excel' id='excel'>Generate Excel</a>
                                              </td>
                                          </tr>
                                      </form>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td nowrap align ="center" colspan="3">
                                  <table  bgcolor="#c2c2c2" width="100%" align = "center" style ="border:1px solid #ABC3D4;">
                                         
                                            {if count($finalArr)>0}
                                            <tr height="30px;" bgcolor="#f2f2f2">
                                              <td nowrap align = "center">
                                                 <b>SNO</b>
                                              </td> 
                                               <td nowrap align = "center">
                                                 <b>Team</b>
                                              </td> 
                                              <td nowrap align = "center">
                                                 <b>Executive</b>
                                              </td>
                                              <td nowrap align = "center" colspan="6">
                                                 <b>New Project</b>
                                              </td>
                                              <td nowrap align = "center" colspan="4">
                                                 <b>Updation Cycle</b>
                                              </td>
                                              <td nowrap align = "center"  colspan="4">
                                                <b>Secondary Price</b>
                                              </td>
                                            </tr>
                                            <tr height="30px;" bgcolor="#f2f2f2">
                                                <td nowrap align = "center">

                                                </td> 
                                                <td nowrap align = "center">

                                                </td> 
                                                <td nowrap align = "center">

                                                </td> 

                                                <td nowrap align = "center">
                                                   <b>Data<br>Collection</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>New<br>Project<br>Audit</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Data<br>Collection<br>CallCenter</b>
                                                </td>
                                                 <td nowrap align = "center">
                                                   <b>Audit-1</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Audit-2</b>
                                                </td>
                                                <td nowrap align = "center">
                                                    <b>Revert</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Data<br>Collection</b>
                                                </td>                                                 
                                                 <td nowrap align = "center">
                                                   <b>Audit-1</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Audit-2</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Revert</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Data<br>Collection</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Audit-1</b>
                                                </td>
                                                <td nowrap align = "center">
                                                   <b>Audit-2</b>
                                                </td>
                                                <td nowrap align = "center">
                                                  <b> Revert</b>
                                                </td>
                                            </tr>
                                            {else}
                                            <tr height ="30px">
                                                <td nowrap align = "center" colspan="17">
                                                    <font color = "red"> No record found!</font>
                                                </td>
                                            </tr>
                                            {/if}
                                          {if count($finalArr)>0}
                                              {$cnt = 1}
                                             {foreach from = $finalArr key = department item = valueMain}
                                                 {$np_dataCollection = 0}
                                                 {$np_dcCallCenter = 0}
                                                 {$np_newProject = 0}
                                                 {$np_audit = 0}
                                                 {$np_audit2 = 0}
                                                 {$np_revert = 0}
                                                 {$up_dataCollection = 0}
                                                 {$up_audit = 0}
                                                 {$up_audit2 = 0}
                                                 {$up_revert = 0}
                                                 {$se_dataCollection = 0}
                                                 {$se_audit = 0}
                                                 {$se_audit2 = 0}
                                                 {$se_revert = 0}
                                                 {foreach from = $valueMain key = adminId item = value}
                                                    {if $cnt%2 == 0}
                                                        {$bgcolor =" bgcolor='#f2f2f2'"}
                                                    {else}
                                                        {$bgcolor =" bgcolor='#ffffff'"}
                                                    {/if}
                                                    <tr height ="30px" {$bgcolor}>
                                                     <td nowrap align = "center">
                                                          {$cnt} {$cnt = $cnt+1}
                                                     </td> 
                                                     <td nowrap align = "center">
                                                         {$department}
                                                     </td> 
                                                     <td nowrap align = "center">
                                                         {$arrAllData[$adminId]->fname}
                                                     </td> 

                                                     <td nowrap align = "center">
                                                         {$value['NewProject']['DataCollection']}
                                                         
                                                         {$np_dataCollection = $np_dataCollection+$value['NewProject']['DataCollection']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                         {$value['NewProject']['NewProject']}
                                                         {$np_newProject = $np_newProject+$value['NewProject']['NewProject']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                         {$value['NewProject']['DcCallCenter']}
                                                         {$np_dcCallCenter = $np_dcCallCenter+$value['NewProject']['DcCallCenter']}
                                                     </td>
                                                      <td nowrap align = "center">
                                                       {$value['NewProject']['Audit1']}
                                                       {$np_audit = $np_audit+$value['NewProject']['Audit1']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['NewProject']['Audit2']}
                                                        {$np_audit2 = $np_audit2+$value['NewProject']['Audit2']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                         {$value['NewProject']['Revert']}
                                                         {$np_revert = $np_revert+$value['NewProject']['Revert']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['UpdationCycle']['DataCollection']}
                                                        {$up_dataCollection = $up_dataCollection+$value['UpdationCycle']['DataCollection']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['UpdationCycle']['Audit1']}
                                                        {$up_audit = $up_audit+$value['UpdationCycle']['Audit1']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['UpdationCycle']['Audit2']}
                                                        {$up_audit2 = $up_audit2+$value['UpdationCycle']['Audit2']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['UpdationCycle']['Revert']}
                                                        {$up_revert = $up_revert+$value['UpdationCycle']['Revert']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['SecondaryPriceCycle']['DataCollection']}
                                                        {$se_dataCollection = $se_dataCollection+$value['SecondaryPriceCycle']['DataCollection']}
                                                     </td>
                                                    <td nowrap align = "center">
                                                        {$value['SecondaryPriceCycle']['Audit1']}
                                                        {$se_audit = $se_audit+$value['SecondaryPriceCycle']['Audit1']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$value['SecondaryPriceCycle']['Audit2']}
                                                        {$se_audit2 = $se_audit2 + $value['SecondaryPriceCycle']['Audit2']}
                                                     </td>
                                                     <td nowrap align = "center">
                                                       {$value['SecondaryPriceCycle']['Revert']}
                                                       {$se_revert = $se_revert + $value['SecondaryPriceCycle']['Revert']}
                                                     </td>
                                                    </tr>
                                                  {/foreach} 
                                                  <tr height ="30px" style="background-color: #ccc">
                                                     <td nowrap align = "right" colspan="3">
                                                         <b>Sum of {ucwords(strtolower($department))}</b>
                                                     </td> 
                                                     <td nowrap align = "center">
                                                         {$np_dataCollection}
                                                     </td>
                                                     <td nowrap align = "center">
                                                           {$np_newProject}
                                                     </td>
                                                     <td nowrap align = "center">
                                                         {$np_dcCallCenter}
                                                     </td>
                                                     <td nowrap align = "center">
                                                     {$np_audit}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$np_audit2}
                                                     </td>
                                                     <td nowrap align = "center">
                                                         {$np_revert}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$up_dataCollection}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$up_audit}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$up_audit2}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$up_revert}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$se_dataCollection}
                                                     </td>
                                                    <td nowrap align = "center">
                                                        {$se_audit}
                                                     </td>
                                                     <td nowrap align = "center">
                                                        {$se_audit2}
                                                     </td>
                                                     <td nowrap align = "center">
                                                       {$se_revert}
                                                     </td>
                                                    </tr>
                                             {/foreach}
                                          {/if}
                                          
                                  </table>
                              </td>
                          </tr>
                      </table>
                     {else}
                        <font color="red">No Access</font>
                    {/if}                    
                  </TD>
              </TR>

	</TABLE>
