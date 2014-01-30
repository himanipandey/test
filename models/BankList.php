<?php

// Model integration for bank list
class BankList extends ActiveRecord\Model
{
    static $table_name = 'bank_list';
    static $primary_key = 'bank_id';
    static function arrBank() {
        $getBank = BankList::find('all',array('order'=>'bank_name asc'));
        $arrBank = array();
        foreach( $getBank as $value ) {
            $arrBank[$value->bank_id] = $value->bank_name;
        }
        return $arrBank;
    }
}
