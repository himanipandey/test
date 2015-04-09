<?php

$docroot = dirname(__FILE__) . "/../";

require_once $docroot . 'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot . 'includes/send_mail_amazon.php';

$past_date = date("Y-m-d", strtotime('-1 days'));
$future_date = date("Y-m-d", strtotime('+1 days'));
$latLongList = '0,1,2,3,4,5,6,7,8,9';
$currentDate = date("Y-m-d");
$cityList = "20,11,6,8,88,2,5,12,1,21,18,16";
$dailyEmail = array(
    array(
        'sql' => "SELECT 
                            rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, rp.PROJECT_URL, c.LABEL as CITY 
                         FROM
                            resi_project rp inner join locality l on rp.locality_id = l.locality_id
                             left join suburb s on l.suburb_id = s.suburb_id
                             left join city c on s.city_id = c.city_id 
                             inner join resi_builder rb on rp.builder_id = rb.builder_id
                        WHERE
                            DATE(rp.created_at) = DATE(subdate(current_date, 1))
                            and rp.version = 'Cms'",
        'subject' => 'Projects inserted yesterday',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'pallavi.singh@proptiger.com', 'chandan.singh@proptiger.com'),
        'attachmentname' => 'projects',
        'message' => '',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select 
                            from_url,to_url,MODIFIIED_DATE
                        FROM
                            redirect_url_map a
                        WHERE
                            DATE(a.MODIFIIED_DATE) = DATE(subdate(current_date, 1))",
        'subject' => 'Redirections inserted yesterday',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'chandan.singh@proptiger.com'),
        'attachmentname' => 'redirections',
        'message' => '',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select cd.CallId, cd.CallStatus, cd.ApiResponse, cd.CallbackJson, cd.CreationTime, pa.USERNAME, GROUP_CONCAT(cp.ProjectId) PROJECTS from CallDetails cd left join CallProject cp on cd.CallId=cp.CallId inner join proptiger_admin pa on cd.AgentId = pa.ADMINID where ApiResponse = 'queued successfully' and (CallbackJson is null or CallbackJson = '') and Date(CreationTime) = Date(DATE_SUB(NOW(), INTERVAL 1 DAY)) group by cd.CallId;",
        'subject' => 'Calls With No Response',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'ravi.srivastava@proptiger.com', 'azitabh.ajit@proptiger.com'),
        'attachmentname' => 'missing_data',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select l.LABEL as LOCALITY_NAME,l.LOCALITY_ID, rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, l.MIN_LATITUDE, l.MAX_LATITUDE, l.MIN_LONGITUDE, l.MAX_LONGITUDE,rp.LATITUDE, rp.LONGITUDE, city.LABEL as CITY_NAME 
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
        'subject' => 'Lat Long Beyond Limits',
        'recipients' => array('cms-cron@proptiger.com', 'kapil.chadha@proptiger.com', 'ankur.dhawan@proptiger.com', 'Sandeep.jakhar@proptiger.com', 'Suneel.kumar@proptiger.com'),
        'attachmentname' => 'Latitude_longitude_beyond_limit',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "UPDATE `project_offers` SET STATUS = 'Inactive' WHERE STATUS = 'Active' AND OFFER_END_DATE='" . $past_date . "';",
        'subject' => 'Expired Project Offers',
        'recipients' => array('cms-cron@proptiger.com', 'kapil.chadha@proptiger.com', 'ankur.dhawan@proptiger.com'),
        'attachmentname' => 'expired_project_offers',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "SELECT pof.id as OFFER_ID,pof.project_id as PROJECT_ID,pof.OFFER,pof.OFFER_DESC,pof.created_at as START_DATE,pof.OFFER_END_DATE,pof.STATUS FROM `project_offers` pof inner join resi_project rp on rp.project_id = pof.project_id and rp.version='Cms' and rp.status in('Active','ActiveInCms') WHERE pof.STATUS = 'Active' AND pof.OFFER_END_DATE='" . $future_date . "';",
        'subject' => 'Project Offers Reaching Expiry Date',
        'recipients' => array('cms-cron@proptiger.com', 'kapil.chadha@proptiger.com', 'ankur.dhawan@proptiger.com', 'Sandeep.jakhar@proptiger.com', 'Suneel.kumar@proptiger.com', 'ravi.srivastava@proptiger.com'),
        'attachmentname' => 'expired_project_offers',
        'message' => "Please extend the offers validity otherwise they will be deactivated.",
        'sendifnodata' => 0
    ),
    array(
        'sql' => "SELECT ls.id Listing_ID, ls.created_at Created_Date, c.name Company, cu.name Seller, ph.PROJECT_ID Project_ID, po.options_id Option_id, po.option_name BHK ,tw.Tower_Name Tower, ls.floor Floor_NO, ls.flat_number Flat_NO, lp.price_per_unit_area UNIT_PRICE, lp.price ABS_PRICE, ms.direction facing 
                FROM listings ls 
                inner JOIN resi_project_phase ph ON ls.phase_id = ph.PHASE_ID AND ph.version='Cms' AND ph.STATUS='Active' and ls.status = 'Active' and ls.listing_category !='Primary'
                inner JOIN resi_project p ON ph.PROJECT_ID = p.PROJECT_ID  and p.version = 'Cms'
                inner JOIN listing_prices lp ON ls.current_price_id = lp.id 
                inner JOIN resi_project_options po ON ls.option_id = po.options_id and po.OPTION_CATEGORY !='logical'
                inner join 
(SELECT  ls.option_id Option_id,ls.tower_id Tower_id,  lp.price_per_unit_area UNIT_PRICE, lp.price ABS_PRICE, ls.facing_id, ls.floor 
                FROM listings ls 
                inner JOIN listing_prices lp ON ls.current_price_id = lp.id and ls.status = 'Active' and ls.listing_category = 'Resale'
                where 1=1 GROUP BY ls.option_id,ls.tower_id,ls.facing_id,ls.floor,UNIT_PRICE,ABS_PRICE HAVING count(*)>1) new2 on po.options_id = new2.Option_id 
                and (new2.facing_id = ls.facing_id or ls.facing_id is null) and (new2.floor = ls.floor or ls.floor is null) and  (ls.tower_id = new2.Tower_id or ls.tower_id is null) and (lp.price_per_unit_area = new2.UNIT_PRICE and lp.price = new2.ABS_PRICE)
                LEFT JOIN resi_project_tower_details tw ON ls.tower_id = tw.tower_id 
                LEFT JOIN master_directions ms on ms.id = ls.facing_id
                LEFT JOIN company_users cu ON ls.seller_id = cu.user_id
                LEFT JOIN company c ON cu.company_id = c.id order by Option_id",
        'subject' => 'Duplicate listings',
//            'recipients'=>array('jitendra.pathak@proptiger.com'), 
        'recipients' => array('cms-cron@proptiger.com', 'suneel.kumar@proptiger.com', 'prakash.kanyal@proptiger.com', 'kapil.chadha@proptiger.com'),
        'attachmentname' => 'Duplicate Listings',
        'message' => "Hi, Please find the attached list of duplicate listings inserted yesterday",
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select options_id, project_id, option_name, option_type, size from resi_project_options where option_category = 'Actual' and  date(created_at) >='" . $past_date . "';",
        'subject' => 'New Configurations',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'Suneel.kumar@proptiger.com', 'kapil.chadha@proptiger.com'),
        'attachmentname' => 'New Configurations',
        'message' => "Configurations which have been created yesterday",
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select rpors.options_id, rpo.project_id , rpo.option_name, rpo.option_type, rpo.size,   
    sum(((IFNULL(room_length,0)*12 + IFNULL(room_length_inch,0))/12)*((IFNULL(room_breath,0)*12 + IFNULL(room_breath_inch,0)))/12) as carpet_area
        from resi_proj_options_room_size rpors
    inner join resi_project_options rpo on  rpo.options_id = rpors.options_id   
         where rpo.size is not null
        group by rpors.options_id
            having (((carpet_area / (rpo.size)) *100) < 60 OR ((carpet_area / (rpo.size)) *100) > 80);",
        'subject' => 'Carpet Area Greater than 80% and Less than 60%',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'Suneel.kumar@proptiger.com', 'kapil.chadha@proptiger.com'),
        'attachmentname' => 'Carpet Area Greater than 80 and Less than 60',
        'message' => "Carpet Area Greater than 80% and Less than 60% of Size",
        'sendifnodata' => 0
    )
);

$weeklyEmail = array(
    array(
        'sql' => "select rp.PROJECT_ID, rp.PROJECT_NAME from resi_project rp 
             where 
                rp.version = 'Cms'
                and rp.status in('Active','ActiveInCms') and project_status_id = 2;",
        'subject' => 'Cancelled projects but not yet marked inactive',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com'),
        'attachmentname' => 'Cancelled_projecst_but_not_yet_marked_inactive',
        'sendifnodata' => 0
    ),
    array(
        /* 'sql'=>"select rp.PROJECT_ID, rp.PROJECT_NAME,rp.promised_completion_date as COMPLETION_DATE from resi_project rp 
          where
          rp.version = 'Cms' and rp.status in('Active','ActiveInCms') and project_status_id in(8,1,7) and promised_completion_date < '$currentDate'
          and promised_completion_date != '0000-00-00' and promised_completion_date is not null;", */
        'sql' => "SELECT rpp.PROJECT_ID,rp.PROJECT_NAME,rpp.PHASE_ID,rpp.PHASE_TYPE,rpp.PHASE_NAME,rpp.COMPLETION_DATE FROM resi_project_phase rpp
						INNER JOIN resi_project rp on rpp.PROJECT_ID = rp.PROJECT_ID AND rp.version = 'Cms'
						WHERE rpp.version = 'Cms' 
						AND rpp.status = 'Active' 
						AND rp.status in('Active','ActiveInCms') 
						AND rpp.construction_status in(8,1,7) 
						AND rpp.COMPLETION_DATE < '$currentDate'
						AND rpp.COMPLETION_DATE != '0000-00-00' AND rpp.COMPLETION_DATE IS NOT NULL
						AND 
						IF(rpp.PROJECT_ID IN (SELECT project_id from resi_project_phase where phase_type = 'Actual' AND status = 'Active' AND version = 'Cms'),rpp.PHASE_TYPE = 'Actual',rpp.PHASE_TYPE = 'Logical')
						ORDER BY rpp.PROJECT_ID ASC;",
        'subject' => 'Projects whose status is Pre Launch,Under construction,Launch projects but Expected completion date is in past',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'ravi.srivastava@proptiger.com'),
        'attachmentname' => 'projects_whose_status_is_pre_launch_under_construction_launch_projects_but_expected_completion_date_is_in_past',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select rp.project_id,rp.project_name,l.label as LOCALITY_NAME, c.label as CITY_NAME,rp.LATITUDE as PROJECT_LATITUDE,rp.LONGITUDE as PROJECT_LONGITUDE,l.latitude as LOCALITY_LATITUDE,l.longitude as LOCALITY_LONGITUDE,((ACOS(SIN(rp.latitude * PI() / 180) * SIN(l.latitude * PI() / 180) + COS(rp.latitude * PI() / 180) 
                * COS(l.latitude * PI() / 180) * COS((rp.longitude - l.longitude) * PI() / 180)) * 180 
                 / PI()) * 60 * 1.1515)*1.609344 AS `DISTANCE`               
 
                from resi_project rp join locality l on rp.locality_id = l.locality_id 
                join suburb s on l.suburb_id = s.suburb_id
                join city c on s.city_id = c.city_id
             where ( rp.LATITUDE  not in ($latLongList) AND rp.LONGITUDE not in ($latLongList)) and
                rp.version = 'Cms' and rp.status in('Active','ActiveInCms') and c.city_id in($cityList) having distance >10 ;",
        'subject' => 'PIDs greater than 10km from locality center',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'Sandeep.jakhar@proptiger.com', 'Suneel.kumar@proptiger.com'),
        'attachmentname' => 'PIDs_greater_than_10km_from_locality_center',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "SELECT rp.project_id,rp.project_name,rp.project_status,max(img.taken_at) created_at
					FROM proptiger.Image img
					INNER JOIN proptiger.ImageType imgt ON img.ImageType_id = imgt.id and imgt.id = 3
					INNER JOIN proptiger.ObjectType ot ON imgt.ObjectType_id = ot.id and ot.id = 1
					INNER JOIN proptiger.RESI_PROJECT rp ON img.object_id = rp.project_id
					INNER JOIN proptiger.RESI_PROJECT_TYPES rpt ON rp.project_id = rpt.project_id
					INNER JOIN proptiger.portfolio_listings plst ON rpt.type_id = plst.type_id
					WHERE  rp.project_status = 'Under Construction'  AND rp.active = 1  				  
				    GROUP BY rp.project_id
				    HAVING ((created_at NOT BETWEEN (CURRENT_DATE  - INTERVAL 90 DAY)  AND 	CURRENT_DATE) AND created_at < CURRENT_DATE);",
        'subject' => 'Protfolio Projects which dont have Construction Updates since last 3 months',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'karanvir.singh@proptiger.com', 'prashant.pracheta@proptiger.com'),
        'attachmentname' => 'protfolio_projects_which_dont_have_Construction_Updates_since_last_3_months',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "SELECT rp.project_id,rp.project_name,rp.project_status,max(lstp.effective_date)  effective_date FROM listing_prices lstp
				INNER JOIN listings lst ON lstp.listing_id = lst.id AND lst.status = 'Active' AND lst.listing_category='Primary'
				INNER JOIN resi_project_phase rpp ON rpp.phase_id = lst.phase_id AND rpp.version = 'Website'
				INNER JOIN proptiger.RESI_PROJECT rp ON rpp.project_id = rp.project_id
				INNER JOIN proptiger.RESI_PROJECT_TYPES rpt ON rp.project_id = rpt.project_id
				INNER JOIN proptiger.portfolio_listings plst ON rpt.type_id = plst.type_id
				WHERE lstp.version = 'Website' AND rpp.status = 'Active' 
				AND rp.project_status not in ('Cancelled','On Hold','Not Launched') 
				AND (rp.availability is null OR rp.availability > 0)  AND rp.active = 1
				GROUP BY rp.project_id 
				HAVING ((effective_date NOT BETWEEN (STR_TO_DATE(concat(YEAR(NOW()),',',MONTH(NOW()),',01'),'%Y,%m,%d')  - INTERVAL 90 DAY)
				AND 
				( STR_TO_DATE(concat(YEAR(NOW()),',',MONTH(NOW()),',01'),'%Y,%m,%d'))) AND effective_date < CURRENT_DATE);",
        'subject' => 'Primary Protfolio Projects which have Month of Price is older than 3 months',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'karanvir.singh@proptiger.com', 'prashant.pracheta@proptiger.com'),
        'attachmentname' => 'Primary_Protfolio_Projects_which_have_Month_of_Price_is_older_than_3_months',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "SELECT rp.project_id,rp.project_name,rp.project_status,max(lstp.effective_date)  effective_date FROM listing_prices lstp
				INNER JOIN listings lst ON lstp.listing_id = lst.id AND lst.status = 'Active' AND lst.listing_category='Primary'
				INNER JOIN resi_project_phase rpp ON rpp.phase_id = lst.phase_id AND rpp.version = 'Website'
				INNER JOIN proptiger.RESI_PROJECT rp ON rpp.project_id = rp.project_id
				INNER JOIN proptiger.RESI_PROJECT_TYPES rpt ON rp.project_id = rpt.project_id
				INNER JOIN proptiger.portfolio_listings plst ON rpt.type_id = plst.type_id
				WHERE lstp.version = 'Website' AND rpp.status = 'Active' 
				AND rp.project_status not in ('Cancelled','On Hold','Not Launched')
				AND rp.availability = 0  AND rp.active = 1
				GROUP BY rp.project_id 
				HAVING ((effective_date NOT BETWEEN (STR_TO_DATE(concat(YEAR(NOW()),',',MONTH(NOW()),',01'),'%Y,%m,%d')  - INTERVAL 90 DAY)
				AND 
				( STR_TO_DATE(concat(YEAR(NOW()),',',MONTH(NOW()),',01'),'%Y,%m,%d'))) AND effective_date < CURRENT_DATE);",
        'subject' => 'Resale Protfolio Projects which have Month of Price is older than 3 months',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'karanvir.singh@proptiger.com', 'prashant.pracheta@proptiger.com'),
        'attachmentname' => 'Resale_Protfolio_Projects_which_have_Month_of_Price_is_older_than_3_months',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "SELECT PROJECT_ID, c.LABEL CITY, l.LABEL Locality, rp.LOCALITY_ID,rp.LATITUDE, rp.LONGITUDE,
                    rp.PROJECT_NAME, rb.BUILDER_NAME FROM
                    resi_project rp
                    join resi_builder rb on rp.builder_id = rb.builder_id
                    join locality l on rp.locality_id = l.locality_id
                    join suburb s on l.suburb_id = s.suburb_id
                    join city c on s.city_id = c.city_id
            where
                    ( rp.LATITUDE IN ($latLongList) OR rp.LONGITUDE IN ($latLongList) OR rp.LATITUDE is null OR rp.LONGITUDE is null)
                AND rp.status in('Active','ActiveInCms') and rp.version = 'Cms';",
        'subject' => 'Missing Latitude and Longitude List',
        'recipients' => array('cms-cron@proptiger.com', 'ankur.dhawan@proptiger.com', 'Ravi.srivastava@proptiger.com', 'kapil.chadha@proptiger.com'),
        'attachmentname' => 'Missing_latitude_longitude_list',
        'sendifnodata' => 0
    ),
    array(
        'sql' => "select project_id, project_name, CHAR_LENGTH(project_description) description_length 
                   from resi_project where CHAR_LENGTH(project_description) < 25 and version = 'Cms' and status != 'Inactive';",
        'subject' => 'Projects having description length less than 25 characters',
        'recipients' => array('cms-cron@proptiger.com', 'pallavi.singh@proptiger.com', 'chandan.singh@proptiger.com'),
        'attachmentname' => 'projects_having_short_description',
        'sendifnodata' => 0
    )
);
?>
