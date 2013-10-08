<?php

// Model integration for bank list
class RoomCategory extends ActiveRecord\Model
{
    static $table_name = 'room_category';

    static public function categoryList(){
        $categories = self::find("all", array("order" => "ROOM_CATEGORY_ID ASC"));
        $arrroomCategory = array();
        foreach($categories as $category){
            $arrroomCategory[$category->room_category_id] = $category->category_name;
        }
        return $arrroomCategory;
    }
}