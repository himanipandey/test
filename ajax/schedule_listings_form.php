<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");


$listing_id = $_POST['selected_rows'];
$current_user = $_POST['current_user'];
$current_user_role = $_POST['current_user_role'];

if ($current_user_role == 'photoGrapher') {
    $readOnlyFlag = 'readonly';
} else {
    $readOnlyFlag = '';
}


$listing_schdule_data = ListingSchedules::find('all', array(
            'joins' => 'left join cms_assignments ca on ca.id = listing_schedules.cms_assignment_id',
            'select' => 'ca.status as assign_status, listing_schedules.cms_assignment_id, listing_schedules.id, listing_schedules.key_person_name, listing_schedules.key_person_contact, listing_schedules.scheduled_date_time',
            'conditions' => array('status' => 'Active', 'listing_id' => $listing_id)));

$key_person_name = '';
$key_person_contact = '';
$scheduled_time = '';
$schduled_id = '';
$cms_assignment_id = '';
if ($listing_schdule_data) {

    $schedule_data = $listing_schdule_data[0];

    if ($schedule_data->assign_status != 'touchUpDone') {
        $schduled_id = $schedule_data->id;
        $cms_assignment_id = $schedule_data->cms_assignment_id;
        $key_person_name = $schedule_data->key_person_name;
        $key_person_contact = $schedule_data->key_person_contact;
        $scheduled_time = $schedule_data->scheduled_date_time->format('Y/m/d h:i a');
    }
}
?>
<table cellpadding=7>
    <tr style="background-color: #666666; color:#fff">
        <th colspan="2">
            Schedule a Listing for Verification 
        </th>
    </tr>
    <tr>
        <td>
            <b><font color="red">*</font>Key Person Name : </b>
        </td>
        <td>
            <input <?php echo $readOnlyFlag ?> type="text" id="key-person-name" value="<?php echo $key_person_name ?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <b><font color="red">*</font>Key Person Contact : </b>
        </td>
        <td>
            <input <?php echo $readOnlyFlag ?> type="text" id="key-person-contact" size=5 value="<?php echo $key_person_contact ?>"  onkeypress = "return isNumberKey(event);" maxlength = "10"/>
        </td>
    </tr>
    <tr>
        <td>
            <b><font color="red">*</font>Date-Time : </b>
        </td>
        <td>
            <div id="datetimepicker2" class="input-append">
                <input style="width:150px" readonly="true" data-format="yyyy/MM/dd HH:mm PP" type="text" id="meeting_date" name="meeting_date" value="<?php echo $scheduled_time ?>"></input>
                <span class="add-on">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                    </i>
                </span>
            </div>
        </td>
    </tr>    
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="button" class="page-button" id="schedule-btn" value="Schedule"  onclick="save_scheduling()"/>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" class="page-button" id="cancel-btn" value="Cancel" onclick="cancel_scheduling()"/>
        </td>

    </tr>
</table>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
<script type="text/javascript">
                $(function () {
                    $('#datetimepicker2').datetimepicker({
                        language: 'en',
                        pick12HourFormat: true,
                        pickSeconds: false,
                    });
                });
</script>

<script type="text/javascript">
    function save_scheduling() {

        if ($('#key-person-name').val().trim() == '') {
            alert("Key Person Name is required!");
            return;
        }
        if ($('#key-person-contact').val().trim() == '') {
            alert("Key Person contact is required!");
            return;
        }
        if ($('#meeting_date').val().trim() == '') {
            alert("Date-Time is required!");
            return;
        }

        $.ajax({
            url: 'ajax/schedule_listings_save.php',
            type: 'POST',
            data: "cms_assignment_id=" + "<?php echo $cms_assignment_id ?>" + "&schduled_id=" + "<?php echo $schduled_id ?>" + "&listing_id=" + "<?php echo $listing_id ?>" + "&key_person_name=" + $('#key-person-name').val().trim() + "&key_person_contact=" + $('#key-person-contact').val().trim() + "&meeting_date=" + $('#meeting_date').val().trim() + "&current_user=" + "<?php echo $current_user ?>",
            beforeSend: function () {
                $("body").addClass("loading");
            },
            success: function (dt) {
                $("body").removeClass("loading");
                if (dt.trim() == '1') {
                    alert("Schedule saved successfully!");
                    //jQuery.fancybox.close();
                    window.location = "listing_assignment_list.php";
                } else {
                    alert("Scheduling Failed");
                }
            }
        });
    }
    function cancel_scheduling() {
        jQuery.fancybox.close();
    }
    $(document).ready(function () {

        $('#create-lot').on('click', function () {
            window.location = 'create_content_lot.php';
        });

    });
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode == 99 || charCode == 118)
            return true;
        if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
            return false;

        return true;
    }
</script>