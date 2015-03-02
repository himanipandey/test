$('document').ready(function(){


	$("#bkn2").click(function(){
		var broker_id = $("#bkn2 :selected").val();
	    var pt_broker_id =  $("#pt_broker_id").val();
	    console.log(broker_id +" "+pt_broker_id);
			if(broker_id == pt_broker_id){
				console.log("ids matched" );	
				$('#name_font').show(1);
				$('#number_font').show(1);	
			} else {
				console.log("ids not matched");	
				$('#name_font').hide(1);
				$('#number_font').hide(1);	

			}
	});


	var bh3 = '#bh3';
	$(bh3).click(function(){
		if($(bh3).val() === 'other'){	
			/*$('#othr').show(1);*/	
			$('#appartment1').show(1);
			$('#appartment2').show(1);
		} else {
			$('#othr').hide(1);
			$('#study_servant').hide(1);
			$('#othr2').val('');
			$('#bed2').val('');
			$('#tol3').val('');
			$('#appartment3').val('');
			$('#appartment1').hide(1);
			$('#appartment2').hide(1);


		}
	});

//option type change event handler


	var appartment3 = '#appartment3';
	$(appartment3).click(function(){
		if($(appartment3).val() === '1' || $(appartment3).val() === '2'){	
			$('#study_servant').show(1);
			$('#othr').show();	
			$('#bath').show();
			$('#bath1').show();
			$('#tol1').show();
			$('#tol2').show();
			$('#tol3').show();
			
			

		} else {
			$('#study_servant').hide(1);
			$('#othr').hide();
			$('#bath').hide();
			$('#bath1').hide();
			$('#tol1').hide();
			$('#tol2').hide();

			$('#bed2').val('');
			$('#tol3').val('');
		}
	});


//price change event handler

	$('#prs5').click(function(){
		//$('#prs3').show(1);
		if($('#prs5 :selected').val() == '2'){	
			//$('#other_charges').show();
			$('#othr_prs2').show();
			$('#tr').show();
			$('#other_charges').show();
		}
		else {
			//$('#pr').hide();
			$('#other_charges').hide();
			$('#othr_prs2').hide();
			$('#tr').hide();
		}
	}); 

	

	$('#yes_conf').click(function(){
		$('#no_conf').removeAttr('checked');
		$('#proj').show(1);
		$('#proj1').show(1);
		$('#project1').hide(1);
		$('#project').hide(1);
	});

	$('#no_conf').click(function(){
		$('#yes_conf').removeAttr('checked');
		$('#project1').show(1);
		$('#project').show(1);
		$('#proj').hide(1);
		$('#proj1').hide(1);
	});

	$('#lkhs_tfr').click(function(){
		$('#crs_tfr').removeAttr('checked');
	});
	$('#crs_tfr').click(function(){
		$('#lkhs_tfr').removeAttr('checked');
	});


	$('#crs1').click(function(){
		$('#lkhs1').removeAttr('checked');
	});
	$('#lkhs1').click(function(){
		$('#crs1').removeAttr('checked');
	});

	$('#crs2').click(function(){
		$('#lkhs2').removeAttr('checked');
	});
	$('#lkhs2').click(function(){
		$('#crs2').removeAttr('checked');
	});

	$('#yes').click(function(){
		$('#no').removeAttr('checked');
		$('#bank_list2').show(1);
	});

	$('#no').click(function(){
		$('#yes').removeAttr('checked');
		$('#bank_list2').show(1);
		$('#bank_list2').val("");
	});

	/*$('#plcy').click(function(){
		$('#plcn').removeAttr('checked');
		$('#plc3').show(1);
	});

	$('#plcn').click(function(){
		$('#plcy').removeAttr('checked');
		$('#plc3').hide(1);
		$('#plc3').val("");
	});*/

	$('#yes_study').click(function(){
		$('#no_study').removeAttr('checked');
	});

	$('#no_study').click(function(){
		$('#yes_study').removeAttr('checked');
	});

	$('#yes_servant').click(function(){
		$('#no_servant').removeAttr('checked');
	});

	$('#no_servant').click(function(){
		$('#yes_servant').removeAttr('checked');
	});


	$('#negotiable_yes').click(function(){
		$('#negotiable_no').removeAttr('checked');
	});
	$('#negotiable_no').click(function(){
		$('#negotiable_yes').removeAttr('checked');
	});

// ajax loader

	 /*$('#modal').ajaxStart(function () {
        $(this).fadeIn('fast');
    }).ajaxStop(function () {
        $(this).stop().fadeOut('fast');
    });

    $('#modal').ajaxStart(function () {
        $(this).fadeIn('fast');
    }).ajaxStop(function () {
        $(this).stop().fadeOut('fast');
    });*/

	$body = $("body");
	
	$(document).on({
	    ajaxStart: function() { $body.addClass("loading");   $("#lmkSave").attr('disabled', true); $("#exit_button").attr('disabled', true); $("#create_button").attr('disabled', true);},
	     ajaxStop: function() { $body.removeClass("loading"); $("#lmkSave").attr('disabled', false); $("#exit_button").attr('disabled', false); $("#create_button").attr('disabled', false);}  

	   
	});

});