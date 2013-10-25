<?php

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
     $name = $_REQUEST['townshipsName'];
    if($_POST['search'] !='' && ($name != '')){   
        $Offset = 0;

    }else{
        $Offset = ($PageNum - 1) * $RowsPerPage;
    }
     $brokerDataArr = array();
    if($name != null)
        $conditionsTownships = array("township_name like '$name%'");
    else
        $conditionsTownships = '';
    $join = " LEFT JOIN proptiger_admin pa on townships.updated_by = pa.adminid";
    $townshipsDetail = Townships::find('all',
           array('joins' => $join,'conditions'=>$conditionsTownships,'order' => 'township_name asc',
                'limit' => "$RowsPerPage","offset" => "$Offset","select" => "townships.*, pa.fname"));
    $NumRows 	  = count($townshipsDetail);
    $townshipsArr = array();
    foreach ($townshipsDetail as $value){	
            array_push($townshipsArr, $value);
    }
    echo "<pre>";
   // print_r($townshipsArr);
    $link ='';
    if($name != '')	{				
            $link .="&townshipsName=".$name."";
    }		
    if($_GET['search']!=''){
            $link.="&search=".$_GET['search']."";
    }
    if($name != '')	{				
            $link.="&townshipsName=".$name."";	
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
    $smarty->assign("townshipsName",$name);
    $smarty->assign("townshipsArr", $townshipsArr);
?>