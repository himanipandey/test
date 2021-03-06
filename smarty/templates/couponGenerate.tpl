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
<script type="text/javascript" src="js/numberToWords.js"></script>


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
      location.reload(true); 
	});


	$("#agentSave").click(function(){
	//alert($('#errmsgDiscount').html());
  //var price_word = "Rs. "+inWords($("#"+price).val());
  //var discount_word = "Rs. "+inWords($("#"+discount).val());
    var projectId = $('#projectId').val()
		var optionId = $('#optionId').val().trim();        
		var price = $('#price').val().trim();
    if (price){
      var price_word = "Rs. "+inWords(price);
    }
    else
     var price_word ="";
    var discount = $('#discount').val().trim();   
    if (discount){
      var discount_word = "Rs. "+inWords(discount);
    }
    else
     var discount_word ="";     

    var discountType = $('#discountType').val();

    var expiryDate = $('#img_date1').val().trim();
   // var redeemHr = $('#redeemHr :selected').val().trim();        
    var totalInventory = $('#totalInventory').val().trim();
    var remainInventory = $('#remainInventory').val().trim();
    var email = $('#email').val().trim();
    	
    var couponId = $('#couponId').val();
		 var error = 0;
	    var mode='';
	    if(couponId) {
        mode = 'update';
        //imgId = $('#imgid').val();
      }
	    else {
        mode='create';
        //imgId = '';
      } 


    if(email==''){
      $('#errmsgEmail').html('<font color="red">Please provide builder E-mail id.</font>');
      $("#email").focus();
        error = 1;
    }
    else if(email != '' && !validateEmail(email)){
      $('#errmsgEmail').html('<font color="red">Please provide a Valid Contact Email.</font>');
      $("#email").focus();
      error = 1;  
  }
    else{
          $('#errmsgEmail').html('');
    } 

    if(remainInventory==''){
      $('#errmsgRemainInventory').html('<font color="red">Please provide No. of Inventory Left.</font>');
      $("#remainInventory").focus();
        error = 1;
    }
    else if(!isNumeric(remainInventory)){
       $('#errmsgRemainInventory').html('<font color="red">Please provide a numeric value.</font>');
       $("#remainInventory").focus();
          error = 1;
    }
    else{
          $('#errmsgRemainInventory').html('');
    }  
 

    if(totalInventory==''){
      $('#errmsgTotalInventory').html('<font color="red">Please provide No. of Total Inventory.</font>');
      $("#totalInventory").focus();
        error = 1;
    }
    else if(!isNumeric(totalInventory)){
       $('#errmsgTotalInventory').html('<font color="red">Please provide a numeric value.</font>');
       $("#totalInventory").focus();
          error = 1;
    }
    else{
          $('#errmsgTotalInventory').html('');
    } 

   /* if(redeemHr==''){
      $('#errmsgRedeemHr').html('<font color="red">Please provide No. of Inventory Left.</font>');
      $("#redeemHr").focus();
        error = 1;
    }
    else if(!isNumeric(redeemHr)){
       $('#errmsgRedeemHr').html('<font color="red">Please provide a valid email.</font>');
       $("#redeemHr").focus();
          error = 1;
    }
    else{
          $('#errmsgRedeemHr').html('');
    } 
*/
    if(expiryDate==''){
      $('#errmsgPurchaseExpDate').html('<font color="red">Please select expiry date.</font>');
      $("#img_date1").focus();
        error = 1;
    }
    else if(isPastDate(expiryDate)){
       $('#errmsgPurchaseExpDate').html(discount_word+'<font color="red">Selecte date is past date.</font>');
       $("#img_date1").focus();
          error = 1;
    }
    else{
          $('#errmsgPurchaseExpDate').html('');
    } 

    if(discount==''){
      $('#errmsgDiscount').html(discount_word+'<font color="red">Please provide Coupon Discount.</font>');
      $("#discount").focus();
        error = 1;
    }
    else if(!isNumeric(discount)){
       $('#errmsgDiscount').html(discount_word+'<font color="red">Please provide a numeric discount.</font>');
       $("#discount").focus();
          error = 1;
    }
    else if(isNumeric(discount)){
      d = parseInt(discount);
      if(discountType=="1"){
        if(optionId=="0"){
          //var maxOptionSize = Math.max.apply(Math, window.optionSizArr);
          var maxOptionSize = Math.max.apply(Math, window.optionSizArr.map(function(a){ return a.option_size; }));
          d = d*maxOptionSize;
          //console.log(d);
        }
        else{
          var optionSize =  Math.max.apply(Math, window.optionSizArr.map(function(a){ console.log(optionId); if(a.option_id==optionId) { return a.option_size; } else return 0; }));
          d = d*optionSize;
          //console.log(optionId);
        }
      }

      if(d>=2000000){
         $('#errmsgDiscount').html(discount_word+'<font color="red">Discount on Property should be less than Rs 20 Lakh.</font>');
         $("#discount").focus();
            error = 1;
      }
      else{
            $('#errmsgDiscount').html(discount_word+'');
      } 
    }
    else{
          $('#errmsgDiscount').html(discount_word+'');
    } 

    if(price==''){
      $('#errmsgPrice').html(price_word+'<font color="red">Please provide Coupon Price.</font>');
      $("#price").focus();
        error = 1;
    }
    else if(!isNumeric(price)){
       $('#errmsgPrice').html(price_word+'<font color="red">Please provide a numeric Price.</font>');
       $("#price").focus();
          error = 1;
    }
    else{
          $('#errmsgPrice').html(price_word+'');
    } 


    if(optionId==''){
      $('#errmsgOptionId').html('<font color="red">Please Select an Option.</font>');
      $("#optionId").focus();
        error = 1;
    }
    else if(!isNumeric(optionId)){
       $('#errmsgOptionId').html('<font color="red">Please provide a valid Option.</font>');
       $("#optionId").focus();
          error = 1;
    }
    else{
          $('#errmsgOptionId').html('');
    } 

    if($("#project").val()==''){
      $('#errmsgProject').html('<font color="red">Please Select a Project.</font>');
      $("#project").focus();
        error = 1;
    }
    else{
          $('#errmsgProject').html('');
    } 

    /*if(discount!='' && price!='' && compareNumber(price,discount)){
      $('#errmsgDiscount').html(discount_word+'<font color="red">discount should be greater than coupon price.</font>');
      $("#discount").focus();
        error = 1;
    } 
*/
    if(remainInventory!='' && totalInventory!='' && compareNumber(remainInventory,totalInventory)){
      $('#errmsgRemainInventory').html('<font color="red">Inventory left should be less than or equal to Total Inventory.</font>');
      $("#remainInventory").focus(); 
        error = 1;
    }



    var data = { id:couponId, projectId:projectId, optionId:optionId, price:price, discountType:discountType, discount:discount, expiryDate:expiryDate, totalInventory : totalInventory, remainInventory:remainInventory, email:email, mode:mode, task:'create_coupon'}; 

	    if (error==0){
      
	      	$.ajax({
	            type: "POST",
	            url: "/saveCouponCatalogue.php",
	            data: data,
              
	            success:function(msg){
				        alert(msg);
	            },
	        });

	    }


	});








  $.widget( "custom.catcomplete", $.ui.autocomplete, {
   
    _renderItem: function( ul, item ) {
      var res = item.id.split("-");
          var tableName = res[1];
      return $( "<li>" )
        .append( $( "<a>" ).text( item.label + "........." +tableName ) )
        .appendTo( ul );
    },
  

  });


 $( "#project" ).catcomplete({
     
      //alert("hello");
      source: function( request, response ) {
        $.ajax({
          url: "{$url}"+"?query="+$("#project").val().trim()+"&typeAheadType=(project)&rows=10",
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full",
           
            name_startsWith: request.term
          },
          success: function( data ) {
            //alert(data);
            response( $.map( data.data, function( item ) {              
                return {
                label: item.displayText,
                value: item.label,
                id:item.id,
                }
              
            }));
          }
        });
      },
      
      select: function( event, ui ) {
        selectedItem = ui.item;
        var res = ui.item.id.split("-");
          //window.projectId = res[2];
          var projectId = res[2];
          $("#projectId").val(projectId);
          var data = { projectId:projectId,  task:'get_options'}; 
          fill_options(data);
          


        //alert(selectedItem.label);
        //log( ui.item ?
         // "Selected: " + ui.item.label :
          //"Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

    });



});

function fill_options(data1){
  $.ajax({
              type: "POST",
              url: "/saveCouponCatalogue.php",
              data: data1,
              
              success:function(data){
                 if(data == "error"){
                       
                      alert(msg)
                       $(window).scrollTop(0);
                        //$("#onclick-create").text("Landmark Successfully Created.");
                  }
                      
                  else {
                    
                    var data = $('<textarea />').html(data).text();
                    data = jQuery.parseJSON(data);

                    console.log(data);
                    var areaId = 'options1';
                    $('#'+areaId).empty();
                     $('#'+areaId).append( "<option value='-1'><span> Select Option </span></option>" );
                     window.optionSizArr = []; //for validation purpose
                     //console.log(window.optionSizArr); 
                    for( var __cnt = 0; __cnt < data.length; __cnt++ ) {
                      var tmp = {};
                      tmp.option_id = data[ __cnt ]['OPTIONS_ID'];
                      tmp.option_size = data[ __cnt ]['SIZE']; 
                      window.optionSizArr.push(tmp);
                      if(data1.option_id==data[ __cnt ]['OPTIONS_ID']){
                        var html = "<option name='option_"+__cnt +"' selected= 'selected' value='"+ data[ __cnt ]['OPTIONS_ID'] +"' ";
                      }
                      else
                        var html = "<option name='option_"+__cnt +"' value='"+ data[ __cnt ]['OPTIONS_ID'] +"' ";
                       
                        html += "><span>"+ data[ __cnt ]['OPTION_NAME'] +"   (size="+ data[ __cnt ]['SIZE'] + ")<span></option>";
                        $('#'+areaId).append( html );
                    }
                    $('#'+areaId).append( "<option value='0'><span> All </span></option>" );
                  }
              },
          });
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

  if (email.indexOf(";") >= 0){
    
    var res = email.split(";");
    var email1 = res[0].trim();
    var email2 = res[1].trim(); //alert("1"+email1+ "  2"+email2);
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return (re.test(email1) && re.test(email2));

    }
  else {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
  }

}
{/literal}

function editCatalogue(id, option_id, price,discount,expiryDate,total_inventory, inventory_left, proj_name, proj_id, email, discount_type, action){

    cleanFields();
    $("#couponId").val(id);

    $('#project').val(proj_name);
    $('#project').attr('disabled', true);

    $('#projectId').val(proj_id);
    var data = { projectId:proj_id,  task:'get_options', option_id:option_id}; 
    fill_options(data);
    $('#options1').attr('disabled', true);

    //$('#options1 :selected').val(option_id);
    $('[name=options1] :selected').val(option_id);
     //alert($('#options1').val());
    $('#optionId').val(option_id);


    $("#price").val(price.replace(/,/g, ""));
    $("#discount").val(discount.replace(/,/g, ""));
   
    $("#img_date1").val(expiryDate);
    
    $("#totalInventory").val(total_inventory);
    
    $("#remainInventory").val(inventory_left);
     $("#email").val(email);
     if(discount_type=='SqFt'){
      $("#discountType").val("1");
     }
     else{
      $("#discountType").val("0");
     }
   
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
    $("#couponId").val('');
    $('#project').val('');
    $('#projectId').val('');
    $('#options1').val('');
    $('#project').attr('disabled', false);
    $('#options1').attr('disabled', false);
    $('#optionId').val('');
    $("#price").val('');
    $("#discount").val('');
   
    $("#img_date1").val('');
    
    $("#totalInventory").val('');
    
    $("#remainInventory").val('');
    $("#email").val('');

    $('#errmsgProject').html('');
    $('#errmsgOptionId').html('');
    $('#errmsgPrice').html('');
    //$('#err').html('');
    $('#errmsgDiscount').html('');
    $('#errmsgPurchaseExpDate').html('');
    $('#errmsgTotalInventory').html('');
    $('#errmsgRemainInventory').html('');
    $('#errmsgEmail').html('');
    window.optionSizArr = [];

}

function isNumberKey(evt)
  {
   var charCode = (evt.which) ? evt.which : event.keyCode;
  
   if (charCode > 31 && (charCode < 48 || charCode > 57) || (charCode == 13))
    return false;

   return true;
  }

function optionIdSelected(value){
//alert($('#options1').val());
  $("#optionId").val(value);
  //alert(value);
  if(value=="0"){
    $("#type1").attr('disabled', true);
    $("#type2").attr('disabled', false);
    $("#discountType").val("1");
  }
  else{
    $("#type1").attr('disabled', false);
    $("#type2").attr('disabled', false);
  }

}

function compareNumber(v1, v2){
  v1 = parseInt(v1);
  v2 = parseInt(v2);
  if(v1>v2){
    return true;
  }
  else{
    return false;
  }
}

function onChangeValue(id){
  var fieldid = "";
  if (id=="price")     
    fieldid = "errmsgPrice";
  else if (id=="discount")
    fieldid = "errmsgDiscount";

   $("#"+fieldid).html("Rs. "+inWords($("#"+id).val())); 
   //var res = indianNumberFormat($("#"+id).val());
    //$("#"+id).val(res);

}

function indianNumberFormat(no){
  var x= no;
  var lastThree = x.substring(x.length-3);
  var otherNumbers = x.substring(0,x.length-3);
  if(otherNumbers != '')
      lastThree = ',' + lastThree;
  var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
 return res;
}

function isPastDate(date){

  var now = Date.now();
  var value = new Date(date);
  console.log(now);
  console.log(value);
  if (value<now) 
    return true;
  else 
    return false;
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
    {if $couponAuth == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Coupon Catalogue Management</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  

                  <div align="left" style="margin-bottom:5px;">
                  <button type="button" id="create_button" align="left">Create New Catalogue</button>
                </div>
                  <div id='create_agent' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <div>
                    
                    <tr>
                      <div class="ui-widget"><td width="10%" align="right" ><font color = "red">*</font>Project : </td>
                      <td width="40%" align="left" ><input type=text name="project" id="project"  style="width:250px;"></td></div><td width="40%" align="left" id="errmsgProject"></td>
                      <td><input type="hidden", id="projectId"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Option : </td>
                      <td width="40%" align="left" ><select name="options1" id="options1"  style="width:250px;" onChange="optionIdSelected(this.value)">
                        <option value="">-Select-</option>
                      </select></td><td width="40%" align="left" id="errmsgOptionId"></td>
                      <td><input type="hidden", id="couponId"><input type="hidden", id="optionId"></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Coupon Price : </td>
                      <td width="40%" align="left" ><input type=text name="price" id="price"  style="width:250px;" onkeypress='return isNumberKey(event)' onchange="onChangeValue(this.id);"></td><td width="20%" align="left" id="errmsgPrice"></td>
                     
                    </tr>

                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Select Discount Type : </td>
                      <td width="40%" align="left" ><select name="discountType" id="discountType"  style="width:250px;">
                        <option id="type1" value="0">Absolute</option>
                        <option id="type2" value="1">Per SqFt</option>
                      </select></td><td width="40%" align="left" id=""></td>
                      <td></td>
                    </tr>


                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Discount on Property : </td>
                      <td width="40%" align="left" ><input type=text name="discount" id="discount"  style="width:250px;" onkeypress='return isNumberKey(event)' onchange="onChangeValue(this.id);"></td><td width="40%" align="left" id="errmsgDiscount"></td>
                      <td></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" ><font color = "red">*</font>Coupon Expiry Date : </td>
                      <td width="30%" align="left" >
                      <input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td>
                     <td width="20%" align="left" id="errmsgPurchaseExpDate"></td>
                    </tr>
<!--
                    <tr>
                      <td width="20%" align="right" >Redeem Expiry Hours : </td>
                      <td width="30%" align="left"><select id="redeemHr" name="redeemHr" >
                                       <option value=''>Select Hours</option>
                                       {foreach from=$redeemHours key=k item=v}
                                           <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                      </td> 
                      <td width="20%" align="left" id="errmsgRedeemHr"></td>
                    </tr>
-->
                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Total Inventory : </td>
                      <td width="40%" align="left" ><input type=text name="totalInventory" id="totalInventory"  style="width:250px;" onkeypress='return isNumberKey(event)'></td><td width="40%" align="left" id="errmsgTotalInventory"></td>
                      <td></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Inventory Left : </td>
                      <td width="40%" align="left" ><input type=text name="remainInventory" id="remainInventory"  style="width:250px;" onkeypress='return isNumberKey(event)'></td><td width="40%" align="left" id="errmsgRemainInventory"></td>
                      <td></td>
                    </tr>

                    <tr>
                      <td width="10%" align="right" ><font color = "red">*</font>Builder Email : </td>
                      <td width="40%" align="left" ><input type=text name="email" id="email"  style="width:250px;"></td><td width="40%" align="left" id="errmsgEmail"></td>
                      <td></td>
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
                                  <th  width=5% align="center">Project</th>
                                  <th  width=5% align="center">Option</th>
                                  <TH  width=8% align="center">Coupon Price</TH>
                                  <TH  width=8% align="center">Discount  on Property</TH>
                                  <TH  width=8% align="center">Coupon Expiry Date</TH>
                                  <TH  width=8% align="center">Total Inventory</TH>
                                  <TH  width=8% align="center">Inventory Left</TH>
                                 
                                <TH width=3% align="center">Edit</TH>
                                </TR>
                              
                          </thead>
                          <tbody>
                               
                                {$i=0}
                                
                                {foreach from=$catalogue key=k item=v}
                                    {$i=$i+1}
                                    {if $i%2 == 0}
                                      {$color = "bgcolor = '#F7F7F7'"}
                                    {else}                            
                                      {$color = "bgcolor = '#FCFCFC'"}
                                    {/if}
                                <TR {$color}>
                                  <TD align=center class=td-border>{$i} </TD>
                                  <TD align=center class=td-border>{$v['BUILDER_NAME']} {$v['PROJECT_NAME']} {$v['PROJECT_ADDRESS']}</TD>
                                  <TD align=center class=td-border>{$v['OPTION_NAME']} size({$v['SIZE']})</TD>
                                  
                                  <!--<TD align=center class=td-border><img src = "{$v['service_image_path']}?width=130&height=100"  width ="100px" height = "100px;" alt = "{$v['alt_text']}"></TD>-->
                                  <TD align=center class=td-border>{$v['coupon_price']}</TD>
                                  <TD align=center class=td-border>{$v['discount']} ({$v['discount_type']})</TD>
                                  
                                 
                                  <TD align=center class=td-border>{$v['purchase_expiry_at']|truncate:13}</TD>
                                
                                  <TD align=center class=td-border>{$v['total_inventory']}</TD>
                                  <TD align=center class=td-border>{$v['inventory_left']}</TD>
                                  

                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return editCatalogue('{$v['id']}', '{$v['option_id']}', '{$v['coupon_price']}', '{$v['discount']}', '{$v['purchase_expiry_at']}', '{$v['total_inventory']}', '{$v['inventory_left']}',
                                  '{$v['PROJECT_NAME']}', '{$v['PROJECT_ID']}', '{$v['email']}','{$v['discount_type']}', 'edit');">Edit</a> </TD>

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