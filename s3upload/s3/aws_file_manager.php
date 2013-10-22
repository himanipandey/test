<?php
require_once dirname(__FILE__).'/aws-sdk-for-php/sdk.class.php';

class AwsFileManager
{
	public static function get_s3_object($key, $secret, $region = null)
	{
		$s3 = new AmazonS3(array("key"=>$key,"secret"=>$secret));
        if($region != null) $s3->set_region($region);
        $s3->use_ssl = 0;
        return $s3;
	}
}
?>