
<script type="text/javascript">

function refreshimg(ct)
{   	
	for(i=1;i<=30;i++)
	{
	 document.getElementById('img'+i).style.display='none';
	}	
	for(i=1;i<=ct;i++)
	{
	 document.getElementById('img'+i).style.display='';
	}		
}
$(document).ready(function(){
	
	 $('.taggedDate').hide();
	 
	if($('select#PType').val() == 'Construction Status'){
			$('.taggedDate').each(function(){
			  $(this).show();
			  if($(this).children('#tower_dropdown').length == 0){
				$(this).append('&nbsp;&nbsp;<b>Month:<font color = "red">*</font>&nbsp;&nbsp;');
				$(this).append($('#select_date').html());
				$(this).append('&nbsp;&nbsp;<b>Tower:&nbsp;&nbsp;');
				$(this).append($('#select_tower').html());
			 }
					
			});
	}
	
	 $('select#PType').change(function(k, v){
			$('.taggedDate').each(function(){
					 $(this).children().remove();$(this).html("");
			 });
			 if($(this).val() == 'Construction Status'){
				 $('.taggedDate').each(function(){
					 $(this).show();
					 if($(this).children('#tower_dropdown').length == 0){
						$(this).append('&nbsp;&nbsp;<b>Month:<font color = "red">*</font>&nbsp;&nbsp;');
						$(this).append($('#select_date').html());
						$(this).append('&nbsp;&nbsp;<b>Tower:&nbsp;&nbsp;');
						$(this).append($('#select_tower').html());
					 }
					
				 });
					
			 }
				         
	         if($('select#PType').val() == 'Project Image'){
				$('.taggedDate').each(function(){
				  $(this).show();
				  if($(this).children('#tower_dropdown').length == 0){
					$(this).append('&nbsp;&nbsp;<b>Display Order:&nbsp;&nbsp;');  
					$(this).append($('#select_display_order').html());
				  }
						
				});
			 }
	         
		});
});
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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18> Add New Project Plans({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
			 <form method="post" enctype="multipart/form-data" >
			  <TABLE cellSpacing=2 cellPadding=2 width="43%" align=center border=1 style = "border:1px solid;">
			   
			     
				<tr>
					<td  align = "center" colspan = "2">
						{if count($ErrorMsg)>0}
					   {foreach from=$ErrorMsg item=data}
					   <font color = "red">{$data}</font><br>
					   {/foreach}
					{/if}
					</td>
				</tr>

				<tr>
				  <td width="20%" align="right" ><b>Project Name :</b><font color = "red">*</font> </td>
				   <td width="30%" align="left" >
				   
					{ucwords($ProjectDetail[0]['PROJECT_NAME'])}
					<input type = "hidden" name = "projectId" value = "{$ProjectDetail[0]['PROJECT_ID']}">
					
				   </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><b>Image Type :</b><font color = "red">*</font></td>
				   <td width="30%" align="left" >
					<select name = "PType" id = "PType">
						<option value =''>Select Type</option>
                                                {if $linkShowHide == 0}
                                                    <option value ='Project Image' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Project Image'} selected {/if}>Project Image</option>
                                                    <option value ='Location Plan' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Location Plan'} selected {/if}>Location Plan</option>
                                                    <option value ='Layout Plan' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Layout Plan'} selected {/if}>Layout Plan</option>
                                                    <option value ='Site Plan' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Site Plan'} selected {/if}>Site Plan</option>
                                                    <option value ='Master Plan' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Master Plan'} selected {/if}>Master Plan</option>
                                                    <option value ='Cluster Plan' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Cluster Plan'} selected {/if}>Cluster Plan</option>
                                                    <option value ='Construction Status' {if $PType == 'Construction Status' || $imagetype == 'const'} selected {/if}>Construction Status</option>
                                                    <option value ='Payment Plan' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Payment Plan'} selected {/if}>Payment Plan</option>
                                                    <option value ='Specification' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Specification'} selected {/if}>Specification</option>
                                                    <option value ='Price List' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Price List'} selected {/if}>Price List</option>
                                                    <option value ='Application Form' {if $imagetype == 'const'} disabled="disabled" {/if} {if $PType == 'Application Form'} selected {/if}>Application Form</option>
						{else}
                                                    <option value ='Construction Status' {if $PType == 'Construction Status' || $imagetype == 'const'} selected {/if}>Construction Status</option>
                                                {/if}
					</select>	
                                        <input type="hidden" name = "linkShowHide" value="{$linkShowHide}">
				  </td>
				 
				</tr>

				 <tr>						
					<td width="20%" align="right" nowrap>
						<b>How many files would you like to upload?.</b>
						</td> 
						<td width="50%" nowrap>		
						
						<select name="img" onchange="refreshimg(this.value);">
							
							 <option {if $img == 1} value="1" selected="selected"{else} value="1" {/if}>1</option>
							 <option {if $img == 2} value="2" selected="selected"{else} value="2" {/if}>2</option> 
							 <option {if $img == 3} value="3" selected="selected"{else} value="3" {/if}>3</option> 
							 <option {if $img == 4} value="4" selected="selected"{else} value="4" {/if}>4</option> 
							  <option {if $img == 5} value="5" selected="selected"{else} value="5" {/if}>5</option> 
							 <option {if $img == 6} value="6" selected="selected"{else} value="6" {/if}>6</option> 
							 <option {if $img == 7} value="7" selected="selected"{else} value="7" {/if}>7</option> 
							  <option {if $img == 8} value="8" selected="selected"{else} value="8" {/if}>8</option> 
							 <option {if $img == 9} value="9" selected="selected"{else} value="9" {/if}>9</option> 
							 <option {if $img == 10} value="10" selected="selected"{else} value="10" {/if}>10</option>
							 
							  <option {if $img == 11} value="11" selected="selected"{else} value="11" {/if}>11</option>
							 <option {if $img == 12} value="12" selected="selected"{else} value="12" {/if}>12</option> 
							 <option {if $img == 13} value="13" selected="selected"{else} value="13" {/if}>13</option> 
							 <option {if $img == 14} value="14" selected="selected"{else} value="14" {/if}>14</option> 
							  <option {if $img == 15} value="15" selected="selected"{else} value="15" {/if}>15</option> 
							 <option {if $img == 16} value="16" selected="selected"{else} value="16" {/if}>16</option> 
							 <option {if $img == 17} value="17" selected="selected"{else} value="17" {/if}>17</option> 
							  <option {if $img == 18} value="18" selected="selected"{else} value="18" {/if}>18</option> 
							 <option {if $img == 19} value="19" selected="selected"{else} value="19" {/if}>19</option> 
							 <option {if $img == 20} value="20" selected="selected"{else} value="20" {/if}>20</option> 

							  <option {if $img == 21} value="21" selected="selected"{else} value="21" {/if}>21</option>
							 <option {if $img == 22} value="22" selected="selected"{else} value="22" {/if}>22</option> 
							 <option {if $img == 23} value="23" selected="selected"{else} value="23" {/if}>23</option> 
							 <option {if $img == 24} value="24" selected="selected"{else} value="24" {/if}>24</option> 
							  <option {if $img == 25} value="25" selected="selected"{else} value="25" {/if}>25</option> 
							 <option {if $img == 26} value="26" selected="selected"{else} value="26" {/if}>26</option> 
							 <option {if $img == 27} value="27" selected="selected"{else} value="27" {/if}>27</option> 
							  <option {if $img == 28} value="28" selected="selected"{else} value="28" {/if}>28</option> 
							 <option {if $img == 29} value="29" selected="selected"{else} value="29" {/if}>29</option> 
							 <option {if $img == 30} value="30" selected="selected"{else} value="30" {/if}>30</option> 
														
						</select>
							
						</td>									
					</td>				
				</tr>
			
				<tr>
				  <td width="20%" align="right" valign = "top" nowrap><b>Plan :</b> </td>
				  <td width="30%" align="left" nowrap>
				  <!-- this is for adding dynamically date dropdown-->
				  <div id="select_date" style="display:none">{$dateDiv}</div>
				   <!-- this is for adding dynamically tower dropdown-->
				  <div id="select_tower" style="display:none">{$towerDetailDiv}</div>
				   <!-- this is for adding dynamically display dropdown-->
				  <div id="select_display_order" style="display:none">{$display_order_div}</div>
				  
				 <!-- <input type=file name='txtlocationplan'  style="width:400px;">-->
				 <div id="img1"><input name="txtlocationplan[]" type="file" id='txtlocationplan1' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img2" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan2' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img3" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan3' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img4" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan4' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img5" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan5' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
						<div class="taggedDate"></div>
				  </div>
				  <div id="img6" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan6' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img7" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan7' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img8" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan8' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
						<div class="taggedDate"></div>
				  </div>
				  <div id="img9" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan9' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img10" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan10' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>

				  <div id="img11" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan11' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img12" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan12' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img13" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan13' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img14" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan14' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img15" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan15' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img16" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan16' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img17" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan17' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img18" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan18' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img19" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan19' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>

				  <div id="img20" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan20' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img21" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan21' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img22" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan22' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img23" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan23' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img24" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan24' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img25" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan25' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img26" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan26' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font><font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img27" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan27' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img28" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan28' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img29" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan29' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>
				  <div id="img30" style="display:none;"><input name="txtlocationplan[]" type="file" id='txtlocationplan30' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedDate"></div>
				  </div>

				</tr>
				<tr>
				  
				  <td colspan = "2" align="right" style="padding-left:152px;" >
				  <input type="hidden" name="edit_project" value="{$edit_project}" />
				  {if $edit_project == ''}
					  <input type="submit" name="Next" id="more" value="Add More" style = "font-size:16px;">
					   <input type="submit" name="Next" id="Next" value="Next" style = "font-size:16px;">
					  &nbsp;&nbsp;<input type="submit" name="Skip" id="Skip" value="Skip" style = "font-size:16px;">
				  {else}
						<input type="submit" name="Next" id="more" value="Add More" style = "font-size:16px;">
						<input type="submit" name="Next" id="more" value="Save" style = "font-size:16px;">
						<input type="submit" name="exit" id="exit" value="Exit" style = "font-size:16px;">
				  {/if}
				  </td>
				</tr>
			      </div>
			   
			    </TABLE>
<!--			</fieldset>-->
			 </form>
          </td>
		  </tr>
		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>


