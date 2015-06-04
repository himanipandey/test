<?php
include("../smartyConfig.php");
include("../appWideConfig.php");
include("../dbConfig.php");
include("../modelsConfig.php");
include("../includes/configs/configs.php");
include("../builder_function.php");

$projectId = $_POST['projectId'];
$locId = $_POST['locId'];

//fetching last updated details of listing prices and project configurations
$lastUpdatedDetail = lastUpdatedAuditDetailProjectPrices($projectId);

//fetching Locality Avg prices
$localityAvgPrice = getLocalityAveragePrice($locId);

//fetching project prices
$arrResult = fetch_listing_prices($projectId);
$arrPrevMonthDate = $arrResult['arrPrevMonthDate'];
$uptionDetailWithPrice = $arrResult['uptionDetailWithPrice'];
?>
<?php

function fetch_listing_prices($projectId) {
    $optionsDetails = Listings::all(array('joins' => "join resi_project_phase p on (p.phase_id = listings.phase_id and p.version = 'Cms') 
    join resi_project_options o on (o.options_id = option_id)", 'conditions' =>
                array("o.PROJECT_ID = $projectId and OPTION_CATEGORY = 'Actual' and p.status = 'Active' and listings.status = 'Active' and listings.listing_category='Primary'"), "select" =>
                "listings.*,p.phase_name,o.option_name,o.size,o.carpet_area,o.villa_plot_area,o.villa_no_floors"));
    $uptionDetailWithPrice = array();
    foreach ($optionsDetails as $key => $value) {

        $listing_price = ListingPrices::find('all', array('conditions' =>
                    array('listing_id = ?', $value->id), "limit" => 1, "order" => "effective_date desc", 'select' =>
                    'effective_date'));


        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['option_name'] = $value->option_name;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['phase_name'] = $value->phase_name;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['size'] = $value->size;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['carpet_area'] = $value->carpet_area;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['villa_no_floors'] = $value->villa_no_floors;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['villa_plot_area'] = $value->villa_plot_area;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['effective_date'] = $listing_price[0]->effective_date;
        $uptionDetailWithPrice[$value->phase_id][$value->option_id]['booking_status_id'] = $value->booking_status_id;
    }

    $PreviousMonthsData = getPrevMonthProjectData($projectId);
    $arrPrevMonthDate = array();
    $arrResult = array();
    foreach ($PreviousMonthsData as $k => $v) {
        if ($cnt > 1) {
            foreach ($v as $keyMiddle => $vMiddle) {
                foreach ($vMiddle as $kLast => $vLast) {
                    $vLast['phase_name'] = $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['phase_name'];
                    if ($cnt == 2) {
                        $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['latestPrice'] = $vLast['price'];
                    }
                    if ($cnt == 3) {
                        $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['prevMonthPrice'] = $vLast['price'];
                    }
                    if ($cnt == 4) {
                        $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['prevPrevMonthPrice'] = $vLast['price'];
                    }
                }
            }
        }
        if ($cnt > 2 and $cnt <= 4)
            $arrPrevMonthDate[] = $k;
        $cnt++;
    }

    $arrResult['arrPrevMonthDate'] = $arrPrevMonthDate;
    $arrResult['uptionDetailWithPrice'] = $uptionDetailWithPrice;


    return $arrResult;
}

function lastUpdatedAuditDetailProjectPrices($projectId) {

    $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_listing_prices a
                           JOIN
                       (SELECT
                            p.DEPARTMENT, MAX(lp._t_transaction_id) as tid
							FROM _t_listing_prices lp
							INNER JOIN listings lst
								 on lp.listing_id = lst.id and lst.listing_category='Primary'
							INNER JOIN resi_project_phase rpp
								 on lst.phase_id = rpp.phase_id
							JOIN proptiger_admin p ON lp.updated_by = p.ADMINID
							WHERE (rpp.PROJECT_ID = $projectId and lst.listing_category='Primary')
							GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";

    $result = mysql_query($qry);
    $count = 0;
    while ($res = mysql_fetch_object($result)) {
        $arrData['listing_prices'][$count]['name'] = $res->FNAME;
        $arrData['listing_prices'][$count]['dept'] = $res->DEPARTMENT;
        $arrData['listing_prices'][$count]['ACTION_DATE'] = $res->updated_at;

        $count++;
    }

    $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_resi_project_options a
                           JOIN
                       (SELECT
                            p.DEPARTMENT, MAX(a._t_transaction_id) as tid
                       FROM
                           _t_resi_project_options a
                       JOIN proptiger_admin p ON a.updated_by = p.ADMINID
                       WHERE
                           a.PROJECT_ID = $projectId
                       GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";

    $result = mysql_query($qry);
    $count = 0;
    while ($res = mysql_fetch_object($result)) {
        $arrData['resi_project_options'][$count]['name'] = $res->FNAME;
        $arrData['resi_project_options'][$count]['dept'] = $res->DEPARTMENT;
        $arrData['resi_project_options'][$count]['ACTION_DATE'] = $res->updated_at;

        $count++;
    }

    return $arrData;
}
?>
<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;" cellpadding=5>
    <tr bgcolor = "#c2c2c2">
        <?php
        if (count($lastUpdatedDetail['listing_prices']) > 0) {
            echo '<td nowrap="nowrap"  align="left" valign="top"><b>Last Updated Detail : Project Price</b><br></br>';
            foreach ($lastUpdatedDetail['listing_prices'] as $item) {
                echo '<b>Department: </b> ' . $item['dept'] . '</br>';
                echo '<b>Name: </b> ' . $item['name'] . '</br>';
                echo '<b>last Updated Date: </b> ' . $item['ACTION_DATE'] . '</br></br>';
            }
            echo '</td>';
        }
        ?>
        <?php
        if (count($lastUpdatedDetail['resi_project_options']) > 0) {
            echo '<td nowrap="nowrap"  align="left" valign="top" colspan = "10"><b>Last Updated Detail : Project Configuration</b><br></br>';
            foreach ($lastUpdatedDetail['resi_project_options'] as $item) {
                echo '<b>Department: </b> ' . $item['dept'] . '</br>';
                echo '<b>Name: </b> ' . $item['name'] . '</br>';
                echo '<b>last Updated Date: </b> ' . $item['ACTION_DATE'] . '</br></br>';
            }
            echo '</td>';
        }
        ?>
    </tr>

    <tr>
        <td width = "100%" align = "center" colspan = "11">
            <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                <tr>
                    <td align="left"  nowrap colspan ="4">
                        <b> Locality Average Price : </b> <?php echo $localityAvgPrice ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width = "100%" align = "center" colspan = "11">
            <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                <tr class="headingrowcolor" height="30px;">
                    <td  nowrap="nowrap"  align="center" class=whiteTxt >SNo.</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Phase Name</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Effecive Date</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Unit Name</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Size</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Carpet Area</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Price Per Unit Area</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt nowrap>Price Per Unit Area <br> in <?php echo $arrPrevMonthDate[0] ?></td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt nowrap>Price Per Unit Area <br> in <?php echo $arrPrevMonthDate[1] ?></td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Villa Floors</td>
                    <td nowrap="nowrap"  align="left" class=whiteTxt>Booking Status</td>
                </tr>
                <?php
                $cntPrice = 0;
                foreach ($uptionDetailWithPrice as $key => $value) {
                    foreach ($value as $keyInner => $valueInner) {
                        if (($cntPrice + 1) % 2 == 0)
                            $color = "bgcolor='#F7F8E0'";
                        else
                            $color = "bgcolor='#f2f2f2'";

                        echo '<tr ' . $color . '>';
                        echo '<td align = "center" >' . ($cntPrice + 1) . '</td>';
                        echo '<td align = "left" >' . $valueInner['phase_name'] . '</td>';
                        echo '<td align = "left" style="width:250px;display:block;" >' . $valueInner['effective_date'] . '</td>';
                        echo '<td>
                        <input type="hidden" value="' . $projectId . '" name="projectId" />
                        ' . $valueInner['option_name'] . '
                  </td>';
                        echo '<td >' .
                        ((isset($valueInner['size'])) ? $valueInner['size'] : '--')
                        . '</td>';
                        echo '<td >' .
                        ((isset($valueInner['carpet_area'])) ? $valueInner['carpet_area'] : '--')
                        . '</td>';
                        echo '<td >' .
                        ((isset($valueInner['latestPrice'])) ? $valueInner['latestPrice'] : '--')
                        . '</td>';
                        echo '<td >' .
                        ((isset($valueInner['prevMonthPrice'])) ? $valueInner['prevMonthPrice'] : 'Not Applicable')
                        . '</td>';
                        echo '<td >' .
                        ((isset($valueInner['prevPrevMonthPrice'])) ? $valueInner['prevPrevMonthPrice'] : 'Not Applicable')
                        . '</td>';
                        echo '<td>' . $valueInner['villa_no_floors'] . '</td>';

                        if ($valueInner['booking_status_id'] > 0) {
                            if ($valueInner['booking_status_id'] == 1) {
                                echo '<td>Available</td>';
                            } elseif ($valueInner['booking_status_id'] == 2) {
                                echo '<td>Sold out</td>';
                            } elseif ($valueInner['booking_status_id'] == 3) {
                                echo '<td>On Hold</td>';
                            }
                        } else {
                            echo '<td>--</td>';
                        }

                        echo '</tr>';

                        $cntPrice++;
                    }
                }
                ?>
            </table>
        </td>
    </tr>

</table>






