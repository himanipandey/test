<?php
/*
$blkUSrArr['SM']=array();
$qryGetModule	=	"SELECT A.ID,A.MODULE,A.LINK_URL,A.TITLE,A.HEADING_DESC,A.STATUS,A.PARENT,B.MODULE_ID  FROM ".MODULES." A INNER JOIN ".MODULE_ACCESS." B  ON (A.ID = B.MODULE_ID) WHERE B.ACCESS_LEVEL = '".$_SESSION['ACCESS_LEVEL']."' AND A.PARENT='0' AND PAGE_STATUS='1' AND DELETED_FLAG=0 ORDER BY A.DISPLAY_ORDER";
$resultMod	=	mysql_query($qryGetModule);

while($allDataMod = mysql_fetch_assoc($resultMod)){
	$resultSetMod[$allDataMod['ID']] = $allDataMod;
}

$smarty->assign("resultSetMod", $resultSetMod);
$arrModu_Submod = array(); 

foreach($resultSetMod as $module=>$innerArr) {
	$qryGetModule1	=	"SELECT ID  FROM ".MODULES." WHERE  PARENT='".$module."'";
	$resultMod1	=	mysql_query($qryGetModule1);
	while($allDataMod1 = mysql_fetch_assoc($resultMod1)){
		$arrModu_Submod[$module][] = $allDataMod1['ID'];
	}
}

$arrCnt = array();
foreach($arrModu_Submod as $mod=>$arr){
	$arrCnt[$mod] = count($arrModu_Submod[$mod]);
}
$smarty->assign("main_count", $arrCnt);

//get the submodules

$qryGetSubModule =	"SELECT A.ID,A.MODULE,A.LINK_URL,A.TITLE,A.HEADING_DESC,A.IMAGE_NAME,A.STATUS,A.PARENT,B.MODULE_ID  FROM ".MODULES." A INNER JOIN ".MODULE_ACCESS." B  ON (A.ID = B.MODULE_ID) WHERE B.ACCESS_LEVEL = '".$_SESSION['ACCESS_LEVEL']."' AND  A.PARENT!= 0 AND PAGE_STATUS='1' AND DELETED_FLAG= 0";
$resultSubMod	=	mysql_query($qryGetSubModule);
while($allDataSubMod = mysql_fetch_assoc($resultSubMod)){
	$resultSetSubMod[] = $allDataSubMod;
}

$smarty->assign("resultSetSubMod", $resultSetSubMod);
*/
/*******************************************query for block users************************************/
/*
$qryForBlkUSr = "SELECT ID,ADMINID ,MODULES_ID,SUBMODULE_ID FROM ".PROPTIGER_MODULES_BLOCK." WHERE ADMINID ='".$_SESSION['adminId']."'";
$execBlkQry = mysql_query($qryForBlkUSr);
$blkUSrArrIs = array();
while($resultBlk = mysql_fetch_assoc($execBlkQry)){
	
	$blkUSrArrIs[$resultBlk['MODULES_ID']][] = $resultBlk['SUBMODULE_ID'];
	$blkUSrArr['SM'][] = $resultBlk['SUBMODULE_ID'];
}

$arrSub = array();
foreach($blkUSrArrIs as $mod=>$arr) {
	$arrSub[$mod] = count($blkUSrArrIs[$mod]);
}

$adminuserName=base64_encode($_SESSION['AdminUserName']);
$email=$_SESSION['ADMINEMAIL'];

$smarty->assign("adminuserName", $adminuserName);
$smarty->assign("email", $email);
$smarty->assign("block_count", $arrSub);
$smarty->assign("blkUSrArr", $blkUSrArr);*/

?>