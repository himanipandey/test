<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$lot_id = $_POST['lot_id'];
$revertIds = $_POST['revertIds'];
$action = $_POST['action'];
$currentUser = $_POST['currentUser'];
$completedBy = $_POST['completedBy'];

$cid = '';
if ($action == 'add') {
    
    print "<table width='300'>"
            . "<tr style='text-align:center'><th>Add Revert Comment<th></tr>"
            . "<tr style='text-align:center'><td><textarea id='comment' style='width:300px;height:120px'></textarea><th></td>"
            . "<tr style='text-align:center'><td><input type='button' value='Save' onclick='add_revert_comment()' class='page-button'/>"
            . "&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Cancel' onclick='cancel_revert_comment()' class='page-button'/></td>"
            . "</table>";
} elseif ($action == 'edit') {
    
    $sqlRevertComments = mysql_query("SELECT clc.comment, clc.id  FROM content_lot_comments clc
                    INNER JOIN content_lot_details cld on cld.id = clc.content_lot_id
                    LEFT JOIN proptiger_admin admin on admin.adminid = clc.created_by
                    WHERE cld.lot_id = '$lot_id' AND clc.content_lot_id in (" . $revertIds . ") AND clc.status='active' ORDER BY clc.id desc LIMIT 1") or die(mysql_error());

    if (mysql_num_rows($sqlRevertComments)) {
        $comment = mysql_fetch_object($sqlRevertComments);
        $cid = $comment->id;
        print "<table width='300'>"
                . "<tr style='text-align:center'><th>Edit Revert Comment<th></tr>"
                . "<tr style='text-align:center'><td><textarea id='comment' style='width:300px;height:120px'>$comment->comment</textarea><th></td>"
                . "<tr style='text-align:center'><td><input type='button' value='Save' onclick='add_revert_comment()' class='page-button'/>"
                . "&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Cancel' onclick='cancel_revert_comment()' class='page-button'/></td>"
                . "</table>";
    }
}
?>
<script type="text/javascript">
    function cancel_revert_comment() {
        jQuery.fancybox.close();
    }
    function add_revert_comment() {
        $.ajax({
            url: 'ajax/lot_action_add_revert_comment.php',
            type: 'POST',
            data: "cid="+"<?php echo $cid ?>"+"&completedBy=" + "<?php echo $completedBy ?>" + "&revertComment=" + $('#comment').val().trim() + "&lot_id=" + "<?php echo $lot_id ?>" + "&revertIds=" + "<?php echo $revertIds ?>" + "&currentUser=" + "<?php echo $currentUser ?>" + "&action=" + "<?php echo $action ?>",
            beforeSend: function () {
                $("body").addClass("loading");
            },
            success: function (dt) {
                $("body").removeClass("loading");
                if (dt.trim() == '1') {
                    alert("Revert comment added successfully!");
                    //jQuery.fancybox.close();
                    window.location = "content_lot_details_assigned.php?l=<?php echo $lot_id ?>";
                } else {
                    alert("Add revert comment failed!");
                }
            }
        });
    }
</script>