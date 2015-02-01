<?php

// Model integration for bank list
class TransactionType extends ActiveRecord\Model
{
    static $table_name = 'transaction_types';
    static function TransactionTypeArr() {
        $getTransactionType = TransactionType::find('all',array('order'=>'type asc'));
        $transac_type_arr = array('New Launch', 'Rent', 'Resale');
        $arrType = array();
        foreach( $getTransactionType as $value ) {
        	if(in_array($value->type, $transac_type_arr))
            	$arrType[$value->id] = $value->type;
        }
        return $arrType;
    }
    

}