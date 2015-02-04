<?php
// Bucket Name

require_once dirname(__FILE__)."/s3/aws_file_manager.php";
require_once dirname(__FILE__)."/image_upload.php";

$bucket="testing-proptiger";

$s3 = AwsFileManager::get_s3_object('AKIAIERS5YQ2JMRPGGQA', '+HyVEmVlBzx0IQYLfYTKFa32K7FeaiaZ/rrHqpFn');
?>
