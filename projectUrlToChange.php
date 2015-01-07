<?php

	error_reporting(1);
ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
        include("modelsConfig.php"); 
	AdminAuthentication();
        include("function/projectPhase.php");

    $qryPDetail = "select rp.project_id,rp.project_name,rp.project_url,l.label as localityName,c.label as cityName,rb.builder_name from resi_project rp
                    join resi_builder rb on rp.builder_id = rb.builder_id
                   join locality l on rp.locality_id = l.locality_id
                   join suburb s on l.suburb_id = s.suburb_id
                   join city c on s.city_id = c.city_id where rp.version = 'Cms' limit 1000";
    $resPDetail = mysql_query($qryPDetail) or die(mysql_error());

echo "Following project url not matched";

    if(mysql_num_rows($resPDetail)>0){
//echo "iiner";

        while($data = mysql_fetch_assoc($resPDetail)){
//echo "<pre>"; print_r($data);
            $txtProjectURL = createProjectURLOneTime($data['cityName'], $data['localityName'], $data['builder_name'], $data['project_name'], $data['project_id']);
            
            if($data['localityName'] != $txtProjectURL){
                echo "Existing URL: ".$data['project_url'] ."<====>According to current format: ". $txtProjectURL."<br>";
//die("inner");
            }
           // $updateQuery = "UPDATE ".RESI_PROJECT." set PROJECT_URL='".$txtProjectURL."' 
                //    where PROJECT_ID=".$data['project_id']." and version = 'Cms'";
            //$resUrl = mysql_query($updateQuery) or die(mysql_error());
        }
    }
	
function createProjectURLOneTime($city, $locality, $builderName, $projectName, $projectId){
    $city = trim(strtolower($city));
    $locality = trim(strtolower($locality));
    $builder = trim(strtolower($builderName));
    $project = trim(strtolower($projectName));
    $projectId = getIdByTypeOneTime($projectId, 'project');
    $projectURL = $city.'/'.$locality.'/'.$builder.'-'.$project.'-'.$projectId;
    $url = preg_replace( '/\s+/', '-', $projectURL);
    return $url;
}
function getIdByTypeOneTime( $id, $id_type ) {
    $txt = "";
    if ( !empty( $id_type ) && is_numeric( $id ) ) {
        $add_value = -1;
        switch( $id_type ) {
            case 'city':
                $add_value = 0;
                break;
            case 'suburb':
                $add_value = 0;
                break;
            case 'locality':
                $add_value = 0;
                break;
            case 'builder':
                $add_value = 0;
                break;
            case 'project':
                $add_value = 0;                                                                                                                  
                break;
            case 'property':
                $add_value = 0;
                break;
        }
        if ( $add_value != -1 ) {
            $txt = $add_value + ( int )$id;
            $txt = "$txt";
        }
    }
    return $txt;
}
?>

