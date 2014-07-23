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
{literal}
<script language="javascript">


jQuery(document).ready(function(){ 
 
  
	$("#create_button").click(function(){
	  cleanFields();
	  
	    $('#search_bottom').hide('slow');
	   $('#create_company').show('slow'); 
     //$('#offAddDiv').hide(); 
	    $('#create_company input,#create_company select,#create_company textarea').each(function(key, value){
	    $(this).attr('disabled',false);		    
	  });	
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
    
    // Address(HQ) data
    //var address_hq = {address = $('#address').val().trim()};
		var address = $('#address').val().trim();
    var city = $('#city option:selected').val();
		var pincode = $('#pincode').val().trim();
    var compphone = $('#compphone').val().trim();
    var fax = $('#compfax').val().trim();
    var email = $('#compemail').val().trim();
    var web = $('#web').val();

    var img = $('#uploadedImage').val();
    //var img = $(':file').val();
    var ipArr = [];
    $('input[name="ips[]"]').each(function() {
      ipArr.push($(this).val());
    });
		//var person = $('#person').val().trim();
		//var phone = $('#phone').val().trim();
		
		var pan = $('#pan').val().trim();
		var status = $('#status').val(); 
		var compid = $('#compid').val();

/*****************************************initializers****************************/
		var error = 0;
	  var mode='';
    if(compid) {
      mode = 'update';
      imgId = $('#imgid').val();
    }
    else {
      mode='create';
      imgId = '';
    } 

   
    var off_loc_data = [];

    
    var coverage_data = [];    
    var contact_person_data = [];

/************************* new field added for broker ****************************/
  //loop through all values of office location table rows to get data
  for(var i=0; i<window.offLocTabRowNo; i++){
    var row =  document.getElementById("officeLocRowId_"+i);
    if(row){
      var rowData = {address:$("#off_loc_address_id_"+i).text().trim(), c_id:$("#off_loc_city_id_"+i).val(), loc_id:$("#off_loc_loc_id_"+i).val()};

      off_loc_data.push(rowData);
        

    }
  }

 //loop through all values of coverage table rows
  for(var i=0; i<window.coverageTabRowNo; i++){
    var row =  document.getElementById("coverageRowId_"+i);
    if(row){
      
      var rowData = {c_id:$("#coverage_city_id_"+i).val(), loc_id:$("#coverage_loc_id_"+i).val(), p_id:$("#coverage_proj_id_"+i).val(), type:$("#coverage_type_"+i).val() }; 

      coverage_data.push(rowData);

    }
  }

 //loop through all values of contact persons rows
  for(var i=0; i<=window.contactTableNo; i++){
    var row =  document.getElementById("rowId_"+i);
    if(row){
      
      var rowData = {person:$("#person_"+i).val().trim(), phone1:$("#phone1_"+i).val().trim(), phone2:$("#phone2_"+i).val().trim(), mobile:$("#mobile_"+i).val().trim(), fax:$("#fax_"+i).val().trim(), email:$("#email_"+i).val().trim()};
        
      contact_person_data.push(rowData);
    }
  }

//get customer care data

  var cust_care_data = {phone:$("#cc_phone").val().trim(), mobile:$("#cc_mobile").val().trim(), fax:$("#cc_fax").val().trim(), email:$("#cc_email").val().trim()};

// broker extra fields
  


  var projectType = [];
  $(".resiProjectType").each(function(){
    if($(this).is(':checked')){
      projectType.push($(this).val());
    }
  })


  var transactionType = [];
  $(".Transaction").each(function(){
    if($(this).is(':checked')){
      transactionType.push($(this).val());
    }
  })
  console.log(transactionType);
  var legalType = $('#compLegalType').children(":selected").val();
  var frating = $('#frating').children(":selected").val();
  var since_op = $('#img_date1').val(); 
  var stn = $('#stn').val();
  var officeSize = $('#officeSize').val();
  var employeeNo = $('#employeeNo').val();
  var ptManager = $('#ptManager').children(":selected").val();


   
  var broker_extra_fields = {legalType:legalType, projectType:projectType, transactionType:transactionType, frating:frating, since_op:since_op, stn:stn, officeSize:officeSize, employeeNo:employeeNo, ptManager:ptManager };

  //console.log(coverage_data);
  //console.log(contact_person_data); 
    
   var data = { id:compid, type:compType, name:name, des:des, address : address, city:city, pincode : pincode, compphone : compphone, fax:fax, email:email, web:web, image:img, imageId:imgId, ipArr : ipArr, off_loc_data:off_loc_data, coverage_data:coverage_data, contact_person_data:contact_person_data, cust_care_data:cust_care_data, broker_extra_fields:broker_extra_fields, pan:pan, status:status, task : "createComp", mode:mode}; 

/******************************validation****************************************/    

    if(fax!='' && !isNumeric1(fax)){
      $('#errmsgfax').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#fax").focus();
      error = 1;
    }
    else{
          $('#errmsgfax').html('');
    }

    /*if(phone!='' && !isNumeric1(phone)){
      $('#errmsgphone').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#phone").focus();
      error = 1;
    }
    else{
          $('#errmsgphone').html('');
    }
  */
    for (var i = 0; i < ipArr.length; i++) {
      if(ipArr[i]!='' && !ValidateIPaddress(ipArr[i])) {
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

   /* if($("#imgUploadStatus").val()=="0"){
      error = 1;
      $('#errmsglogo').html('<font color="red">Please upload a Company Logo.</font>');
    }
  */






   

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
    $("#imgid").val('');
    $("#imgUploadStatus").val("0");
    $("#uploadedImage").val("");



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
    $('#imgPlaceholder').html('');
    $('#errmsglogo').html('');

}



function editCompany(id,name,type,des, status, pan, email, address, city, pin, compphone, imgpath, imgid, imgalttext, ipsstr, person, fax, phone, action){
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

    var str = '<img src = "'+imgpath+'?width=130&height=100"  alt = "'+imgalttext+'">';

    $('#imgPlaceholder').html(str);
    $("#imgid").val(imgid);

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

function refreshIPs(no){
  if(no==0)no=1;
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
            element2.className=fieldClass;
            element2.name =fieldClass+"[]";
            
            cell2.appendChild(element2);

            var cell3 = row.insertCell(2);
            cell3.innerHTML = "";
            cell3.width = "40%";
            cell3.style.textAlign="left";
            cell3.id="errmsgip_"+rowCount;
            cell3.className ="errmsgip";
 
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


jQuery(function(){
                iframeUpload.init();
            });


 
var iframeUpload = {
    init: function() {
        jQuery('#uploadForm').append('<iframe name="uploadiframe" onload="iframeUpload.complete();"></iframe>');
        jQuery('form.uploadForm').attr('target','uploadiframe');
        //jQuery(document).on('submit', 'form.uploadForm', iframeUpload.started);
    },
    started: function() {
        jQuery('#response').removeClass().addClass('loading').html('Loading, please wait.').show();
        jQuery('#uploadForm').hide();
    },
    complete: function(){
        jQuery('#uploadForm').show();
        var response = jQuery("iframe").contents().text();
        if(response){
            response = jQuery.parseJSON(response);
            if(response.status == 1){
              $("#errmsglogo").html('<font color="green">Image Successfully Uploaded.</font>');
              $("#imgUploadStatus").val("1");
              $("#uploadedImage").val(response.image);

            }
            else{
              $("#errmsglogo").html('<font color="red">Image Upload Failed.</font>');
              $("#imgUploadStatus").val("0");
            }
            
        }
        
    }
};

window.contactTableNo = 0;

jQuery(document).ready(function(){ 
    $("#addContact").click(function(){
      
        var tableId = "contact_table";
        var  fieldId = "person";
        var fieldClass = "persons";
        addContactTable(tableId);
      
                
    });
});

function addContactTable(tableId){
    var table = document.getElementById(tableId); 
 window.contactTableNo += 1;
 no = window.contactTableNo;
 var rowId = "rowId_"+no;
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            row.id = rowId;
            var cell1 = row.insertCell(0);
            var element1 = document.createElement("table");
            element1.style.width="100%";
            element1.id="contact_table_"+no;
            cell1.appendChild(element1);
            addContactRow(element1.id, "Name", "person", "errmsgname", no);
            addContactRow(element1.id, "Contact Phone 1", "phone1", "errmsgname", no);
            addContactRow(element1.id, "Contact Phone 2", "phone2", "errmsgname", no);
            addContactRow(element1.id, "Contact Mobile", "mobile", "errmsgname", no);
            addContactRow(element1.id, "Contact Fax", "fax", "errmsgname", no);
            addContactRow(element1.id, "Contact Email", "email", "errmsgname", no);
            addDeleteButton(element1.id, "deleteContact", no);
            //addBlankRow(element1.id);

}

function addContactRow(tableId, label, inputClass, msgClass, no){
    var table = document.getElementById(tableId); 
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            cell1.innerHTML = label;
            cell1.width = "20%";
            cell1.style.textAlign="right";

            var cell2 = row.insertCell(1);
            cell2.width = "30%";
            var element2 = document.createElement("input");
            element2.type = "text";
            element2.style.width="250px";
            element2.id=inputClass+"_"+no;
            element2.className=inputClass;
            element2.name =inputClass+"[]";
            
            cell2.appendChild(element2);

            var cell3 = row.insertCell(2);
            cell3.innerHTML = "";
            cell3.width = "50%";
            cell3.style.textAlign="left";
            cell3.id=msgClass+"_"+no;
            cell3.className = msgClass;
            
}

function addDeleteButton(tableId, buttonClass, no){
      var table = document.getElementById(tableId); 
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            cell1.width = "20%";

            var cell2 = row.insertCell(1);
            cell2.width = "30%";

            var cell3 = row.insertCell(2);
            cell3.width = "50%";
            var element = document.createElement("input");
            element.type = "button";
            element.id=buttonClass+"_"+no;
            element.className=buttonClass;
            element.name =buttonClass+"[]";
            element.value = "Delete Contact Person";
            //element.addEventListener("click", deleteContact(no));
            element.addEventListener("click", function(){
              deleteContactPerson(no);
            });
            //$(element).on("click", "button", deleteContact(no));
            cell3.appendChild(element);


}

function addBlankRow(tableId){
      var table = document.getElementById(tableId); 
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
 
            var cell1 = row.insertCell(0);
            cell1.width = "20%";

            var cell2 = row.insertCell(1);
            cell2.width = "30%";
}

var deleteContactPerson = function(no){
 
    try {
  //console.log(no);

            var tableId = "contact_table";
            var table = document.getElementById(tableId);
            //table.deleteRow(no);
               
            var rowId = "rowId_"+no;
            var row = document.getElementById(rowId);
    
           table.deleteRow(row.rowIndex);
 
 
            }catch(e) {
                alert(e);
            }
};

function getLocality(){
   var cityId = $("#off_loc_city").val();
   var data = {cityId:cityId, task:"office_locations"};
   $.ajax({

              type: "POST",
              url: "/saveCompany.php",
              data: data,
              
              success:function(msg){
                $("#off_loc_locality").find('option:gt(0)').remove();
                $("#off_loc_locality").append(msg);
              }
   });
}

/*******************   office location       ***********************************/
window.offLocTabRowNo=0;

function addOfficeLocation(){
  var cityName = $("#off_loc_city :selected").text();  
  var cityId = $("#off_loc_city :selected").val(); 
  var locName = $("#off_loc_locality :selected").text(); 
  var locId = $("#off_loc_locality :selected").val();
  var address = $("#off_loc_address").val();
  var tableId = "off_loc_table";
  var data = {table_id:tableId, first:{checkbox:"checkbox", class:"class" }, second:{label:"label", text:cityName, }, third:{label:"label", text:locName,}, fourth:{label:"label", text:address,}, city_id:cityId, loc_id:locId};

  if(cityId!='' && locId!='' && address!=''){

    addOfficeLocRow(data);
    $('#offAddDiv').hide(); 
  }
  else{
    alert("Please provide City, Locality and Address.");
  }


}

function addOfficeLocRow(data){
  //console.log(data);
    var table = document.getElementById(data.table_id); 
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            row.className = "border";
            row.id = "officeLocRowId_"+window.offLocTabRowNo;

            var cell1 = row.insertCell(0);
            cell1.width = "10%";
            cell1.className = "border";
            var element1 = document.createElement("input");
            element1.type = "checkbox";
            cell1.style.textAlign = "center";
            //element2.style.width="25px";
            //element2.id=inputclassName+"_"+no;
            element1.className ="off_loc_cb";
            element1.id= "off_loc_cb_"+window.offLocTabRowNo;
            cell1.appendChild(element1);

            var cell2 = row.insertCell(1);
            cell2.className = "border";
            var element2 = document.createElement("label");
            element2.innerHTML = data.second.text;;
            cell2.width = "15%";
            cell2.style.textAlign="center";
            cell2.appendChild(element2);

            var cell3 = row.insertCell(2);
            cell3.className = "border";
            var element3 = document.createElement("label");
            element3.innerHTML = data.third.text;;
            cell3.width = "25%";
            cell3.style.textAlign="center";
            cell3.appendChild(element3);

            var cell4 = row.insertCell(3);
            cell4.className = "border";
            var element4 = document.createElement("label");
            element4.innerHTML = data.fourth.text;
            element4.className = " off_loc_address_class";
            element4.id = "off_loc_address_id_"+window.offLocTabRowNo;
            
            cell4.width = "30%";
            cell4.style.textAlign="center";
            cell4.appendChild(element4);


            var element5 = document.createElement("input");
            element5.type = "hidden";
            element5.value = data.city_id;
            element5.className = " off_loc_city_class";
            element5.id = "off_loc_city_id_"+window.offLocTabRowNo;
            
            cell4.appendChild(element5);

            var element6 = document.createElement("input");
            element6.type = "hidden";
            element6.value = data.loc_id;
            element6.className = " off_loc_locality_class";
            element6.id = "off_loc_loc_id_"+window.offLocTabRowNo;
            
            cell4.appendChild(element6);


            window.offLocTabRowNo +=1;
}

function openOfficeAddDiv(){
  if($('#offAddDiv').css('display') == 'none'){ 
     $('#offAddDiv').show(); 
  }
}

function openCoverageDiv(){
  if($('#coverageDiv').css('display') == 'none'){ 
     $('#coverageDiv').show(); 
  }
}

function deleteOfficeRow(){
var selected=false;
    $(".off_loc_cb").each(function(key, value){
      //alert("here0");
      if($(this).is(':checked')){
          selected=true;
          cbId = $(this).attr('id');
          var res = cbId.split("_");
          var no = res[res.length-1]; 
           try {
                var table = document.getElementById("off_loc_table");
                var rowId = "officeLocRowId_"+no;
                var row = document.getElementById(rowId);
        
               table.deleteRow(row.rowIndex);
     
     
            }catch(e) {
                alert(e);
            }
      }
    });
    if(!selected)
    alert("Please select at least one row.")
}

$.widget( "custom.catcomplete", $.ui.autocomplete, {
   
  _renderItem: function( ul, item ) {
    //var res = item..split("-");
        //var tableName = res[1];
    return $( "<li>" )
      .append( $( "<a>" ).text( item.label ) )
      .appendTo( ul );
  },
  

  });

jQuery(document).ready(function(){

    $( "#searchLocality" ).catcomplete({
      source: function( request, response ) {
        $.ajax({
          url: $("#typeaheadUrl").val()+"?query="+$("#searchLocality").val()+"&typeAheadType=locality&rows=10",
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full",
           
            name_startsWith: request.term
          },
          success: function( data ) {
            response( $.map( data.data, function( item ) {  
              var str = item.id.split("-");
              var id = str[2];
                return {
                label: item.displayText,
                locId:id,
                cName:item.city,
                locName:item.locality
                }
              
            }));
          }
        });
      },
      
      select: function( event, ui ) {
        window.selectedLocality = ui.item;
        
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

    });


  $.widget( "custom.catcomplete1", $.ui.autocomplete, {
   
  _renderItem: function( ul, item ) {
    //var res = item.id.split("-");
        //var tableName = res[1];
    return $( "<li>" )
      .append( $( "<a>" ).text( item.cName+"-"+item.locName+"-"+item.pName) )
      .appendTo( ul );
  },
  

  });

    $( "#searchProjects" ).catcomplete1({
      source: function( request, response ) {
        $.ajax({
          url: "/saveCompany.php",
          dataType: "json",
          type: "POST",
          data: {
            featureClass: "P",
            style: "full",
            query: $("#searchProjects").val(),
            locality: window.selectedLocality.locId,
            task:"find_project_builder",
            option:$("input[name=projectsRadio]:checked").val(),
            name_startsWith: request.term
          },
          success: function( data ) {
            response( $.map( eval(data).data, function( item ) {     
            //console.log("item:"+item.locName);         
                return {
                locId: item.locId,
                locName:item.locName,
                cId:item.cId,
                cName:item.cName,
                pId:item.pId,
                pName:item.pName,
                type:item.ptype,
                label:item.pName,
                value:item.pName
                }
              
            }));
          }
        });
      },
      
      select: function( event, ui ) {
        window.selectedProjects = ui.item;
        //console.log(window.selectedProjects);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

    });
});



/*******************   coverage       ***********************************/
window.coverageTabRowNo=0;

function addCoverage(){
  if($("input[name=projectsRadio]:checked").val()=="all"){
    var cityName = window.selectedLocality.cName; 
    var cityId = "";//window.selectedLocality.cId;
    var locName = window.selectedLocality.locName; 
    var locId = window.selectedLocality.locId;
    var projName = "All";
    var projId = "";
    var bName = "";
    var type = "all";
  }
  else{
    var cityName = window.selectedProjects.cName; 
    var cityId = window.selectedProjects.cId;
    var locName = window.selectedProjects.locName; 
    var locId = window.selectedProjects.locId;
    if(window.selectedProjects.type=="project"){
      var projName = window.selectedProjects.pName;
      var projId = window.selectedProjects.pId;
      var bName = "";
      var type = "project";
    }
    else if(window.selectedProjects.type=="builder"){
      var bName = window.selectedProjects.pName;
      var projId = window.selectedProjects.pId;  //same var to strore pid and bid
      var type = "builder";
      var projName = "All Projects of";//window.selectedProjects.pName;
    }
    else{
      var projName = '';
      var projId = '';
      var bName = "";
      var type = "";
    }
  }

    var tableId = "coverage_table";
    var data = {table_id:tableId, first:{checkbox:"checkbox", class:"class" }, second:{label:"label", text:cityName, }, third:{label:"label", text:locName,}, fourth:{label:"label", text:projName,}, fifth:{label:"label", text:bName,}, city_id:cityId, loc_id:locId, p_id:projId, type:type};
  

  if( locName!='' && projName!=''){

    addCoverageRow(data);
    $('#coverageDiv').hide(); 
  }
  else{
    alert("Please provide City, Locality, Projects and Builders.");
  }


}

function addCoverageRow(data){
  //console.log(data);
    var table = document.getElementById(data.table_id); 
 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            row.className = "border";
            row.id = "coverageRowId_"+window.coverageTabRowNo;

            var cell1 = row.insertCell(0);
            cell1.width = "10%";
            cell1.className = "border";
            var element1 = document.createElement("input");
            element1.type = "checkbox";
            cell1.style.textAlign = "center";
            //element2.style.width="25px";
            //element2.id=inputclassName+"_"+no;
            element1.className ="coverage_cb";
            element1.id= "coverage_cb_"+window.coverageTabRowNo;
            cell1.appendChild(element1);

            var cell2 = row.insertCell(1);
            cell2.className = "border";
            var element2 = document.createElement("label");
            element2.innerHTML = data.second.text;;
            cell2.width = "15%";
            cell2.style.textAlign="center";
            cell2.appendChild(element2);

            var cell3 = row.insertCell(2);
            cell3.className = "border";
            var element3 = document.createElement("label");
            element3.innerHTML = data.third.text;;
            cell3.width = "20%";
            cell3.style.textAlign="center";
            cell3.appendChild(element3);

            var cell4 = row.insertCell(3);
            cell4.className = "border";
            var element4 = document.createElement("label");
            element4.innerHTML = data.fourth.text;
            element4.id = "coverage_project_id_"+window.coverageTabRowNo;
            cell4.width = "20%";
            cell4.style.textAlign="center";
            cell4.appendChild(element4);

            var cell5 = row.insertCell(4);
            cell5.className = "border";
            var element7 = document.createElement("label");
            element7.innerHTML = data.fifth.text;
            element7.id = "coverage_project_id_"+window.coverageTabRowNo;
            cell5.width = "20%";
            cell5.style.textAlign="center";
            cell5.appendChild(element7);


            var element5 = document.createElement("input");
            element5.type = "hidden";
            element5.value = data.city_id;
            element5.id = "coverage_city_id_"+window.coverageTabRowNo;
            cell4.appendChild(element5);

            var element6 = document.createElement("input");
            element6.type = "hidden";
            element6.value = data.loc_id;
            element6.id = "coverage_loc_id_"+window.coverageTabRowNo;
            cell4.appendChild(element6);

            var element8 = document.createElement("input");
            element8.type = "hidden";
            element8.value = data.p_id;
            element8.id = "coverage_proj_id_"+window.coverageTabRowNo;
            cell4.appendChild(element8);

            var element9 = document.createElement("input");
            element9.type = "hidden";
            element9.value = data.type;
            element9.id = "coverage_type_"+window.coverageTabRowNo;
            cell4.appendChild(element9);

            window.coverageTabRowNo +=1;
}


function deleteCoverageRow(){
var selected=false;
    $(".coverage_cb").each(function(key, value){
      //alert("here0");
      if($(this).is(':checked')){
          selected=true;
          cbId = $(this).attr('id');
          var res = cbId.split("_");
          var no = res[res.length-1]; 
           try {
                var table = document.getElementById("coverage_table");
                var rowId = "coverageRowId_"+no;
                var row = document.getElementById(rowId);
        
               table.deleteRow(row.rowIndex);
     
     
            }catch(e) {
                alert(e);
            }
      }
    });
    if(!selected)
    alert("Please select at least one row.")
}

function compnayTypeChanged(){
  if($('#companyTypeEdit').children(":selected").val()=="Broker"){
    $("#broker_extra_field").show();
    $("#legalType").show();
  }
  else{
    $("#broker_extra_field").hide();
    $("#legalType").hide();
  }
}

function coverageRadioChanged(){
  if($("input[name=projectsRadio]:checked").val()=="all"){
    $("#searchProjects").prop("readonly", true);

  }
  else{
    $("#searchProjects").prop("readonly", false);
  }
}

</script>
{/literal}
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
                                    <select id="companyTypeEdit" name="companyEdit" onchange="compnayTypeChanged();">
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

                    <tr id="legalType" style="display:none">
                      <td width="10%" align="right" ><font color = "red">*</font>Company Legal Type : </td>
                      <td width="30%" align="left"><select id="compLegalType" name="compLegalType" >
                        <option name=one value=''>Select Company Legal Type</option>
                        <option name=one value='proprietorship'>Proprietorship</option>
                        <option name=two value='private-limited' >Private Limited</option>
                        <option name=two value='limited' >Limited</option>
                        <option name=two value='individual' >Individual</option>
                                
                        </select>
                      </td> 
                      <td width="40%" align="left" id="errmsgcomplegaltype"></td>
                     </tr> 

                    <tr>
                      <td width="20%" align="right" valign="top">Description :</td>
                      <td width="30%" align="left" >
                      <textarea name="des" rows="5" cols="35" id="des" style="width:250px;"></textarea>
                      </td>
                      
                    </tr>

                    <tr>
                      <td colspan="3" align="left" valign="bottom"><hr><b>Address Details (Headquarter)</b> </td>
                    </tr>

                   

                    <tr>
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Address :</td>
                      <td width="30%" align="left" >
                      <textarea name="address" rows="8" cols="35" id="address" style="width:250px;"></textarea></td>
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
                      <td width="20%" align="right" >Office Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="compphone" id="compphone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcompphone"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Office Fax :</td>
                     <td width="30%" align="left"><input type=text name="compfax" id="compfax" style="width:250px;"></td> 
                    <td width="20%" align="left" id="errmsgcompfax"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Office Email : </td>
                      <td width="30%" align="left"><input type=text name="compemail" id="compemail" style="width:250px;"></td> <td width="20%" align="left" id="errmsgcompweb"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="web" id="web" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>

                    <tr>
                      <td colspan="3"><hr></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Company Logo : </td>
                      <td width="30%" align="left" id="imgPlaceholder"></td> <td width="20%" align="left" id=""><input type="hidden" name='imgid' id="imgid"></td>
                    </tr>

                  </form>
                  <form action="saveCompanyLogo.php" target="uploadiframe" name="uploadForm" id="uploadForm" method="POST" enctype = "multipart/form-data">
                    <tr>
                      <td width="20%" align="right" >Change Logo : </td>
                      <td width="30%" align="left"><input type="file" name='companyImg' id="companyImg" ><input type="hidden" name='imgUploadStatus' id="imgUploadStatus" value="0"><input type="hidden" name='uploadedImage' id="uploadedImage" value=""><input type="submit" id="upload" value="Upload" name="submit"></td> <td width="20%" align="left" id="errmsglogo"></td>
                    </tr>
                    

                  </form>
                  
                  <form>

                
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


<!--  contact persons starts --------------------------------------------------------------------     -->
                    <tr >
                      <td colspan="3" align="left" ><hr><b>Contact Person Details</b></td>
                    </tr>

                    <tr>
                      <td colspan="3" align="left">
                        <table id="contact_table" width = "100%">
                          <tr id="rowId_0">
                          <td colspan="3" align="left">
                          <table id="contact_table_0" width = "100%" >
                            <tr>
                              <td width="20%" align="right" valign="top">Name : </td>
                              <td width="30%" align="left"><input type=text name="person" id="person_0" style="width:250px;"></td> <td width="50%" align="left" id="errmsgweb"></td>
                              </td>
                        
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Phone 1 : </td>
                              <td width="30%" align="left"><input type=text name="phone" id="phone1_0"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgphone"></td>
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Phone 2 : </td>
                              <td width="30%" align="left"><input type=text name="phone" id="phone2_0"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgphone"></td>
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Mobile : </td>
                              <td width="30%" align="left"><input type=text name="phone" id="mobile_0"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgphone"></td>
                            </tr>


                            <tr>
                              <td width="20%" align="right" valign="top">Contact Fax : </td>
                             <td width="30%" align="left"><input type=text name="fax" id="fax_0" style="width:250px;"></td> 
                            <td width="50%" align="left" id="errmsgfax"></td>
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Email : </td>
                              <td width="30%" align="left"><input type=text name="email" id="email_0" style="width:250px;"></td> <td width="50%" align="left" id="errmsgweb"></td>
                            </tr>

                           
                            
                            <tr width="25px">
                              <td width="20%"></td>
                              <td width="30%"></td>
                              <td align="left" style="padding-left:50px;" width="50%">
                              <input type="button" name="deleteContact" id="deleteContact_0" value="Delete Contact Person" style="cursor:pointer" onclick="deleteContactPerson('0');">          
                              </td>
                            </tr>
                            
                          </table>
                        </td>
                      </tr>
                    </table>
                    </td>
                    </tr>

                    <tr>
                     
                      <td align="left" style="padding-left:50px;" colspan="3" >
                      <input type="button" name="addContact" id="addContact" value="Add Contact Person" style="cursor:pointer">                
                      </td>
                    </tr>


<!--  customer care starts --------------------------------------------------------------------     -->
                    <tr height="15">
                      <td colspan="3" align="left" ><hr><b>Customer Care Details</b></td>
                    </tr> 

                    <!--<tr>
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="web" id="web" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>-->

                    
                    <tr>
                      <td width="20%" align="right" >Cust Care Phone : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="cc_phone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcc_phone"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" >Cust Care Mobile : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="cc_mobile"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcc_mobile"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Cust Care Fax :</td>
                     <td width="30%" align="left"><input type=text name="fax" id="cc_fax" style="width:250px;"></td> 
                    <td width="20%" align="left" id="errmsgcc_fax"></td>
                    </tr>

                   <!--<tr>
                      <td width="20%" align="right" >Cust Care Email : </td>
                      <td width="30%" align="left"><input type=text name="email" id="cc_email" style="width:250px;"></td> <td width="20%" align="left" id="errmsgcc_email"></td>
                    </tr>-->

<!--   office locations --------------------------------------------------------------     -->
                   <tr height="15">
                      <td colspan="3" align="left" ><hr><b>Office Locations</b></td>
                    </tr> 
                    <tr id="offAddDiv" style="display:none">
                      
                      <td colspan="3">
                        <table width="100%">
                        <tr>
                          <td width="18%" align="right" valign="top"><font color = "red"></font>City :</td>
                          <td width="30%" align="left" ><select id="off_loc_city" name="" onchange="getLocality();"><option value=''>Select City</option>
                                           {foreach from=$cityArray key=k item=v}
                                               <option value="{$k}">{$v}</option>
                                           {/foreach}
                                           
                                        </select></td>
                          <td width="20%" align="left" id=""></td>
                          
                          <td><input type="hidden", id="lmkid">  </td>
                        </tr>

                        <tr>
                          <td width="18%" align="right" valign="top"><font color = "red"></font>Locality :</td>
                          <td width="30%" align="left" ><select id="off_loc_locality" name="" >
                              <option value=''>Select Locality</option>
                                        </select></td>
                          <td width="20%" align="left" id=""></td>
                          
                          <td><input type="hidden", id="lmkid">  </td>
                        </tr>

                        <tr>
                          <td width="18%" align="right" >Enter Address : </td>
                          <td width="30%" align="left"><input type=text name="" id="off_loc_address" style="width:350px;"></td>
                          <td><input type="button" align="left" id="addOffLoc" value="Add" style="cursor:pointer" onclick="addOfficeLocation();"></td> <td width="20%" align="left" id="errmsgweb"></td>
                        </tr>
                        </table>
                      </td>
                     
                    </tr>

                    <hr>
                    <tr>
                      <td colspan="3" >
                      <table id="off_loc_table" width = "100%" class="border">
                      <tr class="border">
                        <th width="10%" align="center" class="border">checkbox</th>
                        <th width="15%" height="25" align="center" valign="top" class="border">City</th>
                        <th width="25%" align="center" class="border">Locality</th>
                        <th width="30%" align="center" class="border">Address</th>
                      </tr>
                      
                      </table>
                      </td>
                    </tr>
                    <tr>
                      <td width="20%" align="right" ><input type="button" align="left" id="addOffLoc" value="Add" style="cursor:pointer" onclick="openOfficeAddDiv();"></td>
                      <td width="20%" align="left" ><input type="button" align="left" id="addOffLoc" value="Delete" style="cursor:pointer" onclick="deleteOfficeRow();"></td>
                    </tr>


<!--  coverage ----------------------------------------------------------------------> 
                    <tr height="15">
                      <td colspan="3" align="left" ><hr><b>Coverage</b></td>
                    </tr> 

                    <tr id="coverageDiv" style="display:none">
                      
                      <td colspan="3">
                        <table width="100%">
                        <tr>
                          <td width="18%" align="right" valign="top"><font color = "red"></font>Locality :</td>
                          <td width="30%" align="left" ><input id="searchLocality" class="ui-corner-top"></td>
                          <td width="20%" align="left" id=""></td>
                          
                          <td><input type="hidden", id="lmkid"><input type="hidden", id="typeaheadUrl" value='{$url}'>  </td>
                        </tr>

                        <tr>
                          <td width="18%" align="right" valign="top"><font color = "red"></font>Projects :</td>
                          <td width="50%" align="left" ><input type="radio" name="projectsRadio" value="all" id="projectsRadio" onchange="coverageRadioChanged();">All Projects</input>&nbsp; &nbsp; &nbsp; &nbsp; <input type="radio" name="projectsRadio" value="project" id="projectsRadio" onchange="coverageRadioChanged();">Some Projects</input> &nbsp; &nbsp; &nbsp; &nbsp;<input type="radio" name="projectsRadio" value="builder" id="projectsRadio" onchange="coverageRadioChanged();">Some Builders</input></td>
                          <td width="20%" align="left" id=""></td>
                          
                         
                        </tr>

                        <tr>
                          <td width="18%" align="right" valign="top"><font color = "red"></font>Add Projects :</td>
                          <td width="30%" align="left" ><input id="searchProjects" class="ui-corner-top"></td>
                          
                          <td><input type="button" align="left" id="addCoverageProjects" value="Add" style="cursor:pointer" onclick="addCoverage();">  </td>
                        </tr>

                        
                        </table>
                      </td>
                     
                    </tr>

                    <hr>
                    <tr>
                      <td colspan="3" >
                      <table id="coverage_table" width = "100%" class="border">
                      <tr class="border">
                        <th width="10%" align="center" class="border">checkbox</th>
                        <th width="15%" height="25" align="center" valign="top" class="border">City</th>
                        <th width="25%" align="center" class="border">Locality</th>
                        <th width="30%" align="center" class="border">Projects</th>
                        <th width="30%" align="center" class="border">Builders</th>
                      </tr>
                      
                      </table>
                      </td>
                    </tr>
                    <tr>
                      <td width="20%" align="right" ><input type="button" align="left" id="addOffLoc" value="Add" style="cursor:pointer" onclick="openCoverageDiv();"></td>
                      <td width="20%" align="left" ><input type="button" align="left" id="addOffLoc" value="Delete" style="cursor:pointer" onclick="deleteCoverageRow();"></td>
                    </tr>


<!--  coverage ends --------------------------------------------------------------------     -->                    
                    <tr height="15">
                      <td colspan="3" align="left" ><hr></td>
                    </tr> 

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

<!--  broker extra details --------------------------------------------------------------------     -->

                    <tr height="15">
                      <td colspan="3" align="left" ><hr></td>
                    </tr> 

                    <tr id="broker_extra_field" style="display:none">
                      <td colspan="3">
                        <table width="100%">
                        
                        <tr>
                          <td width="20%" align="right" valign="top">Properties Broker Deals In : </td>
                          <td width="30%" align="left">
                            {foreach $resiProjectType key=k item=v}
                              <input type='checkbox' name='resiProjectType[]' class='resiProjectType' value='{$k}' {if $k%2==0} text-align="right" {else} text-align="left"{/if}>{$v} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {if $k%2==0}<br>{/if} 
                            {/foreach}
                          </td>
                          <tr></td>
                        </tr>
                        
                        <tr>
                          <td width="10%" align="right" ><font color = "red">*</font>Transaction Types : </td>
                          <td width="30%" align="left">
                            {foreach $transactionType key=k item=v}
                            <input type='checkbox' name='Transaction[]' class='Transaction' value='{$k}'>{$v} &nbsp;&nbsp;
                            {/foreach}
                          </td> 
                          <td width="40%" align="left" id="errmsgcomplegaltype"></td>
                         </tr> 

                         <tr>
                          <td width="10%" align="right"  valign="center">Rating : </td>
                          <td width="30%" align="left">
                              <!--<input type="radio" name="rating" value="auto">Auto &nbsp; &nbsp; {$rating}<br>-->
                              <input type="radio" name="rating" value="forced">Forced &nbsp; &nbsp;
                              <select id="frating" name="frating" style="width:70px;" valign="center">
                            <option name=one value='0.0'>0</option>
                            <option name=two value='0.5' >0.5</option>
                            <option name=one value='1.0'>1.0</option>
                            <option name=two value='1.5' >1.5</option>
                            <option name=one value='2.0'>2.0</option>
                            <option name=two value='2.5' >2.5</option>
                            <option name=one value='3.0'>3.0</option>
                            <option name=two value='3.5' >3.5</option>
                            <option name=one value='4.0'>4.0</option>
                            <option name=two value='4.5' >4.5</option>
                            <option name=one value='5.0'>5.0</option>
                            <option name=two value='5.5' >5.5</option>
                            <option name=one value='6.0'>6.0</option>
                            <option name=two value='6.5' >6.5</option>
                            <option name=one value='7.0'>7.0</option>
                            <option name=two value='7.5' >7.5</option>
                            <option name=one value='8.0'>8.0</option>
                            <option name=two value='8.5' >8.5</option>
                            <option name=one value='9.0'>9.0</option>
                            <option name=two value='9.5' >9.5</option>
                            <option name=one value='10.0'>10.0</option>
                                    
                            </select>
                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Since Operation : </td>
                          <td width="30%" align="left"><input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td> <td width="20%" align="left" id="errmsgdate"></td>
                        </tr>

                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Service Tax No : </td>
                          <td width="30%" align="left"><input type=text name="stn" id="stn" style="width:250px;"></td> <td width="20%" align="left" id="errmsgstn"></td>
                        </tr>

                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Size of Office (sqft) : </td>
                          <td width="30%" align="left"><input type=text name="officeSize" id="officeSize" style="width:250px;"></td> <td width="20%" align="left" id="errmsgofficesize"></td>
                        </tr>

                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font># Employees : </td>
                          <td width="30%" align="left"><input type=text name="employeeNo" id="employeeNo" style="width:250px;"></td> <td width="20%" align="left" id="errmsgemployeeNo"></td>
                        </tr>

                        <tr>
                          <td width="10%" align="right" ><font color = "red">*</font>PT Relationship Manager: </td>
                            <td width="20%" height="25" align="left" valign="top">
                                        <select id="ptManager" name="ptManager" >
                                           <option value=''>select Manager</option>
                                           {foreach from=$ptRelManager key=k item=v}
                                                  <option value="{$k}">{$v}</option>
                                           {/foreach}
                                        </select>
                                    </td>
                            <td width="40%" align="left" id="errmsgptmanager"></td>
                        </tr>
                      </table>
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
                                  <TH  width=8% align="center">Logo</TH>
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
                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return editCompany('{$v['id']}', '{$v['name']}', '{$v['type']}', '{$v['des']}', '{$v['status']}', '{$v['pan']}', '{$v['email']}', '{$v['address']}', '{$v['city']}', '{$v['pin']}', '{$v['compphone']}', '{$v['service_image_path']}', '{$v['image_id']}', '{$v['alt_text']}', '{$v['ipsstr']}', '{$v['person']}', '{$v['fax']}', '{$v['phone']}', 'read');">{$v['name']}</a></TD>
                                  <TD align=center class=td-border><img src = "{$v['service_image_path']}?width=130&height=100"  width ="100px" height = "100px;" alt = "{$v['alt_text']}"></TD>
                                  <TD align=center class=td-border>{$v['address']}, City-{$v['city_name']}, Pin-{$v['pin']}, Ph.N.-{$v['compphone']}</TD>
                                  <TD align=center class=td-border>{foreach from=$v['ips'] key=k1 item=v1} {$v1}, {/foreach}</TD>
                                  <TD align=center class=td-border>{$v['person']}, Contact No.-{$v['phone']}</TD>
                                  <TD align=center class=td-border>{$v['status']}</TD>
                                  

                                  <TD align=center class=td-border><a href="javascript:void(0);" onclick="return editCompany('{$v['id']}', '{$v['name']}', '{$v['type']}', '{$v['des']}', '{$v['status']}', '{$v['pan']}', '{$v['email']}', '{$v['address']}', '{$v['city']}', '{$v['pin']}', '{$v['compphone']}', '{$v['service_image_path']}', '{$v['image_id']}', '{$v['alt_text']}', '{$v['ipsstr']}', '{$v['person']}', '{$v['fax']}', '{$v['phone']}','edit' );">Edit</a><br/><a href="/companyOrdersList.php?compId={$v['id']}" >View Inventory</a> </TD>

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

<style type="text/css" >

  .border {
    border-collapse: true;
  }
  .border {
    border: 1px solid #eee;
  }


</style>

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