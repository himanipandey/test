<?php
// Authored by Paritosh Singh
// S3Upload helps to upload images
class S3Upload {

    static  $max_file_size = 6;
    static  $supported_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
    static  $default_s3_class = "AmazonS3";
    function __construct($s3, $bucket, $file, $name = null) {
        $this->s3 = $s3;
        $this->bucket = $bucket;
        $this->file = $file;
        $this->size = filesize($file);
        if($name == null)
            $this->original_name = $file;
        else
            $this->original_name = $name;
        $this->name = $this->remove_special_characters($this->original_name);
        $this->remove_forward_slash();
        $this->add_defined_path();
        $this->max_file_size = $this->covert_to_bytes(self::$max_file_size);
        $this->errors = array();
        $this->response = null;
    }

    function upload(){
        $this->validate();
        $this->stop_on_errors();

        $this->response = $this->s3->create_object($this->bucket, $this->name, array('fileUpload'=>$this->file));
        $this->check_response();
        $this->stop_on_errors();
        return $this->name;
    }

    function check_response(){
        $status = $this->response->status;
        if((int)$status != 200){
            $this->add_errors("Error Occurred while uploading, got status code {$status}");
        }
    }

    function validate(){
        $this->validate_s3();
        $this->validate_file();
    }

    function validate_s3(){
            $class = get_class($this->s3);
            if($class != self::$default_s3_class){
                $this->add_errors("Not a valid s3 object");
            }
    }

    function validate_file(){
        $ext = $this->get_file_extension($this->original_name);
//        $original_ext = $this->get_file_extension($this->file);
//        if($ext != $original_ext) $this->add_errors("Extensions of both file do not match");
        if(!file_exists($this->file)) $this->add_errors("File does not exist");
        if(!in_array($ext, self::$supported_formats)) $this->add_errors("Not a valid format, got .{$ext} ");
    }

    function covert_to_bytes($size) {
        return $size * 1024;
    }

    function get_file_extension($filename){
        $i = strrpos($filename,".");
        if (!$i) { return ""; }

        $l = strlen($filename) - $i;
        $ext = substr($filename,$i+1,$l);
        return $ext;
    }

    function add_errors($str){
        array_push($this->errors, $str);
    }

    function show_errors(){
        return implode($this->errors, ", ");
    }

    function stop_on_errors(){
        if(count($this->errors) != 0){
            echo $this->show_errors();
            die;
        }
    }

    function remove_special_characters($str){
        return  preg_replace('/[^a-zA-Z0-9_\-\/\.]/s', '', $str);
    }

    function add_timestamp_to_name($name){
        $pos = strpos($name, ".");
        return substr_replace($name, "_".time(), $pos, 0);
    }

    function add_defined_path(){
        if(defined("S3_STORAGE_PATH")) $this->name = S3_STORAGE_PATH.$this->name;
    }

    function remove_forward_slash(){
        if($this->name[0] == "/"){
            $length = strlen($this->name);
            $this->name = substr($this->name, 1, $length);
        }
    }

}