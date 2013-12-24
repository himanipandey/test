$(document).ready(function(){

		$(".insertProject").live('click', function() {
		var rowId=jQuery(this).attr('rel');

		var requestURL = "";
		$(this).parent().parent().find('input,select').each(function(index) {

		var insertValue=$(this).val();

		if((insertValue.indexOf('+') > -1))
			{
				insertValue=insertValue.replace("+","@");
			}


			requestURL += $(this).attr('tempName') + "=" + insertValue + "&";
		});
		requestURL=requestURL.substring(0, requestURL.length - 1);
		//alert(requestURL);
		$.ajax({
        url: "insertOptions.php?d="+rowId,
        type: "post",
        data: requestURL,
        // callback handler that will be called on success
        success: function(response){
	
			var array = response.split('_');
			var optionID=array[0];
			var beds=array[1];
			var bathrooms=array[2];
			var balconys=array[3];
			var servantrooms=array[4];
			var studyroom=array[5];
			var poojaroom=array[6];
			var rowId=array[7];
			
			//room sizes
			var bedsizes=array[8]; bedsizes = bedsizes.split('#');
			var bedscat=array[9]; bedscat = bedscat.split('#');
			var bathroomsizes=array[10]; bathroomsizes = bathroomsizes.split('#');
			var balconysizes=array[11]; balconysizes = balconysizes.split('#');
			var servantroomsizes=array[12]; servantroomsizes = servantroomsizes.split('#');
			var studyroomsizes=array[13]; studyroomsizes = studyroomsizes.split('#');
			var poojaroomsizes=array[14]; poojaroomsizes = poojaroomsizes.split('#');
			
			var diningsizes=array[15]; diningsizes = diningsizes.split('#');
			var kitchensizes=array[16]; kitchensizes = kitchensizes.split('#');
			var livingsizes=array[17]; livingsizes = livingsizes.split('#');
			var powderroomsizes=array[18]; powderroomsizes = powderroomsizes.split('#');
			var terracesizes=array[19]; terracesizes = terracesizes.split('#');
			var utilityroomsizes=array[20]; utilityroomsizes = utilityroomsizes.split('#');
			var familyroomsizes=array[21]; familyroomsizes = familyroomsizes.split('#');
			
			var html='';
			var roomCategory=$("#roomCategory").html();
		
			html+="<div><form name='f1' method='post' id='f1'><div><input type='hidden' name='optionId' value='"+optionID+"' ></div>";
			html+="<div style='width:700px;'><span style='width:100px; float:left;'><b>Type</b></span><span style='width:150px; float:left;'><b>Category Room</b></span><span style='width:150px; float:left;'><b>Length(ft)</b></span><span style='width:150px; float:left;'><b>Breath(ft)</b></span></div><br/>";
			var j = 1;
			if(beds!=0)
			{

				for (var i=1; i<=beds; i++) {
					roomsizes = bedsizes[i]; ''; length = ''; breath = '';bed_cats = bedscat[i];
					//fetching existing sizes
					if(typeof roomsizes != 'undefined' && roomsizes!=''){
						roomsizes = roomsizes.split('@');
						length = roomsizes[0];
						breath = roomsizes[1];
						bed_cats = bedscat[i];
				    }
			
				bedRoomCategory = roomCategory.replace("roomCategory", "roomCategory_"+j);
				bedRoomCategory = bedRoomCategory.replace('value="'+bed_cats+'"', 'value="'+bed_cats+'" selected');
								
				html+="<br><div>Bedroom "+i+" : "+bedRoomCategory+" <input type='text' name='length_"+j+"'  onkeypress='return isNumberKey(event)' value='"+length+"' /> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+breath+"' /></div>";
				j++;
				}
			}

			if(bathrooms!=0)
			{

				for (var i=1; i<=bathrooms; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Bathroom "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+3+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomLength(bathroomsizes,i)+"' /> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomBreath(bathroomsizes,i)+"' /></div>";
				j++;
				}
			}

			if(balconys!=0)
			{

				for (var i=1; i<=balconys; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Balcony "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+7+"'></span> <span style='width:100px; float:left;'>&nbsp;</span><span style='width:100px; float:left;'></span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomLength(balconysizes,i)+"' /> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomBreath(balconysizes,i)+"' /></div>";
				j++;
				}
			}

			if(servantrooms!=0)
			{

				for (var i=1; i<=servantrooms; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Servant "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+8+"'> </span><span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomLength(servantroomsizes,i)+"' /> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomBreath(servantroomsizes,i)+"' /></div>";
				j++;
				}
			}

			if(studyroom!=0)
			{

				for (var i=1; i<=studyroom; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Study "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+9+"'></span> <span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomLength(studyroomsizes,i)+"' /> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomBreath(studyroomsizes,i)+"' /></div>";
				j++;
				}
			}

			if(poojaroom!=0)
			{

				for (var i=1; i<=poojaroom; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Pooja Room "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+10+"'></span> <span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomLength(poojaroomsizes,i)+"' /> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomBreath(poojaroomsizes,i)+"' /></div>";
				j++;
				}
			}
			/*
			var diningsizes=array[15]; diningsizes = diningsizes.split('#');
			var kitchensizes=array[16]; kitchensizes = kitchensizes.split('#');
			var livingsizes=array[17]; livingsizes = livingsizes.split('#');
			var powderroomsizes=array[18]; powderroomsizes = powderroomsizes.split('#');
			var terracesizes=array[19]; terracesizes = terracesizes.split('#');
			var utilityroomsizes=array[20]; utilityroomsizes = utilityroomsizes.split('#');
			var familyroomsizes=array[21]; familyroomsizes = familyroomsizes.split('#');*/
			
			html+="<br><div><span style='width:110px; float:left;'>Living : <input type='hidden' name='roomCategory_"+j+"' value='"+5+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomLength(livingsizes,1)+"' />&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)' value='"+roomBreath(livingsizes,1)+"' /></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Dining : <input type='hidden' name='roomCategory_"+j+"' value='"+6+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'  value='"+roomLength(diningsizes,1)+"'  />&nbsp;<input type='text'name='breath_"+j+"' onkeypress='return isNumberKey(event)'  value='"+roomBreath(diningsizes,1)+"' /></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Kitchen : <input type='hidden' name='roomCategory_"+j+"' value='"+4+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'  value='"+roomLength(kitchensizes,1)+"' />&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'  value='"+roomBreath(kitchensizes,1)+"' /></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Powder : <input type='hidden' name='roomCategory_"+j+"' value='"+11+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'   value='"+roomLength(powderroomsizes,1)+"' />&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'   value='"+roomBreath(powderroomsizes,1)+"' /></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Utility : <input type='hidden' name='roomCategory_"+j+"' value='"+13+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'   value='"+roomLength(utilityroomsizes,1)+"' />&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'   value='"+roomBreath(utilityroomsizes,1)+"'/></div>";

			html+="<input type='hidden' name='count' value='"+j+"'>";
			html+="<input type='hidden' name='rowId' value='"+rowId+"'>";

			html+="<br><br><div><input type='button' name='Save' value='Save' onClick='submitroomCategory()'/></div></form></div>";




			 $.fancybox({
			'content' : html,
			 'onCleanup' : function() {
				//	$("#row_"+rowId).remove();
			 }

});

		
            // log a message to the console
            //console.log("Hooray, it worked!");
        },
        // callback handler that will be called on error
        error: function(jqXHR, textStatus, errorThrown){
            // log the error to the console
            console.log(
                "The following error occured: "+
                textStatus, errorThrown
            );
        },

    });


	});
});


function submitroomCategory() {
	var i = jQuery("#f1").serialize();

	$.ajax({
        url: "enquiryRoom.php",
        type: "post",
        data: i,
	     // callback handler that will be called on success
        success: function(response){
			$("#row_"+response).remove();
			jQuery.fancybox.close();

		}
	});

}

 function isNumberKey(evt)
  {
	 var charCode = (evt.which) ? evt.which : event.keyCode;
	 if(charCode == 99 || charCode == 118)
    	 return true;
	 if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }
  
  function roomLength(arrroomsizes,i){
	  
	 roomsizes = arrroomsizes[i]; length = '';
	 //fetching existing sizes
	 if(typeof roomsizes != 'undefined' && roomsizes!=''){
		roomsizes = roomsizes.split('@');
		length = roomsizes[0];
	 }
	 
	 return length;
	  
  }
  function roomBreath(arrroomsizes,i){
	  roomsizes = arrroomsizes[i];breath = '';
	 //fetching existing sizes
	 if(typeof roomsizes != 'undefined' && roomsizes!=''){
		roomsizes = roomsizes.split('@');
		breath = roomsizes[1];
	 }
	 
	 return breath;
  }
