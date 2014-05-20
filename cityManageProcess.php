<?php

    $accessCity = '';
    if( $cityAuth == false )
       $accessCity = "No Access";
    $smarty->assign("accessCity",$accessCity);
        
    $smarty->assign("sort",$_GET['sort']);
            $smarty->assign("page",$_GET['page']);
    if ($_GET['mode'] == 'delete') 
    {
        DeleteCity($_GET['cityid']);
    }
    /***********************************************************/
    /**
     * *********************************
     *  Get Sort And Page From  the URL
     * *********************************
     **/
    if(isset($_GET['page'])) {
        $Page = $_GET['page'];
    } else {
        $Page = 1;
    }
    $RowsPerPage = DEF_PAGE_SIZE;
    $PageNum = 1;
    if(isset($_GET['page'])) {
        $PageNum = $_GET['page'];
    }
    $Offset = ($PageNum - 1) * $RowsPerPage;
    /**
     * *************************
     *  Create Sort For Pagging
     * *************************
     **/

    $Self = $_SERVER['PHP_SELF'];
    $NumberOnly = '[0-9]';
    $Sorting .= "<a href=\"$Self?page=1&sort=1\">" . $NumberOnly . "</a>";
    $LetterLinks = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    for ($i = 0; $i < count($LetterLinks); $i++) {
        $Sorting .= "&nbsp;";
        $Sorting .= "<a href=\"$Self?page=1&sort=".$LetterLinks[$i]."\">".$LetterLinks[$i]."</a>";
    }
    $Sorting .= "&nbsp;&nbsp;&nbsp;";
    $Sorting .= "<a href=\"$Self?page=1&sort=all\">View All</a>";
    /**
     * *******************
     *  Query For Pagging
     * *******************
     **/
     $cityDataArr = array();
    if($_REQUEST['cityUrl']!=''){
        $QueryMember = "Select * FROM ".CITY." WHERE URL = '".$_REQUEST['cityUrl']."' ORDER BY CITY_ID DESC";
    }
    else if ($_GET['sort'] == "1") {
        $QueryMember = "Select * FROM ".CITY." WHERE LABEL BETWEEN '0' AND '9'  ORDER BY CITY_ID DESC";
    } else if ($_GET['sort'] == "all") {
        $QueryMember = "Select * FROM ".CITY." WHERE 1  ORDER BY CITY_ID DESC";
    } else {
        $QueryMember = "Select * FROM ".CITY." WHERE  left(LABEL,1)='".$_GET['sort']."' ORDER BY CITY_ID DESC";
    }

    $QueryExecute = mysql_query($QueryMember) or die(mysql_error());
    $NumRows = mysql_num_rows($QueryExecute);
    $smarty->assign("NumRows",$NumRows);

    /**
     **********************************
     *  Create Next and Previous Button
     * *********************************
     **/
    $PagingQuery = "LIMIT $Offset, $RowsPerPage";
    $QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;

    while ($dataArr2 = mysql_fetch_array($QueryExecute_1))
                     {
                            array_push($cityDataArr, $dataArr2);

                     }

                    $smarty->assign("cityDataArr", $cityDataArr);
    $MaxPage = (ceil($NumRows/$RowsPerPage))?ceil($NumRows/$RowsPerPage):'1' ;
    $Num = $_GET['num'];
    $Sort = $_GET['sort'];
    if ($PageNum > 1) {
        $Page = $PageNum - 1;
        $Prev = " <a href=\"$Self?page=$Page&sort=$Sort\">[Prev]</a> ";
        $First = " <a href=\"$Self?page=1&sort=$Sort\">[First Page]</a> ";
    } else {
        $Prev  = ' [Prev] ';
        $First = ' [First Page] ';
    }
    if ($PageNum < $MaxPage) {
        $Page = $PageNum + 1;
        $Next = " <a href=\"$Self?page=$Page&sort=$Sort\">[Next]</a> ";
        $Last = " <a href=\"$Self?page=$MaxPage&sort=$Sort\">[Last Page]</a> ";
    } else {
        $Next = ' [Next] ';
        $Last = ' [Last Page] ';
    }
    $Pagginnation = "<DIV align=\"left\"><font style=\"font-size:11px; color:#000000;\">" . $First . $Prev . " Showing page <strong>$PageNum</strong> of <strong>$MaxPage</strong> pages " . $Next . $Last . "</font></DIV>";

    $smarty->assign("Pagginnation", $Pagginnation);
    $smarty->assign("Sorting", $Sorting);
    $smarty->assign("cityUrl", $_REQUEST['cityUrl']);
   $statusArray = array("0"=>"Inactive","1"=>"Active"); 
   $smarty->assign("statusArray", $statusArray);


?>