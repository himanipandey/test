<?php

// Model integration for city list
include_once 'support/objects.php';
class Townships extends Objects
{
    static $table_name = 'townships';
    static function getTownshipByName($townshipName) {
        $township = Townships::find('all',array('conditions' => array('township_name = ?', $townshipName)));
        return $township;      
    }
    static function getTownShipsById($townshipsId) {
         $townshipDetail = Townships::find('all',
                 array('conditions' => array('id = ?', $townshipsId)));
        return $townshipDetail;
    }
    static function getAllTownships() {
        $allTownships = Townships::find("all",array('order' => 'township_name asc'));
        return $allTownships;
    }
}