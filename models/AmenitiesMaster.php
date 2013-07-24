<?php

// Model integration for bank list
class AmenitiesMaster extends ActiveRecord\Model
{
    static $table_name = 'amenities_master';
    static $has_many = array(
        array('resi_projects', 'class_name' => "ResiProjectAmenities", "foreign_key" => "AMINITY_ID")
    );
}