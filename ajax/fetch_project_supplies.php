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

$lastUpdatedDetail = fetch_last_updated_details_inventories($projectId); //Last Updated Details
//fecthing perivous months data

$ProjectOptionDetail = ProjectOptionDetail($projectId);
$PreviousMonthsData = getPrevMonthProjectData($projectId);
$PreviousMonthsAvailability = getFlatAvailability($projectId);

$arrOnlyPreviousMonthData = array();
foreach ($PreviousMonthsData as $k => $v) {
    if ($k != 'current' && $k != 'latest')
        $arrOnlyPreviousMonthData[] = $k;
}
$smarty->assign("arrOnlyPreviousMonthData", $arrOnlyPreviousMonthData);

$arrAvaiPreviousMonthData = array();
foreach ($PreviousMonthsAvailability as $k => $v) {
    if ($k != 'current' && $k != 'latest')
        $arrAvaiPreviousMonthData[] = $k;
}
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
    <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;" cellpadding="10">
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
        echo '<tr bgcolor = "#c2c2c2">';
        if (count($lastUpdatedDetail['resi_proj_supply']) > 0) {
            echo '<td nowrap="nowrap"  align="left" valign="top" width="20%"><b>Last Updated Detail : Supply</b><br></br>';
            foreach ($lastUpdatedDetail['resi_proj_supply'] as $key => $item) {
                echo "<b>Department: </b> " . $item['dept'] . "</br>
                      <b>Name: </b> " . $item['name'] . "</br>
                       <b>last Updated Date: </b> " . $item['ACTION_DATE'] . "</br></br>";
            }

            echo '</td>';
        }
        if (count($lastUpdatedDetail['project_availabilities']) > 0) {
            echo '<td colspan=15 nowrap="nowrap"  align="left" valign="top" ><b>Last Updated Detail : Inventory</b><br></br>';
            foreach ($lastUpdatedDetail['project_availabilities'] as $key => $item) {
                echo "<b>Department: </b> " . $item['dept'] . "</br>
                      <b>Name: </b> " . $item['name'] . "</br>
                       <b>last Updated Date: </b> " . $item['ACTION_DATE'] . "</br></br>";
            }

            echo '</td>';
        }
        echo '</tr>';
        echo '<tr><td colspan=16>';
        echo '<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">';
        echo '<tr class="headingrowcolor" height="30px;">
		<td class="whiteTxt" align = "center" nowrap><b>SNO.</b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Phase<br>Launch <br> Completion Date<br> Submitted Date <br> Booking Status<br> Construction Status </b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Project Type</b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Unit Type</b></td>
                <td class="whiteTxt" align = "center" nowrap><b>No of Flats</b></td>
                <td class="whiteTxt" align = "center" nowrap><b>Edited No of Flats</b></td>
                <td class="whiteTxt" align = "center" nowrap><b>Launched Units</b></td>
                <td class="whiteTxt" align = "center" nowrap><b>Edited Launched Units</b></td>
		<!-- <td class="whiteTxt" align = "center" nowrap><b>Is flats Information is Currect</b></td> -->
		<td class="whiteTxt" align = "center" nowrap><b>Available No of Flats</b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Available No of Flats<br>in ' . $arrAvaiPreviousMonthData[0] . '</b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Available No of Flats<br>in ' . $arrAvaiPreviousMonthData[1] . '</b></td>
		<!-- <td class="whiteTxt" align = "center" nowrap><b>Available No of Flats Lastest Month</b></td> -->
		<!-- <td class="whiteTxt" align = "center" nowrap><b>Is Available Flat Information is Currect</b></td> -->
		<td class="whiteTxt" align = "center" nowrap><b>Edit Reason</b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Effective Date</b></td>
		<td class="whiteTxt" align = "center" nowrap><b>Phase Remark</b></td>
            </tr>';
        $olderValuePhase = '';
        $cnt = 0;
        $totalSumFlat = 0;
        $totalEditedSumFlat = 0;
        $totalLaunchedFlat = 0;
        $totalEditedLaunchedFlat = 0;
        $totalSumflatAvail = 0;

        foreach ($supplyAllArray as $key => $item) {
            $totalNoOfFlatsPPhase = 0;
            $totalEditedNoOfFlatsPPhase = 0;
            $totalLaunchedFlatsPPhase = 0;
            $totalEditedLaunchedFlatsPPhase = 0;
            $availableoOfFlatsPPhase = 0;
            $olderValueType = '';

            foreach ($item as $keyInner => $innerItem) {
                $totalNoOfFlatsPtype = 0;
                $totalEditedNoOfFlatsPtype = 0;
                $totalLaunchedFlatsPtype = 0;
                $totalEditedLaunchedFlatsPtype = 0;
                $availableoOfFlatsPtype = 0;

                foreach ($innerItem as $keylast => $lastItem) {

                    $cnt = $cnt + 1;
                    if (($cnt) % 2 == 0) {
                        $color = "bgcolor='#F7F8E0'";
                    } else {
                        $color = "bgcolor='#f2f2f2'";
                    }

                    echo '<tr ' . $color . '  height="30px;">';
                    echo '<td valign ="top" align="center">' . $cnt . '</td>';

                    if ($olderValuePhase == '' || $olderValuePhase != $key) {
                        echo '<td valign ="top" align = "center" nowrap rowspan = "' . (count($arrPhaseCount[$key]) + 1) . '">';
                        echo ucfirst($key) . '<br/>';
                        if ($lastItem['LAUNCH_DATE'] != '' && $lastItem['COMPLETION_DATE'] != '') {
                            echo $lastItem['LAUNCH_DATE'] . '<br>' . $lastItem['COMPLETION_DATE'] . '<br>' . $lastItem['submitted_date'];
                        } else {
                            echo '--';
                        }

                        echo '<br/>';

                        if ($lastItem['BOOKING_STATUS_ID'] > 0) {
                            if ($lastItem['BOOKING_STATUS_ID'] == 1) {
                                echo "Available<br/>";
                            } elseif ($lastItem['BOOKING_STATUS_ID'] == 2) {
                                echo "Sold out<br/>";
                            } elseif ($lastItem['BOOKING_STATUS_ID'] == 3) {
                                echo "On Hold<br/>";
                            }
                        } else {
                            echo '--<br/>';
                        }

                        if ($lastItem['construction_status'] != '') {
                            echo $lastItem['construction_status'];
                        } else {
                            echo '--';
                        }

                        echo '</td>';
                    }

                    $olderValuePhase = $key;

                    if ($olderValueType != $keyInner || $olderValueType == '') {
                        echo '<td valign ="top" align = "center" rowspan = "' . count($arrPhaseTypeCount[$key][$keyInner]) . '">';
                        echo $keyInner;
                        echo '</td>';
                    }

                    $olderValueType = $keyInner;

                    echo '<td valign ="top" align="center">';
                    echo (($lastItem['NO_OF_BEDROOMS']) ? $lastItem['NO_OF_BEDROOMS'] : 0) . "BHK";
                    echo '</td>';

                    echo '<td valign ="top" align="center" >';
                    echo $lastItem['NO_OF_FLATS'];
                    $totalNoOfFlatsPtype = $totalNoOfFlatsPtype + $lastItem['NO_OF_FLATS'];
                    $totalEditedNoOfFlatsPtype = $totalEditedNoOfFlatsPtype + $lastItem['EDITED_NO_OF_FLATS'];
                    $totalLaunchedFlatsPtype = $totalLaunchedFlatsPtype + $lastItem['LAUNCHED'];
                    $totalEditedLaunchedFlatsPtype = $totalEditedLaunchedFlatsPtype + $lastItem['EDITED_LAUNCHED'];
                    $totalNoOfFlatsPPhase = $totalNoOfFlatsPPhase + $lastItem['NO_OF_FLATS'];
                    $totalEditedNoOfFlatsPPhase = $totalEditedNoOfFlatsPPhase + $lastItem['EDITED_NO_OF_FLATS'];
                    $totalLaunchedFlatsPPhase = $totalLaunchedFlatsPPhase + $lastItem['LAUNCHED'];
                    $totalEditedLaunchedFlatsPPhase = $totalEditedLaunchedFlatsPPhase + $lastItem['EDITED_LAUNCHED'];
                    if ($key != 'No Phase') {
                        $totalSumFlat = $totalSumFlat + $lastItem['NO_OF_FLATS'];
                        $totalEditedSumFlat = $totalEditedSumFlat + $lastItem['EDITED_NO_OF_FLATS'];
                        $totalLaunchedFlat = $totalLaunchedFlat + $lastItem['LAUNCHED'];
                        $totalEditedLaunchedFlat = $totalEditedLaunchedFlat + $lastItem['EDITED_LAUNCHED'];
                        $totalSumflatAvail = $totalSumflatAvail + $lastItem['AVAILABLE_NO_FLATS'];
                    }
                    if ($phasename != '' && $stageName != '') {
                        if ($lastItem['NO_OF_FLATS'] != $arrProjectSupply[$key][$keyInner][$keylast]['NO_OF_FLATS']) {
                            echo '<br/><span style="background-color: yellow;">' . $arrProjectSupply[$key][$keyInner][$keylast]['NO_OF_FLATS'] . '</span>';
                        }
                    }
                    echo '</td>';


                    echo '<td valign ="top" align="center">' . $lastItem['EDITED_NO_OF_FLATS'] . '</td>';
                    echo '<td valign ="top" align="center">' . $lastItem['LAUNCHED'] . '</td>';
                    echo '<td valign ="top" align="center">' . $lastItem['EDITED_LAUNCHED'] . '</td>';

                    echo '<td valign ="top" align="center">';
                    echo $lastItem['AVAILABLE_NO_FLATS'];
                    $availableoOfFlatsPtype = $availableoOfFlatsPtype + $lastItem['AVAILABLE_NO_FLATS'];
                    $availableoOfFlatsPPhase = $availableoOfFlatsPPhase + $lastItem['AVAILABLE_NO_FLATS'];
                    if ($phasename != '' && $stageName != '') {
                        if ($lastItem['AVAILABLE_NO_FLATS'] != $arrProjectSupply[$key][$keyInner][$keylast]['AVAILABLE_NO_FLATS']) {
                            echo '<br/>';
                            echo '<span style="background-color: yellow;">' . $arrProjectSupply[$key][$keyInner][$keylast]['AVAILABLE_NO_FLATS'] . '</span>';
                        }
                    }
                    echo '</td>';

                    echo '<td valign ="top" align="center">';
                    $monkey = $arrAvaiPreviousMonthData[0];
                    foreach ($PreviousMonthsAvailability[$monkey] as $k2 => $i2) {
                        foreach ($i2 as $k3 => $i3) {
                            if ($lastItem['PHASE_ID'] == $k2) {
                                if ($keyInner == $PreviousMonthsAvailability[$monkey][$k2][$k3]['project_type']) {
                                    if ($lastItem['NO_OF_BEDROOMS'] == $PreviousMonthsAvailability[$monkey][$k2][$k3]['no_of_bedrooms']) {
                                        if (substr($PreviousMonthsAvailability[$monkey][$k2][$k3]['effective_date'], 0, 7) == $monkey) {
                                            echo $PreviousMonthsAvailability[$monkey][$k2][$k3]['available_no_of_flats'];
                                        } else {
                                            echo "Not Applicable";
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo '</td>';

                    echo '<td valign ="top" align="center">';
                    $monkey = $arrAvaiPreviousMonthData[1];
                    foreach ($PreviousMonthsAvailability[$monkey] as $k2 => $i2) {
                        foreach ($i2 as $k3 => $i3) {
                            if ($lastItem['PHASE_ID'] == $k2) {
                                if ($keyInner == $PreviousMonthsAvailability[$monkey][$k2][$k3]['project_type']) {
                                    if ($lastItem['NO_OF_BEDROOMS'] == $PreviousMonthsAvailability[$monkey][$k2][$k3]['no_of_bedrooms']) {
                                        if (substr($PreviousMonthsAvailability[$monkey][$k2][$k3]['effective_date'], 0, 7) == $monkey) {
                                            echo $PreviousMonthsAvailability[$monkey][$k2][$k3]['available_no_of_flats'];
                                        } else {
                                            echo "Not Applicable";
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo '</td>';

                    echo '<td valign ="top" align="center">';
                    echo $lastItem['EDIT_REASON'];
                    if ($phasename != '' && $stageName != '') {
                        if ($lastItem['EDIT_REASON'] != $arrProjectSupply[$key][$keyInner][$keylast]['EDIT_REASON']) {
                            echo '<br/>';
                            echo '<span style="background-color: yellow;">' . $arrProjectSupply[$key][$keyInner][$keylast]['EDIT_REASON'] . '</span>';
                        }
                    }
                    echo '</td>';


                    echo '<td valign ="top" align ="center" nowrap>';
                    echo $lastItem['SUBMITTED_DATE'];
                    if ($phasename != '' && $stageName != '') {
                        if ($lastItem['SUBMITTED_DATE'] != $arrProjectSupply[$key][$keyInner][$keylast]['SUBMITTED_DATE']) {
                            echo '<br>';
                            echo '<span style="background-color: yellow;">' . $arrProjectSupply[$key][$keyInner][$keylast]['SUBMITTED_DATE'] . '</span>';
                        }
                    }
                    echo '</td>';

                    echo '<td align = "center" nowrap>';
                    if ($key != $newK) {
                        if ($lastItem['REMARKS'] != '') {
                            echo $lastItem['REMARKS'];
                        } else {
                            echo "--";
                        }
                        $newK = $key;
                    }
                    echo '</td>';
                    echo '</tr>';
                }

                if (count($arrPhaseTypeCount[$key][$keyInner]) > 1) {
                    echo '<tr bgcolor ="#FBF2EF" height="30px;">';
                    echo '<td align ="right" colspan ="4" nowrap><b>SubTotal ' . $lastItem['PROJECT_TYPE'] . '</b></td>';
                    echo '<td align ="center"><b> ' . $totalNoOfFlatsPtype . '</b></td>';
                    echo '<td align ="center"><b> '.$totalEditedNoOfFlatsPtype.'</b></td>';
                    echo '<td align ="center"><b> '.$totalLaunchedFlatsPtype.'</b></td>';
                    echo '<td align ="center"><b> '.$totalEditedLaunchedFlatsPtype.'</b></td>';
                    echo '<td  align ="center"><b> '.$availableoOfFlatsPtype.'</b></td>';
                    echo '<td  align ="left" >&nbsp;</td>';
                    echo '<td  align ="left" >&nbsp;</td>';
                    echo '<td  align ="left" >&nbsp;</td>';
                    echo '<td  align ="left" >&nbsp;</td>';
                    echo '<td  align ="left" >&nbsp;</td>';
                    echo '</tr>';
                }
            }

            echo '<tr bgcolor ="#F6D8CE" height="30px;">';
            echo '<td align ="right" colspan ="4" nowrap><b>SubTotal '.ucfirst($key).'</b></td>';
            echo '<td align ="center"><b> '.$totalNoOfFlatsPPhase.'</b></td>';
            echo '<td align ="center"><b> '.$totalEditedNoOfFlatsPPhase.'</b></td>';
            echo '<td align ="center"><b> '.$totalLaunchedFlatsPPhase.'</b></td>';
            echo '<td align ="center"><b> '.$totalEditedLaunchedFlatsPPhase.'</b></td>';
            echo '<td align ="center"><b> '.$availableoOfFlatsPPhase.'</b></td>';
            if (ucfirst($key) == 'No Phase') {
                echo '<td  align ="left" colspan ="5"><b>';
                echo 'Sold Out&nbsp;&nbsp;:&nbsp;&nbsp;';
                echo sprintf("%.2f", (100 - ($availableoOfFlatsPPhase * 100 / $totalNoOfFlatsPPhase))) . '%';
                echo '</b></td>';
            } else {
                echo '<td  align ="left" >&nbsp;</td>';
                echo '<td  align ="left" >&nbsp;</td>';
                echo '<td  align ="left" >&nbsp;</td>';
                echo '<td  align ="left" >&nbsp;</td>';
                echo '<td  align ="left" >&nbsp;</td>';
            }

            echo '</tr>';
        }
        
        if(count($supplyAllArray)>1){
           echo '<tr bgcolor ="#F2F2F2" height="30px;">';
           echo '<td align ="right" colspan ="4" nowrap><b>Grand Total '.$flafHideGrandTot.'</b></td>';
           echo '<td align ="center"><b> '.$totalSumFlat.'</b></td>';
           echo '<td align ="center"><b> '.$totalEditedSumFlat.'</b></td>';
           echo '<td align ="center"><b> '.$totalLaunchedFlat.'</b></td>';
           echo '<td align ="center"><b> '.$totalEditedLaunchedFlat.'</b></td>';
           echo '<td align ="center"><b>'.$totalSumflatAvail.'</b></td>';
           echo '<td  align ="left" colspan ="5"><b>Sold Out&nbsp;&nbsp;:&nbsp;&nbsp;';
           echo sprintf("%.2f", (100 - ($totalSumflatAvail * 100 / $totalSumFlat))) . '%';
           echo '</b></td>';
        }

        echo '</table>';
        echo '</td></tr>';
        ?>
    </table>
<?php else: ?>
    Empty
<?php endif; ?>



