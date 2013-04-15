<?php
$docroot = dirname(__FILE__) . "/../";

require_once $docroot.'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot.'includes/send_mail_amazon.php';

$dailyEmail = array(
	array(
		'sql'=>'select rp.PROJECT_ID, rpo.OPTIONS_ID, rpo.BEDROOMS, rpo.BATHROOMS, rpo.UNIT_TYPE, round(rpo.SIZE*rpo.PRICE_PER_UNIT_AREA, 2) as PRICE, round(mean.MEAN_PRICE, 2) as AVG_LOC_PRICE, round((rpo.SIZE*rpo.PRICE_PER_UNIT_AREA-mean.MEAN_PRICE)*100/(mean.MEAN_PRICE),2) as DEVIATION from resi_project rp inner join resi_project_options rpo on rp.PROJECT_ID = rpo.PROJECT_ID inner join (select rp.LOCALITY_ID, rpo.UNIT_TYPE, avg(rpo.SIZE*rpo.PRICE_PER_UNIT_AREA) as MEAN_PRICE from resi_project rp inner join resi_project_options rpo on rp.PROJECT_ID = rpo.PROJECT_ID where rpo.SIZE*rpo.PRICE_PER_UNIT_AREA is not null and rpo.SIZE*rpo.PRICE_PER_UNIT_AREA != 0 group by rp.LOCALITY_ID, rpo.UNIT_TYPE) mean on rp.LOCALITY_ID=mean.LOCALITY_ID and rpo.UNIT_TYPE=mean.UNIT_TYPE where ABS((rpo.SIZE*rpo.PRICE_PER_UNIT_AREA-mean.MEAN_PRICE)/(mean.MEAN_PRICE))>0.3 and rpo.SIZE*rpo.PRICE_PER_UNIT_AREA is not null and rpo.SIZE*rpo.PRICE_PER_UNIT_AREA != 0;', 
		'subject'=>'PROJECTS WITH ABNORMAL PRICE', 
		'recipients'=>array(''), 
		'attachmentname'=>'report', 
		'message'=>'This is a list of projects having rates per unit area significantly different than the man value in that locality.', 
		'sendifnodata'=> 0
	)
);
?>