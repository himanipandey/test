<?php
$seoMetaAccess = "";
if(!$seoMetaAuth){
    $seoMetaAccess = "No Access";
}
$smarty->assign("seoMetaAccess",$seoMetaAccess);
if ($_REQUEST["operation"] == "edit") {
    // Update case
    if ($_POST) {
        if (saveData($_POST, true)) {
            $_SESSION["success_msg"] = "Data saved successfully";
            header("Location:meta_templates.php");
        } else {
            $smarty->assign("ErrorMsg", "Data could not saved, please try again");
        }
    }
    $template_name = $_REQUEST["name"];
    $result = getTemplateByName($template_name);
    $smarty->assign("result", $result);
} else {
    // listing case
    $rowsPerPage = '30';
    $pageNum = 1;
    if (isset($_GET['page'])) {
        $pageNum = $_GET['page'];
    }
    $templateList = getTemplateList();
    pagination();
    $smarty->assign("result", $templateList);
    $smarty->assign("rowsPerPage", $rowsPerPage);
    if ($_SESSION["success_msg"]) {
        $smarty->assign("success_msg", $_SESSION["success_msg"]);
        unset($_SESSION["success_msg"]);
    }
}

// function for listing and pagination
function pagination() {
    global $rowsPerPage, $pageNum, $smarty;
    $sqlStr = "SELECT count(1) as count FROM proptiger.seo_meta_content_templates";
    $sqlResource = mysql_query($sqlStr) or die(mysql_error());
    $countResult = mysql_fetch_assoc($sqlResource);
    $numRows = $countResult["count"];
    $maxPage = (ceil($numRows / $rowsPerPage)) ? ceil($numRows / $rowsPerPage) : '1';


    if ($pageNum > 1) {
        $page = $pageNum - 1;
        $prev = " <a href=\"$Self?page=$page\">[Prev]</a> ";
        $first = " <a href=\"$Self?page=1\">[First Page]</a> ";
    } else {
        $prev = ' [Prev] ';
        $first = ' [First Page] ';
    }
    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = " <a href=\"$Self?page=$page\">[Next]</a> ";
        $last = " <a href=\"$Self?page=$maxPage\">[Last Page]</a> ";
    } else {
        $next = ' [Next] ';
        $last = ' [Last Page] ';
    }
    $pagginnation = '<DIV align="left"><font style="font-size:11px; color:#000000;">' . $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $next . $last . "</font></DIV>";
    $smarty->assign("pagginnation", $pagginnation);
    $smarty->assign("numRows", $numRows);
    $smarty->assign("pageNum", $pageNum);
}

function getTemplateList() {
    global $rowsPerPage, $pageNum, $smarty;
    $Offset = ($pageNum - 1) * $rowsPerPage;
    $sqlStr = "SELECT * FROM proptiger.seo_meta_content_templates LIMIT $Offset, $rowsPerPage";
    $sqlResouce = mysql_query($sqlStr) or die(mysql_error());
    $tempaltes = array();
    while ($data = mysql_fetch_array($sqlResouce)) {
        array_push($tempaltes, $data);
    }
    return $tempaltes;
}

function getTemplateByName($name) {
    $sqlStr = "SELECT * FROM proptiger.seo_meta_content_templates WHERE template_name='{$name}'";
    $sqlResouce = mysql_query($sqlStr) or die(mysql_error());
    $tempaltes = array();
    if (mysql_num_rows($sqlResouce)) {
        $tempaltes = mysql_fetch_assoc($sqlResouce);
    }
    return $tempaltes;
}

// Save Data
function saveData($data, $update = false) {
    try {
        $returnVal = true;
        if ($update) {
            $sqlStr = "UPDATE proptiger.seo_meta_content_templates SET title='{$data["title"]}', description='{$data["description"]}', keywords='{$data["keywords"]}', h1='{$data["h1"]}', h2='{$data["h2"]}', h3='{$data["h3"]}', h4='{$data["h4"]}', others='{$data["others"]}' where template_name='{$data["template_name"]}'";
        }
        $sqlResouce = mysql_query($sqlStr) or ($returnVal = false);
        return $returnVal;
    } catch (Exception $ex) {
        return false;
    }
}
