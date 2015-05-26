<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");


$listing_id = $_POST['listing_id'];
$current_user = $_POST['current_user'];

//fetching existing photo_link
$photo_link = '';
$schduled = ListingSchedules::find('all', array('select' => 'id, photo_link',
            'conditions' => array('status' => 'Active', 'listing_id' => $listing_id)));
if ($schduled) {
    $photo_link = $schduled[0]->photo_link;
}
//fetching exsiting remark
$remark = '';
$cmsAssign = CmsAssignment::find('all', array(
            'select' => 'id, remark',
            'conditions' => array('assignment_type' => 'resale', 'entity_id' => $listing_id, 'status' => 'assignedToPhotoGrapher')));
if ($cmsAssign) {
    $remark = $cmsAssign[0]->remark;
}
?>
<table cellpadding=7>
    <tr style="background-color: #666666; color:#fff">
        <th colspan="2">
            Verify Listing 
        </th>
    </tr> 
    <tr>
        <td>
            <b><font color="red">*</font>Photo Folder Name : </b>
        </td>
        <td>
            <input type="text" name="photo-folder" value="<?php echo $photo_link ?>" id="photo-folder">
        </td>
    </tr>
    <tr>
        <td>
            <b>Remark : </b>
        </td>
        <td>
            <textarea name="schedule-remark" id="schedule-remark"><?php echo $remark ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="button" class="page-button" id="cancel-btn" value="Cancel" onclick="cancel_verifying()"/>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" class="page-button" id="schedule-btn" value="Save"  onclick="save_verifying('save')"/>
            &nbsp;&nbsp;&nbsp;&nbsp;            
            <input type="button" class="page-button" id="schedule-btn" value="Verify"  onclick="save_verifying('verify')"/>
        </td>

    </tr>
</table>
<script type="text/javascript">
    function save_verifying(photo_action) {
        if ($('#photo-folder').val().trim() != '') {
            $.ajax({
                url: 'ajax/verify_schedule_listings_save.php',
                type: 'POST',
                data: "photo_action=" + photo_action + "&listing_id=" + '<?php echo $listing_id ?>' + "&photo_folder_name=" + $('#photo-folder').val().trim() + "&remark=" + $('#schedule-remark').val().trim() + "&current_user=" + "<?php echo $current_user ?>",
                beforeSend: function () {
                    $("body").addClass("loading");
                },
                success: function (dt) {
                    $("body").removeClass("loading");
                    if (dt.trim() == '1') {
                        if (photo_action == 'verify')
                            alert("Listings Verified Successfully!");
                        else
                            alert('Data saved successfully!');
                        window.location = "listing_assignment_list.php";
                    } else {
                        alert("Action Failed");
                    }
                }
            });
        } else {
            alert("Photo Folder Name is required!");
        }


    }
    function cancel_verifying() {
        jQuery.fancybox.close();
    }

</script>