<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
 
$orig_builderId = $_POST['correct_builderId'];
$dup_builderId = $_POST['duplicate_builderId'];

$updateSQLs = array();
$restoreSQLs = array();
$orig_builder = array();
$dup_builder = array();

/*
 map to store primary key for each table
 */
$table_to_id_map = array(
        "proptiger.BANNER_ADS"        =>  array( "key" => "BANNER_ID"),
        "proptiger.BUILDER_AGREEMENT" =>  array( "key" => "ID"),
        "project.resi_project"      =>  array( "key" => "PROJECT_ID",
                                    "col"   => "BUILDER_NAME"),
        "project.builder_contact_info" => array( "key" => "ID"),
        "proptiger.ORDERS"            =>  array( "key" => "ORDER_ID"),
//        "proptiger.ORDERS1"           =>  array( "key" => "ORDER_ID"),
        "proptiger.ORDERS_ARCH"       =>  array( "key" => "ORDER_ID"),
        "project.project_plan_images"=> array( "key" => "PROJECT_PLAN_ID"),
        "proptiger.PROJ_INVOICE"      =>  array( "key" => "PROJ_INVOICE_ID"),
        "proptiger.PROJ_INVOICE_ARCH" =>  array( "key" => "PROJ_INVOICE_ID"),
        "proptiger.REVENUE_ASSURANCE" =>  array( "key" => "ID"),
        "proptiger.TCF_DETAILS"       =>  array( "key" => "ID"),
        "proptiger.CMS_BUILDER_TEMPLATE"  =>  array( "key" => "ID"),
        "proptiger.FEATURED_BUILDER_ORDER"=>  array( "key" => "FEATURED_ID"),
        "proptiger.PAYMENT_COLLECTIONS_DETAILS"=> array( "key" => "PAYMENT_ID",
                                    "col"   => "BUILDER_NAME"),
        "project.resi_project as RP JOIN proptiger.SIMILAR_BUILDER as SB ON RP.PROJECT_ID=SB.PROJECT_ID" => array("key" => "SB.ID",
                                                                                                "col" => "SB.BUILDER_NAME"),
        "project.resi_project as RP JOIN proptiger.SIMILAR_PROPERTIES as SP ON RP.PROJECT_ID=SP.PROJECT_ID" => array("key" => "SP.ID",
                                                                                                "col" => "SP.BUILDER_NAME"),
        "proptiger.FDATA_LISTING as PD JOIN project.resi_project as P ON PD.PROPTIGER_PROJECT_ID = P.PROJECT_ID" => array("key" => "PD.ID",
                                                                                                "col" => "PD.BUILDER_NAME")
 
        );

function specialCharChk($str=''){
    if($str == '') return false;
    
    if(!preg_match('/[\'^£$%&*}{@#~?><>,|=_+¬]/', $str)) return true;

    return false;
}


/*
    table       :   database table name 
    builderId   :   duplicate builder id
    return      :   all rows from table of that builder
*/ 
function getRowsByBuilderId($table, $builderId){

    global $table_to_id_map;
    $data = array();
    $colName = $table_to_id_map[$table]['key'];
    $query = "select ".$colName." as ID from ".$table." where BUILDER_ID = ".$builderId;
	$res = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($res)){
		array_push($data, $row['ID']);
	}

    return $data;
};

/*
    table           :   database table name 
    orig_builderId  :   correct builder id
    dup_builderId   :   duplicate builder id
    rowIds          :   all row ids which need to be updated/restored later
    query to update duplicate builder id, name with correct builder id,name
 */
function updateBuilderInfo($table, $orig_builderId, $dup_builderId, $rowIds){

    global $table_to_id_map;
    global $restoreSQLs;
    global $updateSQLs;
    global $orig_builder;
    global $dup_builder;

    $colName = $table_to_id_map[$table]['key'];
    //if rowIds are empty then dont do anything
    if (sizeof($rowIds) <=0)
        return;

    $ids = implode(",", $rowIds);
    $updateSQL = "update ".$table." set BUILDER_ID = ".$orig_builderId;
    if($table_to_id_map[$table]['col'])
        $updateSQL = $updateSQL.", ".$table_to_id_map[$table]['col']." = '".$orig_builder['NAME']."'";
    $updateSQL = $updateSQL." where ".$colName." in (".$ids.")";
    array_push($updateSQLs, $updateSQL);

    $restoreSQL = "update ".$table." set BUILDER_ID = ".$dup_builderId;
    if($table_to_id_map[$table]['col'])
        $restoreSQL = $restoreSQL.", ".$table_to_id_map[$table]['col']." = '".$dup_builder['NAME']."'";
    $restoreSQL = $restoreSQL." where ".$colName." in (".$ids.")";
    array_push($restoreSQLs, $restoreSQL);
};

/*
    orig_builderId  :   correct builder id
    dup_builderId   :   duplicate builder id
    fetches required information of both builders
 */
function fetchBuilderData($orig_builderId, $dup_builderId){

    global $orig_builder;
    global $dup_builder;
    $query = "select B.BUILDER_ID, B.DISPLAY_ORDER, B.URL, B.BUILDER_NAME, CONCAT(REPLACE (B.URL, '.php' , ''), '-in-', LOWER(C.LABEL), '.php') AS CITY_URL from project.resi_builder as B INNER JOIN project.resi_project AS P ON B.BUILDER_ID = P.BUILDER_ID INNER JOIN project.city AS C ON P.CITY_ID = C.CITY_ID WHERE P.BUILDER_ID IS NOT NULL AND P.ACTIVE =  '1' AND B.BUILDER_ID in (".$orig_builderId.",".$dup_builderId.")";
    $res = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($res)){
		if($row['BUILDER_ID'] == $orig_builderId){
            $obj = &$orig_builder;
        }
        if($row['BUILDER_ID'] == $dup_builderId){
            $obj = &$dup_builder;
        }
        $obj['ID'] = $row['BUILDER_ID'];
        $obj['URL'] = $row['URL'];
        $obj['NAME']= $row['BUILDER_NAME'];
        $obj['CITY_URL']= $row['CITY_URL'];
        $obj['DISPLAY_ORDER'] = $row['DISPLAY_ORDER'];
    }
};

function createUpdateSqls($fromURL, $toURL){
    global $updateSQLs; 
    global $restoreSQLs;

    //TODO:need to check this behavior
    if(!specialCharChk($fromURL) || !specialCharChk($toURL)) return;

    //to avoid cyclic redirection
    if($fromURL == $toURL) return;

    $query = "select * from project.redirect_url_map where FROM_URL ='".$fromURL."'";
    $res = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_array($res);
    if($row && $row['TO_URL'] != ''){
        //talked to chandan singh..he dont want to update if a redirect already exists....
        /*$updateSQL = "update project.redirect_url_map set TO_URL ='".$toURL."', MODIFIIED_DATE = NOW(), MODIFIIED_BY=".$_SESSION['adminId']." where FROM_URL = '".$fromURL."'";
        array_push($updateSQLs, $updateSQL);

        $restoreSQL = "update project.redirect_url_map set TO_URL ='".$row['TO_URL']."', MODIFIIED_DATE = NOW(), MODIFIIED_BY=".$_SESSION['adminId']." where FROM_URL = '".$fromURL."'";
        array_push($restoreSQLs, $restoreSQL);*/
    } else {
        $updateSQL = "insert into project.redirect_url_map (FROM_URL,TO_URL,SUBMITTED_DATE,SUBMITTED_BY) value('".$fromURL."','".$toURL."', NOW(), ".$_SESSION['adminId'].")";
        array_push($updateSQLs, $updateSQL);

        $restoreSQL = "delete from project.redirect_url_map where FROM_URL = '".$fromURL."' and TO_URL = '".$toURL."'";
        array_push($restoreSQLs, $restoreSQL);
    }
};

/*
    orig_builderId  :   correct builder id
    dup_builderId   :   duplicate builder id
    query to insert redirect builder urls and builder-city urls in REDIRECT_URL_MAP table
 */
function redirectOldBuildersURL($orig_builderId, $dup_builderId){
    global $orig_builder;
    global $dup_builder;

    createUpdateSqls($dup_builder['URL'], $orig_builder['URL']);

    createUpdateSqls($dup_builder['CITY_URL'], $orig_builder['CITY_URL']);
};

function updateSEOTags(){

    global $updateSQLs;
    global $restoreSQLs;
    global $orig_builder;
    global $dup_builder;

    $query = "select * from proptiger.PROJECT_SEO_TAGS where URL = '".$orig_builder['URL']."' or URL = '".$dup_builder['URL']."'";
    $res = mysql_query($query) or die(mysql_error());

    $count = mysql_num_rows($res);
    if($count == 1){
        $row = mysql_fetch_array($res);
        if($row['URL'] == $dup_builder['URL']){
            echo 'Error:SEO Tags for Duplicate URL exists and for Original URL not exists.';
            return false;
        }
    }

    return true;
};

/*
    builderId   :   duplicate builder id
    query to disable/enable duplicate builder
 */
function updateBuilderTable($orig_builderId, $dup_builderId){
    global $orig_builder;
    global $dup_builder;
    global $updateSQLs;
    global $restoreSQLs;

    if($dup_builder['DISPLAY_ORDER'] > $orig_builder['DISPLAY_ORDER']){
        $updateSQL = "update project.resi_builder set DISPLAY_ORDER= ".$dup_builder['DISPLAY_ORDER']." where BUILDER_ID in (".$orig_builderId.")";
        array_push($updateSQLs, $updateSQL);
        $restoreSQL = "update project.resi_builder set DISPLAY_ORDER= ".$orig_builder['DISPLAY_ORDER']." where BUILDER_ID in (".$orig_builderId.")";
        array_push($restoreSQLs, $restoreSQL);
    }

    $updateSQL = "update project.resi_builder set ACTIVE = 0 where BUILDER_ID in (".$dup_builderId.")";
    array_push($updateSQLs, $updateSQL);
   
    $restoreSQL = "update project.resi_builder set ACTIVE = 1 where BUILDER_ID in (".$dup_builderId.")";
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
    global $orig_builderId;
    global $dup_builderId;
    global $restoreSQLs;
    global $updateSQLs;
    
    //start transaction
    mysql_query("BEGIN");
     
    //save the resoteSQLs in a sql file in tmp directory
    $timestamp = time();
    $file = '/var/tmp/'.'restoreDuplicateBuilder-'.$timestamp.'-'.$dup_builderId.'-to-'.$orig_builderId.'.sql';
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
}

function main(){

    global $table_to_id_map;
    global $orig_builderId;
    global $dup_builderId;

    fetchBuilderData($orig_builderId, $dup_builderId);

    if(updateSEOTags()){
        foreach ($table_to_id_map as $table_name => $val) {

            $rows = getRowsByBuilderId($table_name, $dup_builderId);

            updateBuilderInfo($table_name, $orig_builderId, $dup_builderId, $rows);
        }

        redirectOldBuildersURL($orig_builderId, $dup_builderId);

        updateBuilderTable($orig_builderId, $dup_builderId);

        executeTnxQuery();
    }
};

    main();
?>
