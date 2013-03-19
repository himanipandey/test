<!-- Load jQuery -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("jquery", "1");
</script>

<!-- Load TinyMCE -->
<script type="text/javascript" src="tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">

	function closefancy()
	{
		var desc=$("#elm1").val();
		$("#projectDesc", window.parent.document).html(desc);	
		parent.$.fancybox.close();

	}

	function closefancy2()
	{
		parent.$.fancybox.close();

	}
	$().ready(function() {

		var projectDesc=$("#projectDesc", window.parent.document).val();	
		
		$("#elm1").val(projectDesc);

		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'tiny_mce/tiny_mce.js',

			// General options
			theme : "simple",
			 height : "400",
			  width : "685",

			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			force_p_newlines : false,
			force_br_newlines : true,
			forced_root_block : '',

			// Example content CSS (should be your site CSS)
			content_css : "tiny_mce/css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>
<!-- /TinyMCE -->

<div>
			<textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 80%" class="tinymce">
				
			</textarea></div>
<div style="float:right; padding-right:5px; padding-top:10px;">
			<input type="submit" name="Change" value="Change" onclick="closefancy()" />
			<input type="submit" name="Exit" value="Exit" onclick="closefancy2()" />
</div>

		
