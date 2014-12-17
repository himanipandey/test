<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$projectDetail = "http://nightly.proptiger-ws.com:8080/app/v4/project-detail/647719";
echo "<pre>";print_r($projectDetail);
$returnData = curl_request($post, 'POST', $url);
function curl_request($post, $method, $url){
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
?>
