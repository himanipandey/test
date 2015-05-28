<?php

require_once (dirname(__FILE__) . '/../modelsConfig.php');

function put2DArrayIntoTable($data){
	$table = "<table border='1'>";
	$table .= getTableRowFromArray(array_keys($data[0]));
	foreach ($data as $row) {
		$table .= getTableRowFromArray($row);
	}
	$table .= "</table>";
	return $table;	
}

function getTableRowFromArray($row){
	$return = "<tr>";
	foreach ($row as $value) {
		$return .= "<td>$value</td>";
	}
	$return .= "</tr>";
	return $return;
}

function putResultsInFile($data, $filePath){
	$handle = fopen($filePath, 'a+');
	fputcsv($handle, array_keys($data[0]), "\t");
	foreach ($data as $value) {
		fputcsv($handle, $value, "\t");
	}
	fclose($handle);
}

function firstDayOf($period, $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('financial_year', 'year', 'quarter', 'month', 'week', 'half_year');

    if(is_string($date)){
        $date;
        $date = new DateTime($date);
    }
    
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period) {
        case 'financial_year':
            $newDate->modify('first day of april ' . $newDate->format('Y'));
            break;
        case 'year':
            $newDate->modify('first day of january ' . $newDate->format('Y'));
            break;
        case 'half_year':
            $month = $newDate->format('n') ;
            
            if($month < 6){
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } else {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            }
            break;
        case 'quarter':
            $month = $newDate->format('n') ;
 
            if ($month < 4) {
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('first day of april ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('first day of october ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('first day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
            break;
    }
    return $newDate;
}


function lastDayOf($period, $date = null)
{   
	$period = strtolower($period);
	$validPeriods = array('financial_year', 'year', 'quarter', 'month', 'week', 'half_year');

	if(is_string($date)){
		$date;
		$date = new DateTime($date);
	}

	if ( ! in_array($period, $validPeriods))
		throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

	$newDate = ($date === null) ? new DateTime() : clone $date;

	switch ($period) {
		case 'financial_year':
			$newDate->modify('last day of march ' . (($newDate->format('Y'))+1));
			break;
			
		case 'year':
			$newDate->modify('last day of december ' . $newDate->format('Y'));

		case 'half_year':
			$months_end = array('June','December');
			$month = $newDate->format('n') ;
			$month = ceil($month/6);
			$m_end = $months_end[$month-1];
			$newDate->modify('last day of '.$m_end . $newDate->format('Y'));
			break;
			
		case 'quarter':
			$months_end = array('March','June','September','December');
			$month = $newDate->format('n') ;
			$month = ceil($month/3);
			$m_end = $months_end[$month-1];
			$newDate->modify('last day of '.$m_end. $newDate->format('Y'));
				
			break;
			
		case 'month':
			$newDate->modify('last day of this month');
			break;
			
		case 'week':
			$newDate->modify(($newDate->format('w') === '0') ? 'sunday last week' : 'sunday this week');
			break;
	}

	return $newDate;
}

function getCSVRowFromArray($entry){
    return str_replace(
            CSV_FIELD_DELIMITER.CSV_LINE_DELIMITER,
            CSV_FIELD_DELIMITER.'NULL'.CSV_LINE_DELIMITER,
            str_replace(
                    CSV_FIELD_DELIMITER.CSV_FIELD_DELIMITER,
                    CSV_FIELD_DELIMITER.'NULL'.CSV_FIELD_DELIMITER,
                    str_replace(
                            CSV_FIELD_DELIMITER.CSV_FIELD_DELIMITER,
                            CSV_FIELD_DELIMITER.'NULL'.CSV_FIELD_DELIMITER,
                            implode(CSV_FIELD_DELIMITER, $entry)
                    )
            ).CSV_LINE_DELIMITER
    );
}

function importTableFromTmpCsv($tableName){
    $command = 'mysqlimport --local --fields-terminated-by='.CSV_FIELD_DELIMITER.' --fields-optionally-enclosed-by=" --lines-terminated-by=\r\n -u'.DB_PROJECT_USER.' -p'.DB_PROJECT_PASS.' -h'.DB_PROJECT_HOST.' '.DB_PROJECT_NAME.' /tmp/'.$tableName.'.csv';
    exec(mysql_real_escape_string($command));
}

function getMonthShiftedDate($date, $shift){
    $date = substr($date, 0, 10);
    return date("Y-m-d", strtotime("$date $shift month"));
}

function indexArrayOnKey($aData, $key){
    global $logger;
    $t1 = microtime(TRUE);
    $result = array();
    foreach ($aData as $data) {
        $result[$data->$key] = $data;
    }
    if(isset($logger)){
        $logger->info("Indexing on key:$key complete. Took " . (microtime(TRUE)-$t1) . " second");
    }
    return $result;
}

function groupOnKey($aData, $key){
    global $logger;
    $t1 = microtime(TRUE);
    $result = array();
    foreach ($aData as $data) {
        if(!isset($result[$data->$key])){
            $result[$data->$key] = array();
        }
        $result[$data->$key][] = $data;
    }
    if(isset($logger)){
        $logger->info("Indexing on key:$key complete. Took " . (microtime(TRUE)-$t1) . " second");
    }
    return $result;
}

function groupOnArrayKey($aData, $key){
    global $logger;
    $t1 = microtime(TRUE);
    $result = array();
    foreach ($aData as $data) {
        if(!isset($result[$data[$key]])){
            $result[$data[$key]] = array();
        }
        $result[$data[$key]][] = $data;
    }
    if(isset($logger)){
        $logger->info("Indexing on key:$key complete. Took " . (microtime(TRUE)-$t1) . " second");
    }
    return $result;
}

function saveToFileOrDb($arRow, $bulkInsertFlag, $handle=NULL){
    if($bulkInsertFlag){
        fwrite ($handle, getCSVRowFromArray($arRow->to_array()));
    }else{
        $arRow->save();
    }
}

function getSumOfKeyValues($aData, $key){
    $sum = 0;
    foreach ($aData as $value) {
        $sum += $value->$key;
    }
    return $sum;
}

function truncateTable($tableName){
    $sql = "truncate table $tableName";
    ActiveRecord\Connection::instance()->query($sql);
}

function getArrayFromObjectArray($aData, $key){
    $result = array();
    foreach($aData as $data){
        $result[] = $data->$key;
    }
    return $result;
}
