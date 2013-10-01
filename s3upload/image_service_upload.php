<?php

class ImageServiceUpload{

    static $image_upload_url = "http://192.168.1.207:8080/data/v1/entity/image";

    function __construct($image, $object, $object_id, $image_type){
        $this->image = $image;
        $this->object = $object;
        $this->object_id = $object_id;
        $this->image_type = $image_type;
        $this->errors = array();
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
//        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
//        $header = substr($response, 0, $header_size);
//        $body = substr($response, $header_size);
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $this->verify_status();
        $this->raise_errors_if_any();
    }

    function verify_status(){
        if((int)$this->status != 200){
            $this->add_errors("Got response code ".$this->status);
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