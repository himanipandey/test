<script type="text/javascript" src="js/tablednd.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
   var list = '';
   var upid = new Array();
   function getEL()
   {
   	var table=document.getElementById("tb1");
   	//alert("sending: "+document.getElementById("pop"));
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
   
   function fireMe()
   {
   
   setTimeout("getEL()",100);
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
   
   function updateorder_loc(cityid,localityid)   {
   	xmlHttpLoc=GetXmlHttpObject();
   	var url="ajax/updateDisorder_loc.php";
 		xmlHttpLoc.onreadystatechange=stateChangedLoc;
   		xmlHttpLoc.open("POST",url,true);
   	    xmlHttpLoc.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlHttpLoc.send("cityid="+cityid+"&updatedorder="+upid+"&listorder="+list+"&localityid="+localityid);
   }
   function stateChangedLoc()   {
   	
   	document.getElementById('displayorderdiv_loc').innerHTML = "<img src='images/ajax-loader.gif' width='25px' height='25px'>";
   	if (xmlHttpLoc.readyState==4)  	{
   		document.getElementById('displayorderdiv_loc').innerHTML=xmlHttpLoc.responseText;
   	 	}
   }
   
   function refresh_locality(ct_id)   {
   	var cityid = ct_id;
   	xmlHttpCt=GetXmlHttpObject();
   	var url="ajax/locality_refresh.php";
   	xmlHttpCt.onreadystatechange=stateChangedct
   	xmlHttpCt.open("POST",url,true);
   	xmlHttpCt.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   	xmlHttpCt.send("cityid="+cityid)
   }
   function stateChangedct()
   {
   	document.getElementById('displayorderloc').innerHTML = "<img src='images/ajax-loader.gif'>";
   	if (xmlHttpCt.readyState==4)  	{
   		document.getElementById('displayorderloc').innerHTML=xmlHttpCt.responseText;
   	}
   }
   /*******************End Ajax Code*************/
   
   function change_loc()  {
   	document.getElementById("locId").value = document.getElementById("locality_id").value;
   }
   
   // Redefine the onDrop so that we can display something
   
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
                                          <TD class="h1" width="67%"><IMG height="18" hspace="5" src="images/arrow.gif" width="18" />Display Order Locality</TD>
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
                                       <th class="whiteTxt" align="center" height="30px"><b>Project Locality Display Sequence</b></th>
                                    </tr>
                                    <tr>
                                       <td>
                                          <table width="60%" border="0" cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td height="15px"></td>
                                             </tr>
                                             <form action="" name="frm" method="post" onsubmit="change_loc();">
                                                <tr>
                                                   <td nowrap="nowrap" height="25" align="left" style="padding-left:15px;padding-right:5px;">
                                                      Select City:
                                                   </td>
                                                   <td height="50%" align="left">
                                                      <select name="cityId" class="cityId button" style="width:145px;" onchange="refresh_locality(this.value);">
                                                         <option value="">Select City</option>
                                                         {section name=data loop=$CityDataArr}
                                                         <option {if $cityId == {$CityDataArr[data].CITY_ID}} value ='{$cityId}' selected="selected" {else} value ='{$CityDataArr[data].CITY_ID}'{/if} >{$CityDataArr[data].LABEL}</option>
                                                         {/section}
                                                      </select>
                                                   </td>
													
                                                   <td height="50%" align="left" style="padding-left:10px;">
                                                      <span id="displayorderloc">
                                                         <select name="locality_id" class="locality_id button" style="width:145px;">
                                                            <option value="">Select Locality</option>
                                                            {section name=data loop=$arr}
                                                            <option value = "{$arr[data].LOCALITY_ID}" {if $arr[data].LOCALITY_ID == $locId} selected {/if}>{$arr[data].LABEL}</option>
                                                            {/section}
                                                         </select>
                                                      </span>
                                                   </td>
                                                   <td style="padding-left:15px;">
                                                      <input type="hidden" name="locId" id="locId" />
                                                      <input type="submit" name="submit" value="Search"  class="button"/>
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
                                       <td class="whiteTxt" colspan="4" align="center" height="20px"></td>
                                    </tr>
                                    {if count($dataArr)>0}
                                    <tr class="headingrowcolor" height="22">
                                       <td class="whiteTxt" align="center" width="100px">Current Order</td>
                                       <td class="whiteTxt" align="center" width="180px">
                                          Builder Name
                                       </td>
                                       <td class="whiteTxt" align="center" width="300px">
                                          Project Name
                                       </td>
                                       <td class="whiteTxt" align="center" width="100px">
                                          Set Order
                                       </td>
                                    </tr>
                                    <tr height="22">
                                       <td align="left" width="580px" colspan="3" valign="top">
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
                                       <td colspan="3" style="padding-top:20px;">
                                          <button onclick="updateorder_loc({$cityId},{$locId});" class="button">Save</button>&nbsp;&nbsp;<span id="displayorderdiv_loc"></span>
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

