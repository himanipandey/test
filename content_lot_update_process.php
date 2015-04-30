<?php

$lot_id = $_REQUEST['l'];
$smarty->assign('lot_id', $lot_id);

$lot_content_id = $_REQUEST['cid'];
$smarty->assign('lot_content_id', $lot_content_id);

$paginationIds = fetch_pagination_ids($lot_id, $lot_content_id);
$smarty->assign('paginationIds', $paginationIds);

$lot_content_details = fetch_lot_content_details($lot_content_id);
$smarty->assign('lot_content_details', $lot_content_details);

if (isset($_POST['updateLot'])) {
    
    $contentLotDetail = ContentLotDetail::find($lot_content_id);
    $contentLotDetail->updated_content = mysql_real_escape_string($_POST['updatedCotent']); 
    $contentLotDetail->status = 'complete'; 
    $contentLotDetail->updated_by = $_SESSION['adminId'];
    $contentLotDetail->save();
    
    if($contentLotDetail->id){
        header("Location:content_lot_details_assigned.php?l=" . $lot_id);
    }
}
?>