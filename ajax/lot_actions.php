<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$vendorID = $_POST['vendorID'];
$lotAction = $_POST['lotAction'];
$lot_id = $_POST['lot_id'];
$assigned_by = $_POST['assigned_by'];
$role = $_POST['currentUserRole'];
$currentUser = $_POST['currentUser'];
$reAssign = (isset($_POST['reAssign'])) ? 1 : 0;

if ($lotAction == 'complete') {
    $status = 'completed';
}elseif ($lotAction == 'approve') {
    $status = 'approved';
} elseif ($lotAction == 'revert') {
    $status = 'reverted';
} else {
    $status = 'assigned';
}

if ($status == 'completed') {
    if ($role == 'contentVendor') {
        $statuses = 'completedByVendor';
    } elseif ($role == 'contentEditor') {
        $statuses = 'waitingApproval';
    }
    ContentLot::transaction(function() {

        global $statuses, $lot_id, $role, $currentUser;

        try {
            //updating data into cms assignments table
            $cmsAssign = CmsAssignment::find('all', array(
                        'select' => 'id',
                        'conditions' => array('assignment_type' => 'content_lots', 'entity_id' => $lot_id)));
            $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
            $cmsAssign->status = $statuses;            
            if ($role == 'contentVendor') {
                $cmsAssign->completed_by =  $currentUser;
                $cmsAssign->assigned_to = null;
            } elseif ($role == 'contentEditor') {
                $cmsAssign->checked_by =  $currentUser;
            }
            
            $cmsAssign->save();


            //updating data into content lots table
            $contentLot = ContentLot::find('all', array(
                        'select' => 'id',
                        'conditions' => array('id' => $lot_id)
            ));
            $contentLot = ContentLot::find($contentLot[0]->id);
            $contentLot->lot_status = $statuses;
            $contentLot->save();
            
        } catch (Exception $e) {
            print "Action Failed!";
        }
    });
    
}elseif ($status == 'approved') {


    ContentLot::transaction(function() {

        global $status, $lot_id;

        try {

            CmsAssignment::update_all(array(
                'set' => 'status = "' . $status . '"',
                'conditions' => array('entity_id' => $lot_id)
                    )
            );

            //updating data into content lots table
            ContentLot::update_all(array(
                'set' => 'lot_status = "' . $status . '"',
                'conditions' => array('id' => $lot_id)
                    )
            );
        } catch (Exception $e) {
            print "Action Failed!";
            // print $e;
            exit;
        }
    });
} else {
    ContentLot::transaction(function() {

        global $status, $lot_id, $vendorID, $assigned_by, $reAssign;

        try {
            //updating data into cms assignments table
            //updating data into cms assignments table
            $cmsAssign = CmsAssignment::find('all', array(
                        'select' => 'id',
                        'conditions' => array('assignment_type' => 'content_lots', 'entity_id' => $lot_id)));
            if ($cmsAssign)
                $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
            else {
                $cmsAssign = new CmsAssignment();
                $cmsAssign->assignment_type = 'content_lots';
                $cmsAssign->entity_id = $lot_id;
                $cmsAssign->created_at = date('Y-m-d H:i:s');
            }
            $cmsAssign->assigned_to = $vendorID;
            $cmsAssign->assigned_by = $assigned_by;

            if ($reAssign) {
                $cmsAssign->completed_by = null;
                $cmsAssign->checked_by = null;
                $cmsAssign->created_at = date('Y-m-d H:i:s');
                $cmsAssign->remark = null;
            }

            $cmsAssign->status = $status;
            $cmsAssign->save();

            //updating data into content lots table
            ContentLot::update_all(array(
                'set' => 'lot_status = "' . $status . '"',
                'conditions' => array('id' => $lot_id)
                    )
            );

            $content_lot_details_status = ($status == 'reverted') ? 'revert' : '';
            $condition = '';
            if ($reAssign) {
                $condition = ", updated_content = null";
            }

            ContentLotDetail::update_all(array(
                'set' => 'status = "' . $content_lot_details_status . '"' . $condition,
                'conditions' => array('lot_id' => $lot_id)
                    )
            );
        } catch (Exception $e) {
            print "Action Failed!";
            // print $e;
            exit;
        }
    });
}


print "Lot " . $status . "!";
?>
