<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");
include("../function/functions_assignments.php");

$lot_id = $_POST['lot_id'];
$assignToUsers = (array) json_decode($_POST['assignToUsers']);
$assignedBy = $_POST['assigned_by'];
?>


<table id="cancel-reassign-tbl" width='500px' cellspacing="5" cellpadding="5">
    <tr style='text-align:left; background:#ccc'>
        <th>
            Cancel / Re-assign
        </th>
    </tr>
    <tr style='text-align:center' >
        <td>
            <input style="margin: -1px 3px 0 0;" type='radio' name='cancel_reassign' class='cancel_reassign' value='cancel'/><b>Cancel</b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input style="margin: -1px 3px 0 0;" type='radio' name='cancel_reassign' class='cancel_reassign' value='reassign'/><b>Reassign</b>
        </td>
    </tr>
    <tr id="cancelLot" style='text-align:center;display:none'>
        <td>
            <span>
                Are you sure you want to cancel this Lot?
            </span>
            <br/><br/>
            <input type="button" class="page-button" value="Back to List" onclick="cancel_reassign_action('back')"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" class="page-button" value="Cancel Lot" onclick="cancel_reassign_action('cancel_lot')"/>
        </td>
    </tr>
    <tr id="reassignLot" style="text-align:center;display:none">
        <td>
            <span>
                Re-assign to the vendor:
                <select id='reassign-users'>
                    <option value="">-select-</option>
                    <?php foreach ($assignToUsers as $userID => $userName): ?>
                        <option value='<?php echo $userID ?>'><?php echo $userName ?></option>
                    <?php endforeach; ?>
                </select>                
            </span>
            <br/><br/>
            <input type="button" class="page-button" value="Back to List" onclick="cancel_reassign_action('back')"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" class="page-button" value="Re-assign Lot" onclick="cancel_reassign_action('reassign_lot')"/>
        </td>
    </tr>
</table>
<script type="text/javascript">
    $(document).ready(function () {
        $('.cancel_reassign').change(function () {
            if ($(this).val() == 'cancel') {
                $('#cancelLot').show();
                $('#reassignLot').hide();
            } else {
                $('#reassignLot').show();
                $('#cancelLot').hide();
            }
        });
    });
    function cancel_reassign_action(lotAction) {
        if (lotAction == 'back') {
            jQuery.fancybox.close();
        } else if (lotAction == 'cancel_lot') {
            $.ajax({
                url: "ajax/cancel_lot.php",
                type: "POST",
                data: "lot_id=" + "<?php echo $lot_id ?>",
                success: function (dt) {
                    if (dt.trim() == 'fail') {
                        alert('Lot cancelation failed!');
                    } else {
                        alert('Lot has been canceled successfully!');
                        window.location = "content_lot_list.php";
                    }

                }
            });
        } else if (lotAction == "reassign_lot") {
            var vendorValue = $('#reassign-users').val();

            if (vendorValue) {
                //assigning lot to related vendor/editor
                $.ajax({
                    url: "ajax/lot_actions.php",
                    type: "POST",
                    data: "reAssign=1&lot_id=" + "<?php echo $lot_id ?>" + "&vendorID=" + vendorValue + "&lotAction=" + "assign" + "&assigned_by=" + "<?php echo $assignedBy ?>",                    
                    success: function (dt) {                       
                        
                        if (dt.trim() != 'Action Failed!') {
                            alert("Lot Re-assigned successfully!");
                            window.location = 'content_lot_list.php';
                        }else{
                            alert(dt);
                        }
                    }
                });

            } else {
                alert("Please select vendor.");
            }
        }
    }
</script>
