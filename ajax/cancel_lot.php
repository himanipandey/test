<?php

session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$lot_id = $_POST['lot_id'];
$status = 'canceled';

ContentLot::transaction(function() {

    global $lot_id, $status;

    try {
        //updating data into cms assignments table
        //updating data into cms assignments table
        $cmsAssign = CmsAssignment::find('all', array(
                    'select' => 'id',
                    'conditions' => array('assignment_type' => 'content_lots', 'entity_id' => $lot_id)));
        if ($cmsAssign) {
            $cmsAssign = CmsAssignment::find($cmsAssign[0]->id);
            $cmsAssign->status = $status;
            $cmsAssign->save();
        }


        //updating data into content lots table
        ContentLot::update_all(array(
            'set' => 'lot_status = "' . $status . '"',
            'conditions' => array('id' => $lot_id)
                )
        );

    } catch (Exception $e) {
        print "fail";
        // print $e;
        exit;
    }
});

print "success";
?>