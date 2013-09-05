<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swapnil
 * Date: 13/6/13
 * Time: 12:33 AM
 * To change this template use File | Settings | File Templates.
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            .c-tab {
                width: 80%;
                margin: 10%;
            }
            .c-td {
                text-align: left;
                font-weight: bold;
                font-family: Arial, Tahoma,Helvetica, sans-serif;
                font-size: 15px;
                text-align: right;
                padding-right: 10px;
            }
            textarea {
                border-radius: 3px;
                width: 100%;
            }
            input {
                border-radius: 3px;
                width: 100%;
            }
            select {
                width: 60px;
            }
            .err {
                border-color: red;
            }
        </style>
        <script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript">
            function closeWindow( text, type, json ) {
                alert( text );
                window.opener.parentFn( type, json );
                window.close();
            }

            function verifyTowData() {
                var allOk = true,
                    name = $('#t-name').attr('value').trim(),
                    floor = $('#no-floor').attr('value'),
                    flat = $('#no-flat').attr('value');
                if ( /^[1-9][0-9]*$/.test( flat ) ) {
                    $('#no-flat').removeClass('err');
                }
                else {
                    $('#no-flat').addClass('err');
                    allOk = false;
                }
                if ( /^[1-9][0-9]*$/.test( floor ) ) {
                    $('#no-floor').removeClass('err');
                }
                else {
                    $('#no-floor').addClass('err');
                    allOk = false;
                }
                if ( name.length > 0 ) {
                    $('#t-name').removeClass('err');
                }
                else {
                    $('#t-name').addClass('err');
                    allOk = false;
                }
                return allOk;
            }

            function verifyPropData() {
                var allOk = true,
                    val = $('#size').attr('value');

                if ( /^[1-9][0-9]+$/.test( val ) ) {
                    $('#size').removeClass('err');
                }
                else {
                    $('#size').addClass('err');
                    allOk = false;
                }
                return allOk;
            }

            function verifyData() {
                var allOk = true,
                    val;
                val = $('#br_name').attr('value');
                if ( /^[a-zA-Z .]+$/.test( val ) ) {
                    $('#br_name').removeClass('err');
                }
                else {
                    $('#br_name').addClass('err');
                    allOk = false;
                }

                val = $('#cp_name').attr('value');
                if ( val != undefined && /^[a-zA-Z .]+$/.test( val ) ) {
                    $('#cp_name').removeClass('err');
                }
                else {
                    $('#cp_name').addClass('err');
                    allOk = false;
                }

                val = $('#email').attr('value');
                if ( /^[a-zA-Z0-9]+[a-zA-Z0-9._]+@[a-zA-Z_.]+?\.[a-zA-Z]{2,5}$/.test( val ) ) {
                    $('#email').removeClass('err');
                }
                else {
                    $('#email').addClass('err');
                    allOk = false;
                }

                val = $('#mobile').attr('value');
                if ( val != undefined && /^[1-9][0-9]{9}$/.test( val ) ) {
                    $('#mobile').removeClass('err');
                }
                else {
                    $('#mobile').addClass('err');
                    allOk = false;
                }

                val = $('#address').attr('value');
                if ( val.length > 0 ) {
                    $('#address').removeClass('err');
                }
                else {
                    $('#address').addClass('err');
                    allOk = false;
                }
                return allOk;
            }
        </script>
    </head>
    <body>
    <div class="" style="border: 1px solid">
<?php
$action = $_REQUEST['ac'];

if ( !$action ) {
    echo "<script language='javascript'>alert('Nothing to do');window.close();</script>";
    exit;
}

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
AdminAuthentication();

require_once("includes/class_supply.php");
require_once("includes/class_project.php");
require_once("common/start.php");

if ( $_REQUEST['new_broker'] == 1 ) {
    $supObj = new Supply( $db_project );
    $name = trim( $_REQUEST['br_name'] );
    $contact = trim( $_REQUEST['cp_name'] );
    $email = trim( $_REQUEST['email'] );
    $mobile = trim( $_REQUEST['mobile'] );
    $address = trim( $_REQUEST['address'] );
    $brokerId = $supObj->AddBroker( $name, $contact, $email, $mobile, $address );
    if ( $brokerId > 0 ) {
        $json = array(
            'id' => $brokerId,
            'name' => $name,
            'email' => $email,
            'mob' => $mobile
        );
        $json = json_encode( $json );
        echo "<script language='javascript'>closeWindow('Broker Added #$brokerId', 'broker', $json);</script>";
    }
    else {
        $errMsg = "Unable to add to database !";
    }
}
elseif ( $_REQUEST['new_prop'] > 0 ) {
    $proObj = new Project( $db_project );
    $otherDetail = array();
    $type = $_REQUEST['p_type'];
    $bed = trim( $_REQUEST['bed'] );
    $bath = trim( $_REQUEST['bath'] );
    $store = trim( $_REQUEST['store'] );
    $servant = trim( $_REQUEST['servant'] );
    $size = trim( $_REQUEST['size'] );
    $sizeUnit = trim( $_REQUEST['size_unit'] );
    $unitName = array();
    $unitName[] = $bed."BHK";
    if ( $bath > 0 ) {
        $unitName[] = $bath."T";
    }
    if ( $store > 0 ) {
        $unitName[] = $store."SR";
    }

    if ( $type == 3 ) {
        $otherDetail['UNIT_NAME'] = "Plots";
    }
    else {
        $otherDetail['UNIT_NAME'] = implode( '+', $unitName );
    }
    $otherDetail['SIZE'] = $size;
    $otherDetail['MEASURE'] = $sizeUnit;
    $otherDetail['BEDROOMS'] = $bed;
    $otherDetail['BATHROOMS'] = $bath;
    $otherDetail['STUDY_ROOM'] = $store;
    $otherDetail['SERVANT_ROOM'] = $servant;
    if ( $type == 2 ) {
        $otherDetail['UNIT_TYPE'] = "Villa";
    }
    elseif ( $type == 3 ) {
        $otherDetail['UNIT_TYPE'] = "Plot";
        unset( $otherDetail['BEDROOMS'] );
        unset( $otherDetail['BATHROOMS'] );
        unset( $otherDetail['STUDY_ROOM'] );
        unset( $otherDetail['SERVANT_ROOM'] );
    }
    else {
        $otherDetail['UNIT_TYPE'] = "Apartment";
    }
    $otherDetail['CREATED_DATE'] = date('Y-m-d h:m:s');
    $optionId = $proObj->addProjectDetail( $_REQUEST['id'], $otherDetail );
    if ( $optionId > 0 ) {
        $json = array(
            'id' => $optionId,
            'name' => $otherDetail['UNIT_NAME'],
            'other' => $otherDetail['UNIT_TYPE'].' ('.$otherDetail['SIZE'].' '.$otherDetail['MEASURE'].')'
        );
        $json = json_encode( $json );
        echo "<script language='javascript'>closeWindow('Property Added #$optionId', 'property', $json);</script>";
    }
    else {
        $errMsg = "Unable to add to database !";
    }
}
elseif ( $_REQUEST['new_tow'] > 0 ) {
    $proObj = new Project( $db_project );
    $otherDetail = array();
    $otherDetail['PROJECT_ID'] = $_REQUEST['id'];
    $otherDetail['TOWER_NAME'] = mysql_escape_string( trim( $_REQUEST['t_name'] ) );
    $otherDetail['NO_OF_FLOORS'] = $_REQUEST['no_floor'];
    $otherDetail['NO_OF_FLATS'] = $_REQUEST['no_flat'];
    $towerId = $proObj->addTowerDetail( $_REQUEST['id'], $otherDetail );
    if ( $towerId > 0 ) {
        $json = array(
            'id' => $towerId,
            'name' => trim( $_REQUEST['t_name'] ),
            'floor' => $otherDetail['NO_OF_FLOORS'],
            'flat' => $otherDetail['NO_OF_FLATS']
        );
        $json = json_encode( $json );
        echo "<script language='javascript'>closeWindow('Tower Added #$towerId', 'tower', $json);</script>";
    }
    else {
        $errMsg = "Unable to add to database !";
    }
}


switch( $action ) {
    case 'broker':
        ?>
        <form method="post">
            <table class="c-tab" border="0">
                <tbody>
                <tr>
                    <td class="c-td">Broker Name</td>
                    <td class="c-td1"><input type="text" name="br_name" id="br_name" placeholder="Enter Name (no special characters)"></td>
                </tr>
                <tr>
                    <td class="c-td">Contact Person Name</td>
                    <td class="c-td1"><input type="text" name="cp_name" id="cp_name" placeholder="Enter Name (no special characters)"></td>
                </tr>
                <tr>
                    <td class="c-td">Contact Email</td>
                    <td class="c-td1"><input type="text" class="" name="email" id="email" placeholder="Enter Email"></td>
                </tr>
                <tr>
                    <td class="c-td">Contact Mobile Number</td>
                    <td class="c-td1"><input type="text" name="mobile" id="mobile" placeholder="Enter 10 digit mobile no" maxlength="10"></td>
                </tr>
                <tr>
                    <td class="c-td">Broker Address</td>
                    <td class="c-td1"><textarea name="address" rows="4" id="address" placeholder="Enter Complete Address"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button style="margin-left: 35%; width: 25%;" onclick="return verifyData();">Add &amp Save</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="ac" value="broker">
            <input type="hidden" name="new_broker" value="1">
        </form>
        <?php
        break;

    case 'prop':
        ?>
        <form method="post">
            <table class="c-tab" border="0">
                <tbody>
                <tr>
                    <td class="c-td">Type</td>
                    <td class="c-td1">
                        <select id="p-type" name="p_type" style="width: 90px;">
                            <option value="1">Apartment</option>
                            <option value="2">Villa</option>
                            <option value="3">Plot</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="c-td">Bed</td>
                    <td class="c-td1">
                        <select id="bed" name="bed">
                            <?php for( $__count = 1; $__count <= 10; $__count++ ) {  ?>
                            <option value="<?php echo $__count; ?>"><?php echo $__count; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="c-td">Bath</td>
                    <td class="c-td1">
                        <select id="bath" name="bath">
                            <?php for( $__count = 0; $__count <= 10; $__count++ ) {  ?>
                                <option value="<?php echo $__count; ?>"><?php echo $__count; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="c-td">Store/Study</td>
                    <td class="c-td1">
                        <select id="store" name="store">
                            <?php for( $__count = 0; $__count <= 5; $__count++ ) {  ?>
                                <option value="<?php echo $__count; ?>"><?php echo $__count; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="c-td">Servant</td>
                    <td class="c-td1">
                        <select id="servant" name="servant">
                            <?php for( $__count = 0; $__count <= 5; $__count++ ) {  ?>
                                <option value="<?php echo $__count; ?>"><?php echo $__count; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="c-td">Size</td>
                    <td class="c-td1">
                        <table>
                            <tr>
                                <td class="c-td">
                                    <input type="text" name="size" id="size" placeholder="Enter Size (number)">
                                </td>
                                <td class="c-td1">
                                    <select name="size_unit">
                                        <option value="sq ft">sq ft</option>
                                        <option value="sq yd">sq yd</option>
                                        <option value="sq m">sq m</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button style="margin-left: 25%; width: 25%;" onclick="return verifyPropData();">Add &amp Save</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="ac" value="prop">
            <input type="hidden" name="new_prop" value="<?php echo $_REQUEST['id']; ?>">
        </form>
        <?php
        break;

    case 'tow':
        ?>
        <form method="post">
            <table class="c-tab" border="0">
                <tbody>
                <tr>
                    <td class="c-td">Tower Name</td>
                    <td class="c-td1">
                        <input type="text" name="t_name" id="t-name" placeholder="Enter Tower Name">
                    </td>
                </tr>
                <tr>
                    <td class="c-td">No. of Flats</td>
                    <td class="c-td1">
                        <input type="text" name="no_flat" id="no-flat" placeholder="Enter no. of Flats">
                    </td>
                </tr>
                <tr>
                    <td class="c-td">No. of Floors</td>
                    <td class="c-td1">
                        <input type="text" name="no_floor" id="no-floor" placeholder="Enter no. of Floors">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button style="margin-left: 25%; width: 25%;" onclick="return verifyTowData();">Add &amp Save</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="ac" value="tow">
            <input type="hidden" name="new_tow" value="<?php echo $_REQUEST['id']; ?>">
        </form>
        <?php
        break;
    default:
        # default code
}

?>
    </div>
    </body>
</html>