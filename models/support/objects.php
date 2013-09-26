<?php

class Objects extends ActiveRecord\Model{
//    public static $virtual_primary_key;

    static $custom_methods = array("all", "first", "last");

    static $default_scope = array();

    // find function overidden for default scope
    static function find(/*$id, $option*/){
//      Get arguments specified in function
        $args = func_get_args();


        // if args are in custom methods
        if(in_array($args[0], static::$custom_methods)){

            // if only one argument is there the we add an additional argument
            if(count($args) == 1) $args[1] = array();
            $options = $args[1];

            if(is_array($options)){
                // check if key exist
                if(!array_key_exists("conditions", $options)) $options["conditions"] = array();

                $conditions = $options["conditions"];

                if(is_string($conditions)){
                    // simple string manipulation
                    $scopes = static::$default_scope;
                    foreach($scopes as $key=>$val){
                        $conditions = static::string_condition_manipulation($conditions, $key, $val);
                    }
                }
                elseif(is_array($conditions)){
                    // array manipulation
                    $conditions = static::array_condition_manipulation($conditions);
                }
                else{
//                    error condition can only be string or array
                }
                $args[1]["conditions"] = $conditions;
            }
        }
        return call_user_func_array('parent::find',$args);
    }


    static function custom_find(/*$id, $option*/){
        
    }
    // function takes a string and add new values in it
    function string_condition_manipulation($string, $key, $value){
        return "{$string} and {$key} = {$value}";
    }

    // Function performs array manipulation for conditions
    function array_condition_manipulation($conditions){
        if(count($conditions) > 0 && array_key_exists(0, $conditions) && strpos($conditions[0],"?") !=NULL){
            $scopes = static::$default_scope;
            foreach($scopes as $key=>$val){
                $conditions[0] = static::string_condition_manipulation($conditions[0],$key,"?");
                array_push($conditions, $val);
            }
        }
        else{
            $conditions = array_merge($conditions, static::$default_scope);
        }
        return $conditions;
    }

}