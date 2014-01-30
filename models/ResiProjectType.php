<?php

// Model integration for bank list
class ResiProjectType extends ActiveRecord\Model
{
    static $table_name = 'resi_project_type';
    static function ProjectTypeArr() {
        $getProjectType = ResiProjectType::find('all',array('order'=>'type_name asc'));
        $arrType = array();
        foreach( $getProjectType as $value ) {
            $arrType[$value->project_type_id] = $value->type_name;
        }
        return $arrType;
    } 
}