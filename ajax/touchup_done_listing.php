<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");


$listing_id = $_POST['listing_id'];
$current_user = $_POST['current_user'];

try {
   
    $cmsAssign = CmsAssignment::find('all', array(
                'select' => 'id',
                'conditions' => array('assignment_type' => 'resale', 'entity_id' => $listing_id, 'status' => 'readyToTouchUp')));
    if ($cmsAssign) {
        $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
        $cmsAssign->status = 'touchUpDone';
        $cmsAssign->save();
    }
} catch (Exception $e) {
    print $e;
}
print 1;
?>
