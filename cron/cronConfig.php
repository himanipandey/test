<?php
$docroot = dirname(__FILE__) . "/../";

require_once $docroot.'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot.'includes/send_mail_amazon.php';

$dailyEmail = array(
	array(
		'sql'=>"SELECT 
					rp.PROJECT_ID, rp.PROJECT_NAME, rp.BUILDER_NAME, rp.PROJECT_URL, c.LABEL as CITY 
				FROM
				   (resi_project rp INNER JOIN city c 
			    ON 
					rp.CITY_ID = c.CITY_ID)
				LEFT JOIN
					audit a
				ON
				   rp.PROJECT_ID = a.PROJECT_ID
			    WHERE
					a.ACTION = 'insert'
			    AND
					a.TABLE_NAME = 'resi_project'
			    AND
				    DATE(a.ACTION_DATE) = DATE(subdate(current_date, 1))", 
		'subject'=>'PROJECTS LIST INSERTED TILL TODAY', 
		'recipients'=>array('vimlesh.rajput@proptiger.com'), 
		'attachmentname'=>'ProjectList', 
		'message'=>'This is a list of projects which are inserted till today.', 
		'sendifnodata'=> 0
	),
	
);
?>
