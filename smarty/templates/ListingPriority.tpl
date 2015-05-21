<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/pager-ajax.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">  
<link rel="stylesheet" type="text/css" href="csss.css"> 
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
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/js/jss.js"></script>

<style>
    .hide-input{ display: none !important; }
    .tablesorter thead .disabled { display: none }
    .tablesorter-bootstrap .tablesorter-header{ cursor: text }
</style>
<script language="javascript">
var pid;
var bt = [];
var option = [];
var sel = [];


function chkConfirm() 
{
    return confirm("Are you sure! you want to delete this record.");
}
function selectCity(value){
  	window.location.href="{$dirname}/locality_near_places_priority.php?&citydd="+value;
}
function selectSuburb(value){
  	var cityid = $('#citydd').val();
    	window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&suburb="+value;
}
function selectLocality(value){ 
    var cityid = $('#citydd').val();
  	window.location.href="{$dirname}/locality_near_places_priority.php?citydd="+cityid+"&locality="+value;
}
function submitButton(){ 
    var queryStrUrl = "";
    if($('#citydd').val() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"citydd=" + $('#citydd').val();
    }
   
    if($('#project_search').val().trim() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"projectName=" + $('#project_search').val().trim();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"projectId=" + $('#selProjId').val();
    }
    if($('#listingId_search').val().trim() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"listingId=" + $('#listingId_search').val().trim();
    }
    if(($("#search_term option:selected").val() != "") && ($("#search_value").val().trim()!="" || $("#search_landmark").val().trim()!="")){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"search_term=" + $("#search_term option:selected").val();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"search_value=" + ($('#search_value').val().trim()? $('#search_value').val().trim() : $("#search_landmark").val().trim());
    }
    if($("#search_term option:selected").val() == "gpid"){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"gpid=" + $("#hidden_gpid").val();
    }
    
    if(($("#search_range option:selected").val() != "") && ($("#range_from").val().trim() != "" || $("#range_to").val().trim() !="")){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"search_range=" + $("#search_range option:selected").val();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"range_from=" + $('#range_from').val().trim();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"range_to=" + $('#range_to').val().trim();
    }
    if($("#bookingStatusId_search").val() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"bStatusId=" + $("#bookingStatusId_search").val();
    }
    queryStrUrl = ((queryStrUrl)? "?" :"") + queryStrUrl;
    window.location.href="{$dirname}/listing_list.php" + queryStrUrl;
    return false;
}
function downloadClick(){
    var queryStrUrl = "";
    if($('#citydd').val() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"cityId=" + $('#citydd').val();
    }
   
    if($('#project_search').val().trim() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"projectName=" + $('#project_search').val().trim();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"projectId=" + $('#selProjId').val();
    }
    if($('#listingId_search').val().trim() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"listingId=" + $('#listingId_search').val().trim();
    }
    if(($("#search_term option:selected").val() != "") && ($("#search_value").val().trim()!="" || $("#search_landmark").val().trim()!="")){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"search_term=" + $("#search_term option:selected").val();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"search_value=" + ($('#search_value').val().trim()? $('#search_value').val().trim() : $("#search_landmark").val().trim());
    }
    if($("#search_term option:selected").val() == "gpid"){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"gpid=" + $("#hidden_gpid").val();
    }
    
    if(($("#search_range option:selected").val() != "") && ($("#range_from").val().trim() != "" || $("#range_to").val().trim() !="")){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"search_range=" + $("#search_range option:selected").val();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"range_from=" + $('#range_from').val().trim();
        queryStrUrl += ((queryStrUrl)? "&" :"") +"range_to=" + $('#range_to').val().trim();
    }
    if($("#bookingStatusId_search").val() !=""){
        queryStrUrl += ((queryStrUrl)? "&" :"") +"bStatusId=" + $("#bookingStatusId_search").val();
    }
    queryStrUrl = ((queryStrUrl)? "?" :"") + queryStrUrl;
    window.location.href="{$dirname}/ajax/downloadListing.php" + queryStrUrl;
    
    return false;
}

function isNumeric(val) {
    var validChars = '0123456789.';
    var validCharsforfirstdigit = '-123456789';
    if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
        return false;    

    for(var i = 1; i < val.length; i++) {
        if(validChars.indexOf(val.charAt(i)) == -1)
            return false;
    }
    return true;
}

function isPhnumber(val) {
    var validChars = '0123456789+-';
    for(var i = 1; i < val.length; i++) {
    	if(validChars.indexOf(val.charAt(i)) == -1)
        	return false;
		}
        if(val.length >14 || val.length < 6)
          	return false;

        return true;
}

function cleanFields(){
  $('#create_Landmark input text,#create_Landmark select,#create_Landmark textarea, :text').each(function(key, value){
      $(this).val('');
    }); 
   $("#seller3").html('');
   $("#bh3").html('');
   $("#listing_id").val("");
   $('#othr').hide();
   $('#other_charges').hide();
   $('#pr').hide();
   /*$('#plc5').hide(1);
   $('#bnk_lst').hide(1);*/
   $("#image_link").html("");
   
}




function editListing(str){
    
  str = JSON.parse(unescape(str));
    cleanFields();
    $('#search-top').hide('slow');
    $('#search-bottom').hide('slow');
    $('#create_Landmark').show('slow'); 

    if(str.id!=null)
      $("#listing_id").val(str.id);
    $("#image_link").html("<a href=listing_img_add.php?listing_id="+str.id+">Add/Edit Listing Images</a>");
    $("#cityddEdit").val(str.property.project.locality.suburb.city.id);
    $("#project").val(str.property.project.name);
    var projectId = str.property.project.projectId;
    $("#proj").val(projectId);
    get_phases(projectId);
    get_towers(projectId);
    $("#towerIdHidden").val(str.towerId);
    $("#phaseIdHidden").val(str.phaseId);
    
    $("#vendor_classified").val(str.vendorId);
    if(str.brokerConsent !=undefined){
        $("#broker_check").val(str.brokerConsent.toString());
    }
    if(str.homeLoanBank !=undefined){
        $("#home_loan").val(str.homeLoanBank.toString());
    }

    if(str.seller!=null){
      var seller_id = str.seller.id;
      
      $('#bkn2').val(str.seller.brokerId); 
      getSeller();
      $("#seller3").val(seller_id);
      
      var pt_broker_id =  $("#pt_broker_id").val();
      if(str.seller.brokerId == pt_broker_id){
        $('#name_font').show(1);
        $('#number_font').show(1);  
      } else {
        $('#name_font').hide(1);
        $('#number_font').hide(1);  

      }

    }
    $("#facing2").val(str.facingId);
    
    $("#floor2").val(str.floor);
    $("#tfr2").val(str.transferCharges);
    $("#flt2").val(str.flatNumber);
    
   var unit_name = str.property.unitName+"-"+str.property.size+" "+str.property.unitType; 

    $('#bh3').html(''); 
    $('#bh3').append($("<option selected='selected' />").val("0").text(unit_name));
    $('#othr').hide(1);
    $('#othr2').val('');
      $('#bed2').val('');
      $('#tol3').val('');
      $('#appartment3').val('');

    var jsonDump = $.parseJSON(str.jsonDump);
    if(jsonDump!=null){
      
      $("#name").val(jsonDump.owner_name);
      $("#email").val(jsonDump.owner_email);
      $("#number").val(jsonDump.owner_number);
      $("#alt_number").val(jsonDump.alt_owner_number);

      $("#total_floor1").val(jsonDump.total_floor);
    }
    
    $("#description3").val(str.description);
    $("#booking_status").val(str.bookingStatusId);
    $("#furnished_options").val(str.furnished);
    $("#review3").val(str.remark);
 
    option.length = 0;
    var tmp = [];
     tmp['size']= str.property.size;
        tmp['bedrooms'] = str.property.bedrooms;
      tmp['bathrooms'] = str.property.bathrooms;
        tmp['propertyId'] =str.property.propertyId;
      tmp['unitType'] = str.property.unitType;
      option.push(tmp);
      
    if(str.currentListingPrice != null){
      if(str.currentListingPrice.pricePerUnitArea > 0){
         $("#prs5").val('2');
         var price_value = str.currentListingPrice.pricePerUnitArea;
         $('#pr').hide();
         $('#tr').show();
         $('#other_charges').show();
         $('#othr_prs2').show();
         $("#othr_prs2").val(str.currentListingPrice.otherCharges);
         $("#prs3").val(price_value);

       }
      else if(str.currentListingPrice.price >0) {
        $("#prs5").val('1');
        var price_value = parseFloat(str.currentListingPrice.price).toFixed(2);
         price_value = price_value/100000;
         if(price_value>=100){
            price_value = price_value/100;
            $("#crs1").val('y');
            $("#crs1").attr('checked','checked');
            $("#lkhs1").val('n');
            $("#lkhs1").removeAttr('checked');
         }else{
            $("#lkhs1").val('y');
            $("#lkhs1").attr('checked','checked');
            $("#crs1").val('n');
            $("#crs1").removeAttr('checked');
         }
         price_value = price_value.toFixed(2).toString();
         $("#prs3").val(price_value);
         $('#pr').show();
         $('#other_charges').hide();
         $('#othr_prs2').val('');
      }
    }

    $("#park2").val(str.noOfCarParks);
    $("#bnk_lst").val(str.homeLoanBankId);
    if(str.homeLoanBankId!='' && str.homeLoanBankId!=null && str.homeLoanBankId>0){
      $("#bnk_lst").show();
    }
    else{
      $("#bnk_lst").val('');
    }
    $("#plc5").val(str.plc);
    if(str.plc!='' && str.plc!=null && str.plc>0){
      $("#plc5").show();
      $('#plcn').removeAttr('checked');
      $('#plcy').attr('checked', true);
    }
    else{
      $('#plc5').val("");
      $('#plcn').attr('checked', true);
      $('#plcy').removeAttr('checked');
    }
    if(str.negotiable!=null){
      if(str.negotiable==true){
        $("#nego_select").val("1");
      }
      if(str.negotiable==false){
        $("#nego_select").val("2");
      }
    }
    
    $("#cityddEdit").attr('disabled',true);
    $("#project").attr('readonly',true);
    $("#proj").attr('readonly',true);
    $("#bh3").attr('disabled',true);

    window.scrollTo(0, 0);

}

function getSeller(){
   var broker_id = $("#bkn2 :selected").val(); 
   $('#seller3').html(''); 
   if(broker_id==null || broker_id=='')  
    return true;
    //console.log(broker_id);
    $.ajax({
            type: "POST",
            url: '/saveSecondaryListings.php',
            data: { broker_id:broker_id, task:'get_seller'},

            success:function(msg){

              //console.log(msg);
              
              var options = $("#seller3");
              //var i = 0;


              msg = $.parseJSON(msg);
              $.each(msg, function(k,v) {
                //console.log(v);
                  options.append($("<option/>").val(v['user_id']).text(v['name']));    
              });    
            },
    });
}



function exitButtonClicked(){
  cleanFields();
     $('#create_company').hide('slow'); 
   
      $('#search_bottom').show('slow');
      location.reload();
}


jQuery(document).ready(function(){       
    
  var i;

$("#tower2").change(function(){
    var total_floor = $('option:selected', this).attr('data-floor');
    if(total_floor>0){
        $("#total_floor1").html('');
        var option = '<option value=' + total_floor + '>' + total_floor + '</option>';
        $("#total_floor1").append(option);
    }else{
        populate_total_floor();
    }
});


$('#search-top').show('slow');
    $('#search-bottom').show('slow');

String.prototype.isMatch = function(s){
	var b = this.match(s)!==null
    return b;
}
 
$("#create_button").click(function(){
  	cleanFields();
  	$('#search-top').hide('slow');
    $('#search-bottom').hide('slow');
    $('#create_Landmark').show('slow');
    $("#cityddEdit").attr('disabled',false);
    $("#project").attr('readonly',false);
    $("#proj").attr('readonly',false);
    $("#bh3").attr('disabled',false); 
    $('#prs5').val('1');
    $('#pr').show();
    
});

  

  $("#exit_button").click(function(){
    exitButtonClicked();
    
  });



$("#exit_button").click(function(){
   cleanFields();
   	$('#create_Landmark').hide('slow'); 
   	$('#search-top').show('slow');
   	$('#search-bottom').show('slow');
});

/*$("#bkn2").change(function () {

   getSeller();
   
});*/


$('#project_search').val(getParameterByName('projectName'));
$('#selProjId').val(getParameterByName('projectId'));
$('#citydd').val(getParameterByName('citydd'));
$('#listingId_search').val(getParameterByName('listingId'));
$('#search_range').val(getParameterByName('search_range'));
$('#range_from').val(getParameterByName('range_from'));
$('#range_to').val(getParameterByName('range_to'));
$('#search_term').val(getParameterByName('search_term'));
$('#bookingStatusId_search').val(getParameterByName('bStatusId'));


if($('#search_term').val()=="gpid"){
    $('#hidden_gpid').val(getParameterByName('gpid'))
    $("#search_landmark").val(getParameterByName('search_value'));
    $('#search_value').addClass("hide-input");
    $('#search_landmark').removeClass("hide-input");
    
}else{
    $('#search_value').val(getParameterByName('search_value'));
    $('#search_value').removeClass("hide-input");
    $('#search_landmark').addClass("hide-input");
}
var listingDelAuth = "{$listingDelAuth}";
// tablesorter ajax pager
 tableSotderUrl='';
{literal}
$(function(){
/*var selCity = null;
selCity = $("#citydd :selected").val();
var selProject = null;
selProject = $("#selProjId").val();*/
  // Initialize tablesorter
  // ***********************
  $("#listing_table")
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
      ajaxUrl : '/ajax_tablesorter_listing.php?page={page}&size={size}&{sortList:col}',
      // modify the url after all processing has been applied
      customAjaxUrl: function(table, url) {
          // manipulate the url string as you desire
          if($("#citydd :selected").val()){
             url += '&city=' + $("#citydd :selected").val();
          }
          if($("#selProjId").val()){
             url += '&project=' + $("#selProjId").val();
          }
          if($("#listingId_search").val()){
             url += '&listingId=' + $("#listingId_search").val();
          }
          if($("#bookingStatusId_search").val()){
             url += '&bStatusId=' + $("#bookingStatusId_search").val();
          }
          if($("#search_term").val()){
              if($("#search_value").val()){
                    url += '&search_term=' + $("#search_term").val();
                    url += '&search_value=' + $("#search_value").val();
              }
              else if($("#search_landmark").val()){
                  url += '&gpid=' + $("#hidden_gpid").val();
              }
          }
          if($("#search_range").val() && ($("#range_from").val() || $("#range_to").val())){
             url += '&search_range=' + $("#search_range").val();
             url += '&range_from=' + $("#range_from").val();
             url += '&range_to=' + $("#range_to").val();
          }
          // trigger my custom event
          $(table).trigger('changingUrl', url);
          // send the server the current page
          tableSotderUrl = url;
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
        //console.log(data);
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
          // this will depend on how the json is set up - see City0.json
          // rows
          
          for ( r=0; r < len; r++ ) {
            row = []; // new row array
            // cells
            for ( c in d[r] ) {
                
              if (typeof(c) === "string") {
                // match the key with the header to get the proper column index
                indx = $.inArray( c, headerXref );
//alert("index : "+indx+" \n c : "+c+" \n value :"+d[r][c]);
//alert(JSON.stringify(d[r]));
                // add each table cell data to row array
                if (indx >= 0) {
                  if(indx==11){//encodeURIComponent(JSON.stringify(d[r][c]))
                    //d[r][c] = {'description': "hello'yes boys"};  
                    var a = d[r][c];
                    //console.log(a);
                    a = escape(d[r][c]);
                    //console.log(a);
                    row[indx] =  "<button type='button' id='edit_button_' onclick='editListing("+JSON.stringify(a)+")' align='left'>Edit</button>";
                 //var hello = {};
                 //console.log(d[r][c]);
                  //row[indx] =  "<button type='button' id='edit_button_' onclick='return editListing("+ hello+ ")' align='left'>Edit</button>" ;
                   }else if(indx == 12 && listingDelAuth==true){
                        var lid = d[r]['ListingId'];
                        row[indx] =  "<button type='button' class='delete-list' data-listingId=" + lid + " align='left'>Delete</button>";
                        
                   }else
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

{/literal}

populate_total_floor();


$("#lmkSave").click(function(){
    var temp = [];
    var listing_id = $("#listing_id").val();
    var cityid = $("#cityddEdit :selected").val();
    var broker_name = $("#bkn2 :selected").text().trim();

    var broker_id = $("#bkn2 :selected").val();
    var pt_broker_id =  $("#pt_broker_id").val();
    //console.log(broker_id +" "+pt_broker_id);


    var owner_name = $("#name").val().trim();
    var owner_email = $("#email").val().trim();
    var owner_number = $("#number").val().trim();
    var alt_owner_number = $("#alt_number").val().trim();
    if(broker_id==pt_broker_id){
          

          if(owner_name == ''||owner_name == null) {
            owner_name = null;
            alert('Enter Owner Name!!');
            return false;
          }

          //
          
          
          if(owner_number == ''||owner_number == null) {
            owner_number = null;
            alert('Enter Owner Number!!');
            return false;
          } else {
            if(!isNumeric(owner_number)){
                alert('Enter Only numeric owner contact no.');
                return false;
            }
          }
          
            

    }
    if(owner_number !="" && owner_number != null && !isNumeric(owner_number)){
        alert('Enter Only numeric owner contact no.');
        return false;
    }
    if(alt_owner_number !=""){
        if(!isNumeric(alt_owner_number)){
           alert('Enter Only numeric alternate contact no.');
           return false;
         }
    }
          
    

    //var projectid = $("#project :selected").text().trim();
    var project_name = $("#project").val().trim();
    var project_id = $("#proj").val().trim();
    var bhk1 = $("#bh3 :selected").text().trim();
    var option_sel = $("#bh3 :selected").val();
    var size = "";
    var bedrooms ="";
    var bathrooms = "";
    var property_id = "";
    var unit_type = "";
    
    if (parseInt(option_sel) < option.length){
      
      $.each(option, function(k,v){
        
        if (k==parseInt(option_sel)){
          //console.log("here0");
          //console.log(v);
          size = v['size'];
          bedrooms = v['bedrooms'];
          bathrooms = v['bathrooms'];
          property_id = v['propertyId'];
          unit_type = v['unitType'];
        }
      });
      
    }
    else{
       size = $("#other_input").val().trim();
       bedrooms = $("#bed2").val().trim();
       bathrooms = $("#tol3").val();
       property_id = "";
       unit_type = $("#appartment3 :selected").text();
    }

   

    var seller_id = '';
    seller_id = $("#seller3 :selected").val(); 

    //var projectid = $("#project :selected").text().trim();
    //var projectid = $("#project").val().trim();
    //var projid = $("#proj").val().trim();
    //var bhk1 = $("#bh3 :selected").text().trim();    
    
    var owner_name = $("#name").val().trim();
    var owner_email = $("#email").val().trim();
    var owner_number = $("#number").val().trim();
    var alt_owner_number = $("#alt_number").val().trim();

    var facing = $("#facing2 :selected").val();
    if(facing=='')
      facing=null;
    //console.log(facing);
    var tower = $("#tower2 :selected").val();
    var floor = $("#floor2").val().trim();
    var total_floor_check = $("#total_floor1 :selected").text();
    var total_floor = null;
    if(total_floor_check == "Select") {
      total_floor = null;
    } else {
      total_floor = parseInt(total_floor_check);
    }
    
    var price_type = parseInt($("#prs5 :selected").val());

    var price = "";
    var price_per_unit_area = "";
    var other_prs = $("#othr_prs2").val().trim();
    var flag = 0;
    
    var ops = parseFloat(other_prs).toFixed(2);
    if (price_type==1){
      price = $("#prs3").val().trim();
      if ($('[name="lkhs1"]').is(':checked'))  {
        if(price!=''){

          price = parseInt(parseFloat(price).toFixed(2) * 100000);
        }
        
      } else {
        if(price!=''){
          price = parseInt(parseFloat(price).toFixed(2) * 10000000);
        }
      }
    }
    else{
      price_per_unit_area = $("#prs3").val().trim();
      price_per_unit_area = parseInt(price_per_unit_area);
      if(price_per_unit_area==null){
        alert("Wrong format Price. Only Intergers allowed.")
        return false;
      }

    }

    //console.log(price);
    //return true;    
    

    var phase_id = $("#phase_id3 :selected").val();      


    var transfer_new = null;
    var trancefer_rate = $("#tfr2").val().trim();
    var price_in = "Lakhs";    

    /*var trancefer_rate_check = $("#transfer_sel").val().trim();
	if(trancefer_rate_check == '') {
		alert("Select price type for transfer");
	} else {
	    if (trancefer_rate_check == '1')  {
	      transfer_new = parseFloat(trancefer_rate).toFixed(2) * 100000; 
	    } else if(trancefer_rate_check == '2'){
	      transfer_new = parseFloat(trancefer_rate).toFixed(2) * 10000000; 
	    }  
	}*/

     
    if ($('[name="lkhs2"]').is(':checked'))  {
      transfer_new = parseFloat(trancefer_rate).toFixed(2) * 100000; 
    } else {
      transfer_new = parseFloat(trancefer_rate).toFixed(2) * 10000000;
    }
    //console.log(transfer_new);
    //return true;
    var appratment = $("#appartment3 :selected").text();
    var penthouse_studio = $("#penthouse_sel :selected").val();
  
    
  
    /*if(penthouse_stdio_temp == "1") {
        if ($('[name="penthouse_studio_yes"]').is(':checked'))  {
            penthouse = true;
        } else {
            penthouse = false;
        }
    } else if(penthouse_stdio_temp == "Studio") { 
        if ($('[name="penthouse_studio_yes"]').is(':checked'))  {
            studio = true;
        } else {
            studio = false; 
        }
    }*/

    var negotiable = null;
    var nego_select_check = $("#nego_select :selected").val();
    if (nego_select_check == '1')  {
        negotiable = true;
    } else if(nego_select_check == '2'){
        negotiable = false;
    }

    //alert("penthouse = "+penthouse);
    //alert("studio = " + studio);

    var flat_number = $("#flt2").val().trim();
    var parking = $("#park2 :selected").val();
    var loan_bank = $("#bnk_lst :selected").val();
    var plc_val = $("#plc5").val().trim();
    
    var study_room = null;
    var study_room_check = $("#study_sel :selected").val();
    if (study_room_check == '1')  {
          study_room = "1";
    } else if(servant_room_check == '2'){
      study_room = "0";
    }  
    
    var servant_room = null;
    var servant_room_check = $("#servant_sel :selected").val();
    if (servant_room_check == '1')  {
          servant_room = "1";
    } else if(servant_room_check == '2'){
      servant_room = "0";
    } 

    var description = $("#description3").val().trim();
    var bookingStatusId = $("#booking_status").val().trim();
    var review = $("#review3").val().trim();

    var task='';
    if(listing_id!='')
       task="update";
    else
       task="create";

     if(property_id=='') {
      //console.log(project_id+" "+bedrooms+" "+unit_type+" "+size);
      if($("#bh3 :selected").val()!='other'){
        alert("Project is a compulsory field.");
        return true;
      }
      else{ 
        if(unit_type=='Apartment' || unit_type=='Villa') { 
          //console.log(unit_type);
          if(project_id=='' ||  bedrooms=='' || unit_type=='Select' || size=='' ){
            alert("project, bedroom, size, Option Type are must if BHK 'Others' is selected.");
            return true;
          }
        }
        else{
          //console.log(unit_type);
          if(project_id=='' || unit_type=='' || size=='' || unit_type=='Select'){
            alert("project, size, Option Type are must if BHK 'Others' is selected.");
            return true;
          }
        }
      }
     }



//console.log("s:"+seller_id+" ppa:"+price_per_unit_area+" p:"+price+" op:"+other_prs);
     //validation checks
     var error = '';

     if(seller_id=='' || !seller_id){
      error += "Seller Name is compulsory field. "
     }
     if((price=='' || price==null || !price) && (price_per_unit_area=='' || price_per_unit_area==null || !price_per_unit_area)){
      error += "Price is compulsory field. "
     }
     if(phase_id=='' || !phase_id){
      error += "Phase is compulsory field. "
     }
     if(total_floor !=null && (floor>total_floor)){
         alert('Floor number should be less than or equal to total number of floors');
         return false;
     }
     
     if($("#bnk_lst").val()!="" && $("#home_loan").val()!=="true"){
         alert('Please select yes in home loan');
         return false;
     }

     if (error != '' ){
      alert(error);
      return true;
     }
      
    var $body = $("body");
    var vendor_classified = $("#vendor_classified").val();
    var broker_check = $("#broker_check").val();
    //$("body").addClass("loading"); /*$("#lmkSave").attr('disabled', true); $("#exit_button").attr('disabled', true); $("#create_button").attr('disabled', true);*/
    $.ajax({
            type: "POST",
            //async: false,
            url: '/saveSecondaryListings.php',

            beforeSend: function(){
              //console.log('in ajax beforeSend');
              $("body").addClass("loading");
            },

            data: { listing_id:listing_id, cityid: cityid, seller_id:seller_id, project_id : project_id, vendor:vendor_classified, broker:broker_check ,property_id:property_id, owner_name:owner_name, owner_email:owner_email, owner_number:owner_number, alt_owner_number:alt_owner_number, unit_type:unit_type, bedrooms: bedrooms, facing : facing, size:size, bathrooms:bathrooms, tower:tower, phase_id: phase_id, floor : floor , total_floor:total_floor, price_type:price_type, price:price, price_per_unit_area:price_per_unit_area, other_charges:other_prs, trancefer_rate:trancefer_rate, flat_number:flat_number, parking:parking, loan_bank:loan_bank, plc_val:plc_val, study_room:study_room, servant_room:servant_room, penthouse_studio:penthouse_studio, negotiable:negotiable, description:description, review:review, task:task, bookingStatusId:bookingStatusId, furnished : $("#furnished_options").val(),homeLoanBank: $("#home_loan").val()},



            success:function(msg){
              
              //console.log(msg);
              msg = $.parseJSON(msg);//console.log(msg.msg);
              //return;
              //console.log(msg.code);
              //console.log(msg.msg);
              if(msg.code==2){
                
               $("body").removeClass("loading");
               if(msg.error_msg){
                   alert(msg.error_msg);
               }    
                exitButtonClicked();
                

              }
              else if(msg.code==1){
                $("body").removeClass("loading");
                if(msg.error_msg){
                   alert(msg.error_msg);
                }
                location.href = "listing_img_add.php?listing_id="+msg.msg;
                
              }
              else{
                //
                //$body = $("body");
                $("body").removeClass("loading");
                alert(msg.msg); /*$body.removeClass("loading"); $("#lmkSave").attr('disabled', false); $("#exit_button").attr('disabled', false); $("#create_button").attr('disabled', false);*/
              }


            },
           
          });


    var placeid = "";
    if(!placeid)
      	var placeid = "";
    var lmkid = "";
    var lmkname = "";
    var lmkaddress = "";
    var lmklat = "";
    var lmklong = "";
    var lmkphone = "";
    var lmkweb = "";
    var lmkprio = "";
    var lmkstatus = "";
    var error = 0;
    var mode='';
    if(lmkid) mode = 'update';
    else mode='create';

  });
   
  $.widget( "custom.catcomplete", $.ui.autocomplete, {
    
    _renderItem: function( ul, item ) {
      //alert(item.label);
      var res = item.id.split("-");
          var tableName = res[1];
      return $( "<li>" )
        .append( $( "<a>" ).text( item.label + "........." +tableName ) )
        .appendTo( ul );
    },
  

  });

  // to get  listings on the table based on project search
  $( "#project_search" ).catcomplete({
      source: function( request, response ) {
        
        $.ajax({
          url: "{$url12}"+"?query="+$("#project_search").val().trim()+"&typeAheadType=(project)&city="+$("#citydd :selected").text().trim()+"&rows=10",
          //url: "{$url12}"+"?query="+$("#proj").val().trim()+$("#cityddEdit :selected").text().trim(),
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full", 
            name_startsWith: request.term
          }, 
           
          success: function( data ) { 
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
        window.selectedItem = ui.item;
        var res = ui.item.id.split("-");
          var projectId = res[2];
          pid = projectId;
          //console.log(projectId);

          $("#selProjId").val(projectId); 
          
           
          
      },
      

      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

    }); 


//project search to get options autocomplete     
   $( "#project" ).catcomplete({
      source: function( request, response ) {
        
        $.ajax({
          url: "{$url12}"+"?query="+$("#project").val().trim()+"&typeAheadType=(project)&city="+$("#cityddEdit :selected").text().trim()+"&rows=10",
          //url: "{$url12}"+"?query="+$("#proj").val().trim()+$("#cityddEdit :selected").text().trim(),
          dataType: "json",
          data: {
            featureClass: "P",
            style: "full", 
            name_startsWith: request.term
          }, 
           
          success: function( data ) { 
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
        window.selectedItem = ui.item;
        var res = ui.item.id.split("-");
          var projectId = res[2];
          pid = projectId;
          //console.log(projectId);

          $("#projectId").val(projectId); 
          var data = { projectId:projectId,  task:'get_options'}; 
           
          //find_project_options();
                
          //console.log("{$url13}"+projectId+"/phase");
          $.ajax({
              //alert("Hello"); 
              url: "{$url13}"+projectId,
              dataType: "json",
              data: {
                featureClass: "P",
                style: "full", 
                //name_startsWith: request.term
              },
              
              success: function( data ) {
                  

                //var v1 = data.data.properties[0].unitName;
                var v1 = data.data.projectDetails.builder.name;
                var v2 = data.data.projectDetails.projectName;
                var v3 = data.data.locality.newsTag;
                //console.log(data);
                //alert(data.data);
                //console.log(v1);
                //console.log(v2);
                //console.log(v3);



                var ln = data.data.properties.length;
                option.length=0;
                for(i = 0; i < ln; i++)  {
                  var size = data.data.properties[i].size;
                  if (size==null) size = '';
                  bt[i] = data.data.properties[i].unitName+', '+size+' '+data.data.properties[i].measure;
                  option[i] =  data.data.properties[i];
                  //bt[i]['option_id'] = data.data.properties[i].unitName;
                  //console.log(data.data.properties[i]);
                }  
                //console.log(option);
                //console.log(bt);

                $('#proj').html('');
                $(function () {
                  $('.proj2 input').val(projectId);
                }); 

                $('#bh3').html(''); 
                var options = $("#bh3");
                var i = 0;

                $.each(bt, function() {
                    options.append($("<option/>").val(i).text(bt[i]));
                    i++;
                });  
                
                var bbt = [];
                //bbt[0] = "Others";
                var j = 0;;
                $.each(bbt, function() {
                    options.append($("<option/>").val('other').text(bbt[j]));
                    i++;
                    j++;
                });   
                
                

                var project_id = $("#proj").val().trim();
                //var project_id = '500055';   
                //alert(project_id);
                get_towers(project_id);
                   
                get_phases(projectId);
                populate_total_floor();
                               
              }
          });


          
          //}
      },
      

      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },

    }); 


    $( "#proj" ).catcomplete({
      source: function( request, response ) {
        //console.log("{$url13}"+$("#proj").val());
        $.ajax({
              //alert("Hello"); 
              url: "{$url13}"+$("#proj").val(),
              dataType: "json",
              async: false,
              data: {
                featureClass: "P",
                style: "full", 
                //name_startsWith: request.term
              },
              
              success: function( data ) {

               
                var ln2 = data.data.properties.length;

                var v12 = data.data.projectDetails.builder.name;
                var v22 = data.data.projectDetails.projectName;
                var v32 = data.data.locality.newsTag;
                
                //console.log(v12);
                //console.log(v22);
                //console.log(v32);

                for(i = 0; i < ln2; i++)  {
                  bt[i] = data.data.properties[i].unitName+', '+data.data.properties[i].size+' '+data.data.properties[i].measure;
                  //console.log(i);
                }  


                $('#project').html('');
                $(function () {
                  $('.project2 input').val(v22+' '+v32);
                }); 

                $('#bh3').html(''); 
                var options = $("#bh3");
                var i = 0;

                $.each(bt, function() {
                    options.append($("<option/>").val(i).text(bt[i]));
                    i++;
                });  
                
                var bbt = [];
                bbt[0] = "Others";
                var j = 0;;
                $.each(bbt, function() {
                    options.append($("<option/>").val('other').text(bbt[j]));
                    i++;
                    j++;
                });

                $('#total_floor1').html('');  
                var total_floor_array = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101];

                var floor_option = $('#total_floor1');
                var j = 0;
                //console.log('Floor option');
                //floor_option.append($("<option/>").val(0).text('Select');
                $.each(total_floor_array, function() {
                    if (j == 0)  {
                       floor_option.append($("<option/>").val(j).text('Select'));   
                    } else {
                       floor_option.append($("<option/>").val(j).text(total_floor_array[j-1]));
                    }
                    j++;
                    //console.log(j);
                });  

                var project_id = $("#proj").val().trim();
                //var project_id = '500055';   
                //alert(project_id);
              get_phases(project_id);  
              get_towers(project_id);
                   
              }
          });
          //}
      },
      

      /*open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },*/

    });  

$("#other_input").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });

$("#bed2").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });

$("#tol3").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });

$("#floor2").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0  && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });


$("#prs3").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if($("#prs5 :selected").val()=='1'){
       if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
          //display error message
          //$("#errmsg").html("Digits Only").show().fadeOut("slow");
                 return false;
      }
      /*{literal}
      var regexPattern = /^\d{0,8}(\.\d{1,2})?$/;         
        //Allow only Number as well 0nly 2 digit after dot(.)
      {/literal}   
      if(!regexPattern.test($("#prs3").val()))
        return false; */

    }
    else{
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          //$("#errmsg").html("Digits Only").show().fadeOut("slow");
                 return false;
      }
    }
            

   });


$("#othr_prs2").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0  && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });


$("#tfr2").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0  && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });

$("#plc5").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });


});



function get_towers(project_id){
  $.ajax({
                        type: "POST",
                        url: '/saveSecondaryListings.php',
                        data: { project_id:project_id, task:'get_tower'},

                        success:function(msg){  
                           //console.log(msg);
                           $("#tower2").html('<option value="">Select</option>');
                            var options = $("#tower2");
                            //var i = 0;


                            msg = $.parseJSON(msg);
                            $.each(msg, function(k,v) {
                              //options.append($("<option/>").val(v['tower_id']).text(v['tower_name']));
                              options.append("<option value= " + v['tower_id'] + " data-floor=" + v['total_floor'] + ">" + v["tower_name"] + "</option>");
                            });
                            var towerId = $("#towerIdHidden").val();
                            if(towerId!='')
                              options.val(towerId);


                        },
                });
}

function get_phases(projectId){
  $.ajax({
                        type: "GET",
                        url: "{$url_phase_id}"+projectId+"/phase",
                        dataType: "json",
                        data: {
                        featureClass: "P",
                        style: "full", 
                        //name_startsWith: request.term
                        },
              
                        success: function( data ) {
                          var ln2 = data.data.length;
                          //phase_id3
                          //option.length=0;
                          //console.log(ln2);
                          //console.log(data.data[0].phaseId);
                          var phase_ids1 = [];
                          var phase_ids2 = [];
                          for(i = 0; i < ln2; i++)  {
                              //console.log(data.data[i].phaseId);
                              phase_ids1.push(data.data[i].phaseId);
                              phase_ids2.push(data.data[i].phaseName);
                          } 
                          $('#phase_id3').html(''); 
                          var phase_options = $("#phase_id3");
                          var i = 0;

                          phase_options.append($("<option/>").val("").text("Select"));
                          $.each(phase_ids2, function() {
                              phase_options.append($("<option/>").val(phase_ids1[i]).text(phase_ids2[i]));
                              i++;
                          });
                          var phaseId = $("#phaseIdHidden").val();
                            if(phaseId!='')
                              phase_options.val(phaseId);
                        }
                          
                });
}

function populate_total_floor(){
  $('#total_floor1').html('');  
                var total_floor_array = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101];
                var floor_option = $('#total_floor1');
                var j = 0;
                //console.log('Floor option');
                //floor_option.append($("<option/>").val(0).text('Select');
                $.each(total_floor_array, function() {
                    if (j == 0)  {
                       floor_option.append($("<option/>").val(j).text('Select'));   
                    } else {
                       floor_option.append($("<option/>").val(j).text(total_floor_array[j-1]));
                    }
                    j++;
                    //console.log(j);
                });
}

/*function isNumeric(val) {
        var validChars = '0123456789';
        var validCharsforfirstdigit = '1234567890';
        if(validCharsforfirstdigit.indexOf(val.charAt(0)) == -1)
                return false;
        

        for(var i = 1; i < val.length; i++) {
            if(validChars.indexOf(val.charAt(i)) == -1)
                return false;
        }


        return true;
}*/


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}



</script>

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
		    	{if $listingAuth == 1}
		            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
		            	<TBODY>
		                	<TR>
		                	  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
		                    	<TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
		                    		<TBODY>
		                      			<TR>
		                        			<TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>          Properties
		                        			</TD>
                                                                <td align="right"></td>
		                      			</TR>
		                    		</TBODY>
		            </TABLE>
		    </TD>
		</TR>
		<TR>
		<TD vAlign=top align=middle class="backgorund-rt" height=450>
                    <span id="peoject_search_msg" style="color:red"></span>
                    <BR>
		    <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
		            <td>
                                <div id="search-top">
                                    <form method = "get">
                                        <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
		            	            <tr>
		                                <td height="25" align="left" valign="top">
                                                    <select id="citydd" name="citydd" >
                                                       <option value=''>Select City</option>
                                                       {foreach from=$cityArray key=k item=v}
                                                           <option value="{$k}" {if $k==$cityId}  {/if}>{$v}</option>
                                                       {/foreach}
                                                    </select>
		                                </td>
                                                <td height="25" align="left" valign="top" style="padding-left: 10px;">
                                                    <input type=text name="project_search" id="project_search" placeholder="Project"  style="width:210px;">
                                                  <input type=hidden name="selProjId" id="selProjId" >
                                                </td>
                                                <td height="25" align="left" valign="top" style="padding-left: 10px;">
                                                    <input type=text name="listingId_serach" id="listingId_search" placeholder="Listing ID"  style="width:210px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                
                                                <td>
                                                    <select name="search_range" id="search_range">
                                                        <option value="">--Select--</option>
                                                        <option value="price">Absolute Price</option>
                                                        <option value="listingPricesPricePerUnitArea">Price Per Unit Area</option>
                                                    </select>
                                                </td>
                                                <td style="padding-left: 10px;">
                                                    <input type="text" name="range_from" id="range_from" placeholder="From">
                                                </td>
                                                <td style="padding-left: 10px;">
                                                    <input type="text" name="range_to" id="range_to" placeholder="To">
                                                </td>
                                                
                                            </tr>

                                            <tr>
                                               
                                                <td>
                                                    <select name="search_term" id="search_term">
                                                        <option value="">--Select--</option>
                                                        <option value="bedrooms">Search By Bedrooms</option>
                                                        <option value="gpid">Search By Landmark</option>
                                                    </select>
                                                    <input type="hidden" id="hidden_gpid">
                                                </td>
                                                <td style="padding-left: 10px;">
                                                    <input type="text" name="search_value" id="search_value" placeholder="Search Value">
                                                    <input type="text" name="search_landmark" id="search_landmark" placeholder="Search Landmark" class="hide-input">
                                                </td>
                                                <td style="padding-left: 10px;">
                                                    <select name="bookingStatusId" id="bookingStatusId_search">
                                                        <option value="">Select Booking Status</option>
                                                        {foreach from=$bStatusList key=bStatusId item=bstatus}
                                                            <option value="{$bStatusId}"> {$bstatus} </option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td style="padding-left: 10px;">
                                                    <input type = "submit" name = "submit" value = "submit" onclick="return submitButton();">
                                                    <input type = "button" name = "Download" value = "Download" onclick="return downloadClick();">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
		                </div> 
		            </td>
                    </tr>
                </table>



    <!--  --------------------------------------------------------------------------------------------------------  -->

                <div align="left" style="margin-bottom:5px;">
                    <button type="button" id="create_button" align="left">
                   		Create New Listing
                    </button> 
                </div> 
                
                <div id='create_Landmark' style="display:none" align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  	<form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    	<input type="hidden" name="old_sub_name" value="">
                    	<div>
<!--City Tr-->         		<tr id="city" style="left:300px;">
                      			<td id="city1">
                      				City
                      			</td>
                            <td>
                                <select id="cityddEdit" name="cityddEdit" >
                                    <option value=''>select city</option>
                                	  {foreach from=$cityArray key=k item=v}
                                    <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                                    {/foreach}
                                </select>
                            </td>
                    		  </tr>


                        <tr id="bkn">
                            <td id = "bkn1">
                                <font color="red">*</font>Broker Name
                            </td>
                            <td id = "bkn3">
                                <select id="bkn2" name="bkn2" onchange="getSeller();">
                                      <option value=''>select name</option>
                                      {foreach from=$brokerArray key=k item=v} 
                                          <option value="{$v['id']}">{$v['name']}</option>
                                      {/foreach}
                                </select>
                                <input type='hidden' id='listing_id'>
                            </td>
                            <td width="100px;">

                            </td>
                            

                            <td id="seller1">
                                <font color="red">*</font>Seller Name:
                            </td>
                            <td id="seller2">
                              <!-- <input type=text name="seller3" id="seller3"> --> 
                              <select id="seller3" name="seller3" >
                                    <option value=''>Seller ID</option>                                    
                              </select>
                            </td>
                            	
                        </tr>
                        <tr id="trv">
                            <td id="tdvlbl" width="100px;">Vendor</td>
                            <td>
                                <select name="vendor" id="vendor_classified">
                                    <option value="">Select Vendor</option>
                                   {foreach from=$comptype key=id item=comp}
                                       <option value="{$id}">{$comp}</option>
                                   {/foreach}
                                </select>
                            </td>
                            <td width="100px;"></td>
                            
                            <td>Broker Consent:</td>
                            <td width="4px"></td>
                            <td>
                                <select name="broker" id="broker_check">
                                    <option value="false">No</option>
                                    <option value="true">Yes</option>
                                </select>
                            </td>
                        </tr>
                      
                        <hr id = "line1" >

                        <tr id="name_number1">
                              <td id="name1">

                                <font id= "name_font" color='red' style="display:none">
                                    *
                                </font>
                                Owner Name
                              </td>
                        
                              <td id="name2">
                                  <input type=text name="name" id="name"  style="width:150px;">
                              </td>
                              
                              <td id="number1">

                                <font id="number_font" color="red" style="display:none">
                                    *
                                </font>
                                Contact Number:
                              </td>
                              <td class="number2">
                                <input type=text name="number" id="number" style="width:150px;">
                              </td> 
                              
                              
                        </tr>
                        <tr id="name_number2">      
                              <td id="email1">
                                
                                Email
                              </td>
                              <td id="email2">
                                  <input type=text name="email" id="email"  style="width:150px;">
                              </td>
                              <td id="number1">

                                <font id="number_font" color="red" style="display:none">
                                    *
                                </font>
                                Alternate Contact Number:
                              </td>
                              <td class="number2">
                                <input type=text name="alt_number" id="alt_number" style="width:150px;">   
                                <input type=hidden value="{$proptiger_broker_id}" name="pt_broker_id" id="pt_broker_id" style="width:100px;">  
                              </td>          
                        </tr>
                        	
                        		<hr id = "line2" >

                    		<tr id="prj">
                      			<div class="ui-widget">
                        			<td id="project1">
                          			<font color = "red">
                              			*
                          			</font>
                          			Project
                        			</td>
                        			<td class="project2">
                            			<input type=text name="project" id="project"  style="width:210px;">
                        			</td>
                        			<td  style="text-align: center;" width="100px;">
                                  		OR
                            		</td>
	                              <td id="proj1">
	                                Project ID:
	                              </td>
	                              <td class="proj2">
	                                <input type=text name="proj" id="proj" style="width:100px">       
	                              </td>
                      			</div>         		
                    		</tr>
       
                    		<tr id="bhk">
	                            <td id = "bh1">
	                                <font color="red">*</font>BHK
	                            </td>
	                            <td id="bh2">
	                                <select id="bh3" name="bh3">
	                                    <option value=''> BHK </option>    
	                                    <script language="javascript" type="text/javascript"> 
	                                    for(var d=0;d< bt.length;d++)  {
	                                        document.write("<option value='"+option[d].propertyId+"' >"+bt[d]+"</option>");
	                                    }
	                                    </script>
	                                </select>
	                            </td>
	                            <td id="appartment1">
	                                  <font color="red">*</font>Option Type
	                            </td>
	                            <td id="appartment2">
	                                  <select name="appartment3" id="appartment3" style="height:28px">

	                                    <option value="0">Select</option>
	                                    <option value="1">Apartment</option>>  
	                                    <option value="2">Villa</option>
	                                    <option value="3">Plot</option>
	                                    <option value="4">Commercial</option>
	                                    <option value="5">Shop</option>
	                                    <option value="6">Office</option>
	                                    <option value="7">Other</option>

	                                </select>
	                            </td>
	                          
                            </tr>
						   
                        <tr id = "othr">
                            <td id="othr1">
                                  <font size="1" color="red">*</font>
                                  <font size="1">
                                  Size
                                  </font>
                            </td>
                            <td id="othr2">
                                  <input type=text name="other_input" id="other_input"> 
                            </td>
                            <td id="bath">
                                  <font size="1" color="red">*</font>
                                  <font size="1">Bedroom</font>
                            </td>
                            <td id="bath1">
                                  <input type=text name="bed2" id="bed2" style="width:60px;height:15px">  
                            </td>
                            <td id="tol1">
                            	<font size="1">
                                  Toilet
                                </font>
                            </td>
                            <td id="tol2">
                                  <input type=text name="tol3" id="tol3">
                            </td>
                        </tr>
  
                        <tr id="study_servant" >
                          <td id = "study1">
                          	<font size = "1">
                              Study Room
                            </font>

                          </td>
                           <td id="study">                   
	                          <select name="study_sel" id="study_sel" style="height:28px;width:70px">

		                                    <option value="0">Select</option>
		                                    <option value="1">Yes</option>
		                                    <option value="2">No</option>  
		                      </select>
                          </td>

                          <td>

                          </td>

                          <td id = "servant1">
                          	<font size = "1">
                              Servant Room
                            </font>
                          </td>
                                              
                          <td width="120px" align="left" id="servant" >
                            <select name="servant_sel" id="servant_sel" style="height:28px;width:70px">

	                                    <option value="0">Select</option>
	                                    <option value="1">Yes</option>
	                                    <option value="2">No</option>  
	                        </select>
                          </td>

                          <td id="penthouse_td1">
                                  
                                  <font size="1">
                                  Apartment Type
                                  </font>
                            </td>
                            <td id="penthouse_td2">
                                  <select name="penthouse_sel" id="penthouse_sel" style="height:28px;width:70px">
                                    <option value=''>Select</option>  
                                      <option value="1">Penthouse</option>
                                      <option value="2">Studio</option>
                                </select>
                            </td> 
                      </tr>
                    		<hr id = "line3" >

                        <tr id="tower_floor"> 
                            
                            <td  align="left" id="phase_id1" style="width:100px;"><font  color="red">*</font>
                                  Phase 
                            </td>
                            <td  align="left" id="phase_id2">
                                 <select id="phase_id3" name="phase_id3" style="width:140px">
                                    <option value=''>Select</option>
                                </select> 
                                <input type=hidden name='phaseIdHidden' id='phaseIdHidden'>
                            </td> 
                            <td id="tower1">
                              		Tower
                           		</td>
                           		<td >
                                <!--<input type=text name="tower2" id="tower2" style="width:100px"> -->
                                <select id="tower2" name="tower2" style="width:140px">
                                    <option value=''>Select</option>
                                </select>
                                <input type=hidden name='towerIdHidden' id='towerIdHidden'>
                            </td> 	

                         </tr>


                        <tr id="tower_floor2">
                        		<td id="flt1">
                                	Flat Number
                            	</td>
	                            <td id="flt3">
	                               <input type=text name="flt2" id="flt2" style="width:100px">
	                            </td>    

                      			<td id="floor1">
                          			Floor
                      			</td>
                      			<td>
                          			<input type=text name="floor2" id="floor2" style="width:50px">
                      			</td>
                      			<td  align="left" id="errmsgfloor">
                      				
                      			</td>
                            <td id="total_floor" style = "width:50px">
                                Total Floors 
                            </td>

                            <td>
                                <select id="total_floor1" name="total_floor1" style="width:80px">
                                    <option value=''>Select</option>
                                    
                                </select>

                            </td>
                      
                    	</tr>

                    	<tr id = "flat_park">

		                      	<td id="park1">
		                      	   	Car Parks
		                      	</td>
		                        <td>
		                            <select id="park2" name="park2" style="width:100px">
		                                <option value=''>Select</option>                                         
		                                <option value="0">0</option>
		                                <option value="1">1</option>
		                                <option value="2">2</option>
		                                <option value="3">3</option>
		                                <option value="4">4</option>
		                                <option value="5">5</option>
		                            </select>    
		                        </td>
		                      	<td align="left" id="errmsgpark" style="width:220px;">
		                      			
		                      	</td>
			                      	<td id = "negotiable_id1" style="position:relative;width:100px; text-align:left;">
	                             		 Negotiable  
			                        </td>    
		                          <td id = "negotiable_id2">
		                            <select id="nego_select" name="nego_select" style="width:120px">
	                                    <option value=''>Select</option>  
	                                      <option value="1">Yes</option>
	                                      <option value="2">No</option>

	                                </select>
		                          </td>
                        </tr>
                       	<tr id="facing_project">
                       		 	<td id = "facing1">
	                                Facing
	                            </td>
	                            <td>
	                                <select id="facing2" name="facing2" style="width:120px">
	                                    <option value=''>Select</option>  
                                      {foreach $dirctionsArr  key=k item=v}
                                      <option value="{$v['id']}">{$v['direction']}</option>
                                      {/foreach}
	                                      
	                                </select>
	                            </td>	
                       	</tr>
                        	<hr id = "line4" >
               			<tr id="prs_trf">

                          			<td id="prs1">
                              			<font color="red">*</font>Price: 
                          			</td>
                              
                                                             
                                
                                <td id="prs4">
                                  <select id="prs5" name="prs5" style="width:150px">
                                      <option value='0'>Select</option>  
                                      <option value='1' selected="selected">All Inclusive</option>
                                      <option value='2'>Per Sq. Ft.</option>
                                  </select>
                                </td>

                                <td id="prs2">
                                    <input type=text name="prs3" id="prs3" style="width:100px">
                                </td> 
                                <td id="other_charges" style="display:none;"> 
                                    Other Charges:
                                </td>  
                                <td>
                                    <input type=text name="othr_prs2" id="othr_prs2" style="width:100px;display:none">
                                </td>
                                
                    		</tr> 

                        <tr id="prs_typ">
                            
                            <td width="110px" align="left" id="pr" style="padding-left:220px;display:none" colspan="2">
                              <label  for="one" style="font-size:11px;" >
                                lacs &nbsp;   
                                 <input type="radio" id="lkhs1" name="lkhs1" value="y" checked="checked" /> 
                                 &nbsp;&nbsp; crs &nbsp;
                                 <input type="radio" id="crs1" name="crs1" value="n" />
                              </label>    
                            </td>

                        

                            <!-- <td width="630px" align="left" id="tr" style="padding-left:220px;display:none">

                              <label  for="one" style="font-size:11px;">
                                lacs &nbsp;   
                                  <input type="radio" id="lkhs2" name="prstp2" value="y" checked="checked" /> 
                                  &nbsp;&nbsp; crs &nbsp;
                                  <input type="radio" id="crs2" name="prstp2" value="n" />
                              </label>    
                            </td>  --> 

                        </tr>
                       
                      
                    	<tr id="hln">
                            
                            <td width="100px">Home Loan</td>
                            <td>
                                <select name="home_loan" id="home_loan" style="width:100px;margin-right: 20px;">
                                  <option value="">Select</option>
                                  <option value="true">Yes</option>
                                  <option value="false">No</option>
                              </select>
                           </td>
                        	<td id="hln1">Home Loan Bank</td>
                        	<td  id="hln2" >
                          		<select name="bnk_lst" id="bnk_lst" style="width:200px;" >
                                            <option value=''> select bank	</option>
                                            {foreach from=$bankArray key=k item=v}
                                                <option value="{$k}" {if $bankId==$k}  selected="selected" {/if}>{$v}</option>
                                            {/foreach}
                                        </select> 
                        	</td> 

	                        <td id = "plc1">PLC</td>
	                                        
	                        <td id="plc4" >
	                           <input type=text name="plc5" id="plc5" width="20px" style="text-align: left;">
	                        </td> 
	                         
	                        
                      </tr>

                      <tr id = "negotiable_id" style="position:absolute;left:300px;top:1240px">
                          <td id ="tfr1" >
                              Transfer Rate:     
                          </td>
                          <td colspan="2">
                              <input type=text name="tfr2" id="tfr2" style="width:100px">
                          </td>

                          <!-- <td width="110px" align="left" id="tfr_price" style="padding-left:-10px;" >
                              <select name="transfer_sel" id="transfer_sel" style="height:28px;width:80px">
		                                    <option value="0">Select</option>
		                                    <option value="1">Lacs</option>
		                                    <option value="2">Crs</option>  
		                      </select> 
                          </td> -->

                          
                      </tr>
                      		<hr id = "line5" >

                      <tr id="discription1">
                        <td id = "discription4">
                            Description
                        </td> 
                        <td id = "discription2" >
                              <textarea type=text name="description3" id="description3" style="width:250px" >
                              </textarea>
                        </td>
                        <td id="review1">
                            Remark
                        </td>
                        <td id="review2">
                            <textarea type=text name="review3" id="review3" style="height:100px;width:250px" >
                            </textarea>
                        </td>
                      </tr>
                      <tr id="booking_status_row" style="position: absolute;left: 300px;top: 1580px">
                          <td>Booking Status</td>
                          <td>
                                <select name="booking_status" id="booking_status">
                                    {*<option value=""> Select Booking Status </option>*}
                                    {foreach from=$bStatusList key=bStatusId item=bstatus}
                                        <option value="{$bStatusId}"> {$bstatus} </option>
                                    {/foreach}
                                </select>
                          </td>
                          <td width="110px"></td>
                          <td width="100px">Frunished</td>
                          <td>
                              <select name="furnished_options" id="furnished_options">
                                  <option value="">Select</option>
                                  {foreach from=$furnished_options item=furnished_option}
                                    <option value="{$furnished_option}">{$furnished_option}</option>
                                  {/foreach}
                              </select>
                          </td>
                      </tr>

                      <tr>
                        <td id = "image_link" colspan="4">
                            
                        </td> 
                        
                      </tr>
                      <!--<a target="_blank" href="https://www.proptiger.com/">  
                        <IMG STYLE="position:absolute; TOP:950px; LEFT:330px; WIDTH:150px; HEIGHT:100px" SRC="car.jpg">
                        </IMG>
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/"> 
                        <IMG STYLE="position:absolute; TOP:950px; LEFT:500px; WIDTH:150px; HEIGHT:100px" SRC="sunrise.jpeg">
                        </IMG>
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/"> 
                        <IMG STYLE="position:absolute; TOP:950px; LEFT:670px; WIDTH:150px; HEIGHT:100px" SRC="car.jpg">
                        </IMG>
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/">   
                        <IMG STYLE="position:absolute; TOP:950px; LEFT:840px; WIDTH:150px; HEIGHT:100px" SRC="sunrise.jpeg">
                        </IMG>  
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/">   
                        <IMG STYLE="position:absolute; TOP:950px; LEFT:1010px; WIDTH:150px; HEIGHT:100px" SRC="car.jpg">
                        </IMG>
                      </a>
                      

                      <a target="_blank" href="https://www.proptiger.com/"> 
                        <IMG STYLE="position:absolute; TOP:1070px; LEFT:330px; WIDTH:150px; HEIGHT:100px" SRC="car.jpg">
                        </IMG>
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/">   
                        <IMG STYLE="position:absolute; TOP:1070px; LEFT:500px; WIDTH:150px; HEIGHT:100px" SRC="sunrise.jpeg">
                        </IMG>
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/">   
                        <IMG STYLE="position:absolute; TOP:1070px; LEFT:670px; WIDTH:150px; HEIGHT:100px" SRC="car.jpg">
                        </IMG>
                      </a>
                      <a target="_blank" href="https://www.proptiger.com/">   
                        <IMG STYLE="position:absolute; TOP:1070px; LEFT:840px; WIDTH:150px; HEIGHT:100px" SRC="sunrise.jpeg">
                        </IMG>
                      </a>                       
                      <a target="_blank" href="https://www.proptiger.com/">   
                        <IMG STYLE="position:absolute; TOP:1070px; LEFT:1010px; WIDTH:150px; HEIGHT:100px" SRC="car.jpg">
                        </IMG>
                      </a>  -->
 

          </form>          
                
            			<tr>
                      <td width="400px"> </td>

                    		<td align="left" style="padding-top:1480px;" >
                       			<input type="button" name="lmkSave" id="lmkSave" value="Save" style="cursor:pointer"> &nbsp;&nbsp;     
                       			<input type="button" name="exit_button" id="exit_button" value="Exit" style="cursor:pointer">                 
                    		</td>
            			</tr>
        			</div>
    			</form>
    		</table> 
    	</div> 




                    <div class="modal">Please Wait..............</div>
                    <div id="search-bottom">
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0 id="listing_table" class="tablesorter">
                        <form name="form1" method="post" action="">
                           <thead>
                                <TR class = "headingrowcolor">
                                    <th align="center" class="filter-false sorter-false">Serial</th>
                                  <TH align="center" class="filter-false sorter-false">Listing Id</TH>
                                  <th align="center" class="filter-false sorter-false">City</th>
                                  <TH align="center" class="filter-false sorter-false">Broker Name</TH>
                                  <TH align="center" class="filter-false sorter-false">Project</TH>
                                  <TH align="center" class="filter-false sorter-false">Listing</TH>
                                  <TH align="center" class="filter-false sorter-false">Price</TH>
                                  <TH align="center" class="filter-false sorter-false">Created Date</TH>
                                  <TH align="center" class="filter-false sorter-false">Photo</TH>
                                  <TH align="center" class="filter-false sorter-false">Price Verified</TH>
                                  <TH align="center" class="filter-false sorter-false">Error Messsage</TH>
                                  <TH align="center" class="filter-false sorter-false">Save</TH>
                                  {if $listingDelAuth==true}
                                    <TH align="center" class="filter-false sorter-false">Delete</TH>
                                  {/if}
                                </TR>
                              
                          </thead>
                          <tbody>
                               
                               
                                <!--<TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD></TR>-->
                          </tbody>
                          
                          <tr>
                                <td class="pager" colspan="13">
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
$(document).ready(function(){
    $(".delete-list").live("click",function(event){
        var listing_id= $(this).attr("data-listingId");
        if(confirm("Are you sure! you want to delete this record.")){
            $.ajax({
                type: "POST",
                url: '/saveSecondaryListings.php',
                data : { task : "delete_listing", listingId: listing_id},
                success : function(data, text){
                    data = JSON.parse(data);
                    if(data.code==2){
                        var target = $( event.target );
                        target.closest('tr').hide();
                    }
                    else{
                        alert(data.msg);
                    }
                },
                error : function(request, status, error){
                    alert(request.responseText);
                }
            });
        }
        
    });
    
    //***** Filter *************
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _renderItem: function( ul, item ) {
          return $( "<li>" )
            .append( $( "<a>" ).text( item.label )).appendTo( ul );
        },
    });
    $( "#search_landmark" ).catcomplete({
        source: function( request, response ) {

          $.ajax({
            url: "https://proptiger.com/columbus/app/v4/typeahead",
            dataType: "json",
            data: {
              query: $("#search_landmark").val(),
              enhance: "gp", 
              city: ($("#citydd").val()) ? $("#citydd :selected").text().trim() : ""
            }, 

            success: function( data ) { 
              response( $.map( data.data, function( item ) {              
                  return {
                  label: item.displayText,
                  googlePlaceId: item.googlePlaceId,
                  id:item.id,
                  }

              }));
            }
          });      
        },

        select: function( event, ui ) {
          $("#hidden_gpid").val(ui.item.googlePlaceId);
        },
        open: function() {
          $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
          $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        },

    });
    $("#search_term").change(function(){
        $( "#search_landmark" ).val("");
        $( "#search_value" ).val("");
            
        if($("#search_term").val() == "gpid"){
            $( "#search_landmark" ).removeClass("hide-input");
            $( "#search_value" ).addClass("hide-input");
        }
        else{
            $( "#search_landmark" ).addClass("hide-input");
            $( "#search_value" ).removeClass("hide-input");
        }
    });
    
    $("#project_search").keypress(function(event){
        if($("#citydd").val() == ""){
            $("#peoject_search_msg").text("Please select city first for better project search experience");
        }else{
            $("#peoject_search_msg").text('');
        }
    });
    $("#project_search").focusout(function(){
        $("#peoject_search_msg").text('');
    });
    
});

</script>
