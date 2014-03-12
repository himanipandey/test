<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$docroot = dirname(__FILE__) . "/../";
set_time_limit(0);
$watermark_path = "images/pt_shadow1.png";
ini_set("memory_limit","256M");
include("SimpleImage.php");
include("watermark_image.class.php");
include("../smartyConfig.php");
require_once $docroot.'dbConfig.php';
include("../appWideConfig.php");
include("../includes/configs/configs.php");

$qry = "SELECT builder_name,entity,builder_id,builder_image,service_image_id FROM cms.resi_builder where created_at > '2013-11-14 00:00:00' 
        and builder_id = 105546 limit 10";
$res = mysql_query($qry) or die(mysql_error());
$arrBuilder = array();
while ($data = mysql_fetch_assoc($res)) {
        $exp = explode("/",$data['builder_image']);
        if($exp[1] == '') {
            $builderFoldername	= str_replace(' ','-',strtolower($data['entity']));
           $createFolder = $newImagePath.$builderFoldername;
            if(!is_dir($createFolder))
             mkdir($createFolder, 0777);
            $builderToSearch = explode(".",str_replace("//","",$data['builder_image']));
            if($builderToSearch[0] != '') {
    /*************start builder folder create *******************/
                mysql_close();
                proptigerDbConnect();
                $qryServ = "select * from proptiger.image where id = ".$data['service_image_id'];
                $resServ = mysql_query($qryServ) or die(mysql_error());
                $dataServ = mysql_fetch_assoc($resServ);
                echo "<pre>";
                print_r($dataServ);
                $toSearch = $newImagePath.$builderToSearch[0];

                   $qryUpdate = "update resi_builder set builder_image = '".$finalPath."' 
                                 where builder_id = ".$data['builder_id'];    
                  // mysql_query($qryUpdate);
           //  unlink($from);
           die;
    /*************end builder folder create *******************/
    /*************project folder cut and paste in builder folder*******************/
             $qryProj = "select project_id,project_name,project_small_image from resi_project
                           where builder_id = '".$data['builder_id']."' and version = 'Cms'";
                $resProj = mysql_query($qryProj) or die(mysql_error());
                while($projData = mysql_fetch_assoc($resProj)) {
                    $projFolderName = strtolower(str_replace(' ','-',$projData['project_name']));
                    $path = $newImagePath.$builderFoldername."/".$projFolderName;
                    mkdir($path,0777);
                    $sourceFolder = $newImagePath.$projFolderName;
                    recurse_copy($sourceFolder,$path);
               
                $projectFromImg = '//'.$projFolderName;
                $projectToImg = '/'.$builderFoldername.'/'.$projFolderName;
                $finalProjectImg = str_replace($projectFromImg,$projectToImg,$projData['project_small_image']);
                $qryProjectImgUpdate = "update resi_project set project_small_image = '".$finalProjectImg."'
                                                  where project_id = '".$projData['project_id']."'";
                mysql_query($qryProjectImgUpdate) or die(mysql_error());
               
             /****code for update project plan image***/
               $qryPlanImg = "select * from project_plan_images where project_id = ".$projData['project_id'];
                $resPlanimg = mysql_query($qryPlanImg);
                while($dataPlan = mysql_fetch_assoc($resPlanimg)) {
                    $currentPlanPath = $dataPlan['PLAN_IMAGE'];
                    $toReplace = "/".$builderFoldername."/";
                    $updatedPath = str_replace("//",$toReplace,$currentPlanPath);
                   $qryUpdatePlan = "update project_plan_images set plan_image = '".$updatedPath."'
                                      where project_plan_id = ".$dataPlan['PROJECT_PLAN_ID']."";
                    mysql_query($qryUpdatePlan);
                    
                }
            /***end ********/
                /**********code for update floor plan************/
                $qryFloorPlanImg = "select f.floor_plan_id,f.image_url from resi_floor_plans f
                                    join resi_project_options opt on f.option_id = opt.options_id
                                    where opt.project_id = ".$projData['project_id'];
                $resFloorPlanImg = mysql_query($qryFloorPlanImg);
                while($dataFloorPlan = mysql_fetch_assoc($resFloorPlanImg)) {
                    $currentFloorPlanPath = $dataFloorPlan['image_url'];
                    $toReplace = "/".$builderFoldername."/";
                    $updatedFloorPath = str_replace("//",$toReplace,$currentFloorPlanPath);
                    $qryUpdateFloorPlan = "update resi_floor_plans set IMAGE_URL = '".$updatedFloorPath."'
                                      where FLOOR_PLAN_ID = ".$dataFloorPlan['floor_plan_id']."";
                    mysql_query($qryUpdateFloorPlan);
                }
              }
            }
        }
}

/**
 * Copy a file, or recursively copy a folder and its contents
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       string   $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function recurse_copy($src,$dst) { 
    str_replace("//","/",$dst);
    $files = scandir($src);
    foreach($files as $value) {
        if(strlen($value)>4) {
            $sourcePath = $src."/".$value;
            $destpath = $dst."/".$value;
            $copy = copy($sourcePath,$destpath);
            chmod($destpath, 0777);
            unlink($sourcePath);
        }
       
    }
    rmdir($src);
} 
function proptigerDbConnect() {
    mysql_connect("localhost",'root','root') or die(mysql_error());
    mysql_select_db('cms') or die(mysql_error());
}
?>
