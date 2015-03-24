<?php

$bankid = $_REQUEST['bank_id'];
$smarty->assign("bankid", $bankid);

if ($_REQUEST['submit'] == "Submit") { // add
    $bankname = trim($_REQUEST['bankname']);
    $bank_detail = trim($_REQUEST['bank_detail']);
    $logo_name = $_FILES['logo']['name'];


    $banks = new BankList();
    $banks->bank_name = $bankname;
    $banks->bank_detail = $bank_detail;
    $banks->status = "Active";
    $banks->save();

    $bankid = $banks->bank_id;
    $altText = $bankname . " " . "Home Loan";
    if ($logo_name != '' && $bankid != '') {

        $img = array();
        $img['error'] = $_FILES['logo']["error"];
        $img['type'] = $_FILES['logo']["type"];
        $img['name'] = $_FILES['logo']["name"];
        $img['tmp_name'] = $_FILES['logo']["tmp_name"];

        $tmp = array();
        $tmp['image'] = "@" . $img['tmp_name'];
        $tmp['objectId'] = $bankid;
        $tmp['objectType'] = "bank";
        $tmp['imageType'] = "logo";
        $tmp['title'] = $bankname;
        $tmp['altText'] = $altText;
        $unitImageArr['upload_from_tmp'] = "yes";
        $unitImageArr['method'] = "POST";
        $unitImageArr['url'] = IMAGE_SERVICE_URL;
        $unitImageArr['params'] = $tmp;
        $postArr[] = $unitImageArr;

        $response = writeToImageService($postArr);

        /**/
        foreach ($response as $k => $v) {

            if (empty($v->error->msg)) {
                $image_id = $v->data->id;
            } else {
                $Error = $v->error->msg;
            }
        }
    }

    if ($logo_name != '' && $image_id) {
        $banks->bank_logo = $logo_name;
        $banks->service_image_id = $image_id;
    }

    $banks->save();
    if (empty($Error))
        header("Location:bank_list.php?page=1&sort=all");
}else if ($_REQUEST['update'] == "Update") { //edit
    $bankname = trim($_REQUEST['bankname']);
    $bank_detail = trim($_REQUEST['bank_detail']);
    $logo_name = $_FILES['logo']['name'];


    $banks = BankList::find($bankid);
    $bankid = $banks->bank_id;
    if ($_REQUEST['image_id'])
        $service_image_id = $_REQUEST['image_id'];
    else
        $service_image_id = 0;
    $altText = $bankname . " " . "Home Loan";

    if ($logo_name != '') {

        $img = array();
        $img['error'] = $_FILES['logo']["error"];
        $img['type'] = $_FILES['logo']["type"];
        $img['name'] = $_FILES['logo']["name"];
        $img['tmp_name'] = $_FILES['logo']["tmp_name"];

        $tmp = array();
        $tmp['image'] = "@" . $img['tmp_name'];
        $tmp['objectId'] = $bankid;
        $tmp['objectType'] = "bank";
        $tmp['imageType'] = "logo";
        $tmp['title'] = $bankname;
        $tmp['altText'] = $altText;
        if ($service_image_id > 0) {
            $tmp['service_image_id'] = $service_image_id;
            $tmp['update'] = "yes";
            $unitImageArr['url'] = IMAGE_SERVICE_URL . "/" . $service_image_id;
        }else{
            $unitImageArr['url'] = IMAGE_SERVICE_URL;
        }
        $unitImageArr['upload_from_tmp'] = "yes";
        $unitImageArr['method'] = "POST";
        
        $unitImageArr['params'] = $tmp;
        $postArr[] = $unitImageArr;

        $response = writeToImageService($postArr);


        /**/
        foreach ($response as $k => $v) {
            if (empty($v->error->msg)) {
                $image_id = $v->data->id;
            } else {
                $Error = $v->error->msg;
            }
        }
    }

    $banks->bank_name = $bankname;
    $banks->bank_detail = $bank_detail;

    if ($logo_name != '' && $image_id) {
        $banks->bank_logo = $logo_name;
        $banks->service_image_id = $image_id;
    } elseif (isset($_POST['bankLogo']) && $_POST['bankLogo'] == 'del-logo') {
        $service_image_id = $_REQUEST['image_id'];
        $params = array(
            "service_image_id" => $service_image_id,
            "delete" => "yes",
        );
        $postArr = array();
        $unitImageArr = array();
        $unitImageArr['img'] = "";
        $unitImageArr['objectId'] = $bankid;
        $unitImageArr['objectType'] = "bank";
        $unitImageArr['params'] = $params;
        $postArr[] = $unitImageArr;
        $response = writeToImageService($postArr);
        
        $banks->bank_logo = '';
        $banks->service_image_id = 0;
    }


    $banks->save();
    if (empty($Error))
        header("Location:bank_list.php?page=1&sort=all");
}

if ($bankid != '') {
    $bank = BankList::find($bankid);
    $smarty->assign("bankname", $bank->bank_name);
    $smarty->assign("bank_detail", $bank->bank_detail);
    $smarty->assign("img", $bank->bank_logo);
    //$smarty->assign("service_image_id",$bank->service_image_id);
    $smarty->assign("Error", $Error);
}
?>
