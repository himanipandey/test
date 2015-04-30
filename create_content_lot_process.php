<?php

$CityDataArr = City::CityArr();
$smarty->assign("CityDataArr", $CityDataArr);

//fetching content users
$assignToUsers = fetch_assignTo_users();
$smarty->assign('assignToUsers', $assignToUsers);

if (isset($_POST['createLot'])) {

    $errors = '';

    $lot_type = $_POST['lotType'];
    $lot_city = $_POST['city'];
    $articles = mysql_real_escape_string($_POST['selArticles']);
    $assigned_to = $_POST['assignTo'];

    ContentLot::transaction(function() {

        global $lot_type, $lot_city, $articles, $assigned_to, $errors;

        try {
            //saving data into content_lots
            $contentLot = new ContentLot();
            $contentLot->lot_type = $lot_type;
            $contentLot->lot_city = $lot_city;
            $contentLot->lot_status = ($assigned_to) ? 'assigned' : 'unassigned';
            $contentLot->created_by = $_SESSION['adminId'];
            $contentLot->created_at = date('Y-m-d H:i:s');
            $contentLot->save();

            //saving data into content lot details
            $articlesContent = get_lot_entity_content($lot_type, $articles);

            foreach ($articlesContent as $entityID => $content) {
                $contentLotDetail = new ContentLotDetail();
                $contentLotDetail->lot_id = $contentLot->id;
                $contentLotDetail->entity_id = $entityID;
                $contentLotDetail->content = $content['content'];
                $contentLotDetail->entity_name = $content['entity_name'];
                $contentLotDetail->updated_by = $_SESSION['adminId'];
                $contentLotDetail->save();
            }


            //saving data into cms assignments table
            if ($assigned_to) {
                $cmsAssign = new CmsAssignment();
                $cmsAssign->assignment_type = 'content_lots';
                $cmsAssign->entity_id = $contentLot->id;
                $cmsAssign->assigned_to = $assigned_to;
                $cmsAssign->assigned_by = $_SESSION['adminId'];
                $cmsAssign->status = 'assigned';
                $cmsAssign->created_at = date('Y-m-d H:i:s');
                $cmsAssign->save();
            }

            $errors = 0;
        } catch (Exeception $e) {
            $errors = $e;
        }
    });

    if ($errors == 0) {
        header("Location:content_lot_list.php");
    } else {
        $smarty->assign('errors', $errors);
    }
}
?>
