<?php

// Model integration for bank list
class BrokerPropertyType extends ActiveRecord\Model
{
    static $table_name = 'broker_property_type';
    static function PropertyTypeArr() {
        $getPropertyType = BrokerPropertyType::find('all',array('order'=>'type_name asc',          'conditions'=> array('project_type_id' > '11')));
        $arrType = array();
        foreach( $getPropertyType as $value ) {
            $arrType[$value->project_type_id] = $value->type_name;
        }
        return $arrType;
    } 
}