<?php
    include("../smartyConfig.php");
    include("../appWideConfig.php");
    include("../dbConfig.php");
    include("../includes/configs/configs.php");
    date_default_timezone_set('Asia/Kolkata');
    include("../builder_function.php"); 
    include("../function/reportsFunction.php"); 
    include("../modelsConfig.php"); 

    $arr = array();
    $fromdateymd = $fromdate = $_REQUEST['frmdate']!='' ? $_REQUEST['frmdate'] : date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    $todateymd  = $todate   = $_REQUEST['todate']!='' ? $_REQUEST['todate'] : date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")));

    $dateArr = getDatesBetweeenTwoDates($fromdate,$todate);

    foreach($dateArr as $key=>$dates)
    {
        $df = date('d',strtotime($dates));
        $mf = date('m',strtotime($dates));
        $Yf = date('Y',strtotime($dates));

        $fromdate = date('Y-m-d',mktime(0,0, 0, $mf, $df, $Yf));
        $todate = date('Y-m-d',mktime(0,0, 0, $mf, $df, $Yf));
        $seldate = date('Y-m-d',mktime(0,0, 0, $mf, $df, $Yf));

        $quryand = $and = '';
        $and = ' WHERE ';
        if($fromdate!='')
        {
            $quryand .= $and." DATE(A.DATE_TIME)>='".$fromdate."'";
            $and = ' AND ';
        }

        if($todate!='')
        {
            $quryand .= $and." DATE(A.DATE_TIME)<='".$todate."'";
            $and = ' AND ';
        }

        if($_REQUEST['user']!='')
        {
            $quryand .= $and." A.ADMIN_ID='".$_REQUEST['user']."'";
            $and = ' AND ';
        }
        
        if($_REQUEST['team']!='')
        {
            $quryand .= $and." B.DEPARTMENT='".$_REQUEST['team']."'";
            $and = ' AND ';
        }

        if($todate == '' && $fromdate == '')
        {
            $quryand .= $and." DATE(A.DATE_TIME)>='".$fromdate."' AND DATE(A.DATE_TIME)<='".$todate."'";
            $and = ' AND ';
        }
        $quryand .= $and."C.version = 'cms'";
        #---------------------------------------
       $qry = "SELECT
                    A.PROJECT_ID,C.PROJECT_NAME,ph.name as PROJECT_PHASE,st.name as PROJECT_STAGE,
                    B.FNAME,B.DEPARTMENT,psm.project_status as PROJECT_STATUS,A.DATE_TIME DT,D.LABEL AS CITY_NAME
                FROM
                    project_stage_history A 
                    LEFT JOIN proptiger_admin B ON A.ADMIN_ID=B.ADMINID
                    LEFT JOIN resi_project C ON A.PROJECT_ID=C.PROJECT_ID
                    inner join locality lo on C.LOCALITY_ID = lo.LOCALITY_ID
                    inner join ".SUBURB." sub on lo.SUBURB_ID = sub.SUBURB_ID
                    inner join city D ON D.CITY_ID=sub.CITY_ID 
                    inner join master_project_phases ph ON A.project_phase_id=ph.id
                    inner join master_project_stages st ON A.project_stage_id=st.id
                    inner join project_status_master psm on C.project_status_id = psm.id
                    ".$quryand."
                ORDER BY A.DATE_TIME ";
        $allData = ResiProject::find_by_sql($qry);

        foreach( $allData as $data ) {

            $fname = $data->fname; 
            $team = $data->department; 
            $dt = $data->dt;
            $phase = $data->project_phase;
            $stage = $data->project_stage;
            $projectId = $data->project_id;
            $projectName = $data->project_name;

            $projectStatus = $data->project_status;

            $cityName = $data->city_name;

            $arr[] = array(
                        'PROJECT_ID'=>$projectId,
                        'PROJECT_NAME'=>$projectName,
                        'PROJECT_STATUS'=>$projectStatus,
                        'FNAME'=> $fname,
                        'DEPARTMENT'=> $team,
                        'PROJECT_STAGE'=>$stage,
                        'PROJECT_PHASE'=>$phase,
                        'DT'=>$dt,
                        'CITY_NAME'=>$cityName
                    );
        }
    }

    $contents = "";

    $contents .= "<table cellspacing=1 bgcolor='#c3c3c3' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>
    <tr bgcolor='#f2f2f2'>
    <td>SNO</td>
    <td>DATE</td>
    <td>TEAM</td>
    <td>USER</td>
    <td>PROJECT ID</td>
    <td>PROJECT NAME</td>
    <td>PROJECT STATUS</td>
    <td>STAGE</td>
    <td>PHASE</td>
    <td>CITY</td>
    </tr>
    ";
    $cnt = 1;
    foreach($arr as $key=>$ob1)
    {
        $ex   = $ob1['FNAME'];
        $team = $ob1['DEPARTMENT'];
        $dt = $ob1['DT'];
        $phase = $ob1['PROJECT_PHASE'];
        $stage = $ob1['PROJECT_STAGE'];
        $projid = $ob1['PROJECT_ID'];
        $projname = $ob1['PROJECT_NAME'];
        $projectStatus = $ob1['PROJECT_STATUS'];

        $cityName = $ob1['CITY_NAME'];

        $contents .= "
        <tr bgcolor='#f2f2f2'>
        <td>".$cnt."</td>
        <td>".$dt."</td>
        <td>".$team."</td>
        <td>".$ex."</td>
        <td>".$projid."</td>
        <td>".$projname."</td>
        <td>".$projectStatus."</td>
        <td>".$phase."</td>
        <td>".$stage."</td>	
        <td>".$cityName."</td>
        </tr>
    ";
            $cnt++;

    }

    $contents .= "</table>";

    $filename ="excelreport-".date('YmdHis').".xls";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
    echo $contents;

?>
