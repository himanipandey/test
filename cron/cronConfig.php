<?php
$docroot = dirname(__FILE__) . "/../";

require_once $docroot.'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot.'includes/send_mail_amazon.php';

$latLongList = '0,1,2,3,4,5,6,7,8,9';
$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-1,date("Y")));
$dailyEmail = array(
	array(
		'sql'=>"SELECT 
                            rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, rp.PROJECT_URL, c.LABEL as CITY 
                         FROM
                            resi_project rp inner join locality l on rp.locality_id = l.locality_id
                             left join suburb s on l.suburb_id = s.suburb_id
                             left join city c on s.city_id = c.city_id 
                             inner join resi_builder rb on rp.builder_id = rb.builder_id
                        WHERE
                            DATE(rp.created_at) = DATE(subdate(current_date, 1))
                            and rp.version = 'Cms'", 
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
            'sql'=>"select cd.CallId, cd.CallStatus, cd.ApiResponse, cd.CallbackJson, cd.CreationTime, pa.USERNAME, GROUP_CONCAT(cp.ProjectId) PROJECTS from CallDetails cd left join CallProject cp on cd.CallId=cp.CallId inner join proptiger_admin pa on cd.AgentId = pa.ADMINID where ApiResponse = 'queued successfully' and (CallbackJson is null or CallbackJson = '') and Date(CreationTime) = Date(DATE_SUB(NOW(), INTERVAL 1 DAY)) group by cd.CallId;",
            'subject'=>'Calls With No Response',
            'recipients'=>array('ankur.dhawan@proptiger.com','ravi.srivastava@proptiger.com', 'azitabh.ajit@proptiger.com'), 
            'attachmentname'=>'missing_data',
            'sendifnodata'=>0
        ),
        array(
            'sql'=>"select l.LABEL as LOCALITY_NAME,l.LOCALITY_ID, rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, l.MIN_LATITUDE, l.MAX_LATITUDE, l.MIN_LONGITUDE, l.MAX_LONGITUDE,rp.LATITUDE, rp.LONGITUDE, city.LABEL as CITY_NAME 
            from locality l inner join resi_project rp 
            on l.LOCALITY_ID = rp.LOCALITY_ID
            inner join suburb s on l.suburb_id = s.suburb_id
            inner join city on s.city_id = city.city_id
            inner join resi_builder rb on rp.builder_id = rb.builder_id
             where 
            l.IS_GEO_BOUNDARY_CLEAN = 'true'
            and rp.version = 'Cms'
            and ((rp.LONGITUDE not between l.MIN_LONGITUDE and l.MAX_LONGITUDE) or (rp.LATITUDE not between l.MIN_LATITUDE and l.MAX_LATITUDE))
             and (rp.LATITUDE not in($latLongList) or rp.LONGITUDE not in($latLongList));",
            'subject'=>'Lat Long Beyond Limits',
            'recipients'=>array('ankur.dhawan@proptiger.com'), 
            'attachmentname'=>'Latitude_longitude_beyond_limit',
            'sendifnodata'=>0
        ),
        array(
            "sql"=>"select rp.project_name as PROJECT_NAME, rp.PROJECT_ID, rb.BUILDER_NAME,l.LABEL as LOCALITY
                ,c.LABEL as CITY,pa.fname as UPDATED_BY,
             CASE
                WHEN vl.table_name = 'resi_project' THEN 'PROJECT' 
                WHEN vl.table_name = 'resi_builder' THEN 'BUILDER'
                WHEN vl.table_name = 'locality' THEN 'LOCALITY'
                WHEN vl.table_name = 'city' THEN 'CITY'
                else
                    vl.table_name
                END AS data,vl.category,vl.video_url
            from    resi_project rp 
             inner join locality l
            on  rp.LOCALITY_ID = l.LOCALITY_ID
            inner join suburb s on l.suburb_id = s.suburb_id
            inner join city c on s.city_id = c.city_id
            inner join resi_builder rb on rp.builder_id = rb.builder_id
            inner join video_links vl on rp.project_id = vl.table_id
            inner join proptiger_admin pa on vl.updated_by = pa.adminid
             where 
            rp.version = 'Cms'
            and vl.updated_at >'".$yesterday."';",
            "subject"=>"New Videos added",
            'recipients'=>array('ankur.dhawan@proptiger.com','prasha.agarwal@proptiger.com','karanvir.singh@proptiger.com'),
            "attachmentname"=>"New Videos added",
            "sendifnodata"=>0
        )
);

 
?>
