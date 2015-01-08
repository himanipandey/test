<?php
$docroot = dirname(__FILE__) . "/../";

require_once $docroot.'dbConfig.php';

//list($builders, $localities) = getActiveBuildersAndLocalites();
$builders = getActiveBuilders();
$localities = getActiveLocalities();
$suburbs = getActiveSuburbs();
$cities = getActiveCities();

print_r($builders);
print_r($localities);
print_r($suburbs);
print_r($cities);

// making entities active.
setEntityActive($localities, "locality_id", "cms.locality");
setEntityActive($suburbs, "suburb_id", "cms.suburb");
setEntityActive($cities, "city_id", "cms.city");
setEntityActive($builders, "builder_id", "cms.resi_builder");

// making entities in active.
setEntityInActive($localities, "locality_id", "cms.locality");
setEntityInActive($suburbs, "suburb_id", "cms.suburb");
setEntityInActive($cities, "city_id", "cms.city");
setEntityActive($builders, "builder_id", "cms.resi_builder");

function setEntityInActive($entityData, $columnName, $entityTable)
{
    $entityStr = implode(",", $entityData);
        
    $sql = "UPDATE $entityTable set status = 'Inactive' WHERE $columnName NOT IN ($entityStr)";
    $rs = doQuery($sql);

    if(empty($rs))
    {
        echo $sql."\n";
        echo mysql_error()."\n";
    }
}

function setEntityActive($entityData, $columnName, $entityTable)
{
    $entityStr = implode(",", $entityData);
    
    $sql = "UPDATE $entityTable set status = 'Active' WHERE $columnName IN ($entityStr)";
    $rs = doQuery($sql);

    if(empty($rs))
    {
        echo $sql."\n";
        echo mysql_error()."\n";
    }
}

function getActiveCities()
{
    $sql = "select distinct c.city_id from cms.city c join cms.suburb s on (c.city_id = s.city_id) join cms.locality l on (s.suburb_id = l.suburb_id) join cms.resi_project rp on (l.locality_id = rp.locality_id) join resi_project_options rpo on (rp.project_id = rpo.project_id) where s.status = 'Active' and l.status = 'Active' and rp.status = 'Active' and rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual'";
    $rs = doQuery($sql);

    $cities = array();
    while( ($row=mysql_fetch_row($rs)) !== FALSE)
        $cities[] = $row[0];

    return $cities;
}

function getActiveSuburbs()
{
    $sql = "select distinct s.suburb_id from cms.suburb s join cms.locality l on (s.suburb_id = l.suburb_id) join cms.resi_project rp on (l.locality_id = rp.locality_id) join resi_project_options rpo on (rp.project_id = rpo.project_id) where s.status = 'Active' and l.status = 'Active' and rp.status = 'Active' and rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual'";
    $rs = doQuery($sql);

    $suburbs = array();
    while( ($row=mysql_fetch_row($rs)) !== FALSE)
        $suburbs[] = $row[0];

    return $suburbs;
}

function getActiveBuildersAndLocalites()
{
    $sql = "SELECT project_id, locality_id, builder_id FROM cms.resi_project
            where status in ('Active', 'ActiveInCms')";
    $rs = doQuery($sql);

    $builders = array();
    $localities = array();
    while( ($row=mysql_fetch_row($rs)) !== FALSE)
    {
        $builderId = $row[2];
        $localityId = $row[1];
        $builders[$builderId] = 1;
        $localities[$localityId] = 1;
    }

    return array(array_keys($builders), array_keys($localities) );
}

function getActiveBuilders(){
    $sql = "select distinct rb.builder_id from cms.resi_builder rb join resi_project rp on (rp.builder_id = rb.builder_id) join resi_project_options rpo on (rp.project_id = rpo.project_id) where rp.status = 'Active' and rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual'";
    $rs = doQuery($sql);
    $builders = array();
    while(($row = mysql_fetch_row($rs)) !== FALSE ) {
        $builders[] = $row[0];
    }
    return $builders;
}

function getActiveLocalities() {
    $sql = "select distinct l.locality_id from cms.locality l join cms.resi_project rp on (l.locality_id = rp.locality_id) join cms.resi_project_options rpo on (rp.project_id = rpo.project_id) where l.status = 'Active' and rp.status = 'Active' and rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual'";
    $rs = doQuery($sql);
    $localities = array();
    while(($row = mysql_fetch_row($rs)) !== FALSE ) {
	$localities[] = $row[0];
    }
    return $localities;    
}


function doQuery($qry) {
    $res = mysql_query($qry) or die(mysql_error());
    return $res;
}
?>
