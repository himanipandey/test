<?php
/*
 * Wrapper around MySQL query execution
* 1. Provides exception support in case of errors
* 2. Provides consistency in error handling by e.g. printing standardized error messages
* 3. Times the queries, which helps in tracing slow queries.
*/
function dbQuery($sql)
{
    $result = array();
    $mysql_res = mysql_query($sql);

    if($mysql_res === FALSE)
    {
        die('Error Running Mysql Query');
    }
    while($row=  mysql_fetch_assoc($mysql_res)){
        $result[] = $row;
    }
    return $result;
}

function dbExecute($sql){
    $mysql_res = mysql_query($sql);

    if($mysql_res === FALSE)
    {
        die('Error Running Mysql Query');
    }
    return mysql_affected_rows();
}
?>
