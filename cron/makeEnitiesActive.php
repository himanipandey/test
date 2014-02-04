<?php
$docroot = dirname(__FILE__) . "/../";
require_once $docroot.'dbConfig.php';
list($builders, $localities) = getActiveBuildersAndLocalites();
$suburbs = getActiveSuburbs($localities);
$cities = getActiveCities($suburbs);

print_r($builders);
print_r($localities);
print_r($suburbs);
print_r($cities);

// making entities active.
setEntityActive($localities, "locality_id", "cms.locality");
setEntityActive($suburbs, "suburb_id", "cms.suburb");
setEntityActive($cities, "city_id", "cms.city");

// making entities in active.
setEntityInActive($localities, "locality_id", "cms.locality");
setEntityInActive($suburbs, "suburb_id", "cms.suburb");
setEntityInActive($cities, "city_id", "cms.city");

function setEntityInActive($entityData, $columnName, $entityTable)
{
    $entityStr = implode(",", $entityData);
    
    $sql = <<<QRY
        UPDATE $entityTable set status = "Inactive" WHERE $columnName NOT IN ($entityStr)
QRY;
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
    
    $sql = <<<QRY
        UPDATE $entityTable set status = "Active" WHERE $columnName IN ($entityStr)
QRY;
    $rs = doQuery($sql);

    if(empty($rs))
    {
        echo $sql."\n";
        echo mysql_error()."\n";
    }
}

function getActiveCities($suburbs)
{
    $suburbIdStr = implode(",", $suburbs);

    $sql = <<<QRY
        SELECT distinct(city_id) FROM cms.suburb WHERE suburb_id IN ($suburbIdStr)
QRY;
    $rs = doQuery($sql);

    $cities = array();
    while( ($row=mysql_fetch_row($rs)) !== FALSE)
        $cities[] = $row[0];

    return $cities;
}

function getActiveSuburbs($localities)
{
    $localityIdStr = implode(",", $localities);
    $sql = <<<QRY
        SELECT distinct(suburb_id) FROM cms.locality WHERE locality_id in ($localityIdStr)
QRY;
    $rs = doQuery($sql);

    $suburbs = array();
    while( ($row=mysql_fetch_row($rs)) !== FALSE)
        $suburbs[] = $row[0];

    return $suburbs;
}

function getActiveBuildersAndLocalites()
{
    $sql = <<<QRY
        SELECT project_id, locality_id, builder_id FROM cms.resi_project
            where status in ("Active", "ActiveInCms")
QRY;
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

function doQuery($sql) {
    $res = mysql_query($sql) or die(mysql_error());
    return $res;
}
?>
