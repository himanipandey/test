<?php
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
    $validPeriods = array('year', 'quarter', 'month', 'week', 'half_year');

    if(is_string($date)){
        $date;
        $date = new DateTime($date);
    }
    
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period) {
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
?>
