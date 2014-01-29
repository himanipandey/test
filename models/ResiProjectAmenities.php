<?php

// Model integration for bank list
class ResiProjectAmenities extends ActiveRecord\Model
{
    static $table_name = 'resi_project_amenities';

    static $belongs_to = array(
        array('project', "class_name" => "ResiProject", "foreign_key" => "PROJECT_ID"),
        array('amenity', "class_name" => "AmenitiesMaster", "foreign_key" => "AMENITY_ID")
  );
}