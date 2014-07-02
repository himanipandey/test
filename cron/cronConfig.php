<?php
$docroot = dirname(__FILE__) . "/../";

require_once $docroot.'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot.'includes/send_mail_amazon.php';

$past_date = date("Y-m-d",strtotime('-1 days'));
$future_date = date("Y-m-d",strtotime('+1 days'));
$latLongList = '0,1,2,3,4,5,6,7,8,9';
$currentDate = date("Y-m-d");
$cityList = "20,11,6,8,88,2,5,12,1,21,18,16";
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
            and rp.version = 'Cms' and rp.status in('Active','ActiveInCms')
            and ((rp.LONGITUDE not between l.MIN_LONGITUDE and l.MAX_LONGITUDE) or (rp.LATITUDE not between l.MIN_LATITUDE and l.MAX_LATITUDE))
             and (rp.LATITUDE not in($latLongList) or rp.LONGITUDE not in($latLongList));",
            'subject'=>'Lat Long Beyond Limits',
            'recipients'=>array('ankur.dhawan@proptiger.com'), 
            'attachmentname'=>'Latitude_longitude_beyond_limit',
            'sendifnodata'=>0
        ),
         array(
            'sql'=>"UPDATE `project_offers` SET STATUS = 'Inactive' WHERE STATUS = 'Active' AND OFFER_END_DATE='".$past_date ."';",
            'subject'=>'Expired Project Offers',
            'recipients'=>array('ankur.dhawan@proptiger.com'), 
            'attachmentname'=>'expired_project_offers',
            'sendifnodata'=>0
        ),
        array(
            'sql'=>"SELECT pof.id as OFFER_ID,pof.project_id as PROJECT_ID,pof.OFFER,pof.OFFER_DESC,pof.created_at as START_DATE,pof.OFFER_END_DATE,pof.STATUS FROM `project_offers` pof inner join resi_project rp on rp.project_id = pof.project_id and rp.version='Cms' and rp.status in('Active','ActiveInCms') WHERE pof.STATUS = 'Active' AND pof.OFFER_END_DATE='".$future_date."';",
            'subject'=>'Project Offers Reaching Expiry Date',
            'recipients'=>array('ankur.dhawan@proptiger.com'), 
            'attachmentname'=>'expired_project_offers',
            'message'=>"Please extend the offers validity otherwise they will be deactivated.",
            'sendifnodata'=>0
        )
);

$weeklyEmail = array(
     array(
            'sql'=>"select rp.PROJECT_ID, rp.PROJECT_NAME from resi_project rp 
             where 
                rp.version = 'Cms'
                and rp.status in('Active','ActiveInCms') and project_status_id = 2;",
                'subject'=>'Cancelled projects but not yet marked inactive',
                'recipients'=>array('ankur.dhawan@proptiger.com'), 
                'attachmentname'=>'Cancelled_projecst_but_not_yet_marked_inactive',
                'sendifnodata'=>0
        ),
     array(
            'sql'=>"select rp.PROJECT_ID, rp.PROJECT_NAME,rp.promised_completion_date as COMPLETION_DATE from resi_project rp 
             where 
                rp.version = 'Cms' and rp.status in('Active','ActiveInCms') and project_status_id in(8,1,7) and promised_completion_date < '$currentDate'
                    and promised_completion_date != '0000-00-00' and promised_completion_date is not null;",
               'subject'=>'Projects whose status is Pre Launch,Under construction,Launch projects but Expected completion date is in past',
               'recipients'=>array('ankur.dhawan@proptiger.com'), 
               'attachmentname'=>'projects_whose_status_is_pre_launch_under_construction_launch_projects_but_expected_completion_date_is_in_past',
               'sendifnodata'=>0
        ),
     array(
            'sql'=>"select rp.project_id,rp.project_name,l.label as LOCALITY_NAME, c.label as CITY_NAME,((ACOS(SIN(rp.latitude * PI() / 180) * SIN(l.latitude * PI() / 180) + COS(rp.latitude * PI() / 180) 
                * COS(l.latitude * PI() / 180) * COS((rp.longitude - l.longitude) * PI() / 180)) * 180 
                 / PI()) * 60 * 1.1515)*1.609344 AS `distance`               
 
                from resi_project rp join locality l on rp.locality_id = l.locality_id 
                join suburb s on l.suburb_id = s.suburb_id
                join city c on s.city_id = c.city_id
             where 
                rp.version = 'Cms' and rp.status in('Active','ActiveInCms') and c.city_id in($cityList) having distance >10 ;",
               'subject'=>'PIDs greater than 10km from locality center',
               'recipients'=>array('ankur.dhawan@proptiger.com'), 
               'attachmentname'=>'PIDs_greater_than_10km_from_locality_center',
               'sendifnodata'=>0
        )
);
?>
