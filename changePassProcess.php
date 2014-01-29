<?php
$smarty->assign("page", $_GET['page']);
$smarty->assign("sort", $_GET['sort']);
if ($_POST['Save'] == "Save") {
    $phoneNo = getAdminPhoneNo();
    $newPhoneNo = stripPhoneNumber($_POST['phoneno']);
    if(!(strval($newPhoneNo) === strval($phoneNo))){
        if(strlen($newPhoneNo)<10) $ErrorMsg[] = "Please put in a valid phone no.";
        else{
            $admin = ProptigerAdmin::find($_SESSION['adminId']);
            $admin->mobile = $newPhoneNo;
            $admin->save();
            $ErrorMsg[] = "Cloudagent phone no changed successfully.";
        }
    }
    else{
        $oldpassword = trim($_POST['oldpassword']);
        $newpassword = trim($_POST['newpassword']);
        $reNewpassword = trim($_POST['reNewpassword']);

        $smarty->assign("oldpassword", $oldpassword);
        $smarty->assign("LoginError", $newpassword);
        $smarty->assign("reNewpassword", $LoginError);

        if ($oldpassword == '') {
            $ErrorMsg[] = "Old  Password is required.";
        }
        if ($newpassword == '') {
            $ErrorMsg[] = "New Password is required.";
        } else if (strlen($newpassword) < 5) {
            $ErrorMsg[] = 'New Password is required and minimum 5 characters. <br>';
        }
        if ($reNewpassword == '') {
            $ErrorMsg[] = "Re-enter New Password is required.";
        }
        if ($newpassword != $reNewpassword) {
            $ErrorMsg[] = 'New Password  and Re-enter New Password do not match. <br>';
        }

        if (is_array($ErrorMsg)) {
            // Do Nothing
        } else {
            $checkRecord = UpdateAdminPssword($newpassword, $oldpassword, $_SESSION['adminId']);

            if ($checkRecord == 1) {
                $ErrorMsg[] = 'Your password has been updated successfully. <br>';
            } else {
                $ErrorMsg[] = 'Old password is not match password. <br>';
            }
            //header("Location:changePass.php");
        }
    }
    $smarty->assign("ErrorMsg", $ErrorMsg);
} else if (isset($_POST['Exit'])) {
    header("Location:ProjectList.php");
}

$phoneNo = getAdminPhoneNo();
$smarty->assign("phoneNo", $phoneNo);


function getAdminPhoneNo(){
    $adminDetails = AdminDetail($_SESSION['adminId']);
    return $phoneNo = $adminDetails['MOBILE'];
}

function stripPhoneNumber($phNo){
    $phNo = preg_replace('/[^0-9,]|,[0-9]*$/','',$phNo);
    return $phNo;
}
?>