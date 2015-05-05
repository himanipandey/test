<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$lot_id = $_POST['lot_id'];

$condition = '';
if ($_POST['revertId']) {
    $condition = " AND clc.content_lot_id = '" . $_POST['revertId'] . "'";
}
if ($_POST['role']) {
    $condition .= " AND clc.status= 'active'";
}

$sqlRevertComments = mysql_query("SELECT clc.*,cld.entity_id,rp.project_name, rb.builder_name,
                    cl.lot_type,cl.lot_type, loc.label as locality, city.label as lot_city, 
                    admin.fname created_by FROM content_lot_comments clc
                    INNER JOIN content_lot_details cld on cld.id = clc.content_lot_id
                    LEFT JOIN content_lots cl on cld.lot_id = cl.id 
                    LEFT JOIN city city on city.city_id = cl.lot_city
                    LEFT JOIN resi_project rp on rp.project_id = cld.entity_id and cl.lot_type = 'project' 
                   LEFT JOIN resi_builder rb on (rp.builder_id = rb.builder_id or cld.entity_id = rb.builder_id)
                       and (cl.lot_type = 'project' OR cl.lot_type = 'builder') 
                   LEFT JOIN locality loc on (loc.locality_id = rp.locality_id or cld.entity_id = loc.locality_id )                   
                    LEFT JOIN proptiger_admin admin on admin.adminid = clc.created_by
                    
                    WHERE cld.lot_id = '$lot_id'" . $condition . ""
        . " GROUP BY clc.id ORDER BY clc.id desc") or die(mysql_error());


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
        
        if ($row->lot_type == 'project')
            $entity_name = $row->builder_name . " " . $row->project_name;
        elseif ($row->lot_type == 'locality')
            $entity_name = $row->locality;
        elseif ($row->lot_type == 'builder')
            $entity_name = $row->builder_name;
        elseif ($row->lot_type == 'city')
            $entity_name = $row->lot_city;

        print "<tr>"
                . "<td>" . $count . "</td>"
                . "<td>" . $entity_name . "</td>"
                . "<td>" . $row->created_at . "</td>"
                . "<td>" . $row->comment . "</td>"
                . "<td>" . $row->created_by . "</td>"
                . "</tr>";

        $count++;
    }
    print "</table>";
}else{
    
    print "No Comments Available!";
}
?>
