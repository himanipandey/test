<?php
// Bucket Name

require_once dirname(__FILE__)."/s3/aws_file_manager.php";
require_once dirname(__FILE__)."/image_upload.php";

$bucket="proptiger-img";

$s3 = AwsFileManager::get_s3_object('AKIAI5FTEFLES7UMOD4A', 'HMvOkDtE4OtZJFPkGozE7lEaFuKUWZbcjnNdWnSm');
?>