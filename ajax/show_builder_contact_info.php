<?php
session_start();

include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
include("../modelsConfig.php");

$builderID = $_POST['BUILDER_ID'];
$builderName = $_POST['BUILDER_NAME'];
$url = $_POST['URL'];
$website = $_POST['WEBSITE'];

$qry_contact_info = "SELECT * FROM builder_contacts WHERE BUILDER_ID = '" . $builderID . "'";
$resContact = mysql_query($qry_contact_info);
$arrContact = array();
while ($dataContact = mysql_fetch_array($resContact)) {
    $qry = "select * from project_builder_contact_mappings 
             where builder_contact_id = '" . $dataContact['ID'] . "'";
    $res = mysql_query($qry) or die(mysql_error());
    while ($row_ids = mysql_fetch_object($res))
        $dataContact['PROJECTS'][] = $row_ids->project_id;

    array_push($arrContact, $dataContact);
}
$agentId = $_POST['currentUser'];

$sql = "SELECT PROJECT_ID,PROJECT_NAME FROM resi_project 
            WHERE 
                BUILDER_ID = '" . $builderID . "' 
                AND PROJECT_NAME != ''
                and version = 'cms'
                ORDER BY PROJECT_NAME ASC";
$res = mysql_query($sql) or die(mysql_error());
$ProjectList = array();
while ($data = mysql_fetch_assoc($res)) {
    array_push($ProjectList, $data);
}

$arrCampaign = CampaignDids::allCampaign();
?>
<table cellSpacing=0 cellPadding=0 width="100%" style = "border:1px solid #BDBDBD;" align= "center">
    <tr><td>&nbsp;</td></tr>      
    <TR>
        <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b>Builder URL:</b> <?php echo $url ?>&nbsp;&nbsp;&nbsp;&nbsp;<b>Builder Website:</b> <?php echo $website ?></TD>

    </TR>
    <tr><td>&nbsp;</td></tr>    

    <TR style = "display:none;" id = "update_insert_delete">
        <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b><font COLOR="#008000">Data has been Inserted/Updated/Deleted Successfully!</font></b></TD>
    </TR>

    <TR style = "display:none;" id = "update_insert">
        <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b><font COLOR="#008000">Data has been Inserted/Updated/Deleted Successfully!</font></b></TD>
    </TR>

    <TR style = "display:none;" id = "error1">
        <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b><font COLOR="red">Problem in data Insertion/Updation!</font></b></TD>
    </TR>


    <tr class="headingrowcolor" height="30px;">

        <td style = "padding-left:20px;" nowrap="nowrap" width="1%" align="center"class=whiteTxt>SNo.</td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="2%" align="left" class=whiteTxt>Contact Name</td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Phone</td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Click To Call</td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Campaign Name </td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Select Projects for Call </td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Remark </td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Success / Fail </td>
        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Email</td>

        <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Projects</td>
        <td  style = "padding-right:20px;"nowrap="nowrap" width="1%" align="center" class=whiteTxt >Delete </td>  
    </tr>

    <input type = "hidden" name = "builderid" id = "builderId" value = "<?php echo $builderID ?>">

    <?php
    $cnt = 1;
    foreach ($arrContact as $index => $contact) {
        if ($cnt % 2 == 0) {
            $color = "bgcolor = '#F7F7F7'";
        } else {
            $color = "bgcolor = '#FCFCFC'";
        }

        $name = $contact['NAME'];
        $phone = $contact['PHONE'];
        $email = $contact['EMAIL'];
        $projects = $contact['PROJECTS'];
        $id = $contact['ID'];

        echo '<tr><td>&nbsp;</td></tr> ';
        echo '<tr><td>&nbsp;</td></tr> ';

        echo '<tr id="row_1" ' . $color . '>';

        echo '<td align="center" valign= "top">' . $cnt . '</td>';
        echo '<td align="center" valign = "top">
                <input type = "text" name = "name[]" id = "name_' . $cnt . '" value = "' . $name . '" style = "width:150px">

                <input type = "hidden" name = "name_old[]" value = "' . $name . '" style = "width:150px">

                <input type = "hidden" name = "id[]" id = "id_' . $cnt . '" value = "' . $id . '" style = "width:150px">
            </td>';
        echo '<td align="center" valign = "top">
            <input type = "text" name = "phone[]" id = "phone_' . $cnt . '" class="phone_box" value = "' . $phone . '" style = "width:120px"  onkeypress = "return isNumberKey(event);" maxlength = "10">

            <input type = "hidden" name = "phone_old[]" value = "' . $phone . '" style = "width:150px">
        </td>';
        echo '<td align="center" valign = "top">
            <a href="javascript:void(0);" id = "c2c_'.$cnt.'" class="c2c" style = "width:120px"  onclick = "clickToCall(this);"> Click To Call </a>
              </td>';
        echo '<td align="center" valign = "top">
            <select name="campaignName[]" id="campaignName_'.$cnt.'">';
            foreach($arrCampaign as $item){
                echo '<option value="'.$item.'">'.$item.'</option>';
                }
        echo '</select></td>';
        
        echo '<td align="center" valign = "top">

            <select name = "projects_call_[]" id = "projects_call_'.$cnt.'" multiple>
                <option value = "">Select Project</option>';
                foreach($ProjectList as $key=>$item){
                    echo  '<option value = "'.$item['PROJECT_ID'].'" >'.$item['PROJECT_NAME'].'</option>';
                }                 
        echo '</select></td>';
        
        echo '<td align="center" valign = "top">
            <textarea name = "remark_call_[]" id = "remark_call_'.$cnt.'"></textarea>
            </td>';
        
        echo '<td align="center" valign = "top">
                <input type="hidden" name="callId[]" id="callId_'.$cnt.'" value="">
                <a href="javascript:void(0);" id = "success_'.$cnt.'" onclick="setStatus(this);"> Success </a> ||
                <a href="javascript:void(0);" id = "fail_'.$cnt.'" onclick="setStatus(this);"> Fail </a>
            </td>';
        
        echo '<td align="center" valign = "top">
                <input type = "text" name = "email[]" id = "email_'.$cnt.'" value = "'.$email.'" style = "width:160px">
                <input type = "hidden" name = "emails_old[]" value = "'.$email.'" style = "width:150px">
            </td>';
        
        echo '<td align="center" valign = "top">
             <input type = "hidden" name = "projects_old[]" value = "'.$projects.'" style = "width:150px">
            <select name = "projects_call_[]" id = "projects_call_'.$cnt.'" multiple>
                <option value = "">Select Project</option>';
                foreach($ProjectList as $key=>$item){
                    echo  '<option value = "'.$item['PROJECT_ID'].'" >'.$item['PROJECT_NAME'].'</option>';
                }                 
        echo '</select></td>';
        
        echo '<td align="center" valign = "top"><input type="checkbox" name="dlt_'.$cnt.'" id = "'.$cnt.'"></td>';
        
        echo '</tr>';

        $cnt++;
    }
    ?>
    
    <tr id="row_2">

        <td align="center" valign= "top">
            <?php echo $cnt ?>
        </td>

        <td align="center" valign = "top">

            <input type = "text" name = "name[]" id = "name_<?php echo $cnt ?>" value = "" style = "width:150px">
            <input type = "hidden" name = "id[]" id = "id_<?php echo $cnt ?>" value = "blank1" style = "width:150px">
        </td>

        <td align="center" valign = "top">
            <input type = "text" name = "phone[]" id = "phone_<?php echo $cnt ?>" value = "" style = "width:120px"  onkeypress = "return isNumberKey(event);
    " maxlength = "13">
        </td>
        <td align="center" valign = "top">
            <a href="javascript:void(0);
    " id = "c2c_<?php echo $cnt ?>" class="c2c" style = "width:120px"  onclick = "clickToCall(this);
    "> Click To Call </a>
        </td>
        <td align="center" valign = "top">
			<select name="campaignName[]" id="campaignName<?php echo $cnt ?>">
            <?php  foreach($arrCampaign as $item){
                echo '<option value="'.$item.'">'.$item.'</option>';
                }?>              
            </select>
        </td>
        <td align="center" valign = "top">

            <select name = "projects_call_[]" id = "projects_call_<?php echo $cnt ?>" multiple>
                <option value = "">Select Project</option>
                <?php 
                foreach($ProjectList as $key=>$item){
                    echo  '<option value = "'.$item['PROJECT_ID'].'" >'.$item['PROJECT_NAME'].'</option>';
                }
                ?>
            </select>
        </td>
        <td align="center" valign = "top">

            <textarea name = "remark_call_[]" id = "remark_call_<?php echo $cnt ?>'"></textarea>
        </td>
        <td align="center" valign = "top">
            <input type="hidden" name="callId[]" id="callId_<?php echo $cnt ?>" value="">
            <a href="javascript:void(0);" id = "success_<?php echo $cnt ?>'" onclick="setStatus(this);
    "> Success </a> ||
            <a href="javascript:void(0);
    " id = "fail_<?php echo $cnt ?>" onclick="setStatus(this);"> Fail </a>
        </td>

        <td align="center" valign = "top">
            <input type = "text" name = "email[]" id = "email_<?php echo $cnt ?>" value = "" style = "width:160px">
        </td>
        <td align="center" valign = "top">
            <select name = "projects_call_[]" id = "projects_call_<?php echo $cnt ?>" multiple>
                <option value = "">Select Project</option>
                <?php 
                foreach($ProjectList as $key=>$item){
                    echo  '<option value = "'.$item['PROJECT_ID'].'" >'.$item['PROJECT_NAME'].'</option>';
                }
                ?>
            </select>
        </td>
        <td align="center" valign = "top"><input type="checkbox" name="dlt_<?php echo $cnt ?>" id = "<?php echo $cnt ?>"></td>


    </tr>

    <tr id="row_3">

        <td align="center" valign= "top">
            <?php echo ++$cnt ?>
        </td>

        <td align="center" valign = "top">

            <input type = "text" name = "name[]" id = "name_<?php echo $cnt ?>" value = "" style = "width:150px">
            <input type = "hidden" name = "id[]" id = "id_<?php echo $cnt ?>" value = "blank1" style = "width:150px">
        </td>

        <td align="center" valign = "top">
            <input type = "text" name = "phone[]" id = "phone_<?php echo $cnt ?>" value = "" style = "width:120px"  onkeypress = "return isNumberKey(event);
    " maxlength = "13">
        </td>
        <td align="center" valign = "top">
            <a href="javascript:void(0);
    " id = "c2c_<?php echo $cnt ?>" class="c2c" style = "width:120px"  onclick = "clickToCall(this);
    "> Click To Call </a>
        </td>
        <td align="center" valign = "top">
			<select name="campaignName[]" id="campaignName<?php echo $cnt ?>">
            <?php  foreach($arrCampaign as $item){
                echo '<option value="'.$item.'">'.$item.'</option>';
                }?>              
            </select>
        </td>
        <td align="center" valign = "top">

            <select name = "projects_call_[]" id = "projects_call_<?php echo $cnt ?>" multiple>
                <option value = "">Select Project</option>
                <?php 
                foreach($ProjectList as $key=>$item){
                    echo  '<option value = "'.$item['PROJECT_ID'].'" >'.$item['PROJECT_NAME'].'</option>';
                }
                ?>
            </select>
        </td>
        <td align="center" valign = "top">

            <textarea name = "remark_call_[]" id = "remark_call_<?php echo $cnt ?>'"></textarea>
        </td>
        <td align="center" valign = "top">
            <input type="hidden" name="callId[]" id="callId_<?php echo $cnt ?>" value="">
            <a href="javascript:void(0);" id = "success_<?php echo $cnt ?>'" onclick="setStatus(this);
    "> Success </a> ||
            <a href="javascript:void(0);
    " id = "fail_<?php echo $cnt ?>" onclick="setStatus(this);"> Fail </a>
        </td>

        <td align="center" valign = "top">
            <input type = "text" name = "email[]" id = "email_<?php echo $cnt ?>" value = "" style = "width:160px">
        </td>
        <td align="center" valign = "top">
            <select name = "projects_call_[]" id = "projects_call_<?php echo $cnt ?>" multiple>
                <option value = "">Select Project</option>
                <?php 
                foreach($ProjectList as $key=>$item){
                    echo  '<option value = "'.$item['PROJECT_ID'].'" >'.$item['PROJECT_NAME'].'</option>';
                }
                ?>
            </select>
        </td>
        <td align="center" valign = "top"><input type="checkbox" name="dlt_<?php echo $cnt ?>" id = "<?php echo $cnt ?>"></td>


    </tr>

    <tr><td>&nbsp;</td></tr>         
    <tr class = "headingrowcolor">
        <td align="right" nowrap  colspan= "15">
            <input type="hidden" name="projectId" value="{$projectId}" id ="projectId"/>

            <input type="button" name="btnSave" id="btnSave" value="Save" onclick = "return chkConfirm(<?php echo count($arrContact)?>);" />

        </td>
    </tr>
</table>
