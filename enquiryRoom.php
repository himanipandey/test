<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");

if (isset($_POST['newRoom'])) {

    $roomType = $_POST['newRoom'];
    $sql = "INSERT INTO " . ROOM_CATEGORY . " (`CATEGORY_NAME`) VALUES ('$roomType')";

    $row = mysql_query($sql) or die(mysql_error());

    echo "New Room Type added successfully!";
} else {

    $count = $_POST['count'];
    $optionId = trim($_POST['optionId']);
    $allRooms = $_POST['roomCategory'];
    $rowId = $_POST['rowId'];

    $error = '';
    $masterBedroomsArea = 0;
    foreach ($allRooms as $row) {
        $catID = $row['id'];
        $lengthFT = $row['length_ft'];
        $lengthIH = $row['length_inch'];
        $breathFT = $row['breath_ft'];
        $breathIH = $row['breath_inch'];
        $title = $row['title'];

        if (($lengthFT == '' || $lengthFT == 0) && in_array($catID, array(1, 2, 3, 7, 8, 9, 10))) {
            $error[] = 'Length(ft) is required for ' . $title;
        }
        if (($breathFT == '' || $breathFT == 0) && in_array($catID, array(1, 2, 3, 7, 8, 9, 10))) {
            $error[] = 'Breath(ft) is required for ' . $title;
        }

        if ($lengthFT && $breathFT && $catID == 1) {
            $masterBedroomsArea = ($lengthFT * 12 + $lengthIH) * ($breathFT * 12 + $breathIH);
        }

        if ($lengthFT && $breathFT && $catID == 2) {
            $otBedroomsArea = ($lengthFT * 12 + $lengthIH) * ($breathFT * 12 + $breathIH);
            if ($otBedroomsArea >= $masterBedroomsArea)
                $error[] = 'Area of Master Bedroom should be greater than other bedroom area';
        }
    }

    if (empty($error)) {
        
        $createdAt = date('Y-m-d H:i:s');

        //check if oldrecord exist
        $sql_old = "SELECT created_at FROM " . PROJECT_OPTIONS_ROOM_SIZE . " WHERE `OPTIONS_ID` = '$optionId' limit 1";
        $res_old = mysql_query($sql_old) or die(mysql_error());

        if ($res_old) {
            $res_old = mysql_fetch_object($res_old);
            $createdAt = ($res_old->created_at == '0000-00-00 00:00:00')? date('Y-m-d H:i:s') : $res_old->created_at;
        }

        //deleted old records
        $sql_del_old = "DELETE FROM " . PROJECT_OPTIONS_ROOM_SIZE . " WHERE `OPTIONS_ID` = '$optionId'";
        mysql_query($sql_del_old) or die(mysql_error());

        foreach ($allRooms as $row) {
            $catID = $row['id'];
            $lengthFT = $row['length_ft'];
            $lengthIH = $row['length_inch'];
            $breathFT = $row['breath_ft'];
            $breathIH = $row['breath_inch'];
            $updatedBy = $_SESSION['adminId'];
            $createdAt = $createdAt;

            if ((($lengthFT == '' &&  $lengthIH == '') || ($breathFT == '' && $breathIH == '')) || $catID == '') {
                continue;
            }

            $sql = "INSERT INTO " . PROJECT_OPTIONS_ROOM_SIZE . " (`OPTIONS_ID`,`ROOM_CATEGORY_ID`,`ROOM_LENGTH`,`ROOM_BREATH`, `ROOM_LENGTH_INCH`, `ROOM_BREATH_INCH`, `updated_by`, `created_at`) VALUES ('$optionId',' $catID','$lengthFT', '$breathFT', '$lengthIH','$breathIH', '$updatedBy', '$createdAt')";

            $row = mysql_query($sql) or die(mysql_error());
        }

        echo "";
    } else {
        echo implode("\n", $error);
    }
}
?>
