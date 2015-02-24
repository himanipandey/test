<?php   

$authorityId = $_REQUEST['authorityId'];
$smarty->assign("authorityId", $authorityId);
$ErrMsg = array();
print_r($_REQUEST);
 /*****************City Data************/
 $CityDataArr = City::CityArr();
 $smarty->assign("CityDataArr", $CityDataArr);

if(isset($_POST['btnExit'])){
        header("Location:housingAuthorities.php?page=1&sort=all");
}
if (isset($_POST['btnSave'])) {

    $authorityName = trim($_REQUEST['authorityName']);
    $smarty->assign("authorityName", $authorityName);
    $city= trim($_REQUEST['city']);
    $smarty->assign("city", $city);
    
        
//  Server Side Validation
    if( $authorityName == '')  {
        $ErrorMsg["authorityName"] = "Please enter Authority name.";
    }
    if( $city == '')  {
        $ErrorMsg["city"] = "Please select City";
    }
    echo $authorityName;
    if(!preg_match('/^[a-zA-z0-9 ]+$/', $authorityName)){
        $ErrorMsg["authorityName"] = "Special characters are not allowed";
    }
    $getAuthority = HousingAuthorities::getAuthoritiesByName($authorityName);
    
    if(count($getAuthority) > 0 && $authorityId != $getAuthority[0]->id)
    {
        $ErrorMsg["authorityName"] = "This Authority Already exists";
    }
           
    $smarty->assign("ErrorMsg", $ErrorMsg);
    
    if(count($ErrorMsg) == 0) {
        if ($authorityId != '')
            $townshipsInsert = HousingAuthorities::find($authorityId);
        else
            $townshipsInsert = new HousingAuthorities();
  
        $townshipsInsert->authority_name = $authorityName;
        $townshipsInsert->city_id = $city;
        $townshipsInsert->updated_by = $_SESSION['adminId'];                
        if( $townshipsInsert->save() )
           header("Location:housingAuthorities.php?page=1&sort=all");
    }     
}
elseif($authorityId!=''){
    $townShipsDetail = HousingAuthorities::getAuthoritiesById($authorityId);
    $townshipsName = $townShipsDetail[0]->authority_name;
    $townshipsId = $townShipsDetail[0]->id;
    $city = $townShipsDetail[0]->city_id;
    $smarty->assign("authorityName", $townshipsName);
    $smarty->assign("authorityId", $townshipsId);
    $smarty->assign("city", $city);
}

 
?>
