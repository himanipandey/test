<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>

<script language="javascript">

jQuery(document).ready(function(){ 
	$("#create_button").click(function(){
	  cleanFields();
	  
	    $('#search_bottom').hide('slow');
	   $('#create_company').show('slow'); 
	});

	$("#exit_button").click(function(){
	  cleanFields();
	   $('#create_company').hide('slow'); 
	 
	    $('#search_bottom').show('slow');
	});



  



	$("#lmkSave").click(function(){
		var compType = $('#companyTypeEdit').children(":selected").val();
		var name = $('#name').val().trim();        
		var des = $('#des').val().trim();
		var address = $('#address').val().trim();
    var city = $('#city option:selected').val();
		var pincode = $('#pincode').val().trim();
    var compphone = $('#compphone').val().trim();
    var ipArr = [];
    $('input[name="ips[]"]').each(function() {
      ipArr.push($(this).val());
    });
		var person = $('#person').val().trim();
		var phone = $('#phone').val().trim();
		var fax = $('#fax').val().trim();
		var email = $('#email').val().trim();
		var web = $('#web').val();
		var pan = $('#pan').val().trim();
		var status = $('#status').val(); 
		var compid = $('#compid').val();
		 var error = 0;
	    var mode='';
	    if(compid) mode = 'update';
	    else mode='create';


    if(fax!='' && !isNumeric1(fax)){
      $('#errmsgfax').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#fax").focus();
      error = 1;
    }
    else{
          $('#errmsgfax').html('');
    }

    if(phone!='' && !isNumeric1(phone)){
      $('#errmsgphone').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#phone").focus();
      error = 1;
    }
    else{
          $('#errmsgphone').html('');
    }

    for (var i = 0; i < ipArr.length; i++) {
      if(ipArr[i]!='' && !ValidateIPaddress(ipArr[i])) {
        console.log(ipArr[i]);
        $('#errmsgip_'+i).html('<font color="red">Please provide a valid IP.</font>');
        $("#ip_"+i).focus();
        error = 1;
      }
      else{
            $('#errmsgip_'+i).html('');
      }
       
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

    if(compType==''){
      $('#errmsgcomptype').html('<font color="red">Please select a Company Type.</font>');
      $("#companyTypeEdit").focus();
      error = 1;
    }
    else{
          $('#errmsgcomptype').html('');
    }







    var data = { id:compid, type:compType, name:name,des:des, address : address, city:city, pincode : pincode, compphone : compphone, ipArr : ipArr, person : person, phone:phone, fax:fax, email:email, web:web, pan:pan, status:status, task : "createComp", mode:mode}; 

	    if (error==0){
      
	      	$.ajax({
	            type: "POST",
	            url: "/saveCompany.php",
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




}); //end document.ready


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
        

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}



function ValidateIPaddress(ipaddress)   
{  
 if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress))  
  {  
    return (true)  
  }  
  return (false)  
} 


function cleanFields(){
    $("#compid").val('');
    $('#companyTypeEdit').val('');
    $("#name").val('');
    $("#des").val('');
    $("#address").val('');
    $("#city").val('');
    $("#pincode").val('');
    $("#compphone").val('');
    $("#person").val('');
    $("#phone").val('');
    $("#fax").val('');
    $("#email").val('');
    $("#web").val('');
    $("#pan").val('');
    $("#status").val('');
    $('input[name="ips[]"]').each(function() {
      $(this).val('');
    });


    $('#errmsgcity').html('');
    $('#errmsgcomptype').html('');
    $('#errmsgname').html('');
    $('#errmsgaddress').html('');
    $('#errmsgphone').html('');
    $('#errmsgpincode').html('');
    $('#errmsgfax').html('');
    $('#errmsgcompphone').html('');
    $('.errmsgip').each(function() {
      $(this).html('');
    });
}



function editCompany(id,name,type,des, status, pan, email, address, city, pin, ipsstr, compphone, person, fax, phone){
    cleanFields();
    $("#compid").val(id);
    $('#city').val(city);
    $("#companyTypeEdit").val(type);
    $("#name").val(name);
    $("#des").val(des);
    $("#address").val(address);
    $("#pincode").val(pin);
    $("#compphone").val(compphone);
   // var ipstring = ipstring.substring(0, ipstring.length -1);
    var ipsarr = ipsstr.split("-");
    

    $("#ip_no").val(ipsarr.length);
    refreshIPs(ipsarr.length);

    
    for(var i=0; i < ipsarr.length; i++){
      $("#ip_"+i).val(ipsarr[i]);
    }

    $("#person").val(person);
    $("#phone").val(phone);
    //$("#web").val(lmkweb);
    $("#fax").val(fax);
    $("#status").val(status);
    $("#email").val(email);
   
    $("#pan").val(pan);
    //$('#search-top').hide('slow');
    $('#search_bottom').hide('slow');
    window.scrollTo(0, 0);

    if($('#create_company').css('display') == 'none'){ 
     $('#create_company').show('slow'); 
    }
}

function refreshIPs(no){
  if(no==0) no=1;
  var val = $("#deal option:selected").val();
  var tableId = "ip_table";
  var table = document.getElementById(tableId);
  //var old_no = parseInt($("#projects").rows.length);
  var old_no = parseInt(table.rows.length);
  var new_no = parseInt(no);
 
  if(new_no > old_no){
    for(old_no; old_no < new_no; old_no++){
      addRow(tableId);
    }
  }
  else if(new_no < old_no){
    for(old_no; old_no>new_no; old_no--){
      deleteRow(tableId);
    }
  }
}


function addRow(tableID) {
            var val = $("#deal option:selected").val();
  
    var  fieldId = "ip";
    var fieldClass = "ips";
  
            var table = document.getElementById(tableID); 
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            cell1.innerHTML = "";
            cell1.width = "15%";
            cell1.style.textAlign="right";

            var cell2 = row.insertCell(1);
            //cell1.width = "20%";
            var element2 = document.createElement("input");
            element2.type = "text";
            element2.style.width="250px";
            element2.id=fieldId+"_"+rowCount;
            element2.class=fieldClass;
            element2.name =fieldClass+"[]";
            
            cell2.appendChild(element2);

            var cell3 = row.insertCell(2);
            cell3.innerHTML = "";
            cell3.width = "40%";
            cell3.style.textAlign="left";
            cell3.id="errmsgip_"+rowCount;
            cell3.class ="errmsgip";
 
}

function deleteRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
             table.deleteRow(rowCount-1);
               
 
 
            }catch(e) {
                alert(e);
            }
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
    {if $companyAuth == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Company Management</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  

                  <div align="left" style="margin-bottom:5px;">
                  <button type="button" id="create_button" align="left">Create New Company</button>
                </div>
                  <div id='create_company' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <input type="hidden" name="old_sub_name" value="">
                    <div>
                    
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Company Type: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="companyTypeEdit" name="companyEdit" >
                                       <option value=''>select Company Type</option>
                                       {foreach from=$comptype key=k item=v}
                                              <option value="{$v}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgcomptype"></td>
                    </tr>
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Name : </td>
                      <td width="40%" align="left" ><input type=text name="name" id="name"  style="width:250px;"></td><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="compid"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Description :</td>
                      <td width="30%" align="left" >
                      <input type=text name="des" id="des"  style="width:250px;">
                      </td>
                      
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
                      <td width="20%" align="right" >Company Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="compphone" id="compphone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcompphone"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" >Company IPs: </td>
                      <td width="20%" height="25" align="left" valign="top">
                    <select name="ip_no" id="ip_no" onchange="refreshIPs(this.value);">
                      <option  value="0" {if $v=="ert"} selected="selected"{else} value="0" {/if}>Select</option>
                     <option  value="1" {if $v=="ert"} selected="selected"{else} value="1" {/if}>1</option>
                     <option  value="2" {if $v=="ert"} selected="selected"{else} value="2" {/if}>2</option> 
                     <option  value="3" {if $v=="ert"} selected="selected"{else} value="3" {/if}>3</option> 
                     <option value="4" {if $v=="ert"} selected="selected"{else} value="4" {/if}>4</option> 
                      <option  value="5" {if $v=="ert"} selected="selected"{else} value="5" {/if}>5</option> 
                     <option  value="6" {if $v=="ert"} selected="selected"{else} value="6" {/if}>6</option> 
                     <option value="7" {if $v=="ert"} selected="selected"{else} value="7" {/if}>7</option> 
                      <option  value="8" {if $v=="ert"} selected="selected"{else} value="8" {/if}>8</option> 
                     <option  value="9" {if $v=="ert"} selected="selected"{else} value="9" {/if}>9</option> 
                     <option  value="10" {if $v=="ert"} selected="selected"{else} value="10" {/if}>10</option>
                    </select></td>
                    </tr>

                    <tr>
                      <td colspan="3" >
                      <table id="ip_table" width = "100%">
                      <tr>
                      <td width="16%" align="right" ></td>
                        <td width="20%" height="25" align="left" valign="top">
                            <input type=text name="ips[]" id="ip_0" style="width:250px;">
                        </td>
                        <td width="40%" align="left" class="errmsgip" id="errmsgip_0"></td>
                       </tr>
                      </table>
                      </td>
                    </tr>
                    <tr>
                      <td width="20%" align="right" valign="top">Contact Person :</td>
                      <td width="30%" align="left"><input type=text name="person" id="person" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                      </td>
                
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Contact Email : </td>
                      <td width="30%" align="left"><input type=text name="email" id="email" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Contact Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="phone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgphone"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">fax :</td>
                     <td width="30%" align="left"><input type=text name="fax" id="fax" style="width:250px;"></td> 
                    <td width="20%" align="left" id="errmsgfax"></td>
                    </tr>

                    

                    <!--<tr>
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="web" id="web" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>-->

                    <tr>
                      <td width="20%" align="right" >Pancard No : </td>
                      <td width="30%" align="left"><input type=text name="pan" id="pan" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
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
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
                      <input type="button" name="lmkSave" id="lmkSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp; <input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">                 
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
                                  <th  width=5% align="center">Type</th>
                                  <TH  width=8% align="center">Name</TH>
                                  <TH  width=8% align="center">Address</TH>
                                  <TH  width=8% align="center">Company IPs</TH>
                                  <TH  width=8% align="center">Contact Person</TH>
                                  
                                 <TH width=6% align="center">Status</TH> 
                                <TH width=3% align="center">Edit</TH>
                                </TR>
                              
                          </thead>
                          <tbody>
                               
                                {$i=0}
                                
                                {foreach from=$compArr key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i} </TD>
                                  <TD align=center class=td-border>{$v['type']}</TD>
                                  <TD align=center class=td-border>{$v['name']}</TD>
                                  <TD align=center class=td-border>{$v['address']}, City-{$v['city_name']}, Pin-{$v['pin']}, Ph.N.-{$v['compphone']}</TD>
                                  <TD align=center class=td-border>{foreach from=$v['ips'] key=k1 item=v1} {$v1}, {/foreach}</TD>
                                  <TD align=center class=td-border>{$v['person']}, Contact No.-{$v['phone']}</TD>
                                  <TD align=center class=td-border>{$v['status']}</TD>
                                  
                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return editCompany('{$v['id']}', '{$v['name']}', '{$v['type']}', '{$v['des']}', '{$v['status']}', '{$v['pan']}', '{$v['email']}', '{$v['address']}', '{$v['city']}', '{$v['pin']}', '{$v['ipsstr']}', '{$v['person']}', '{$v['fax']}', '{$v['phone']}' );">Edit</a> &nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" >View Orders</a> </TD>
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
        {/if}
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>