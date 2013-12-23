 <?php

    $accessRedirectUrl = '';
    if( $urlAuth == false )
       $accessRedirectUrl = "No Access";
    $smarty->assign("accessRedirectUrl",$accessRedirectUrl);
  
    $smarty->assign("sort",$_GET['sort']);
    $smarty->assign("page",$_GET['page']);
    if ($_GET['mode'] == 'delete') {
       $qryDeleteUrl = "delete from redirect_url_map where from_url = '".$_REQUEST['deleteUrl']."'";
       $resDeleteUrl = mysql_query($qryDeleteUrl) or die(mysql_error());
    }

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

    if($_REQUEST['search']!='' && ($_REQUEST['url']!='')){   
            $Offset = 0;

    }else{
            $Offset = ($PageNum - 1) * $RowsPerPage;
    }
     $urlDataArr = array();

    if($_REQUEST['url']!=''){
        $qryFlg = "WHERE rum.from_url LIKE '%".$_REQUEST['url']."%' OR rum.to_url like '%".$_REQUEST['url']."%' ";
    }
    else
        $qryFlg =  '';
    $QueryMember = "select rum.*, a.FNAME as SUBMITTED_BY, b.FNAME as MODIFIED_BY
        from redirect_url_map rum join proptiger_admin a on rum.submitted_by = a.adminid
        left join proptiger_admin b on rum.modified_by = b.adminid $qryFlg";
    $QueryExecute = mysql_query($QueryMember) or die(mysql_error());
    $NumRows = mysql_num_rows($QueryExecute);
    $PagingQuery = "LIMIT $Offset, $RowsPerPage";
    $QueryExecute_1 = mysql_query($QueryMember." ".$PagingQuery) ;
    while ($dataArr2 = mysql_fetch_assoc($QueryExecute_1)){	
        array_push($urlDataArr, $dataArr2);
    }
    $link ='';
    if($_GET['url'] != '')	{				
       $link .="&url=".$_GET['url']."";
    }

    if($_GET['search']!=''){
       $link.="&search=".$_GET['search']."";
    }

    if($_POST['url'] != '')	{				
       $link.="&url=".$_REQUEST['url']."";

    }

    if(isset($_REQUEST['search']) && $_REQUEST['search']== 'Search'){
            $link.="&search=".$_REQUEST['search']."";
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
    $smarty->assign("url",$_REQUEST['url']);
    $smarty->assign("urlDataArr", $urlDataArr);
?>                 
                  
