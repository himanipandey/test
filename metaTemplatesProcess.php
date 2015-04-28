<?php

$accessBuilder = '';
if ($builderAuth == false) {
    $accessBuilder = "No Access";
}
$smarty->assign("accessBuilder", $accessBuilder);
$rowsPerPage = '30';
$pageNum = 1;
if (isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}
$Offset = ($pageNum - 1) * $rowsPerPage;
$sqlStr = "SELECT * FROM proptiger.seo_meta_content_templates LIMIT $Offset, $rowsPerPage";
$sqlResouce = mysql_query($sqlStr) or die(mysql_error());
$tempaltes = array();
while ($data = mysql_fetch_array($sqlResouce)) {
    array_push($tempaltes, $data);
}
pagination();

function pagination() {
    global $rowsPerPage, $pageNum, $smarty;
    $sqlStr = "SELECT count(1) as count FROM proptiger.seo_meta_content_templates";
    $sqlResource = mysql_query($sqlStr) or die(mysql_error());
    $countResult = mysql_fetch_assoc($sqlResource);
    $numRows = $countResult["count"];
    $maxPage = (ceil($numRows / $rowsPerPage)) ? ceil($numRows / $rowsPerPage) : '1';
    $num = $_GET['num'];
    $sort = $_GET['sort'];
    if ($pageNum > 1) {
        $page = $pageNum - 1;
        $prev = " <a href=\"$Self?page=$page&sort=$sort\">[Prev]</a> ";
        $first = " <a href=\"$Self?page=1&sort=$sort\">[First Page]</a> ";
    } else {
        $prev = ' [Prev] ';
        $first = ' [First Page] ';
    }
    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $Next = " <a href=\"$Self?page=$page&sort=$sort\">[Next]</a> ";
        $Last = " <a href=\"$Self?page=$maxPage&sort=$sort\">[Last Page]</a> ";
    } else {
        $Next = ' [Next] ';
        $Last = ' [Last Page] ';
    }
    $pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $Next . $Last . "</font></DIV>";
    $smarty->assign("pagginnation", $pagginnation);
    $smarty->assign("pagginnation", $pagginnation);
    $smarty->assign("Sorting", $sorting);
    $smarty->assign("NumRows", $numRows);
}

function getTemplates() {
    
}
