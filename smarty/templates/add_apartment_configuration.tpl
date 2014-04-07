  <script type="text/javascript" src="js/jquery.js"></script>

   <script type="text/javascript" src="js/apartmentConfiguration.js"></script>

   <script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

 <SCRIPT language=Javascript>

      function isNumberKey(evt)
      {

		
         var charCode = (evt.which) ? evt.which : event.keyCode;
		 if(charCode == 99 || charCode == 118)
        	 return true;
         if (charCode > 31 && (charCode < 46 || charCode > 57))
            return false;

         return true;
      }



	 function fillUnitName(id)
	 {
		var idValue=id+1;
		var selectValBed = $("#bed_"+idValue+" :selected").val();
		var selectValBath = $("#bathrooms_"+idValue+" :selected").val();
		var carpet_id = "txtCarpetAreaInfo_"+(idValue);
		if(selectValBed!='0' && selectValBath!='0')
		{
			var str=selectValBed+"BHK+"+selectValBath+"T";
			if($("#"+carpet_id).attr('checked'))
				var unitName=$("#txtUnitName_"+idValue).val(str+"(Carpet)");
			else
				var unitName=$("#txtUnitName_"+idValue).val(str);
		}
	 }

	 function show_add(id)
	 {
		var idValue=id+1;
		var id = "add_"+(id+1);
		var unitName=$("#txtUnitName_"+idValue).val();
		var txtPricePerUnitArea=$("#txtPricePerUnitArea_"+idValue).val();
		var txtSize=$("#txtSize_"+idValue).val();
		var selectVal = $("#bed_"+idValue+" :selected").val();
		if(unitName !='' && txtSize!='' && selectVal!='0')
		{
			document.getElementById(id).style.display = '';
		}
		else
		{
			document.getElementById(id).style.display = 'none';
		}

	 }
/*******function for deletion confirmation***********/
    function chkConfirm() 
    {
        var chk = 0;
        for(var i=0;i<=30;i++) {      
            if($("#"+i).attr('checked')) {
                chk = 1;
            }
        }

        if(chk == 1)
            return confirm("Are you sure! you want to delete records which are checked.");
    }
    
    function add_carpet(index){
		
		carpet_id = "txtCarpetAreaInfo_"+(index + 1);
		unitname_id = "txtUnitName_"+(index + 1);
		unitname_val = $("#"+unitname_id).val();
		
		if($("#"+carpet_id).attr('checked')){
			$("#"+unitname_id).val(unitname_val+"(Carpet)");
		}else{
			unitname_val = unitname_val.replace("(Carpet)","")
			$("#"+unitname_id).val(unitname_val);
		}
		
			
		
	}
    /******code for read only drop down if edit**********/
        function onChangeAction(id,edit_project) {
           var oldBed = id+"_old";
            var valOld = $("#"+oldBed).val();
            if (edit_project != '' && valOld != '0' && valOld != '') {
                $("#"+id).val(valOld); 
                return false;
          }
      }

      function onChangeActionBath(id,edit_project) {
           var oldBed = id+"_old";
            var valOld = $("#"+oldBed).val();
            if (edit_project != '' && valOld != '0' && valOld != '') {
                //$("#"+id).val(valOld); 
                return false;
          }
      };
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>{if $edit_project != ''} Edit {else}Add New{/if} Project Configuration({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

		  <form method="post" enctype="multipart/form-data">

		  <div id="mainDiv">
		<div id='roomCategory' style='display:none;' >
				<select name='roomCategory' >
				<option value=''>Select</option>
				{$j=0}
				{foreach from=$RoomCategoryArr key=k item=v}
				{if $j>1}
				{break}
				{/if}	<option value="{$k}">{$v}</option>

					{$j++}
				{/foreach}
				</select>
				</div>


<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->

			  <div style="overflow:auto;">
                      {$globalDelete  = 0}
                    {if $ProjectDetail[0]['PROJECT_TYPE_ID']==$typeA || $ProjectDetail[0]['PROJECT_TYPE_ID']==$typeVA || $ProjectDetail[0]['PROJECT_TYPE_ID']==$typePA || $ProjectDetail[0]['PROJECT_TYPE_ID'] == 0}
                      <TABLE cellSpacing=2 cellPadding=4 width="100%" align=center  style="border:1px solid #c2c2c2;">

                      <div>

                         {foreach from = $ErrorMsg  key=k item = datafirst}
                            <tr onmouseover="showHideDiv('row_{$k}',1);" onmouseout="showHideDiv('row_{$k}',2);">
                                    <th colspan="15" align = left><font color="red">{if  $k == 0} First row errors {else if $k == 1} Second row errors {else if $k == 2} Third row errors
                                    {else if $k == 3} Fourth row errors {else if $k == 4} Fifth row errors {else if $k == 5} Sixth row errors {else if $k == 6} Seventh row errors
                                    {else if $k == 7} Eighth row errors {else if $k == 8} Ninth row errors {else if $k == 9} Tenth row errors {/if}</font></th>

                            </tr>

                        <tr id="row_{$k}"><td colspan="15"><font color="red">{$datafirst}</font></td></tr>


                          {/foreach}

                        <tr><td colspan="17"><b><span style='font-size:15px;'>APARTMENTS</span></b></td></tr>
                        <tr><td colspan="17"><font color="red">{$projecteror} {if $projectId != ''}{$ErrorMsg1}{/if}</font></td></tr>
                        {foreach from = $ErrorMsg2  key=k1 item = v1}
                            <tr><td colspan="17"><font color="red">{$projecteror} {if $projectId != ''}Row {($v1['dupkey'])+1} is duplicate of Row {($v1['key'])+1} {/if}</font></td></tr>
                        {/foreach}
                        <tr class = "headingrowcolor" >
                          <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >
                             Delete
                          </td>  
                          <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
                          <td nowrap="nowrap" width="2%" align="left" class=whiteTxt>Room Sizes</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Bedrooms</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Bathrooms</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Unit Name</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Size</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Carpet Area Info</td>
                          <!-- <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Price Per Unit Area</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Price Per Unit Area DP</td>

                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Price Per Unit High</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt >Price Per Unit Low</td>
							
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Number of Floors</td>-->
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Balcony</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Study Room</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Servant Room</td>
                          <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Pooja Room</td>
                      
                        </tr>

                        {$var = 0}

                        {$looprange	=	15}
                        {$flg = 0}
                        {section name=foo start= 0 loop={$looprange} step=1}
                          
                            {$var	=$var+1}

                            {if $var%2 == 0}
                                {$color = "bgcolor = '#F7F7F7'"}
                            {else}
                                {$color = "bgcolor = '#FCFCFC'"}
                            {/if}

                            {if $txtUnitNameval[$smarty.section.foo.index] != ''}
                                {$flg = $flg+1}
                            {/if}
                        <tr {$color} id="row_{($smarty.section.foo.index+1)}">
                          <td align="center"><input type="checkbox" name="delete[{$globalDelete}]" id = "{$globalDelete}"></td>
                           {$globalDelete = $globalDelete+1}
                          <td align="center">
                                 {($smarty.section.foo.index+1)}
                          </td>
                           <td align="center">
                                 <span  {if ($txtUnitNameval[$smarty.section.foo.index] =='') && ($edit_project != '')} style = "display:none;" {/if}  id = "add_{($smarty.section.foo.index+1)}" class="insertProject" rel="{($smarty.section.foo.index+1)}"><a href='#' >Add</a></span>
                          </td>
                          <td align="center">
                              <input type = "hidden" name = "bed_{($smarty.section.foo.index+1)}_old" id = "bed_{($smarty.section.foo.index+1)}_old" value ="{$bedval[{$smarty.section.foo.index}]}">
                                <select id="bed_{($smarty.section.foo.index+1)}"  onchange = "onChangeAction('bed_{($smarty.section.foo.index+1)}',{$edit_project}), show_add({$smarty.section.foo.index}); fillUnitName({$smarty.section.foo.index});" tempName="bed" name = 'bed[]' style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (array_key_exists($var, $ErrorMsg)) && (({$bedval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$bedval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};">
                                    <option value = "0">Select</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                    <option {if $bedval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

                                </select>

                          </td>
                            
                          <td>
                              <input type = "hidden" name = "bathrooms_{($smarty.section.foo.index+1)}_old" id = "bathrooms_{($smarty.section.foo.index+1)}_old" value ="{$bathroomsval[{$smarty.section.foo.index}]}">
                                 <select id="bathrooms_{($smarty.section.foo.index+1)}"  onchange = "onChangeActionBath('bathrooms_{($smarty.section.foo.index+1)}',{$edit_project}), fillUnitName({$smarty.section.foo.index});" tempName="bathrooms" name = bathrooms[] style="border:1px solid #c3c3c3;">
                                    <option value = "0">Select</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                    <option {if $bathroomsval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

                                </select>

                          </td>

                          <td>
                                  <input type='hidden' value={$projectId} name='projectId' tempName="projectId"   />

                                  <input type='hidden' value="Apartment" name='unitType[]' tempName="unitType"   />
                                <input type = 'hidden' name = typeid_edit[] tempName="typeid_edit"  value="{$TYPE_ID[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$TYPE_ID[{$smarty.section.foo.index}]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};">
                                <input type=text onblur = "show_add({$smarty.section.foo.index});" tempName="txtUnitName"  name=txtUnitName[] id="txtUnitName_{($smarty.section.foo.index+1)}" value="{$txtUnitNameval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$txtUnitNameval[{$smarty.section.foo.index}]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "40">

                          </td>
                          <td align="left" >
                                <input onblur = "show_add({$smarty.section.foo.index});" onkeypress="return isNumberKey(event)"  type=text name=txtSize[] id="txtSize_{($smarty.section.foo.index+1)}"   tempName="txtSize" value="{$txtSizeval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtSizeval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$txtSizeval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
                          </td>
                          <td>
                                <input type="checkbox" onclick="add_carpet({($smarty.section.foo.index)})" name="txtCarpetAreaInfo_{($smarty.section.foo.index)}" id="txtCarpetAreaInfo_{($smarty.section.foo.index+1)}"   tempName="txtCarpetAreaInfo"  style="width:100px;border:1px solid"  maxlength = "10" {if $txtDisplayCarpetArea[{$smarty.section.foo.index}]} checked="checked"{/if}>

                          </td>
                          <!--
                          <td>
                              <input onblur = "show_add({$smarty.section.foo.index});" onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitArea[] id="txtPricePerUnitArea_{($smarty.section.foo.index+1)}"  tempName="txtPricePerUnitArea" value="{$txtPricePerUnitAreaval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtPricePerUnitAreaval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$txtPricePerUnitAreaval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};" readonly=""  maxlength = "10">
                          </td>
                          <td>
                                <input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitAreaDp[] tempName="txtPricePerUnitAreaDp" id="txtPricePerUnitAreaDp_{($smarty.section.foo.index+1)}" value="{$txtPricePerUnitAreaDpval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10"  readonly="">
                          </td>

                           <td>
                                <input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitHigh[] tempName="txtPricePerUnitHigh" id="txtPricePerUnitHigh_{($smarty.section.foo.index+1)}" value="{$txtPricePerUnitHighval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10"  readonly="">

                          </td>
                           <td>
                                <input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitLow[] tempName="txtPricePerUnitLow"  id="txtPricePerUnitLow_{($smarty.section.foo.index+1)}" value="{$txtPricePerUnitLowval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10"  readonly="">

                          </td> 

						  <td>
                                <input onkeypress="return isNumberKey(event)" type=text name=txtNoOfFloor[] tempName="txtNoOfFloor"  id="txtNoOfFloor" value="{$txtNoOfFloor[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;" maxlength = "5">

                          </td>
							-->
                                <input onkeypress="return isNumberKey(event)" type="hidden" name=txtVillaPlotArea[] tempName="txtVillaPlotArea"  id=txtVillaPlotArea value="{$txtVillaPlotArea[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">


                                <input onkeypress="return isNumberKey(event)" type="hidden" name=txtVillaFloors[] tempName="txtVillaFloors"  id=txtVillaFloors value="{$txtVillaFloors[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">

                                <input onkeypress="return isNumberKey(event)" type="hidden" name=txtVillaTerraceArea[] tempName="txtVillaTerraceArea"  id=txtVillaTerraceArea value="{$txtVillaTerraceArea[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">


                                <input onkeypress="return isNumberKey(event)" type="hidden" name=txtVillaGardenArea[] tempName="txtVillaGardenArea"  id=txtVillaGardenArea value="{$txtVillaGardenArea[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">

                           <td>
                                 <select tempName="Balconys" name = Balconys[] style="border:1px solid #c3c3c3;">
                                    <option value = "">Select</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                    <option {if $balconysval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>
                                </select>
                          </td>

                           <td>
                                 <select tempName="studyrooms" name = studyrooms[] style="border:1px solid #c3c3c3;">
                                    <option value = "">Select</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                    <option {if $studyroomsval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>
                                </select>
                          </td>
                           <td>
                                 <select tempName="servantrooms" name = servantrooms[] style="border:1px solid #c3c3c3;">
                                    <option value = "">Select</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                    <option {if $servantroomsval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>
                                </select>

                          </td>
                           <td>
                                 <select tempName="poojarooms"  name = poojarooms[] style="border:1px solid #c3c3c3;">
                                    <option value = "">Select</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                    <option {if $poojaroomsval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>
                                </select>
                          </td>
                                <input onkeypress="return isNumberKey(event)" type=hidden name=txtSizeLen[] id="txtSizeLen_{($smarty.section.foo.index+1)}" tempName="txtSizeLen" value="{$txtSizeLenval_P[$new_index]}" style="width:100px;border:1px solid #c3c3c3"  maxlength = "10">

                                <input onkeypress="return isNumberKey(event)" type=hidden name=txtSizeBre[] id="txtSizeBre_{($smarty.section.foo.index+1)}" tempName="txtSizeBre" value="{$txtSizeBreval_P[$new_index]}" style="width:100px;border:1px solid #FF0000"  maxlength = "10">

                                <input onkeypress="return isNumberKey(event)" type=hidden name=txtPlotArea[] tempName="txtPlotArea"  id=txtPlotArea value="{$txtPlotArea_P[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">



                        </tr>
                        {/section}

                        </div>
                       <div id='roomForm' ></div>
                       </TABLE>
                    {/if}
                    {if $ProjectDetail[0]['PROJECT_TYPE_ID']==$typeV || $ProjectDetail[0]['PROJECT_TYPE_ID']==$typeVA || $ProjectDetail[0]['PROJECT_TYPE_ID']==$typePV}
                      <TABLE cellSpacing=2 cellPadding=4 width="93%" align="center"  style="border:1px solid #c2c2c2;">
						  <tr><td colspan="17"><font color="red">{$projecteror} {if $projectId != ''}{$ErrorMsg1}{/if}</font></td></tr>

                      <div>

                              {foreach from = $ErrorMsg  key=k item = datafirst}
                                <tr onmouseover="showHideDiv('row_{$k}',1);" onmouseout="showHideDiv('row_{$k}',2);">
                                        <th colspan="15" align = left><font color="red">{if  $k == 0} First row errors {else if $k == 1} Second row errors {else if $k == 2} Third row errors
                                        {else if $k == 3} Fourth row errors {else if $k == 4} Fifth row errors {else if $k == 5} Sixth row errors {else if $k == 6} Seventh row errors
                                        {else if $k == 7} Eighth row errors {else if $k == 8} Ninth row errors {else if $k == 9} Tenth row errors {/if}</font></th>

                                </tr>

                            <tr id="row_{$k}" ><td colspan="15"><font color="red">{$datafirst}</font></td></tr>


                              {/foreach}
                            <tr><td colspan="16"><b><span style='font-size:15px;'>VILLAS</span></b></td></tr>
                            <tr><td colspan="16"></td></tr>
                            <tr class = "headingrowcolor" >
                              <td  nowrap="nowrap" width="1%" align="center" class="whiteTxt">Delete</td>
                              <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
                              <td nowrap="nowrap" width="7%" align="left" class=whiteTxt>Room Sizes</td>
                               <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Bedrooms</td>
                              <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Bathrooms</td>
                              <td nowrap="nowrap" width="7%" align="left" class=whiteTxt><font color = red>*</font>Unit Name</td>
                              <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Size</td>
                              <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Carpet Area Info</td>
                              <!-- <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit Area</td>
                              <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit Area DP</td>

                               <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit High</td>
                              <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Price Per Unit Low</td>
                               <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Villa floors</td> -->
                               <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Villa Plot Area</td>
                              <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Villa Terrace Area</td>
                              <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Villa Garden Area</td>
                              <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Balcony</td>

                               <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Study Room</td>
                               <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Servant Room</td>
                                <td nowrap="nowrap" width="3%" align="left" class="whiteTxt">Pooja Room</td>
                             <!--   <td nowrap="nowrap" width="3%" align="left" class="whiteTxt">Property Status</td> -->

                            </tr>

                            {section name=foo start= {$looprange} loop={$looprange+15} step=1}

                                {$var	=$var+1}

                                {if $var%2 == 0}
                                    {$color = "bgcolor = '#F7F7F7'"}
                                {else}
                                    {$color = "bgcolor = '#FCFCFC'"}
                                {/if}



                            <tr {$color} id="row_{($smarty.section.foo.index+1)}">
                              <td align="center"><input type="checkbox" name="delete[{$globalDelete}]"  id = "{$globalDelete}"></td>
                              {$globalDelete = $globalDelete+1}
                              <td align="center">
                                     {($smarty.section.foo.index+1)}
                              </td>
                              <td align="center">
                               {if $flg != 0}
                                    {$new_index = $smarty.section.foo.index-15}
                                {else}
                                    {$new_index = $smarty.section.foo.index}
                                {/if}
                                     <span {if ($txtUnitNameval_VA[$new_index] =='') && ($edit_project == '')}style = "display:none;" {/if} id = "add_{($smarty.section.foo.index+1)}" class="insertProject" rel="{($smarty.section.foo.index+1)}"><a href='#' >Add</a></span>
                              </td>
                              <td align="center">
                                    
                                  <input type = "hidden" name = "bed_{($smarty.section.foo.index+1)}_old" id = "bed_{($smarty.section.foo.index+1)}_old" value ="{$bedval_VA[$new_index]}">
                                  
                                    <select id="bed_{($smarty.section.foo.index+1)}"  onchange = "onChangeAction('bed_{($smarty.section.foo.index+1)}',{$edit_project}), show_add({$smarty.section.foo.index}); fillUnitName({$smarty.section.foo.index});" tempName="bed" name = 'bed[]' style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$bedval_VA[$new_index]} == '') OR !is_numeric({$bedval_VA[$new_index]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};">
                                        <option value = "0">Select</option>
                                        <option {if $bedval_VA[$new_index] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                        <option {if $bedval_VA[$new_index] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                        <option {if $bedval_VA[$new_index] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                        <option {if $bedval_VA[$new_index] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                        <option {if $bedval_VA[$new_index] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                        <option {if $bedval_VA[$new_index] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                        <option {if $bedval_VA[$new_index] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                        <option {if $bedval_VA[$new_index] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                        <option {if $bedval_VA[$new_index] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                        <option {if $bedval_VA[$new_index] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

                                    </select>

                              </td>
                              <input type = "hidden" name = "bathrooms_{($smarty.section.foo.index+1)}_old" id = "bathrooms_{($smarty.section.foo.index+1)}_old" value ="{$bathroomsval_VA[$new_index]}">
                              <td>
                                     <select id="bathrooms_{($smarty.section.foo.index+1)}"  onchange = "onChangeActionBath('bathrooms_{($smarty.section.foo.index+1)}', {$edit_project}),fillUnitName({$smarty.section.foo.index});" tempName="bathrooms" name = bathrooms[] style="border:1px solid #c3c3c3;">
                                        <option value = "0">Select</option>
                                        <option {if $bathroomsval_VA[$new_index] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                        <option {if $bathroomsval_VA[$new_index] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                        <option {if $bathroomsval_VA[$new_index] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                        <option {if $bathroomsval_VA[$new_index] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                        <option {if $bathroomsval_VA[$new_index] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                        <option {if $bathroomsval_VA[$new_index] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                        <option {if $bathroomsval_VA[$new_index] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                        <option {if $bathroomsval_VA[$new_index] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                        <option {if $bathroomsval_VA[$new_index] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                        <option {if $bathroomsval_VA[$new_index] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>



                                    </select>

                              </td>

                              <td>
                                    <input type='hidden' value={$projectId} name='projectId' tempName="projectId"   />

                                    <input type='hidden' value="Villa" name='unitType[]' tempName="unitType"   />
                                    <input type = 'hidden' name = typeid_edit[] tempName="typeid_edit"  value="{$TYPE_ID_VA[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$TYPE_ID_VA[$new_index]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};">
                                    <input type=text onblur = "show_add({$smarty.section.foo.index});" tempName="txtUnitName"  name=txtUnitName[] id="txtUnitName_{($smarty.section.foo.index+1)}" value="{$txtUnitNameval_VA[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$txtUnitNameval_VA[$new_index]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "40">

                              </td>
                              <td align="left" >
                                    <input onblur = "show_add({$smarty.section.foo.index});" onkeypress="return isNumberKey(event)"  type=text name=txtSize[] id="txtSize_{($smarty.section.foo.index+1)}"   tempName="txtSize" value="{$txtSizeval_VA[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtSizeval_VA[$new_index]} == '') OR !is_numeric({$txtSizeval_VA[$new_index]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
                              </td>
                              <td>
                                <input type="checkbox" onclick="add_carpet({($smarty.section.foo.index)})" name="txtCarpetAreaInfo_{($smarty.section.foo.index)}" id="txtCarpetAreaInfo_{($smarty.section.foo.index+1)}"   tempName="txtCarpetAreaInfo"  style="width:100px;border:1px solid"  maxlength = "10" {if $txtCarpetAreaInfo_VA[{$new_index}]} checked="checked"{/if}>

                              </td>
                              <!--
                              <td>
                                    <input onblur = "show_add({$smarty.section.foo.index});" onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitArea[] id="txtPricePerUnitArea_{($smarty.section.foo.index+1)}"  tempName="txtPricePerUnitArea" value="{$txtPricePerUnitAreaval_VA[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtPricePerUnitAreaval_VA[$new_index]} == '') OR !is_numeric({$txtPricePerUnitAreaval_VA[$new_index]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10" readonly="">
                              </td>
                              <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitAreaDp[] tempName="txtPricePerUnitAreaDp" id=txtPricePerUnitAreaDp value="{$txtPricePerUnitAreaDpval_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10" readonly="">
                              </td>

                               <td>
                                    <input onkeypress=11"return isNumberKey(event)" type=text name=txtPricePerUnitHigh[] tempName="txtPricePerUnitHigh" id=txtPricePerUnitHigh value="{$txtPricePerUnitHighval_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10" readonly="">

                              </td>
                               <td   >
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitLow[] tempName="txtPricePerUnitLow"  id=txtPricePerUnitLow value="{$txtPricePerUnitLowval_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10" readonly="">

                              </td>

							  <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtVillaFloors[] tempName="txtVillaFloors"  id=txtVillaFloors value="{$txtVillaFloors_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">

                          </td> -->

                                    <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtVillaPlotArea[] tempName="txtVillaPlotArea"  id=txtVillaPlotArea value="{$txtVillaPlotArea_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">

                              </td>

                               


                               <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtVillaTerraceArea[] tempName="txtVillaTerraceArea"  id=txtVillaTerraceArea value="{$txtVillaTerraceArea_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">

                              </td>

                               <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtVillaGardenArea[] tempName="txtVillaGardenArea"  id=txtVillaGardenArea value="{$txtVillaGardenArea_VA[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">

                              </td>

                               <td>
                                     <select tempName="Balconys" name = Balconys[] style="border:1px solid #c3c3c3;">
                                        <option value = "">Select</option>
                                        <option {if $balconysval_VA[$new_index] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                        <option {if $balconysval_VA[$new_index] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                        <option {if $balconysval_VA[$new_index] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                        <option {if $balconysval_VA[$new_index] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                        <option {if $balconysval_VA[$new_index] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                        <option {if $balconysval_VA[$new_index] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                        <option {if $balconysval_VA[$new_index] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                        <option {if $balconysval_VA[$new_index] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                        <option {if $balconysval_VA[$new_index] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                        <option {if $balconysval_VA[$new_index] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

                                    </select>

                              </td>

                               <td>
                                     <select tempName="studyrooms" name = studyrooms[] style="border:1px solid #c3c3c3;">
                                        <option value = "">Select</option>
                                        <option {if $studyroomsval_VA[$new_index] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                        <option {if $studyroomsval_VA[$new_index] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                        <option {if $studyroomsval_VA[$new_index] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                        <option {if $studyroomsval_VA[$new_index] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                        <option {if $studyroomsval_VA[$new_index] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                        <option {if $studyroomsval_VA[$new_index] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                        <option {if $studyroomsval_VA[$new_index] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                        <option {if $studyroomsval_VA[$new_index] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                        <option {if $studyroomsval_VA[$new_index] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                        <option {if $studyroomsval_VA[$new_index] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

                                    </select>

                              </td>
                               <td>
                                     <select tempName="servantrooms" name = servantrooms[] style="border:1px solid #c3c3c3;">
                                        <option value = "">Select</option>
                                        <option {if $servantroomsval_VA[$new_index] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                        <option {if $servantroomsval_VA[$new_index] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                        <option {if $servantroomsval_VA[$new_index] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                        <option {if $servantroomsval_VA[$new_index] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                        <option {if $servantroomsval_VA[$new_index] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                        <option {if $servantroomsval_VA[$new_index] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                        <option {if $servantroomsval_VA[$new_index] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                        <option {if $servantroomsval_VA[$new_index] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                        <option {if $servantroomsval_VA[$new_index] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                        <option {if $servantroomsval_VA[$new_index] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>
                                    </select>

                              </td>
                               <td>
                                     <select tempName="poojarooms"  name = poojarooms[] style="border:1px solid #c3c3c3;">
                                        <option value = "">Select</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
                                        <option {if $poojaroomsval_VA[$new_index] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

                                    </select>

                              </td>
                              <td>
                    <!--  <select tempName="propstatus"  name = propstatus[] style="border:1px solid #c3c3c3;">
                          <option value = "Available">Select</option>
                          <option {if $statusval_VA[$new_index] == 'Available'} selected ="selected" {/if} value = "Available">Available</option>
                          <option {if $statusval_VA[$new_index] == 'Sold Out'} selected ="selected" {/if} value = "Sold Out">Sold Out</option>
                      </select> -->
                    </td>

                                <input onkeypress="return isNumberKey(event)" type=hidden name=txtSizeLen[] id="txtSizeLen_{($smarty.section.foo.index+1)}" tempName="txtSizeLen" value="{$txtSizeLenval_P[$new_index]}" style="width:100px;border:1px solid #c3c3c3"  maxlength = "10">

                                <input onkeypress="return isNumberKey(event)" type=hidden name=txtSizeBre[] id="txtSizeBre_{($smarty.section.foo.index+1)}" tempName="txtSizeBre" value="{$txtSizeBreval_P[$new_index]}" style="width:100px;border:1px solid #FF0000"  maxlength = "10">

                                <input onkeypress="return isNumberKey(event)" type=hidden name=txtPlotArea[] tempName="txtPlotArea"  id=txtPlotArea value="{$txtPlotArea_P[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">



                            </tr>
                            {/section}

                        </div>
                        <div id='roomForm' ></div>
                    </TABLE>
                    {/if}

                    {if $ProjectDetail[0]['PROJECT_TYPE_ID']== $typeP || $ProjectDetail[0]['PROJECT_TYPE_ID']==$typePA || $ProjectDetail[0]['PROJECT_TYPE_ID']==$typePV || $ProjectDetail[0]['PROJECT_TYPE_ID']== $typeC}
                        {$typeName = 'Plot'}
                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']== $typeC}
                            {$typeName = 'Commercial'}
                        {/if}
                      <br />
                      <TABLE cellSpacing=2 cellPadding=4 width="60%" align="left" style="border:1px solid #c2c2c2;" border="0">
                        <tr><td colspan="17"><font color="red">{$projecteror} {if $projectId != ''}{$ErrorMsg1}{/if}</font></td></tr>
                        <div>  {foreach from = $ErrorMsg  key=k item = datafirst}
                                <tr onmouseover="showHideDiv('row_{$k}',1);" onmouseout="showHideDiv('row_{$k}',2);">
                                        <th colspan="15" align = left><font color="red">{if  $k == 0} First row errors {else if $k == 1} Second row errors {else if $k == 2} Third row errors
                                        {else if $k == 3} Fourth row errors {else if $k == 4} Fifth row errors {else if $k == 5} Sixth row errors {else if $k == 6} Seventh row errors
                                        {else if $k == 7} Eighth row errors {else if $k == 8} Ninth row errors {else if $k == 9} Tenth row errors {/if}</font></th>

                                </tr>

                            <tr id="row_{$k}" ><td colspan="15"><font color="red">{$datafirst}</font></td></tr>


                              {/foreach}
                            <tr><td colspan="7"><b><span style='font-size:15px;'>{$typeName}</span></b></td></tr>

                            <tr><td colspan="7"></td></tr>

                            <tr class = "headingrowcolor" >
                                 <td  nowrap="nowrap" width="1%" align="center" class="whiteTxt">Delete</td>
                                <td nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
                                <td nowrap="nowrap" width="7%" align="left" class=whiteTxt><font color = red>*</font>Unit Name</td>
                                <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Size(Length)</td>
                                <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Size(Breadth)</td>
                                <!-- <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit Area</td> -->
                                <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Area</td>
                                <!-- <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Property Status</td> -->
                            </tr>

                            {section name=foo start= {$looprange} loop={$looprange+15} step=1}
                                {$var	=$var+1}
                                
                            <tr {$color} id="row_{($smarty.section.foo.index+1)}">
                               <td align="center"><input type="checkbox" name="delete[{$globalDelete}]" id = "{$globalDelete}"></td>
                               {$globalDelete = $globalDelete+1}
                               <td align="center">
                                     {($smarty.section.foo.index+1)}
                              </td>
							   {if $flg != 0}
                                    {$new_index = $smarty.section.foo.index-15}
                                {else}
                                    {$new_index = $smarty.section.foo.index}
                                {/if}
                              <!--<td align="center">
                                {if $flg != 0}
                                    {$new_index = $smarty.section.foo.index-15}
                                {else}
                                    {$new_index = $smarty.section.foo.index}
                                {/if}
                                     <span {if ($txtUnitNameval_P[$new_index] =='') && ($edit_project == '')}style = "display:none;" {/if} id = "add_{($smarty.section.foo.index+1)}" class="insertProject" rel="{($smarty.section.foo.index+1)}"><a href='#' >Add</a></span>
                              </td>-->
                              <td>
                                    <input type='hidden' value={$projectId} name='projectId' tempName="projectId"   />
                                    <input type='hidden' value='{$typeName}' name='unitType[]' tempName="unitType"   />
                                    <input type = 'hidden' name = typeid_edit[] tempName="typeid_edit"  value="{$TYPE_ID_P[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$TYPE_ID_P[$new_index]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};">
                                    <input type=text tempName="txtUnitName"  name=txtUnitName[] id="txtUnitName_{($smarty.section.foo.index+1)}" value="{$txtUnitNameval_P[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$txtUnitNameval_P[$new_index]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "40">
                              </td>
                              <td align="left" >
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtSizeLen[] id="txtSizeLen_{($smarty.section.foo.index+1)}" tempName="txtSizeLen" value="{$txtSizeLenval_P[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtSizeLenval_P[$new_index]} == '') OR !is_numeric({$txtSizeLenval_P[$new_index]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
                              </td>
                              <td align="left" >
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtSizeBre[] id="txtSizeBre_{($smarty.section.foo.index+1)}" tempName="txtSizeBre" value="{$txtSizeBreval_P[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtSizeBreval_P[$new_index]} == '') OR !is_numeric({$txtSizeBreval_P[$new_index]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
                              </td>
                              <!--
                              <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitArea[] id="txtPricePerUnitArea{($smarty.section.foo.index+1)}"  tempName="txtPricePerUnitArea_P" value="{$txtPricePerUnitAreaval_P[$new_index]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtPricePerUnitAreaval_P[$new_index]} == '') OR !is_numeric({$txtPricePerUnitAreaval_P[$new_index]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10" readonly="">
                              </td>
                              -->
                              <td>
                                    <input onkeypress="return isNumberKey(event)" type=text name=txtPlotArea[] tempName="txtPlotArea"  id=txtPlotArea value="{$txtPlotArea_P[$new_index]}" style="width:100px;border:1px solid #c3c3c3;"  maxlength = "10">
                              </td>
                              <!--
                              <td>
                                <select tempName="propstatus"  name = propstatus[] style="border:1px solid #c3c3c3;">
                                    <option value = "Available">Select</option>
                                    <option {if $statusval_P[$new_index] == 'Available'} selected ="selected" {/if} value = "Available">Available</option>
                                    <option {if $statusval_P[$new_index] == 'Sold Out'} selected ="selected" {/if} value = "Sold Out">Sold Out</option>
                                </select>
                              </td>
                              -->
                            </tr>
                        {/section}

                        </div>
                        <div id='roomForm' ></div>
                    </TABLE>
                    {/if}

          <table width = "100%">
            <tr class = "headingrowcolor">
                <td align="left">
                 <input type = "hidden" name = "cityId" id = "cityId" value = "{$ProjectDetail[0]['CITY_ID']}">
                 <input type="hidden" name="projectId" value="{$projectId}" />
                 <input type="hidden" name="oldbuilderId" value="{$builderId}" />
                 {if $edit_project == ''}
                  <input type="submit" name="Skip" id="Skip" value="Skip" />
                 {/if}

                 <input type="submit" name="btnSave" id="btnSave" {if $edit_project == ''} value="Next" {else} value="Save" {/if} onclick = "return chkConfirm();" />
								 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
							 </td>
						</tr>
					</table>

				</div>
				</div>

				</form>
<!--			</fieldset>-->

       </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>
