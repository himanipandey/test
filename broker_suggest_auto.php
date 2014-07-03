<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript"> 
jQuery(document).ready(function(){
    $('#brokerName').autocomplete({source:"suggest_auto.php?type=broker", minLength:1});
    $("input[name=txtSubsUserEmail[]]").autocomplete({source:"suggest_auto.php?type=broker", minLength:1});
});
</script>
<link rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.2.custom.css" />
<style type="text/css">
<!--
/* style the auto-complete response */
li.ui-menu-item { font-size:12px !important; }
-->
</style> 
