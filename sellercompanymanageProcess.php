<?php

/**
 * @author AKhan
 * @copyright 2013
 */
    $sort = '';
    $page = '';
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
    
    $conditions = array('conditions' => '');
    if(!empty($_REQUEST['broker'])){
        $conditions = array(" brokers.name LIKE '%".$_REQUEST['broker']."%' AND table_name = 'brokers'");
    }
    
    $brokerCompany = '';
    
    $options = array();
    $NumRows = '';
    $join = " left join brokers
        on agents.broker_id = brokers.id 
        left join _t_city AS city
        on addresses.city_id = city.city_id";
    
    if(!empty($RowsPerPage) && !empty($Offset))
    {
        if(!empty($_REQUEST['broker']))
            $options = array('joins' => $join , 'select' => 
        'brokers.*,city.label' , 'conditions' => $conditions);
        else
            $options = array('joins' => $join , 'select' => 
        'brokers.*,city.label' , 'conditions' => array("table_name = 'brokers'"));
                
        $brokerCompany = SellerCompany::find('all' , $options);
        $NumRows = count($brokerCompany);
            
        if(!empty($_REQUEST['broker']))
        {
            $options = array('joins' => $join , 'select' => 
        'brokers.*,city.label' , 'limit' => $RowsPerPage , 'offset' => $Offset , 'conditions' => $conditions);   
        }   
        else
        {
            $options = array('joins' => $join ,'select' => 
        'brokers.*,city.label' , 'limit' => $RowsPerPage , 'offset' => $Offset, 'conditions' => array("table_name = 'brokers'"));
        }
        $brokerCompany = BrokerCompany::find('all' , $options);
    }
    else
    {
        if(!empty($_REQUEST['broker']))
            $options = array('joins' => $join , 'select' => 
        'brokers.*,city.label' , 'conditions' => $conditions);
        else
            $options = array('joins' => $join , 'select' => 
        'brokers.*,city.label', 'conditions' => array("table_name = 'brokers'"));
        
        $brokerCompany = BrokerCompany::find('all' , $options);
        $NumRows = count($brokerCompany);
        
        if(!empty($_REQUEST['broker']))
            $options = array('joins' => $join , 'select' => 
        'brokers.*,city.label' , 'limit' => $RowsPerPage , 'conditions' => $conditions);
        else
            $options = array('joins' => $join ,'select' => 
        'brokers.*,city.label' , 'limit' => $RowsPerPage, 'conditions' => array("table_name = 'brokers'") );
        $brokerCompany = BrokerCompany::find('all' , $options);
        
        //echo BrokerCompany::connection()->last_query;
    }
    $brokerDataArr = array();
    $i = 0;
    if(!empty($brokerCompany))
    {
        foreach($brokerCompany as $key => $val)
        {
            
            $bcmp = array();
            $bcontact = array();
            //$bcmpLocation = BrokerCompanyLocation::find('all' , array('conditions' => "table_name = 'brokers' AND table_id =". $val->id));
            $bcmpLocation = BrokerCompanyLocation::CityLocIDArr($val->id);
            
            
            
            if(!empty($bcmpLocation))
            {
                foreach($bcmpLocation as $k => $v)
                    $bcmp[] = $v->locality_id;
            }
            
            $contactDetail = BrokerCompanyContact::ContactArr($val->id);
            print'<pre>';
            //print_r($bcmpLocation);
            print_r($contactDetail);
            die;
            if(!empty($contactDetail))
            {
                foreach($contactDetail as $k => $v)
                    $bcontact[] = $v->id;
            }
            
            $brokerDataArr[$i]['id'] = $val->id;
            
            $name = '';
            if(!empty($val->name))
            {
                if(strlen($val->name) > 14)
                    $name = substr($val->name , 0 ,14).'...';
                else
                    $name = $val->name;
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
            
            $addr = '';
            $addressline1 = '';
            $addressline2 = '';
            $city = '';
            $pincode = '';
            foreach($bcmpLocation as $k => $v)
            {
                $addressline1 = $v->address_line_1;
                $addressline2 = $v->address_line_2;
                $city = $v->city;
                $pincode = $v->pincode;
            }
            
            if(!empty($addressline1) && !empty($addressline2))
            {
                $addr = $addressline1." ".$addressline2;
                if(strlen($addr) > 16)
                    $addr = substr($addr , 0 , 12).'...';
            }
            else if(!empty($addressline1))
            {
                $addr = $addressline1;
                if(strlen($addr) > 16)
                    $addr = substr($addr , 0 , 12).'...';
            }
            else if(!empty($addressline2))
            {
                $addr = $addressline2;
                if(strlen($addr) > 16)
                    $addr = substr($addr , 0 , 12).'...';
                            
            }
                
             
            $brokerDataArr[$i]['addressline1'] = $addr;
            $brokerDataArr[$i]['city_id'] = $city;
            $brokerDataArr[$i]['pincode'] = $pincode;
            $brokerDataArr[$i]['phone1'] = !empty($val->phone1)?$val->phone1:'';
            $brokerDataArr[$i]['phone2'] = !empty($val->phone2)?$val->phone2:'';
            $brokerDataArr[$i]['fax'] = !empty($val->fax)?$val->fax:'';
            
            $eml = '';
            if(!empty($val->email))
            {
                if(strlen($val->email) > 14)
                    $eml = substr($val->email , 0 ,14).'...';
                else
                    $eml = $val->email;
            }
            else
            {
                $eml = '';
            }
            
            $brokerDataArr[$i]['email'] = $eml;
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
            
            $brokerDataArr[$i]['cc_phone'] = $val->cc_phone;
            $brokerDataArr[$i]['cc_mobile'] = $val->cc_mobile;
            $brokerDataArr[$i]['cc_fax'] = $val->cc_fax;
            $brokerDataArr[$i]['cc_email'] = $val->cc_email;
            
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
            $brokerDataArr[$i]['label'] = $val->label;
                        
            $brokerDataArr[$i]['cityloc'] = base64_encode(json_encode($bcmp));
            $brokerDataArr[$i]['contacts'] = base64_encode(json_encode($bcontact));
            
            $i++;
            
        }
    }
    die;
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
    $smarty->assign("broker",$_REQUEST['broker']);
    $smarty->assign("brokerDataArr", $brokerDataArr);
    $smarty->assign("accessBroker", $accessBroker);
    $smarty->assign("accessBroker", $accessBroker);
    
    
?>
