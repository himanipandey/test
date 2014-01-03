<?php

/**
 * @author AKhan
 * @copyright 2013
 */
 //print'<pre>';
// print_r($_REQUEST);
// die;
    $sort = 'all';
    $page = '1';
    if(isset($_GET['sort']) && !empty($_GET['sort']))
        $sort = $_GET['sort'];
    
    if(isset($_GET['page']) && !empty($_GET['page']))
        $page = $_GET['page'];
        
    $smarty->assign("sort",$sort);
    $smarty->assign("page",$page);
            
    if(isset($_GET['page'])) {
        $Page = $_GET['page'];
    } else {
        $Page = 1;
    }
    $RowsPerPage = '10';
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
    
    $conditions = '';
    if(!empty($_REQUEST['broker']) && empty($_REQUEST['pan']) && empty($_REQUEST['active_since'])){
        $conditions = " brokers.broker_name LIKE '%".trim($_REQUEST['active_since'])."%'";
        
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['pan']) && empty($_REQUEST['active_since'])){
        $conditions = array(" brokers.pan LIKE '%".trim($_REQUEST['pan'])."%'");
    }
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['pan']) && !empty($_REQUEST['active_since'])){
        
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        
        $conditions = array(" brokers.active_since LIKE '%".$date."%'");
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['pan']) && empty($_REQUEST['active_since'])){
        $conditions = array(" brokers.broker_name LIKE '%".trim($_REQUEST['active_since'])."%' AND brokers.pan LIKE '%".trim($_REQUEST['pan'])."%'");
    }
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['pan']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = array(" brokers.broker_name LIKE '%".trim($_REQUEST['active_since'])."%' AND brokers.active_since LIKE '%".$date."%'");
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['pan']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = array(" brokers.pan LIKE '%".trim($_REQUEST['pan'])."%' AND brokers.active_since LIKE '%".$date."%'");
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['pan']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = array(" brokers.broker_name LIKE '%".trim($_REQUEST['active_since'])."%' AND brokers.pan LIKE '%".trim($_REQUEST['pan'])."%' AND brokers.active_since LIKE '%".$date."%'");
    }
    
    
    $brokerCompany = '';
    
    $options = array();
    $NumRows = '';
    $join = "";
    
    if(!empty($RowsPerPage) && !empty($Offset))
    {
        $options = array('joins' => $join , 'select' => 
        'brokers.*' , 'conditions' => $conditions);
            
        $brokerCompany = BrokerCompany::find('all' , $options);
        $NumRows = count($brokerCompany);
            
        $options = array('joins' => $join , 'select' => 
        'brokers.*' , 'limit' => $RowsPerPage , 'offset' => $Offset , 'conditions' => $conditions);
        
        $brokerCompany = BrokerCompany::find('all' , $options);
    }
    else
    {
        $options = array('joins' => $join , 'select' => 
        'brokers.*' , 'conditions' => $conditions);
        
        $brokerCompany = BrokerCompany::find('all' , $options);
        $NumRows = count($brokerCompany);
        
        $options = array('joins' => $join , 'select' => 
        'brokers.*' , 'limit' => $RowsPerPage , 'conditions' => $conditions);
        
        $brokerCompany = BrokerCompany::find('all' , $options);
        
        //echo BrokerCompany::connection()->last_query;
    }
    $brokerDataArr = array();
    $i = 0;
    
    //print'<pre>';
//    print_r($brokerCompany);
//    die;
    
    //$img = json_decode(file_get_contents('http://nightly.proptiger-ws.com:8080/data/v1/entity/image?objectType=brokerCompany&objectId=1'));
    //print'<pre>';
    ////$imgurl = '';
//    $imgid = '';
//    if(!empty($img))
//    {
//        foreach($img as $key => $val)
//        {
//            //print_r($val);
//            if($key == "data")
//            {
//                $imgurl = $val[0]->absolutePath;
//                $imgid = $val[0]->id;
//            }
//        }
//        //print_r($img);
//    }
    //echo $imgurl." ".$imgid;
//    die;
    if(!empty($brokerCompany))
    {
        
        foreach($brokerCompany as $key => $val)
        {
            $bcmp = array();
            $bcontact = array();
            
            $brokerDataArr[$i]['id'] = $val->id;
            
            $name = '';
            if(!empty($val->broker_name))
            {
                if(strlen($val->broker_name) > 14)
                    $name = substr($val->broker_name , 0 ,14).'...';
                else
                    $name = $val->broker_name;
            }
            else
            {
                $name = '';
            }
            $brokerDataArr[$i]['name'] = $name;
            $brokerDataArr[$i]['pan'] = $val->pan;
            
            $desc = '';
            if(!empty($val->description))
            {
                if(strlen($val->description) > 14)
                    $desc = substr($val->description , 0 ,14).'...';
                else
                    $desc = $val->description;
            }
            else
            {
                $desc = '';
            }
            $brokerDataArr[$i]['description'] = $desc;
            $brokerDataArr[$i]['status'] = $val->status;
            
            $img = json_decode(file_get_contents('http://nightly.proptiger-ws.com:8080/data/v1/entity/image?objectType=brokerCompany&objectId='.$val->id));
            $imgurl = '';
            $imgid = '';
            
            if(!empty($img))
            {
                foreach($img as $k1 => $v1)
                {
                    if($k1 == "data")
                    {
                        $imgurl = $v1[0]->absolutePath;
                        $imgid = $v1[0]->id;
                    }
                }
            }
            
            $flg = 0;
            
            if(!empty($val->active_since))
            {
                
                foreach($val->active_since as $k => $v)
                {
                    if($k == 'date')
                    {
                        $brokerDataArr[$i]['active_since'] = !empty($v)?date('d/m/Y' , strtotime($v)):'';
                        $flg = 1;
                        break;
                    }
                }
            }
            if($flg == 0)
                $brokerDataArr[$i]['active_since'] = '';
            
            $flg = 0;
            if(!empty($val->created_at))
            {
                foreach($val->created_at as $k => $v)
                {
                    if($k == 'date')
                    {
                        $brokerDataArr[$i]['created_at'] = $v;
                        $flg = 1;
                        break;
                    }
                }
            }
            
            if($flg == 0)
                $brokerDataArr[$i]['created_at'] = '';
            
            $flg = 0;
            if(!empty($val->updated_at))
            {
                foreach($val->updated_at as $k => $v)
                {
                    if($k == 'date')
                    {
                        $brokerDataArr[$i]['updated_at'] = $v;
                        $flg = 1;
                        break;
                    }
                }
            }
            
            if($flg == 0)
                $brokerDataArr[$i]['updated_at'] = '';
                
            
            $brokerDataArr[$i]['updated_by'] = $val->updated_by;
            $brokerDataArr[$i]['imageurl'] = $imgurl;
            $brokerDataArr[$i]['imageid'] = $imgid;
            
            $i++;
            
        }
    }
   
    //print'<pre>';
//    print_r($brokerDataArr);
//    die;
    
    
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
    $smarty->assign("Sorting", $Sorting);
    $smarty->assign("NumRows",$NumRows);
    
    $smarty->assign("brokerDataArr", $brokerDataArr);
    $smarty->assign("accessBroker", $accessBroker);
    $smarty->assign("broker", !empty($_REQUEST['broker'])?$_REQUEST['broker']:'');
    $smarty->assign("pan", !empty($_REQUEST['pan'])?$_REQUEST['pan']:'');
    $smarty->assign("active_since", !empty($_REQUEST['active_since'])?$_REQUEST['active_since']:'');
    
    
?>
