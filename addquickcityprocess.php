<?php
    $accessCity = '';
    if( $cityAuth == false )
       $accessCity = "No Access";
    $smarty->assign("accessCity",$accessCity);
    $CityDataArr = City::CityArr();
    $smarty->assign("CityDataArr", $CityDataArr);
   /***************Query for suburb selected************/
if($_POST['cityId'] != '')
{
    $suburbSelect = Array();
    $sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM SUBURB AS A WHERE A.CITY_ID = " . $_POST['cityId'] . " ";

    $data = mysql_query($sql, $db);

    while ($dataArr = mysql_fetch_array($data))
    {
           array_push($suburbSelect, $dataArr);
    }
    $smarty->assign("suburbSelect", $suburbSelect);
    
    /***************end Query for suburb selected************/
    /***************Query for Locality selected************/

    $localitySelect = Array();
    $sql = "SELECT A.LOCALITY_ID, A.SUBURB_ID, A.CITY_ID, A.LABEL FROM LOCALITY AS A WHERE A.CITY_ID = " . $_POST['cityId'];

    if ($suburbId != null) {
    $sql .= " AND A.SUBURB_ID = " . $suburbId;
    }


    $data = mysql_query($sql, $db);

    while ($dataArr = mysql_fetch_array($data))
    {
           array_push($localitySelect, $dataArr);
    }	
}	 
$smarty->assign("localitySelect", $localitySelect);
/***************end Query for Locality selected************/
?>
