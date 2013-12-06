<?php   

$townshipsId = $_REQUEST['townshipsId'];
$smarty->assign("townshipsId", $townshipsId);
$ErrMsg = array();

if(isset($_POST['btnExit'])){
        header("Location:townships.php?page=1&sort=all");
}
if (isset($_POST['btnSave'])) {

    $townshipsName = trim($_REQUEST['townshipsName']);
    $smarty->assign("townshipsName", $townshipsName);
    
//  Server Side Validation
    if( $townshipsName == '')  {
        $ErrorMsg["townshipsName"] = "Please enter TownShips name.";
    }
    if(!preg_match('/^[a-zA-z0-9 ]+$/', $townshipsName)){
        $ErrorMsg["townshipsName"] = "Special characters are not allowed";
    }
    $getTownships = Townships::getTownshipByName($townshipsName);
    
    if(count($getTownships) > 0)
    {
        $ErrorMsg["townshipsName"] = "This township Already exists";
    }
    
    
    $smarty->assign("ErrorMsg", $ErrorMsg);
    if(count($ErrorMsg) == 0) {
        if ($townshipsId != '')
            $townshipsInsert = Townships::find($townshipsId);
        else
            $townshipsInsert = new Townships();
  
        $townshipsInsert->township_name = $townshipsName;
        $townshipsInsert->updated_by = $_SESSION['adminId'];                
        if( $townshipsInsert->save() )
           header("Location:townships.php?page=1&sort=all");
    }     
}
elseif($townshipsId!=''){
    $townShipsDetail = Townships::getTownShipsById($townshipsId);
    $townshipsName = $townShipsDetail[0]->township_name;
    $townshipsId = $townShipsDetail[0]->id;
    $smarty->assign("townshipsName", $townshipsName);
    $smarty->assign("townshipsId", $townshipsId);
}

 
?>
