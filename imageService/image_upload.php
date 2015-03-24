<?php

//require dirname(__FILE__)."/upload.php";
require_once dirname(__FILE__)."/image_service_upload.php";
class ImageUpload{

    static $required_options = array("object_id", "object", "image_type", "image_path");
    //static $required_options = array("object_id", "object", "s3");
    static $max_file_size = 15;
    static $supported_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
    //static $s3_bucket = "proptiger-testing";

    function __construct($image, $options) {
        $this->image = $image;
        $this->options = $options;
        $this->size = filesize($image);
        $this->errors = array();
    }


    function validate(){
        $this->validate_options();
        $this->validate_size();
        $this->validate_image();
        $this->raise_errors_if_any();
    }
    
    function validate_options(){
        $keys = array_keys($this->options);
        $missing_params = array_diff(static::$required_options, $keys);
        if(count($missing_params) > 0){
            $this->add_errors("Missing parameters: ".implode($missing_params, ", "));
        }
    }


    function validate_size(){
        $max_size_in_bytes = $this->covert_to_bytes(static::$max_file_size);
        if($this->size > $max_size_in_bytes){
            $this->add_errors("Max upload size is ".static::$max_file_size." MB.");
        }
    }

    function validate_image(){
        $ext = $this->get_file_extension($this->image);
        if(!file_exists($this->image)) $this->add_errors("File does not exist: {$this->image}");
        if(!in_array($ext, static::$supported_formats)) $this->add_errors("Not a valid format, got .{$ext} ");
    }

    function upload(){
        //$this->validate();
       
        //$service_object = $this->upload_service();
        //return array("service" => $service_object);
        return $this->upload_service();
    }

    function update(){
        //$this->validate();
        $options = $this->options;
        //$s3_object = $this->upload_s3();
        //print'<pre>';print_r($options); 
       /*if($options["service_image_id"])
            $service_object = $this->update_service();
        else{
            $service_object = $this->upload_service();

        }
        return array("service" => $service_object);*/
        if($options["service_image_id"])
            return  $this->update_service();
        else{
            return  $this->upload_service();

        }
        
    }

    function updateWithoutImage(){
        $options = $this->options;
        $service_object = array();
        if($options["service_image_id"])
            return $this->update_service_without_image();
        //return array("service" => $service_object);
    }
    function delete(){
        $options = $this->options;
        $service_object = array();
        if($options["service_image_id"])
             return $this->delete_service();
        //return array("service" => $service_object);
    }



    function covert_to_bytes($size) {
        return $size * 1024 * 1024;
    }

    function add_errors($errors){
        array_push($this->errors, $errors);
    }

    function raise_errors_if_any(){
        if(count($this->errors) > 0){
            die(implode($this->errors,", "));
        }
    }

    function get_file_extension($filename){
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    function upload_s3(){
        $s3 = $this->options["s3"];
        $new_file_name = $this->options["image_path"];
        $s3_object = new S3Upload($s3, static::$s3_bucket, $this->image, $new_file_name);
        $s3_object->upload();
        return $s3_object;
    }

    function upload_service(){
       
        return $this->send_service_request("POST");

    }

    function update_service(){
        return $this->send_service_request("PUT");
    }

    function update_service_without_image(){
        return $this->send_service_request("PUT", "");
    }

    function delete_service(){
        return $this->send_service_request("DELETE");
    }

    function send_service_request($request_type, $str){
        $options = $this->options;
        if($request_type != "DELETE"){
            $object = $options["object"];
            $object_id = $options["object_id"];
            $image_type = $options["image_type"];
        }
        else{
            $object = NULL;
            $object_id = NULL;
            $image_type = NULL;
        }

        $extra_params = array();
        $image_id = NULL;
        if(array_key_exists("service_extra_params", $options)) $extra_params = $options["service_extra_params"];
        if(array_key_exists("service_image_id", $options)) $image_id = $options["service_image_id"];
        //if(array_key_exists("dtype", $options)) $extra_params["dtype"]  = $options["dtype"];

        $service_object = new ImageServiceUpload($this->image, $object, $object_id, $image_type,  $extra_params, $request_type, $image_id);
        $returnArr = array();
        $returnArr = $service_object->upload();

          
         //print'<pre>'; print_r($returnArr); die();
        return $returnArr;
        //return $service_object;
    }
}