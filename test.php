<?php

/**
 * @author AKhan
 * @copyright 2013
 */
//
//$dirPath = "new_images";
//if(is_dir($dirPath))
//    rmdir($dirPath);                
//$result = mkdir($dirPath );
//
//if ($result == 1) {
//    echo $dirPath . " has been created";
//    chmod($dirPath , 0777);
//} else {
//    echo $dirPath . " has NOT been created";
//}
//
//die;


include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("modelsConfig.php");
include("s3upload/s3_config.php");
include("SimpleImage.php");
$cityLocArr = CityLocationRel::CityLocArr();
$str = '';
$result = array();
foreach($cityLocArr as $key => $val)
{
    $str .= '"'.$val.'",';
    array_push($result , array("id" => $key , "value" => $val));
    
}

$str = trim($str , ",");
$result = json_encode($result);
//print'<pre>';
//print_r($result);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Autocomplete - Default functionality</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

 <style>
.ui-autocomplete {
max-height: 100px;
overflow-y: auto;
/* prevent horizontal scrollbar */
overflow-x: hidden;
}
/* IE 6 doesn't support max-height
* we use height instead, but this forces the menu to always be this tall
*/
* html .ui-autocomplete {
height: 100px;
}
</style>
<script>
$(function() {
var availableTags = <?=$result?>; 
//[
//"ActionScript",
//"AppleScript",
//"Asp",
//"BASIC",
//"C",
//"C++",
//"Clojure",
//"COBOL",
//"ColdFusion",
//"Erlang",
//"Fortran",
//"Groovy",
//"Haskell",
//"Java",
//"JavaScript",
//"Lisp",
//"Perl",
//"PHP",
//"Python",
//"Ruby",
//"Scala",
//"Scheme"
    
//];
$( "#tags" ).autocomplete({
        source: availableTags,
        select: function( event, ui ) {
            //log( ui.item ?
//            "Selected: " + ui.item.value + " aka " + ui.item.id :
//            "Nothing selected, input was " + this.value );
            alert(ui.item.value+ ' '+ ui.item.id )
        }
    });
});
</script>
</head>
<body>
<div class="ui-widget">
<label for="tags">Tags: </label>
<input id="tags">
</div>
</body>
</html>