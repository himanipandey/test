<?php
session_start();
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");


$listing_ids = $_POST['selected_rows'];
$current_user = $_POST['current_user'];

//fetching photographers under FieldManager
$pgf_sql = mysql_query("SELECT adminid, fname from proptiger_admin where status = 'Y' AND manager_id = '".$current_user."'") or die(mysql_error());


?>
<table cellpadding=7>
    <tr style="background-color: #666666; color:#fff">
        <th colspan="2">
            Assign Listings 
        </th>
    </tr>
    <tr>
        <td>
            <b>Listings:</b><br/>
            <select multiple="true" id="listing-ids">
                <?php 
                    foreach($listing_ids as $id => $name){
                        echo '<option value="'.$id.'">'.$name.'</option>';
                    }
                ?>
            </select>
        </td>
        <td>
            <b>Photographers:</b><br/>
            <select  id="photographers">
                <?php 
                    if(mysql_num_rows($pgf_sql)){
                        while($row = mysql_fetch_object($pgf_sql)){
                            echo '<option value="'.$row->adminid.'">'.$row->fname.'</option>';
                        }  
                    }else{
                        echo '<font color="red">You don\'t have Photographers to assign!. </font>';
                    }
                    
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center">
            <input type="button" class="page-button" id="schedule-btn" value="Assign"  onclick="save_assignment()"/>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="button" class="page-button" id="cancel-btn" value="Cancel" onclick="cancel_assignment()"/>
        </td>

    </tr>
</table>
<script type="text/javascript">
    function save_assignment() {       
        $.ajax({
            url: 'ajax/assign_listings_save.php',
            type: 'POST',
            data: "listing_ids=" + '<?php echo json_encode($listing_ids) ?>' + "&photographers=" + $('#photographers').val().trim() + "&current_user=" + "<?php echo $current_user ?>",
            beforeSend: function () {
                $("body").addClass("loading");
            },
            success: function (dt) {
                $("body").removeClass("loading");
                if (dt.trim() == '1') {
                    alert("Listings Assigned Successfully!");
                    //jQuery.fancybox.close();
                    window.location = "listing_assignment_list.php";
                } else {
                    alert("Assignment Failed");
                }
            }
        });
        
    }
    function cancel_assignment() {
        jQuery.fancybox.close();
    }
   
</script>