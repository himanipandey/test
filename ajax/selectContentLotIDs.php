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
    'canceled' => 'Canceled',
    'revertedToVendor' => 'Active'
);

$activeLotArr = array('unassigned', 'assigned', 'completedByVendor', 'waitingApproval', 'reverted', 'revertedToVendor', 'approved');

//project locality builder city

$lotType = $_REQUEST['lotType'];
$city = $_REQUEST['city'];
$pids = $_REQUEST['pids'];

if ($lotType == '') {
    print "Please select Lot Type";
} elseif ($city == '' && $lotType != 'city' && $lotType != 'project') {
    print "Please select City";
} else {
    //project
    if ($lotType == 'project') {
        if ($city == '') {
            $all_pids = explode(',', $pids);
            $all_pids = array_map('trim', $all_pids);
            $all_pids = implode(",", $all_pids);
            $condition = " and resi_project.project_id in ($all_pids)";
        } else {
            $condition = " and city.city_id = '$city'";
        }
        //fetch all the project which are not inactive and version is cms
        $allProjectSql = "SELECT cld.lot_id, cl.lot_status, resi_project.created_at, resi_project.project_id, concat(resi_builder.builder_name, ' ', resi_project.project_name) project_name, 
                            ( LENGTH(resi_project.project_description) - LENGTH(REPLACE(resi_project.project_description, ' ', ''))+1) words FROM `resi_project`                     
                            left join locality
                               on resi_project.locality_id = locality.locality_id
                            left join suburb 
                               on locality.suburb_id = suburb.suburb_id
                            left join city
                               on suburb.city_id = city.city_id  
                            left join content_lot_details cld on cld.entity_id = resi_project.project_id
                            left join content_lots cl on cl.id = cld.lot_id
                            LEFT JOIN resi_builder  on resi_project.builder_id = resi_builder.builder_id and resi_builder.builder_status = 0 
                            left join content_lot_approved_projects clap on resi_project.project_id = clap.project_id
                             WHERE  
                                    resi_project.status in ('Active','ActiveInCms')
                                    and clap.project_id is null
                                    and  resi_project.version = 'Cms'
                                    " . $condition . "
                                    ORDER BY resi_project.project_id DESC";
        $allProjects = mysql_query($allProjectSql) or die(mysql_error());
        if (mysql_num_rows($allProjects)) {
            print ' <table id="myTable" class="tablesorter"> 
                        <thead>
                            <tr>
                                <th style="font-size: 12px" nowrap>Project Name</td>
                                <th style="font-size: 12px" nowrap>Project ID</th>
                                <th style="font-size: 12px" nowrap>Created At</th>
                                <th style="font-size: 12px" nowrap>Lot#</th>
                                <th class="filter-false" style="font-size: 12px" nowrap>
                                    Select<br/>';
            if ($city == '') {
              print  '<input type="checkbox" id="id-ALL" value="ALL" onclick="selectAllID(this.value)">';
            }
            print '</th>
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
            $valid_ids = array();
            while ($row = mysql_fetch_object($allProjects)) {
                $valid_ids[] = $row->project_id;
                if (in_array($row->lot_status, $activeLotArr)) {
                    $select_status = 'disabled = "true"';
                } else {
                    $select_status = '';
                }
                print '<tr>
                            <td>' . $row->project_name . '</td>
                            <td>' . $row->project_id . '</td>'
                        . '<td>' . $row->created_at . '</td>';
                if ($row->lot_id) {
                    print '<td>#' . $row->lot_id . ' - ' . $arrLotStatus[$row->lot_status] . '</td>';
                } else {
                    print '<td></td>';
                }
                print '<td>
                            <input class="select-pid-box" ' . $select_status . ' type="checkbox" id="id-' . $row->project_id . '" value="' . $row->project_id . '" name="' . $row->words . '" onclick="selectID(this.value)">
                        </td>
                        </tr>';
            }
            print '</tbody>
                </table>';

            //showing invalid ids
            if ($city == '') { //incase city not selected         
                $all_pids = explode(',', $all_pids);
                $invalid_pids = array_diff($all_pids, $valid_ids);
                if ($invalid_pids) {
                    echo "<b>Invalid Project IDs :</b>";
                    foreach ($invalid_pids as $pid) {
                        echo " <font color='#db0306'><b>" . $pid . "</b></font>,";
                    }
                }
            }
        } else {
            print "No Project available to select!";
        }
    } else if ($lotType == 'locality') { //locality
        //fetch all the project which are not inactive and version is cms
        $allLocSql = "SELECT cld.lot_id, cl.lot_status, locality.locality_id,locality.label, 
                        ( LENGTH(locality.description) - LENGTH(REPLACE(locality.description, ' ', ''))+1) words, count(project_id) under_projects
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
                    $select_status = 'disabled = "true"';
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
        $allBuilderSql = "select cld.lot_id, cl.lot_status, resi_builder.builder_id, resi_builder.entity, 
                        ( LENGTH(resi_builder.description) - LENGTH(REPLACE(resi_builder.description, ' ', ''))+1) words, count(project_id) under_projects
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
                    $select_status = 'disabled = "true"';
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
        $allCitySql = "SELECT cld.lot_id, cl.lot_status, city_id, label, ( LENGTH(description) - LENGTH(REPLACE(description, ' ', ''))+1) words from "
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
                    $select_status = 'disabled = "true"';
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
    var wordCount = 0;
    var lotType = "<?php echo $lotType ?>";
    $(document).ready(function () {
        if (arrIDs.length > 0) {            
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
    function selectAllID(v) {
        if ($('#id-' + v).is(':checked')) {
            index = '';
            arrIDs = [];
            wordCount = 0;
            $('.select-pid-box').each(function () {
                $(this).attr('checked', true);
                arrIDs.push($(this).val());
                wordCount = parseInt(wordCount) + parseInt($(this).attr('name'));
            });

        } else {
            $('.select-pid-box').each(function () {
                $(this).attr('checked', false);
            });
            index = '';
            arrIDs = [];
            wordCount = 0;

        }
        $('#totalArticles').html(arrIDs.length);
        $('#totalWords').html(wordCount);
        if(arrIDs.length > 0)
            $('#selArticles').val(arrIDs.join(','));
    }
</script>

