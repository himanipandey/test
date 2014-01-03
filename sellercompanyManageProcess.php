<?php

/**
 * @author AKhan
 * @copyright 2013
 */
 
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

    if($_POST['search']!='' && ($_POST['seller']!='')){   
        $Offset = 0;

    }else{
        $Offset = ($PageNum - 1) * $RowsPerPage;
    }
     $sellerDataArr = array();
    
    $conditions = array(" b_c.type = 'Agent'");
    
    
    if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%'";
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%'";
    }
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND agents.rating = '".$_REQUEST['agent_rating']."'";
    }
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND agents.active_since LIKE '%".$date."%'";
    }
    
    
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%'";
    }
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."'";
    }
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND agents.active_since LIKE '%".$date."%'";
    }
    
    
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."'";
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
     else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.active_since LIKE '%".$date."%'";
    }
    
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND agents.rating = '".$_REQUEST['agent_rating']."' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."'";
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND agents.active_since LIKE '%".$date."%'";
    }
    
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && empty($_REQUEST['active_since'])){
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%'";
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    
    else if(empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    
    
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(!empty($_REQUEST['broker']) && empty($_REQUEST['agent']) && !empty($_REQUEST['agent_rating']) && empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND agents.rating = '".$_REQUEST['agent_rating']."' AND agents.active_since LIKE '%".$date."%'";
    }
     else if(!empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND brokers.broker_name LIKE '%".trim($_REQUEST['broker'])."%' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    else if(empty($_REQUEST['broker']) && !empty($_REQUEST['agent']) && empty($_REQUEST['agent_rating']) && !empty($_REQUEST['agent_quali']) && !empty($_REQUEST['active_since'])){
        $date = explode("/" , $_REQUEST['active_since']);
        $date = $date[2]."-".$date[1]."-".$date[0];
        $conditions = " b_c.type = 'Agent' AND b_c.name LIKE '%".trim($_REQUEST['agent'])."%' AND aq.id LIKE '%".$_REQUEST['agent_quali']."%' AND agents.active_since LIKE '%".$date."%'";
    }
    
    
    
    
    
    $sellerCompany = '';
    
    $options = array();
    $NumRows = '';
    $join = " LEFT JOIN brokers
        ON agents.broker_id = brokers.id 
        LEFT JOIN broker_contacts AS b_c
        ON agents.id = b_c.broker_id
        LEFT JOIN academic_qualifications AS aq
        ON agents.academic_qualification_id = aq.id
        ";
    
    if(!empty($RowsPerPage) && !empty($Offset))
    {
        $options = array('joins' => $join , 'select' => 
        'agents.*,brokers.broker_name,b_c.name,aq.qualification' , 'conditions' => $conditions);
        
        $sellerCompany = SellerCompany::find('all' , $options);
        
        $NumRows = count($sellerCompany);
        
        $options = array('joins' => $join ,'select' => 
        'agents.*,brokers.broker_name,b_c.name,aq.qualification' , 'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => $conditions);
        $sellerCompany = SellerCompany::find('all' , $options);
        
    }
    else
    { 
        $options = array('joins' => $join , 'select' => 
        'agents.*,brokers.broker_name,b_c.name,aq.qualification' , 'conditions' => $conditions);
        
        $sellerCompany = SellerCompany::find('all' , $options);
       // echo SellerCompany::connection()->last_query."<br>";
        $NumRows = count($sellerCompany);
        
        $options = array('joins' => $join ,'select' => 
        'agents.*,brokers.broker_name,b_c.name,aq.qualification' , 'limit' => $RowsPerPage, 'conditions' => $conditions);
        $sellerCompany = SellerCompany::find('all' , $options); 
    }
    
    //print'<pre>';
//    print_r($sellerCompany);
//    die;
//    $sellerDataArr = array();
    $i = 0;
    if(!empty($sellerCompany))
    {
        foreach($sellerCompany as $key => $val)
        {
            $sellerDataArr[$i]['id'] = !empty($val->id)?$val->id:'';
            $sellerDataArr[$i]['seller_cmpny'] = !empty($val->broker_name)?((strlen($val->broker_name) > 30)? substr($val->broker_name , 0 , 30)."..." : $val->broker_name ):'';
            $sellerDataArr[$i]['seller_name'] = !empty($val->name)?$val->name:'';
            $sellerDataArr[$i]['seller_type'] = !empty($val->seller_type)?$val->seller_type:'';
            $sellerDataArr[$i]['rating'] = !empty($val->rating)?$val->rating:'0';
            $sellerDataArr[$i]['qualification'] = !empty($val->qualification)?$val->qualification:'None';
            
            $img = json_decode(file_get_contents('http://nightly.proptiger-ws.com:8080/data/v1/entity/image?objectType=sellerCompany&objectId='.$val->id));
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
            
            $active_since = '';
            if(!empty($val->active_since))
            {   
                foreach($val->active_since as $k => $v)
                {
                    if($k == 'date')
                    {
                        $sellerDataArr[$i]['active_since'] = !empty($v)?date('d/m/Y' , strtotime($v)):'';
                        break;
                    }
                }
                       
            }
            else
                $sellerDataArr[$i]['active_since'] = '';
            
            $sellerDataArr[$i]['status'] = $val->status;
            $sellerDataArr[$i]['imageurl'] = $imgurl;
            $sellerDataArr[$i]['imageid'] = $imgid;
            $i++;
            
        }
    }
    
    //print'<pre>';
//    print_r($sellerDataArr);
//    die;
    
    
    $link ='';
    if($_GET['seller'] != '')	{				
            $link .="&seller=".$_GET['seller']."";
    }		
    if($_GET['search']!=''){
            $link.="&search=".$_GET['search']."";
    }
    if($_POST['seller'] != '')	{				
            $link.="&seller=".$_POST['seller']."";	
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
    $smarty->assign("seller",$_REQUEST['seller']);
    $smarty->assign("sellerDataArr", $sellerDataArr);
    
    $smarty->assign("broker", !empty($_REQUEST['broker'])?$_REQUEST['broker']:'');
    $smarty->assign("agent", !empty($_REQUEST['agent'])?$_REQUEST['agent']:'');
    $smarty->assign("agent_rating", !empty($_REQUEST['agent_rating'])?$_REQUEST['agent_rating']:'');
    $smarty->assign("agent_quali", !empty($_REQUEST['agent_quali'])?$_REQUEST['agent_quali']:'');
    $smarty->assign("active_since", !empty($_REQUEST['active_since'])?$_REQUEST['active_since']:'');
    
    
    
?>
