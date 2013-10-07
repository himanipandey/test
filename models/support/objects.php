<?php
/*
Objects V0.1
Authored by: Vimlesh Rajput, Paritosh Singh
Date: 7/10/2013
 */
require_once(dirname(__FILE__)."/table_attribute_mappings.php"); // auxilary functions mapping
require_once(dirname(__FILE__)."/table_attributes.php"); // auxilary functions mapping
class Objects extends ActiveRecord\Model{

    static $custom_methods = array("all", "first", "last");

    static $default_scope = array();

    static $extra_attributes = "_extra_attributes";

    static $extra_values = "_extra_values";

    static $updated_by = "_updated_by";

    static $virtual_primary_key = NULL;


/********************************************* Special Methods (should be placed at first) ****************************/
    //  Specifiy all extra attributes which are being stored
    static  function get_extra_params(){
        return array(static::$extra_values, static::$extra_attributes, static::$updated_by);
    }

/********************************************* End Of Special Methods *************************************************/



/*********************************************  Static Private methods ************************************************/

    //  Private function which add new required conditions
    static private function add_custom_conditions($options, $custom_conditions){
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
            //error condition can only be string or array
            }
        }
        else{
            // error: options should be array
        }
        return $conditions;
    }

    // function takes a string and add new values in it as conditions
    static private function string_condition_manipulation($string, $key, $value, $operation = '='){
        if($value != "?" && $value != "(?)" && !is_array($value))
            $value = "'{$value}'";
        return "{$string} and {$key} {$operation} {$value}";
    }

    // Function performs array manipulation for conditions
    static private function array_condition_manipulation($conditions, $scopes){
        if(count($conditions) > 0 && array_key_exists(0, $conditions)){
            foreach($scopes as $key=>$val){
                if(is_array($val)){
                    $operator = "in";
                    $operand  = "(?)";
                }
                else{
                    $operator = "=";
                    $operand  = "?";
                }
                $conditions[0] = static::string_condition_manipulation($conditions[0],$key,$operand, $operator);
                array_push($conditions, $val);
            }
        }
        else{
            $conditions = array_merge($conditions, $scopes);
        }
        return $conditions;
    }


    //  Fetch all auxilary fields
    static private function get_extra_attributes(){
        $table_name = static::$table_name;
        $conditions = array("table_name" => $table_name);
        $columns = array();
        $table_objects = TableAttributeMappings::find("all", array("conditions" => $conditions));
        foreach($table_objects as $obj){
            array_push($columns, $obj->attribute_name);
        }
        return $columns;
    }

    //  Get value from extra attributes
    static private function get_extra_values($ids = array()){
        $table_name = static::$table_name;
        $conditions = array("table_name" => $table_name, "table_id" => $ids);
        $table_variables = array();
        $auxilary_results = TableAttributes::find("all", array("conditions" => $conditions));
        foreach($auxilary_results as $result){
            $table_variables[$result->table_id][$result->attribute_name] = $result;
        }
        return $table_variables;
    }


    //  Fetches all extra auxillary attributes
    static private function fetch_extra_values($objects){
        $single_object = !is_array($objects);

        if($single_object) $objects = array($objects);
        $object_ids = array();
        foreach($objects as $object) array_push($object_ids, $object->get_primary_key_value());

        $existing_attributes = static::get_extra_values($object_ids);
        $stored_objects = array();

        foreach($objects as $object){
            $primary_key_value = $object->get_primary_key_value();
            if(array_key_exists($primary_key_value, $existing_attributes))
                $attributes = $existing_attributes[$primary_key_value];
            else
                $attributes = array();
            foreach($attributes as $key=>$val){
                $object->$key = $val->attribute_value;
            }
            array_push($stored_objects, $object);
        }

        if ($single_object) $stored_objects = $stored_objects[0];
        return $stored_objects;
    }


/**************************************** End of Static Private functions *********************************************/





/****************************************  Private Instance Methods  **************************************************/

    //  The virtual id is generated
    //  Needs model with name same as current model and Ids appended to it
    private function generate_virtual_id(){
        // try to use try catch and return false on failure
        $className = get_called_class();
        $primary_key = static::$virtual_primary_key;
        $id_class_model = $className."Ids";
        $insertId = new $id_class_model();
        $insertId->save();
        $this->$primary_key = $insertId->id;
        return true;
    }

    //  Sets the extra attributes
    private function set_extra_attributes(){
        $extra_attributes = static::$extra_attributes;
        if(!isset($this->$extra_attributes)){
            $this->$extra_attributes = static::get_extra_attributes();
        }
    }

    //  Sets values in extra attributes
    private function set_extra_values(){
        $extra_attributes = static::$extra_attributes;
        $updated_by = static::$updated_by;
        // Setting additional attributes
        $this->set_extra_attributes();
        $primary_key_value = $this->get_primary_key_value();
        $existing_attributes = static::get_extra_values(array($primary_key_value));
        if(array_key_exists($primary_key_value, $existing_attributes))
            $existing_attributes = $existing_attributes[$primary_key_value];
        else
            $existing_attributes = array();
        foreach($this->$extra_attributes as $attr){
            if(isset($this->$attr)){
                if(array_key_exists($attr, $existing_attributes)){
                    $table_attribute = $existing_attributes[$attr];
                }
                else{
                    $table_attribute = new TableAttributes();
                    $table_attribute->table_name = static::$table_name;
                    $table_attribute->table_id = $this->get_primary_key_value();
                    $table_attribute->attribute_name = $attr;
                }

                $table_attribute->attribute_value = $this->$attr;
                $table_attribute->updated_by = $this->$updated_by;
                $table_attribute->save();
                unset($this->$attr);
            }
        }
    }

    private function unset_extra_params(){
        $extra_params = static::get_extra_params();
        foreach($extra_params as $params){
            if(isset($this->$params)) unset($this->$params);
        }
    }


/****************************************** End of Private Instance Methods *******************************************/



/****************************************** Public Static methods *****************************************************/

    // find function overidden for default scope
    static public function find(/*$id, $option*/){
        // Get arguments specified in function
        $args = func_get_args();

        // if only one argument is there the we add an additional argument
        if(count($args) == 1) $args[1] = array();

        // skip_default_scope skips the default scope
        if(!array_key_exists("skip_default_scope", $args[1])){
            $args[1]["skip_default_scope"] = false;
        }

        if(!array_key_exists("get_extra_scope", $args[1])){
            $args[1]["get_extra_scope"] = false;
        }

        $extra_scope = $args[1]["get_extra_scope"];

        // if args are in custom methods
        if(in_array($args[0], static::$custom_methods)){
            // adding default scopes on when it is not skipped
            if(!$args[1]["skip_default_scope"])
                $args[1]["conditions"] = static::add_custom_conditions($args[1], static::$default_scope);

        }
        unset($args[1]["skip_default_scope"]);
        unset($args[1]["get_extra_scope"]);

        // Avoids to give empty options array as they create problems
        if (count($args[1]) == 0) unset($args[1]);
        $objects =  call_user_func_array('parent::find',$args);

        // Fetching extra values from auxillary table
        if($extra_scope){
            $objects = static::fetch_extra_values($objects);
        }

        return $objects;
    }


    //  Let user find object using virtual primary key
    static public function virtual_find(/*$id, $option*/){

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

    //  Function takes array of values and create new entry if  model is not already present else
    //  update the existing row
    static public function create_or_update($options = array()){
        if(static::$virtual_primary_key != NULL){
            $primary_key = static::$virtual_primary_key;
            $find = "virtual_find";
            $save = "virtual_save";
        }
        else{
            $primary_key = static::$primary_key;
            $find = "find";
            $save = "save";
        }

        if(array_key_exists($primary_key, $options) && $options[$primary_key]){
            $object = static::$find($options[$primary_key]);
        }
        else{
            $object = new static();
        }

        foreach($options as $key=>$val){
            $object->$key = $val;
        }

        $object->$save();
        return $object;
    }

/******************************************* End of public static method **********************************************/



/******************************************* Public Instance Methods **************************************************/

    //  Overridding setter function for objects
    public function __set($name, $value){
        $extra_params = static::get_extra_params();
        $extra_attributes = static::$extra_attributes;

        if(in_array($name, $extra_params)){
            $this->$name = $value;
            return $value;
        }
        $this->set_extra_attributes();

        if(in_array($name, $this->$extra_attributes)){
            $this->$name = $value;
            return $value;
        }

        parent::__set($name, $value);
    }

    //  Save function overridden
    public function save($validate = true){
        $result = parent::save($validate);
        $this->set_extra_values();
        return $result;
    }

    //  Save objects in virtual domain
    public function virtual_save($validate = true) {
        $primary_key = static::$virtual_primary_key;                
        if( !$this->$primary_key ) {            
         $this->generate_virtual_id();   
        }
        $scopes = static::$default_scope;
        foreach($scopes as $key=>$val){
            $this->$key = $val;
        }      
        $this->save($validate);
    }

    //  Gives the value for primary key of objects
    //  Checks if given value is primary key or virtual_primary_key
    public function get_primary_key_value(){
        if(static::$virtual_primary_key != NULL){
            $primary_key = static::$virtual_primary_key;
        }
        else{
            $primary_key = static::$primary_key;
        }
        return $table_id = $this->$primary_key;
    }

    public function set_attr_updated_by($id){
        $updated_by = static::$updated_by;
        $this->$updated_by = $id;
    }

/****************************************** End of Public Instance Methods ********************************************/

}


/* Functions to be referenced in future */

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
