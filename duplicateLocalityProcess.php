<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
 
$orig_localityId = $_POST['correct_localityId'];
$dup_localityId = $_POST['duplicate_localityId'];

$updateSQLs = array();
$restoreSQLs = array();
$orig_locality = array();
$dup_locality = array();
global $SEMANTIC_URL_CITIES;

/*
 map to store primary key for each table
 */
$table_to_id_map = array(
    "proptiger.RESI_PROJECT"    =>  array(  "key"   =>  "PROJECT_ID",
                                        "col"   =>  array('SUBURB_ID')),
    "proptiger.LOCALITY_IMAGE"  =>  array(  "key"   =>  "IMAGE_ID"),
    "proptiger.ENQUIRY"         =>  array(  "key"   =>  "ID"),
//can not be done right now as no primary key in this table    
//    "proptiger.LOCALITY_NEAR_PLACES"    =>  array(  "key"   =>  ),
    "proptiger.LOCALITY_REVIEW" =>  array(  "key"   =>  "REVIEW_ID",
                                            "col"   =>  array("OVERALL_RATING","LOCALITY","SAFETY","PUB_TRANS","SCHOOLS","REST_SHOP","PARKS","TRAFFIC","PARKING","HOSPITALS","CIVIC")),
    "proptiger.REVIEW_COMMENTS" =>  array(  "key"   =>  "COMMENT_ID"),
    "proptiger.PRICE_TREND_IMAGE"   =>  array(  "key"   =>  "ID"),
    "proptiger.RESALE_NEWLAUNCH"    =>  array(  "key"   =>  "ID"),
    "proptiger.SEO_LISTINGS"    => array(   "key"   =>  "ID")
);


function specialCharChk($str=''){                                                                                                               
    if($str == '') return false;

    if(!preg_match('/[\'^£$%&*}{@#~?><>,|=_+¬]/', $str)) return true;

    return false;
}

/*
    orig_localityId  :   correct locality id
    dup_localityId   :   duplicate locality id
    fetches required information of both localities
 */
function fetchLocalityData($orig_localityId, $dup_localityId){

    global $orig_locality;
    global $dup_locality;
    //fetch locality basic info
    $query = "select L.LOCALITY_ID, L.SUBURB_ID, L.URL, LOWER(TRIM(L.LABEL)) as LOCALITY, LOWER(C.LABEL) as CITY, L.PRIORITY from proptiger.LOCALITY as L join proptiger.RESI_PROJECT as P on L.LOCALITY_ID=P.LOCALITY_ID join proptiger.CITY as C on L.CITY_ID=C.CITY_ID WHERE L.LOCALITY_ID in (".$orig_localityId.",".$dup_localityId.")";
    $res = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($res)){
		if($row['LOCALITY_ID'] == $orig_localityId){
            $obj = &$orig_locality;
        }
        if($row['LOCALITY_ID'] == $dup_localityId){
            $obj = &$dup_locality;    
        }
        $obj['ID'] = $row['LOCALITY_ID'];
        $obj['LOCALITY'] = preg_replace( '/\s+/', '-', $row["LOCALITY"]);
        $obj['CITY'] = $row['CITY'];
        $obj['URL'] = $row['URL'];
        $obj['SUBURB_ID'] = $row['SUBURB_ID'];
        $obj['PRIORITY'] = $row['PRIORITY'];
    }

    //fetch locality rating info
    $reviewQuery = "select LR.LOCALITY_ID, AVG(NULLIF(LR.OVERALL_RATING,0)) as OVERALL_RATING, AVG(NULLIF(LR.LOCATION,0)) as LOCATION, AVG(NULLIF(LR.SAFETY,0)) as SAFETY, AVG(NULLIF(LR.PUB_TRANS,0)) as PUB_TRANS, AVG(NULLIF(LR.REST_SHOP,0)) as REST_SHOP, AVG(NULLIF(LR.SCHOOLS,0)) as SCHOOLS, AVG(NULLIF(LR.PARKS,0)) as PARKS, AVG(NULLIF(LR.TRAFFIC,0)) as TRAFFIC, AVG(NULLIF(LR.PARKING,0)) as PARKING, AVG(NULLIF(LR.HOSPITALS,0)) as HOSPITALS, AVG(NULLIF(LR.CIVIC,0)) as CIVIC from proptiger.LOCALITY_REVIEW as LR where LR.LOCALITY_ID in (".$orig_localityId.",".$dup_localityId.")";
    $res = mysql_query($reviewQuery) or die(mysql_error());
	while($row = mysql_fetch_array($res)){
		if($row['LOCALITY_ID'] == $orig_localityId){
            $obj = &$orig_locality;
        }
        if($row['LOCALITY_ID'] == $dup_localityId){
            $obj = &$dup_locality;    
        }
        $obj['OVERALL_RATING']= $row['OVERALL_RATING'];
        $obj['LOCATION']= $row['LOCATION'];
        $obj['SAFETY']= $row['SAFETY'];
        $obj['PUB_TRANS']= $row['PUB_TRANS'];
        $obj['REST_SHOP']= $row['REST_SHOP'];
        $obj['SCHOOLS']= $row['SCHOOLS'];
        $obj['PARKS']= $row['PARKS'];
        $obj['TRAFFIC']= $row['TRAFFIC'];
        $obj['PARKING']= $row['PARKING'];
        $obj['HOSPITALS']= $row['HOSPITALS'];
        $obj['CIVIC']= $row['CIVIC'];
    }
};


function updateSEOTags(){
    
    global $updateSQLs;
    global $restoreSQLs;
    global $orig_locality;
    global $dup_locality;

    //TODO:need to check if this check is required for all types of locality urls....
    $query = "select * from proptiger.PROJECT_SEO_TAGS where URL = '".$orig_locality['URL']."' or URL = '".$dup_locality['URL']."'";
    $res = mysql_query($query) or die(mysql_error());

    $count = mysql_num_rows($res);
    if($count == 1){
        $row = mysql_fetch_array($res);
        if($row['URL'] == $dup_locality['URL']){
            echo 'Error:SEO Tags for Duplicate URL exists and for Original URL not exists.';
            return false;
        }       
    }       

    return true;
};


/*
    table       :   database table name 
    localityId   :   duplicate locality id
    return      :   all rows from table of that locality
*/ 
function getRowsByLocalityId($table, $localityId){

    global $table_to_id_map;
    $data = array();
    $colName = $table_to_id_map[$table]['key'];
    $query = "select ".$colName." as ID from ".$table." where LOCALITY_ID = ".$localityId;
    $res = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_array($res)){
        array_push($data, $row['ID']);
    }
    
    return $data;
};

/*                                                                                                                                                    
    table           :   database table name 
    orig_localityId  :   correct locality id
    dup_localityId   :   duplicate locality id
    rowIds          :   all row ids which need to be updated/restored later
    query to update duplicate locality id, name with correct locality id,name
*/
function updateLocalityInfo($table, $orig_localityId, $dup_localityId, $rowIds){
                         
    global $table_to_id_map;
    global $restoreSQLs;
    global $updateSQLs;
    global $orig_locality;
    global $dup_locality;
                                             
    $colName = $table_to_id_map[$table]['key'];
    //if rowIds are empty then dont do anything
    if (sizeof($rowIds) <=0)
        return;
    
    $ids = implode(",", $rowIds);
    $updateSQL = "update ".$table." set LOCALITY_ID = ".$orig_localityId;
    if($table_to_id_map[$table]['col']){
        foreach ($table_to_id_map[$table]['col'] as $val) {
            $updateSQL = $updateSQL.", ".$val." = '".$orig_locality[$val]."'";
        }
    }
    $updateSQL = $updateSQL." where ".$colName." in (".$ids.")";
    array_push($updateSQLs, $updateSQL);
                                                                                                     
    $restoreSQL = "update ".$table." set LOCALITY_ID = ".$dup_localityId;
    if($table_to_id_map[$table]['col']){
         foreach ($table_to_id_map[$table]['col'] as $val) {
            $restoreSQL = $restoreSQL.", ".$val." = '".$dup_locality[$val]."'";
        }
    }
    $restoreSQL = $restoreSQL." where ".$colName." in (".$ids.")";
    array_push($restoreSQLs, $restoreSQL);

};

function createUpdateSqls($fromURL, $toURL){
    global $updateSQLs;
    global $restoreSQLs;
   
    //TODO:need to verify this logic
    if(!specialCharChk($fromURL) || !specialCharChk($toURL)) return;

    //to avoid cyclic redirection
    if($fromURL == $toURL) return;

    $query = "select * from proptiger.REDIRECT_URL_MAP where FROM_URL ='".$fromURL."'";
    $res = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_array($res);                                                                                                               
    if($row && $row['TO_URL'] != ''){
        //talked to chandan singh..do not update if a redirect exists coz existing one is correct..
        /*$updateSQL = "update proptiger.REDIRECT_URL_MAP set TO_URL ='".$toURL."', MODIFIIED_DATE = NOW(), MODIFIIED_BY=".$_SESSION['adminId']." where FROM_URL = '".$fromURL."'";
        array_push($updateSQLs, $updateSQL);

        $restoreSQL = "update proptiger.REDIRECT_URL_MAP set TO_URL ='".$row['TO_URL']."', MODIFIIED_DATE = NOW(), MODIFIIED_BY=".$_SESSION['adminId']." where FROM_URL = '".$fromURL."'";
        array_push($restoreSQLs, $restoreSQL);*/
    } else {
        $updateSQL = "insert into proptiger.REDIRECT_URL_MAP (FROM_URL,TO_URL,SUBMITTED_DATE,SUBMITTED_BY) value('".$fromURL."','".$toURL."', NOW(), ".$_SESSION['adminId'].")";
        array_push($updateSQLs, $updateSQL);

        $restoreSQL = "delete from proptiger.REDIRECT_URL_MAP where FROM_URL = '".$fromURL."' and TO_URL = '".$toURL."'";
        array_push($restoreSQLs, $restoreSQL);
    }
};

/*
    orig_localityId  :   correct locality id
    dup_localityId   :   duplicate locality id
    query to insert redirect locality, locality-city, locality-city-amenities, locality-city-localities 
        city-locality-bedroom, city-locality-bedroom-budget and city-locality-budget urls in REDIRECT_URL_MAP table
*/
function redirectOldLocalitiesURL($orig_localityId, $dup_localityId){
                 
    global $orig_locality;
    global $dup_locality;
    global $SEMANTIC_URL_CITIES;
    $budjetArr = array("5-25"=> "5 Lacs - 25 Lacs", "25-40" =>"25 Lacs - 40 Lacs","40-70" =>"40 Lacs - 70 Lacs","70-150"=> "70 Lacs - 1.5 Cr","150-500" =>"1.5 Cr - 5 Cr");

    $diffUrlArr = array(
                    'schools',
                    'restaurants',
                    'hospitals',
                    'banks',
                    'atms',
                    'petrol-pumps'
                );  

    createUpdateSqls($dup_locality['URL'], $orig_locality['URL']);

    //  add overview url
    $fromURL = "real-estate/".str_replace( ' ', '-', $dup_locality['LOCALITY'] )."-".$dup_locality['CITY'];
    $toURL = "real-estate/".str_replace( ' ', '-', $orig_locality['LOCALITY'] )."-".$orig_locality['CITY'];
    createUpdateSqls($fromURL, $toURL);

    foreach( $diffUrlArr AS $type ) { 
        //  add amenities url
        $fromURL = "real-estate/".str_replace( ' ', '-', $dup_locality['LOCALITY'] )."-".$dup_locality['CITY']."/".$type;
        $toURL = "real-estate/".str_replace( ' ', '-', $orig_locality['LOCALITY'] )."-".$orig_locality['CITY']."/".$type;
        createUpdateSqls($fromURL, $toURL);
    }   
    
    //  add all locality url for city
    $fromURL = "real-estate/".str_replace( ' ', '-', $dup_locality['LOCALITY'] )."-".$dup_locality['CITY']."/localities";
    $toURL = "real-estate/".str_replace( ' ', '-', $orig_locality['LOCALITY'] )."-".$orig_locality['CITY']."/localities";
    createUpdateSqls($fromURL, $toURL);

    $query = "SELECT DISTINCT TRIM(RPT.BEDROOMS) as BEDROOMS, L.LOCALITY_ID, RP.CITY_ID, LOWER(TRIM(L.LABEL)) AS LOCALITY,LOWER(TRIM(C.LABEL)) AS CITY FROM proptiger.RESI_PROJECT RP INNER JOIN proptiger.LOCALITY L ON (RP.LOCALITY_ID = L.LOCALITY_ID) INNER JOIN proptiger.CITY C ON (RP.CITY_ID = C.CITY_ID) INNER JOIN proptiger.RESI_PROJECT_TYPES RPT ON (RPT.PROJECT_ID = RP.PROJECT_ID) WHERE L.LOCALITY_ID in (".$dup_localityId.") AND RPT.BEDROOMS!=0 AND L.ACTIVE=1 AND C.ACTIVE=1";
    $resultSet = mysql_query($query);

    while($record = mysql_fetch_assoc($resultSet)){
       
        $record["LOCALITY-"] = preg_replace( '/\s+/', '-', trim($record["LOCALITY"]));
        array_push($bedRoomArray, $record['BEDROOMS']);
        if(in_array($record['CITY'], $SEMANTIC_URL_CITIES)){
            $fromURL = 'property/' . $record['CITY'] . '/' . $dup_locality['LOCALITY'] . '/' . $record['BEDROOMS'] . '-bhk';
            $toURL = 'property/' . $record['CITY'] . '/' . $orig_locality['LOCALITY'] . '/' . $record['BEDROOMS'] . '-bhk';
        }
        else {
            $fromURL = $record['BEDROOMS'].'bhk-apartments-flats-'.$dup_locality['LOCALITY'].'-'.$record["CITY"].'.php';
            $toURL = $record['BEDROOMS'].'bhk-apartments-flats-'.$orig_locality['LOCALITY'].'-'.$record["CITY"].'.php';
        }
        createUpdateSqls($fromURL, $toURL);

        foreach($budjetArr as $key => $value){
            $budgetArr = explode('-', $key);
            if(in_array($record['CITY'], $SEMANTIC_URL_CITIES)){
                $fromURL = 'property/' . $record['CITY'] . '/' . strtolower($dup_locality['LOCALITY']) . '/' . $record['BEDROOMS'] . '-bhk' . '/budget-' . trim($budgetArr[0] . '-' . trim($budgetArr[1]));
                $toURL = 'property/' . $record['CITY'] . '/' . strtolower($orig_locality['LOCALITY']) . '/' . $record['BEDROOMS'] . '-bhk' . '/budget-' . trim($budgetArr[0] . '-' . trim($budgetArr[1]));
            } else {
                $fromURL = strtolower($record['BEDROOMS']."bhk-".$key."-lacs-apartments-flats-".$dup_locality['LOCALITY']."-".$record['CITY'].".php");
                $toURL = strtolower($record['BEDROOMS']."bhk-".$key."-lacs-apartments-flats-".$orig_locality['LOCALITY']."-".$record['CITY'].".php");
            }
            createUpdateSqls($fromURL, $toURL);

            if(in_array($record['CITY'], $SEMANTIC_URL_CITIES)){
                $fromURL = 'property/' . $record['CITY'] . '/' . $dup_locality['LOCALITY'] . '/budget-' . trim($budgetArr[0]. '-' . trim($budgetArr[1]));
                $toURL = 'property/' . $record['CITY'] . '/' . $orig_locality['LOCALITY'] . '/budget-' . trim($budgetArr[0]. '-' .     trim($budgetArr[1]));
            } else {
                $fromURL = strtolower($key."-lacs-apartments-flats-".$dup_locality['LOCALITY']."-".$record['CITY'].".php");
                $toURL = strtolower($key."-lacs-apartments-flats-".$orig_locality['LOCALITY']."-".$record['CITY'].".php");
            }
            createUpdateSqls($fromURL, $toURL);
        }
    }

};

/*
    localityId   :   duplicate locality id
    query to disable/enable duplicate locality
*/
function updateLocalityTable($orig_localityId, $dup_localityId){
    global $dup_locality;
    global $orig_locality;
    global $updateSQLs;
    global $restoreSQLs;

    if($dup_locality['PRIORITY'] > $orig_locality['PRIORITY']){                                                                               
        $updateSQL = "update proptiger.LOCALITY set PRIORITY= ".$dup_locality['PRIORITY']." where LOCALITY_ID in (".$orig_localityId.")";
        array_push($updateSQLs, $updateSQL);
        $restoreSQL = "update proptiger.LOCALITY set PRIORITY= ".$orig_locality['PRIORITY']." where LOCALITY_ID in (".$orig_localityId.")";
        array_push($restoreSQLs, $restoreSQL);
    }   

    $updateSQL = "update proptiger.LOCALITY set ACTIVE = 0 where LOCALITY_ID in (".$dup_localityId.")";
    array_push($updateSQLs, $updateSQL);
                                 
    $restoreSQL = "update proptiger.LOCALITY set ACTIVE = 1 where LOCALITY_ID in (".$dup_localityId.")";
    array_push($restoreSQLs,$restoreSQL);
                                         
    //echo "<pre>";
    //echo json_encode($updateSQLs);
    //echo "</pre><pre>";
    //echo json_encode($restoreSQLs);
    //echo "</pre>";
};

/*
    execute all update queries in transaction
    create a restore file in /var/tmp to undo the changes if required
*/
function executeTnxQuery(){

    global $orig_localityId;
    global $dup_localityId;
    global $restoreSQLs;
    global $updateSQLs;
                    
    //start transaction
    mysql_query("BEGIN");
                                 
    //save the resoteSQLs in a sql file in tmp directory
    $timestamp = time();
    $file = '/var/tmp/'.'restoreDuplicateLocality-'.$timestamp.'-'.$dup_localityId.'-to-'.$orig_localityId.'.sql';
    $handle = fopen($file, 'w');

    if(!$handle){
        die('Error: failed to open file: '.$file);
        mysql_query("ROLLBACK");
    }

    $data = implode(";", $restoreSQLs).';';
    fwrite($handle, $data);

    //run the updateSQLs
    foreach($updateSQLs as $key => $sql){
        $res = mysql_query($sql);
        if(!$res){
            mysql_query("ROLLBACK");
            echo "Error: Failed to run sql :".$sql;
            return;
        }
    }
    
    mysql_query("COMMIT");
    //end transaction

    //close file handler
    fclose($handle);
    echo "Updated duplicate builder ids successfully.";
};

function main(){

    global $table_to_id_map;
    global $orig_localityId;
    global $dup_localityId;
    
    fetchLocalityData($orig_localityId, $dup_localityId);

    if(updateSEOTags()){
        foreach ($table_to_id_map as $table_name => $val) {

            $rows = getRowsByLocalityId($table_name, $dup_localityId);

            updateLocalityInfo($table_name, $orig_localityId, $dup_localityId, $rows);
        }

        redirectOldLocalitiesURL($orig_localityId, $dup_localityId);

        updateLocalityTable($orig_localityId, $dup_localityId);

        executeTnxQuery();
    }
};

    main();
?>
