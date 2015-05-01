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

if($action == 'addComment'){
    
    print "<table width='300'>"
    . "<tr style='text-align:center'><th>Add Revert Comment<th></tr>"
    . "<tr style='text-align:center'><td><textarea id='comment' style='width:300px;height:120px'></textarea><th></td>"
    . "<tr style='text-align:center'><td><input type='button' value='Revert' onclick='add_revert_comment()' class='page-button'/>"
            . "&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Cancel' onclick='cancel_revert_comment()' class='page-button'/></td>"
    . "</table>";
    
}

?>
<script type="text/javascript">
    function cancel_revert_comment(){
        jQuery.fancybox.close();
    }
    function add_revert_comment(){
        $.ajax({
            url:'ajax/lot_action_add_revert_comment.php',
            type:'POST',
            data:"completedBy="+"<?php echo $completedBy ?>"+"&revertComment="+$('#comment').val().trim()+"&lot_id="+"<?php echo $lot_id ?>"+"&revertIds="+"<?php echo $revertIds ?>"+"&currentUser="+"<?php echo $currentUser ?>"+"&action=addRevertComment",
            beforeSend: function () {
                $("body").addClass("loading");
            },
            success: function (dt) {
                $("body").removeClass("loading");               
                if(dt.trim() == '1'){
                    alert("Article(s) reverted successfully!");
                    //jQuery.fancybox.close();
                    window.location = "content_lot_list_assigned.php";
                }else{
                    alert("Article(s) revert action failed!");
                }
            }
        });
    }
</script>