<?php

// Model integration for bank list
class ResiPhaseQuantity extends ActiveRecord\Model
{
    static $table_name = 'resi_phase_quantity';

    function quantity_for_phase($phase_id){
        $query = "SELECT UNIT_TYPE, GROUP_CONCAT(CONCAT(BEDROOMS, ':', QUANTITY)) as AGG from ".self::$table_name." WHERE PHASE_ID='".$phase_id."' GROUP BY UNIT_TYPE";
        return ResiPhaseQuantity::find_by_sql($query);
    }
}