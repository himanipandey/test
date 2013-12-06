<?php

/**
 * @author AKhan
 * @copyright 2013
 */

$dirPath = "new_images";
if(is_dir($dirPath))
    rmdir($dirPath);                
$result = mkdir($dirPath );

if ($result == 1) {
    echo $dirPath . " has been created";
    chmod($dirPath , 0777);
} else {
    echo $dirPath . " has NOT been created";
}

die;

?>