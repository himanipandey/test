<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript"> 
jQuery(document).ready(function(){
    $('#builderName').autocomplete({
        source:"suggest_auto.php?type=builder", 
        minLength:1,
        select: function( event, ui ) {
          $(".builerUPdate").val(ui.item.builder_id);
          $("#builder").val(ui.item.builder_id);
        }
    });
    $('#townshipName').autocomplete({
        source:"suggest_auto.php?type=townships&id=true", 
        minLength:1,
        select: function( event, ui ) {
          $("#townshipId").val(ui.item.id);
        }
    });
});
</script>
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.2.custom.css" />
<style type="text/css">
<!--
/* style the auto-complete response */
li.ui-menu-item { font-size:12px !important; }
-->
</style> 
