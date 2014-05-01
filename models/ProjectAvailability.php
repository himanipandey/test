<?php

use ActiveRecord\Model;

class ProjectAvailability extends Model {
    function deleteAvailabilityForPhase($projectId, $phaseId){
        $allAvailability = self::findAvailabilityForPhase($projectId, $phaseId);
        $ids = array();
        foreach ($allAvailability as $value) {
            $ids[] = $value->id;
        }
        if(!empty($ids)) self::table()->delete(array('id'=>$ids));
    }
    
    function findAvailabilityForPhase($projectId, $phaseId){
        $sql = "select ps.project_id, ps.phase_id, ps.no_of_bedroom, ps.project_type, pa.* from " . self::table_name() . " pa INNER JOIN project_supplies ps on (ps.ID = pa.project_supply_id and ps.version = 'Cms') where ps.project_id = '$projectId' and ps.phase_id = '$phaseId'";
        return self::find_by_sql($sql);
    }
    
    function getProjectEditHistoryBeforeDate($projectId, $date){
        $sql = "select max(tpa.id) id from " . ProjectSupply::table_name() . " ps inner join " . self::table_name() . " pa on (ps.id = pa.project_supply_id and ps.version = 'Cms') inner join _t_" . self::table_name() . " tpa on pa.id=tpa.id where ps.project_id = $projectId";
        if(!empty($date))$sql .= " and tpa._t_transaction_date < '$date'";
        $sql .= " group by ps.id";
        $res = self::find_by_sql($sql);
        $ids = array();
        foreach ($res as $v) {
            array_push($ids, $v->id);
        }
        $result = array();
        if(!empty($ids)){
            $sql = "select tpa._t_transaction_id, ps.id, ps.phase_id, ps.no_of_bedroom, ps.supply, pa.availability, rpp.PHASE_NAME from " . ProjectSupply::table_name() . " ps left join " . ResiProjectPhase::table_name() ." rpp on (ps.phase_id = rpp.PHASE_ID and rpp.version = 'Cms') inner join " . self::table_name() . " pa on (ps.id = pa.project_supply_id and ps.version = 'Cms') inner join _t_" . self::table_name() . " tpa on pa.id=tpa.id where tpa.id in (" . implode(',', $ids) . ") group by ps.id";
            $res = self::find_by_sql($sql);
            foreach ($res as $r) {
                $result[] = array(
                    '_t_transaction_id'=>$r->_t_transaction_id,
                    'PROJ_SUPPLY_ID'=>$r->id,
                    'PHASE_ID'=>$r->phase_id,
                    'NO_OF_BEDROOMS'=>$r->no_of_bedroom,
                    'NO_OF_FLATS'=>$r->supply,
                    'AVAILABLE_NO_FLATS'=>$r->availability,
                    'PHASE_NAME'=>$r->phase_name
                );
            }
        }
        return $result;
    }
    
    public static function getAvailability($supplyId){
		
            $sql = "select availability from ".self::table_name()." where  project_supply_id ='".$supplyId."' order by `effective_month` desc limit 1";
            $res = self::find_by_sql($sql);
            if($res)
                    return $res[0]->availability;
            else
                    return 0;
		
	}

    public static function copyProjectInventoryToWebsite($projectId, $updatedBy){
        $result = array();
       
        $supply_ids = ResiProjectPhase::find('all', array('joins'=>'inner join listings l on resi_project_phase.PHASE_ID = l.phase_id inner join project_supplies ps1 on l.id = ps1.listing_id and ps1.version = "Cms" inner join project_supplies ps2 on ps1.listing_id = ps2.listing_id and ps2.version = "Website"', 'conditions'=>array('PROJECT_ID'=>$projectId), 'select'=>'ps1.id cms_supply_id,ps1.is_verified cms_verfied_flag,ps1.supply,ps1.launched, ps2.id website_supply_id'));
         
        
        $all_supply_ids = array(NULL);
        foreach ($supply_ids as $supply_id) {
            $all_supply_ids[] = $supply_id->cms_supply_id;
            $all_supply_ids[] = $supply_id->website_supply_id;
             //copy the supply and lanuched unit in website if flag=>true 
            if($supply_id->cms_verfied_flag == 'true'){
			   	$website_supply = ProjectSupply::find($supply_id->website_supply_id);
				$website_supply->supply = $supply_id->supply;
				$website_supply->launched = $supply_id->launched;
				$website_supply->save();	
			}
        }
        
         //removing orphan prices and inventory
        $orphan_inventory = ProjectAvailability::find("all", array("joins"=>"inner join project_supplies b on project_availabilities.project_supply_id = b.id and b.version = 'Website' inner join project_supplies c on b.listing_id = c.listing_id and c.version = 'Cms' 
         left join project_availabilities d on c.id = d.project_supply_id and d.effective_month = project_availabilities.effective_month
         left join listings lst on lst.id = b.listing_id and lst.id = c.listing_id
         inner join resi_project_phase rpp on rpp.phase_id = lst.phase_id and rpp.version = 'Cms'  and rpp.project_id = '$projectId' and d.id is null"));
		$all_orphan_avails = array();		
		foreach($orphan_inventory as $key=>$value){
		  $all_orphan_avails[] = $value->id;
		}
		$all_orphan_avails = implode(",", $all_orphan_avails);		
		if($all_orphan_avails)
		  ProjectAvailability::delete_all(array('conditions'=>array("id in (".$all_orphan_avails.")")));

		$orphan_prices = ListingPrices::find("all",array("joins"=> "left join listing_prices b on listing_prices.listing_id = b.listing_id and listing_prices.effective_date = b.effective_date and b.version = 'Cms' inner join listings lst on lst.id = listing_prices.listing_id inner join resi_project_phase rpp on rpp.phase_id = lst.phase_id and rpp.version = 'Cms' and rpp.project_id = '$projectId' and b.id is null","conditions"=>array('version'=>'Website')));		
		$all_orphan_prices = array();		
		foreach($orphan_prices as $key=>$value){
		  $all_orphan_prices[] = $value->id;
		}
		 $all_orphan_prices = implode(",", $all_orphan_prices);
		if($all_orphan_prices)
		  ListingPrices::delete_all(array('conditions'=>array("id in (".$all_orphan_prices.")")));	
        
        
        $all_inventory_data = self::find('all', array('project_supply_id'=>$all_supply_ids));
        
        $indexed_inventory_data = array();
        foreach ($all_inventory_data as $inventory_data) {
            $date = substr($inventory_data->effective_month, 0,10);
            $indexed_inventory_data[$inventory_data->project_supply_id][$date] = $inventory_data;
        }
        
        foreach ($supply_ids as $supply_id) {
            if(isset($indexed_inventory_data[$supply_id->cms_supply_id])){
                foreach ($indexed_inventory_data[$supply_id->cms_supply_id] as $month => $cms_month_data){
                    $cms_month_data = $cms_month_data->to_array();
                    unset($cms_month_data['id']);
                    $cms_month_data['project_supply_id'] = $supply_id->website_supply_id;
                    $cms_month_data['updated_by'] = $updatedBy;
                    $cms_month_data['updated_at'] = 'NOW()';
                    if(isset($indexed_inventory_data[$supply_id->website_supply_id][$month])){
                        $website_month_data = $indexed_inventory_data[$supply_id->website_supply_id][$month];
                        $website_month_data->update_attributes($cms_month_data);
                        $result[] = $website_month_data->save();
                    }
                    else{
                        $cms_month_data['created_at'] = 'NOW()';
                        $result[] = self::create($cms_month_data);
                    }
                }
            }
        }
        return $result === array_filter($result);
    }
    
    public static function getInventoryForIndexing($aProjectId){
        $condition = "where rp.version = 'Website' and pa.effective_month between '" . MIN_B2B_DATE . "' and '" . MAX_B2B_DATE . "' and rp.PROJECT_ID in (".  implode(',', $aProjectId).")";
        $sql = "select concat_ws('/', rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS, pa.effective_month) as unique_key, concat_ws('/', rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS) as key_without_month, rp.PROJECT_ID, rpp.PHASE_ID, rpo.OPTION_TYPE as UNIT_TYPE, rpo.BEDROOMS, pa.effective_month, rpp.PHASE_NAME, date_format(if(rpp.COMPLETION_DATE=0, NULL, rpp.COMPLETION_DATE), '%Y-%m-01') COMPLETION_DATE, date_format(" . ResiProjectPhase::$custom_launch_date_string . ", '%Y-%m-01') LAUNCH_DATE, FLOOR(avg(rpo1.SIZE)) as AVERAGE_SIZE, rpp.PHASE_TYPE, if(" . ResiProjectPhase::$custom_launch_date_string . " = pa.effective_month, ps.supply, null) supply, ps.supply ltd_supply, if(" . ResiProjectPhase::$custom_launch_date_string . " = pa.effective_month, ps.launched, null) launched, ps.launched ltd_launched, pa.availability as inventory from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rpp.status = 'Active' inner join listings li on rpp.PHASE_ID = li.phase_id and li.status = 'Active' inner join resi_project_options rpo on rpo.OPTIONS_ID = li.option_id and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on li.id = ps.listing_id and ps.version = 'Website' inner join project_availabilities pa on ps.id = pa.project_supply_id $condition group by pa.id order by rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS, pa.effective_month";
        return self::find_by_sql($sql);
    }
}
