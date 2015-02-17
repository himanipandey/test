<?php

// Model integration for bank list
class Devices extends ActiveRecord\Model
{
    static $table_name = 'devices';
    static $primary_key = 'id';
    static function getAllDevices() {
        $getDevices = Devices::find('all',array('order'=>'id asc'));
        $arrDevices = array();
        foreach( $getDevices as $value ) {
            $arrDevices[$value->id] = $value->display_name;
        }
        return $arrDevices;
    }
}
