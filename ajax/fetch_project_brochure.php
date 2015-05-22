<?php

    include("../smartyConfig.php");
    include("../appWideConfig.php");
    include("../dbConfig.php");
    include("../modelsConfig.php");
    include("../includes/configs/configs.php");
    include("../imageService/image_upload.php");    
    
    $projectId = $_POST['objectId'];
   
    $url = ImageServiceUpload::$doc_upload_url."?objectType=project&objectId=".$projectId."&documentType=projectBrouchure";
    $content = file_get_contents($url);
    $imgPath = json_decode($content);
    
    if($imgPath->data)    
        echo $imgPath->data[0]->absoluteUrl;
    else
        echo "Empty";

?>