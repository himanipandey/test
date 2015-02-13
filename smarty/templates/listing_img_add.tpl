<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
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


function isNumeric(val) {
        var validChars = '0123456789.';
        var validCharsforfirstdigit = '-01234567890';
        if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
                return false;
        

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}


function appendToNo(no){
	var returnVal;
	if(isNumeric(no) && no.trim()!="")
	{
		var mod = no%100;
		if(mod==0) returnVal="ground";
		else if(mod==1) returnVal=mod+"st";
		else if(mod==2) returnVal=mod+"nd";
		else if(mod==3) returnVal=mod+"rd";
		else returnVal=mod+"th";
		
	}
	else
		returnVal = no;
	return returnVal
}

function validateFloor(from, to){
	var returnVal
	
	if(parseInt(to)>parseInt(from)){ returnVal="true";}
	else returnVal="false";
	
	return returnVal;
	

}


function amenities_change(e){
 ///*	
    var indx = $('.amenitiesType').index($(e));
	var amenity_val = e.value;
	//var index = $(e).attr('name').match(/\[(.*?)\]/)[1];
	//console.log(e.name);
	
		
		
		
		$('input[name= "title[]"]').each(function(index, elm){
			if(indx == index+1){
				if(amenity_val!==''){
					$(this).val(amenity_val);
				}
				else{
					$(this).val();
				}

			}
				
		});
//*/
	
}


$(document).ready(function(){
	
	 $('.taggedDate').hide();
	  $('.taggedMonth').hide();
	  $('input[name= "title[]"]').each(function(index, elm){
					
					 $(this).val($('select#PType').val());
					$(this).attr("readonly", true);
					
					
					

				});

	var itype = $('select#PType').val();	         
	
 	
			$('.taggedDate').each(function(){
			  $(this).show();
			  if($(this).children('#tower_dropdown').length == 0){
				$(this).append('&nbsp;&nbsp;<b>Display Order:&nbsp;&nbsp;');  
				$(this).append($('#select_display_order').html());
				
			  }
			  

					
			});
	

	
	 $('select#PType').change(function(k, v){
	 			$('input[name= "title[]"]').each(function(index, elm){
					$(this).val($('select#PType').val());
					$(this).attr("readonly", false);
				});
	 	
			$('.taggedDate').each(function(){
					 $(this).children().remove();$(this).html("");
			 });
			 $('.taggedMonth').each(function(){
						
					 $(this).hide();	
				})
			 
		var itype = $('select#PType').val();	         
				$('.taggedDate').each(function(){
				  $(this).show();
				  if($(this).children('#tower_dropdown').length == 0){
					$(this).append('&nbsp;&nbsp;<b>Display Order:&nbsp;&nbsp;');  
					$(this).append($('#select_display_order').html());
				  }
						
				});
			 
	   

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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18> Add New Listing Images({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
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
					   <font color = "red" style="font-size:17px">{$data}</font><br>
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
					{foreach $listing_image_types key=k item=v}
						
                        <option value ='{$v}' {if $PType == $v} selected {/if}>{$v}</option>
                                                   
						
                    {/foreach}   
					</select>	
                      
				 
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
				   <!-- this is for adding dynamically tower dropdown-->
				  <div id="select_tower" style="display:none">{$towerDetailDiv}</div>
				   <!-- this is for adding dynamically display dropdown-->
				  <div id="select_display_order" style="display:none">{$display_order_div}</div>
				  <!-- this is for adding dynamically floor dropdown-->

				   <div style="display:none" id="amenitiesTypeDiv">
				 &nbsp;&nbsp;&nbsp;&nbsp; <b>Amenities Type :</b><font color = "red">*</font> 
				  
				   	<select name = "SType[]" class = "amenitiesType" onchange="amenities_change(this)">
						<option value =''>Select Amenities Type</option>
						{foreach  from=$amenities key=k item=v}
                          <option >{$v}</option>
						{/foreach}
				   </select>
				 </div>

				  <div id="select_floor" style="display:none">
				  	&nbsp;&nbsp;<b>Floor No. From:<font color = "red"></font></b>&nbsp;&nbsp;
						<input name="floor_from[]" type="text" class="formstyle2" id="floor_from" size="10"  onchange="floor_change_from(this)" />	
					&nbsp;&nbsp;<b>Floor No. To:<font color = "red"></font></b>&nbsp;&nbsp;
						<input name="floor_from[]" type="text" class="formstyle2" id="floor_to" size="10"  onchange="floor_change_to(this)" />
					</div>
				  
				 <!-- <input type=file name='txtlocationplan'  style="width:400px;">-->
				 <div id="img1" style="margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan1' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]" readonly="1">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" size="10"  onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>					
				  </div>
				  <div id="img2" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan2' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date2" type="text" class="formstyle2" id="img_date2" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger2" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>	
				  </div>
				  <div id="img3" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan3' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
					<div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date3" type="text" class="formstyle2" id="img_date3" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger3" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>	
				  </div>
				  <div id="img4" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan4' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				  <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date4" type="text" class="formstyle2" id="img_date4" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger4" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img5" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan5' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				  <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date5" type="text" class="formstyle2" id="img_date5" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger5" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
						<div class="taggedDate"></div>
				  </div>
				  <div id="img6" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan6' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date6" type="text" class="formstyle2" id="img_date6" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger6" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img7" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan7' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date7" type="text" class="formstyle2" id="img_date7" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger7" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img8" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan8' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date8" type="text" class="formstyle2" id="img_date8" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger8" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
						<div class="taggedDate"></div>
				  </div>
				  <div id="img9" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan9' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date9" type="text" class="formstyle2" id="img_date9" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger9" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img10" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan10' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date10" type="text" class="formstyle2" id="img_date10" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger10" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>

				  <div id="img11" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan11' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date11" type="text" class="formstyle2" id="img_date11" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger11" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img12" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan12' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date12" type="text" class="formstyle2" id="img_date12" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger12" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img13" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan13' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date13" type="text" class="formstyle2" id="img_date13" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger13" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img14" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan14' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date14" type="text" class="formstyle2" id="img_date14" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger14" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img15" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan15' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date15" type="text" class="formstyle2" id="img_date15" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger15" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img16" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan16' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date16" type="text" class="formstyle2" id="img_date16" readonly="1" size="10" onchange="tagged_date_change(this)"/>  <img src="../images/cal_1.jpg" id="img_date_trigger16" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img17" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan17' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date17" type="text" class="formstyle2" id="img_date17" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger17" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img18" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan18' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date18" type="text" class="formstyle2" id="img_date18" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger18" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img19" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan19' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date19" type="text" class="formstyle2" id="img_date19" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger19" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>

				  <div id="img20" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan20' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date20" type="text" class="formstyle2" id="img_date20" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger20" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img21" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan21' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date21" type="text" class="formstyle2" id="img_date21" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger21" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img22" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan22' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date22" type="text" class="formstyle2" id="img_date22" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger22" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img23" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan23' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date23" type="text" class="formstyle2" id="img_date23" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger23" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img24" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan24' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date24" type="text" class="formstyle2" id="img_date24" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger24" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img25" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan25' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date25" type="text" class="formstyle2" id="img_date25" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger25" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img26" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan26' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font><font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date26" type="text" class="formstyle2" id="img_date26" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger26" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img27" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan27' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date27" type="text" class="formstyle2" id="img_date27" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger27" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img28" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan28' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date28" type="text" class="formstyle2" id="img_date28" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger28" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img29" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan29' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date29" type="text" class="formstyle2" id="img_date29" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger29" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
					<div class="taggedDate"></div>
				  </div>
				  <div id="img30" style="display:none;margin-bottom:10px;"><input name="txtlocationplan[]" type="file" id='txtlocationplan30' class="imgup"/>&nbsp;&nbsp;<b>Title:<font color = "red">*</font></b>&nbsp;&nbsp;<input type = "text" name = "title[]">
				   <div class="taggedMonth" style="display:block;float:left">
					   &nbsp;&nbsp;<b>Tagged Date:<font color = "red">*</font></b>&nbsp;&nbsp;
						<input name="img_date30" type="text" class="formstyle2" id="img_date30" readonly="1" size="10" onchange="tagged_date_change(this)" />  <img src="../images/cal_1.jpg" id="img_date_trigger30" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
					</div>
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


		 <!-- image edit  --> 

		<TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
				  <TABLE cellSpacing=2 cellPadding=2 width="100%" align=center border=1 style = "border:1px solid;">
					<form method="post" enctype="multipart/form-data">
					<tr>
					<td  align = "left" colspan = "2">
						{if count($ErrorMsgEdit)>0}
					   {foreach from=$ErrorMsgEdit item=data}
					   <font color = "red" style="font-size:17px;">{$data}</font><br>
					   {/foreach}
					{/if}
					</td>
				</tr>
					<tr>
						<td width="100%" align="left" >
				  
						<div id="imagesDiv">
						Delete all: <input type='checkbox'  id="hdnCheckUncheck" value='0' name='checkall' onclick=" checkednewAll()">
							<br>
						<form name="f1" id="f1" method="post" action ="" enctype = "multipart/form-data">
						   <input type="hidden" name="listing_edit" value="yes" >
							<table cellpadding=0 cellspacing=1>
								<tr>
									<td>
										<table  cellpadding=0 cellspacing=1 bgcolor='#c2c2c2'>
											<tr bgcolor='#ffffff'>
									
												{$cnt = 0}
												{$date_cnt = 0}
												{section name=data loop=$ImageDataListingArr}
						
												<td class = "tdcls_{$cnt}" >
													<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
														
															<a class="pt_reqflrplan" href="{$ImageDataListingArr[data].SERVICE_IMAGE_PATH}
															
															" target="_blank">
																<img src="{$ImageDataListingArr[data].SERVICE_IMAGE_PATH}?width=130&height=100" height="70px" width="70px" title="{$ImageDataListingArr[data].SERVICE_IMAGE_PATH}" alt="{$ImageDataListingArr[data].alt_text}" />
															</a>
															<br>
														Image Type:{$ImageDataListingArr[data].PLAN_IMAGE}<input type = "text" readonly name = "PType[{$cnt}]" id="PType{$cnt}"
														value = "{$ImageDataListingArr[data].PLAN_TYPE}"
														STYLE="width: 165px;border:1px solid #c3c3c3;">
                                                                                                                
                                                                                                                <input type = "hidden" name = "currentPlanId[{$cnt}]"
														value = "{$ImageDataListingArr[data].PROJECT_PLAN_ID}">

														<input type="hidden" value="{$path}{$ImageDataListingArr[data].PLAN_IMAGE}" name="property_image_path[{$cnt}]" /><br><br>
                                                        <input type="hidden" value="{$ImageDataListingArr[data].SERVICE_IMAGE_ID}" name="service_image_id[{$cnt}]" />
														Image Title:<font color = "red">*</font><input type="text" name="title[{$cnt}]" {if $ImageDataListingArr[data].PLAN_TYPE != 'Cluster Plan'}readonly="readonly"{/if} value = "{$ImageDataListingArr[data].TITLE}"  STYLE="width: 165px;border:1px solid #c3c3c3;" id="title{$cnt}"/><br><br>
														{if $ImageDataListingArr[data].PLAN_TYPE == 'Construction Status'}
														<div class="taggedDate">
															Tagged Date:<font color = "red">*</font>&nbsp;&nbsp;
															<input name="img_date{$cnt}" type="text" class="formstyle2" id="img_date{$cnt}" readonly="1" size="10"  value="{{$ImageDataListingArr[data].tagged_month}}" onchange="tagged_date_change({$cnt})"/>  <img src="../images/cal_1.jpg" id="img_date_trigger{$cnt}" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
															<br><br>
															Tower:&nbsp;&nbsp;
															<select name= "txtTowerId[{$cnt}]" onchange='tower_change({$cnt})' id="tower{$cnt}">
																<option value="" >--Select Tower--</option>
																{section name=towerdata loop=$towerDetail}
																	<option value="{$towerDetail[towerdata].TOWER_ID}" {if $ImageDataListingArr[data].tower_id == $towerDetail[towerdata].TOWER_ID} selected {/if} >{$towerDetail[towerdata].TOWER_NAME}</option>
																{/section}
																	<option value="0" {if $ImageDataListingArr[data].tower_id == "0"} selected {/if}>Other</option>
															</select>
														</div>
														{/if}
														{if $ImageDataListingArr[data].PLAN_TYPE == 'Cluster Plan'}
														<div class="taggedDate1">
															Tower:<font color = "red">*</font>&nbsp;&nbsp;
															<select name= "txtTowerId[{$cnt}]" onchange='tower_change({$cnt})' id="tower{$cnt}">
																<option value="" >--Select Tower--</option>
																{section name=towerdata loop=$towerDetail}
																	<option value="{$towerDetail[towerdata].TOWER_ID}" {if $ImageDataListingArr[data].tower_id == $towerDetail[towerdata].TOWER_ID} selected {/if} >{$towerDetail[towerdata].TOWER_NAME}</option>
																{/section}
																{if count($towerDetail)<1}
																	<option value="0" {if $ImageDataListingArr[data].tower_id == "0"} selected {/if}>Other</option>
																{/if}
															</select>
															<br><br>
															Floor No. From:<font color = "red"></font>&nbsp;&nbsp;<input name="floor_from{$cnt}" type="text" class="formstyle2" id="floor_from{$cnt}" size="10"  onchange="floor_change_from({$cnt})" />	
															&nbsp;&nbsp;<br>Floor No. To:<font color = "red"></font>&nbsp;&nbsp;
																<input name="floor_to[{$cnt}]" type="text" class="formstyle2" id="floor_to{$cnt}" size="10"  onchange="floor_change_to({$cnt})" />
														</div>
														{/if}
														
															Display Order:&nbsp;&nbsp;
															<select name= "txtdisplay_order[{$cnt}]" >
																{foreach from=$display_order_div_edit key=keyss item=datass}
																	<option value="{$keyss}"  {if $ImageDataListingArr[data].display_order == $keyss} selected {/if}>{$datass}</option>
																{/foreach}																	
															</select>
															<br/>
														
														<br/><br/>
														Delete:<input type="radio" name="chk_name[{$cnt}]" value="delete_img" id="chk_{$cnt}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														Edit:<input type="radio" name="chk_name[{$cnt}]"  value="edit_img"><br><br>
														New Image?:<input type="file" name="img[{$cnt}]"/>
														
														


													</div>
												</td>
												{$cnt = $cnt+1}
												{/section}
											</tr>
										</table>
									</td>
								</tr>
					
								<tr>
								 
								  <td colspan = "2" align="right" style="padding-left:152px;" >
								  <span id = 'rowcount' style = 'display:none;'>{$count}</span>
								   <span id = 'rowPropCount' style = 'display:none;'>{$countPropImages}</span>
								  
								  <input type="hidden" name="projectId" value="{$projectId}" />
								  <input type="submit" name="btnSave" id="btnSave" value="Submit" style = "font-size:16px;"  >
								  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style = "font-size:16px;">
								  </td>
								</tr>
								</form>
						</table>
			

				</div>
				</td>
				</tr>






		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {}
        
        for(i=1;i<=30;i++){
            cals_dict["img_date_trigger"+i] = "img_date"+i;
     
        };

        $.each(cals_dict, function(k, v) {
            if ($('#' + k).length > 0) {
                Calendar.setup({
                    inputField: v, // id of the input field
                    //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
                    ifFormat: "%Y-%m-%d", // format of the input field
                    button: k, // trigger for the calendar (button ID)
                    align: "Tl", // alignment (defaults to "Bl")
                    singleClick: true,
                    showsTime: true
                });
            }
        });
   
 </script>

