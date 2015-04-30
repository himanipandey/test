<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$arrLotStatus = array(
    'unassigned' => 'Active',
    'assigned' => 'Active',
    'completedByVendor' => 'Active',
    'waitingApproval' => 'Active',
    'approved' => 'Approved',
    'reverted' => 'Active',
    'canceled' => 'Canceled'
);

$activeLotArr = array('unassigned', 'assigned', 'completedByVendor', 'waitingApproval', 'reverted');

//project locality builder city

$lotType = $_REQUEST['lotType'];
$city = $_REQUEST['city'];

if ($lotType == '') {
    print "Please select Lot Type";
} elseif ($city == '' && $lotType != 'city') {
    print "Please select City";
} else {
    //print $lotType . " - " . $city;
    //project
    if ($lotType == 'project') {
        //fetch all the project which are not inactive and version is cms
        $allProjectSql = "SELECT cld.lot_id, cl.lot_status, resi_project.project_id, resi_project.project_name, length(resi_project.project_description) words FROM `resi_project`                     
                            left join locality
                               on resi_project.locality_id = locality.locality_id
                            left join suburb 
                               on locality.suburb_id = suburb.suburb_id
                            left join city
                               on suburb.city_id = city.city_id  
                            left join content_lot_details cld on cld.entity_id = resi_project.project_id
                            left join content_lots cl on cl.id = cld.lot_id
                             WHERE city.city_id in ($city)  
                                    and resi_project.status in ('Active','ActiveInCms')
                                    and  resi_project.version = 'Cms'";
        $allProjects = mysql_query($allProjectSql) or die(mysql_error());
        if (mysql_num_rows($allProjects)) {
            print ' <table id="myTable" class="tablesorter"> 
                        <thead>
                            <tr>
                                <th style="font-size: 12px" nowrap>Project Name</td>
                                <th style="font-size: 12px" nowrap>Project ID</th>
                                <th style="font-size: 12px" nowrap>Lot#</th>
                                <th class="filter-false" style="font-size: 12px" nowrap>Select</th>
                            </tr>
                        </thead>
                        ';
            print ' <tfoot>

                    <tr>
                        <th colspan="21" class="pager form-horizontal" style="font-size:12px;">

                            <button class="btn first"><i class="icon-step-backward"></i></button>
                            <button class="btn prev"><i class="icon-arrow-left"></i></button>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <button class="btn next"><i class="icon-arrow-right"></i></button>
                            <button class="btn last"><i class="icon-step-forward"></i></button>
                            <select class="pagesize input-mini" title="Select page size">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option  value="100">100</option>
                            </select>
                            <select class="pagenum input-mini" title="Select page number"></select>
                        </th>
                    </tr>
                </tfoot>';
            print '<tbody>';
            while ($row = mysql_fetch_object($allProjects)) {
                if (in_array($row->lot_status, $activeLotArr)) {
                    $select_status = 'readonly = "true"';
                } else {
                    $select_status = '';
                }
                print '<tr>
                            <td>' . $row->project_name . '</td>
                            <td>' . $row->project_id . '</td>';
                if ($row->lot_id) {
                    print '<td>#' . $row->lot_id . ' - ' . $arrLotStatus[$row->lot_status] . '</td>';
                } else {
                    print '<td></td>';
                }
                print '<td>
                            <input ' . $select_status . ' type="checkbox" id="id-' . $row->project_id . '" value="' . $row->project_id . '" name="' . $row->words . '" onclick="selectID(this.value)">
                        </td>
                        </tr>';
            }
            print '</tbody>
                </table>';
        } else {
            print "No Project available to select!";
        }
    } else if ($lotType == 'locality') { //locality
        //fetch all the project which are not inactive and version is cms
        $allLocSql = "SELECT cld.lot_id, cl.lot_status, locality.locality_id,locality.label, length(locality.description) words, count(project_id) under_projects
                        FROM `locality` INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)
                        INNER JOIN city c ON(a.city_id = c.city_id) 
                        LEFT JOIN resi_project rp on rp.locality_id = locality.locality_id 
                                            and rp.status in ('Active','ActiveInCms')
                                            and rp.version = 'Cms'
                        left join content_lot_details cld on cld.entity_id = locality.locality_id
                        left join content_lots cl on cl.id = cld.lot_id
                        WHERE a.city_id = '$city' and a.status = 'Active' 
                            and locality.status = 'Active'         
                            group by locality_id
                            order by under_projects desc";

        $allLocs = mysql_query($allLocSql) or die(mysql_error());
        if (mysql_num_rows($allLocs)) {
            print ' <table id="myTable" class="tablesorter"> 
                        <thead>
                            <tr>
                                <th style="font-size: 12px" nowrap>Locality Name</td>
                                <th style="font-size: 12px" nowrap>Locality ID</th>
                                <th style="font-size: 12px" nowrap>Lot#</th>
                                <th class="filter-false" style="font-size: 12px" nowrap>Select</th>
                            </tr>
                        </thead>
                        ';
            print ' <tfoot>

                    <tr>
                        <th colspan="21" class="pager form-horizontal" style="font-size:12px;">

                            <button class="btn first"><i class="icon-step-backward"></i></button>
                            <button class="btn prev"><i class="icon-arrow-left"></i></button>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <button class="btn next"><i class="icon-arrow-right"></i></button>
                            <button class="btn last"><i class="icon-step-forward"></i></button>
                            <select class="pagesize input-mini" title="Select page size">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option  value="100">100</option>
                            </select>
                            <select class="pagenum input-mini" title="Select page number"></select>
                        </th>
                    </tr>
                </tfoot>';
            print '<tbody>';
            while ($row = mysql_fetch_object($allLocs)) {
                if (in_array($row->lot_status, $activeLotArr)) {
                    $select_status = 'readonly = "true"';
                } else {
                    $select_status = '';
                }
                print '<tr>
                            <td>' . $row->label . '</td>
                            <td>' . $row->locality_id . '</td>';
                if ($row->lot_id) {
                    print '<td>#' . $row->lot_id . ' - ' . $arrLotStatus[$row->lot_status] . '</td>';
                } else {
                    print '<td></td>';
                }
                print '  <td>
                                <input ' . $select_status . ' type="checkbox" id="id-' . $row->locality_id . '" value="' . $row->locality_id . '" name="' . $row->words . '" onclick="selectID(this.value)">
                            </td>
                        </tr>';
            }
            print '</tbody>
                </table>';
        } else {
            print "No Locality available to select!";
        }
    } else if ($lotType == 'builder') { //builder        
        //fetch all the project which are not inactive and version is cms
        $allBuilderSql = "select cld.lot_id, cl.lot_status, resi_builder.builder_id, resi_builder.entity, length(resi_builder.description) words, count(project_id) under_projects
                        FROM resi_builder 
                        LEFT JOIN resi_project rp on rp.builder_id = resi_builder.builder_id 
                                        and rp.status in ('Active','ActiveInCms')
                                        and rp.version = 'Cms'
                        left join content_lot_details cld on cld.entity_id = resi_builder.builder_id
                        left join content_lots cl on cl.id = cld.lot_id
                        where resi_builder.builder_status = 0 
                            and resi_builder.city_id = '$city'
                            group by builder_id
                            order by under_projects desc";

        $allBuilders = mysql_query($allBuilderSql) or die(mysql_error());
        if (mysql_num_rows($allBuilders)) {
            print ' <table id="myTable" class="tablesorter"> 
                        <thead>
                            <tr>
                                <th style="font-size: 12px" nowrap>Builder Name</td>
                                <th style="font-size: 12px" nowrap>Builder ID</th>
                                <th style="font-size: 12px" nowrap>Lot#</th>
                                <th class="filter-false" style="font-size: 12px" nowrap>Select</th>
                            </tr>
                        </thead>
                        ';
            print ' <tfoot>

                    <tr>
                        <th colspan="21" class="pager form-horizontal" style="font-size:12px;">

                            <button class="btn first"><i class="icon-step-backward"></i></button>
                            <button class="btn prev"><i class="icon-arrow-left"></i></button>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <button class="btn next"><i class="icon-arrow-right"></i></button>
                            <button class="btn last"><i class="icon-step-forward"></i></button>
                            <select class="pagesize input-mini" title="Select page size">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option  value="100">100</option>
                            </select>
                            <select class="pagenum input-mini" title="Select page number"></select>
                        </th>
                    </tr>
                </tfoot>';
            print '<tbody>';
            while ($row = mysql_fetch_object($allBuilders)) {
                if (in_array($row->lot_status, $activeLotArr)) {
                    $select_status = 'readonly = "true"';
                } else {
                    $select_status = '';
                }
                print '<tr>
                            <td>' . $row->entity . '</td>
                            <td>' . $row->builder_id . '</td>';
                if ($row->lot_id) {
                    print '<td>#' . $row->lot_id . ' - ' . $arrLotStatus[$row->lot_status] . '</td>';
                } else {
                    print '<td></td>';
                }
                print '<td>
                                <input ' . $select_status . ' type="checkbox" id="id-' . $row->builder_id . '" value="' . $row->builder_id . '" name="' . $row->words . '" onclick="selectID(this.value)">
                            </td>
                        </tr>';
            }
            print '</tbody>
                </table>';
        } else {
            print "No Builder available to select!";
        }
    } else if ($lotType == 'city') { //city
        //fetch all the project which are not inactive and version is cms
        $allCitySql = "SELECT cld.lot_id, cl.lot_status, city_id, label, length(description) words from "
                . " city "
                . " left join content_lot_details cld on cld.entity_id = city.city_id
                        left join content_lots cl on cl.id = cld.lot_id"
                . " where city.status = 'Active' order by label asc";
        $allCities = mysql_query($allCitySql) or die(mysql_error());
        if (mysql_num_rows($allCities)) {
            print ' <table id="myTable" class="tablesorter"> 
                        <thead>
                            <tr>
                                <th style="font-size: 12px" nowrap>City Name</td>
                                <th style="font-size: 12px" nowrap>City ID</th>
                                <th style="font-size: 12px" nowrap>Lot#</th>
                                <th class="filter-false" style="font-size: 12px" nowrap>Select</th>
                            </tr>
                        </thead>
                        ';
            print ' <tfoot>

                    <tr>
                        <th colspan="21" class="pager form-horizontal" style="font-size:12px;">

                            <button class="btn first"><i class="icon-step-backward"></i></button>
                            <button class="btn prev"><i class="icon-arrow-left"></i></button>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <button class="btn next"><i class="icon-arrow-right"></i></button>
                            <button class="btn last"><i class="icon-step-forward"></i></button>
                            <select class="pagesize input-mini" title="Select page size">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option  value="100">100</option>
                            </select>
                            <select class="pagenum input-mini" title="Select page number"></select>
                        </th>
                    </tr>
                </tfoot>';
            print '<tbody>';
            while ($row = mysql_fetch_object($allCities)) {
                if (in_array($row->lot_status, $activeLotArr)) {
                    $select_status = 'readonly = "true"';
                } else {
                    $select_status = '';
                }
                print '<tr>
                            <td>' . $row->label . '</td>
                            <td>' . $row->city_id . '</td>';
                if ($row->lot_id) {
                    print '<td>#' . $row->lot_id . ' - ' . $arrLotStatus[$row->lot_status] . '</td>';
                } else {
                    print '<td></td>';
                }
                print ' <td>
                                <input ' . $select_status . ' type="checkbox" id="id-' . $row->city_id . '" value="' . $row->city_id . '" name="' . $row->words . '" onclick="selectID(this.value)">
                            </td>
                        </tr>';
            }
            print '</tbody>
                </table>';
        } else {
            print "No City available to select!";
        }
    }
}
?>
<script type="text/javascript">
    var arrIDs = [];
    var wordCount = 0;
    $(document).ready(function () {
        if ($('#selArticles').val().trim() != '') {
            arrIDs = $('#selArticles').val().split(',');
            $.each(arrIDs, function (i, v) {
                $('#id-' + v).prop('checked', true);
                wordCount = parseInt(wordCount) + parseInt($('#id-' + v).attr('name'));
            });

        }

    });
    function selectID(v) {
        if ($('#id-' + v).is(':checked')) {
            arrIDs.push(v);
            wordCount = parseInt(wordCount) + parseInt($('#id-' + v).attr('name'));
        } else {
            var index = arrIDs.indexOf(v);
            arrIDs.splice(index, 1);
            wordCount = parseInt(wordCount) - parseInt($('#id-' + v).attr('name'));
        }
        $('#totalArticles').html(arrIDs.length);
        $('#totalWords').html(wordCount);
        $('#selArticles').val(arrIDs.join(','));
    }
</script>

