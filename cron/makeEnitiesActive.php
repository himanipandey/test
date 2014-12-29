<?php
$docroot = dirname(__FILE__) . "/../";

require_once $docroot.'dbConfig.php';

//list($builders, $localities) = getActiveBuildersAndLocalites();
$builders = getActiveBuilders();
$localities = getActiveLocalities();
$suburbs = getActiveSuburbs();
$cities = getActiveCities();

#print_r($builders);
#print_r($localities);
#print_r($suburbs);
#print_r($cities);

// making entities active.
setEntityActive($localities[0], "locality_id", "cms.locality");
setEntityActiveInCms($localities[1], "locality_id", "cms.locality");
setEntityActive($suburbs[0], "suburb_id", "cms.suburb");
setEntityActiveInCms($suburbs[1], "suburb_id", "cms.suburb");
setEntityActive($cities[0], "city_id", "cms.city");
setEntityActiveInCms($cities[1], "city_id", "cms.city");
setEntityActive($builders[0], "builder_id", "cms.resi_builder");
setEntityActiveInCms($builders[1], "builder_id", "cms.resi_builder");

// making entities in active.
setEntityInActive($localities[2], "locality_id", "cms.locality");
setEntityInActive($suburbs[2], "suburb_id", "cms.suburb");
setEntityInActive($cities[2], "city_id", "cms.city");
setEntityInActive($builders[2], "builder_id", "cms.resi_builder");

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

function setEntityActiveInCms($entityData, $columnName, $entityTable) {
    $entityStr = implode(",", $entityData);
    if (empty($entityStr)) {
      return;
    }
    $sql = "UPDATE $entityTable set status = 'ActiveInCms' WHERE $columnName IN ($entityStr)";
    $rs = doQuery($sql);
    if(empty($rs))
    {
        echo $sql."\n";
        echo mysql_error()."\n";
    }
}

function getActiveCities()
{
    $sql = "select c.city_id, sum(if(rp.status = 'Active', 1, 0)) active_count, sum(if(rp.status = 'ActiveInCms', 1, 0)) active_cms_count from cms.city c join cms.suburb s on (c.city_id = s.city_id) join cms.locality l on (s.suburb_id = l.suburb_id) join cms.resi_project rp on (l.locality_id = rp.locality_id) join cms.resi_project_options rpo on (rp.project_id = rpo.project_id) where rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual' group by c.city_id";
    $rs = doQuery($sql);
    return getActiveData($rs);
}

function getActiveSuburbs()
{
    $sql = "select s.suburb_id, sum(if(rp.status = 'Active', 1, 0)) active_count, sum(if(rp.status = 'ActiveInCms', 1, 0)) active_cms_count from cms.suburb s join cms.locality l on (s.suburb_id = l.suburb_id) join cms.resi_project rp on (l.locality_id = rp.locality_id) join cms.resi_project_options rpo on (rp.project_id = rpo.project_id) where rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual' group by s.suburb_id";
    $rs = doQuery($sql);
    return getActiveData($rs);
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
    $sql = "select rb.builder_id, sum(if(rp.status = 'Active', 1, 0)) active_count, sum(if(rp.status = 'ActiveInCms', 1, 0)) active_cms_count from cms.resi_builder rb join cms.resi_project rp on (rb.builder_id = rp.builder_id) join cms.resi_project_options rpo on (rp.project_id = rpo.project_id) where rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual' group by rb.builder_id";
    $rs = doQuery($sql);
    return getActiveData($rs);  
}

function getActiveLocalities() {
    $sql = "select l.locality_id, sum(if(rp.status = 'Active', 1, 0)) active_count, sum(if(rp.status = 'ActiveInCms', 1, 0)) active_cms_count from cms.locality l join cms.resi_project rp on (l.locality_id = rp.locality_id) join cms.resi_project_options rpo on (rp.project_id = rpo.project_id) where rp.version = 'Website' and rp.residential_flag = 'Residential' and rpo.option_category = 'Actual' group by l.locality_id";
    $rs = doQuery($sql);
    return getActiveData($rs);
}

function getActiveData($rs) {
    $activeInWebsite = array();
    $activeInCms = array();
    $completeActive = array();
    while( ($row=mysql_fetch_row($rs)) !== FALSE)
        if ($row[1] > 0) {
            $activeInWebsite[] = $row[0];
	    $completeActive[] = $row[0];
	}
	else if ($row[2] > 0) {
            $activeInCms[] = $row[0];
	    $completeActive[] = $row[0];
	}
    return array($activeInWebsite, $activeInCms, $completeActive);
}

function doQuery($qry) {
    $res = mysql_query($qry) or die(mysql_error());
    return $res;
}
?>
