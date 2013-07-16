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

    if($_POST['search']!='' && ($_POST['broker']!='')){   
        $Offset = 0;

    }else{
        $Offset = ($PageNum - 1) * $RowsPerPage;
    }
     $brokerDataArr = array();

    if($_REQUEST['broker']!=''){
        $qryFlg = " BROKER_NAME LIKE '".$_REQUEST['broker']."%'";
    }else{
        $qryFlg = "1";
    }
    $QueryMember = "Select * FROM ptigercrm.".BROKER_LIST." WHERE  ".$qryFlg."  ORDER BY BROKER_ID DESC";
    $QueryExecute = mysql_query($QueryMember) or die(mysql_error());
    $NumRows 	  = mysql_num_rows($QueryExecute);
    $PagingQuery = "LIMIT $Offset, $RowsPerPage";
    $QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;
    while ($dataArr2 = mysql_fetch_array($QueryExecute_1)){	
            array_push($brokerDataArr, $dataArr2);
    }
    $link ='';
    if($_GET['broker'] != '')	{				
            $link .="&broker=".$_GET['broker']."";
    }		
    if($_GET['search']!=''){
            $link.="&search=".$_GET['search']."";
    }
    if($_POST['broker'] != '')	{				
            $link.="&broker=".$_POST['broker']."";	
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
    $smarty->assign("broker",$_REQUEST['broker']);
    $smarty->assign("brokerDataArr", $brokerDataArr);
?>