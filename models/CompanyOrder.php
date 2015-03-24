<?php

// Model integration for Company list
class CompanyOrder extends ActiveRecord\Model
{
    static $table_name = 'company_orders';   
    
    static function getAllOrders($compId = 0){
		
	  if($compId > 0){
        $all_orders = self::find('all',array('joins'=>'inner join company on company_orders.company_id = company.id and company.status = "Active" left join  broker_contacts on company_orders.company_id = broker_contacts.broker_id','select'=>'company.name,broker_contacts.name as contact_person,company_orders.*','conditions'=>array('company_orders.company_id=?',$compId)));
      }else{
		 $all_orders = self::find('all',array('joins'=>'inner join company on company_orders.company_id = company.id and company.status = "Active" left join  broker_contacts on company_orders.company_id = broker_contacts.broker_id','select'=>'company.name,broker_contacts.name as contact_person,company_orders.*')); 
	  }	
	  
	  $return_arr = array();
	  foreach($all_orders as $v){
		$res = array();  
		$res['order_id'] = $v->id;
                $res['order_name'] = $v->order_name;
	    $res['company_id'] = $v->company_id;
	    $res['name'] = $v->name;
	    $res['contact_person'] = $v->contact_person;
	    $res['sales_persion_id'] = $v->sales_persion_id;
	    $res['order_type'] = $v->order_type;
	    $res['order_date'] = date_format($v->order_date, 'Y-m-d');
	    $res['order_amount'] = $v->order_amount;
	    $res['order_expiry_date'] = date_format($v->order_expiry_date, 'Y-m-d');
	    $res['trial_duration'] = $v->trial_duration;
	    
	    array_push($return_arr,$res);   
	  }	  
	  return $return_arr;
	}
	
	static function getOrderDetails($orderId){
		$order_details = self::find('all',array('joins'=>'inner join company on company_orders.company_id = company.id and company.status = "Active" left join broker_contacts on company_orders.company_id = broker_contacts.broker_id
		left join company_order_payments on company_orders.id = company_order_payments.company_order_id
		left join proptiger.company_subscriptions on  company_orders.subscription_id = proptiger.company_subscriptions.id
		left join proptiger.subscription_sections  on proptiger.company_subscriptions.id = proptiger.subscription_sections.subscription_id
		left join proptiger.subscription_columns   on proptiger.company_subscriptions.id = proptiger.subscription_columns.subscription_id
		left join proptiger.subscription_permissions   on proptiger.company_subscriptions.id = proptiger.subscription_permissions.subscription_id
		left join proptiger.permissions  on proptiger.subscription_permissions.permission_id = proptiger.permissions.id
		left join proptiger.user_subscription_mappings  on proptiger.company_subscriptions.id = proptiger.user_subscription_mappings.subscription_id
        left join proptiger.FORUM_USER  on proptiger.user_subscription_mappings.user_id =  proptiger.FORUM_USER.user_id
		left join proptiger_admin on company_orders.sales_persion_id = proptiger_admin.adminid
		','select'=>'proptiger_admin.fname,proptiger_admin.adminid,proptiger.permissions.object_type_id,proptiger.permissions.object_id,proptiger.subscription_columns.column_group,proptiger.subscription_sections.section,proptiger.company_subscriptions.id as subscription_id, proptiger.company_subscriptions.status as subscription_status,company.name as company_name,broker_contacts.name as contact_person,proptiger.user_subscription_mappings.user_id, FORUM_USER.email, company_orders.company_id,company_orders.order_type,company_orders.order_date,company_orders.trial_duration,company_orders.order_expiry_date,company_orders.order_amount,company_orders.order_name, company_order_payments.payment_method,company_order_payments.id as payment_id,company_order_payments.payment_instrument_no,company_order_payments.payment_amount,company_order_payments.payment_date','conditions'=>array('company_orders.id=?',$orderId)));
		
		//print "<pre>".print_r($order_details,1)."</pre>";//	die;	
		$order_all_details = array();
		$pmtNo = 0;
		$userNo = 0;
		$order_permission_details = array();
		$payment_details_str = array();
		$order_user_details = array();		
		
		$order_all_details['order_id'] = $orderId;
                $order_all_details['order_name'] = $order_details[0]->order_name;
		$order_all_details['client_id'] = $order_details[0]->company_id;
		$order_all_details['company_name'] = $order_details[0]->company_name;
		$order_all_details['sales_person_id'] = $order_details[0]->adminid;
		$order_all_details['order_date'] = date_format($order_details[0]->order_date, 'Y-m-d');
		$order_all_details['order_type'] = $order_details[0]->order_type;
		$order_all_details['order_duration'] = $order_details[0]->trial_duration;
		$order_all_details['order_expiry_date'] = date_format($order_details[0]->order_expiry_date, 'Y-m-d');
		$order_all_details['order_amount'] = $order_details[0]->order_amount;
		$order_all_details['subscription_id'] = $order_details[0]->subscription_id;
		$order_all_details['subscription_status'] = $order_details[0]->subscription_status;
		
		foreach($order_details as $order){
		  $pmt_str = $order->payment_id."-".$order->payment_method."-".$order->payment_instrument_no."-".$order->payment_amount."-".$order->payment_date;
		  if($order->payment_method && !in_array($pmt_str,$payment_details_str)){
			$order_payment_details = array();
			$order_payment_details['payment_id'] = $order->payment_id;
		    $order_payment_details['payment_method'] = $order->payment_method;
		    $order_payment_details['payment_instrument_no'] = $order->payment_instrument_no;
		    $order_payment_details['payment_amount'] = $order->payment_amount;
		    $order_payment_details['payment_date'] = $order->payment_date;
		    $pmtNo++;
		    $order_all_details['payment_details'][] = $order_payment_details;
		    $payment_details_str[] = implode("-",$order_payment_details);
	      }	
	      if($order->section && !in_array($order->section,$order_all_details['sections'])){
			$order_all_details['sections'][] = $order->section;  
		  }
		  if($order->column_group && !in_array($order->column_group,$order_all_details['data_access'])){
			$order_all_details['data_access'][] = $order->column_group;  
		  }
		  if($order->object_type_id && !in_array($order->object_id,$order_all_details['gAccess_ids'])){
			$order_all_details['gAccess'] = ($order->object_type_id==6)?'gAccess_cities':'gAccess_locs'; 
			if($order->object_type_id == 4){
			  $object_details = Locality::find("all",array("conditions"=>array('locality_id=? and status=?',$order->object_id,'Active'),"select"=>"label"));
			  $order_all_details['gAccess_ids'][$order->object_id] = $object_details[0]->label;  
			}elseif($order->object_type_id == 7){
			  $object_details = Suburb::getSuburbById($order->object_id);
			  $order_all_details['gAccess_ids'][$order->object_id] = $object_details[0]->label;  
			}else{
			  $object_details = City::getCityById($order->object_id);	
			  $order_all_details['gAccess_ids'][$order->object_id] = $object_details[0]->label;  
			}
		  }
		  if($order->email && !in_array($order->email,$order_all_details['user_emails'])){
		    $order_all_details['user_emails'][] = $order->email;  
		    $userNo++;
		  }
		  if($order->user_id && !in_array($order->user_id,$order_all_details['user_ids'])){
		    $order_all_details['user_ids'][] = $order->user_id;  
		  }	
		    			
		}
		if($pmtNo == 0)
		  $pmtNo = 1;
		if($userNo == 0)
		  $userNo = 1;
		$order_all_details['pmtNo'] = $pmtNo;
		$order_all_details['userNo'] = $userNo;
		
		//print "<pre>order details : ".print_r($order_all_details,1)."</pre>";
		
		return $order_all_details;		
		
    }
}
