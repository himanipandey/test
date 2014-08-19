<?php

// Model integration for bank list
class TransactionType extends ActiveRecord\Model
{
    static $table_name = 'transaction_types';
    static function TransactionTypeArr() {
        $getTransactionType = TransactionType::find('all',array('order'=>'type asc'));
        $arrType = array();
        foreach( $getTransactionType as $value ) {
            $arrType[$value->id] = $value->type;
        }
        return $arrType;
    } 
}