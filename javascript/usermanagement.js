jQuery(document).ready(function(){
	jQuery("input[name='radiopass']").click(function(){

		if (jQuery("input[name='radiopass']:checked").val() == '1') {
			jQuery('.pt_selectradio').show();
		}else{
			jQuery('.pt_selectradio').hide();
		}

	});

	if(jQuery('#userid').val()==''){

		jQuery(".pt_changestatus").hide();
	
	}else{
		jQuery(".pt_changestatus").show();
	}

	if(jQuery("#dept").val()=='SALES'){
	
		jQuery("#targetlevel").show();
	}else{
		jQuery("#targetlevel").hide();
	}

});



function hideBlockFunc(level){

	if(level=='1' || level=='0'){
		jQuery('.pt_hidemanagers').hide();
	}else{
		jQuery('.pt_hidemanagers').show();
	}
}



function saveManager(managerid){
	//alert(managerid)

	jQuery('#managerids').val(managerid);
}

function selectedCityByRegion(zonecode){

	if(zonecode=='E'){
		var newOptions = {
		'Kolkata' : 'Kolkata'

	};

		var select = jQuery('#branch');
		if(select.prop) {
			var options = select.prop('options');
		}
		else {
			var options = select.attr('options');
		}
		jQuery('option', select).remove();

		jQuery.each(newOptions, function(val, text) {
			options[options.length] = new Option(text, val);
		});

	}else if(zonecode=='W'){

		var newOptions = {
		'Pune' : 'Pune',
		'Mumbai' : 'Mumbai',

	};

		var select = jQuery('#branch');
		if(select.prop) {
			var options = select.prop('options');
		}
		else {
			var options = select.attr('options');
		}
		jQuery('option', select).remove();

		jQuery.each(newOptions, function(val, text) {
			options[options.length] = new Option(text, val);
		});

	}else if(zonecode=='N'){

		var newOptions = {
		'Delhi' : 'Delhi',
		'Ghaziabad' : 'Ghaziabad',
		'Gurgaon' : 'Gurgaon',
		'Noida' : 'Noida',
		};

		var select = jQuery('#branch');
		if(select.prop) {
			var options = select.prop('options');
		}
		else {
			var options = select.attr('options');
		}
		jQuery('option', select).remove();

		jQuery.each(newOptions, function(val, text) {
			options[options.length] = new Option(text, val);
		});

	}

	else if(zonecode=='S'){

		var newOptions = {
		'Chennai' : 'Chennai',
		'Indore' : 'Indore',
		'Ahmedabad' : 'Ahmedabad',
		'Bangalore' : 'Bangalore',
		};

		var select = jQuery('#branch');
		if(select.prop) {
			var options = select.prop('options');
		}
		else {
			var options = select.attr('options');
		}
			jQuery('option', select).remove();

			jQuery.each(newOptions, function(val, text) {
				options[options.length] = new Option(text, val);
			});

		}

	else if(zonecode==''){

		var newOptions = {
		'Chennai' : 'Chennai',
		'Indore' : 'Indore',
		'Ahmedabad' : 'Ahmedabad',
		'Bangalore' : 'Bangalore',
		'Pune' : 'Pune',
		'Mumbai' : 'Mumbai',
		'Delhi' : 'Delhi',
		'Ghaziabad' : 'Ghaziabad',
		'Gurgaon' : 'Gurgaon',
		'Noida' : 'Noida',
		'Kolkata' : 'Kolkata'
		};

		var select = jQuery('#branch');
		if(select.prop) {
			var options = select.prop('options');
		}
		else {
			var options = select.attr('options');
		}
		jQuery('option', select).remove();

		jQuery.each(newOptions, function(val, text) {
			options[options.length] = new Option(text, val);
		});

	}
}


function SelectTargetLevelForSales(value,inputtype){


	if(inputtype=='department'){
		if(value=='SALES'){
			jQuery("#targetlevel").show();
		}else{
			jQuery("#targetlevel").hide();
		}
		var department = value;
		var branch = jQuery("#branch").val();
	}
	
	if(inputtype=='branch'){
		var branch = value;
		var department = jQuery("#dept").val();
	}

	if(department!='' && branch!=''){
		jQuery.ajax({
		   type: "POST",
		   url: "RefreshBanStat.php",
		   data: "branch="+branch+"&department="+department+"&part=findallmanagers",
		   success: function(data) {
			 jQuery('#changemanager').html(data);
		  }
		});
	}
}

