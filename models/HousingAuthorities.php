<?php

// Model integration for city list
include_once 'support/objects.php';
class HousingAuthorities extends Objects
{
    static $table_name = 'housing_authorities';
    static function getAuthoritiesByName($name) {
        $authority = HousingAuthorities::find('all',array('conditions' => array('authority_name = ?', $name), 'joins'=> ' inner join city c on c.CITY_ID=housing_authorities.city_id', 'select'=>' housing_authorities.*, c.LABEL ' ));
        return $authority;      
    }
    static function getAuthoritiesById($id) {
         $authorityDetail = HousingAuthorities::find('all',
                 array('conditions' => array('id = ?', $id)));
        return $authorityDetail;
    }
    static function getAllAuthorities() {
        $allAuthorities = HousingAuthorities::find("all",array('order' => 'authority_name asc', 'joins'=> ' inner join city c on c.CITY_ID=housing_authorities.city_id', 'select'=>' housing_authorities.*, c.LABEL '));
        return $allAuthorities;
    }
}