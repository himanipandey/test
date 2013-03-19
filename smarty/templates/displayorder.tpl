<script type="text/javascript" src="javascript/tablednd.js"></script>
<script type="text/javascript" src="javascript/jquery.js"></script>
<script>
   var list = '';
   var upid = new Array();
   function getEL()   {
   	var table=document.getElementById("tb1");
   	var tableDnD=new TableDnD();
   	tableDnD.init(table);
    tableDnD.onDrop = function(table, row) {
       var rows = this.table.tBodies[0].rows;
       var debugStr = "rows now: ";
       for (var i=0; i<rows.length; i++) {
           debugStr += rows[i].id+" ";
       }
		document.getElementById('debug').innerHTML = 'row['+row.id+'] dropped<br>'+debugStr;
		list = document.getElementById('debug').innerHTML;
		upid.push(row.id);
	   }
   }
   
   function fireMe()  {
   
   	setTimeout("getEL()",100);
   }
   
   
   /*************Ajax code************/	
   		function GetXmlHttpObject()		{
   			var xmlHttp=null;
   			try	{
   				xmlHttp=new XMLHttpRequest();
   			}catch (e){
				try		{
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}catch (e)	{
   					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
   			}
   		}
   		return xmlHttp;
   	}
   	
   	function updateorder(buildid)  	{
   		var cityid = {$cityId};
   			xmlHttp=GetXmlHttpObject()
   		if (xmlHttp==null)		{
   			alert ("Browser does not support HTTP Request")
   			return
   		}
   		
   		var url="updateDisorder.php";
   		xmlHttp.onreadystatechange=stateChanged
   		xmlHttp.open("POST",url,true);
   		xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   		xmlHttp.send("cityid="+cityid+"&updatedorder="+upid+"&listorder="+list)
   	}
   	
	function stateChanged()	{
   		document.getElementById('displayorderdiv').innerHTML = "<img src='../images/ajax-loader.gif' width='25px' height='25px'>";
   		if (xmlHttp.readyState==4)	{
   			document.getElementById('displayorderdiv').innerHTML=xmlHttp.responseText;
   		}
   	}

  </script>

<style type="text/css">
.button {
    border: 1px solid #C2C2C2;
    background: #F2F2F2;
}
</style>

<body onload="fireMe()">
   </TD>
   </TR>
   <TR>
      <TD class="white-bg paddingright10" vAlign="top" align="middle" bgColor="#ffffff">
         <TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
            <TBODY>
               <TR>
                  <TD width="224" height="25">&nbsp;</TD>
                  <TD width="10">&nbsp;</TD>
                  <TD width="866">&nbsp;</TD>
               </TR>
               <TR>
                  <TD class="paddingltrt10" vAlign="top" align="middle" bgColor="#ffffff">
                    {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
                  </TD>
                  <TD vAlign="center" align="middle" width="10" bgColor="#f7f7f7">&nbsp;</TD>
                  <TD vAlign="top" align="middle" width="100%" bgColor="#eeeeee" height="400">
                     <TABLE cellSpacing="1" cellPadding="0" width="100%" bgColor="#b1b1b1" border="0">
                        <TBODY>
                           <TR>
                              <TD class="h1" align="left" background="images/heading_bg.gif" bgColor="#ffffff" height="40">
                                 <TABLE cellSpacing="0" cellPadding="0" width="99%" border="0">
                                    <TBODY>
                                       <TR>
                                          <TD class="h1" width="67%"><IMG height="18" hspace="5" src="../images/arrow.gif" width="18" />Display Order</TD>
                                          <TD align="right" colSpan="3"></TD>
                                       </TR>
                                    </TBODY>
                                 </TABLE>
                              </TD>
                           </TR>
                           <TR>
                              <TD vAlign="top" align="middle" class="backgorund-rt" height="450">
                                 <BR />
                                 <table width="680px" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #c2c2c2;">
                                    <tr class="headingrowcolor">
                                       <th class="whiteTxt" align="center" height="25px"><b>Project Display Sequence</b></th>
                                    </tr>
                                    <tr>
                                       <td>
                                          <table width="60%" border="0" cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td height="15px"></td>
                                             </tr>
                                             <form action="" name="frm" method="post">
                                                <tr>
                                                   <td nowrap="nowrap" height="25" align="right" style="padding-left:5px;padding-right:5px;">
                                                      Select City:
                                                   </td>
                                                   <td align="left">
                                                      <select name="cityId" class="cityId button" style="width:145px;">
                                                         <option value="">Select City</option>
                                                         {section name=data loop=$CityDataArr}
                                                         <option {if $cityId == {$CityDataArr[data].CITY_ID}} value ='{$cityId}' selected="selected" {else} value ='{$CityDataArr[data].CITY_ID}'{/if} >{$CityDataArr[data].LABEL}</option>
                                                         {/section}
                                                      </select>&nbsp;&nbsp;
													  <input type="submit" name="submit" value="Search" class="button"/>
                                                   </td>
                                                   
                                                </tr>
                                             </form>
                                             <tr>
                                                <td height="15px"></td>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                 </table>
                                 <TABLE width="680px" align="center" border="0">
                                    <tr>
                                       <td class="whiteTxt" colspan="4" align="center" height="10px"></td>
                                    </tr>
                                    {if count($dataArr)>0}
                                    <tr class="headingrowcolor">
                                       <td class="whiteTxt" align="center" width="100px" height="25">Current Order</td>
                                       <td class="whiteTxt" align="center" width="180px" height="25">Builder Name</td>
                                       <td class="whiteTxt" align="center" width="300px" height="25">Project Name</td>
                                       <td class="whiteTxt" align="center" width="100px" height="25">Set Order</td>
                                    </tr>
                                    <tr>
                                       <td align="left" width="590px" colspan="3" valign="top">
                                          <table id="tb1" width="590px" border="0" bordercolor="#999999" cellspacing="1" bgcolor="#333333">
                                             {$count = 1}
                                             {section name=nm loop=$dataArr}
                                             {$count = $count+1}
                                             {if $count%2 == 1}
                                             {$color = "bgcolor = '#FFFFFF'"}
                                             {else}
                                             {$color = "bgcolor = '#FCFCFC'"}
                                             {/if}
                                             <tr {$color} id="{$count-1}---{$dataArr[nm].PROJECT_ID}" >
                                             <td height="21" align="center" width="100px">{$count-1}</td>
                                             <td align="left" width="180px" style="padding-left:5px;">
                                                {if $dataArr[nm].BUILDER_NAME != ''} {$dataArr[nm].BUILDER_NAME} {else} N.A. {/if}
                                             </td>
                                             <td align="left" width="300px" style="padding-left:5px;">
                                                {if $dataArr[nm].PROJECT_NAME != ''} {$dataArr[nm].PROJECT_NAME} {else} N.A. {/if}
                                             </td>
                                             </tr>
                                             {/section}
                                          </table>
                                       </td>
                                       <td align="center" width="100px" valign="top">
                                          <table cellspacing="1" width="100%" bgcolor="#333333">
                                             {$count2 = 0}
                                             {if count($dataArr)>0}
                                             {section name=nm2 loop=$dataArr}
                                             {$count2 = $count2+1}
                                             {if $count%2 == 1}
                                             {$color = "bgcolor = '#FFFFFF'"}
                                             {else}
                                             {$color = "bgcolor = '#FCFCFC'"}
                                             {/if}
                                             <tr {$color}>
                                             <td align="center" height="21">{$count2}</td>
                                             </tr>
                                             {/section}
                                             {/if}
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="3" style="padding-top:10px;">
                                          <button onclick="updateorder();" class="button">Save</button> &nbsp;&nbsp;&nbsp;<span id="displayorderdiv"></span>
                                       </td>
                                       
                                    </tr>
                                    {/if}
                                 </TABLE>
                                 <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                       <td>
                                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td width="77%" height="25" align="center">
                                                </td>
                                                <td align="right">&nbsp;</td>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                 </table>
                              </TD>
                           </TR>
                        </TBODY>
                     </TABLE>
                  </TD>
               </TR>
            </TBODY>
         </TABLE>
      </TD>
   </TR>
   <TR>
   </TR>
   <div style="display:none;float:right;" id="debug">RC1</div>
</body>

