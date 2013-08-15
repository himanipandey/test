<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

    $data = array();
    //change the db to project
    $query = "select L.LOCALITY_ID, L.LABEL as LOCALITY, C.LABEL as CITY, S.LABEL as SUBURB from proptiger.LOCALITY as L join proptiger.CITY as C on L.CITY_ID=C.CITY_ID join proptiger.SUBURB as S on L.SUBURB_ID=S.SUBURB_ID where L.ACTIVE = '1' order by L.LOCALITY_ID asc";
    $res = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_array($res)){
        array_push($data, $row); 
    }

    $smarty->assign("localityList", $data);

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."duplicateLocality.tpl");

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

?>
