<?php

// Model integration for Builder Alias
class BuilderAlias extends ActiveRecord\Model
{
    static $table_name = 'builder_alias';
    static function insetUpdateBuilderAlias($data) {     
        $insertUpdate = new BuilderAlias();
        $insertUpdate->builder_id = $data['builder_id'];
        $insertUpdate->alias_with = $data['alias_with'];
        $insertUpdate->updated_date = $data['updated_date'];
        $insertUpdate->updated_by = $data['updated_by'];
        $insertUpdate->save();
    }
}