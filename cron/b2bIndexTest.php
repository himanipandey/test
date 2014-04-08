<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function runTests(){
    return verifySupply() && verifyLaunched() && verifyPhases() && verifyNonZeroColumns(); //&& verifyNegativeUnitsSold() && verifyPlotBedrooms();
}

// verifying if supply and launched unit in d_inventory_price table is correct
function verifySupply(){
    $count = DInventoryPriceTmp::find_by_sql("select count(*) as count from project_supplies a inner join listings b on a.listing_id = b.id inner join resi_project_options c on b.option_id = c.OPTIONS_ID and c.OPTION_CATEGORY = 'Logical' inner join resi_project_phase d on b.phase_id = d.PHASE_ID and d.version = 'Website' left join d_inventory_prices_tmp e on d.PHASE_ID = e.phase_id and c.BEDROOMS = e.bedrooms and c.OPTION_TYPE = e.unit_type where a.version = 'Website' and (a.supply != e.ltd_supply or a.launched != e.ltd_launched_unit);");
    return $count[0]->count == 0;
}

// verifying if launched unit in d_inventory_price table is correct
function verifyLaunched(){
    $count = DInventoryPriceTmp::find_by_sql("select count(*) as count from project_supplies a inner join listings b on a.listing_id = b.id inner join resi_project_options c on b.option_id = c.OPTIONS_ID and c.OPTION_CATEGORY = 'Logical' inner join resi_project_phase d on b.phase_id = d.PHASE_ID and d.version = 'Website' left join d_inventory_prices_tmp e on d.PHASE_ID = e.phase_id and c.BEDROOMS = e.bedrooms and c.OPTION_TYPE = e.unit_type where a.version = 'Website' and a.launched != e.launched_unit;");
    return $count[0]->count == 0;
}

// verifying if both, actual and logical, phases are in d_inv_prices table
function verifyPhases(){
    $count = DInventoryPriceTmp::find_by_sql("select count(distinct PHASE_TYPE), PROJECT_ID from d_inventory_prices group by PROJECT_ID having count(distinct PHASE_TYPE)>1");
    return empty($count);
}

// checking certain columns for zero values
function verifyNonZeroColumns(){
    $count = DInventoryPriceTmp::count(array('conditions' => 'average_price_per_unit_area = 0'));
    return ($count==0);
}

// checking for negative units sold
function verifyNegativeUnitsSold(){
    $count = DInventoryPriceTmp::count(array('conditions' => 'units_sold < 0'));
    return ($count==0);
}

// verifies if plots have non zero bedrooms
function verifyPlotBedrooms(){
    $count = DInventoryPriceTmp::count(array('conditions' => "unit_type = 'Plot' and bedrooms != 0"));
    return ($count==0);
}
