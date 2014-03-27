<?php
	$projectId			=	$_REQUEST['projectId'];
	$projectDetail		=	ResiProject::virtual_find($projectId);
    $projectDetail     =   $projectDetail->to_array();
    foreach($projectDetail as $key=>$value){
        $projectDetail[strtoupper($key)] = $value;
        unset($projectDetail[$key]);
    }
    $projectDetail     =   array($projectDetail);

    $offerDetails = ProjectOffers::find('all',array('conditions'=>array('project_id'=>$projectId,'status'=>'Active')));

    $price_unit = array(
		'Lakhs' => '100000',
		'Crores' => '10000000',
		'Thousands' => '1000',
		'Hundreds' => '100'
    );
    
    if(isset($_POST['btnSave'])){
		
		//print "<pre>".print_r($_POST,1)."</pre>"; die;
		//saving offers into database
		$offer_type = $_POST['offerType'];
		$offer_desc = $_POST['offerDesc'];
		$offer_period = '';
		$offer_price = '';
		$offer_price_type = '';
		$discount_on = '';
		$other_text = '';
		$discount_date = '';
		$instalment = '';
		
		
		if($offer_type == 'NoPreEmi'){
			$offer_type = 'NoPreEmi';
			if($_POST['no_emi_period'] == 'months')
				$offer_period = $_POST['no_emi_Months']."months";
			elseif($_POST['no_emi_period'] == 'pos')
				$offer_period = 'possession';
			
			if($_POST['no_emi_price'] == 'percent'){
				$offer_price = $_POST['no_emi_price_emiPer'];
				$offer_price_type = 'Percent';
			}elseif($_POST['no_emi_price'] == 'deci'){
				$offer_price = $_POST['no_emi_price_emiDeci'] * $price_unit[$_POST['no_emi_price_emiUnit']]; 	
				$offer_price_type = 'Absolute';	
			}
						
		}else if($offer_type == 'PartEmi'){
			$count=1;
			while($count<=$_REQUEST['plp_noi']){
			  if($_POST['plp_period_'.$count] == 'months')
				$offer_period[$count] = $_POST['plp_Months_'.$count]."months";
			  else
				$offer_period[$count] = 'possession';
				
			  if($_POST['plp_price_'.$count] == 'percent'){
				$offer_price[$count] = $_POST['plp_Per_'.$count] ;
				$offer_price_type[$count] = 'Percent'; 
			  }else{
				$offer_price[$count] = $_POST['plp_Deci_'.$count] * $price_unit[$_POST['plp_Unit_'.$count]]; 
				$offer_price_type[$count] = 'Absolute';		
			  }
			  $count++;
			}			
		}else if($offer_type == 'NoCharges'){
			$count = 1;
			while($count<= count($_POST['nac_discount_on'])){
			  $discount_on[$count] = $_POST['nac_discount_on'][$count-1];
			  if($_POST['nac_discount_on'][$count-1] == 'Other')
				$other_text = $_POST['nac_other_txt'];
			  $count++;
			}		
		}else if($offer_type == 'PriceDiscount'){
			if($_POST['pd_price'] == 'percent'){
				$offer_price = $_POST['pd_price_emiPer'];
				$offer_price_type = 'Percent'; 
			}else{
				$offer_price = $_POST['pd_price_emiDeci']* $price_unit[$_POST['pd_price_emiUnit']];
				$offer_price_type = 'Absolute';	
			}
			
			if(isset($_POST['pd_date']))
				$discount_date = $_POST['pd_date'];
			
			if(isset($_POST['pd_on'])){
				$discount_on = $_POST['pd_on'];
				if($discount_on == 'Other')
					$other_text  =  $_POST['pd_other_txt'];
			}
			
		}		
			
		if(isset($_GET['v'])){
			$project_offers = ProjectOffers::find($_GET['v']);
			ProjectOffersDetails::delete_all(array('conditions'=>array('offer_id' => $_GET['v']))); //massive delete for curret offer
		}
		else
			$project_offers = new ProjectOffers();

		$offer_desc = preg_replace('/[^a-zA-Z0-9 ^{}<>?,:;|\'"!+=@#$%&*_%\[().\]\\/-]/s', '', $offer_desc); //special chars handeling
				
		$project_offers->project_id = $projectId;
		$project_offers->offer = $offer_type;
		$project_offers->offer_desc = ($offer_desc)?$offer_desc:null;
		$project_offers->updated_by = $_SESSION['adminId'];
		$project_offers->save();
	
		if($project_offers->id && $offer_type != 'Other'){
			if($offer_type == 'PartEmi' || $offer_type == 'NoCharges'){ //multiple rows
				$count=1;
				$limit = ($_REQUEST['plp_noi'])?$_REQUEST['plp_noi']:count($discount_on);				
				while($count<=$limit){
					$project_offers_details = new ProjectOffersDetails(); 
					$project_offers_details->offer_id = $project_offers->id;
					$project_offers_details->discount_on = ($discount_on[$count])?$discount_on[$count]:null;
					$project_offers_details->other_text = ($discount_on[$count]=='Other')?$other_text:null;					
					$project_offers_details->offer_period = ($offer_period[$count])?$offer_period[$count]:null;
					$project_offers_details->offer_price = ($offer_price[$count])?$offer_price[$count]:null;					
					$project_offers_details->offer_price_type = ($offer_price_type[$count])?$offer_price_type[$count]:null;					
					$project_offers_details->discount_date = ($discount_date)?$discount_date:null;					
					$project_offers_details->instalment = ($_REQUEST['plp_noi'])?$count:null;					
					$project_offers_details->updated_by = $_SESSION['adminId'];
					$project_offers_details->save();					
					$count++;
				}
																
			}else{		
				$project_offers_details = new ProjectOffersDetails();
				$project_offers_details->offer_id = $project_offers->id;
				$project_offers_details->discount_on = ($discount_on)?$discount_on:null;
				$project_offers_details->offer_period = ($offer_period)?$offer_period:null;
				$project_offers_details->offer_price = ($offer_price>0)?$offer_price:null;
				$project_offers_details->offer_price_type = ($offer_price_type)?$offer_price_type:null;
				$project_offers_details->other_text = ($other_text)?$other_text:null;
				$project_offers_details->discount_date = ($discount_date)?$discount_date:null;
				$project_offers_details->instalment = ($instalment)?$instalment:null;
				$project_offers_details->updated_by = $_SESSION['adminId'];
				$project_offers_details->save();
			}
		}

		header("Location:project_offers.php?projectId=".$projectId."&edit=add&preview=true");
		
	}elseif(isset($_POST['btnExit'])){
		if(isset($_GET['v']))
		  header("Location:project_offers.php?projectId=".$projectId."&edit=add&preview=true");
		else
		  header("Location:show_project_details.php?projectId=" . $projectId);
	}
    
    //offer deletion
    if($_GET['edit'] == 'delete' && isset($_GET['v'])){
		$project_offers = ProjectOffers::find($_GET['v']);
		$project_offers->status = 'Inactive';
		$project_offers->save();
		header("Location:project_offers.php?projectId=".$projectId."&edit=add&preview=true");
	}
	
	$priceDeciUnit = 'Lakhs';
	$smarty->assign("priceDeciUnit", $priceDeciUnit);
	 
	//edit offers
	if($_GET['edit'] == 'edit' && isset($_GET['v'])){
		$project_offers = ProjectOffers::find($_GET['v']);
		$project_offers_details = ProjectOffersDetails::find('all',array('conditions'=>array('offer_id'=>$_GET['v'])));
	
		$smarty->assign("currOffer", $project_offers->offer);
		$smarty->assign("offer_desc", $project_offers->offer_desc);
		$smarty->assign("offerId", $_GET['v']);	
		
		$count=0;
		while($count<count($project_offers_details)){
			$discount[$count] = $project_offers_details[$count]->discount_on; 
			$other_text = ($project_offers_details[$count]->other_text)?$project_offers_details[$count]->other_text:"";
			
			$offer_period = explode("months",$project_offers_details[0]->offer_period);
			
			$plp_arr['offer_period'][$count+1]= $offer_period[0];
			$plp_arr['offer_price_type'][$count+1]= $project_offers_details[$count]->offer_price_type;
			$plp_arr['offer_price'][$count+1]= $project_offers_details[$count]->offer_price;
			//offer price unit
			$offer_price = $project_offers_details[$count]->offer_price;$priceDeciUnit='';
			if($project_offers_details[$count]->offer_price_type == 'Absolute'){
				if($offer_price >=10000000){
					$plp_arr['priceDeciUnit'][$count+1] = 'Crores';
					$plp_arr['offer_price'][$count+1] = $offer_price/10000000;
				}elseif($offer_price < 10000000 && $offer_price > 99999){
					$plp_arr['priceDeciUnit'][$count+1] = 'Lakhs';
					$plp_arr['offer_price'][$count+1] = $offer_price/100000;
				}elseif($offer_price < 100000 && $offer_price > 999){
					$plp_arr['priceDeciUnit'][$count+1]  = 'Thousands';	
					$plp_arr['offer_price'][$count+1]  = $offer_price/1000;
				}
				else{
					$plp_arr['priceDeciUnit'][$count+1] = 'Hundreds';	
					$plp_arr['offer_price'][$count+1] = $offer_price/100;
				}		
			}
			$count++;
		}				
			
		//offer price unit
		$offer_price = $project_offers_details[0]->offer_price;$priceDeciUnit='';
		if($project_offers_details[0]->offer_price_type == 'Absolute'){
			if($offer_price >=10000000){
				$priceDeciUnit = 'Crores';
				$offer_price = $offer_price/10000000;
			}elseif($offer_price < 10000000 && $offer_price > 99999){
				$priceDeciUnit = 'Lakhs';
				$offer_price = $offer_price/100000;
			}elseif($offer_price < 100000 && $offer_price > 999){
				$priceDeciUnit = 'Thousands';	
				$offer_price = $offer_price/1000;
			}
			else{
				$priceDeciUnit = 'Hundreds';	
				$offer_price = $offer_price/100;
			}		
		}
		
		$offer_period = explode("months",$project_offers_details[0]->offer_period);
						
		$smarty->assign("plp_arr",$plp_arr);
		$smarty->assign("noi",count($project_offers_details));
		$smarty->assign("discount_on", $discount);
		$smarty->assign("offer_period", $offer_period[0]);
		$smarty->assign("offer_price_type", $project_offers_details[0]->offer_price_type);
		$smarty->assign("other_text", $other_text);
		$smarty->assign("discount_date", date("d-m-Y",strtotime(trim(substr($project_offers_details[0]->discount_date,0,11)))));		
		$smarty->assign("offer_price", $offer_price);
		$smarty->assign("priceDeciUnit", $priceDeciUnit);
		
	}
	   
	$smarty->assign("arrOfferTypes", $arrOfferTypes);
	$smarty->assign("projectDetail", $projectDetail);
	$smarty->assign("offerDetails", $offerDetails);
	$smarty->assign("projectId", $projectId);
    $smarty->assign("projecteror", $projecteror);

?>
