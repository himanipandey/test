<?php

class Objects extends ActiveRecord\Model{
//    public static $virtual_primary_key;

    static $custom_methods = array("all", "first", "last");

    static $default_scope = array();

    // find function overidden for default scope
    static function find(/*$id, $option*/){
//      Get arguments specified in function
        $args = func_get_args();

        // if only one argument is there the we add an additional argument
        if(count($args) == 1) $args[1] = array();

        // skip_default_scope skips the default scope
        if(!array_key_exists("skip_default_scope", $args[1])){
            $args[1]["skip_default_scope"] = false;
        }
        // if args are in custom methods
        if(in_array($args[0], static::$custom_methods)){
            // adding default scopes on when it is not skipped
            if(!$args[1]["skip_default_scope"])
                $args[1]["conditions"] = static::add_custom_conditions($args[1], static::$default_scope);

        }
        unset($args[1]["skip_default_scope"]);

        // Avoids to give empty options array as they create problems
        if (count($args[1]) == 0) unset($args[1]);
        return call_user_func_array('parent::find',$args);
    }


//  Let user find object using virtual primary key
    static function virtual_find(/*$id, $option*/){

        $args = func_get_args();
        $primary_key = static::$virtual_primary_key;

        if(is_numeric($args[0])){
            if(count($args) == 1) $args[1] = array();
            $args[1]["conditions"] = static::add_custom_conditions($args[1], array($primary_key => $args[0]));
            $args[0] = "all";
        }
        $object = call_user_func_array('static::find',$args);
        if(count($object) == 0){
            $object[0] = NULL;
        }
        return $object[0];

    }

    function add_custom_conditions($options, $custom_conditions){
        if(is_array($options)){
            // check if key exist
            if(!array_key_exists("conditions", $options)) $options["conditions"] = array();

            $conditions = $options["conditions"];
            if(is_string($conditions)){
                // simple string manipulation
                foreach($custom_conditions as $key=>$val){
                    $conditions = static::string_condition_manipulation($conditions, $key, $val);
                }
            }
            elseif(is_array($conditions)){
                // array manipulation
                $conditions = static::array_condition_manipulation($conditions, $custom_conditions);
            }
            else{
//                    error condition can only be string or array
            }
        }
        else{
            // error: options should be array
        }
        return $conditions;
    }

    // function takes a string and add new values in it
    function string_condition_manipulation($string, $key, $value){
        return "{$string} and {$key} = {$value}";
    }

    // Function performs array manipulation for conditions
    function array_condition_manipulation($conditions, $scopes){
        if(count($conditions) > 0 && array_key_exists(0, $conditions) && strpos($conditions[0],"?") !=NULL){
            foreach($scopes as $key=>$val){
                $conditions[0] = static::string_condition_manipulation($conditions[0],$key,"?");
                array_push($conditions, $val);
            }
        }
        else{
            $conditions = array_merge($conditions, $scopes);
        }
        return $conditions;
    }
    
//  Save objects in virtual domain
    function virtual_save() {           
        $primary_key = static::$virtual_primary_key;                
        if( !$this->$primary_key ) {            
         $this->generate_virtual_id();   
        }        
        else{
            $this->find_id_for_virtual_id();
        }
        $scopes = static::$default_scope;
        foreach($scopes as $key=>$val){
            $this->$key = $val;
        }      
        $this->save();    
    }
    
//  The virtual id is generated
//  Needs model with name Ids appended
    function generate_virtual_id(){
//      try to use try catch and return false on failure
        $className = get_called_class();        
        $primary_key = static::$virtual_primary_key;                
        $id_class_model = $className."Ids";    
        $insertId = new $id_class_model();
        $insertId->save();
        $this->$primary_key = $insertId->id;
        return true;                        
    }
    
//  Find the id for virtaul id
    function find_id_for_virtual_id(){
        $primary_key = static::$virtual_primary_key;
        $object = static::virtual_find($this->$primary_key);
        $this->id = $object->id;
        return true;
    }
    /****function for default date for created at field* for referance**************/
    /*function createdAt()  {
        $className = get_called_class();
        $tableName = static::$table_name;
        $allColumns = $className::connection()->columns($tableName);
        if( array_key_exists('created_at',$allColumns) ) {
            if(!$this->created_at){
                echo "got you";
                die;
                $this->created_at = date('m/d/Y h:i:s');   
            }                   
        }
        if( array_key_exists('updated_at',$allColumns) ) {
            if(!$this->updated_at){
                $this->updated_at = date('m/d/Y h:i:s', time());   
            }                   
        }
    }  
    */
}