<?php

$RowsPerPage = '30';
$PageNum = 1;
if (isset($_GET['page'])) {
    $PageNum = $_GET['page'];
}
$Offset = ($PageNum - 1) * $RowsPerPage;
$sqlStr = "SELECT * FROM proptiger.seo_meta_content_templates LIMIT $Offset, $RowsPerPage";
$sqlResouce = mysql_query($sqlStr) or die(mysql_error());
$tempaltes = array();
while ($data = mysql_fetch_array($sqlResouce)) {
    array_push($tempaltes, $data);
}
prd($tempaltes);