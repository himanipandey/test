<?php
    session_start();
    include("../dbConfig.php");    
    include("../appWideConfig.php");    
    include("../builder_function.php");
    include("../modelsConfig.php");
    //include("../includes/configs/configs.php");

    $lot_updated_data = $_POST['lot_updated_data'];
    $lot_content_id = $_POST['lot_content_id'];
    
    $condition = '';
    if($lot_updated_data == ''){
       $condition = 'OR updated_content is null'; 
    }
    
    $sqlLotContent = mysql_query("SELECT id FROM content_lot_details"
            . " WHERE (updated_content = '".addslashes($lot_updated_data)."' $condition) AND id = '".$lot_content_id."'") or die(mysql_error());
    
    if(mysql_num_rows($sqlLotContent) > 0){
        print 1;
    }else{
        print 0;
    }
  ?>
