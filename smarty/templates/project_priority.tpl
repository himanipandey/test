<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script language="javascript">
function chkConfirm() 
{
    return confirm("Are you sure! you want to delete this record.");
}
function selectCity(value){
	window.location.href="{$dirname}/project_priority.php?&citydd="+value;
}
function selectSuburb(value){
	var cityid = $('#citydd').val();
    window.location.href="{$dirname}/project_priority.php?citydd="+cityid+"&suburb="+value;
}
function selectLocality(value){	
    var cityid = $('#citydd').val();
	window.location.href="{$dirname}/project_priority.php?citydd="+cityid+"&locality="+value;
}
function openProjectPriorityAdd()
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var url = '/setProjectPriority.php?cityId='+cityid+'&localityid='+localityid+'&suburbid='+suburbid;
    $.fancybox({
        'width'                : 720,
        'height'               : 200,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                 : 'iframe'
    })
}

function projectPriorityEdit(id,type,priority)
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var url = '/setProjectPriority.php?cityId='+cityid+'&localityid='+localityid+'&suburbid='+suburbid+'&type='+type+'&id='+id+'&priority='+priority+'&mode=edit';
    $.fancybox({
        'width'                :720,
        'height'               :200,
        'scrolling'            : 'yes',
        'href'                 : url,
        'type'                : 'iframe'
    })
}

function projectPriorityDelete(id,type)
{
    var cityid      = $('#citydd').val();
    var localityid  = $('#loc').val();
    var suburbid    = $('#sub').val();
    var r = confirm("Are you sure you want to reset");
    if (r == true)
    {
        $.ajax({
          type: "POST",
          url: '/deletePriority.php',
          data: { mode:'project', cityId:cityid, localityid:localityid, suburbid: suburbid, type:type, id:id },
          success:function(msg){
            if(msg == 1){
                 alert("Priority Successfully deleted");
                 window.location.reload(true); 
             }
          }
      })
    }
    else
    {
        alert("OK");
    } 
    
}
function show_loc_inst(){
    var wid = screen.width/3;
    var hei = (screen.height/2+25);
    var w = window.open("Surprise1", "_blank","toolbar=no ,left=300, top=200, scrollbar=yes, location=0, status=no,titlebar=no,menubar=no,width="+wid +",height=" +hei);
    var d = w.document.open();
    d.write("<!DOCTYPE html><html><body><h1>Instructions</h1><p>1. Select the city and optinally locality and suburb to see the current projects in that area.</p><p>2. You can set at the max 15 projects for a given area.</p><p>3. Click Add Project to add a project.In that popup box, type in either project name, or project id to insert that project. </p><p>3.1. Type in the priority in the priority field.Lower numeric value is higher priority.</p><p>3.2. Check the checkbox to auto shift the projects, if desired.If this is selected, then projects at and below specified priority are shifted down 1 priority level. If this is not selected, then multiple projects could be at the same priority(which is fine, if that is what you want.)</p><p>4. Projects after first 15 would be  automatically reset to default priority.</p></body></html>");
    d.close();
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
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Project Priority</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="70%" border="0" cellpadding="0" cellspacing="0" align="center">
                          <tr>
                                <td width="20%" height="25" align="left" valign="top">
                                    <select id="citydd" name="citydd" onchange="selectCity(this.value)">
                                       <option value=''>select city</option>
                                       {foreach from=$cityArray key=k item=v}
                                           <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                                <td width="20%" height="25" align="left" valign="top">
                                    <select id="sub" name="sub" onchange="selectSuburb(this.value)">
                                       <option value=''>select suburb</option>
                                       {foreach from=$suburbArr key=k item=v}
                                           <option value="{$k}" {if $suburbId==$k}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                                <td width="20%" height="25" align="left" valign="top">
                                    <select id="loc" name="loc" onchange="selectLocality(this.value)">
                                       <option value=''>select locality</option>
                                       {foreach from=$localityArr key=k item=v}
                                           <option value="{$v->locality_id}" {if $localityId==$v->locality_id}
                                              selected="selected" {/if}>{$v->label}</option>
                                       {/foreach}
                                    </select>
                                </td>
                                <td width="20%" height="25" align="left" valign="top"><input type="button" name="add" value="Add Project Priority" onclick="return openProjectPriorityAdd();" /></td>
                                <td width="20%" height="25" align="center" valign="top"><img src="images/1376493783_help.png" onclick="show_loc_inst();" border="0" style="cursor: pointer;"/></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0>
                        <form name="form1" method="post" action="">
                          <TBODY>
                                <TR class = "headingrowcolor">
                                  <TD class=whiteTxt width=2% align="center">S.No.</TD>
                                  <TD class=whiteTxt width=5% align="center">Name</TD>
                                  <TD class=whiteTxt width=2% align="center">Id</TD>
                                  <TD class=whiteTxt width=5% align="center">Priority
                                  {if (!isset($smarty.post) || !empty($smarty.post.desc_x) )}
                                      <span style="clear:both;margin-left:10px"><input type="image" name="asc" value="asc" src="images/arrow-up.png" width="16"></span>
                                  {else}
                                      <span style="clear:both;margin-left:10px"><input type="image" name="desc" value="desc" src="images/arrow-down.png"></span>
                                  {/if}
                                  </TD> 
                                  <TD class=whiteTxt width=5% align="center">&nbsp;</TD>
                                </TR>
                                <TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>
                                {$i=0}
                                {if isset($suburbId)}
                                    {$type = DISPLAY_ORDER_SUBURB}
                                {else if isset($localityId)}
                                    {$type = DISPLAY_ORDER_LOCALITY}
                                {else}
                                    {$type = DISPLAY_ORDER}
                                {/if}
                                {foreach from=$projectArr key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                       			
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i}</TD>
                                  <TD align=center class=td-border>{$v.PROJECT_NAME}</TD>
                                  <TD align=center class=td-border>{$v.PROJECT_ID}</TD>
                                  <TD align=center class=td-border>{$v.$type}</TD>
                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return projectPriorityEdit('{$v.PROJECT_ID}','{$type}','{$v.$type}');">Edit</a> | <a href="javascript:void(0);" onclick="return projectPriorityDelete('{$v.PROJECT_ID}','{$type}');">Reset</a></TD>
                                </TR>
                                {/foreach}
                                <TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD></TR>
                          </TBODY>
                        </form>
                    </TABLE>
                 </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
