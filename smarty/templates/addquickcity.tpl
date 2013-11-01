
<script type="text/javascript" src="js/jquery.js"></script>

<script>

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
</script>


<script>
function dispcity(cityId)
{
	var id = $("#cityId").val();
	var dataString = 'part=addquickcity&flg=suburb&id='+ id;

	$("#city_txtbox_hidden").val(id);

	$("#subcity_txtbox_hidden").val('');
	$("#subcity_txtbox").val('');

	$("#locality_txtbox_hidden").val('');
	$("#locality_txtbox").val('');

	if(id!='')
	{
		cTxt=$("#cityId :selected").text();
		$('#city_txtbox').attr('readonly',false);
		$("#city_txtbox").val(cTxt);
		//$('#city_txtbox').attr('readonly',true);
		//$("#city_txtbox").css('background','#c2c2c2');
	}
	if(id=='' || id=='0')
	{

		$('#city_txtbox').attr('readonly',false);
		$("#city_txtbox").val('');
		$("#city_txtbox").css('background','#ffffff');
	}

	$.ajax
	({
		type: "POST",
		url: "RefreshSuburb.php",
		data: dataString,
		cache: false,
		success: function(html)
		{
			$(".suburbId").html(html);
		}
	});
        
        var dataStringSub = 'part=addquickcity&flg=locality&id='+id;

	$.ajax
	({
		type: "POST",
		url: "RefreshSuburb.php",
		data: dataStringSub,
		cache: false,
		success: function(html)
		{
			$(".localityId").html(html);
		}
	});
}

function addupdatecity()
{
	id = $("#city_txtbox_hidden").val();
	label = $("#city_txtbox").val();	
	xmlHttpadd1=GetXmlHttpObject();
	if (xmlHttpadd1==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	var rtrn = specialCharacterValidation($("#city_txtbox").val());
	if(rtrn == false)
	{
		alert("Special Characters are not allowed");
		return false;
	}
	
	if(label == '')
	{
		alert("Please select city or enter text");
		return false;
	}
	else
	{
		var url="addnewcity.php?cityval="+label+"&id="+id;
		xmlHttpadd1.open("GET",url,false);
		xmlHttpadd1.send(null);
		var returnval=xmlHttpadd1.responseText;
		if(xmlHttpadd1)
		{
			document.getElementById('maincity').innerHTML = returnval;
			var cityselid=$("#cityId :selected").val();
			dispcity(cityselid);
			alert("The record has been successfully updated.");
		}

	}


}


function deletecity()
{
	if(confirm("Are you sure! you want to delete this record."))
	{
		id = $("#cityId").val();

		xmlHttpdeletec=GetXmlHttpObject();
		if (xmlHttpdeletec==null)
		{
			alert ("Browser does not support HTTP Request")
			return false;
		}
		if(id == '')
		{
			alert("Please select city");
			return false;
		}
		else
		{
			var url="addnewcity.php?ciddelete="+id;
			//alert(url);
			xmlHttpdeletec.open("GET",url,false);
			xmlHttpdeletec.send(null);
			var returnval=xmlHttpdeletec.responseText;
			if(returnval)
			{
				document.getElementById('maincity').innerHTML = returnval;

				alert("The record has been successfully Deleted.");
				dispcity(id);
			}

		}
	}
	else
	{
		return false ;
	}


}
/*-------------------------------------------------------------------------------*/

function dispsubcity(subcityid)
{
	var id = $("#suburbId").val();
	var cityid = $("#cityId").val();
        $("#subcity_txtbox_hidden").val(id);

	//$("#locality_txtbox_hidden").val('');
	//$("#locality_txtbox").val('');


	if(id!='')
	{
		var cTxt=$("#suburbId :selected").text();
		$('#subcity_txtbox').attr('readonly',false);
		$("#subcity_txtbox").val(cTxt);
		//$('#subcity_txtbox').attr('readonly',true);
		//$("#subcity_txtbox").css('background','#c2c2c2');
	}
	if(id=='' || id=='0')
	{
		$('#subcity_txtbox').attr('readonly',false);
		$("#subcity_txtbox").val('');
		$("#subcity_txtbox").css('background','#ffffff');
	}

	
}

function addupdatesubcity()
{
	id = $("#subcity_txtbox_hidden").val();
	label = $("#subcity_txtbox").val();
	cityid = $("#cityId").val();

	xmlHttpadd1=GetXmlHttpObject();
	if (xmlHttpadd1==null)
	{
		alert ("Browser does not support HTTP Request")
		return false;
	}
	
	var rtrn = specialCharacterValidation($("#subcity_txtbox").val());
	if(rtrn == false)
	{
		alert("Special Characters are not allowed");
		return false;
	}
	if(cityid == '')
	{
		alert("Please select city");
		return false;
	}
	else if(label == '')
	{
		alert("Please enter suburb");
		return false;
	}
	else
	{
		var url="addnewsubcity.php?cityid="+cityid+"&subcityval="+label+"&id="+id;

		xmlHttpadd1.open("GET",url,false);
		xmlHttpadd1.send(null);
		var returnval=xmlHttpadd1.responseText;
		if(returnval)
		{
			document.getElementById('mainsubcity').innerHTML = returnval;
			subcityselid=$("#suburbId :selected").val();
			dispsubcity(subcityselid);
			alert("The record has been successfully updated.");
		}

	}

}

function deletesubcity()
{
	if(confirm("Are you sure! you want to delete this record."))
	{
		id = $("#suburbId").val();
		cid = $("#cityId").val();

		xmlHttpdeletesub=GetXmlHttpObject();
		if (xmlHttpdeletesub==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
			if(id == '')
			{
				alert("Please select Suburb");
			}
			else
			{
				var url="addnewsubcity.php?deletesubcity="+id+"&cid="+cid;
				//alert(url);
				xmlHttpdeletesub.open("GET",url,false);
				xmlHttpdeletesub.send(null);
				var returnval=xmlHttpdeletesub.responseText;
				if(returnval)
				{
					document.getElementById('mainsubcity').innerHTML = returnval;
					dispsubcity(subcityselid);
					alert("The record has been successfully deleted.");
				}
			}
	}
	else
	{
		return false ;
	}

}
/*-------------------------------------------------------------------------------*/

function displocality(localityid)
{
	var id=$("#localityId").val();
	var cityid=$("#cityId").val();
	var suburbId=$("#suburbId").val();

	//var dataString = 'id='+ id;

	$("#locality_txtbox_hidden").val(id);

	if(id!='')
	{
		var cTxt=$("#localityId :selected").text();
		$('#locality_txtbox').attr('readonly',false);
		$("#locality_txtbox").val(cTxt);
		//$('#locality_txtbox').attr('readonly',true);
		//$("#locality_txtbox").css('background','#c2c2c2');
	}
	if(id=='' || id=='0')
	{
		$('#locality_txtbox').attr('readonly',false);
		$("#locality_txtbox").val('');
		$("#locality_txtbox").css('background','#ffffff');
	}
        
       if(id!='' && cityid!=''){
       var dataStrAutofill = 'part=autofillsub&loc='+id+'&cityid='+cityid;
        $.ajax 	({
            type: "POST",
            url: "RefreshSuburb.php",
            data: dataStrAutofill,
            cache: false,
            success: function(suburbArr) {
            var newsuburbArr = suburbArr;
                $("#suburbId option[value='" + newsuburbArr[0] + "']").attr("selected","selected");
                $('#subcity_txtbox').val(newsuburbArr[1]);
              }
	});
       }

}

function addupdatelocality()
{
	var id = $("#locality_txtbox_hidden").val();
	var label = $("#locality_txtbox").val();
	var label = label.replace("&", "@");
	var cityid = $("#cityId").val();
	var suburbId = $("#suburbId").val();
        
        //alert(id+'--'+label+'--'+cityid+'--'+suburbId);
	
	var xmlHttpadd1=GetXmlHttpObject();
	if (xmlHttpadd1==null)
	{
		alert ("Browser does not support HTTP Request");
		return false;
	}

	var rtrn = specialCharacterValidation($("#locality_txtbox").val());
	if(rtrn == false)
	{
		alert("Special Characters are not allowed");
		return false;
	}
	
	if(cityid == '')
	{
		alert("Please select city");
		return false;
	}
	else if(suburbId == '')
	{
		alert("Please select suburb");
		return false;
	}
	else if(label == '')
	{
		alert("Please enter locality");
		return false;
	}
	else
	{
		var url="addnewlocality.php?cityid="+cityid+"&subcityval="+suburbId+"&localityval="+label+"&id="+id;

		xmlHttpadd1.open("GET",url,false);
		xmlHttpadd1.send(null);
		var returnval=xmlHttpadd1.responseText;
		if(xmlHttpadd1)
		{
                    document.getElementById('mainlocality').innerHTML = returnval;
                    var localityselid=$("#localityId :selected").val();
                    displocality(localityselid);
                    alert("The record has been successfully updated.");
		}
	}

}


function deletelocality()
{
	if(confirm("Are you sure! you want to delete this record."))
	{
		cityid = $("#cityId").val();
		suburbId = $("#suburbId").val();
		localityId = $("#localityId").val();

		xmlHttpdeleteloc=GetXmlHttpObject();
		if (xmlHttpdeleteloc==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		if(localityId == '')
		{
			alert("Please select locality");
		}
		else
		{
			var url="addnewlocality.php?cid="+cityid+"&localitydelete="+localityId+"&subiddelete="+suburbId;
			//alert(url);

			xmlHttpdeleteloc.open("GET",url,false);
			xmlHttpdeleteloc.send(null);
			var returnval=xmlHttpdeleteloc.responseText;
			if(returnval)
			{
				document.getElementById('mainlocality').innerHTML = returnval;
				localityselid=$("#localityId :selected").val();
				displocality(localityselid);
				alert("The record has been successfully deleted.");
                                location.reload();
			}
		}

	}
	else
	{
		return false ;
	}
}

function specialCharacterValidation(fieldVal)
{
	var lengthStr = fieldVal.length;
	var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
	var flg = 0;
	for (var i = 0; i < lengthStr; i++) {
		var srch = iChars.search(fieldVal[i]);
	    if (srch != -1) {
	    		flg = 1;
	        }
	  }
	if(flg == 1)
		return false;
	else
		return true;
}

</script>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{$SITETITLE}</title>
<link href="{$FORUM_SERVER_PATH}css/css.css" rel="stylesheet" type="text/css">
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
  <tr>
    <td class="white-bg" align="center" bgcolor="#ffffff" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%">

      </table>
</TD>
</TR>
<TR>
<TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>

<TR>
<TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>

</TD>
<TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
<TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
<TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
<TR>
<TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
<TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
<TR>
<TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Add Quick City</TD>
<TD align=right colSpan=3></TD>
</TR>
</TBODY></TABLE>
</TD>
</TR>
<TR>
<TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
<table width="90%" border="0" align="centercityval" cellpadding="0" cellspacing="0" style="border:1px solid #c2c2c2;">
<tr class = 'headingrowcolor'>
	<th class=whiteTxt colspan="2" align="center" height="30px"></th>
	</tr>
<tr>
<td>
{if $accessCity == ''}

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <form name="frm">
    {if $ErrorMsg["errmsg"] != ''}
    <tr>
    <td align="center" colspan="3">
            <font color = 'red'>{$ErrorMsg["errmsg"]}</font>oncity_update</td>
    </tr>
    {/if}
    <tr><td height="15px" colspan="3"></td></tr>

    <tr>
    <td  height="25" align="left" style="padding-left:5px;">
    Add City:
            </td>
            <td height="50%" align="left">
                    <div id="maincity">
                            <select name="cityId" id = "cityId" class="cityId" onchange="dispcity(this.value);" STYLE="width: 150px">
                                    <option value =''>Select City</option>
                                     {section name=data loop=$CityDataArr}
                                    <option {if $cityId == {$CityDataArr[data].CITY_ID}} value ='{$cityId}' selected="selected" {else} value ='{$CityDataArr[data].CITY_ID}' {/if}>{$CityDataArr[data].LABEL}</option>
                                     {/section}
                            </select>
                    </div>
            </td>

            <td height="25" align="left">
                    <div id="maincity_txtbox">
                            <input type="hidden" name="city_txtbox_hidden" id="city_txtbox_hidden">
                            <input type="text" name="city_txtbox" id="city_txtbox" maxLength="40">
                    <a href="#" onclick="addupdatecity();"><b>Save</b></a>  | 
                    <a href="#" onclick="return deletecity();">Delete</a>
                    </div>
            </td>
    </tr>
    <tr ><th class=whiteTxt colspan="3" align="center" height="5px"></th></tr>
    
    <tr>
    <td  height="25" align="left" style="padding-left:5px;">
    Add locality:
                            </td>
                            <td height="50%" align="left">
                            <div id="mainlocality">
                            <select name="localityId" id = "localityId" class="localityId" onchange="displocality(this.value,1);" STYLE="width: 150px">
                                    <option value="">Select Locality</option>
                                    {if count($localitySelect) gt 0}

                                            {section name=data loop=$localitySelect}

                                            <option {if $localityId == {$localitySelect[data].LOCALITY_ID}} value = "{$localitySelect[data].LOCALITY_ID}" selected="selected" {else}  value = "{$localitySelect[data].LOCALITY_ID}" {/if}>{$localitySelect[data].LABEL}</option>
                                            {/section}
                                    {/if}
                            </select>
                            </div>
                            </td>
                            <td height="25" align="left">
                            <div  id="mainlocality_txtbox">
                                    <input type="hidden" name="locality_txtbox_hidden" id="locality_txtbox_hidden">
                                    <input type="text" name="locality_txtbox" id="locality_txtbox" maxLength="40" >
                                    <a href="#" onclick="addupdatelocality();"><b>Save</b></a>  | 
                                    <a href="#" onclick="return deletelocality();">Delete</a>
                            </div>
                    </tr>
    <tr><th class=whiteTxt colspan="3" align="center" height="5px"></th></tr>
     <tr>
    <td  height="25" align="left" style="padding-left:5px;">
    Add Suburb:
                            </td>
                            <td height="50%" align="left">
                            <div id="mainsubcity">
                            <select name="suburbId" id = "suburbId" class="suburbId" onchange="dispsubcity(this.value,1);" STYLE="width: 150px">
                            <option value="">Select Suburb</option>
                            {if count($suburbSelect) gt 0}
                                    {section name=data loop=$suburbSelect}
                                    <option {if $suburbId == {$suburbSelect[data].SUBURB_ID}} value = "{$suburbSelect[data].SUBURB_ID}" selected="selected" {else}  value = "{$suburbSelect[data].SUBURB_ID}" {/if}>{$suburbSelect[data].LABEL}</option>
                                    {/section}
                            {/if}
                            </select>
                            </div>
                            </td>
                            <td height="25" align="left">
                            <div id="mainsubcity_txtbox">
                                    <input type="hidden" name="subcity_txtbox_hidden" id="subcity_txtbox_hidden">
                                    <input type="text" name="subcity_txtbox" id="subcity_txtbox" maxLength="40">
                                    <a href="#" onclick="addupdatesubcity();"><b>Save</b></a>  | 
                                    <a href="#" onclick="return deletesubcity();">Delete</a>
                            </div>
                            </tr>
    <tr><th class=whiteTxt colspan="3" align="center" height="5px"></th></tr>
                            </form>
    </table>
{else}
    <font color = "red">No Access</font>
{/if}
</td>


</tr>

</table>


</body></html>