<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$schduled_id = $_POST['schduled_id'];
$listing_id = $_POST['listing_id'];
$key_person_name = $_POST['key_person_name'];
$key_person_contact = $_POST['key_person_contact'];
$current_user = $_POST['current_user'];
$meeting_date = $_POST['meeting_date'];

try{
    if($schduled_id){
       $schedule = ListingSchedules::find($schduled_id); 
       $schedule->updated_by = $current_user;
       //$meeting_date = $_POST['meeting_date'];
    }else{
       $schedule = new ListingSchedules();
       $schedule->created_by = $current_user;
       $schedule->created_at = date('Y-m-d H:i:s');
       //$meeting_date = date("Y-m-d H:i:s A", strtotime($_POST['meeting_date']));
    }
    
    $schedule->listing_id = $listing_id;
    $schedule->key_person_name = $key_person_name;
    $schedule->key_person_contact = $key_person_contact;
    $schedule->scheduled_date_time = $meeting_date;   
    
    $schedule->save();
    
}catch(Exception $e){
    print $e;
}
print 1;
?>
