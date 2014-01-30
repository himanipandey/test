<script>
	function bank_validation()
	{
		if(document.getElementById("bankname").value == '')
		{
			alert("Please enter bank name!");
			document.getElementById("bankname").focus();
			return false;
		}
		if(document.getElementById("bank_detail").value == '')
		{
			alert("Please enter bank detail!");
			document.getElementById("bank_detail").focus();
			return false;
		}
		return true;
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
                <TD class=h1 align="left" background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18><b>Add Bank</b></TD>
                      <TD align=right colSpan=3></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height="450"><BR>
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="left">
                             <!--{$Sorting}--> 
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>

					
					{if count($errormsg)!= 0} <table height = "30px"><tr><td><font color ="red">{$errormsg[0]}</font></td></tr></table>{/if}
					  <table cellSpacing=1 cellPadding="4" width="50%" align="center" style = "border:1px solid;" >
					
						<form name = "frm" method = "post" onsubmit = "return bank_validation();" enctype = "multipart/form-data">
						
						<tr bgcolor = '#F7F7F7'>
							<td align = "right"><b>Bank Name:</b></td>
							<td align = "left"><input type = "text" name = "bankname" id = "bankname" value = "{$bankname}"></td>
						</tr>
						<tr bgcolor = '#F7F7F7'>
							<td align = "right"><b>Bank Logo:</b></td>
							<td align = "left"><input type = "file" name = "logo"></td>
						</tr>
						<tr bgcolor = '#F7F7F7'>
							<td align = "right" valign ="top"><b>Bank Detail:</b></td>
							<td align = "left">
								<textarea name = "bank_detail" id = "bank_detail" rows="15" cols="30">{$bank_detail}</textarea>
							</td>
						</tr>
						
							 <tr  class = "headingrowcolor"><td align = "right" colspan = "3"><input type = "submit" value = "Submit" name = "submit"></td></tr>
						</form>
					</table>
				

					
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

	Calendar.setup({

		inputField     :    "f_date_start",     // id of the input field
	//    ifFormat       :    "%Y/%m/%d %l:%M %P",      // format of the input field
	  ifFormat       :    "%Y-%m-%d",      // format of the input field
		button         :    "f_trigger_start",  // trigger for the calendar (button ID)
		align          :    "Tl",           // alignment (defaults to "Bl")
		singleClick    :    true,
		showsTime		:	true

	});

</script>
 <script type="text/javascript">	
	Calendar.setup({

		inputField     :    "f_date_end",     // id of the input field
	//    ifFormat       :    "%Y/%m/%d %l:%M %P",      // format of the input field
	  ifFormat       :    "%Y-%m-%d",      // format of the input field
		button         :    "f_trigger_end",  // trigger for the calendar (button ID)
		align          :    "Tl",           // alignment (defaults to "Bl")
		singleClick    :    true,
		showsTime		:	true

	});

/**************Date Diff coding************************/
var dt1 = 0;
var dt2 = 0;
var diff=0;
function DateDiff1(dt,flg)
{
//alert(parseDate(dt)+"here");
	var DiffNoOfDays	=	0;
	if(flg == 1)
		this.dt1 =  parseDate(dt);
	else
		this.dt2 = parseDate(dt);

	if( ((this.dt1 != 0) || (this.dt1 != '')) && ((this.dt2 != 0) || (this.dt2 != '')) )
	{
		DiffNoOfDays	=	DateDiff(this.dt1,this.dt2)/86400000;
		DiffNoOfDays		=	Math.abs(DiffNoOfDays);
		
	}
	this.diff=DiffNoOfDays;
}

function parseDate(input)
{
	var parts = input.match(/(\d+)/g);
	return new Date(parts[0], parts[1]-1, parts[2]); // months are 0-based
}



function DateDiff(date1,date2)
{
   return date1.getTime() - date2.getTime();
}

function getDiff()
{
	 this.diff;
	 if(this.diff > 30)
	 {
		alert("Maximum limit of Date Difference Exceeded! ");
		return false;
	 }
	 return true;
}

</script>