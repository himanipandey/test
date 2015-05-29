<?php

require_once "support/objects.php";
class ResiBuilder extends Objects
{
    static $table_name = 'resi_builder';
    static function BuilderEntityArr() {
        $getBuilder = ResiBuilder::find('all',array( "select" => "builder_id, builder_name",'order'=>'entity asc')); 
        $arrBuilder = array();
        foreach($getBuilder as $value) {
            $arrBuilder[$value->builder_id] = $value->builder_name;
        }
        return $arrBuilder;
    } 
    static function ProjectSearchBuilderEntityArr() {
        $getBuilder = ResiBuilder::find('all',array("conditions"=>array("builder_status = 0"),"select" => "builder_id, entity",'order'=>'entity asc')); 
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
    
    static function updatestatusofbuilder($builderId) {
        $responce =  self::update_all(array('conditions' => array('builder_id' => $builderId), 'set' => "builder_status = 1"));
        return $responce;
    }
    static function getbuilderurl($builderId) {
        $responce =  ResiBuilder::find('all',array('conditions'=>array("builder_id = ?",$builderId),"select" => "url")); 
        return $responce;
    }
    static function getbuildername($builderId) {
        $responce =  ResiBuilder::find('all',array('conditions'=>array("builder_id = ?",$builderId),"select" => "builder_name")); 
        return $responce;
    }

    static function updateBuiderScore($builderId, $builderScore){
        self::update_all(array('conditions' => array('builder_id' => $builderId), 'set' => "builder_score = $builderScore"));
    }
}
