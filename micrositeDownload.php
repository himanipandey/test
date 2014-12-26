<?php

/*
 * Created By Vimlesh Rajput on 26th Dec 2014
 */

 $file_url = 'microsite.json';
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
readfile($file_url); // do the double-download-dance (dirty but worky)
?>
