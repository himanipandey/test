<?php



print "<pre>".print_r($_REQUEST,1)."</pre>";










/////////// Initial Values
$orderDur = array("1week"=>"1 week","2weeks"=>"2 weeks","3weeks"=>"3 weeks","4weeks"=>"1 weeks");
$smarty->assign("orderDur",$orderDur);
$paymentMhd = array("BankAccountTrnasfer"=>"Bank Account Trnasfer","BankDraft" => "Bank Draft","Other" => "Other");
$smarty->assign("paymentMhd",$paymentMhd);
$paymentNoDetails = 1; //by defualt
$smarty->assign('paymentNoDetails',$paymentNoDetails);
$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);
#$locsArray = Locality::localityList();
#$smarty->assign("locsArray", $locsArray);
$subsUsrDetails = 1; //by defualt
$smarty->assign('subsUsrDetails',$subsUsrDetails);
#populate sales persons
$sales_pers = fetch_sales_persons();
$smarty->assign('sales_pers',$sales_pers);

 
?>
