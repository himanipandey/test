<?php

require_once dirname(__FILE__) . '/../cron/cronFunctions.php';

// Model integration for resi_project
use ActiveRecord\Model;

class DInventoryPriceTmp extends Model {

    static $table_name = 'd_inventory_prices_tmp';

    public static function updateFirstPromoisedCompletionDate() {
        self::connection()->query("update " . self::table_name() . " dipt inner join (select rpp.PHASE_ID, substring(substring_index(group_concat(rpec.EXPECTED_COMPLETION_DATE order by EXPECTED_COMPLETION_ID ASC), ',', 1), 1, 10) first_promised_completion_date from resi_project_phase rpp inner join resi_proj_expected_completion rpec on rpp.PHASE_ID = rpec.phase_id group by rpp.PHASE_ID) t on dipt.PHASE_ID = t.PHASE_ID set dipt.first_promised_completion_date = t.first_promised_completion_date");
		self::update_all ( array (
				'set' => 'first_promised_completion_date = completion_date',
				'conditions' => 'first_promised_completion_date is null' 
		) );
		self::update_all ( array (
				'set' => 'first_promised_completion_date = completion_date',
				'conditions' => 'first_promised_completion_date > completion_date' 
		) );
		self::update_all ( array (
				'set' => "completion_delay = period_diff(date_format(completion_date, '%Y%m'), date_format(first_promised_completion_date, '%Y%m'))",
				'conditions' =>  "first_promised_completion_date < effective_month and completion_date >= effective_month"
		) );
    }

    public static function deleteEntriesBeforeLaunch() {
        self::delete_all(array('conditions' => 'effective_month < launch_date and launch_date is not null'));
    }

    public static function deleteInvalidPriceEntries() {
        $sql = "update " . self::table_name() . " a inner join " . self::table_name() . " b on a.phase_id = b.phase_id and a.unit_type = b.unit_type and a.bedrooms = b.bedrooms and a.effective_month = DATE_ADD(b.effective_month, INTERVAL 1 MONTH) set a.average_price_per_unit_area = null, a.average_total_price = null, a.average_price_per_unit_area_quarter = null, a.average_price_per_unit_area_year = null, a.average_price_per_unit_area_financial_year = null where a.inventory = 0 and (b.inventory = 0 or b.inventory is null)";
        self::connection()->query($sql);
        #self::update_all(array('set' => 'average_price_per_unit_area = null, average_total_price = null, average_price_per_unit_area_quarter = null, average_price_per_unit_area_year = null, average_price_per_unit_area_financial_year = null', 'conditions' => 'inventory = 0'));
    }

    public static function populateDemand() {
        self::populateProjectDemand();
        self::populateLocalityDemand();
        self::update_all(array('set' => 'demand = customer_demand + investor_demand'));
    }

    public static function populateProjectDemand() {
        $allLeadSql = "select concat_ws('/', l.LEAD_ID, date_format(min(lp.CREATED_DATE), '%Y-%m')) lead_key, lp.PROJECT_ID, l.PROJECT_TYPE UNIT_TYPE, if(l.PROJECT_TYPE = 'plot', 0, group_concat(distinct l.BEDROOMS)) all_bedrooms, date_format(min(lp.CREATED_DATE), '%Y-%m-01') EFFECTIVE_MONTH, l.CLIENT_TYPE from ptigercrm.LEADS l inner join ptigercrm.LEAD_PROJECTS lp on l.LEAD_ID = lp.LEAD_ID inner join resi_project_ids rpi on rpi.id = lp.PROJECT_ID where l.PROJECT_TYPE is not null and l.PROJECT_TYPE <> '' and l.CLIENT_TYPE is not null and l.CLIENT_TYPE <> '' and lp.CREATED_DATE >= '" . B2B_DEMAND_START_DATE . " 00:00:00' and lp.ACTIVE = '1' group by l.LEAD_ID, lp.PROJECT_ID order by lead_key";
        $aAllLead = self::find_by_sql($allLeadSql);
        $aAllLead = groupOnKey($aAllLead, 'lead_key');

        $aAllBedRoomCount = self::getBedroomCountForAllProjects();
        $aAllPhaseCount = self::getPhaseCountForAllProjectBedrooms();

        foreach ($aAllLead as $aAllProjectLead) {
            $leadProjectCount = count($aAllProjectLead);
            foreach ($aAllProjectLead as $projectLead) {
                $key = implode("/", array($projectLead->project_id, ucfirst($projectLead->unit_type)));

                $bedrooms = isset($aAllBedRoomCount[$key]) ? $aAllBedRoomCount[$key] : NULL;
                if (!empty($bedrooms)) {
                    $bedrooms = $bedrooms->all_bedrooms;
                    $leadBedrooms = array_intersect(explode(",", $projectLead->all_bedrooms), explode(",", $bedrooms));
                    $bedroomCount = count($leadBedrooms);
                    foreach ($leadBedrooms as $bedroom) {
                        $phaseCount = $aAllPhaseCount[implode("/", array($projectLead->project_id, ucfirst($projectLead->unit_type), $bedroom))]->phase_count;
                        if ($projectLead->client_type === 'buyer') {
                            $updateStr = "customer_demand = (customer_demand+(1/($leadProjectCount*$bedroomCount*$phaseCount)))";
                        } elseif ($projectLead->client_type === 'investor') {
                            $updateStr = "investor_demand = (investor_demand+(1/($leadProjectCount*$bedroomCount*$phaseCount)))";
                        }
                        self::update_all(array('set' => $updateStr, 'conditions' => array('project_id' => $projectLead->project_id, 'unit_type' => $projectLead->unit_type, 'bedrooms' => $bedroom, 'effective_month' => $projectLead->effective_month)));
                    }
                }
            }
        }
    }

    public static function populateLocalityDemand() {
        $allLeadSql = "select concat_ws('/', l.LEAD_ID, date_format(min(lp.CREATED_DATE), '%Y-%m')) lead_key, lp.LOCALITY_ID, l.PROJECT_TYPE UNIT_TYPE, if(l.PROJECT_TYPE = 'plot', 0, group_concat(distinct l.BEDROOMS)) all_bedrooms, date_format(min(lp.CREATED_DATE), '%Y-%m-01') EFFECTIVE_MONTH, l.CLIENT_TYPE, lp.PROJECT_ID from ptigercrm.LEADS l inner join ptigercrm.LEAD_PROJECTS lp on l.LEAD_ID = lp.LEAD_ID inner join locality lo on lp.LOCALITY_ID = lo.LOCALITY_ID where l.CLIENT_TYPE is not null and l.CLIENT_TYPE <> '' and lp.CREATED_DATE > '" . B2B_DEMAND_START_DATE . " 00:00:00' and lp.ACTIVE = '1' group by l.LEAD_ID, lp.LOCALITY_ID having count(distinct PROJECT_ID) = 1 and (PROJECT_ID is null or PROJECT_ID = 0) order by lead_key";
        $aAllLead = self::find_by_sql($allLeadSql);
        $aAllLead = groupOnKey($aAllLead, 'lead_key');

        foreach ($aAllLead as $aAllLocalityLead) {
            $leadLocalityCount = count($aAllLocalityLead);
            foreach ($aAllLocalityLead as $localityLead) {
                $conditions = array('locality_id' => $localityLead->locality_id, 'unit_type' => $localityLead->unit_type, 'effective_month' => $localityLead->effective_month, 'bedrooms' => explode(",", $localityLead->all_bedrooms));

                if ($localityLead->client_type === 'buyer') {
                    $demandType = 'customer_demand';
                } elseif ($localityLead->client_type === 'investor') {
                    $demandType = 'investor_demand';
                }

                $entries = self::getDemandWeightBasedOnCond($conditions, $demandType);
                foreach ($entries as $id => $weight) {
                    $updateStr = "$demandType=$demandType+($weight/$leadLocalityCount)";
                    self::update_all(array('set' => $updateStr, 'conditions' => array('id' => $id)));
                }
            }
        }
    }

    public static function getDemandWeightBasedOnCond($aCondition, $demandType = 'demand') {
        $selectStr = "id, " . $demandType . " demand";

        $aData = self::find('all', array('select' => $selectStr, 'conditions' => $aCondition));
        $sum = getSumOfKeyValues($aData, 'demand');

        $result = array();
        if ($sum == 0) {
            foreach ($aData as $data) {
                $count = count($aData);
                $result[$data->id] = 1 / $count;
            }
        } else {
            foreach ($aData as $data) {
                $result[$data->id] = $data->demand / $sum;
            }
        }
        return $result;
    }

    public static function getBedroomCountForAllProjects() {
        $aData = self::find('all', array('select' => "concat_ws('/', project_id, unit_type) unique_key, project_id, unit_type, effective_month, group_concat(distinct bedrooms) all_bedrooms, count(distinct bedrooms) bedroom_count", 'group' => 'project_id, unit_type'));
        return indexArrayOnKey($aData, 'unique_key');
    }

    public static function getPhaseCountForAllProjectBedrooms() {
        $aData = self::find('all', array('select' => "concat_ws('/', project_id, unit_type, bedrooms) unique_key, project_id, unit_type, effective_month, count(distinct phase_id) phase_count", 'group' => 'project_id, unit_type, bedrooms, effective_month'));
        return indexArrayOnKey($aData, 'unique_key');
    }

    public static function updateSecondaryPriceForAllProjects() {
        $sql = "update " . self::table_name() . " dip inner join (select PHASE_ID, UNIT_TYPE, DATE_FORMAT(EFFECTIVE_DATE, '%Y-%m-01') DATE, avg((MIN_PRICE+MAX_PRICE)/2) AVG_PRICE from project_secondary_price group by PROJECT_ID, PHASE_ID, UNIT_TYPE, DATE_FORMAT(EFFECTIVE_DATE, '%Y-%m-01')) t on dip.phase_id = t.phase_id and dip.unit_type = t.UNIT_TYPE and dip.effective_month = t.DATE set dip.average_secondary_price_per_unit_area = t.AVG_PRICE";
        self::connection()->query($sql);
    }

    public static function setLaunchDateMonthSales() {
        $sql = "update " . self::table_name() . " dipt inner join resi_project rp on dipt.project_id = rp.project_id and rp.version = 'Website' inner join resi_project_phase rpp on dipt.phase_id = rpp.phase_id and rpp.version = 'Website' set dipt.units_sold = dipt.ltd_launched_unit - dipt.inventory where (date_format(rp.pre_launch_date, '%Y-%m-01') = dipt.effective_month or (rp.pre_launch_date = 0 and date_format(rpp.launch_date, '%Y-%m-01') = dipt.effective_month) or (rp.pre_launch_date = 0 and rpp.launch_date = 0 and date_format(rp.launch_date, '%Y-%m-01') = dipt.effective_month)) and dipt.inventory is not null";
        self::connection()->query($sql);
    }

    public static function updateSupplyAndLaunched() {
        $sql = "update " . self::table_name() . " a inner join listings d on (a.phase_id = d.phase_id and d.listing_category='Primary') and d.status = 'Active' inner join resi_project_options e on d.option_id = e.options_id and (e.bedrooms = a.bedrooms or (a.bedrooms = 0 and e.bedrooms is null)) and a.unit_type = e.option_type and e.option_category = 'Logical' inner join project_supplies f on d.id = f.listing_id and f.version = 'Website' set a.ltd_supply = f.supply, a.ltd_launched_unit = f.launched";
        self::connection()->query($sql);
        self::update_all(array('set' => 'supply = ltd_supply, launched_unit = ltd_launched_unit', 'conditions' => 'launch_date = effective_month'));
        self::update_all(array('set' => 'launched_unit = 0, supply = 0', 'conditions' => 'effective_month != launch_date or launch_date is null'));
    }

    public static function setUnitdelivered() {
        self::update_all(array('set' => 'units_delivered = 0'));
        self::update_all(array('set' => 'units_delivered = ltd_launched_unit', 'conditions' => 'completion_date = effective_month'));
    }

    public static function updateProjectDominantType() {
        $sql = "update " . self::table_name() . " a inner join (select project_id, substring_index(group_concat(unit_type order by supply desc), ',', 1) unit_type from (select project_id, unit_type, sum(supply) supply from " . self::table_name() . " group by project_id, unit_type having supply > 0) t group by project_id) b  on a.project_id = b.project_id and a.unit_type = b.unit_type set a.is_dominant_project_unit_type = 'True'";
        self::connection()->query($sql);
    }

    public static function updateConstructionStatus() {
        self::update_all(array('set' => "construction_status = 'Completed'", 'conditions' => "construction_status in ('Ready for Possession', 'Occupied')"));
    }

    public static function setInventoryOverhang() {
        $sql = "update " . self::table_name() . " dip inner join (select a.id, a.inventory/avg(b.units_sold) inventory_overhang from " . self::table_name() . " a inner join " . self::table_name() . " b on a.phase_id = b.phase_id and a.unit_type = b.unit_type and a.bedrooms = b.bedrooms and (12*year(a.effective_month) + month(a.effective_month))-(12*year(b.effective_month)+month(b.effective_month)) between 0 and 2 and b.units_sold is not null group by a.id) t on dip.id = t.id set dip.inventory_overhang = t.inventory_overhang;";
        self::connection()->query($sql);
    }
    
    public static function setRateOfSale() {
        $sql = "update " . self::table_name() . " dip inner join (select a.id, avg(b.units_sold) rate_of_sale from " . self::table_name() . " a inner join " . self::table_name() . " b on a.phase_id = b.phase_id and a.unit_type = b.unit_type and a.bedrooms = b.bedrooms and (12*year(a.effective_month) + month(a.effective_month))-(12*year(b.effective_month)+month(b.effective_month)) between 0 and 2 and b.units_sold is not null group by a.id) t on dip.id = t.id set dip.rate_of_sale = t.rate_of_sale;";
        self::connection()->query($sql);
    }

    public static function setPeriodAttributes(){
        $fields = array("inventory", "average_price_per_unit_area", "average_secondary_price_per_unit_area", "ltd_launched_unit", "inventory_overhang", "rate_of_sale");
        foreach ($fields as $field) {
            self::setPeriodAttributeForField($field);
        }
    }
    
    public static function setPeriodAttributeForField($field){
        $quarterSql = "update " . self::table_name() . " dipt inner join (select substring_index(group_concat(id order by effective_month desc), ',', 1) id, substring_index(group_concat($field order by effective_month desc), ',', 1) quarter_value from " . self::table_name() . " where effective_month between '" . MIN_B2B_DATE . "' and '" . MAX_B2B_DATE . " 'group by phase_id, unit_type, bedrooms, quarter) t on dipt.id = t.id set dipt." . $field . "_quarter = t.quarter_value";
        self::connection()->query($quarterSql);

        $yearSql = "update " . self::table_name() . " dipt inner join (select substring_index(group_concat(id order by effective_month desc), ',', 1) id, substring_index(group_concat($field order by effective_month desc), ',', 1) year_value from " . self::table_name() . " where effective_month between '" . MIN_B2B_DATE . "' and '" . MAX_B2B_DATE . " 'group by phase_id, unit_type, bedrooms, year) t on dipt.id = t.id set dipt." . $field . "_year = t.year_value";
        self::connection()->query($yearSql);

        $financialYearSql = "update " . self::table_name() . " dipt inner join (select substring_index(group_concat(id order by effective_month desc), ',', 1) id, substring_index(group_concat($field order by effective_month desc), ',', 1) financial_year_value from " . self::table_name() . " where effective_month between '" . MIN_B2B_DATE . "' and '" . MAX_B2B_DATE . " 'group by phase_id, unit_type, bedrooms, financial_year) t on dipt.id = t.id set dipt." . $field . "_financial_year = t.financial_year_value";
        self::connection()->query($financialYearSql);
    }

    public static function removeZeroSizes(){
        self::update_all(array('set' => 'average_size = null, average_total_price = null', 'conditions' => 'average_size = 0'));
    }

    public static function deleteEntriesWithoutMonth() {
        self::delete_all(array('conditions' => 'effective_month is null'));
    }
}
