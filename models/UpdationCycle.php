<?php

// Model integration for bank list
class UpdationCycle extends ActiveRecord\Model
{
    static $table_name = 'updation_cycle';
    static function updationCycleTable() {
        $updationCycleList = UpdationCycle::find('all');
        return $updationCycleList;
    }
}