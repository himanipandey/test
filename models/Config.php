<?php

// Model integration for Company list
class Config extends ActiveRecord\Model
{
    static $table_name = 'config';
    
    static function getConfigValue($groupName, $configName){
        return self::find(array('group_name'=>$groupName, 'config_name'=>$configName))->value;
    }
}