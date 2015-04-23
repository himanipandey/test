<!--<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> -->
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#cityName').autocomplete({
            source: "suggest_auto.php?type=city",
            minLength : 1,
            select: function(event, ui){
                if ($('#city').length) {                    
                    $("#city").val(ui.item.city_id);
                    update_locality(ui.item.city_id);
                }
                if($("#cityId").length){
                   $("#cityId").val(ui.item.city_id);
                   update_locality(ui.item.city_id); 
                }
            },
            response: function(event, ui){
                if(ui.content.length == 0){                 
                    $("#city").val(''); 
                    $("#cityId").val('');
                }
            }
            
        });
        $('#builderName').autocomplete({
            source: "suggest_auto.php?type=builder",
            minLength: 1,
            select: function (event, ui) {                

                //updating builder image
                if ($('#builderbox').length) {
                    $(".builderId").val(ui.item.builder_id); 
                    getBuilderImage();
                }
                if ($('.builerUPdate').length) {
                    $(".builerUPdate").val(ui.item.builder_id);                   
                }
                if ($('#builder').length) {                    
                    $("#builder").val(ui.item.builder_id);
                }
            },
            response: function( event, ui ) {
                if(ui.content.length == 0){                 
                    $(".builderId").val('');                    
                }
            }
        });
        $('#townshipName').autocomplete({
            source: "suggest_auto.php?type=townships&id=true",
            minLength: 1,
            select: function (event, ui) {
                if($("#townshipId").length){
                    $("#townshipId").val(ui.item.id);
                }
                if($("#township").length){
                    $("#township").val(ui.item.id);
                }
                
            },
            response: function( event, ui ) {
                if(ui.content.length == 0){                  
                    $("#township").val('');
                }
                   
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
