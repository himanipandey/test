<?php
    $accessBank = '';
    if( $bankAuth == false )
       $accessBank = "No Access";
    $smarty->assign("accessBank",$accessBank);

    error_reporting(E_ALL & ~E_NOTICE);
    if ($_GET['mode'] == 'delete') 
    {

            DeleteBank($_GET['bank_id']);
            header("Location:bank_list.php?page=1&sort=all");
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
 $projecttower = array();
if ($_GET['sort'] == "1") {
    $QueryMember = "SELECT A.BANK_ID,A.BANK_NAME,A.BANK_LOGO,A.BANK_DETAIL,A.SERVICE_IMAGE_ID FROM ".BANK_LIST." AS A WHERE BANK_NAME BETWEEN '0' AND '9' ORDER BY A.BANK_ID DESC";
} else if ($_GET['sort'] == "all") {
    $QueryMember = "SELECT A.BANK_ID,A.BANK_NAME,A.BANK_LOGO,A.BANK_DETAIL,A.SERVICE_IMAGE_ID FROM ".BANK_LIST." AS A WHERE 1 ORDER BY A.BANK_NAME DESC";
} else {
    $QueryMember = "SELECT A.BANK_ID,A.BANK_NAME,A.BANK_LOGO,A.BANK_DETAIL,A.SERVICE_IMAGE_ID FROM ".BANK_LIST." AS A WHERE  left(BANK_NAME,1)='".$_GET['sort']."' ORDER BY A.BANK_ID DESC";
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
			array_push($projecttower, $dataArr2);
			
		 }
		
//Read image from image service
$objectType = "bank";
$img_path = array();
foreach ($projecttower as $k => $v) {
    $objectId = $v['BANK_ID'];
    $service_image_id = $v['SERVICE_IMAGE_ID'];
    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId."&service_image_id=".$service_image_id;
    $content = file_get_contents($url);
    $imgPath = json_decode($content);
    $data = array();
    foreach($imgPath->data as $k=>$v){
        $data[$k]['IMAGE_ID'] = $v->id;
        $data[$k][$obj] = $v->objectId;
        $data[$k]['priority'] = $v->priority;
        $data[$k]['IMAGE_CATEGORY'] = $v->imageType->type;
        $data[$k]['IMAGE_DISPLAY_NAME'] = $v->title;
        $data[$k]['IMAGE_DESCRIPTION'] = $v->description;
        $data[$k]['SERVICE_IMAGE_ID'] = $v->id;
        $data[$k]['SERVICE_IMAGE_PATH'] = $v->absolutePath;
    }
    array_push($img_path, $data[0]['SERVICE_IMAGE_PATH']);
    //$img_path = $data[0]['SERVICE_IMAGE_PATH'];

}

$smarty->assign("projecttower", $projecttower);
$smarty->assign("image_path", $img_path);
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