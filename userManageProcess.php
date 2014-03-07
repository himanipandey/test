<?php	

$accessUserManage = '';
if( $userManagement == false )
   $accessUserManage = "No Access";
$smarty->assign("accessUserManage",$accessUserManage);
$smarty->assign("sort",$_GET['sort']);
$smarty->assign("page",$_GET['page']);
if ($_GET['mode'] == 'delete') 
{
    
	DeleteAdminUser($_GET['userid'],'first');
	mysql_close();
	include("includes/configs/pt.in.db.php");
	DeleteAdminUser($_GET['userid'],'second');
	mysql_close();
	include("dbConfig.php");
}

if(isset($_GET['page'])) {
    $Page = $_GET['page'];
} else {
    $Page = 1;
}
$RowsPerPage = '30';
$PageNum = 1;
if(isset($_GET['page'])) {
    $PageNum = $_GET['page'];
}


if($_POST['search']!='' && ($_POST['name']!='' || $_POST['username']!='')){
	$Offset = 0;
	
}else{
	$Offset = ($PageNum - 1) * $RowsPerPage;
}

$userDataArr = array();
if($_POST['username']!='' || $_POST['name']!='' ||  $_POST['email']!='' || $_POST['mobile']!='' || $_POST['empid']!=''){
	$and ='';
	
	if($_POST['empid']!=''){
		$addQry.= $and." EMP_CODE ='".trim($_POST['empid'])."'";
		$and = ' AND';
	}

	if($_POST['name']!=''){
		$addQry.= $and." FNAME LIKE '%".trim($_POST['name'])."%'";
		$and = ' AND';
	}
	
	if($_POST['username']!=''){
		
		$addQry.= $and." USERNAME LIKE '%".trim($_POST['username'])."%'";
		$and = ' AND';
	}
	
	if($_POST['email']!=''){
		$addQry.= $and." ADMINEMAIL LIKE '%".trim($_POST['email'])."%'";
		$and = ' AND';
	}

	if($_POST['mobile']!=''){
		$addQry.= $and." MOBILE = '".trim($_POST['mobile'])."'";
		$and = ' AND'; 
	} 

}else{
	$addQry= " 1 ORDER BY FNAME";
}

$QueryMember = "SELECT * FROM ".ADMIN." WHERE ".$addQry."";
$QueryExecute = mysql_query($QueryMember) or die(mysql_error());
$NumRows = mysql_num_rows($QueryExecute);
$smarty->assign("NumRows",$NumRows);	
$PagingQuery = "LIMIT $Offset, $RowsPerPage";
$QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;
while ($dataArr2 = mysql_fetch_assoc($QueryExecute_1))	 {			
	array_push($userDataArr, $dataArr2);					
}

$smarty->assign("userDataArr", $userDataArr);

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
$Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";$smarty->assign("Pagginnation", $Pagginnation);$smarty->assign("Sorting", $Sorting);

$regionArray = array('N'=>'North','S'=>'South','E'=>'East','W'=>'West');
$smarty->assign("regionArray", $regionArray);
$smarty->assign("departmentArray", $departmentArray);
$smarty->assign('name',$_POST['name']);
$smarty->assign('username',$_POST['username']);
$smarty->assign('email',$_POST['email']);
$smarty->assign('empid',$_POST['empid']);
$smarty->assign('mobile',$_POST['mobile']);
?>