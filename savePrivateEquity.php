<?php

error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();
if($_POST['task']=="getProject"){
	$builder_id = $_POST['builder_id'];
	$projectData = array();
	$arrSearchFields = array();
	if( $builder_id != '' ){ 
	    $arrSearchFields['builder_id'] = $builder_id;
		$getSearchResult = ResiProject::getAllSearchResult($arrSearchFields);

		foreach ($getSearchResult as $k => $v) {
			$tmpArr = array();
			$tmpArr['id'] = $v->project_id;
			$tmpArr['name'] = $v->project_name.", ".$v->project_address;
			array_push($projectData, $tmpArr);
		}
		echo json_encode($projectData);
	}
}

if($_POST['task']=="save"){
	$deal_id = $_POST['deal_id'];
	$pe_id = $_POST['pe_id'];
	$pe_id_2 = $_POST['pe_id_2'];
	$pe_id_3 = $_POST['pe_id_3'];
	$type = $_POST['type'];
	$builder_id = $_POST['builderId'];
	
	$value = $_POST['value'];
	$article = $_POST['article'];
	$date = $_POST['date'];
	$extra = $_POST['extra'];
	$mode = $_POST['mode'];
	
	if($mode=="create"){
		$query = "INSERT INTO private_equity_deals (type, pe_id, pe_id_2, pe_id_3, builder_id, value, transaction_date, article_link, extra_values) values ('$type', '$pe_id', ".($pe_id_2!=''?$pe_id_2:"NULL").", ".($pe_id_3!=''?$pe_id_3:"NULL").", ".($builder_id!=''?$builder_id:"NULL").", '$value', '$date', '$article', '$extra')";
		
		$res = mysql_query($query);
        if(mysql_error())
            echo "3";
        else
            echo "1";
	}
	elseif($mode=="update" && $deal_id != 0 && $deal_id != 'NaN'){
		$query = "UPDATE private_equity_deals SET type = '$type', pe_id = '$pe_id', pe_id_2 = ".($pe_id_2!=''?$pe_id_2:"NULL").", pe_id_3 = ".($pe_id_3!=''?$pe_id_3:"NULL").", builder_id = ".($builder_id!=''?$builder_id:"NULL").", value = '$value', transaction_date = '$date', article_link = '$article', extra_values = '$extra'  WHERE id = '$deal_id'";
		
	    $res = mysql_query($query);
        if(mysql_error())
            echo "3";
        else
            echo "1";
	}
}


if($_POST['task']=="delete"){
	$id = $_POST['id'];
	
		$query = "DELETE FROM private_equity_deals WHERE id='{$id}'";
		$res = mysql_query($query);
        if(mysql_affected_rows()>0)
            echo "1";
        else
            echo "3".mysql_error();
	
}


?>
