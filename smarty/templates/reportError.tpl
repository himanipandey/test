<style type="text/css">
.button {
    border: 1px solid #C2C2C2;
    background: #F2F2F2;
}
.fwb {
	font-weight: bold;
}
.borderRed{ border:1px solid red;
}
.borderBlack{ border:1px solid #677788;
}
</style>
<script type="text/javascript" src="/fancybox/fancybox/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
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
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Error List</TD>
                      <TD align=right colSpan=3>
                          </TD>
                    </TR>
                    </TBODY>
                  </TABLE>
                </TD>
              </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                    <div id="messageUpdate"></div>
                    <TABLE cellSpacing=1 cellPadding=4 width="100%;" align=center border=0 style='table-layout: fixed;'>
                    <form name="form1" method="post" action="">
                    <TBODY>
                        <TR class = "headingrowcolor" height="25">
                              <TD class=whiteTxt align="center" style="width:20px;"><input type="checkbox" id="selectinvert" name="selectinvert1" value="" onclick="checkedAll(document.form1.selectinvert);" /></TD>
                              <TD class=whiteTxt align="center" style="width:30px;">S.No.</TD>
                              <TD class=whiteTxt align="left" style="width:150px;">Error Type: Error Desc</TD>
                              <TD class=whiteTxt align="left" style="width:100px;">Project</TD>
                              <TD class=whiteTxt align="left" style="width:100px;">Property Type</TD>
                              <TD class=whiteTxt align='left' style="width:80px;">Date Reported</TD>
                              <TD class=whiteTxt align='left' style="width:190px;">Status</TD>
                              <TD class=whiteTxt align='left' style="width:200px;">Comments</TD>
                              <TD class=whiteTxt align="center" style="width:100px;">History</TD>
                              <TD class=whiteTxt align="center" style="width:50px;">URL link</TD>
                              <!--<TD class=whiteTxt width=12% align="center">Last Modified Date</TD>
                              <TD class=whiteTxt width=12% align="center">History</TD-->
                        </TR>
                        <TR><TD colspan=10 class=td-border></TD></TR>
                        <TR>
                            <TD colspan="10" class="td-border" height="12">
                               <div style="float:right"><input type="button" name="subbtn_top" id="subbtn_top" value="Update Error Status" /></div>
                            </TD>
                         </TR>
                        {$count = 0}
                        {section name=data loop=$errorDataArr}
                            {$count = $count+1}
                            {if $count%2 == 0}
                                    {$color = "bgcolor = '#FCFCFC'"} 
                            {else}
                                    {$color = "bgcolor = '#F7F7F7'"}
                            {/if}	
                            <TR {$color}>
                                <TD align=center class=td-border>
                                    <input type="checkbox" id="selectinvert{$errorDataArr[data].ID}" name="selectinvert" value="{$errorDataArr[data].ID}" />
                                </TD>
                                <TD align=center class=td-border>{$count}</TD>
                                <TD align=left class=td-border style="overflow:hidden; word-wrap: break-word;"><b>{$error_type[$errorDataArr[data].ERROR_TYPE]}</b>: {$errorDataArr[data].DETAILS}</TD>
                                <TD align=left class=td-border>{$errorDataArr[data].BUILDER_NAME} {$errorDataArr[data].PROJECT_NAME}, {$errorDataArr[data].LOCALITY}, {$errorDataArr[data].CITY}</TD>
                                <TD align=left class=td-border>{$errorDataArr[data].UNIT_NAME}<br />{$errorDataArr[data].UNIT_TYPE}<br />{if $errorDataArr[data].SIZE > 0}({$errorDataArr[data].SIZE} sq ft){/if}</TD>
                                <TD align=left class=td-border>{$errorDataArr[data].DATE|date_format}</TD>
                                <TD align=left class=td-border>
                                    <select name="status_{$errorDataArr[data].ID}" id="status_{$errorDataArr[data].ID}" style="width:160px;">
                                        <option value="0" {if $errorDataArr[data].STATUS_ID == 0} selected='selected' {/if}>No action taken</option>
                                        <option value="1" {if $errorDataArr[data].STATUS_ID == 1} selected='selected' {/if}>No issue Found</option>
                                        <option value="2" {if $errorDataArr[data].STATUS_ID == 2} selected='selected' {/if}>Issue found / being resolved</option>
                                        <option value="3" {if $errorDataArr[data].STATUS_ID == 3} selected='selected' {/if}>Error Corrected</option>
                                    </select>
                                </TD>
                                <TD align=left class=td-border>
                                    <textarea id="comments_{$errorDataArr[data].ID}" name="comments_{$errorDataArr[data].ID}" placeholder="Enter your Comments here" rows="3" cols="20"></textarea>
                                </TD>
                                <TD align=left class=td-border><a href="javascript:void(0);" id="history" onclick=openHistBox({$errorDataArr[data].ID})>See History</a></TD>
                                <TD align=left class=td-border>
                                    {if $errorDataArr[data].URL!=''}
                                        <a href="{$errorDataArr[data].URL}"  target='_blank'>URL</a>
                                    {/if}
                                </TD>
                            </TR>
                        {/section}
                        {if count($errorDataArr)<=0}
                            <TR><TD colspan="10" class="td-border" align="left">Sorry, no records found.</TD></TR>
                        {/if}
                         
                        <TR><TD colspan="10" class="td-border" align="right">&nbsp;</TD></TR>
                     
                      </TBODY>
                    </FORM>
                    </TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<TR>
 
</TR>

<script type="text/javascript">
var checkflag = "false";
function checkedAll(field) {
    if (checkflag == "false") {
      for (i = 0; i < field.length; i++) {
        field[i].checked = true;
      }
      checkflag = "true";
    } else {
      for (i = 0; i < field.length; i++) {
        field[i].checked = false;
      }
      checkflag = "false";
    }
}

function countChecked() {
     var n = jQuery("input[type=checkbox]:checked").length;
     if(n==0){
         jQuery("#confirmMsg").html('<font size="2" face="verdana" color="red">Please Select Atleast One Checkbox</font>');
         return false;
     }else
     {
         jQuery("#confirmMsg").html('');
         return true;
     }
}
   
jQuery(document).ready(function(){
    jQuery('input[id^="subbtn_"]').bind('click',function(){
        var i=0;
        var val = []; 
        var idArr= [];
        var data = [];
        var current_status_val= [];
        var x = countChecked();
        if(x==false)
        {
            alert("Please check any checkbox first!");
            return false;
        }
        var cnt = 0;
        jQuery(':checkbox:checked').each(function(i){
            if(jQuery("#comments_"+jQuery(this).val()).val()===''){
                jQuery("#comments_"+jQuery(this).val()).focus();
                jQuery("#comments_"+jQuery(this).val()).addClass('borderRed');
                cnt = cnt + 1;
                alert('Please enter your comment.');
                return false;
            }else{
               jQuery("#comments_"+jQuery(this).val()).removeClass('borderRed').addClass('borderBlack');
            }
            current_status_val[i] = $('#status_'+jQuery(this).val()).val();
            val[i] = jQuery(this).val()+'_'+jQuery("textarea#comments_"+jQuery(this).val()).val();
            idArr[i]=jQuery(this).val();
        });
        if(cnt==0){
             jQuery.ajax({
                type: "POST",
                url: "ajax/updateErrorStatus.php",
                data: "completeData="+val+'&current_status_val='+current_status_val+'&adminid='+{$smarty.session.adminId},
                 /*beforeSend: function() {
                      setTimeout( function() {
                          jQuery('#confirmMsg').hide();
                      }, 3000 );
                      jQuery("#messageUpdate").html('<img src = "images/bar-circle.gif" width="20px" height="20px;">');
                  },*/

                 success: function(response){ 
                     if(response == 'Success'){
                        alert("Error Status Updated Sucessfully");
                        location.reload();
                     }else{
                        alert("Error in updation");
                        location.reload();
                     }
                 }
             });
          }          
    });
});

function openHistBox(errid)
{
    $.fancybox({
        'href': '/errorHistory.php?errid='+errid,
        'type': 'iframe'
    })
}
</script>