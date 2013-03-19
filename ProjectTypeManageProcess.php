<?php

	
	$smarty->assign("sort",$_GET['sort']);
	$smarty->assign("page",$_GET['page']);
if ($_GET['mode'] == 'delete') 
{
    DeleteCrawlerProjectType($_GET['projecttypeid']);
	header("Location:ProjectTypeList.php?page=1&sort=all");
}
/***********************************************************/
/**
 * *********************************
 *  Get Sort And Page From  the URL
 * *********************************
 **/
if(isset($_GET['page'])) {
    $Page = $_GET['page'];
} else {
    $Page = 1;
}
$RowsPerPage = DEF_PAGE_SIZE;
$PageNum = 1;
if(isset($_GET['page'])) {
    $PageNum = $_GET['page'];
}
$Offset = ($PageNum - 1) * $RowsPerPage;
/**
 * *************************
 *  Create Sort For Pagging
 * *************************
 **/
 
$Self = $_SERVER['PHP_SELF'];
$NumberOnly = '[0-9]';
$Sorting .= "<a href=\"$Self?page=1&sort=1\">" . $NumberOnly . "</a>";
$LetterLinks = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
for ($i = 0; $i < count($LetterLinks); $i++) {
    $Sorting .= "&nbsp;";
    $Sorting .= "<a href=\"$Self?page=1&sort=".$LetterLinks[$i]."\">".$LetterLinks[$i]."</a>";
}
$Sorting .= "&nbsp;&nbsp;&nbsp;";
$Sorting .= "<a href=\"$Self?page=1&sort=all\">View All</a>";
/**
 * *******************
 *  Query For Pagging
 * *******************
 **/
 $projecttypeDataArr = array();
if ($_GET['sort'] == "1") {
    $QueryMember = "Select A.PROPERTY_ID, B.ID AS PID, A.ID,A.UNIT_NAME,A.TYPE,A.SIZE,A.MEASURE,A.PRICE,B.PROPERTY_NAME,B.FOLDER_NAME  FROM ".CRAWLER_PROJECT_TYPES." AS A, ".CRAWLER_PROJECT." AS B ,".PROJECT." AS MP WHERE PROPERTY_NAME BETWEEN '0' AND '9' AND A.PROPERTY_ID = B.ID AND B.PROPERTY_TYPE_INSERT_STATUS=0 AND MP.PROJECT_ID =B.PROPTIGER_PROJECT_ID 	 ORDER BY A.PROPERTY_ID DESC";
} else if ($_GET['sort'] == "all") {
   $QueryMember = "Select  A.PROPERTY_ID, B.ID AS PID, A.ID,A.UNIT_NAME,A.TYPE,A.SIZE,A.MEASURE,A.PRICE,B.PROPERTY_NAME,B.FOLDER_NAME  FROM ".CRAWLER_PROJECT_TYPES." AS A, ".CRAWLER_PROJECT." AS B ,".PROJECT." AS MP WHERE A.PROPERTY_ID = B.ID AND B.PROPERTY_TYPE_INSERT_STATUS=0  AND MP.PROJECT_ID =B.PROPTIGER_PROJECT_ID  ORDER BY A.PROPERTY_ID DESC";
} else {
    $QueryMember = "Select  A.PROPERTY_ID, B.ID AS PID, A.ID,A.UNIT_NAME,A.TYPE,A.SIZE,A.MEASURE,A.PRICE,B.PROPERTY_NAME,B.FOLDER_NAME  FROM ".CRAWLER_PROJECT_TYPES." AS A, ".CRAWLER_PROJECT." AS B ,".PROJECT." AS MP WHERE  left(PROPERTY_NAME,1)='".$_GET['sort']."' AND A.PROPERTY_ID = B.ID AND B.PROPERTY_TYPE_INSERT_STATUS=0  AND MP.PROJECT_ID =B.PROPTIGER_PROJECT_ID  ORDER BY A.PROPERTY_ID DESC";
}


$QueryExecute 	= mysql_query($QueryMember) or die(mysql_error());
$NumRows 		= mysql_num_rows($QueryExecute);
$smarty->assign("NumRows",$NumRows);
	
/**
 * *********************************
 *  Create Next and Previous Button
 * *********************************
 **/
$PagingQuery = "LIMIT $Offset, $RowsPerPage";
$QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;

while ($dataArr2 = mysql_fetch_array($QueryExecute_1))
		 {
			array_push($projecttypeDataArr, $dataArr2);
			
		 }
		
		$smarty->assign("projecttypeDataArr", $projecttypeDataArr);
$MaxPage = (ceil($NumRows/$RowsPerPage))?ceil($NumRows/$RowsPerPage):'1' ;
$Num = $_GET['num'];
$Sort = $_GET['sort'];
if ($PageNum > 1) {
    $Page = $PageNum - 1;
    $Prev = " <a href=\"$Self?page=$Page&sort=$Sort\">[Prev]</a> ";
    $First = " <a href=\"$Self?page=1&sort=$Sort\">[First Page]</a> ";
} else {
    $Prev  = ' [Prev] ';
    $First = ' [First Page] ';
}
if ($PageNum < $MaxPage) {
    $Page = $PageNum + 1;
    $Next = " <a href=\"$Self?page=$Page&sort=$Sort\">[Next]</a> ";
    $Last = " <a href=\"$Self?page=$MaxPage&sort=$Sort\">[Last Page]</a> ";
} else {
    $Next = ' [Next] ';
    $Last = ' [Last Page] ';
}
$Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";

$smarty->assign("Pagginnation", $Pagginnation);


$smarty->assign("Sorting", $Sorting);
?>