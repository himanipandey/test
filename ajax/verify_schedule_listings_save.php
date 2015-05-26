<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");


$listing_id = $_POST['listing_id'];
$photo_action = $_POST['photo_action'];
$current_user = $_POST['current_user'];
$remark = $_POST['remark'];
$photo_folder_name = $_POST['photo_folder_name'];

try {
    //fetching schedules if any
    $schduled_id = ListingSchedules::find('all', array('select' => 'id',
                'conditions' => array('status' => 'Active', 'listing_id' => $listing_id)));
    if ($schduled_id) {
        $schedule = ListingSchedules::find($schduled_id[0]->id);
    } else {
        $schedule = new ListingSchedules();
    }
    $schedule->photo_link = $photo_folder_name;
    $schedule->updated_by = $current_user;
    $schedule->save();

    //updating remark
    $cmsAssign = CmsAssignment::find('all', array(
                'select' => 'id',
                'conditions' => array('assignment_type' => 'resale', 'entity_id' => $listing_id, 'status' => 'assignedToPhotoGrapher')));
    if ($cmsAssign) {
        $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
        if($photo_action == 'verify'){
           $cmsAssign->status = 'readyToTouchUp'; 
           $cmsAssign->completed_by = $current_user;
        }
        if($remark){
           $cmsAssign->remark = $remark; 
        }
        $cmsAssign->save();
    }
    
    
} catch (Exception $e) {
    print $e;
}
print 1;
?>
