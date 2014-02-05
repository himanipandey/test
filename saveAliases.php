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

if(!empty($_POST['aliasname']))
{
	$aliasName   = $_POST['aliasname'];
    
	 saveAliases($aliasName);


}
else echo "4";

?> 