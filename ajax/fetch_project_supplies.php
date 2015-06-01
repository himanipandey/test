<?php
include("../smartyConfig.php");
include("../appWideConfig.php");
include("../dbConfig.php");
include("../modelsConfig.php");
include("../includes/configs/configs.php");
include("../builder_function.php");

$projectId = $_POST['projectId'];
$project_phase = $_POST['project_phase'];
$isSupplyLaunchVerified = $_POST['isSupplyLaunchVerified'];

$supplyAll = array();
$res = ProjectSupply::projectSupplyForProjectPage($projectId);
$arrPhaseCount = array();
$arrPhaseTypeCount = array();

foreach ($res as $data) {
    if ($data['PHASE_NAME'] == '')
        $data['PHASE_NAME'] = 'noPhase';
    $supplyAll[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = $data;
    $arrPhaseCount[$data['PHASE_NAME']][] = $data['PROJECT_TYPE'];
    $arrPhaseTypeCount[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = '';
}

$supplyAllArray = array();
foreach ($supplyAll as $k => $v) {
    foreach ($v as $kMiddle => $vMiddle) {
        foreach ($vMiddle as $kLast => $vLast) {
            $supplyAllArray[$k][$kMiddle][$kLast]['PHASE_NAME'] = $vLast['PHASE_NAME'];
            $supplyAllArray[$k][$kMiddle][$kLast]['LAUNCH_DATE'] = $vLast['LAUNCH_DATE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['REMARKS'] = $vLast['REMARKS'];
            $supplyAllArray[$k][$kMiddle][$kLast]['COMPLETION_DATE'] = $vLast['COMPLETION_DATE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['submitted_date'] = $vLast['submitted_date'];
            $supplyAllArray[$k][$kMiddle][$kLast]['PROJECT_ID'] = $vLast['PROJECT_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['PHASE_ID'] = $vLast['PHASE_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['NO_OF_BEDROOMS'] = $vLast['NO_OF_BEDROOMS'];
            $supplyAllArray[$k][$kMiddle][$kLast]['EDITED_NO_OF_FLATS'] = $vLast['NO_OF_FLATS'];
            $supplyAllArray[$k][$kMiddle][$kLast]['EDITED_LAUNCHED'] = $vLast['LAUNCHED'];

            $supplyAllArray[$k][$kMiddle][$kLast]['EDIT_REASON'] = $vLast['EDIT_REASON'];
            $supplyAllArray[$k][$kMiddle][$kLast]['SUBMITTED_DATE'] = $vLast['SUBMITTED_DATE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['PROJECT_TYPE'] = $vLast['PROJECT_TYPE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['LISTING_ID'] = $vLast['LISTING_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['BOOKING_STATUS_ID'] = $vLast['BOOKING_STATUS_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['construction_status'] = $vLast['construction_status'];


            $qryEditedLaunched = "select ps.supply,ps.launched,pa.availability from project_supplies ps
									inner join project_availabilities pa on ps.id = pa.project_supply_id
									where listing_id = '" . $vLast['LISTING_ID'] . "' and version = 'Cms' order by effective_month desc limit 1";

            $resEditedLaunched = mysql_query($qryEditedLaunched) or die(mysql_error());
            $dataEditedLaunched = mysql_fetch_assoc($resEditedLaunched);
            $supplyAllArray[$k][$kMiddle][$kLast]['NO_OF_FLATS'] = $dataEditedLaunched['supply'];
            $supplyAllArray[$k][$kMiddle][$kLast]['LAUNCHED'] = $dataEditedLaunched['launched'];
            $supplyAllArray[$k][$kMiddle][$kLast]['AVAILABLE_NO_FLATS'] = $dataEditedLaunched['availability'];
        }
    }
}

$lastUpdatedDetail = fetch_last_updated_details_inventories($projectId); //To Do
?>
<?php

function fetch_last_updated_details_inventories($projectId) {
    $qry = " SELECT rp.updated_at, p.FNAME, p.DEPARTMENT                   
                    FROM
                       _t_project_supplies rp
						  JOIN " . LISTINGS . " lst ON rp.LISTING_ID = lst.ID
						  JOIN " . RESI_PROJECT_OPTIONS . " rpo ON lst.OPTION_ID = rpo.OPTIONS_ID
                          JOIN proptiger_admin p ON rp.UPDATED_BY = p.ADMINID
                       WHERE
                           rpo.PROJECT_ID = $projectId ORDER BY rpo.UPDATED_AT Desc LIMIT 1";

    $result = mysql_query($qry);
    $count = 0;
    while ($res = mysql_fetch_object($result)) {
        $arrData['resi_proj_supply'][$count]['name'] = $res->FNAME;
        $arrData['resi_proj_supply'][$count]['dept'] = $res->DEPARTMENT;
        $arrData['resi_proj_supply'][$count]['ACTION_DATE'] = $res->updated_at;

        $count++;
    }

    $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_project_availabilities a
                           JOIN
                       (SELECT p.DEPARTMENT , MAX(pal._t_transaction_id) as tid FROM _t_project_availabilities pal
						INNER JOIN project_supplies ps
							 on pal.project_supply_id = ps.id
						INNER JOIN listings lst
							 on ps.listing_id = lst.id 
						INNER JOIN resi_project_phase rpp
							 on lst.phase_id = rpp.phase_id
						JOIN proptiger_admin p ON pal.updated_by = p.ADMINID
						WHERE (rpp.PROJECT_ID = $projectId and lst.listing_category='Primary')
						GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";

    $result = mysql_query($qry);
    $count = 0;
    while ($res = mysql_fetch_object($result)) {
        $arrData['project_availabilities'][$count]['name'] = $res->FNAME;
        $arrData['project_availabilities'][$count]['dept'] = $res->DEPARTMENT;
        $arrData['project_availabilities'][$count]['ACTION_DATE'] = $res->updated_at;

        $count++;
    }

    return $arrData;
}
?>

<?php if ($supplyAllArray): ?>
    <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
        <?php
        if (in_array($project_phase, $ARR_PROJ_EDIT_PERMISSION[$dept])) {
            echo '<tr>
                    <td align="left"  nowrap><b>Supply</b><button class="clickbutton" onclick="$(this).trigger(\'event8\');">Edit</button>';
            if ($supplyEditPermissionAccess == 1) {
                if (!$isSupplyLaunchVerified) {
                    echo '<button class="clickbutton" style="background-color: red" onclick="$(this).trigger(\'event17\');">Verify Supply Change</button>';
                }
            }
            echo '<button class="clickbutton" onclick="$(this).trigger(\'event19\');">Edit Historical Price-Inventory</button></td>';
            echo '</tr>';
        }
        echo '<tr><tr bgcolor = "#c2c2c2">';

        echo '</tr>';
        ?>
    </table>
<?php endif; ?>



