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
    
    $conditions = array(" b_c.type = 'Agent' AND adr.table_name = 'agents'");
    
    $sellerCompany = '';
    
    $options = array();
    $NumRows = '';
    $join = " LEFT JOIN brokers
        ON agents.broker_id = brokers.id 
        LEFT JOIN broker_contacts AS b_c 
        ON agents.id = b_c.broker_id
        LEFT JOIN addresses AS adr
        ON agents.id = adr.table_id
        LEFT JOIN academic_qualifications AS aq
        ON agents.academic_qualification_id = aq.id
        ";
    
    if(!empty($RowsPerPage) && !empty($Offset))
    {
        $options = array('joins' => $join , 'select' => 
        'agents.*,brokers.name,b_c.name,b_c.contact_email,CONCAT(adr.address_line_1," ",adr.address_line_2) AS address,adr.city_id,adr.pincode,aq.qualification');
                
        $sellerCompany = SellerCompany::find('all' , $options);
        $NumRows = count($sellerCompany);
            
        $options = array('joins' => $join ,'select' => 
        'agents.*,brokers.name,b_c.name,b_c.contact_email,CONCAT(adr.address_line_1," ",adr.address_line_2) AS address,adr.city_id,adr.pincode,aq.qualification' , 'limit' => $RowsPerPage , 'offset' => $Offset);
        $sellerCompany = BrokerCompany::find('all' , $options);
    }
    else
    {
        $options = array('joins' => $join , 'select' => 
        'agents.*,brokers.broker_name,b_c.name,b_c.contact_email,CONCAT(adr.address_line_1," ",adr.address_line_2) AS address,adr.city_id,adr.pincode,aq.qualification' , 'conditions' => $conditions);
        
        $sellerCompany = SellerCompany::find('all' , $options);
        $NumRows = count($sellerCompany);
        
        $options = array('joins' => $join ,'select' => 
        'agents.*,brokers.broker_name,b_c.name,b_c.contact_email,CONCAT(adr.address_line_1," ",adr.address_line_2) AS address,adr.city_id,adr.pincode,aq.qualification' , 'limit' => $RowsPerPage, 'conditions' => $conditions);
        $sellerCompany = SellerCompany::find('all' , $options);
        
        //echo SellerCompany::connection()->last_query;
    }
    
    //print'<pre>';
//    print_r($sellerCompany);
//    die;
    $sellerDataArr = array();
    $i = 0;
    if(!empty($sellerCompany))
    {
        foreach($sellerCompany as $key => $val)
        {
            $sellerDataArr[$i]['id'] = !empty($val->id)?$val->id:'';
            $sellerDataArr[$i]['seller_cmpny'] = !empty($val->broker_name)?$val->broker_name:'';
            $sellerDataArr[$i]['seller_name'] = !empty($val->name)?$val->name:'';
            $sellerDataArr[$i]['seller_type'] = !empty($val->seller_type)?$val->seller_type:'';
            $sellerDataArr[$i]['rating'] = !empty($val->rating)?$val->rating:'0';
            $sellerDataArr[$i]['qualification'] = !empty($val->qualification)?$val->qualification:'None';
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
    
    
    
?>
