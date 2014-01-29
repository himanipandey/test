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
?>
