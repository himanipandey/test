<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/pager-ajax.css">
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


<style>
  /*  css for ajax loader
  */


/* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   speak for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */
.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        40%;
    left:       60%;
    height:     20%;
    width:      20%;
    background: rgba( 255, 255, 255, .8 ) 
                url('http://i.stack.imgur.com/FhHRx.gif') 
                50% 50% 
                no-repeat;
    
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}

</style>



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
      $("#rating_auto").attr('disabled',true);
      $("#broker_switch").hide();
	});

	$("#exit_button").click(function(){
	  cleanFields();
	   $('#create_company').hide('slow'); 
	 
	    $('#search_bottom').show('slow');
	});



$(function(){
/*var selCity = null;
selCity = $("#citydd :selected").val();
var selProject = null;
selProject = $("#selProjId").val();*/
  // Initialize tablesorter
  // ***********************
  $("#company_table")
    .tablesorter({
      theme: 'blue',
      widthFixed: true,
      sortLocaleCompare: true, // needed for accented characters in the data
      sortList: [ [0,1] ],
      widgets: ['zebra'],
      widgetOptions : {
        filter_serversideFiltering : false,
      } 
    })

    //before initialize
    .on('pagerBeforeInitialized', function(event, pager){
    var table = this,
        $table = $(this);

    pager.page = 0;            // set current page here
    pager.size = 25;           // set current size here
    pager.currentFilters = []; // set initial filters here
  })

    // initialize the pager plugin
    // ****************************
    .tablesorterPager({

      // **********************************
      //  Description of ALL pager options
      // **********************************

      // target the pager markup - see the HTML block below
      container: $(".pager"),

      size: 25,

      // use this format: "http:/mydatabase.com?page={ page }&size={ size }&{ sortList:col }"
      // where {page} is replaced by the page number (or use {page+1} to get a one-based index),
      // {size} is replaced by the number of records to show,
      // {sortList:col} adds the sortList to the url into a "col" array, and {filterList:fcol} adds
      // the filterList to the url into an "fcol" array.
      // So a sortList = [[2,0],[3,0]] becomes "&col[2]=0&col[3]=0" in the url
      // and a filterList = [[2,Blue],[3,13]] becomes "&fcol[2]=Blue&fcol[3]=13" in the url
      //ajaxUrl : 'assets/City{page}.json?{filterList:filter}&{sortList:column}',
      //ajaxUrl : '/ajax_listing_table_copy.php?page={page}&size={size}&{sortList:col}',
      //ajaxUrl : '/ajax_tablesorter_listing.php?page={page}&size={size}&{sortList:col}&city={selCity}&project={selProject}',
      ajaxUrl : '/ajaxGetCompany.php?page={page}&size={size}&{sortList:col}',
      // modify the url after all processing has been applied
      customAjaxUrl: function(table, url) {
          // manipulate the url string as you desire
          var compType="";
          var name="";
          var status="";
          var compid = getParameterByName('compid');


          $(".tablesorter-filter").each(function(){ 
            if($(this).attr("data-column")=="1") 
              compType= $(this).val(); 
            if($(this).attr("data-column")=="2") 
              name= $(this).val(); 
            if($(this).attr("data-column")=="6") 
              status= $(this).val(); 
          });

           url += '&compType=' +compType;  
           //if($("#project_search").val().trim()!='')
            url += '&name=' + name; 
            url += '&status=' + status; 
            url += '&compid=' + compid; 
          // trigger my custom event
          $(table).trigger('changingUrl', url);
          // send the server the current page
          return url;
      },

      // add more ajax settings here
      // see http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
      ajaxObject: {
        dataType: 'json'
      },

      // process ajax so that the following information is returned:
      // [ total_rows (number), rows (array of arrays), headers (array; optional) ]
      // example:
      // [
      //   100,  // total rows
      //   [
      //     [ "row1cell1", "row1cell2", ... "row1cellN" ],
      //     [ "row2cell1", "row2cell2", ... "row2cellN" ],
      //     ...
      //     [ "rowNcell1", "rowNcell2", ... "rowNcellN" ]
      //   ],
      //   [ "header1", "header2", ... "headerN" ] // optional
      // ]
      // OR
      // return [ total_rows, $rows (jQuery object; optional), headers (array; optional) ]
      ajaxProcessing: function(data){
        console.log(data);
        if (data && data.hasOwnProperty('rows')) {
          var indx, r, row, c, d = data.rows,
          // total number of rows (required)
          total = data.total_rows,
          // array of header names (optional)
          headers = data.headers,
          // cross-reference to match JSON key within data (no spaces)
          headerXref = headers.join(',').replace(/\s+/g,'').split(','),
          // all rows: array of arrays; each internal array has the table cell data for that row
          rows = [],
          // len should match pager set size (c.size)
          len = d.length;
          var serialNo = data.serialNo;
          // this will depend on how the json is set up - see City0.json
          // rows
          for ( r=0; r < len; r++ ) {
            row = []; // new row array
            // cells
            for ( c in d[r] ) {
              if (typeof(c) === "string") {
                // match the key with the header to get the proper column index
                indx = $.inArray( c, headerXref );

                // add each table cell data to row array
                if (indx >= 0) {
                  if(indx==0){
                    row[indx] = serialNo+r;
                  }
                  else if(indx==6){//encodeURIComponent(JSON.stringify(d[r][c]))
                    //d[r][c] = {'description': "hello'yes boys"};  
                    var a = d[r][c];
                    var b = JSON.parse(a);
                    
                    a = escape(d[r][c]);
                    
                    var edit = 'edit';
                    row[indx] = "<a href='javascript:void(0);' onclick='editCompany("+JSON.stringify(a)+ ", "+JSON.stringify(edit)+")'>Edit</a><br/><a href='/companyOrdersList.php?compId="+JSON.stringify(b.id)+"'>ViewOrders</a><br/><a href='/createCompanyOrder.php?c="+JSON.stringify(b.id)+"'>AddOrders</a><input type='hidden' id='extra_data' value='{$v['extra_json']}'>";

                    //<a href="javascript:void(0);" onclick="return editCompany('{$v['id']}', '{$v['name']}', '{$v['type']}', '{$v['broker_info_type']}', '{$v['des']}', '{$v['status']}', '{$v['pan']}', '{$v['email']}', '{$v['address']}', '{$v['city']}', '{$v['pin']}', '{$v['compphone']}', '{$v['service_image_path']}', '{$v['image_id']}', '{$v['alt_text']}', '{$v['ipsstr']}', '{$v['person']}', '{$v['compfax']}', '{$v['phone']}', '{$v['active_since']}', '{$v['web']}', '{$v['extra_json']}','edit' );">Edit</a><br/><a href="/companyOrdersList.php?compId={$v['id']}" >ViewOrders</a><br/><a href="/createCompanyOrder.php?c={$v['id']}">AddOrders</a><input type="hidden" id="extra_data" value='{$v['extra_json']}'>

                    //console.log(a);
                    //row[indx] =  "<button type='button' id='edit_button_' onclick='editCompany("+JSON.stringify(a)+")' align='left'>Edit</button>";
                 //var hello = {};
                 //console.log(d[r][c]);
                  //row[indx] =  "<button type='button' id='edit_button_' onclick='return editListing("+ hello+ ")' align='left'>Edit</button>" ;
                  }
                  else
                    row[indx] = d[r][c];
                }
              }
            }
            rows.push(row); // add new row array to rows array
          }
          // in version 2.10, you can optionally return $(rows) a set of table rows within a jQuery object
          return [ total, rows, headers ];
        }
      },

      // output string - default is '{page}/{totalPages}'; possible variables: {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
      output: '{startRow} to {endRow} ({totalRows})',

      // apply disabled classname to the pager arrows when the rows at either extreme is visible - default is true
      updateArrows: true,

      // starting page of the pager (zero based index)
      page: 0,

      // Number of visible rows - default is 10
      size: 25,

      // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
      // table row set to a height to compensate; default is false
      fixedHeight: false,

      // remove rows from the table to speed up the sort of large tables.
      // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
      removeRows: false,

      // css class names of pager arrows
      cssNext        : '.next',  // next page arrow
      cssPrev        : '.prev',  // previous page arrow
      cssFirst       : '.first', // go to first page arrow
      cssLast        : '.last',  // go to last page arrow
      cssPageDisplay : '.pagedisplay', // location of where the "output" is displayed
      cssPageSize    : '.pagesize', // page size selector - select dropdown that sets the "size" option
      cssErrorRow    : 'tablesorter-errorRow', // error information row

      // class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
      cssDisabled    : 'disabled' // Note there is no period "." in front of this class name

    });

});

//js for loading

$body = $("body");
  
  $(document).on({
      ajaxStart: function() { $body.addClass("loading");   $("#lmkSave").attr('disabled', true); $("#exit_button").attr('disabled', true); $("#create_button").attr('disabled', true);
  },
       ajaxStop: function() { $body.removeClass("loading"); $("#lmkSave").attr('disabled', false); $("#exit_button").attr('disabled', false); $("#create_button").attr('disabled', false);
    }  

     
  });

//save form

	$("#lmkSave").click(function(){
		var compType = $('#companyTypeEdit').children(":selected").val();
    if($("#broker_info_status").val()=="Advance")
      var broker_info_type="Advance";
    else if(compType=="Broker" && $("#broker_switch").val()=="Advance Information"){
      var broker_info_type="Basic";
    }
    else if(compType=="Broker" && $("#broker_switch").val()=="Basic Information"){
      var broker_info_type="Advance";
    }
		var name = $('#name').val().trim();        
		var des = $('#des').val().trim();
    
    // Address(HQ) data
    
		var address = $('#address').val().trim();
    var city = $('#city option:selected').val();
		var pincode = $('#pincode').val().trim();
    var compphone = $('#compphone').val().trim();
    var compfax = $('#compfax').val().trim();
    var email = $('#compemail').val().trim();
    var web = $('#web').val();

    var img = $('#uploadedImage').val();
    var sign_up_form = $('#uploadedSignUpForm').val();
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
		window.error = 0;
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
  /*for(var i=0; i<window.offLocTabRowNo; i++){
    var row =  document.getElementById("officeLocRowId_"+i);
    if(row){
      var rowData = { "address" :$("#off_loc_address_id_"+i).text().trim(), c_id:$("#off_loc_city_id_"+i).val(), loc_id:$("#off_loc_loc_id_"+i).val(), db_id:$("#off_loc_dbId_"+i).val()};

      off_loc_data.push(rowData);
        

    }
  }

 //loop through all values of coverage table rows
  for(var i=0; i<window.coverageTabRowNo; i++){
    var row =  document.getElementById("coverageRowId_"+i);
    if(row){
      
      var rowData = { c_id:$("#coverage_city_id_"+i).val(), loc_id:$("#coverage_loc_id_"+i).val(), p_id:$("#coverage_proj_id_"+i).val(), type:$("#coverage_type_"+i).val(), db_id:$("#coverage_db_id_"+i).val() }; 

      coverage_data.push(rowData);

    }
  }*/

 //loop through all values of contact persons rows
  for(var i=0; i<=window.contactTableNo; i++){
    var row =  document.getElementById("rowId_"+i);
    if(row){
      if($("#person_"+i).val().trim() != ''){
        var rowData = { person:$("#person_"+i).val().trim(), phone1:$("#phone1_"+i).val().trim(), phone2:$("#phone2_"+i).val().trim(), mobile:$("#mobile_"+i).val().trim(), fax:$("#fax_"+i).val().trim(), email:$("#email_"+i).val().trim()};
        contact_person_data.push(rowData);
        valid_noncompul($("#phone1_"+i).val().trim(), "Please provide a numeric Phone No.", "errmsgphone1_"+i);
        valid_noncompul($("#phone2_"+i).val().trim(), "Please provide a numeric Phone No.", "errmsgphone2_"+i);
        valid_noncompul($("#mobile_"+i).val().trim(), "Please provide a numeric Mobile No.", "errmsgmobile_"+i);
        valid_noncompul($("#fax_"+i).val().trim(), "Please provide a numeric fax No.", "errmsgfax_"+i);
      }
    }
  }

//get customer care data

 /* var cust_care_data = { phone:$("#cc_phone").val().trim(),  mobile:$("#cc_mobile").val().trim(), fax:$("#cc_fax").val().trim() };
  valid_noncompul($("#cc_phone").val().trim(), "Please provide a numeric phone no.", "errmsgcc_phone");
  valid_noncompul($("#cc_mobile").val().trim(), "Please provide a numeric mobile no.", "errmsgcc_mobile");
  valid_noncompul($("#cc_fax").val().trim(), "Please provide a numeric fax no.", "errmsgcc_fax");
*/

// broker extra fields
  

if(compType=='Broker'){
  var projectType = [];
  $(".resiProjectType").each(function(){
    if($(this).is(':checked')){
      var v = $(this).val();
      var a = { id: "pt_db_id_"+v, val:v};
      projectType.push(v);
    }
  })


  var transactionType = [];
  $(".Transaction").each(function(){
    if($(this).is(':checked')){
      var v = $(this).val();
      var a = { id: "tt_db_id_"+v, val:v};
      transactionType.push(v);
    }
  });

var device = [];
  $(".device").each(function(){
    if($(this).is(':checked')){
      var v = $(this).val();
      //var a = { id: "tt_db_id_"+v, val:v};
      device.push(v);
    }
  });
  console.log(device);
  var bd_id = $('#bd_id').val(); 
  var legalType = $('#compLegalType').children(":selected").val();
  var frating = $('#frating').children(":selected").val();

  var bankId = $('#bankName').children(":selected").val();
  var accountNo = $('#accountNo').val().trim();
  var accountType = $('#accountType').children(":selected").val();
  var ifscCode = $('#ifscCode').val().trim();
  var bank_details = '';
  if(bankId || accountNo || accountType || ifscCode)
   bank_details = { bankId:bankId, accountNo:accountNo, accountType:accountType, ifscCode:ifscCode };

  var formSignUpDate = $('#img_date2').val(); 
  var signUpBranch = $('#signUpBranch :selected').val();

  var since_op = $('#img_date1').val(); 
  var stn = $('#stn').val();
  var officeSize = $('#officeSize').val();
  var employeeNo = $('#employeeNo').val();
  var ptManager = $('#ptManager').children(":selected").val();
  if($('input:radio[name=relative]:checked').val() == "Yes")
    var ptRelative = $('#ptRelative').children(":selected").val();
  else
    var ptRelative = 0;
   
  //var broker_extra_fields = { id:bd_id, legalType:legalType, projectType:projectType, transactionType:transactionType, frating:frating, since_op:since_op, stn:stn, officeSize:officeSize, employeeNo:employeeNo, ptManager:ptManager };

  var broker_extra_fields = { id:bd_id, legalType:legalType, projectType:projectType, transactionType:transactionType, frating:frating, formSignUpDate:formSignUpDate, signUpBranch:signUpBranch, device:device, since_op:since_op, ptManager:ptManager, ptRelative:ptRelative };

  if (broker_info_type=="Advance"){
    //valid_compul(since_op, isDate, "Please provide a valid date.", "errmsgdate");
    /*valid_compul(stn, isAlphaNumeric, "Please provide a numeric service tax no.", "errmsgstn");
    valid_compul(officeSize, isNumeric1, "Please provide a no.", "errmsgofficesize");
    valid_compul(employeeNo, isNumeric1, "Please provide a no.", "errmsgemployeeNo");*/
    valid_compul(ptManager, isNumeric1, "Please select a Proptiger Manager.", "errmsgptmanager");
    //valid_compul(transactionType, valid_tt_type, "Please select a transaction type.", "errmsgtttype");
  }
}

  //console.log(coverage_data);
  //console.log(contact_person_data); 
    
   //var data = { id:compid, type:compType, broker_info_type:broker_info_type, name:name, des:des, address : address, city:city, pincode : pincode, compphone : compphone, compfax:compfax, email:email, web:web, image:img, imageId:imgId, ipArr : ipArr, off_loc_data:off_loc_data, coverage_data:coverage_data, contact_person_data:contact_person_data, cust_care_data:cust_care_data, broker_extra_fields:broker_extra_fields, pan:pan, status:status, task : "createComp", mode:mode}; 

   var data = { id:compid, type:compType, broker_info_type:broker_info_type, name:name, des:des, address : address, city:city, pincode : pincode, compphone : compphone, compfax:compfax, email:email, web:web, image:img, signUpForm:sign_up_form, imageId:imgId, ipArr : ipArr, contact_person_data:contact_person_data, broker_extra_fields:broker_extra_fields, pan:pan, status:status, bank_details:bank_details, task : "createComp", mode:mode}; 

/******************************validation****************************************/    

  function valid_tt_type(arr){
    if (arr.length==0) 
      return false;

    for(var i=0; i<arr.length; i++){
      if(!isNumeric(arr[i]))
        return false;
    }

    return true;

  }
    

    if(pan!='' && !validatePan(pan)){
      $('#errmsgpan').html('<font color="red">Please provide all alpha Numeric Characters.</font>');
      $("#pan").focus();
      window.error = 1;
    }
    else{
          $('#errmsgpan').html('');
    }

    if(email!='' && !validateEmail(email)){
      $('#errmsgcompemail').html('<font color="red">Please provide a vaild email id.</font>');
      $("#compemail").focus();
      window.error = 1;
    }
    else{
          $('#errmsgcompemail').html('');
    }

    if(compfax!='' && !isNumeric1(compfax)){
      $('#errmsgcompfax').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#compfax").focus();
      window.error = 1;
    }
    else{
          $('#errmsgcompfax').html('');
    }


 
    for (var i = 0; i < ipArr.length; i++) {
      if(ipArr[i]!='' && !ValidateIPaddress(ipArr[i])) {
        $('#errmsgip_'+i).html('<font color="red">Please provide a valid IP.</font>');
        $("#ip_"+i).focus();
        window.error = 1;
      }
      else{
            $('#errmsgip_'+i).html('');
      }
       
    }

    if(email != '' && !validateEmail(email)){
	   $('#errmsgemail').html('<font color="red">Please provide a Valid Contact Email.</font>');
      $("#email").focus();
      error = 1;	
	  }

    /*if(compphone==''){
      $('#errmsgcompphone').html('<font color="red">Please provide an Office Phone no.</font>');
      $("#compphone").focus();
      window.error = 1;
    }
    else*/ if(compphone!='' && !isNumeric1(compphone)){
      $('#errmsgcompphone').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#compphone").focus();
      window.error = 1;
    }
    else{
          $('#errmsgcompphone').html('');
    }

    if(pincode!='' && !isNumeric(pincode)){
      $('#errmsgpincode').html('<font color="red">Please provide a Numeric Value.</font>');
      $("#pincode").focus();
      window.error = 1;
    }
    else{
          $('#errmsgpincode').html('');
    }

    if(city <= 0 || city=='') {
      $('#errmsgcity').html('<font color="red">Please select a City.</font>');
      $("#city").focus();
      window.error = 1;
    }
    else{
          $('#errmsgcity').html('');
    }

    if(address==''){
      $('#errmsgaddress').html('<font color="red">Please provide an Address for the company</font>');
      $("#address").focus();
      window.error = 1;
    }
    else{
          $('#errmsgaddress').html('');
    }

    if(legalType=='' && compType=="Broker"){
      $('#errmsgcomplegaltype').html('<font color="red">Please provide a Company legal type.</font>');
      $("#compLegalType").focus();
      window.error = 1;
    }
    else{
          $('#errmsgcomplegaltype').html('');
    }

    if(name==''){
      $('#errmsgname').html('<font color="red">Please provide a Company Name.</font>');
      $("#name").focus();
      window.error = 1;
    }
    else{
          $('#errmsgname').html('');
    }

    if(compType==''){
      $('#errmsgcomptype').html('<font color="red">Please select a Company Type.</font>');
      $("#companyTypeEdit").focus();
      window.error = 1;
    }
    else{
          $('#errmsgcomptype').html('');
    }

   /* if($("#imgUploadStatus").val()=="0"){
      window.error = 1;
      $('#errmsglogo').html('<font color="red">Please upload a Company Logo.</font>');
    }
  */






   
var $body = $("body");
	    if (window.error==0){
      
	      	$.ajax({ 
	            type: "POST",
	            url: "/saveCompany.php",
	            data: data,

              beforeSend: function(){
                console.log('in ajax beforeSend');
                $("body").addClass("loading");
                $("#lmkSave").attr('disabled','disabled');
              },
              

	            success:function(msg){
                console.log("msg"+msg);
                $("body").removeClass("loading");
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
                           $("#lmkSave").removeAttr('disabled');
	               }
	               else if(msg == 4){
	                //$("#onclick-create").text("No Landmark Selected.");
	                   alert("no data");
                           $("#lmkSave").removeAttr('disabled');
	               }
	               else if(msg == 9){
	                  alert("Company with Same Contact Email Already Exist!");
                          $("#lmkSave").removeAttr('disabled');
	               }
	               else{ 
                           alert(msg);
                           $("#lmkSave").removeAttr('disabled');
                       };
                       
	            },
	        });

	    }


	});

$.widget( "custom.catcomplete2", $.ui.autocomplete, {
   
  _renderItem: function( ul, item ) {
    //var res = item..split("-");
        //var tableName = res[1];
    return $( "<li>" )
      .append( $( "<a>" ).text( item.label ) )
      .appendTo( ul );
  },
  

  });
  
  $( "#name" ).autocomplete({
     // q = $("#searchPlace").val();
     // alert("hello");
      source: function( request, response ) {
        $.ajax({
          url: "/saveCompany.php",
          dataType: "json",
          type: "post",
          data: {
            query: $( "#name" ).val().trim(),
            task: "find_company_name",
            featureClass: "P",
            style: "full",
            name_startsWith: request.term
          },
          success: function( data ) {
            //alert(data);
            response( $.map( data.data, function( item ) {  
              console.log(item.name)            
                return {
                label: item.name,
                value: item.name,
                }
              
            }));
          }
        });
      },
      
      select: function( event, ui ) {
        selectedItem = ui.item;
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


    $(".relative").click(function(){
      if($('input:radio[name=relative]:checked').val() == "Yes")
          $("#ptRelative").show();
      else
        $("#ptRelative").hide();
      
    });


  //$("#lmkSave").click(function(){

  //});

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
function isAlphaNumeric(val){
  return true;
}

function isDate(val){
  if(val=="")
    return false
  else
  return true;
}




function valid_noncompul(v, msg, msgid ){
  console.log(v +":"+msgid);
  if(v!='' && !isNumeric1(v)){
      $('#'+msgid).html('<font color="red">'+msg+'</font>');
      //$("#phone").focus();
      window.error = 1;
    }
    else{
          $('#'+msgid).html('');
    }
}

function valid_compul(v, fun, msg, msgid ){
  var bool = fun(v);
    if(v==''){
      $('#'+msgid).html('<font color="red">'+msg+'</font>');
      //$("#address").focus();
      window.error = 1;
    }
    else if(v!='' && !bool){
      $('#'+msgid).html('<font color="red">'+msg+'</font>');
      //$("#phone").focus();
      window.error = 1;
    }
    else{
          $('#'+msgid).html('')
;    }
}

function ValidateIPaddress(ipaddress)   
{  
 if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress))  
  {  
    return (true)  
  }  
  return (false)  
} 

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatePan(pan) { 
    var re = /^[a-zA-Z\d-]*$/;
    return re.test(pan);
}

function cleanFields(){
    $("#compid").val('');
    $('#companyTypeEdit').val('');
    $("#broker_info_status").val('');
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

    $('#create_company input text,#create_company select,#create_company textarea, :text').each(function(key, value){
      $(this).val('');       
    }); 

    $(".resiProjectType").each(function(){
          $(this).prop('checked', false);
    });


    $(".Transaction").each(function(){
          $(this).prop('checked', false);
    });

    /*$("#coverage_table tbody tr").remove();
    $("#off_loc_table").find("tr").remove();*/

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
    $('#errmsgcomplegaltype').html('');
    $('#errmsgpan').html('');
    $('#errmsgemail').html('');
    //$('#errmsgemployeeNo').html('');
    $('#errmsgtttype').html('');
    $('#errmsgptmanager').html('');
    //$('#errmsgofficesize').html('');
    //$('#errmsgstn').html('');
    $('#errmsgdate').html('');
    

}



function editCompany(str, action){
  //id,name,type, broker_info_type, des, status, pan, email, address, city, pin, compphone, imgpath, imgid, imgalttext, ipsstr, person, compfax, phone, active_since, web, a, action

    cleanFields();
    str = JSON.parse(unescape(str));
    console.log("here123");
console.log(str);
    var id = str.id;
    var name = str.name;
    var type = str.type;
    var broker_info_type = str.broker_info_type;
    var des = str.des;
    var status = str.status;
    var pan = str.pan;
    var email = str.email;
    var address = str.address;
    var city = str.city;
    var pin = str.pin;
    var compphone = str.compphone;
    var imgpath = str.service_image_path;
    var imgid = str.image_id;
    var imgalttext = str.alt_text;
    var ipsstr = str.ipsstr;
    var person = str.person;
    var compfax = str.compfax;
    var phone = str.phone;
    var active_since = str.active_since;
    var web = str.web;
    var a = str.extra_json;


    $("#compid").val(id);
    $("#brokerId").val(id); //console.log( $("#brokerId").val());
    $('#city').val(city);
    $("#companyTypeEdit").val(type);
    $("#broker_info_status").val(broker_info_type);
    if (broker_info_type=="Basic"){
      $("#broker_switch").show();
      $('#main_table tr').not('.broker_basic').hide();
      $('#broker_extra_field').show();
      $('#broker_table_extra tbody tr').show();
      $('#broker_table_extra tbody tr').not('.broker_basic').hide();
      $("#broker_switch").prop("value","Advance Information");
      //basic_info_bt_clicked();
    }
    else{
      $("#broker_switch").prop("value","Advance Information");
      $("#broker_switch").prop("disabled", true);
      $("#broker_switch").hide();
    }
    if(type=="Broker"){
      //console.log("here"+type);
      $("#broker_id").show();
    }
    $("#name").val(name);
    $("#des").val(des);
    $("#address").val(address);
    $("#pincode").val(pin);
    $("#compphone").val(compphone);
    $("#compfax").val(compfax);
    $("#web").val(web);
    $("#compemail").val(email);
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

    //$("#person").val(person);
    //$("#phone").val(phone);
    
    $("#status").val(status);
    
   
    $("#pan").val(pan);

   // $('#img_date1').val(active_since.substring(0,10)); 
   $('#img_date1').val(active_since); 
    //var data = a;
    if(type=="Broker"){
      $("#broker_extra_field").show();
      $("#legalType").show();
    }
    else{
      $("#broker_extra_field").hide();
      $("#legalType").hide();
    }
    //console.log("here");


    
    //a = JSON.parse(unescape(a));
    //a = JSON.stringify(a);
    a = $('<div/>').html(a).text();
    a = eval('('+a+')'); 
    //console.log(a);
    //console.log("here1");
    //var data = $("#extra_data").val();
    
    //var data = eval('("data":'+ a+ ') ');
    //console.log(a.data);
    var cP =  a.data.cont_person;
    for(var i=0; i<cP.length; i++){
      if(i>0)
      addContactTable("contact_table");
      $("#id_"+i).val(cP[i].id);
      $("#person_"+i).val(cP[i].person);
      $("#email_"+i).val(cP[i].email);
      $("#phone1_"+i).val(cP[i].phone1);
      $("#phone1_id_"+i).val(cP[i].phone1_id);
      $("#phone2_"+i).val(cP[i].phone2);
      $("#phone2_id_"+i).val(cP[i].phone2_id);
      $("#mobile_"+i).val(cP[i].mobile);
      $("#mobile_id_"+i).val(cP[i].mobile_id);
      $("#fax_"+i).val(cP[i].fax);
      $("#fax_id_"+i).val(cP[i].fax_id);
    }

    var offLoc = a.data.off_loc; 
    var tableId = "off_loc_table";
    for(var i=0; i<offLoc.length; i++){
      var data = { table_id:tableId, dbId:offLoc[i].id, first:{ checkbox:"checkbox", class:"class" }, second:{ label:"label", text:offLoc[i].c_name, }, third:{ label:"label", text:offLoc[i].loc_name,}, fourth:{ label:"label", text:offLoc[i].address,}, city_id:offLoc[i].c_id, loc_id:offLoc[i].loc_id};
        //addOfficeLocRow(data);
    }


    var c = a.data.coverage; 
    var tableId = "coverage_table"; var type=''; var p_name=''; var b_name='';
    for(var i=0; i<c.length; i++){
      if(c[i].b_id=='0' && c[i].p_id=='0'){
        type='all';
        p_name='All'; b_name='All';
      }
      else if(c[i].b_id=='0'){
        type='project'; 
        p_name=c[i].p_name; b_name='';
      }
      else if(c[i].p_id=='0'){
        type='builder';
        p_name='All Projects of'; b_name=c[i].b_name;
      }
      var data = { table_id:tableId, dbId:c[i].id, first:{ checkbox:"checkbox", class:"class" }, second:{ label:"label", text:c[i].c_name, }, third:{ label:"label", text:c[i].loc_name,}, fourth:{ label:"label", text:p_name,}, fifth:{ label:"label", text:b_name,}, city_id:c[i].c_id, loc_id:c[i].loc_id, p_id:c[i].p_id, type:type};
        //addCoverageRow(data);
    }

    var cc = a.data.cust_care;  
     /* $("#cc_phone").val(cc.phone);
      $("#cc_phone_id").val(cc.phone_id);
      $("#cc_mobile").val(cc.mobile);
      $("#cc_mobile_id").val(cc.mobile_id);
      $("#cc_fax").val(cc.fax);
      $("#cc_fax_id").val(cc.fax_id);*/


    var pT = a.data.broker_prop_type;
    $(".resiProjectType").each(function(){
      for(var i=0; i<pT.length; i++){
        if( $(this).val()==pT[i].broker_property_type_id ){
          $(this).prop('checked', true);
          $("#pt_db_id_"+$(this).val()).val(pT[i].id);
        }
      }
    });


    var tT = a.data.transac_type;
    $(".Transaction").each(function(){
      for(var i=0; i<tT.length; i++){
         if( $(this).val()==tT[i].transaction_type_id ){
          $(this).prop('checked', true);
          $("#tt_db_id_"+$(this).val()).val(tT[i].id);
        }
      }
    });

    var devices = a.data.devices;
    $(".device").each(function(){
      for(var i=0; i<devices.length; i++){
         if( $(this).val()==devices[i].device_id ){
          $(this).prop('checked', true);
          //$("#tt_db_id_"+$(this).val()).val(tT[i].id);
        }
      }
    });

  var bD = a.data.broker_details; 
  $('#bd_id').val(bD.id);
  $('#compLegalType').val(bD.legal_type);
  $('#frating').val(bD.rating);
  //$('#stn').val(bD.service_tax_no);
  //$('#officeSize').val(bD.office_size); 
  //$('#employeeNo').val(bD.employee_no);
  $('#ptManager').val(bD.pt_manager_id);
  $('#ptRelative').val(bD.pt_relative_id);
  $('#img_date2').val(bD.form_signup_date);
  $('#signUpBranch').val(bD.form_signup_branch);
  //$('#device').val(bD.primary_device_used);

  if(bD.pt_relative_id>0){
    $("#relative_yes").prop("checked", true);
    $("#ptRelative").show();
  }

  var bankDetails = a.data.bank_details; 
  //$('#bd_id').val(bD.id);
  $('#bankName').val(bankDetails.bank_id);
  $('#accountNo').val(bankDetails.account_no);
  $('#accountType').val(bankDetails.account_type);
  $('#ifscCode').val(bankDetails.ifsc_code);





    //$('#search-top').hide('slow');
    $('#search_bottom').hide('slow');
    window.scrollTo(0, 0);

    if($('#create_company').css('display') == 'none'){ 
     $('#create_company').show('slow'); 
    }
console.log("here2");
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
    console.log("here3");
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
function validateEmail(email) 
  {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
  }

jQuery(function(){
                iframeUpload.init();
                iframeUploadSignUpForm.init();
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
        var response = jQuery("iframe[name=uploadiframe]").contents().text();
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



var iframeUploadSignUpForm = {
    init: function() {
        jQuery('#uploadSignUpForm').append('<iframe name="uploadiframeSignup" onload="iframeUploadSignUpForm.complete();"></iframe>');
        jQuery('form.uploadSignUpForm').attr('target','uploadiframeSignup');
        //jQuery(document).on('submit', 'form.uploadForm', iframeUpload.started);
    },
    started: function() {
        jQuery('#response').removeClass().addClass('loading').html('Loading, please wait.').show();
        jQuery('#uploadSignUpForm').hide();
    },
    complete: function(){
        jQuery('#uploadSignUpForm').show();
        var response = jQuery("iframe[name=uploadiframeSignup]").contents().text();
        if(response){
            response = jQuery.parseJSON(response);
            if(response.status == 1){
              $("#errmsgsignupform").html('<font color="green">Image Successfully Uploaded.</font>');
              $("#signUpFormUploadStatus").val("1");
              $("#uploadedSignUpForm").val(response.image);

            }
            else{
              $("#errmsgsignupform").html('<font color="red">Image Upload Failed.</font>');
              $("#signUpFormUploadStatus").val("0");
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
            addContactRow(element1.id, "Contact Phone 1", "phone1", "errmsgphone1", no);
            addContactRow(element1.id, "Contact Phone 2", "phone2", "errmsgphone2", no);
            addContactRow(element1.id, "Contact Mobile", "mobile", "errmsgmobile", no);
            addContactRow(element1.id, "Contact Fax", "fax", "errmsgfax", no);
            addContactRow(element1.id, "Contact Email", "email", "errmsgemail", no);
            addDeleteButton(element1.id, "deleteContact", no);
            addHiddenRow(element1.id, "person_id");
            addHiddenRow(element1.id, "phone1_id");
            addHiddenRow(element1.id, "phone2_id");
            addHiddenRow(element1.id, "mobile_id");
            addHiddenRow(element1.id, "fax_id");
           

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

function addHiddenRow(tableId, inputClass){
      var table = document.getElementById(tableId); 
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            var cell1 = row.insertCell(0);
            cell1.width = "20%";
            var element1 = document.createElement("input");
            element1.type = "hidden";
            //element2.style.width="250px";
            element1.id=inputClass+"_"+no;
            element1.className=inputClass;
            cell1.appendChild(element1);
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
   var data = { cityId:cityId, task:"office_locations"};
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
  var data = { table_id:tableId, dbId:"", first:{ checkbox:"checkbox", class:"class" }, second:{ label:"label", text:cityName, }, third:{ label:"label", text:locName,}, fourth:{ label:"label", text:address,}, city_id:cityId, loc_id:locId};

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

            var element7 = document.createElement("input");
            element7.type = "hidden";
            element7.value = data.dbId;
            //element6.className = " off_loc__class";
            element7.id = "off_loc_dbId_"+window.offLocTabRowNo;
            cell4.appendChild(element7);


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
    var bName = "All";
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
    var data = { table_id:tableId, dbId:'', first:{ checkbox:"checkbox", class:"class" }, second:{ label:"label", text:cityName, }, third:{ label:"label", text:locName,}, fourth:{ label:"label", text:projName,}, fifth:{ label:"label", text:bName,}, city_id:cityId, loc_id:locId, p_id:projId, type:type};
  

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

            var element10 = document.createElement("input");
            element10.type = "hidden";
            element10.value = data.dbId;
            element10.id = "coverage_db_id_"+window.coverageTabRowNo;
            cell4.appendChild(element10);

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

function companyTypeChanged(){
  if($('#companyTypeEdit').children(":selected").val()=="Broker"){
    $("#broker_extra_field").show();
    $("#legalType").show();
    $("#broker_switch").show();
    $("#broker_switch").val("Basic Information");
    $('.broker_basic').show();
    if (compid>0 && broker_info_status=="Basic"){
      $('#main_table tr').not('.broker_basic').hide();
      $('#broker_extra_field').show();
      $('#broker_table_extra tbody tr').show();
      $('#broker_table_extra tbody tr').not('.broker_basic').hide();
      $("#broker_switch").prop("value","Advance Information");
    }
    if(("#brokerId").val()!='')
      ("#broker_id").show();
  }
  else{
    $("#broker_switch").hide();
    $('#main_table tbody tr').show();
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

function basic_info_bt_clicked(){

  
  //$("#main_table tr").hide();
  //$("#main_table tr.broker_basic").show();
  //$("#broker_switch").prop("value", "Advanced Information");
  var value = $("#broker_switch").val();
  var compid = $('#compid').val();
  var broker_info_status = $('#broker_info_status').val();
 /* if (compid>0 && broker_info_status=="Basic"){
    $('#main_table tr').not('.broker_basic').hide();
    $('#broker_extra_field').show();
    $('#broker_table_extra tbody tr').show();
    $('#broker_table_extra tbody tr').not('.broker_basic').hide();
    $("#broker_switch").prop("value","Advance Information");
  }
   // var value = "Basic Information";//alert(value);
  else
  */ 

    if(value!="Basic Information"){
      $('#main_table tbody tr').show();
      $("#broker_switch").prop("value","Basic Information");
    }
    else{
      $('#main_table tr').not('.broker_basic').hide();
      $('#broker_extra_field').show();
      $('#broker_table_extra tbody tr').show();
      $('#broker_table_extra tbody tr').not('.broker_basic').hide();
      $("#broker_switch").prop("value","Advance Information");
    }

    if(("#brokerId").val()!='')
      ("#broker_id").show();
  
  
 //$('#main_table tr.broker_basic').show();
  //$('#main_table tr#broker_extra_field').not('.broker_basic_extra').hide();
 //$(".broker_basic").show()

}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 id="main_table">
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <input type="hidden" name="old_sub_name" value="">
                    <div>
                    
                    <tr class="broker_basic">
                      <td width="10%" align="right" ><font color = "red">*</font>Company Type: </td>
                        <td width="20%" height="25" align="left" valign="top">
                                    <select id="companyTypeEdit" name="companyEdit" onchange="companyTypeChanged();">
                                       <option value=''>select Company Type</option>
                                       {foreach from=$comptype key=k item=v}
                                              <option value="{$v}" {if "" ==$v}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select>
                                </td>
                        <td width="40%" align="left" id="errmsgcomptype"></td>        
                        <td width="40%" align="left"> <input type="button" name="broker_switch" id="broker_switch" value="Basic Information" onclick="basic_info_bt_clicked();" style="cursor:pointer" ><input type="hidden", id="broker_info_status"> </td>
                       
                    </tr>
                    <tr class="broker_basic">
                      <div class="ui-widget">
                      <td width="10%" align="right" ><font color = "red">*</font>Name : </td>
                      <td width="40%" align="left" ><input type=text name="name" class="broker_basic" id="name"  style="width:250px;"></td> </div><td width="40%" align="left" id="errmsgname"></td>
                      <td><input type="hidden", id="compid"></td>
                    </tr>

                    <tr id="broker_id" style="display:none" class="broker_basic">
                      <div class="ui-widget">
                      <td width="10%" align="right" ><font color = "red"></font>Broker Id : </td>
                      <td width="40%" align="left" ><input type=text name="name" class="broker_basic" id="brokerId" readonly="readonly" style="width:250px;" ></td> </div><td width="40%" align="left" id="errmsgname"></td>
                     
                    </tr>

                    <tr id="legalType" style="display:none" class="broker_basic">
                      <td width="10%" align="right" ><font color = "red">*</font>Company Legal Type : </td>
                      <td width="30%" align="left"><select id="compLegalType" name="compLegalType" class="broker_basic">
                        <option name=one value=''>Select Company Legal Type</option>
                        <option name=one value='proprietorship'>Proprietorship</option>
                        <option name=one value='partnership'>Partnership</option>
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

                   

                    <tr class="broker_basic">
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>Address :</td>
                      <td width="30%" align="left" >
                      <textarea name="address" rows="8" cols="35" class="broker_basic" id="address" style="width:250px;"></textarea></td>
                      <td width="20%" align="left" id="errmsgaddress"></td>
                   
                    </tr>

                    <tr class="broker_basic">
                      <td width="20%" align="right" valign="top"><font color = "red">*</font>City :</td>
                      <td width="30%" align="left" ><select id="city" name="city" >
                                       <option value=''>select city</option>
                                       {foreach from=$cityArray key=k item=v}
                                           <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                       {/foreach}
                                    </select></td>
                      <td width="20%" align="left" class="broker_basic" id="errmsgcity"></td>
                      
                      <td><input type="hidden", id="lmkid">  </td>
                    </tr>

                    <tr class="broker_basic">
                      <td width="20%" align="right" >Pincode : </td>
                      <td width="30%" align="left"><input type=text name="pincode" class="broker_basic" id="pincode"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgpincode"></td>
                    </tr>


                    <tr class="broker_basic">
                      <td width="20%" align="right" ><font color = "red"></font>Office Phone No. : </td>
                      <td width="30%" align="left"><input type=text name="compphone" class="broker_basic" id="compphone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcompphone"></td>
                    </tr>

                    <tr class="broker_basic">
                      <td width="20%" align="right" valign="top">Office Fax :</td>
                     <td width="30%" align="left"><input type=text name="compfax" class="broker_basic" id="compfax" style="width:250px;"></td> 
                    <td width="20%" align="left" id="errmsgcompfax"></td>
                    </tr>

                    <tr class="broker_basic">
                      <td width="20%" align="right" >Office Email : </td>
                      <td width="30%" align="left"><input type=text name="compemail" class="broker_basic" id="compemail" style="width:250px;"></td> <td width="20%" align="left" id="errmsgcompemail"></td>
                    </tr>

                    <tr class="broker_basic">
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="web" class="broker_basic" id="web" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
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
                    <tr class="broker_basic">
                      <td colspan="3" align="left" ><hr><b>Contact Person Details</b></td>
                    </tr>

                    <tr class="broker_basic">
                      <td colspan="3" align="left">
                        <table id="contact_table" width = "100%">
                          <tr id="rowId_0">
                          <td colspan="3" align="left">
                          <table id="contact_table_0" width = "100%" >
                            <tr>
                              <td width="20%" align="right" valign="top">Name : </td>
                              <td width="30%" align="left"><input type=text name="person" id="person_0" style="width:250px;"></td> <td width="50%" align="left" id="errmsgname_0"></td>
                              </td>
                        
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Phone 1 : </td>
                              <td width="30%" align="left"><input type=text name="phone" id="phone1_0"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgphone1_0"></td>
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Phone 2 : </td>
                              <td width="30%" align="left"><input type=text name="phone" id="phone2_0"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgphone2_0"></td>
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Mobile : </td>
                              <td width="30%" align="left"><input type=text name="phone" id="mobile_0"  style="width:250px;"></td> <td width="50%" align="left" id="errmsgmobile_0"></td>
                            </tr>


                            <tr>
                              <td width="20%" align="right" valign="top">Contact Fax : </td>
                             <td width="30%" align="left"><input type=text name="fax" id="fax_0" style="width:250px;"></td> 
                            <td width="50%" align="left" id="errmsgfax_0"></td>
                            </tr>

                            <tr>
                              <td width="20%" align="right" >Contact Email : </td>
                              <td width="30%" align="left"><input type=text name="email" id="email_0" style="width:250px;"></td> <td width="50%" align="left" id="errmsgemail_0"></td>
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

                    <tr class="broker_basic">
                     
                      <td align="left" style="padding-left:50px;" colspan="3" >
                      <input type="button" name="addContact" id="addContact" value="Add Contact Person" style="cursor:pointer">                
                      </td>
                    </tr>


<!--  customer care starts --------------------------------------------------------------------     -->
                    <!-- <tr height="15">
                      <td colspan="3" align="left" ><hr><b>Customer Care Details</b></td>
                    </tr>  -->

                    <!--<tr>
                      <td width="20%" align="right" >Website : </td>
                      <td width="30%" align="left"><input type=text name="web" id="web" style="width:250px;"></td> <td width="20%" align="left" id="errmsgweb"></td>
                    </tr>-->

                    
                    <!-- <tr>

                      <td width="20%" align="right" >Cust Care Phone : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="cc_phone"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcc_phone"><input type=hidden name="phone" id="cc_phone_id"></td>

                    </tr>

                    <tr>
                      <td width="20%" align="right" >Cust Care Mobile : </td>
                      <td width="30%" align="left"><input type=text name="phone" id="cc_mobile"  style="width:250px;"></td> <td width="20%" align="left" id="errmsgcc_mobile"><input type=hidden name="phone" id="cc_mobile_id"></td>
                    </tr>

                    <tr>
                      <td width="20%" align="right" valign="top">Cust Care Fax :</td>
                     <td width="30%" align="left"><input type=text name="fax" id="cc_fax" style="width:250px;"></td> 
                    <td width="20%" align="left" id="errmsgcc_fax"><input type=hidden name="phone" id="cc_fax_id"></td>
                    </tr> -->

                   <!--<tr>
                      <td width="20%" align="right" >Cust Care Email : </td>
                      <td width="30%" align="left"><input type=text name="email" id="cc_email" style="width:250px;"></td> <td width="20%" align="left" id="errmsgcc_email"></td>
                    </tr>-->

<!--   office locations --------------------------------------------------------------     -->
                   <!-- <tr height="15">
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
                    </tr> -->


<!--  coverage ----------------------------------------------------------------------> 
                    <!-- <tr height="15">
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
                    </tr> -->


<!--  coverage ends --------------------------------------------------------------------     -->                    
                    <tr height="15">
                      <td colspan="3" align="left" ><hr></td>
                    </tr> 

                    <tr>
                      <td width="20%" align="right" >Pancard No : </td>
                      <td width="30%" align="left"><input type=text name="pan" id="pan" style="width:250px;"></td> <td width="20%" align="left" id="errmsgpan"></td>
                    </tr>

                    <tr class="broker_basic">
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

                    <tr class="broker_basic" id="broker_extra_field" style="display:none" >
                      <td colspan="3">
                        <table width="100%" id="broker_table_extra">
                        
                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">Properties Broker Deals In : </td>
                          <td width="30%" align="left">
                            <table width="100%">
                              {$i=0}
                            {foreach $resiProjectType key=k item=v}
                            {$i=$i+1}
                              {if $i%2!=0}<tr>{/if}
                              <td >
                              <input type='checkbox' name='resiProjectType[]' class='resiProjectType'  value='{$k}' >{$v}<input type='hidden' id="pt_db_id_{$k}"> </td>
                               {if $i%2==0}</tr>{/if}
                            {/foreach}
                          </table>
                          </td>
                          <tr></td>
                        </tr>
                        
                        <tr class="broker_basic_extra">
                          <td width="10%" align="right" valign="top"><font color = "red"></font>Transaction Types : </td>
                          <td width="30%" align="left">
                            <table width="100%">
                              {$i=0}
                            {foreach $transactionType key=k item=v}
                              {$i=$i+1}
                              {if $i%2!=0}<tr>{/if}
                              <td >
                            <input type='checkbox' name='Transaction[]' class='Transaction' value='{$k}'>{$v}<input type='hidden' id="tt_db_id_{$k}"> </td>
                            {if $i%2==0}</tr>{/if}
                            {/foreach}
                          </table>
                          </td> 
                          <td width="40%" align="left" id="errmsgtttype"></td>
                         </tr> 

                         <tr>
                          <td width="10%" align="right"  valign="top">Rating : </td>
                          <td width="30%" align="left">
                              <input type="radio" name="rating" id="rating_auto" disabled="disabled">Auto &nbsp; &nbsp; <br>
                              <input type="radio" name="rating" value="forced" checked='checked' disabled="disabled">Forced &nbsp; &nbsp;
                              <select id="frating" name="frating" style="width:70px;" valign="center">
                            <option name=one value='0.00'>0</option>
                            <option name=two value='0.50' >0.5</option>
                            <option name=one value='1.00'>1.0</option>
                            <option name=two value='1.50' >1.5</option>
                            <option name=one value='2.00'>2.0</option>
                            <option name=two value='2.50' >2.5</option>
                            <option name=one value='3.00'>3.0</option>
                            <option name=two value='3.50' >3.5</option>
                            <option name=one value='4.00'>4.0</option>
                            <option name=two value='4.50' >4.5</option>
                            <option name=one value='5.00'>5.0</option>
                            <option name=two value='5.50' >5.5</option>
                            <option name=one value='6.00'>6.0</option>
                            <option name=two value='6.50' >6.5</option>
                            <option name=one value='7.00'>7.0</option>
                            <option name=two value='7.50' >7.5</option>
                            <option name=one value='8.00'>8.0</option>
                            <option name=two value='8.50' >8.5</option>
                            <option name=one value='9.00'>9.0</option>
                            <option name=two value='9.50' >9.5</option>
                            <option name=one value='10.00'>10.0</option>
                            </select>
                          </td>
                        </tr>

                    <tr class="broker_basic_extra">
                      <td colspan="3" align="left" ><hr><b>Broker Bank Details</b></td>
                    </tr>
                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">Bank Name: </td>
                          <td width="30%" align="left">
                              <select name="bankName" id="bankName" height="5px" width="200px" >
                                  <option value=''> select bank </option>
                                    {foreach from=$bankArray key=k item=v}
                                        <option value="{$k}" >{$v}</option>
                                    {/foreach}
                              </select>
                          </td>
                          <td width="20%" align="left" id="errmsgbankname"></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">Account No: </td>
                          <td width="30%" align="left">
                             <input type=text name="accountNo" id="accountNo" style="width:250px;">
                          </td>
                          <td width="20%" align="left" id="errmsgaccountno"></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">Account Type: </td>
                          <td width="30%" align="left">
                             <select name="accountType" id="accountType" height="5px" width="200px" >
                                  <option value=''> select </option>
                                    {foreach from=$bankAccountType key=k item=v}
                                        <option value="{$v}">{$v}</option>
                                    {/foreach}
                              </select>
                          </td>
                          <td width="20%" align="left" id="errmsgaccounttype"></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">IFSC Code: </td>
                          <td width="30%" align="left">
                             <input type=text name="ifscCode" id="ifscCode" style="width:250px;">
                          </td>
                          <td width="20%" align="left" id="errmsgifsccode"></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td colspan="3" align="left" ><hr><b>Broker SignUp Details</b></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" ><font color = "red"></font>Form SignUp Date: </td>
                          <td width="30%" align="left"><input name="img_date2" type="text" class="formstyle2" id="img_date2" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger2" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td> <td width="20%" align="left" id="errmsgsignupdate"></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top"><font color='red'>*</font>Proptiger Branch: </td>
                          <td width="30%" align="left">
                          <select id="signUpBranch" name="signUpBranch" ><option value=''>Select City</option>
                                           {foreach from=$ptBranchArray key=k item=v}
                                               <option value="{$k}">{$v}</option>
                                           {/foreach}
                          </select>
                          </td>
                          <td width="20%" align="left" id="errmsgsignupbranch"></td>
                        </tr>

                        <!-- <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">Upload Signup Form Soft Copy: </td>
                          <td width="30%" align="left">
                             <input type="file" name='signUpForm' id="signUpForm" >
                          </td>
                          <td width="20%" align="left" id="errmsgsignupform"></td>
                        </tr> -->

                        </form>
                        <form action="saveCompanyLogo.php" target="uploadiframeSignup" name="uploadSignUpForm" id="uploadSignUpForm" method="POST" enctype = "multipart/form-data">
                          <tr>
                            <td width="20%" align="right" >Upload Signup Form Soft Copy: </td>
                            <td width="30%" align="left"><input type="file" name='signUpForm' id="signUpForm" ><input type="hidden" name='signUpFormUploadStatus' id="signUpFormUploadStatus" value="0"><input type="hidden" name='uploadedSignUpForm' id="uploadedSignUpForm" value=""><input type="submit" id="uploadSignUp" value="uploadSignUp" name="submit"></td> <td width="20%" align="left" id="errmsgsignupform"></td>
                          </tr>
                          

                        </form>
                        
                        <form>

                        <tr class="broker_basic_extra">
                          <td colspan="3" align="left" ><hr><b></b></td>
                        </tr>

                        <tr class="broker_basic_extra">
                          <td width="20%" align="right" valign="top">Primary Device Usage : </td>
                          <td width="30%" align="left">
                           <!-- <select id="device" name="device" valign="center"> 
                              <option name=one value=''>Select Device Used</option>
                              {foreach from=$devices key=k item=v}
                                <option name=one value='{$k}'>{$v}</option>
                              {/foreach}
                          </select> -->
                        
                            <table width="100%">
                              {$i=0}
                            {foreach $devices key=k item=v}
                              {$i=$i+1}
                              {if $i%2!=0}<tr>{/if}
                              <td >
                            <input type='checkbox' name='device[]' class='device' value='{$k}'>{$v}<input type='hidden' id="tt_db_id_{$k}"> </td>
                            {if $i%2==0}</tr>{/if}
                            {/foreach}
                          </table>
                          
                          </td>
                          <td width="20%" align="left" id="errmsgdevice"></td>
                          
                        </tr>


                        <tr>
                          <td width="20%" align="right" ><font color = "red"></font>Years in Operations : </td>
                          <td width="30%" align="left"><input name="img_date1" type="text" class="formstyle2" id="img_date1" readonly="1" />  <img src="../images/cal_1.jpg" id="img_date_trigger1" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td> <td width="20%" align="left" id="errmsgdate"></td>
                        </tr>

                        <!-- <tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Service Tax No : </td>
                          <td width="30%" align="left"><input type=text name="stn" id="stn" style="width:250px;"></td> <td width="20%" align="left" id="errmsgstn"><input type=hidden id="bd_id"></td>
                        </tr>

                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Size of Office (sqft) : </td>
                          <td width="30%" align="left"><input type=text name="officeSize" id="officeSize" style="width:250px;"></td> <td width="20%" align="left" id="errmsgofficesize"></td>
                        </tr>

                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font># Employees : </td>
                          <td width="30%" align="left"><input type=text name="employeeNo" id="employeeNo" style="width:250px;"></td> <td width="20%" align="left" id="errmsgemployeeNo"></td>
                        </tr> -->

                        <tr >
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

                        <tr class="broker_basic_extra" >
                          <td width="10%" align="right" ><font color = "red"></font>Any Relative in Proptiger: </td>
                            <td width="20%" height="25" align="left" valign="top">
                              <input type="radio" name="relative"  value='Yes' class="relative" id='relative_yes'>Yes &nbsp; &nbsp; <br>
                              <input type="radio" name="relative" value="No" class="relative" id='relative_no' checked='checked'>No &nbsp; &nbsp;
                                    <select  id="ptRelative" name="ptRelative" style="display:none">
                                       <option value=''>select Relative</option>
                                       {foreach from=$ptRelative key=k item=v}
                                              <option value="{$k}">{$v}</option>
                                       {/foreach}
                                    </select>
                                    </td>
                            <td width="40%" align="left" id="errmsgptrelative"></td>
                        </tr>
                      </table>
                      </td>
                    </tr>

                    <tr class="broker_basic">
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
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0 class="tablesorter" id="company_table">
                        <form name="form1" method="post" action="">
                          <thead>
                                <TR class = "headingrowcolor">
                                  <th  width=2% align="center">No.</th>
                                  <th  width=5% align="center">Type</th>
                                  <TH  width=8% align="center">Name</TH>
                                  <TH  width=8% align="center">Address</TH>
                                  <TH  width=8% align="center">Contact Person</TH>
                                 <TH width=6% align="center">Status</TH> 
                                <TH width=3% align="center">Edit</TH>
                                </TR>
                              
                          </thead>
                          <tbody></tbody>
                          
                          <tfoot>
                              <tr>
                                <th>1</th> <!-- tfoot text will be updated at the same time as the thead -->
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>5</th>
                                <th>6</th>
                                <th>7</th>
                              </tr>
                              <tr>
                                <td class="pager" colspan="8">
                                  <img src="tablesorter/addons/pager/icons/first.png" class="first"/>
                                  <img src="tablesorter/addons/pager/icons/prev.png" class="prev"/>
                                  <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                  <img src="tablesorter/addons/pager/icons/next.png" class="next"/>
                                  <img src="tablesorter/addons/pager/icons/last.png" class="last"/>
                                  <select class="pagesize">
                                  <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                  </select>
                                </td>
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
        
        for(i=1;i<=2;i++){
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
