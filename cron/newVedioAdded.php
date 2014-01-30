<?php
/*
 * Send mail new vedios added count also existing vedios count categorywise
 */
$docroot = dirname(__FILE__) . "/../";
require_once $docroot.'dbConfig.php';
require_once $docroot.'includes/send_mail_amazon.php';
$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-1,date("Y")));
$sqlNewAdded = "select count(video_url) as COUNT,category from video_links 
       where 
       created_at = '".$yesterday."%' group by category";
$resNewAdded = mysql_query($sqlNewAdded) or die(mysql_error());
$arrAllData = array();
while($dataNewAdded = mysql_fetch_assoc($resNewAdded)) {
    if($dataNewAdded['category'] == 'Walkthrough')
        $arrAllData[0]['Walkthrough'] = $dataNewAdded['COUNT'];
    if($dataNewAdded['category'] == 'Sample flat')
        $arrAllData[1]['Sample_flat'] = $dataNewAdded['COUNT'];
    if($dataNewAdded['category'] == 'Presentation')
        $arrAllData[2]['Presentation'] = $dataNewAdded['COUNT'];
    
}

$sqlExisting ="select count(video_url) as COUNT,category as categoryExisting from video_links 
       where 
       created_at < '".$yesterday."' group by category";
$resExisting = mysql_query($sqlExisting) or die(mysql_error());

while($dataExisting = mysql_fetch_assoc($resExisting)) {
    if($dataExisting['categoryExisting'] == 'Walkthrough')
        $arrAllData[0]['Walkthrough_existing'] = $dataExisting['COUNT'];
    if($dataExisting['categoryExisting'] == 'Sample flat')
        $arrAllData[1]['Sample_flat_existing'] = $dataExisting['COUNT'];
    if($dataExisting['categoryExisting'] == 'Presentation')
        $arrAllData[2]['Presentation_existing'] = $dataExisting['COUNT'];
}
$message = '<html><table align = "center" width = "500px" style = "border:1px solid;" border = "1"><tr><th colspan = "4" align = "center">New Videos added and total videos</th></tr>';
$message .= '<tr><th align = "center">Category</th><th align = "center">New Videos</th><th align = "center">Existing Videos</th><th align = "center">Total Videos</th></tr>';

$cnt = 0;
$categoryName = array();
$categoryValue = array();
$categoryValueExist = array();
$total = 0;
$totalExist = 0;
foreach($arrAllData as $key=>$value) {
    
    if($cnt == 0){
        $categoryName[$cnt] = 'Walkthrough';
        if(isset($value['Walkthrough'])) {
            $categoryValue[$cnt] = $value['Walkthrough'];
            $total += $value['Walkthrough'];
        }
        else {
             $categoryValue[$cnt] = 0;
             $total += 0;
        }
        if(isset($value['Walkthrough_existing'])) {
            $categoryValueExist[$cnt] = $value['Walkthrough_existing'];
            $totalExist += $value['Walkthrough_existing'];
        }
        else {
             $categoryValueExist[$cnt] = 0;
             $totalExist += 0;
        }
    }
    
    if($cnt == 1){
        $categoryName[$cnt] = 'Sample flat';
        if(isset($value['Sample_flat'])) {
            $categoryValue[$cnt] = $value['Sample_flat'];
            $total += $value['Sample_flat'];
        }
        else {
             $categoryValue[$cnt] = 0;
             $total += 0;
        }
        if(isset($value['Sample_flat_existing'])) {
            $categoryValueExist[$cnt] = $value['Sample_flat_existing'];
            $totalExist += $value['Sample_flat_existing'];
        }
        else {
             $categoryValueExist[$cnt] = 0;
             $totalExist += 0;
        }
    }
    
    if($cnt == 2){
        $categoryName[$cnt] = 'Presentation';
        if(isset($value['Presentation'])) {
            $categoryValue[$cnt] = $value['Presentation'];
            $total += $value['Presentation'];
        }
        else {
             $categoryValue[$cnt] = 0;
             $total += 0;
        }
        if(isset($value['Presentation_existing'])) {
            $categoryValueExist[$cnt] = $value['Presentation_existing'];
            $totalExist += $value['Presentation_existing'];
        }
        else {
             $categoryValueExist[$cnt] = 0;
             $totalExist += 0;
        }
    }
    $cnt++;
    
}

for($i=0;$i<3;$i++){
    $message .= '<tr><td align = "center">'.$categoryName[$i].'</td><td align = "center">'.$categoryValue[$i].'</td><td align = "center">'.$categoryValueExist[$i].'</td><td align = "center">'.($categoryValueExist[$i]+$categoryValue[$i]).'</td></tr>';
}
$message .= '<tr><th align = "center">Grand Total</th><th align = "center">'.$total.'</td><th align = "center">'.$totalExist.'</th><th align = "center">'.($totalExist+$total).'</th></tr>';
$message.= "</table></html>";
$subject = "New videos added yesterday";
$toArr = array("vimlesh.rajput@proptiger.com","manish.goyal@proptiger.com","mohit.dargan@proptiger.com");
$from = 'no-reply@proptiger.com';
foreach($toArr as $Tovalue)
    sendMailFromAmazon($Tovalue,  $subject, $message, $from , $cc=null, $bcc=null, $ajaxCall=true)

?>
