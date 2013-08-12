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
		'subject'=>'Projects inserted yesterday', 
		'recipients'=>array('ankur.dhawan@proptiger.com','chandan.singh@proptiger.com'), 
		'attachmentname'=>'projects', 
		'message'=>'', 
		'sendifnodata'=> 0
	),
	
        array(
		'sql'=>"select 
                            from_url,to_url,MODIFIIED_DATE
                        FROM
                            redirect_url_map a
                        WHERE
                            DATE(a.MODIFIIED_DATE) = DATE(subdate(current_date, 1))", 
		'subject'=>'Redirections inserted yesterday', 
		'recipients'=>array('ankur.dhawan@proptiger.com','chandan.singh@proptiger.com'), 
		'attachmentname'=>'redirections', 
		'message'=>'', 
		'sendifnodata'=> 0
	),
        array(
            'sql'=>"select cd.CallId, cd.CallStatus, cd.ApiResponse, cd.AudioLink, cd.CreationTime, pa.USERNAME, GROUP_CONCAT(cp.ProjectId) PROJECTS from CallDetails cd left join CallProject cp on cd.CallId=cp.CallId inner join proptiger_admin pa on cd.AgentId = pa.ADMINID where ApiResponse = 'queued successfully' and (AudioLink is null or AudioLink = '') and TIME_TO_SEC(TIMEDIFF(NOW(), CreationTime))>300 and CreationTime > DATE_SUB(NOW(), INTERVAL 1 DAY) group by cd.CallId;",
            'subject'=>'Calls With No Response',
            'recipients'=>array('ankur.dhawan@proptiger.com','ravi.srivastava@proptiger.com'), 
            'attachmentname'=>'missing_data',
            'sendifnodata'=>0
        )
);
?>
