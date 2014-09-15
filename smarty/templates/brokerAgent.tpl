<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>


<script language="javascript">

jQuery(document).ready(function(){ 
 
  
	$("#create_button").click(function(){
	  cleanFields();
	  
	    $('#search_bottom').hide('slow');
	   $('#create_agent').show('slow'); 
     //$('#offAddDiv').hide(); 
	    $('#create_company input,#create_company select,#create_company textarea').each(function(key, value){
	    $(this).attr('disabled',false);		    
	  });	
	});

	$("#exit_button").click(function(){
	  cleanFields();
	   $('#create_agent').hide('slow'); 
	 
	    $('#search_bottom').show('slow');
	});


	$("#agentSave").click(function(){
		var broker = $('#broker').children(":selected").val();
		var name = $('#name').val().trim();        
		var address = $('#address').val().trim();
    	var city = $('#city option:selected').val();
		var pincode = $('#pincode').val().trim();
	    var compphone = $('#compphone').val().trim();
	    //var img = $('#uploadedImage').val();
	    //var img = $(':file').val();
	   // var ipArr = [];
	    //$('input[name="ips[]"]').each(function() {
	     // ipArr.push($(this).val());
	    //});
		//var person = $('#person').val().trim();
		var phone = $('#phone').val().trim();
		//var fax = $('#fax').val().trim();
		var email = $('#email').val().trim();
		//var web = $('#web').val();
		//var pan = $('#pan').val().trim();
		var status = $('#status').val(); 
    var agentRole = $('#role option:selected').val();
    var qualification = $('#qualification option:selected').val();
    var activeSince = $('#img_date1').val();
		var agentId = $('#agentId').val();
    var userId = $('#userId').val();
		 var error = 0;
	    var mode='';
	    if(agentId) {
        mode = 'update';
        //imgId = $('#imgid').val();
      }
	    else {
        mode='create';
        //imgId = '';
      } 

    if(email==''){
      $('#errmsgemail').html('<font color="red">Please provide an Email Id.</font>');
      $("#email").focus();
        error = 1;
    }
    else if(!validateEmail(email)){
       $('#errmsgemail').html('<font color="red">Please provide a valid email.</font>');
       $("#email").focus();
          error = 1;
    }
    else{
          $('#errmsgemail').html('');
    }  
      

    if(phone==''){
     	$('#errmsgphone').html('<font color="red">Please provide a Mobile No.</font>');
	    $("#phone").focus();
	      error = 1;
  	}
  	else if(!isNumeric1(phone)){
  		 $('#errmsgphone').html('<font color="red">Please provide a 10 digit Numeric Value.</font>');
  		 $("#phone").focus();
  	      error = 1;
  	}
    else{
          $('#errmsgphone').html('');
    }

   
    if(compphone!='' && !isNumeric1(compphone)){
      $('#errmsgcompphone').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#compphone").focus();
      error = 1;
    }
    else{
          $('#errmsgcomphone').html('');
    }

    if(pincode!='' && !isNumeric(pincode)){
      $('#errmsgpincode').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#pincode").focus();
      error = 1;
    }
    else{
          $('#errmsgpincode').html('');
    }

    if(city <= 0 || city=='') {
      $('#errmsgcity').html('<font color="red">Please select a City.</font>');
      $("#city").focus();
      error = 1;
    }
    else{
          $('#errmsgcity').html('');
    }

    if(address==''){
      $('#errmsgaddress').html('<font color="red">Please provide an Address for the company</font>');
      $("#address").focus();
      error = 1;
    }
    else{
          $('#errmsgaddress').html('');
    }

    if(name==''){
      $('#errmsgname').html('<font color="red">Please provide a Company Name.</font>');
      $("#name").focus();
      error = 1;
    }
    else{
          $('#errmsgname').html('');
    }

    if(broker==''){
      $('#errmsgbroker').html('<font color="red">Please select a Company Type.</font>');
      $("#broker").focus();
      error = 1;
    }
    else{
          $('#errmsgbroker').html('');
    }

   /* if($("#imgUploadStatus").val()=="0"){
      error = 1;
      $('#errmsglogo').html('<font color="red">Please upload a Company Logo.</font>');
    }
  */






    var data = { id:agentId, userId:userId, brokerId:broker, name:name, address : address, city:city, pincode : pincode, compphone : compphone, phone:phone, email:email, status:status, agent_role:agentRole, active_since:activeSince, qualification:qualification, task : "createAgent", mode:mode}; 

	    if (error==0){
      
	      	$.ajax({
	            type: "POST",
	            url: "/saveBrokerAgent.php",
	            data: data,
              
	            success:function(msg){
				   if(msg == 1){
	               
	               location.reload(true);
                 $(window).scrollTop(0);
	                //$("#onclick-create").text("Landmark Successfully Created.");
	               }
	               else if(msg == 2){
	                //$("#onclick-create").text("Landmark Already Added.");
	                   
	                   location.reload(true); 
	               }
	               else if(msg == 3){
	                //$("#onclick-create").text("Error in Adding Landmark.");
	                   alert("error");
	               }
	               else if(msg == 4){
	                //$("#onclick-create").text("No Landmark Selected.");
	                   alert("no data");
	               }
	               else alert(msg);
	            },
	        });

	    }


	});

});


function copyAddressClick(){
	var selectBrokerId = $("#broker").val();
	if($("#copyAddress").is(':checked')){
		if(!jQuery.isEmptyObject({$adressArr})){
			var addressArr = eval({$adressArr});
			//console.log(addressArr.);
			for(var k in addressArr){
				if (addressArr[k].id==selectBrokerId){
					$("#address").val(addressArr[k].data[0]);
					$("#city").val(addressArr[k].data[1]);
				    $("#pincode").val(addressArr[k].data[2]);
				    $("#compphone").val(addressArr[k].data[3]);
				    $("#address").prop('readonly', 'readonly');
				    $("#city").prop('disabled', true);
				    $("#pincode").prop('readonly', 'readonly');
				    $("#compphone").prop('readonly', 'readonly');
				}
			}

				/**/
		}
	}
	else{
		$("#address").prop('readonly', false);
	    $("#city").prop('disabled', false);
	    $("#pincode").prop('readonly', false);
	    $("#compphone").prop('readonly', false);

	}
}

function brockerChanged(){
	copyAddressClick();

}


function isNumeric(val) {
        var validChars = '0123456789';
        var validCharsforfirstdigit = '1234567890';
        if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
                return false;
        

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}

//phone no
function isNumeric1(val) {
        var validChars = '-+0123456789';
        var validCharsforfirstdigit = '-+1234567890';
        if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
                return false;
        if(val.length>10 || val.length<6) return false;

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}

{literal}
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
{/literal}

function editAgent(brokerId,id, user_id, name,role,status, email, address, city, pin, mobile, phone, active_since,qualification, action){

  console.log(qualification);
    cleanFields();
    $("#broker").val(brokerId);
    $("#agentId").val(id);
    $("#userId").val(user_id);
    $('#city').val(city);
    $("#role").val(role);
    $("#name").val(name);
    //$("#des").val(des);
    $("#address").val(address);
    $("#pincode").val(pin);
    $("#compphone").val(phone);

    //$("#person").val(person);
    $("#phone").val(mobile);
    //$("#web").val(lmkweb);
    //$("#fax").val(fax);
    $("#status").val(status);
    $("#email").val(email);
    if(email!=''){
      $("#email").prop('readonly', 'readonly');
    }
    $("#qualification").val(qualification);
    $("#img_date1").val(active_since);
    //$("#pan").val(pan);
    //$('#search-top').hide('slow');
    $('#search_bottom').hide('slow');
    window.scrollTo(0, 0);

    if($('#create_agent').css('display') == 'none'){ 
     $('#create_agent').show('slow'); 
    }

    if(action == 'read'){
    $('#create_company input,#create_company select,#create_company textarea').each(function(key, value){
    if($(this).attr('id') != 'exit_button')      
        $(this).attr('disabled',true);        
    });
  }else{
    $('#create_company input,#create_company select,#create_company textarea').each(function(key, value){
      $(this).attr('disabled',false);       
    });   
    }



}

function cleanFields(){
    $("#broker").val('');
    $("#agentId").val('');
    $("#userId").val('');
    $('#role').val('');
    $("#name").val('');
    //$("#des").val('');
    $("#address").val('');
    $("#city").val('');
    $("#pincode").val('');
    $("#compphone").val('');
    //$("#person").val('');
    $("#phone").val('');
    //$("#fax").val('');
    $("#email").val('');
    $("#email").prop('readonly', false);
    //$("#web").val('');
    //$("#pan").val('');
    $("#status").val('');
    $("#qualification").val('');
    $("#img_date1").val('');

    $('#errmsgbroker').html('');
    $('#errmsgcity').html('');
    //$('#err').html('');
    $('#errmsgname').html('');
    $('#errmsgaddress').html('');
    $('#errmsgphone').html('');
    $('#errmsgpincode').html('');
    //$('#errmsgfax').html('');
    $('#errmsgcompphone').html('');
    //$('.errmsgip').each(function() {
      //$(this).html('');
    //});
    //$('#imgPlaceholder').html('');
    //$('#errmsglogo').html('');

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
    <!--{if $companyAuth == 1}-->
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Broker Agent Management</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  

                  <div align="left" style="margin-bottom:5px;">
                  <button type="button" id="create_button" align="left">Create New Agent</button>
                </div>
                  <div id='create_agent' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <div>
                    
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Company Name: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="broker" name="broker" onchange="brockerChanged();">
                                       <option value=''>select Broker</option>
                                       {foreach from=$brokerArr key=k item=v}
                                              <option value="{$v['id']}" {if "" ==$v['id']}  selected="selected" {/if}>{$v['name']}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgbroker"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Agent Name : </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="agentId"><input type="hidden", id="userId"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Agent Role : </td>
                      <td width="30%" align="left"><select id="role" name="role" >
                        <option name=one value='Broker Agent' selected='selected'>Broker Agent</option>
                        
                                
                        </select>
                      </td> 
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Status : </td>
                      <td width="30%" align="left"><select id="status" name="status" >
                        <option name=one value='Active'> Active </option>
                        <option name=two value='Inactive' > Inactive </option>
                                
                        </select>
                      </td> 
                    </tr>

                    <tr>
                      <td colspan="3" align="left" valign="bottom"><hr><b>Contact Details </b> </td>
                    </tr>

                   <tr>
                   	<td><table><tr>
		                      <td width="5%" align="right" ><input type="checkbox" name="copyAddress" id="copyAddress" onchange="copyAddressClick();"></td>
		                      <td align="left">Copy Company Address</td>
		            </tr></table></td>          
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Address :</td>
                      <td width="30%" align="left" >
                      <textarea name="address" rows="10" cols="35" id="address" style="width:250px;"></textarea></td>
                      <td width="20%" align="left" id="errmsgaddress"></td>
                   
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>City :</td>
                      <td width="30%" align="left" ><select id="city" name="city" >
                                       <option value=''>select city</option>
                                       {foreach from=$cityArray key=k item=v}
                                           <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select></td>
                      <td width="20%" align="left" id="errmsgcity"></td>
                      
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Pincode : </td>
                      <td width="30%" align="left"><input type=text name="pincode" id="pincode"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgpincode"></td>
                    </tr>


                    <tr>
                      <td width="20%" align="right" >Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="compphone" id="compphone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcompphone"></td>
                    </tr>

                    
                    <tr>
                      <td width="20%" align="right" ><font color = "red">*</font>Mobile : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="phone"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgphone"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" ><font color = "red">*</font>Email : </td>
                      <td width="30%" align="left"><input type=text name="email" id="email" style="width:250px;"></td> <td width="50%" align="left" id="errmsgemail"></td>
                    </tr>

                    <tr>
                      <td colspan="3"><hr></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Operational Since : </td>
                      <td width="30%" align="left" >
                      <input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                     <td width="20%" align="left" id="errmsginvestdate"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Agent Qualification : </td>
                      <td width="30%" align="left"><select id="qualification" name="qualification" >
                                       <option value=''>Select Qualification</option>
                                       {foreach from=$sellerQualification key=k item=v}
                                           <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                      </td> 
                    </tr>

                    <tr>
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
                      <input type="button" name="agentSave" id="agentSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp; <input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">                 
                      </td>
                    </tr>
                    </div>
                  </form>
                  
                  </table> 
                  </div> 




                    <div id="search_bottom">
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0 class="tablesorter">
                        <form name="form1" method="post" action="">
                          <thead>
                                <TR class = "headingrowcolor">
                                  <th  width=2% align="center">No.</th>
                                  <th  width=5% align="center">Broker Name</th>
                                  <TH  width=8% align="center">Agent Name</TH>
                                  <TH  width=8% align="center">Agent Role</TH>
                                  <TH  width=8% align="center">Address</TH>
                                  <TH  width=8% align="center">Contacts</TH>
                                  <TH  width=8% align="center">Operational Since</TH>
                                  <TH  width=8% align="center">Acad Qualification</TH>
                                  
                                 <TH width=6% align="center">Status</TH> 
                                <TH width=3% align="center">Edit</TH>
                                </TR>
                              
                          </thead>
                          <tbody>
                               
                                {$i=0}
                                
                                {foreach from=$agentArr key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i} </TD>
                                  <TD align=center class=td-border>{$v['brokerName']}</TD>
                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return editAgent('{$v['brokerId']}', '{$v['id']}', '{$v['user_id']}', '{$v['name']}', '{$v['role']}', '{$v['status']}', '{$v['email']}', '{$v['address']}', '{$v['city']}', '{$v['pin']}', '{$v['mobile']}', '{$v['phone']}', '{$v['active_since']}', '{$v['qualification_id']}', 'read');">{$v['name']}</a></TD>
                                  <!--<TD align=center class=td-border><img src = "{$v['service_image_path']}?width=130&height=100"  width ="100px" height = "100px;" alt = "{$v['alt_text']}"></TD>-->
                                  <TD align=center class=td-border>{$v['role']}</TD>
                                  <TD align=center class=td-border>{$v['address']}, City-{$v['city_name']}, Pin-{$v['pin']}</TD>
                                  
                                 
                                  <TD align=center class=td-border>Ph.N.-{$v['phone']}, Mobile-{$v['mobile']}, Email-{$v['email']}</TD>
                                  <TD align=center class=td-border>{$v['active_since']|truncate:13}</TD>
                                  <TD align=center class=td-border>{$v['qualification']}</TD>
                                  <TD align=center class=td-border>{$v['status']}</TD>
                                  

                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return editAgent('{$v['brokerId']}', '{$v['id']}', '{$v['user_id']}', '{$v['name']}', '{$v['role']}', '{$v['status']}', '{$v['email']}', '{$v['address']}', '{$v['city']}', '{$v['pin']}', '{$v['mobile']}', '{$v['phone']}', '{$v['active_since']}', '{$v['qualification_id']}', 'edit');">Edit</a><br/><a href="/companyOrdersList.php?compId={$v['id']}" >ViewOrders</a><br/><a href="/createCompanyOrder.php?c={$v['id']}">AddOrders</a> </TD>

                                </TR>
                                {/foreach}
                                <!--<TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD></TR>-->
                          </tbody>
                          <tfoot>
                                                        <tr>
                                                            <th colspan="21" class="pager form-horizontal" style="font-size:12px;">
                                                                
                                                                <button class="btn first"><i class="icon-step-backward"></i></button>
                                                                <button class="btn prev"><i class="icon-arrow-left"></i></button>
                                                                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                                                <button class="btn next"><i class="icon-arrow-right"></i></button>
                                                                <button class="btn last"><i class="icon-step-forward"></i></button>
                                                                <select class="pagesize input-mini" title="Select page size">
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option>
                                                                    <option selected="selected" value="100">100</option>
                                                                </select>
                                                                <select class="pagenum input-mini" title="Select page number"></select>
                                                            </th>
                                                        </tr>
                           </tfoot>
                        </form>
                    </TABLE>
                  </div>
                 </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
        <!--{/if}-->
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
     


<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {}
        
        for(i=1;i<=1;i++){
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