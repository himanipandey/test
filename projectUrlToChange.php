<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	date_default_timezone_set('Asia/Kolkata');
	include("builder_function.php"); 
	AdminAuthentication();	
	include("modelsConfig.php");


      if($txtBuilderUrlOld != $txtBuilderUrl){
                                    $qryPDetail = "select rp.project_id,rp.project_name,rp.project_url,l.label as localityName,c.label as cityName,rb.builder_name from ".RESI_PROJECT." rp
                                                    join resi_builder rb on rp.builder_id = rb.builder_id
                                                   join locality l on rp.locality_id = l.locality_id
                                                   join suburb s on l.suburb_id = s.suburb_id
                                                   join city c on s.city_id = c.city_id where rp.version = 'Cms'";
                                    $resPDetail = mysql_query($qryPDetail) or die(mysql_error());

echo "Following project url not matched";
                                    if(mysql_num_rows($resPDetail)>0){
                                        while($data = mysql_fetch_assoc($resPDetail)){
                                            $txtProjectURL = createProjectURL($data['cityName'], $data['localityName'], $data['builder_name'], $data['project_name'], $data['project_id']);
                                            
                                            if($data['localityName'] != $txtProjectURL){
                                                echo "Existing URL: ".$data['project_url'] ."<====>According to current format: ". $txtProjectURL."<br>";
                                            }
                                           // $updateQuery = "UPDATE ".RESI_PROJECT." set PROJECT_URL='".$txtProjectURL."' 
                                                //    where PROJECT_ID=".$data['project_id']." and version = 'Cms'";
                                            //$resUrl = mysql_query($updateQuery) or die(mysql_error());
                                        }
                                    }
                                }

	
	
?>

