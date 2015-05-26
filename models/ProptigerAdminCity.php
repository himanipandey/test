<?php

// Model integration for bank list
class ProptigerAdminCity extends ActiveRecord\Model {

    static $table_name = 'proptiger_admin_city';

    static function get_admin_city($adminId) {
        $admin_cities_arr = array();
        $admin_cities = ProptigerAdminCity::find('all', array('joins' => 'inner join city on proptiger_admin_city.city_id = city.city_id ','select' => 'city.city_id, city.label', 'conditions' => array('admin_id' => $adminId)));
        if ($admin_cities) {
            foreach ($admin_cities as $city) {
                $admin_cities_arr[$city->city_id] = $city->label;
            }
        }

        return $admin_cities_arr;
    }
    
    static function get_admin_city_ids($adminId) {
        $admin_cities_arr = array();
        $admin_cities = ProptigerAdminCity::find('all', array('joins' => 'inner join city on proptiger_admin_city.city_id = city.city_id ','select' => 'city.city_id, city.label', 'conditions' => array('admin_id' => $adminId)));
        if ($admin_cities) {
            foreach ($admin_cities as $city) {
                $admin_cities_arr[] = $city->city_id;
            }
        }

        return $admin_cities_arr;
    }

}
