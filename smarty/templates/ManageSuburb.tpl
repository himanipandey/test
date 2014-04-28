
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 

<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>





<script language="javascript">




function chkConfirm() 
	{
		return confirm("Are you sure! you want to delete this record.");
	}

function selectCity(value){
  
	document.getElementById('frmcity').submit();
	window.location.href="{$dirname}/suburbList.php?page=1&sort=all&citydd="+value;
}

//jQuery(document).ready(function(){



function showHier(){

   
    //var j = '{$suburb_str}';
    //var j = JSON.parse('{$suburb_str}'); 
   //var jsonstring = JSON.stringify(j);
   //alert(jsonstring);
   //jsonstring = jsonstring.replace(/"/g, "'");
   //alert(jsonstring);

  $.fancybox({
        'width'                :800,
        'height'               :1000,
        'scrolling'            : 'no',
        'href'                 : "/showHierarchy.php?cityid="+'{$cityId}',
        'type'                : 'iframe',
        
    })

  
}

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
    //Interne   t Explorer
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


function addupdatesubcity()
{
  //id = $("#subcity_txtbox_hidden").val();
  parent_id = $("#suburbId").val();
  cityid = $("#citydd").val();
  label = $("#subcity_txtbox").val();
  //alert("label:"+label+" name:"+name);
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
    var url="addnewsubcity.php?cityid="+cityid+"&subcityval="+label+"&parent_id="+parent_id;

    xmlHttpadd1.open("GET",url,false);
    xmlHttpadd1.send(null);
    var returnval=xmlHttpadd1.responseText;
    
                var stringSplitSuburb = new Array();
                 stringSplitSuburb = returnval.split("#"); 
                if(stringSplitSuburb.length >1) {
                    alert("This suburb already exist");
                }
    else if(xmlHttpadd1)
    {
                    document.getElementById('mainsubcity').innerHTML = stringSplitSuburb[0];
                    subcityselid=$("#suburbId :selected").val();                    
                    alert("The record has been successfully updated.");
                    window.location.reload(true);
    }

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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Suburb List</TD>
                      <td align = "right">{if $cityId != ''}<a href="suburbadd.php?c={$cityId}" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Suburb</b></a>{/if}</td>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                {if $accessSuburb == ''}
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="left">
                             {$Sorting}   <br> <br>
                             <form id="frm_build" method="post" action ="suburbList.php?page=1&sort=all">
                                 <b>Suburb URL :</b> <input name="suburbUrl" id="suburbUrl" value="{$suburbUrl}" class="button">
                                 <input type="submit" name="search" id="search" value="Search" class="button">
                             </form> <br>
                            </td>
							<td width="35%" height="25" align="right" valign="top">
                             <form name="frmcity" id="frmcity" method="post">
                                <select id="citydd" name="citydd" onchange="selectCity(this.value)">
                                       <option>select</option>
                                       {foreach from=$cityArray key=k item=v}
                                        <option  value="{$v.CITY_ID}" {if $cityId=={$v.CITY_ID}}  selected="selected" {/if}>{$v.LABEL}</option>
                                  {/foreach}
                                </select>
                           </form>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                    <form name="form1" method="post" action="">
                      <TBODY>
                      <TR class = "headingrowcolor">
                        <TD class=whiteTxt width=13% align="center">SUBURB NAME</TD>
                         <TD class=whiteTxt width=15% align="center">URL</TD>                          
			 <TD class=whiteTxt width=10% align="center">STATUS</TD>
                        <TD class=whiteTxt width=12% align="center">ACTION</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>
                      {$count = 0}
					  {section name=data loop=$localityDataArr}
					  
					  {$count = $count+1}
					  {if $count%2 == 0}
                       			
						{$color = "bgcolor = '#F7F7F7'"}
					  {else}                       			
						{$color = "bgcolor = '#FCFCFC'"}
					 {/if}	
                      <TR {$color}>
                        <TD align=left class=td-border>{if $localityDataArr[data].LABEL!=''}{$localityDataArr[data].LABEL}{else}<span align="center">-</span>{/if}</TD>
						
						<TD align=left class=td-border>{if $localityDataArr[data].URL!=''}{$localityDataArr[data].URL}{else}-{/if}</TD>

						<TD align=left class=td-border>{if $localityDataArr[data].STATUS!=''}{$localityDataArr[data].STATUS}{else}-{/if}</TD>

						 <TD  class="td-border" align=left>
                                                    <a href="suburbadd.php?suburbid={$localityDataArr[data].SUBURB_ID}&c={$localityDataArr[data].CITY_ID}" title="Edit">Edit</a>
                                                </TD>
                      </TR>
                       {/section}
                        {if $NumRows<=0}
							{if $cityId!="select"}
								<TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
							
							{else if $cityId=="" || $cityId=="select"}
								<TR><TD colspan="9" class="td-border" align="left">Please select atleast one option.</TD></TR>
							{/if}
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
                            <td width="77%" height="25" align="center">{$Pagginnation}
                              
                            </td>
                            <td align="right">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>

                    <tr>
    <td  height="25" align="right" style="padding-left:5px;">
    Add Suburb:
                            </td>
                           <td height="50%" align="left">
                            <div id="mainsubcity">
                             
                            <select name="suburbId" id = "suburbId" class="suburbId" STYLE="width: auto">
                            <option value="">Select Parent Suburb (optional)</option>
                            {foreach from=$suburbSelect key=k item=v}
                                           <option value="{$v.id}">{$v.label}</option>
                                       {/foreach}
                            </select> 
                            
                            </div>
                            </td>
                            <td height="25" align="left">
                            <div id="mainsubcity_txtbox">
                                    <input type="hidden" name="subcity_txtbox_hidden" id="subcity_txtbox_hidden">
                                    <input type="text" name="subcity_txtbox" id="subcity_txtbox" maxLength="40">
                                    <a href="#" onclick="addupdatesubcity();"><b>Save</b></a>  
                                    <a href="#" onclick="showHier();"><b>See Hierarchy</b></a>
                            </div>
                            </tr>
                            <tr>
                              <div>
                               <ul id="organisation">
               

                    </ul>
               
                        
            
            </div>
            
            

        </div>
                            </tr>

                  </table>                
		    {/if}
                 {else}
                     <font color = "red">No Access</font>
                 {/if}
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


