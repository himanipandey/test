 function selectModuleById(value){
  
   		jQuery.ajax({
   			
   			type: "POST",
   			url:'ajax/parentModuleTitle.php',
   			data: "id="+value,
   			beforeSend: function() {
				jQuery("#res").html('<div style="position:absolute;z-index:1000;"><img src="images/ajax-loader.gif" width="35px;" height="35px;"></div>');
				
			},
			success: function(resp){
				jQuery("#res").html('');
				var resonsedata = resp.split("@");

					if(resonsedata[0]!='' && resonsedata[0]!=0 ){
						jQuery("#modulemsg").show();
						jQuery("#modulemsg").html('<b>This module comes under <font color = "green">'+resonsedata[0]+'</b>.</font>');
					}else if(resonsedata[0]==0){
						jQuery("#modulemsg").show();
						jQuery("#modulemsg").html('<b>This module is set as <font color = "green">Main module</b>.</font>');
						jQuery("#alext").html('');
					}
					if(resonsedata[0]==''){
						jQuery("#modulemsg").show();
						jQuery("#modulemsg").html('<b><font color="red">Please select an option.</b></font>');
					}
				

				if(resonsedata[1]!=''){
					jQuery("#txt_image_bx").val(resonsedata[1]);
				}
   			}
		});

		if(value==0){
			jQuery(".pt_block").show();
			jQuery(".pt_img").hide();
		}else if(value>0){
			jQuery(".pt_img").show();
			jQuery(".pt_block").hide();
		}else {
			jQuery(".pt_block").hide();
			jQuery(".pt_img").hide();
		}
   	}

	jQuery("#btnSave").bind('click',function(){
		
		if(jQuery("#txt_modules").val()==''){
			jQuery("#err_mod").html('please enter a module key');
			jQuery("#txt_modules").focus();
			return false;
		}else{
			jQuery("#err_mod").html('');
		}
		
		if(jQuery("#txt_link").val()==''){ 
			jQuery("#err_link").html('please enter a link url');
			jQuery("#txt_link").focus();
			return false;
		}else{
			jQuery("#err_link").html('');
		}

		if(jQuery("#txt_title").val()==''){ 
			jQuery("#err_title").html('please enter a module title');
			jQuery("#txt_title").focus();
			return false;
		}else{
			jQuery("#err_title").html('');
		}
		
		if(jQuery("#txt_moddesc").val()==''){ 
			jQuery("#err_desc").html('please enter a module description');
			jQuery("#txt_moddesc").focus();
			return false;
		}else{
			jQuery("#err_desc").html('');
		}
		
		if(jQuery("#parent").val()=='select'){ 
			jQuery("#modulemsg").html('<span style="color:red">Please select an option.</span>');
			jQuery("#parent").focus();
			return false;
		}else{
			jQuery("#modulemsg").html('');
		}

		
		if(jQuery("#parent").val()==0){

			if(jQuery("#txt_image").val()==''){ 
				jQuery("#alert").html('<span style="color:red;font-weight:normal">Please choose an image for module.</span>');
				jQuery("#txt_image").focus();
				return false;
			}else{
				jQuery("#alert").html('');
			}
		}
	});


jQuery("#txt_modules").blur(function(){

var module_key = jQuery(this).val();
jQuery.ajax({
	type :"POST",
	url:"ajax/parentModuleTitle.php",
	data:"part=checkModuleKey&key="+module_key,
    beforeSend:function(){
	
	},
	success:function(responsedata){

		if(responsedata>0){
			jQuery("#err_mod").html('<img src="images/wrong.png" width="13" height="13">&nbsp;&nbsp;<span style="color:red">Module key <b>'+module_key+'</b> already Exist.<b>!</b></span>');
		}else{
			jQuery("#err_mod").html('<img src="images/right.png" width="13" height="13">&nbsp;&nbsp;<span style="color:green">Module key <b>'+module_key+'</b> Avaliable.</span>');
			}
	}	
	});
});