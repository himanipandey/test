<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$lot_id = $_POST['lot_id'];
$revertIds = explode(",", $_POST['revertIds']);
$action = $_POST['action'];
$currentUser = $_POST['currentUser'];
$revertComment = $_POST['revertComment'];
$status = 'revertedToVendor';
$completedBy = $_POST['completedBy'];
$cid = $_POST['cid'];


    ContentLot::transaction(function() {

        global $cid, $action, $status, $lot_id, $currentUser, $revertComment, $revertIds, $completedBy;

        try {

//            CmsAssignment::update_all(array(
//                'set' => 'status = "' . $status . '", assigned_to = "' . $completedBy . '", checked_by = "'.$currentUser.'"',
//                'conditions' => array('assignment_type' => 'content_lots', 'entity_id' => $lot_id)
//                    )
//            );
//
//            //updating data into content lots table
//            ContentLot::update_all(array(
//                'set' => 'lot_status = "' . $status . '"',
//                'conditions' => array('id' => $lot_id)
//                    )
//            );
//            
//            //updating data into content lots details table
//            ContentLotDetail::update_all(array(
//                'set' => 'status = "revert"',
//                'conditions' => array('lot_id' => $lot_id, 'id' => $revertIds)
//                    )
//            );
            
            //insert into content lot comments
            foreach ($revertIds as $k => $revertId) {
                if ($action == "add") {
                    $contentLotComment = new ContentLotComments();
                }else{
                    $contentLotComment = ContentLotComments::find($cid);
                }                
                $contentLotComment->content_lot_id = $revertId;
                $contentLotComment->comment = $revertComment;
                $contentLotComment->created_by = $currentUser;
                $contentLotComment->created_at = date('Y-m-d H:i:s');                
                $contentLotComment->save();
            }
            
        } catch (Exception $e) {
            print "Action Failed!";
            // print $e;
            exit;
        }
    });

print "1";
?>
