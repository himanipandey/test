
<?php

error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");

include("dbConfig.php");

include("modelsConfig.php");

include("includes/configs/configs.php");

include("builder_function.php");
//die("here");
include("function/functions_priority.php");
//die("here");

//echo TEST;
$BaseURL = MAPURL;
$smarty->assign('MapURL', MAPURL);
$smarty->assign('MAPURLDRAW', MAPURL."/boundaryTracing/googleMapDrawing.html");
//die;
AdminAuthentication();
//die("here");

    include('LocalityNearPlacesPriorityProcess.php');
    //die("here");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."localityNearPlacesPriority.tpl");
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>

<!--<script type="text/javascript">var BaseUrl = "<?= $BaseURL ?>";</script>
<script type="text/javascript" src="boundaryTracing/drag.js"></script> -->