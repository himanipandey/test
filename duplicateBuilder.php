<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

    $data = array();
    $query = "select BUILDER_ID, BUILDER_NAME, BUILDER_IMAGE, ENTITY from proptiger.RESI_BUILDER order by BUILDER_NAME asc";
    $res = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_array($res)){
        array_push($data, $row); 
    }

    $smarty->assign("builderList", $data);

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."duplicateBuilder.tpl");

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

?>
