<?php
	$projectId			=	$_REQUEST['projectId'];
	$projectDetail		=	ResiProject::virtual_find($projectId);
    $projectDetail     =   $projectDetail->to_array();
    foreach($projectDetail as $key=>$value){
        $projectDetail[strtoupper($key)] = $value;
        unset($projectDetail[$key]);
    }
    $projectDetail     =   array($projectDetail);
    
    $offerDetails = ProjectOffers::find('all',array('conditions'=>array('project_id'=>$projectId)));
       
    
    if(isset($_POST['btnSave'])){
		
		//saving offers into database
		$offer_type = $_POST['offerType'];
		$offer_desc = $_POST['offerDesc'];
		$offer_period = '';
		$offer_price = '';
		$discount_on = '';
		
		if($offer_type == 'No Pre-EMI'){
			if($_POST['no_emi_period'] == 'months')
				$offer_period = $_POST['no_emi_Months'] . " months"; 
			else
				$offer_period = 'till possession';
			
			if($_POST['no_emi_price'] == 'percent')
				$offer_price = $_POST['no_emi_price_emiPer'] ."%"; 
			elseif($_POST['no_emi_price'] == 'deci')
				$offer_price = $_POST['no_emi_price_emiDeci'] ." ".$_POST['no_emi_price_emiUnit']; 		
						
		}else if($offer_type == 'Part Now Part Later'){
			if($_POST['part_emi_period'] == 'months')
				$offer_period = $_POST['part_emiMonths'] . " months"; 
			else
				$offer_period = 'possession';
			
			if($_POST['part_emi_price'] == 'percent')
				$offer_price = $_POST['part_emi_price_emiPer'] ."%"; 
			else
				$offer_price = $_POST['part_emi_price_emiDeci'] ." ".$_POST['[part_emi_price_emiUnit']; 	
			
		}else if($offer_type == 'No Additional Charges (PLC/Amenities)'){
			
			$str = array();
			if(isset($_POST['nac_plc']))
				$str[] = $_POST['nac_plc'];
			if(isset($_POST['nac_parking']))
				$str[] = $_POST['nac_parking'];
			if(isset($_POST['nac_clubMembership']))
				$str[] = $_POST['nac_clubMembership'];
			if(isset($_POST['nac_gymMembership']))
				$str[] = $_POST['nac_gymMembership'];
			if(isset($_POST['nac_other']))
				$str[] = "@Other@".$_POST['nac_other_txt'];
				
			$discount_on = implode(",",$str);
			
		}else if($offer_type == 'Price Discount'){
			
			if($_POST['pd_price'] == 'percent')
				$offer_price = $_POST['pd_price_emiPer'] ."%"; 
			else
				$offer_price = $_POST['pd_price_emiDeci'] ." ".$_POST['[pd_price_emiUnit']; 	
			
			if(isset($_POST['pd_date']))
				$offer_period = $_POST['pd_date'];
			
			if(isset($_POST['pd_on'])){
				$discount_on = ($_POST['pd_on'] == 'Other')?"@Other@".$_POST['pd_other_txt']:$_POST['pd_on'];
			}
			
		}		
		
		$is_project_offer = ProjectOffers::find("all",array("conditions"=>array("offer"=>$offer_type)));
		
		if(!$is_project_offer){
			
			if(isset($_GET['v']))
				$project_offers = ProjectOffers::find($_GET['v']);
			else
				$project_offers = new ProjectOffers();
			$project_offers->project_id = $projectId;
			$project_offers->offer = $offer_type;
			$project_offers->discount_on = $discount_on;
			$project_offers->offer_period = $offer_period;
			$project_offers->offer_price = $offer_price;
			$project_offers->offer_desc = $offer_desc;
			$project_offers->updated_by = $_SESSION['adminId'];
			$project_offers->save();
			
			header("Location:project_offers.php?projectId=".$projectId."&edit=add&preview=true");
		}else{
			 $ErrorMsg["offerType"] = "Offer Type already exist.";
			 $smarty->assign("ErrorMsg", $ErrorMsg);
		}
	}elseif(isset($_POST['btnExit'])){
		if(isset($_GET['v']))
		  header("Location:project_offers.php?projectId=".$projectId."&edit=add&preview=true");
		else
		  header("Location:show_project_details.php?projectId=" . $projectId);
	}
    
    //offer deletion
    if($_GET['edit'] == 'delete' && isset($_GET['v'])){
		$project_offers = ProjectOffers::find($_GET['v']);
		$project_offers->delete();
		header("Location:project_offers.php?projectId=".$projectId."&edit=add&preview=true");
	}
	
	//edit offers
	if($_GET['edit'] == 'edit' && isset($_GET['v'])){
		$project_offers = ProjectOffers::find($_GET['v']);
				
		//fetching Integer value of Offer Period & Offer Price
		if(strstr($project_offers->offer_period,"months")){
			$periodInt = explode("months",$project_offers->offer_period);
			$smarty->assign("periodInt",$periodInt[0]);
		}
		if(strstr($project_offers->offer_price,"%")){
			$pricePer = explode("%",$project_offers->offer_price);
			$smarty->assign("pricePer",$pricePer[0]);
		}if(strstr($project_offers->offer_price," ")){
			$priceDeci = explode(" ",$project_offers->offer_price);
			$smarty->assign("priceDeci",$priceDeci[0]);
			$smarty->assign("priceDeciUnit",$priceDeci[1]);
		}
		//fetching Discount ON
		if(strstr($project_offers->discount_on,'@Other@')){
			$discount_on_other = explode('@Other@',$project_offers->discount_on);
			$smarty->assign("discount_on_txt",$discount_on_other[1]);
			//print $discount_on_other[1];
		}
		$discount_on = explode(',',$project_offers->discount_on);
		$smarty->assign("discountOn",$discount_on);
		
		$smarty->assign("currOffer", $project_offers->offer);
		$smarty->assign("offerPeriod",$project_offers->offer_period);
		$smarty->assign("offerPrice",$project_offers->offer_price);
		$smarty->assign("currOfferDesc", $project_offers->offer_desc);
		$smarty->assign("offerId", $_GET['v']);
	}
    
	$smarty->assign("arrOfferTypes", $arrOfferTypes);
	$smarty->assign("projectDetail", $projectDetail);
	$smarty->assign("offerDetails", $offerDetails);
	$smarty->assign("projectId", $projectId);
    $smarty->assign("projecteror", $projecteror);

?>
