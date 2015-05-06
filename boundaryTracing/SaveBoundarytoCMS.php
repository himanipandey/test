<?php
	include("dbConfig.php");

    $Sql = "SELECT * FROM boundary_data";
    $Tower = array();
    $ExecSql = mysql_query($Sql) or die();
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $tmp = array();
            $tmp['id'] = $Res['id'];
            $tmp['name'] = $Res['boundary'];
            if($Res['tower_id']!='')
                array_push($Tower, $tmp);
            $cnt++;
            //$tower = $Res['TOWER_NAME'];
        }    
    }
    //echo $cnt;
   

    echo json_encode($Tower);
?>

