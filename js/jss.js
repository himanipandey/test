$('document').ready(function(){
	var bh3 = '#bh3';
	$(bh3).click(function(){
		if($(bh3).val() === 'other'){	
			$('#othr').show(1);	
		} else {
			$('#othr').hide(1);
		}
	});

	$('#prs5').click(function(){
		$('#prs3').show(1);
		if($('#prs5 :selected').val() == '2'){	
			$('#other_charges').show();
			$('#pr').hide();
		}
		else if($('#prs5 :selected').val() == '1'){	
			$('#pr').show();
			$('#other_charges').hide();
		} else {
			$('#pr').hide();
			$('#other_charges').hide();
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
		$('#bank_list2').hide(1);
		$('#bank_list2').val("");
	});

	$('#plcy').click(function(){
		$('#plcn').removeAttr('checked');
		$('#plc3').show(1);
	});

	$('#plcn').click(function(){
		$('#plcy').removeAttr('checked');
		$('#plc3').hide(1);
		$('#plc3').val("");
	});

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
});