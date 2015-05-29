<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");


$listing_ids = (array)json_decode($_POST['listing_ids']);
$photographers = $_POST['photographers'];
$current_user = $_POST['current_user'];

try {
    
    foreach($listing_ids as $list_id => $listing_name){
        
        //handeling re-assignment
        $cmsAssign = CmsAssignment::find('all', array(
                        'select' => 'id',
                        'conditions' => array('assignment_type' => 'resale', 'entity_id' => $list_id, 'status' =>'assignedToPhotoGrapher')));
        if($cmsAssign){
            $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
        }else{
            $cmsAssign = new CmsAssignment();
        }
        
        $cmsAssign->remark = '';
        $cmsAssign->assignment_type = 'resale';
        $cmsAssign->entity_id = $list_id;
        $cmsAssign->created_at = date('Y-m-d H:i:s');
        $cmsAssign->assigned_to = $photographers;
        $cmsAssign->assigned_by = $current_user;
        $cmsAssign->status = 'assignedToPhotoGrapher';
        $cmsAssign->save();
        
        //saving assignment id into listings_schdules
        $listing_schdule_data = ListingSchedules::find('all', array('select' => 'id','conditions' => array('status' => 'Active', 'listing_id' => $list_id)));
        if($listing_schdule_data){
            $list_sch = ListingSchedules::find($listing_schdule_data[0]->id);
            $list_sch->cms_assignment_id = $cmsAssign->id;
            $list_sch->save();
        }
    }   

    
} catch (Exception $e) {
    print $e;
}
print 1;
?>
