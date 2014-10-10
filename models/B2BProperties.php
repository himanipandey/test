<?php
class B2BProperties extends ActiveRecord\Model {
	
	static $table_name = 'b2b_properties';
	static $MAX_B2B_DATE_DbColumnLabel = 'end_date';
	static $MAX_B2B_SCRIPT_DATE_DbColumnLabel = 'indexing_script_end_date';
	static $MIN_B2B_DATE_DbColumnLabel = 'start_date';
	static $DEMAND_START_DATE_DbColumnLabel = 'demand_start_date';

	public static function getB2BMinDate() {
		return (self::fetchAndExtractB2BPropertyValue(self::$MIN_B2B_DATE_DbColumnLabel));
	}
	
	public static function getB2BMaxDate() {
		return (self::fetchAndExtractB2BPropertyValue(self::$MAX_B2B_DATE_DbColumnLabel));
	}

  public static function getB2BScriptMaxDate() {
    return (self::fetchAndExtractB2BPropertyValue(self::$MAX_B2B_SCRIPT_DATE_DbColumnLabel));
  }
	
	public static function getB2BDemandStartDate() {
		return (self::fetchAndExtractB2BPropertyValue(self::$DEMAND_START_DATE_DbColumnLabel));
	}
	
	private static function fetchAndExtractB2BPropertyValue($columnLabel) {
		$sqlQuery = "SELECT value FROM " . self::table_name() . " where name='{$columnLabel}'";
		$result = self::connection()->query($sqlQuery);
		$value = $result->fetch(PDO::FETCH_NUM);
		return $value [0];
	}
}
