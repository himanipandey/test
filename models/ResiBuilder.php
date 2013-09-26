<?php

class ResiBuilder extends ActiveRecord\Model
{
    static $table_name = 'resi_builder';
    static function BuilderEntityArr() {
        $getBuilder = ResiBuilder::find('all',array( "select" => "builder_id, entity",'order'=>'entity asc')); 
        $arrBuilder = array();
        foreach($getBuilder as $value) {
            $arrBuilder[$value->builder_id] = $value->entity;
        }
        return $arrBuilder;
    } 
    static function getBuilderById($builderId) {
        $builderDetail = ResiBuilder::find('all',array('conditions'=>array("builder_id = $builderId")));
        return $builderDetail;
    }
}