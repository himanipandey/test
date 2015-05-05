<?php

class getLandMark extends ActiveRecord\Model
{
    static $table_name = 'landmark_infrastructure';

    static function getLandmarks() {
        /*$select = "distinct(infrastructure_type)";

        $res = getLandMark::find('all', array('select'=> $select));
        $result = '';
        foreach ($res as $key => $value) {
            $a = $value->master_spec_class_name;
        }

        $result = $a;*/
        $result[0] = 'Tfjskd';
        $result[1] = 'Hfhdi';
        $result[2] = 'Gfjd';
        return $result;
    }
}