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
$cms_assignment_id = $_POST['cms_assignment_id'];

try {
    if ($schduled_id) {
        $schedule = ListingSchedules::find($schduled_id);
        $schedule->updated_by = $current_user;        
    } else {
        $schedule = new ListingSchedules();
        $schedule->created_by = $current_user;
        $schedule->created_at = date('Y-m-d H:i:s');        
    }

    $schedule->listing_id = $listing_id;
    $schedule->key_person_name = $key_person_name;
    $schedule->key_person_contact = $key_person_contact;
    $schedule->scheduled_date_time = $meeting_date;
    $schedule->save();
    
    //inactive all other active schedulings
    ListingSchedules::update_all(array('set' => 'status = "Inactive"', 'conditions' => array("id <> '".$schedule->id."' AND listing_id ='".$listing_id."'")));
       
    
} catch (Exception $e) {
    print $e;
}
print 1;
?>
