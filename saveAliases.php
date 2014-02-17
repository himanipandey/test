<?php
//echo "hi";
//die(hello);
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/alias_functions.php");
AdminAuthentication();
// ajax call for attaching, removing and creating a tag
if($_POST['task']=='attachAlias'){
	//die("here");
	if(!empty($_POST['tableName']) && !empty($_POST['tableId']) && !empty($_POST['aliasTableName']) && !empty($_POST['aliasTableId']) ){
		//die("he");
		$tbname = $_POST['tableName'];
		$tbid = $_POST['tableId'];
		$altbname = $_POST['aliasTableName'];
		$altbid = $_POST['aliasTableId'];
		attachAliases($tbname, $tbid, $altbname, $altbid);
	}
}
else if($_POST['task']=='dettachAlias'){
	//die("here");
	if(!empty($_POST['tableName']) && !empty($_POST['tableId']) && !empty($_POST['aliasTableName']) && !empty($_POST['aliasTableId']) ){
		//die("he");
		$tbname = $_POST['tableName'];
		$tbid = $_POST['tableId'];
		$altbname = $_POST['aliasTableName'];
		$altbid = $_POST['aliasTableId'];
		dettachAliases($tbname, $tbid, $altbname, $altbid);
	}
}

else if($_POST['task']=='createAlias'){
	//die("here");
	if(!empty($_POST['tableName']) && !empty($_POST['tableId']) && !empty($_POST['aliasName']) ){
		//die("he");
		$tbname = $_POST['tableName'];
		$tbid = $_POST['tableId'];
		$altbname = $_POST['aliasName'];
		createAliases($tbname, $tbid, $altbname);
	}
}


else{
if(!empty($_POST['aliasname']))
{
	$aliasName   = $_POST['aliasname'];
    
	 saveAliases($aliasName);


}
else echo "4";
}

?> 