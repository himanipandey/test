<?php
include("../smartyConfig.php");
include("../appWideConfig.php");
include("../dbConfig.php");
include("../modelsConfig.php");
include("../includes/configs/configs.php");
include("../imageService/image_upload.php");

$projectId = $_POST['projectId'];
$projectType = $_POST['projectType'];

$qry = "SELECT 
               d.AudioLink,a.FNAME,d.CampaignName,d.Remark,d.ContactNumber,d.StartTime,d.EndTime,d.media_service_status,p.BROKER_ID,p.CallId 
            FROM 
               (" . CALLDETAILS . " d LEFT JOIN " . CALLPROJECT . " p 
            ON
               d.CallId = p.CallId)
            LEFT JOIN
               " . ADMIN . " a
            ON 
               d.AgentId = a.ADMINID
            WHERE
               p.ProjectId = $projectId
            AND
               d.PROJECT_TYPE = '$projectType'";
$res = mysql_query($qry) or die(mysql_error());
$arrCallLink = array();
if (mysql_num_rows($res) > 0) {
    while ($data = mysql_fetch_assoc($res)) {
        if ($data['media_service_status'] == 'Uploaded') {
            $url = AUDIO_SERVICE_URL . "?objectType=call&objectId=" . $data['CallId'];
            $content = file_get_contents($url);
            $imgPath = json_decode($content);

            foreach ($imgPath->data as $k1 => $v1) {
                $service_audio_path = $v1->absoluteUrl;
            }
            if (isset($service_audio_path) && $service_audio_path != '')
                $data['AudioLink'] = $service_audio_path;
        }

        array_push($arrCallLink, $data);
    }
}
?>
<?php if (count($arrCallLink) > 0): ?>

    <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">

        <tr class="headingrowcolor" height="30px;">
            <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >SNo.</td>
            <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >Caller Name</td>
            <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >Start Time</td>
            <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >End Time</td>
            <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >Contact No</td>
            <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >Audio Link</td>
            <?php if ($projectType == 'primary'): ?>
                <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >Campaign Name</td>
            <?php endif; ?>
            <td nowrap="nowrap" width="90%" align="left" class=whiteTxt>Remark</td>
            <?php if ($projectType == 'secondary'): ?>
                <td nowrap="nowrap" width="90%" align="left" class=whiteTxt>Action</td>
            <?php endif; ?>
        </tr>

        <?php foreach ($arrCallLink as $key => $item): ?> 
            <?php
            if (($key + 1) % 2 == 0) {
                $color = "bgcolor='#F7F8E0'";
            } else {
                $color = "bgcolor='#f2f2f2'";
            }
            ?>
            <tr <?php echo $color ?> height="25px;">
                <td nowrap="nowrap" width="5%" align="center">
                    <?php echo $key + 1 ?>
                </td>
                <td width ="10%">
                    <?php echo $item['FNAME'] ?>
                </td>
                <td width ="15%">
                    <?php echo $item['StartTime'] ?>
                </td>
                <td width ="15%">
                    <?php echo $item['EndTime'] ?>
                </td>
                <td width ="10%" nowrap>
                    <?php echo $item['ContactNumber'] ?>
                </td>
                <td width ="30%" nowrap>
                    <a href = "<?php echo $item['AudioLink'] ?>" target=_blank><?php echo $item['AudioLink'] ?></a>
                </td>
                <?php if ($projectType == 'primary'): ?>
                    <td width ="90%">
                        <?php echo $item['CampaignName'] ?>
                    </td>
                <?php endif; ?>
                <td width ="90%">
                    <?php echo $item['Remark'] ?>
                </td>
                <?php if ($projectType == 'secondary'): ?>
                    <td width ="90%">
                        <a href="javascript:void(0);" name="call_edit" value="Edit" onclick="return broker_call_edit(<?php echo $item['CallId'] ?>, <?php echo $item['BROKER_ID'] ?>);" >Edit</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <?php echo 'Empty'; ?>
<?php endif; ?>