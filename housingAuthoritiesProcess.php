<?php
	 /*****************City Data************/
	$CityDataArr = City::CityArr();
	$smarty->assign("CityDataArr", $CityDataArr);
	 $smarty->assign('dirname',$dirName);

	$cityId = $_REQUEST['citydd'];
    $smarty->assign('cityId',$cityId);
    
    $smarty->assign("sort",$_GET['sort']);
    $smarty->assign("page",$_GET['page']);
    if(isset($_GET['page'])) {
        $Page = $_GET['page'];
    } else {
        $Page = 1;
    }
    $RowsPerPage = '30';
    $PageNum = 1;
    if(isset($_GET['page'])) {
        $PageNum = $_GET['page'];
    }
     $name = $_REQUEST['authorityName'];
    if($_POST['search'] !='' && ($name != '')){   
        $Offset = 0;

    }else{
        $Offset = ($PageNum - 1) * $RowsPerPage;
    }
     $brokerDataArr = array();
     $AND = '';
    if($name != null){
        $conditionsTownships = "authority_name like '$name%'";
        $AND = ' AND ';
    }
    else
        $conditionsTownships = '';
    if(!empty($cityId)){
        $conditionsCity = $AND."city_id = '$cityId'";
    }
    else{
        $conditionsCity = '';
    }

    $join = " inner join city c on c.CITY_ID=housing_authorities.city_id LEFT JOIN proptiger_admin pa on housing_authorities.updated_by = pa.adminid";
    $townshipsDetail = HousingAuthorities::find('all',
           array('joins' => $join,'conditions'=>array($conditionsTownships.$conditionsCity),'order' => 'authority_name asc',
                'limit' => "$RowsPerPage","offset" => "$Offset","select" => "housing_authorities.*, pa.fname, c.LABEL as city_name"));
    $townshipsDetailAll = HousingAuthorities::find('all',
           array('joins' => $join,'conditions'=>array($conditionsTownships.$conditionsCity),'order' => 'authority_name asc',
                "select" => "housing_authorities.*, pa.fname"));
    $NumRows 	  = count($townshipsDetailAll);
    $townshipsArr = array();
    foreach ($townshipsDetail as $value){	
            array_push($townshipsArr, $value);
    }
    $link ='';
    if($name != '')	{				
            $link .="&authorityName=".$name."";
    }		
    if($_GET['search']!=''){
            $link.="&search=".$_GET['search']."";
    }
    if($name != '')	{				
            $link.="&authorityName=".$name."";	
    }
    if(isset($_POST['search']) && $_POST['search']== 'Search'){
            $link.="&search=".$_POST['search']."";
    }
    $MaxPage = (ceil($NumRows/$RowsPerPage))?ceil($NumRows/$RowsPerPage):'1' ;
    $Num = $_GET['num'];
    $Sort = $_GET['sort'];
    if ($PageNum > 1) {
            $Page = $PageNum - 1;
            $Prev = " <a href=\"$Self?page=$Page&sort=$Sort$link\">[Prev]</a> ";
            $First = " <a href=\"$Self?page=1&sort=$Sort$link\">[First Page]</a> ";
    } else {
            $Prev  = ' [Prev] ';
            $First = ' [First Page] ';
    }
    if ($PageNum < $MaxPage) {
            $Page = $PageNum + 1;
            $Next = " <a href=\"$Self?page=$Page&sort=$Sort$link\">[Next]</a> ";
            $Last = " <a href=\"$Self?page=$MaxPage&sort=$Sort$link\">[Last Page]</a> ";
    } else {
            $Next = ' [Next] ';
            $Last = ' [Last Page] ';
    }
    $Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";
    $smarty->assign("Pagginnation", $Pagginnation);
    $smarty->assign("Pagginnation", $Pagginnation);
    $smarty->assign("Sorting", $Sorting);
    $smarty->assign("NumRows",$NumRows);
    $smarty->assign("authorityName",$name);
    $smarty->assign("authorityArr", $townshipsArr);
    
?>
