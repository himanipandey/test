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

			var html='';
			var roomCategory=$("#roomCategory").html();


			html+="<div><form name='f1' method='post' id='f1'><div><input type='hidden' name='optionId' value='"+optionID+"' ></div>";
			html+="<div style='width:700px;'><span style='width:100px; float:left;'><b>Type</b></span><span style='width:150px; float:left;'><b>Category Room</b></span><span style='width:150px; float:left;'><b>Length(ft)</b></span><span style='width:150px; float:left;'><b>Breath(ft)</b></span></div>";
			var j = 1;
			if(beds!=0)
			{

				for (var i=1; i<=beds; i++) {
				html+="<br><div>Bedroom "+i+" : "+ roomCategory.replace("roomCategory", "roomCategory_"+j)+" <input type='text' name='length_"+j+"'  onkeypress='return isNumberKey(event)'/> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'/></div>";
				j++;
				}
			}

			if(bathrooms!=0)
			{

				for (var i=1; i<=bathrooms; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Bathroom "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+3+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)'/> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'/></div>";
				j++;
				}
			}

			if(balconys!=0)
			{

				for (var i=1; i<=balconys; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Balcony "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+7+"'></span> <span style='width:100px; float:left;'>&nbsp;</span><span style='width:100px; float:left;'></span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)'/> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'/></div>";
				j++;
				}
			}

			if(servantrooms!=0)
			{

				for (var i=1; i<=servantrooms; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Servant "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+8+"'> </span><span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)'/> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'/></div>";
				j++;
				}
			}

			if(studyroom!=0)
			{

				for (var i=1; i<=studyroom; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Study "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+9+"'></span> <span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)'/> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'/></div>";
				j++;
				}
			}

			if(poojaroom!=0)
			{

				for (var i=1; i<=poojaroom; i++) {
				html+="<br><div><span style='width:110px; float:left;'>Pooja Room "+i+" :  <input type='hidden' name='roomCategory_"+j+"' value='"+10+"'></span> <span style='width:100px; float:left;'>&nbsp;</span><input type='text' name='length_"+j+"' onkeypress='return isNumberKey(event)'/> &nbsp;&nbsp;&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'/></div>";
				j++;
				}
			}

			html+="<br><div><span style='width:110px; float:left;'>Living : <input type='hidden' name='roomCategory_"+j+"' value='"+5+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'>&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Dining : <input type='hidden' name='roomCategory_"+j+"' value='"+6+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'>&nbsp;<input type='text'name='breath_"+j+"' onkeypress='return isNumberKey(event)'></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Kitchen : <input type='hidden' name='roomCategory_"+j+"' value='"+4+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'>&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Powder : <input type='hidden' name='roomCategory_"+j+"' value='"+11+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'>&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'></div>"; j++;
			html+="<br><div><span style='width:110px; float:left;'>Utility : <input type='hidden' name='roomCategory_"+j+"' value='"+13+"'></span><span style='width:100px; float:left;'>&nbsp;</span><input type='text'  name='length_"+j+"' onkeypress='return isNumberKey(event)'>&nbsp;<input type='text' name='breath_"+j+"' onkeypress='return isNumberKey(event)'></div>";

			html+="<input type='hidden' name='count' value='"+j+"'>";
			html+="<input type='hidden' name='rowId' value='"+rowId+"'>";

			html+="<br><br><div><input type='button' name='Save' value='Save' onClick='submitroomCategory()'/></div></form></div>";




			 $.fancybox({
			'content' : html,
			 'onCleanup' : function() {
					$("#row_"+rowId).remove();
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
