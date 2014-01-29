
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swapnil
 * Date: 13/6/13
 * Time: 4:09 PM
 * To change this template use File | Settings | File Templates.
 */

set_time_limit(0);
error_reporting(1);
ini_set('display_errors','1');
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
$inquiryId = $_REQUEST['in'];
if ( !$inquiryId ) {
    header("Location: supplyentry.php");
    exit;
}

if ( $_REQUEST['add_info'] == 1 && $_REQUEST['in'] > 0 ) {
    /*
    echo "<pre>";
    print_r( $_REQUEST );
    print_r( $_FILES );
    echo "</pre>";
    //*/
    $param = array();
    $furnishType = array( "", "Fully Furnished", "Semi Furnished", "Unfurnished" );
    $park2 = array( "", "Covered", "Uncovered" );
    $park4 = array( "", "Covered", "Uncovered", "Common" );
    $ownership = array( "", "Freehold", "Leasehold", "POA", "Co-operative Society" );
    $param['FURNISHED_TYPE'] = $furnishType[ $_REQUEST['furnish'] ];
    $param['PARKING_2'] = $park2[ $_REQUEST['park2'] ];
    $param['PARKING_4'] = $park4[ $_REQUEST['park4'] ];
    $param['OWNERSHIP'] = $ownership[ $_REQUEST['ownership'] ];
    $insert = false;
    foreach( $param as $col => $val ) {
        if ( $val != "" ) {
            $insert = true;
        }
        else {
            unset( $param[ $col ] );
        }
    }
    if ( $insert ) {
        $supObj = new Supply( $db_crm );
        $updated = $supObj->AddAdditionalDetails( $_REQUEST['in'], $param );
        if ( $updated > 0 ) {
            //  updated rows
        }
        else {
            $errMsg = "Failed to add info !";
        }
    }
    $upload = 1;
    if ( $upload ) {
        $upload_dir = dirname(__FILE__) . "/static/images/project_images";
        $imgArr = array('in_img', 'ex_img');
        foreach( $imgArr as $thisImg ) {
            foreach( $_FILES[$thisImg]['error'] as $key => $error ) {
                if ( $error == UPLOAD_ERR_OK ) {
                    $tmp_name = $_FILES[$thisImg]['tmp_name'][$key];
                    $finalName = "";
                    $locName = $_FILES[$thisImg]["name"][$key];
                    if ( $thisImg == "in_img" ) {
                        $finalName = "proj_".$_REQUEST['in']."_internal_".$locName;
                    }
                    elseif ( $thisImg == "ex_img" ) {
                        $finalName = "proj_".$_REQUEST['in']."_external_".$locName;
                    }
                    if ( $finalName != "" ) {
                        move_uploaded_file( $tmp_name, "$upload_dir/$finalName" );
                    }
                }
            }
        }
    }
    if ( !$errMsg ) {
        print_r("-3-");
        header("Location: desktop.php");
        exit;
    }
}

if($_REQUEST && $_REQUEST['action']=='update') {
    $listing=$supObj->getListingByID($_REQUEST['in']);
    $furnish=$listing[0]['FURNISHED_TYPE'];
    $parking_2=$listing[0]['PARKING_2'];
    $parking_4=$listing[0]['PARKING_4'];
    $ownership=$listing[0]['OWNERSHIP'];
}

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

?>
<link href="/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/bootstrap/js/bootstrap.js"></script>

<div class="main-container">
    <div class="form-container">
        <ul class="tabset">
            <li><a onclick="return false;" href="">Basic Details</a></li>
            <li class="active"><a onclick="return false;" href="">Additional Details</a></li>
        </ul>
        <br />
        <br />
        <div class="main-form">
            <?php if ( !$errMsg ) {
                echo $errMsg;
            } ?>
            <form name="additional_info" method="POST" enctype="multipart/form-data">
                <table border="0" class="main-form-table">
                    <tbody>
                    <tr class="trow">
                        <td class="t-input-name">
                            Furnished Type
                        </td>
                        <td class="t-input-value">
                            <select name="furnish" class="t-input c-select">
                                <option class="furnish" value="">Select Furnished Type</option>
                                <option class="furnish" value="1">Fully Furnished</option>
                                <option class="furnish" value="2">Semi Furnished</option>
                                <option class="furnish" value="3">Unfurnished</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            2 Wheeler Parking
                        </td>
                        <td class="t-input-value">
                            <select name="park2" class="t-input c-select">
                                <option class="park2" value="">Select Parking Type</option>
                                <option class="park2" value="1">Covered</option>
                                <option class="park2" value="2">Uncovered</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            4 Wheeler Parking
                        </td>
                        <td class="t-input-value">
                            <select name="park4" class="t-input c-select">
                                <option class="park4" value="">Select Parking Type</option>
                                <option class="park4" value="1">Covered</option>
                                <option class="park4" value="2">Uncovered</option>
                                <option class="park4" value="3">Common</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Ownership Type
                        </td>
                        <td class="t-input-value">
                            <select name="ownership" class="t-input c-select">
                                <option class="ownership" value="">Select Ownership Type</option>
                                <option class="ownership" value="1">Freehold</option>
                                <option class="ownership" value="2">Leasehold</option>
                                <option class="ownership" value="3">POA</option>
                                <option class="ownership" value="4">Co-operative Society</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Upload Internal Images
                        </td>
                        <td class="t-input-value">
                            <input type="file" class="f-input" name="in_img[]" id="in-img" multiple>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td class="t-input-name">
                            Upload External Images
                        </td>
                        <td class="t-input-value">
                            <input type="file" class="f-input" name="ex_img[]" id="ex-img" multiple>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <input type="hidden" name="in" value="<?php echo $inquiryId; ?>">
                <input type="hidden" name="add_info" value="1">
                <input type="hidden" name="action" value="updater">
                <button class="frm-btn" onclick="return validateImg();">Save</button>
                <a href="resale_display.php"><button class="frm-btn" type="button" value="input">Skip</button></a>
            </form>
        </div>
    </div>
</div>
<?php
include('footer.php');
?>

<script type="text/javascript">
function selector(){
    $(".furnish").each(function () {
        if ($(this).html() == "<?php echo $furnish ?>") {
            $(this).attr("selected","selected");
            return;
        }
    });
    $(".park2").each(function () {
        if ($(this).html() == "<?php echo $parking_2 ?>") {
            $(this).attr("selected","selected");
            return;
        }
    });
    $(".park4").each(function () {
        if ($(this).html() == "<?php echo $parking_4 ?>") {
            $(this).attr("selected","selected");
            return;
        }
    });
    $(".ownership").each(function () {
        if ($(this).html() == "<?php echo $ownership ?>") {
            $(this).attr("selected","selected");
            return;
        }
    });
}
window.onload = selector;
</script>