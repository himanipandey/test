<?php

// Model integration for SEO Data
class SeoData extends ActiveRecord\Model
{
    static $table_name = 'seo_data';
    static function insetUpdateSeoData($data) {     
        $insertUpdate = SeoData::find('all',array('conditions'=>
        array('table_id = ? and table_name = ?', $data['table_id'],$data['table_name'])));                  
        
        if( count($insertUpdate) >0 ) {
             $insertUpdate = $insertUpdate[0];
             $insertUpdate->updated_at = date('Y-m-d H:i:s');
        }
        else {
             $insertUpdate = new SeoData();
             $insertUpdate->created_at = date('Y-m-d H:i:s');
             $insertUpdate->table_id = $data['table_id'];
        }
        $insertUpdate->table_name = $data['table_name'];
        $insertUpdate->meta_title = $data['meta_title'];
        $insertUpdate->meta_keywords = $data['meta_keywords'];
        $insertUpdate->meta_description = $data['meta_description'];
        $insertUpdate->updated_by = $data['updated_by'];
        $insertUpdate->save();
    }
    static function getSeoData($rowId,$table_name) {
        $getData = SeoData::find('all',array('conditions'=>
                   array("table_id = ? and table_name = ? ", $rowId, $table_name)));
       return $getData;
    }
}