<?php

class ImageServiceUpload{

    static $image_upload_url = "http://nightly.proptiger-ws.com:8080/data/v1/entity/image";
    static $object_types = array("project" => "project",
        "option" => "property",
        "builder" => "builder",
        "locality" => "locality"
    );

    static $image_types = array(
        "project" => array(
            "location_plan" => "location_plan",
            "layout_plan" => "layout_plan",
            "site_plan" => "site_plan",
            "master_plan" => "master_plan",
            "cluster_plan" => "cluster_plan",
            "construction_status" => "construction_status",
            "payment_plan" => "payment_plan",
            "specification" => "specification",
            "price_list" => "price_list",
            "application_form" => "application_form",
            "project_image" => "main"
        ),
        "option" => array("floor_plan" => "floor_plan"),
        "builder" => array("builder_image" => "logo"),
        "locality" => array("locality_image" => "main"));

    function __construct($image, $object, $object_id, $image_type){
        $this->image = $image;
        $this->object = $object;
        $this->object_id = $object_id;
        $this->image_type = $image_type;
        $this->errors = array();
        $this->validate();
    }

    function upload(){
        $post = array('image'=>'@'.$this->image,'objectType'=>$this->object,
            'objectId' => $this->object_id, 'imageType' => $this->image_type);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,static::$image_upload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response= curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->response_header = substr($response, 0, $header_size);
        $this->response_body = json_decode(substr($response, $header_size));
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $this->verify_status();
        $this->raise_errors_if_any();
    }

    function validate(){
        $this->validate_keys();
        $this->raise_errors_if_any();
    }

    function validate_keys(){
        if(!array_key_exists($this->object, static::$object_types)){
            $this->add_errors($this->object." object not found");
        }
        else{
            if(!array_key_exists($this->image_type, static::$image_types[$this->object])){
                $this->add_errors($this->image_type." image type does not exist in hash.");
            }
        }
    }

    function verify_status(){
        if((int)$this->status != 200){
            $this->add_errors("Got response code ".$this->status.": ".$this->response_body->error->msg);
        }
        else{
            if(property_exists($this->response_body, "error")){
                $this->add_errors("Got error: ".$this->response_body->error->msg);
            }

        }
    }

    function add_errors($errors){
        array_push($this->errors, $errors);
    }

    function raise_errors_if_any(){
        if(count($this->errors) > 0){
            die(implode($this->errors,", "));
        }
    }
}