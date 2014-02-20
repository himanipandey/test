<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function runTests(){
    return verifySupply() && verifyLaunched() && verifyPhases() && verifyNonZeroColumns();
}

function verifySupply(){
    $count = DInventoryPriceTmp::find_by_sql("select count(*) as count from project_supplies a inner join listings b on a.listing_id = b.id inner join resi_project_options c on b.option_id = c.OPTIONS_ID and c.OPTION_CATEGORY = 'Logical' inner join resi_project_phase d on b.phase_id = d.PHASE_ID and d.version = 'Website' left join d_inventory_prices_tmp e on d.PHASE_ID = e.phase_id and c.BEDROOMS = e.bedrooms and c.OPTION_TYPE = e.unit_type where a.version = 'Website' and a.supply != e.ltd_supply;");
    return $count[0]->count == 0;
}

function verifyLaunched(){
    $count = DInventoryPriceTmp::find_by_sql("select count(*) as count from project_supplies a inner join listings b on a.listing_id = b.id inner join resi_project_options c on b.option_id = c.OPTIONS_ID and c.OPTION_CATEGORY = 'Logical' inner join resi_project_phase d on b.phase_id = d.PHASE_ID and d.version = 'Website' left join d_inventory_prices_tmp e on d.PHASE_ID = e.phase_id and c.BEDROOMS = e.bedrooms and c.OPTION_TYPE = e.unit_type where a.version = 'Website' and a.launched != e.launched_unit;");
    return $count[0]->count == 0;
}

function verifyPhases(){
    $count = DInventoryPriceTmp::find_by_sql("select count(distinct PHASE_TYPE), PROJECT_ID from d_inventory_prices group by PROJECT_ID having count(distinct PHASE_TYPE)>1");
    return empty($count);
}

function verifyNonZeroColumns(){
    $count = DInventoryPriceTmp::find('all', array('conditions' => 'average_price_per_unit_area = 0'));
    return empty($count);
}
?>
