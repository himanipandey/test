<?php

$lot_id = $_REQUEST['l'];
$smarty->assign('lot_id', $lot_id);

$lot_details = fetch_lot_details($lot_id);
$smarty->assign('lot_details', $lot_details);

//is_allowed to see thr details
$smarty->assign('is_allowed', is_allowed($lot_details['lot_status'], $lot_details["assigned_to"]));

//current user role
$smarty->assign('currentRole', $_SESSION['ROLE']);
$smarty->assign('currentUser', $_SESSION['adminId']);

if (isset($_POST['lotCompleted'])) {

    $role = $_SESSION['ROLE'];
    $status = 'assigned';
    if ($role == 'contentVendor') {
        $status = 'completedByVendor';
    } elseif ($role == 'contentEditor') {
        $status = 'waitingApproval';
    }

    ContentLot::transaction(function() {

        global $status, $lot_id, $role;

        try {
            //updating data into cms assignments table
            $cmsAssign = CmsAssignment::find('all', array(
                        'select' => 'id',
                        'conditions' => array('assignment_type' => 'content_lots', 'entity_id' => $lot_id)));
            $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
            $cmsAssign->status = $status;            
            if ($role == 'contentVendor') {
                $cmsAssign->completed_by =  $_SESSION['adminId'];
                $cmsAssign->assigned_to = null;
            } elseif ($role == 'contentEditor') {
                $cmsAssign->checked_by =  $_SESSION['adminId'];
            }
            
            $cmsAssign->save();


            //updating data into content lots table
            $contentLot = ContentLot::find('all', array(
                        'select' => 'id',
                        'conditions' => array('id' => $lot_id)
            ));
            $contentLot = ContentLot::find($contentLot[0]->id);
            $contentLot->lot_status = $status;
            $contentLot->save();
            
            header("Location:content_lot_list_assigned.php");
            
        } catch (Exception $e) {
            print $e;
        }
    });
}

function is_allowed($lot_status, $assignedTo){
  $role = $_SESSION['ROLE'];  
  
  if(($role == 'contentVendor' && ($lot_status == 'assigned' || $lot_status == 'revertedToVendor') && $assignedTo == $_SESSION['adminId']) || ($role == 'contentEditor'  && ($lot_status == 'assigned' || $lot_status == 'reverted')) || ($role == 'contentTeamLead')){
      return true;
  }else{
      return false;
  }
}
?>