<?php

	$ErrorMsg='';

	$projectId = $_REQUEST['projectId'];
	$projectDetail	= ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail);
    
   	$builderDetail = fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
	if(isset($_REQUEST['edit']))
		$edit_project = $projectId;
	else
		$edit_project = $projectId;
	$smarty->assign("edit_project", $edit_project);
	
	$smarty->assign("projectId", $projectId);
	
	$smarty->assign("param_edit",$_REQUEST['edit']);
	
if($_REQUEST['edit'] == 'edit' && !isset($_POST['page_stage'])){
	
	$video_id =  mysql_real_escape_string($_REQUEST['v']);
	$project_video = mysql_fetch_object(mysql_query("SELECT * FROM " . VIDEO_LINKS . " WHERE video_id='$video_id' and table_id='$projectId'")) or die(mysql_error());
	$smarty->assign("video_category", $project_video->category);
	$smarty->assign("video_url", $project_video->video_url);
	$smarty->assign("video_id", $project_video->video_id);
	
	//header("Location:project_video_add.php?projectId=$projectId&edit=add");
	
	
}elseif($_REQUEST['edit'] == 'delete'){
	
	$video_id =  mysql_real_escape_string($_REQUEST['v']);
	mysql_query("DELETE FROM " . VIDEO_LINKS . " WHERE video_id='$video_id' and table_id='$projectId'") or die(mysql_error());
	
	header("Location:project_video_add.php?projectId=$projectId&edit=add");
	
	
}elseif (isset($_POST['Next']))
{
	
	
	$folderName		=	$projectDetail[0]['PROJECT_NAME'];

	/***********Folder name**********/
	$builderNamebuild		=	explode("/",$builderDetail['BUILDER_IMAGE']);

	/********************************/
	$BuilderName		=	$builderNamebuild[1];
	$ProjectName		=	str_replace(" ","-",$projectDetail[0]['PROJECT_NAME']);

	$arrTitle = array();

	  	
		if( $projectId == '')
	    {
	      $ErrorMsg["projectId"] = "Please select Project name.";
	    }
        else if( $_REQUEST['PType'] == '')
	    {
	      $ErrorMsg["ptype"] = "Please select Video type.";
	    }else if( !array_filter($_REQUEST['Url']))
	    {
	      $ErrorMsg["ptype"] = "Please enter Video Url.";
	    }	  
	   //checking Url is empty
	    $count = 0;
	    $temp_arr = array();
		while($count < $_REQUEST['img']){
			
			if(trim($_REQUEST['Url'][$count]) == ''){
				$ErrorMsg["ptype"] = "Please enter Video Url."; break;
			}else{
				
				$ext_vlinks = checkDuplicateVideoLink($_REQUEST['Url'][$count]);
				
			  if(array_key_exists($_REQUEST['Url'][$count], $temp_arr)){
				  $ErrorMsg["ptype"] = "Duplicate Video Url not allowed."; break;				  
			  }else if($ext_vlinks){//checking duplicacy
			     if(!isset($_REQUEST['video_id'])){
					$ErrorMsg["ptype"] = "Video Url already exist"; 
					break;
				 }
				 elseif($_REQUEST['video_id'] && $ext_vlinks > 1){
					$ErrorMsg["ptype"] = "Video Url already exist"; break;
				 }
				 
			  }
			  $temp_arr[$_REQUEST['Url'][$count]] = $_REQUEST['Url'][$count];
			}
			$count++;
		}
         
        $smarty->assign("PType", $_REQUEST['PType']);
	if(is_array($ErrorMsg)) {
		$smarty->assign("ErrorMsg",$ErrorMsg);
	}
	else
	{
				
			$video_url =  trim(mysql_real_escape_string($_REQUEST['Url'][0]));
			$video_type =  mysql_real_escape_string($_REQUEST['PType']);
			
		if(isset($_POST['page_stage'])){
			
			$video_id = mysql_real_escape_string($_REQUEST['video_id']);
			
			mysql_query("UPDATE ". VIDEO_LINKS ." SET video_url='$video_url', category = '$video_type' WHERE video_id='$video_id ' AND table_id='$projectId'") or die(mysql_error());
		
			header("Location:project_video_add.php?projectId=$projectId&edit=add");
		}else{
			$count = 0;
			while($count < $_REQUEST['img']){
				$video_url =  trim(mysql_real_escape_string($_REQUEST['Url'][$count]));
				$video_type =  mysql_real_escape_string($_REQUEST['PType']);
				mysql_query("INSERT INTO " . VIDEO_LINKS . " (`video_id`, `table_id`, `table_name`, `category`, `video_url`) VALUES (NULL, '$projectId', 'resi_project', '".$video_type."', '".addslashes($video_url)."');") or die(mysql_error());
				$count++;
			}
		}
		
	}
}
else if(isset($_POST['exit']))
{
	
	if($_REQUEST['edit'] == 'edit')
		header("Location:project_video_add.php?projectId=$projectId&edit=add");
	else
		 header("Location:ProjectList.php?projectId=".$projectId);
}

$videoDetail = project_video_detail($projectId);
$smarty->assign("videoDetail", $videoDetail);

?>
