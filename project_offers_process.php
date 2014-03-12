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
		
		//saving offers into database
		$offer_type = $_POST['offerType'];
		$offer_desc = $_POST['offerDesc'];
		$offer_period = '';
		$offer_price = '';
		$offer_price_type = '';
		$discount_on = '';
		$other_text = '';
		
		if($offer_type == 'NoPreEmi'){
			$offer_type = 'NoPreEmi';
			if($_POST['no_emi_period'] == 'months')
				$offer_period = $_POST['no_emi_Months'];
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
			if($_POST['part_emi_period'] == 'months')
				$offer_period = $_POST['part_emiMonths'];
			else
				$offer_period = 'possession';
			
			if($_POST['part_emi_price'] == 'percent'){
				$offer_price = $_POST['part_emi_price_emiPer'] ;
				$offer_price_type = 'Percent'; 
			}else{
				$offer_price = $_POST['part_emi_price_emiDeci'] * $price_unit[$_POST['part_emi_price_emiUnit']]; 
				$offer_price_type = 'Absolute';		
			}
			
		}else if($offer_type == 'NoCharges'){
			$discount_on = $_POST['nac_discount_on'];
			if($discount_on == 'Other')
				$other_text = $_POST['nac_other_txt'];
				
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
		
		if(isset($_GET['v']))
			$project_offers = ProjectOffers::find($_GET['v']);
		else
			$project_offers = new ProjectOffers();
		$project_offers->project_id = $projectId;
		$project_offers->offer = $offer_type;
		$project_offers->discount_on = ($discount_on)?$discount_on:null;
		$project_offers->offer_period = ($offer_period)?$offer_period:null;
		$project_offers->offer_price = ($offer_price>0)?$offer_price:null;
		$project_offers->offer_price_type = ($offer_price_type)?$offer_price_type:null;
		$project_offers->other_text = ($other_text)?$other_text:null;
		$project_offers->discount_date = ($discount_date)?$discount_date:null;
		$project_offers->offer_desc = ($offer_desc)?$offer_desc:null;
		$project_offers->updated_by = $_SESSION['adminId'];
		$project_offers->save();
		
		//print "<pre>".print_r($project_offers,1)."</pre>"; die;
		
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
				
		$smarty->assign("currOffer", $project_offers->offer);
		$smarty->assign("discount_on", $project_offers->discount_on);
		$smarty->assign("offer_period", $project_offers->offer_period);
		$smarty->assign("offer_price_type", $project_offers->offer_price_type);
		$smarty->assign("other_text", $project_offers->other_text);
		$smarty->assign("discount_date", $project_offers->discount_date);
		$smarty->assign("offer_desc", $project_offers->offer_desc);
		
		//offer price unit
		$offer_price = $project_offers->offer_price;$priceDeciUnit='';
		if($project_offers->offer_price_type == 'Absolute'){
			if($project_offers->offer_price >=10000000){
				$priceDeciUnit = 'Crores';
				$offer_price = $offer_price/10000000;
			}elseif($project_offers->offer_price < 10000000 && $project_offers->offer_price > 99999){
				$priceDeciUnit = 'Lakhs';
				$offer_price = $offer_price/100000;
			}elseif($project_offers->offer_price < 100000 && $project_offers->offer_price > 999){
				$priceDeciUnit = 'Thousands';	
				$offer_price = $offer_price/1000;
			}
			else{
				$priceDeciUnit = 'Hundreds';	
				$offer_price = $offer_price/100;
			}		
		}
		$smarty->assign("offer_price", $offer_price);
		$smarty->assign("priceDeciUnit", $priceDeciUnit);
		$smarty->assign("offer_desc", $project_offers->offer_desc);
		$smarty->assign("offerId", $_GET['v']);
	}
	
   
	$smarty->assign("arrOfferTypes", $arrOfferTypes);
	$smarty->assign("projectDetail", $projectDetail);
	$smarty->assign("offerDetails", $offerDetails);
	$smarty->assign("projectId", $projectId);
    $smarty->assign("projecteror", $projecteror);

?>
