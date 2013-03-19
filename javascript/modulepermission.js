
	jQuery("#mainmodule").change(function(){
	
		var mod_id = jQuery(this).val();
		jQuery.ajax({
   			
   			type: "POST",
   			url:'ajax/blockModuleByUser.php',
   			data: "part=getsubmodule&id="+mod_id,
   			beforeSend: function() {
				jQuery("#res").html('<div style="position:absolute;z-index:1000;"><img src="images/ajax-loader.gif" width="35px;" height="35px;"></div>');
			},
			success: function(resp){
				jQuery("#res").html('');
				jQuery("#pt_subblk_submod").show();
				jQuery("#submod").html(resp);
			}
		});
	});


	jQuery("#accesslevel_2").change(function(){
	
		var acc_level = jQuery(this).val();
		jQuery.ajax({
   			type: "POST",
   			url:'ajax/blockModuleByUser.php',
   			data: "part=getuser&al="+acc_level,
   			beforeSend: function() {
				jQuery("#res1").html('<div style="position:absolute;z-index:1000;"><img src="images/ajax-loader.gif" width="35px;" height="35px;"></div>');
			},
			success: function(response){
				jQuery("#res1").html('');
				jQuery("#pt_subblk_user").show();
				jQuery("#user").html(response);
			}
		});
	});


	jQuery("#btnSave").bind('click',function(){
		var modtitle = jQuery("#modtitle").val();
		var accesslevel = jQuery("#accesslevel").val();

		if(modtitle=='select'){
			jQuery("#noerr").html('');
			jQuery("#errr").show();
			jQuery("#errr").html('Please select a module title');
			return false;
		}else{
			jQuery("#errr").html('');
		}
		
	
	 if(accesslevel=='select'){
			jQuery("#noerr").html('');
			jQuery("#errr").show();
			jQuery("#errr").html('please select a access level');
			return false;
		}else{
			jQuery("#errr").html('');
		}
	});


	jQuery("#btnBlock").bind('click',function(){
		var mainmodule = jQuery("#mainmodule").val();
		var submod = jQuery("#submod").val();
		var accesslevel = jQuery("#accesslevel_2").val();
		var user = jQuery("#user").val();
		

		if(mainmodule=='select'){
			jQuery("#noerr2").html('');
			jQuery("#errr_2").show();
			jQuery("#errr_2").html('Please select a main module');
			return false;
		}else{
			jQuery("#errr_2").html('');
		}
		
	
	 if(submod==''){
			jQuery("#noerr2").html('');
			jQuery("#errr_2").show();
			jQuery("#errr_2").html('please select a sub module');
			return false;
		}else{
			jQuery("#errr_2").html('');
		}

	 if(accesslevel=='select'){
			jQuery("#noerr2").html('');
			jQuery("#errr_2").show();
			jQuery("#errr_2").html('Please select an access level');
			return false;
		}else{
			jQuery("#errr_2").html('');
		}

	if(user==''){
			jQuery("#noerr2").html('');
			jQuery("#errr_2").show();
			jQuery("#errr_2").html('Please select a user name');
			return false;
		}else{
			jQuery("#errr_2").html('');
		}
	});


	jQuery("#mainmodule").change(function(){
	
		var mod_id = jQuery(this).val();
		jQuery.ajax({
   			
   			type: "POST",
   			url:'ajax/blockModuleByUser.php',
   			data: "part=changeAccessLevel&id="+mod_id,
   			beforeSend: function() {
				jQuery("#res").html('<div style="position:absolute;z-index:1000;"><img src="images/ajax-loader.gif" width="35px;" height="35px;"></div>');
			},
			success: function(respdt){
				
				
				jQuery("#accesslevel_2").html(respdt);
			}
		});
	});

