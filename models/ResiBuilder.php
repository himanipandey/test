<?php

require_once "support/objects.php";
class ResiBuilder extends Objects
{
    static $table_name = 'resi_builder';
    static function BuilderEntityArr() {
        $getBuilder = ResiBuilder::find('all',array( "select" => "builder_id, builder_name",'order'=>'builder_name asc')); 
        $arrBuilder = array();
        foreach($getBuilder as $value) {
            $arrBuilder[$value->builder_id] = $value->builder_name;
        }
        return $arrBuilder;
    } 
    static function getBuilderById($builderId) {
        $builderDetail = ResiBuilder::find('all',array('conditions'=>array("builder_id = $builderId")));
        return $builderDetail;
    }
}