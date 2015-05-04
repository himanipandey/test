<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$lot_id = $_POST['lot_id'];

$condition = '';
if($_POST['revertId']){
    $condition = " AND clc.content_lot_id = '".$_POST['revertId']."'";
}
if($_POST['role']){
    $condition .= " AND clc.status= 'active'";
}

$sqlRevertComments = mysql_query("SELECT clc.*, cld.entity_name, admin.fname created_by FROM content_lot_comments clc
                    INNER JOIN content_lot_details cld on cld.id = clc.content_lot_id
                    LEFT JOIN proptiger_admin admin on admin.adminid = clc.created_by
                    WHERE cld.lot_id = '$lot_id'".$condition." ORDER BY clc.id desc") or die(mysql_error());


if (mysql_num_rows($sqlRevertComments) > 0) {
    
    print "<table width=800>"
            . "<tr style='background:#ccc'><th colspan=5 text-align=left>Revert Comments (Lot #$lot_id)</th></tr>"
            . "<tr style='text-align:left'>"
            . "<th>SL</th>"
            . "<th>Article</th>"
            . "<th>Commented At</th>"
            . "<th>Comments</th>"
            . "<th>Commented By</th>"
            . "</tr>";
    $count = 1;
    while ($row = mysql_fetch_object($sqlRevertComments)) {

        print "<tr>"
                . "<td>".$count."</td>"
                . "<td>" . $row->entity_name . "</td>"
                . "<td>" . $row->created_at . "</td>"
                . "<td>" . $row->comment . "</td>"
                . "<td>" . $row->created_by . "</td>"
                . "</tr>";

        $count++;
    }
    print "</table>";
   
}
?>
