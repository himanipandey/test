<?php

//ini_set(display_errors, 1);
//ini_set(error_reporting, E_ALL);
set_time_limit(0);
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
AdminAuthentication();

require_once("includes/class_supply.php");
require_once("includes/class_project.php");
require_once("common/start.php");

$projObj= new Project($db_project);
$supObj = new Supply($db_project);

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

?>
<link href="/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/bootstrap/js/bootstrap.js"></script>
<link href="/css/supply.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/supplyentry.js" ></script>

<?php
$readonly="no";

$brokerList = $supObj->GetBrokerList();

if($_REQUEST && $_REQUEST['edit']>0) {
    $id=$_REQUEST['edit'];
    $readonly="yes";

    $listing=$supObj->getListingByID($_REQUEST['edit']);
    $optionData=$projObj->getOptionDetails($listing[0]['PROPERTY_OPTION_ID']);
    $citylocality=$projObj->getProjectCityLocality($listing[0]['PROJECT_ID']);
    $Builder=$projObj->getProjectBuilder($listing[0]['PROJECT_ID']);
    $towerinfo=$projObj->getTowerDetails($listing[0]['TOWER_ID']);
    $completion=$projObj->getProjectCompletion($listing[0]['PROJECT_ID']);
    $projid=$listing[0]['PROJECT_ID'];
    $param=array('proid'=>$projid);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, SERVER_URL."/typeahead.php");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $projectDetails = curl_exec($ch);
    curl_close($ch);
    $projectDetails = json_decode( $projectDetails, TRUE );
    $yr=date('Y');
    $yrcons=substr($projectDetails[0]['COMPLETION_DATE'],-4,4);
    $yrcons=intval($yr)-intval($yrcons);
    if($yrcons==$yr) {
        $yrcons="NA";
        $projectDetails[0]['COMPLETION_DATE']="Not Available";
    }

    $_REQUEST['in_price']=$listing[0]['INDICATIVE_PRICE'];
    $_REQUEST['year_of_cons']=$yrcons;
    $_REQUEST['possdate']=$projectDetails[0]['COMPLETION_DATE'];
    $_REQUEST["contact_person_type"]=$listing[0]['CONTACT_TYPE'];
    $_REQUEST["contact_name"]=$listing[0]['CONTACT_NAME'];
    $_REQUEST["email"]=$listing[0]['CONTACT_EMAIL'];
    $_REQUEST["mobile_no"]=$listing[0]['CONTACT_MOBILE'];
    $_REQUEST["login_rate"]=$listing[0]['LOGIN_RATE'];
    $_REQUEST["login_rate_type"]=$listing[0]['LOGIN_RATE_UNIT'];
    $_REQUEST["dmd_rate"]=$listing[0]['DEMAND_RATE'];
    $_REQUEST["dmd_rate_type"]=$listing[0]['DEMAND_RATE_UNIT'];
    $_REQUEST["oth_charge"]=$listing[0]['OTHER_CHARGE'];
    $_REQUEST["oth_charge_type"]=$listing[0]['OTHER_CHARGE_UNIT'];
    $_REQUEST["park_charge"]=$listing[0]['PARKING_CHARGE'];
    $_REQUEST["amt_paid"]=$listing[0]['AMOUNT_PAID'];
    $_REQUEST["amt_paid_type"]=$listing[0]['AMOUNT_PAID_UNIT'];
    $_REQUEST["on_home_loan"]=$listing[0]['ON_HOME_LOAN'];
    $_REQUEST["negotiable"]=$listing[0]['IS_NEGOTIABLE'];;
    $_REQUEST["remark"]=$listing[0]['REMARK'];
    $_REQUEST["projectid"]=$listing[0]['PROJECT_ID'];
    $_REQUEST["projectname"]=$listing[0]['PROJECT_NAME'];
    $_REQUEST["cityname"]=$citylocality['CITY'];
    $_REQUEST["localityname"]=$citylocality['LOCALITY'];
    $_REQUEST["builder"]=$Builder;
    $_REQUEST["flat_no"]=$listing[0]['FLAT_NO'];
    $_REQUEST["floor_no"]=$listing[0]['FLOOR_NO'];
    $_REQUEST["desc"]=$listing[0]['DESCRIPTION'];
    $_REQUEST["address"]=$listing[0]['ADDRESS'];
    $_REQUEST["ava_prop"]=$optionData[0]['UNIT_NAME']."(".$optionData[0]['SIZE']." ".$optionData[0]['MEASURE'].")";
    $_REQUEST["tow_det"]=$towerinfo[0]['TOWER_NAME'];
}
else if ( $_REQUEST && $_REQUEST['submit_supply_entry'] == 1 ) {
    //echo "<pre>";print_r($_REQUEST);echo "</pre>";
    $allOk = true;
    $inputParam = array();
    $errorArr = array();
    if ( strtolower( $_REQUEST['contact_person_type'] ) == "broker" ) {
        //  broker
        if ( $_REQUEST['broker_id'] > 0 ) {
            $brokerInfo = $supObj->GetBrokerById( $_REQUEST['broker_id'] );
            if ( $brokerInfo ) {
                $inputParam['CONTACT_TYPE'] = $brokerInfo['BROKER_ID'];
                $inputParam['CONTACT_NAME'] = $brokerInfo['BROKER_NAME'];
                $inputParam['CONTACT_EMAIL'] = $brokerInfo['BROKER_EMAIL'];
                $inputParam['CONTACT_MOBILE'] = $brokerInfo['BROKER_MOBILE'];
            }
            else {
                //  false broker info
                $allOk = false;
            }
        }
        else {
            //  false broker info
            $allOk = false;
        }
    }
    else {
        //  owner
        $inputParam['CONTACT_TYPE'] = 0;

        $name = trim( $_REQUEST['contact_name'] );
        if ( $name != "" ) {
            $inputParam['CONTACT_NAME'] = $name;
        }
        else {
            $errorArr[] = 'contact_name';
        }

        $email = trim( $_REQUEST['email'] );
        if ( $email != "" ) {
            $inputParam['CONTACT_EMAIL'] = $email;
        }
        else {
            $errorArr[] = 'email';
        }

        $mobileNo = trim( $_REQUEST['mobile_no'] );
        if ( is_numeric( $mobileNo ) && $mobileNo != "" ) {
            $inputParam['CONTACT_MOBILE'] = $mobileNo;
        }
        else {
            $errorArr[] = 'mobile_no';
        }
    }

    if ( is_numeric( $_REQUEST['login_rate'] ) && $_REQUEST['login_rate'] > 0 ) {
        $inputParam['LOGIN_RATE'] = $_REQUEST['login_rate'];
        if ( $_REQUEST['login_rate_type'] == 2 ) {
            $inputParam['LOGIN_RATE_UNIT'] = "SQYD";
        }
        else {
            $inputParam['LOGIN_RATE_UNIT'] = "SQFT";
        }
    }
    elseif ( trim( $_REQUEST['login_rate'] ) == '' ) {
        //  do nothing ---> to skip validation
    }
    else {
        $errorArr[] = 'login_rate';
        $allOk = false;
    }

    if ( is_numeric( $_REQUEST['in_price'] ) && $_REQUEST['in_price'] > 0 ) {
        $inputParam['INDICATIVE_PRICE'] = $_REQUEST['in_price'];
    }

    if ( is_numeric( $_REQUEST['dmd_rate'] ) && $_REQUEST['dmd_rate'] > 0 ) {
        $inputParam['DEMAND_RATE'] = $_REQUEST['dmd_rate'];
        if ( $_REQUEST['dmd_rate_type'] == 2 ) {
            $inputParam['DEMAND_RATE_UNIT'] = "SQYD";
        }
        else {
            $inputParam['DEMAND_RATE_UNIT'] = "SQFT";
        }
    }

    //  one of demand rate and indicative rate is compulsory
    $dRate = trim( $_REQUEST['dmd_rate'] );
    $iRate = trim( $_REQUEST['in_price'] );
    if ( ( $dRate == 0 || $dRate == "" ) && ( $iRate == 0 || $iRate == "" ) ) {
        $errMsg[] = "One of demand and indicative rate is compulsory";
        $allOk = false;
    }

    if ( is_numeric( $_REQUEST['oth_charge'] ) && $_REQUEST['oth_charge'] > 0 ) {
        $inputParam['OTHER_CHARGE'] = $_REQUEST['oth_charge'];
        if ( $_REQUEST['oth_charge_type'] == 2 ) {
            $inputParam['OTHER_CHARGE_UNIT'] = "SQYD";
        }
        else {
            $inputParam['OTHER_CHARGE_UNIT'] = "SQFT";
        }
    }
    elseif ( trim( $_REQUEST['oth_charge'] ) == '' ) {
        //  do nothing
    }
    else {
        $errorArr[] = 'oth_charge';
        $allOk = false;
    }

    if ( is_numeric( $_REQUEST['park_charge'] ) && $_REQUEST['park_charge'] > 0 ) {
        $inputParam['PARKING_CHARGE'] = $_REQUEST['park_charge'];
    }
    elseif ( trim( $_REQUEST['park_charge'] ) == '' ) {
        //  do nothing
    }
    else {
        $errorArr[] = 'park_charge';
        $allOk = false;
    }

    if ( is_numeric( $_REQUEST['amt_paid'] ) && $_REQUEST['amt_paid'] > 0 ) {
        $inputParam['AMOUNT_PAID'] = $_REQUEST['amt_paid'];
        if ( $_REQUEST['amt_paid_type'] == "per" ) {
            $inputParam['AMOUNT_PAID_UNIT'] = "PERCENT";
        }
        elseif ( $_REQUEST['amt_paid_type'] == "inr" ) {
            $inputParam['AMOUNT_PAID_UNIT'] = "INR";
        }
    }
    elseif ( trim( $_REQUEST['amt_paid'] ) == '' ) {
        //  do nothing
    }
    else {
        $errorArr[] = 'amt_paid';
        $allOk = false;
    }

    if ( $_REQUEST['on_home_loan'] == 1 ) {
        $inputParam['ON_HOME_LOAN'] = 1;
    }
    else {
        $inputParam['ON_HOME_LOAN'] = 0;
    }

    if ( $_REQUEST['negotiable'] == "negotiable" ) {
        $inputParam['IS_NEGOTIABLE'] = 1;
    }
    else {
        $inputParam['IS_NEGOTIABLE'] = 0;
    }

    $remark = trim( $_REQUEST['remark'] );
    if ( $remark != "" ) {
        $inputParam['REMARK'] = $remark;
    }

    //  Project Info
    $projectId = trim( $_REQUEST['project_id'] );
    if ( $projectId > 0 ) {
        $projectName = trim( $_REQUEST['project_name'] );
        if ( $projectName != "" ) {
            $inputParam['PROJECT_ID'] = $projectId;
            $inputParam['PROJECT_NAME'] = $projectName;
        }
        else {
            $allOk = false;
            $errorArr[] = 'project_name';
        }
    }
    else {
        //  incorrect project id !
        $allOk = false;
        $errorArr[] = 'project_id';
    }

    $flatNo = trim( $_REQUEST['flat_no'] );
    if ( strlen( $flatNo ) > 0 ) {
        $inputParam['FLAT_NO'] = $flatNo;
    }
    elseif ( trim( $_REQUEST['flat_no'] ) == '' ) {
        //  do nothing
    }
    else {
        $allOk = false;
        $errorArr[] = 'flat-no';
    }

    if ( is_numeric( $_REQUEST['floor_no'] ) && $_REQUEST['floor_no'] > 0 ) {
        $inputParam['FLOOR_NO'] = $_REQUEST['floor_no'];
    }
    elseif ( trim( $_REQUEST['floor_no'] ) == '' ) {
        //  do nothing
    }
    else {
        $allOk = false;
        $errorArr[] = 'flat-no';
    }

    $addr = trim( $_REQUEST['address'] );
    if ( strlen( $addr ) > 0 ) {
        $inputParam['ADDRESS'] = $addr;
    }
    elseif ( trim( $_REQUEST['address'] ) == '' ) {
        //  do nothing
    }
    else {
        $allOk = false;
        $errorArr[] = 'address';
    }

    $desc = trim( $_REQUEST['desc'] );
    if ( strlen( $desc ) > 0 ) {
        $inputParam['DESCRIPTION'] = $desc;
    }
    elseif ( trim( $_REQUEST['desc'] ) == '' ) {
        //  do nothing
    }
    else {
        $allOk = false;
        $errorArr[] = 'desc';
    }

    $towerId = $_REQUEST['tower_id'];
    if ( is_numeric( $towerId ) && $towerId > 0 ) {
        $inputParam['TOWER_ID'] = $towerId;
    }

    $propertyOptionId = $_REQUEST['available_prop_id'];
    if ( is_numeric( $propertyOptionId ) && $propertyOptionId > 0 ) {
        $inputParam['PROPERTY_OPTION_ID'] = $propertyOptionId;
    }

    //$allOk = false;
    if ( $allOk && sizeof( $errorArr ) == 0 ) {
        $inventoryId = $supObj->AddInventoryToDB( $inputParam );
        if ( $inventoryId > 0 ) {
            //  redirect to next page
            if ( SEND_EMAIL ) {
                //send_mail_now(  )
                $to = RESALE_EMAIL;
                $from = 'no-reply@proptiger.com';
                $cc = RESALE_GROUP_EMAIL;
                $subject = "Resale Entry Added #".$inventoryId;
                $resaleDetail = $supObj->GetInventoryForMail( $inventoryId );
                $body = getBody( $resaleDetail );
                require_once ("send_mail_amazon.php");
                sendRawEmailFromAmazon($to, $from, $cc, $subject, $body, '', '', array($to, $cc));
            }

            header("Location: additionalentry.php?in=".$inventoryId);
            exit;
        }
        else {
            $errMsg = "Unable to add data, please try again.";
        }
    }
    else {
        if ( !empty( $errorArr ) ) {
            $errMsg = "Error in ".implode(", ", $errorArr);
        }
    }
}
elseif ($_REQUEST && $_REQUEST['update_listing']==1) {
    $data=array();
    $data['action']='update_listing';
    $data['ID']=$_REQUEST['ID'];
    $data['CONTACT_TYPE']=$_REQUEST['contact_person_type'];
    $data['CONTACT_NAME']=$_REQUEST['contact_name'];
    $data['CONTACT_EMAIL']=$_REQUEST['email'];
    $data['CONTACT_MOBILE']=$_REQUEST['mobile_no'];
    $data['LOGIN_RATE']=$_REQUEST['login_rate'];
    $data['LOGIN_RATE_UNIT']=$_REQUEST['login_rate_type'];
    $data['DEMAND_RATE']=$_REQUEST['dmd_rate'];
    $data['DEMAND_RATE_UNIT']=$_REQUEST['dmd_rate_type'];
    $data['OTHER_CHARGE']=$_REQUEST['oth_charge'];
    $data['OTHER_CHARGE_UNIT']=$_REQUEST['oth_charge_type'];
    $data['PARKING_CHARGE']=$_REQUEST['park_charge'];
    $data['AMOUNT_PAID']=$_REQUEST['amt_paid'];
    $data['AMOUNT_PAID_UNIT']=$_REQUEST['amt_paid_type'];
    $data['ON_HOME_LOAN']=$_REQUEST['on_home_loan'];
    $data['IS_NEGOTIABLE']=$_REQUEST['negotiable'];
    $data['REMARK']=$_REQUEST['remark'];
    $data['PROJECT_ID']=$_REQUEST['project_id'];
    $data['PROJECT_NAME']=$_REQUEST['project_name'];
    $data['FLAT_NO']=$_REQUEST['flat_no'];
    $data['FLOOR_NO']=$_REQUEST['floor_no'];
    $data['DESCRIPTION']=$_REQUEST['desc'];
    $data['ADDRESS']=$_REQUEST['address'];
    $data['TOWER_ID']=$_REQUEST['towerId'];
    $data['INDICATIVE_PRICE']=$_REQUEST['in_price'];

    $supObj->updateRecord($data);
    header("Location: additionalentry.php?in=".$data['ID']."&action=update");
};

function getBody( $info ) {
    $info['Added By'] = trim( $_SESSION['FNAME']." ".$_SESSION['LNAME'] );
    $info['Available Property'] = $_REQUEST['ava_prop'];
    $info['Locality'] = $_REQUEST['locality_name'];
    $info['Builder Name'] = $_REQUEST['builder_name'];
    $info['Project Name'] = $_REQUEST['project_name'];
    $col = "";
    $val = "";
    foreach( $info as $__col => $__val ) {
        $col .= "<td>$__col</td>";
        $val .= "<td>$__val</td>";
    }
    return "Below resale supply entry has been added in CRM with following details:<br /><br />
            <table border='1'>
                <tr style='background-color: wheat'>
                    $col
                </tr>
                <tr>
                    $val
                </tr>
            </table><br /><br />
            To view all the details, please login in CRM.<br /><br />Regards,<br />CRM Team";
}
?>
<div class="main-container">

    <!-- Main Input form starts here -->
    <div class="form-container">
    <ul class="tabset">
        <li class="active"><a onclick="return false;" href="">Basic Details</a></li>
        <li><a onclick="return false;" href="">Additional Details</a></li>
    </ul>
    <div class="main-form">
        <br />
        <br />
        <?php if ( $errMsg != "" ) { ?>
            <div class="err"><?php echo $errMsg; ?></div>
        <?php } ?>
            <form name="supply_entry" method="POST" action="supplyentry.php">
                <table border="0" class="main-form-table">
                    <tbody>
                    <tr class="trow">
                        <td class="t-input-name">
                            Contact Person Type
                        </td>
                        <td class="t-input-value">
                            <input type="radio" id="is-own" name="contact_person_type" value="Owner" <?php echo ( $_REQUEST['contact_person_type'] != "Broker" ) ? "checked='yes'" : ""; ?> onClick="contactTypeChange('owner')"> Owner
                            <input type="radio" id="is-bro" name="contact_person_type" value="Broker" <?php echo ( $_REQUEST['contact_person_type'] == "Broker" ) ? "checked='yes'" : ""; ?> onClick="contactTypeChange('broker')"> Broker
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Broker
                        </td>
                        <td class="t-input-value">
                            <select id="broker-radio" name="broker_id" class="t-input c-select" disabled="disabled" onChange="fillBrokerInfo();">
                                <option value="" other-data="">Select Broker</option>
                                <?php foreach ($brokerList as $__brokerCount => $__thisBroker) { ?>
                                    <option id="<?php echo "broId-".$__thisBroker['BROKER_ID']; ?>" other-data="<?php echo $__thisBroker['BROKER_EMAIL']."_##_".$__thisBroker['BROKER_MOBILE']; ?>" value="<?php echo $__thisBroker['BROKER_ID']; ?>"><?php echo $__thisBroker['BROKER_NAME']; ?></option>
                                <?php } ?>
                            </select>
                            &nbsp;<a href="#" onClick="window.open('newdetail.php?ac=broker','Add Broker','height=500,width=550');return false;">Add Broker</a>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Contact Name
                        </td>
                        <td class="t-input-value">
                            <input id="contact-name" value="<?php echo $_REQUEST['contact_name'] ?>" class="t-input c-input" type="text" name="contact_name" placeholder="Enter Contact Name (no special characters)" onblur="verifyData('contact-name');">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Contact Email
                        </td>
                        <td class="t-input-value">
                            <input id="email" value="<?php echo $_REQUEST['email'] ?>" class="t-input c-input" type="text" name="email" placeholder="Enter E-Mail" onblur="verifyData('email');">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Mobile Number
                        </td>
                        <td class="t-input-value">
                            +91 <input id="mobile-no" value="<?php echo $_REQUEST['mobile_no'] ?>" class="t-input c-input" type="text" name="mobile_no" placeholder="Enter 10 Digit Mobile Number" onblur="verifyData('mobile-no');" maxlength="10">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="spacer2"></div>
                <div class="sec-head">
                    Value of Property
                </div>
                <hr class="hr-separator">

                <table>
                    <tbody>
                    <tr class="trow">
                        <td class="t-input-name">
                            Login Rate
                        </td>
                        <td class="t-input-value">
                            <input id="login-rate" value="<?php echo $_REQUEST['login_rate']; ?>" class="t-input c-input" type="text" name="login_rate" placeholder="Enter Login Rate (number)" onblur="verifyData('login-rate');">
                            <select class="b-input" name="login_rate_type">
                                <option value="1" <?php if ( $_REQUEST['login_rate_type'] != 2 ) echo "selected"; ?>>per square feet</option>
                                <option value="2" <?php if ( $_REQUEST['login_rate_type'] == 2 ) echo "selected"; ?>>per square yard</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Demand Rate
                        </td>
                        <td class="t-input-value">
                            <input id="dmd-rate" value="<?php echo $_REQUEST['dmd_rate']; ?>" class="t-input c-input" type="text" name="dmd_rate" placeholder="Enter Demand Rate (number)" onblur="verifyData('dmd-rate');">
                            <select id="dmd-rate-unit" class="b-input" name="dmd_rate_type">
                                <option value="1" <?php if ( $_REQUEST['dmd_rate_type'] != 2 ) echo "selected"; ?>>per square feet</option>
                                <option value="2" <?php if ( $_REQUEST['dmd_rate_type'] == 2 ) echo "selected"; ?>>per square yard</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Other Charges
                        </td>
                        <td class="t-input-value">
                            <input id="oth-charge" value="<?php echo $_REQUEST['oth_charge']; ?>" class="t-input c-input" type="text" name="oth_charge" placeholder="Enter Other Charges (number)" onblur="verifyData('oth-charge');">
                            <select class="b-input" name="oth_charge_type">
                                <option value="1" <?php if ( $_REQUEST['oth_charge_type'] != 2 ) echo "selected"; ?>>per square feet</option>
                                <option value="2" <?php if ( $_REQUEST['oth_charge_type'] == 2 ) echo "selected"; ?>>per square yard</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Car Parking, Club etc Charges
                        </td>
                        <td class="t-input-value">
                            <input id="park-charge" value="<?php echo $_REQUEST['park_charge']; ?>" class="t-input c-input" type="text" name="park_charge" placeholder="Enter Car Parking &amp; Club Charges (number)" onblur="verifyData('park-charge')">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Amount Paid
                        </td>
                        <td class="t-input-value">
                            <input id="amt-paid" value="<?php echo $_REQUEST['amt_paid']; ?>" class="t-input c-input" type="text" name="amt_paid" placeholder="Enter Other Charges (number)" onblur="verifyData('amt-paid');">
                            <select class="b-input" name="amt_paid_type">
                                <!--option value="0">Select Payment Type</option-->
                                <option value="per" <?php if ( $_REQUEST['oth_charge_type'] == "per" ) echo "selected"; ?>>%</option>
                                <option value="inr" <?php if ( $_REQUEST['oth_charge_type'] == "inr" ) echo "selected"; ?>>INR</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Indicative Price (Approx)
                        </td>
                        <td class="t-input-value">
                            <input id="in-price" value="<?php echo $_REQUEST['in_price']; ?>" class="t-input c-input" type="text" name="in_price" placeholder="Enter Approx Price in Lacs (eg: 700000)" onblur="verifyData('in-price');">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Is This Property Taken With Home Loan
                        </td>
                        <td class="t-input-value">
                            <input type="radio" name="on_home_loan" value="1"> Yes
                            <input type="radio" name="on_home_loan" value="0" checked="yes"> No
                        </td>
                    </tr>
                    <tr class="trow">
                        <td>
                        </td>
                        <td class="t-input-value">
                            <input type="checkbox" name="negotiable" value="negotiable" <?php if ( $_REQUEST['negotiable'] == "negotiable" ) echo "checked='yes'"; ?> > Negotiable
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Any Remark
                        </td>
                        <td class="t-input-value">
                            <textarea name="remark" style="width:55%;" rows="4" class="c-text"><?php echo $_REQUEST['remark'] ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="spacer2"></div>
                <hr class="hr-seperator">

                <table>
                    <tbody>
                    <tr class="trow">
                        <td class="t-input-name">
                            Project Name
                        </td>
                        <td class="t-input-value">
                            <input class="t-input c-input" id="project-name" type="text" placeholder="Enter Project Name &amp; Select from Auto Select" onkeyup="getProjects()" value="<?php echo $_REQUEST['projectname'] ?>" <?php if($_REQUEST['edit']>0){ echo "readonly='readonly'";} ?>>
                            <input class="t-input c-input" id="act-project-name" type="text" name="project_name" readonly="readonly" style="display: none;border: none;">
                            <input type="hidden" name="project_id" id="project-id" value="<?php echo $_REQUEST['projectid'] ?>">
                            <ul id="auto-sug" class="auto-sug c-input" style="display: none;background-color: antiquewhite">

                            </ul>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            City
                        </td>
                        <td class="t-input-value">
                            <input readonly="readonly" id="pro-city" class="t-input c-input bgw" type="text" name="city_name" placeholder="" value="<?php echo $_REQUEST['cityname'] ?>">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Locality
                        </td>
                        <td class="t-input-value">
                            <input readonly="readonly" id="pro-loc" class="t-input c-input bgw" type="text" name="locality_name" placeholder="" value="<?php echo $_REQUEST['localityname'] ?>">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Builder Name
                        </td>
                        <td class="t-input-value">
                            <input readonly="readonly" id="pro-build" class="t-input c-input bgw" type="text" name="builder_name" placeholder="" value="<?php echo $_REQUEST['builder'] ?>">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Available Properties
                        </td>
                        <td class="t-input-value">
                            <div class="conf-tab" id="ava-prop">
                                <?php echo $_REQUEST['ava_prop'] ?>
                            </div>
                            &nbsp;<a id="add-new-prop" style="display: none;" href="#" onClick="return false;">Add New Property Type</a>
                            <input id="ava-prop-mail" type="hidden" name="ava_prop" value="">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Block / Tower No.
                        </td>
                        <td class="t-input-value">
                            <div class="conf-tab" id="tow-det">
                                <?php echo $_REQUEST['tow_det'] ?>
                            </div>
                            &nbsp;<a id="add-new-tow" style="display: none;" href="#" onClick="return false;">Add New Tower</a>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Flat Number
                        </td>
                        <td class="t-input-value">
                            <input value="<?php echo $_REQUEST['flat_no'] ?>" id="flat-no" class="t-input c-input" name="flat_no" type="text" placeholder="Enter Flat Number" onblur="verifyData('flat-no')">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Property on Which Floor
                        </td>
                        <td class="t-input-value">
                            <input value="<?php echo $_REQUEST['floor_no'] ?>" id="floor-no" class="t-input c-input" name="floor_no" type="text" placeholder="Enter Property Floor Number" onblur="createAdd();verifyData('floor-no');">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Possession Date
                        </td>
                        <td class="t-input-value">
                            <input  id="pos-date" class="t-input c-input" type="text" name="poss_date" value="<?php echo $_REQUEST['possdate'] ?>" readonly="<?php echo $readonly?>">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Year of Construction of Property
                        </td>
                        <td class="t-input-value">
                            <input disabled="disabled" id="pro-cons-date" class="t-input c-input" type="text" name="year_of_cons" value="<?php echo $_REQUEST['year_of_cons'] ?>" readonly="<?php echo $readonly?>">
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Address
                        </td>
                        <td class="t-input-value">
                            <textarea name="address" id="address" style="width:55%;" rows="4" class="c-text" placeholder="Enter Address of Property" onblur="verifyData('address');"><?php echo $_REQUEST['address'] ?></textarea>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Brief Description
                        </td>
                        <td class="t-input-value">
                            <textarea name="desc" id="desc" style="width:55%;" rows="6" class="c-text" placeholder="Enter Description about Properties" onblur="verifyData('desc');"><?php echo $_REQUEST['desc'] ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br /><br />

                <?php if($_REQUEST['edit']>0) { ?>
                <input type="hidden" name="update_listing" value="1">
                <input type="hidden" name="ID" value="<?php echo $_REQUEST['edit'] ?>">
                <?php } else { ?>
                <input type="hidden" name="submit_supply_entry" value="1">
                <?php } ?>
                <button class="frm-btn" onClick="return verifyForm();">Save &amp; Continue</button>
            </form>
        </div>
    </div>
    <!-- Main Input form ends here -->
</div>
<script language="javascript">
    function parentFn( type, json ) {
        var html = "";
        if ( type == "broker" ) {
            html = '<option id="broId-'+json.id+'" other-data="'+json.email+'_##_'+json.mob+'" value="'+json.id+'">'+json.name+'</option>';
            $('#is-bro').attr('checked', 'yes');
            $('#broker-radio').append(html);
            $('#broker-radio').attr('value', json.id);
            fillBrokerInfo();
        }
        else if ( type == "property" ) {
            html = '<span class="conf-sub-tab"><table border="0"><tbody><tr><td rowspan="2">' +
                '<input checked="yes" type="radio" name="available_prop_id" value="'+json.id+'">' +
                '</td><td><div class="prop-type">'+json.name+'</div>'+json.other+'</td></tr></tbody></table></span>';
            $('#ava-prop').append(html);
        }
        else if ( type == "tower" ) {
            html = '<span class="conf-sub-tab"><table border="0"><tbody><tr><td rowspan="2">' +
                '<input checked="yes" type="radio" name="tower_id" id="tower-id-'+json.id+'" value="'+json.id+'"></td><td>' +
                '<div id="tower-name-'+json.id+'" class="prop-type">'+json.name+'</div>Flats: '+json.flat+', Floors: '+json.floor+'</td></tr></tbody></table></span>';
            $('#tow-det').append(html);
        }
    }
</script>
<?php
include('footer.php');
?>