<?php

class ImageServiceUpload{

    static $image_upload_url = "http://nightly.proptiger-ws.com:8080/data/v1/entity/image";
    //static $image_upload_url = "http://nightly-build.proptiger-ws.com/";
     
    static $valid_request_methods = array("POST", "PUT", "DELETE");
    static $object_types = array("project" => "project",
        "option" => "property",
        "builder" => "builder",
        "locality" => "locality",
        "bank" => "bank",
        "broker_company" => "broker_company"
    );

    static $image_types = array(
        "project" => array(
            "location_plan" => "locationPlan",
            "layout_plan" => "layoutPlan",
            "site_plan" => "sitePlan",
            "master_plan" => "masterPlan",
            "cluster_plan" => "clusterPlan",
            "construction_status" => "constructionStatus",
            "payment_plan" => "paymentPlan",
            "specification" => "specification",
            "price_list" => "priceList",
            "application_form" => "applicationForm",
            "project_image" => "main"
        ),
        "option" => array("floor_plan" => "floorPlan"),
        "builder" => array("builder_image" => "logo"),
        "locality" => array(
            "locality_image" => "main",
            "other" => "other",
            "mall" => "mall",
            "road" => "road",
            "school" => "school",
            "hospital" => "hospital"
        ),
        "bank" => array("logo" => "logo"),
        "broker_company" => array("logo" => "logo")
        );

    function __construct($image, $object, $object_id, $image_type, $extra_params, $method, $image_id = NULL){
        $this->image = $image;
        $this->object = $object;
        $this->object_id = $object_id;
        $this->image_type = $image_type;
        $this->image_id = $image_id;
        $this->method = trim($method);
        $this->extra_params = $extra_params;
        $this->errors = array();
        $this->validate();
    }

    function upload(){
        $params = array('image'=>'@'.$this->image,'objectType'=>static::$object_types[$this->object],
            'objectId' => $this->object_id, 'imageType' => static::$image_types[$this->object][$this->image_type]);
        $extra_params = $this->extra_params;
        $params = array_merge($params, $extra_params);
        if($this->method == "DELETE")
            $response = static::delete($this->image_id, $params);
        elseif($this->method == "PUT")
            $response = static::update($this->image_id, $params);
        else
            $response = static::create($params);
        $this->response_header = $response["header"];
        $this->response_body = $response["body"];
        $this->status = $response["status"];
        $this->verify_status();
        $this->raise_errors_if_any();
    }

    static function join_urls() {
        $args = func_get_args();
        $paths = array();
        foreach ($args as $arg) {
            $paths = array_merge($paths, (array)$arg);
        }

        $paths = array_map(create_function('$p', 'return trim($p, "/");'), $paths);
        $paths = array_filter($paths);
        return join('/', $paths);
    }

    static function curl_request($post, $method, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
        if($method == "POST" || $method == "PUT")
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response= curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response_header = substr($response, 0, $header_size);
        $response_body = json_decode(substr($response, $header_size));
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        return array("header" => $response_header, "body" => $response_body, "status" => $status);
    }

    static function create($post){
        return static::curl_request($post, 'POST', static::$image_upload_url);
    }

    static function delete($id, $post){
        $url = static::join_urls(static::$image_upload_url, $id);
        return static::curl_request($post, 'DELETE', $url);
    }

    static function update($id, $post){
        $url = static::join_urls(static::$image_upload_url, $id);
        return static::curl_request($post, 'POST', $url);
    }

    function validate(){
        if($this->method != "DELETE"){
            $this->validate_keys();
        }
        $this->check_extra_params();
        $this->raise_errors_if_any();
    }


    function check_extra_params(){
        if(!is_array($this->extra_params)){
            $this->add_errors("Extra params should be array");
        }
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

    function validate_request_methods(){
        if(!array_key_exists($this->method, static::$valid_request_methods)){
            $this->add_errors("Not a valid request method {$this->method}. Valid methods are: ".
                implode(", ", static::$valid_request_methods));
        }

        if(($this->method == "PUT" || $this->method == "DELETE") && $this->image_id == NULL ){
            $this->add_errors("Image id cannot be null for {$this->method} type request");
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

    function data(){
        return $this->response_body->data;
    }
}